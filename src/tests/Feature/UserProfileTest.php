<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_user_profile_displays_correct_information()
    {
        // ユーザーとプロフィールを作成
        $user = User::factory()->create([
            'name' => 'Test User',
        ]);

        // プロフィール画像を設定
        $profileImagePath = 'profile_image/default.jpg';
        Profile::factory()->create([
            'user_id' => $user->id,
            'profile_image' => $profileImagePath,
        ]);

        // 出品した商品を作成
        $soldItems = Item::factory()->count(2)->create([
            'seller_id' => $user->id,
            'title' => 'Sold Item Title', // 明示的なタイトル設定
        ]);

        // 購入した商品を作成
        $purchasedItems = Item::factory()->count(2)->create([
            'title' => 'Purchased Item Title', // 明示的なタイトル設定
        ]);
        foreach ($purchasedItems as $item) {
            Purchase::factory()->create([
                'item_id' => $item->id,
                'buyer_id' => $user->id,
            ]);
        }

        // 出品した商品のタブを表示するテスト
        $this->actingAs($user);
        $response = $this->get(route('mypage', ['tab' => 'sell']));

        $response->assertStatus(200);
        $response->assertViewIs('profile');

        // 出品した商品のタイトルが表示されていることを確認
        foreach ($soldItems as $item) {
            $response->assertSee($item->title);
        }

        // 購入した商品のタブを表示するテスト
        $response = $this->get(route('mypage', ['tab' => 'buy']));

        $response->assertStatus(200);
        $response->assertViewIs('profile');

        // 購入した商品のタイトルが表示されていることを確認
        foreach ($purchasedItems as $item) {
            $response->assertSee($item->title);
        }

        // プロフィール情報が正しく表示されていることを確認
        $response = $this->get(route('mypage'));
        $response->assertSee('Test User');
        $response->assertSee($profileImagePath);
    }
}
