<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AddressUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_address_update_reflects_in_purchase_screen()
    {
        // ユーザーとプロフィール作成
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        // テスト用商品の作成
        $item = Item::factory()->create();

        // ログイン状態で配送先変更画面にアクセス
        $this->actingAs($user);
        $response = $this->get(route('purchase.form', $item->id));

        // 購入画面の表示確認
        $response->assertStatus(200);
        $response->assertViewIs('purchase');

        // 変更前の住所の記載確認
        $response->assertSee($profile->postal_code);
        $response->assertSee($profile->address);
        $response->assertSee($profile->building);

        // 配送先変更画面にアクセス
        $response = $this->get(route('address.edit', ['item_id' => $item->id]));
        $response->assertStatus(200);
        $response->assertViewIs('delivary');

        // 新しい住所を送信
        $updatedData = [
            'postal_code' => '123-4567',
            'address' => '東京都新宿区新宿1-1-1',
            'building' => 'テストビル101',
            'item_id' => $item->id, // リクエストに商品IDを含める
        ];
        $this->patch(route('address.update'), $updatedData);

        // データベースの更新を確認
        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'postal_code' => $updatedData['postal_code'],
            'address' => $updatedData['address'],
            'building' => $updatedData['building'],
        ]);

        // 購入画面にリダイレクト
        $response = $this->get(route('purchase.form', ['item_id' => $item->id]));
        $response->assertStatus(200);

        // レンダリングされたHTMLを直接確認
        $html = $response->getContent();
        $this->assertStringContainsString('123-4567', $html);
        $this->assertStringContainsString('東京都新宿区新宿1-1-1', $html);
        $this->assertStringContainsString('テストビル101', $html);
    }


    /** @test */
    public function test_updated_address_saves_correctly_in_purchase()
    {
        // ユーザーとプロフィール作成
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        // テスト用商品の作成
        $item = Item::factory()->create();

        // ログイン状態で配送先を更新
        $this->actingAs($user);
        $updatedData = [
            'postal_code' => '123-4567',
            'address' => '東京都新宿区新宿1-1-1',
            'building' => 'テストビル101',
            'item_id' => $item->id,
        ];
        $this->patch(route('address.update'), $updatedData);

        // 購入処理をシミュレート
        Purchase::factory()->create([
            'item_id' => $item->id,
            'buyer_id' => $user->id,
            'delivary_postal_code' => $updatedData['postal_code'],
            'delivary_address' => $updatedData['address'],
            'delivary_building' => $updatedData['building'],
        ]);

        // 購入情報が正しく保存されていることを確認
        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'buyer_id' => $user->id,
            'delivary_postal_code' => $updatedData['postal_code'],
            'delivary_address' => $updatedData['address'],
            'delivary_building' => $updatedData['building'],
        ]);
    }
}
