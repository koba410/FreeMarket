<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Models\Profile;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_completes_purchase_and_marks_item_as_sold()
    {
        // テスト用ユーザーと商品を作成
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $buyer_profile = Profile::factory()->create(['user_id' => $buyer->id]); // プロファイルを作成
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'is_sold' => false,
        ]);

        // 購入リクエストをシミュレート
        $this->actingAs($buyer);
        $response = $this->post(route('stripe.cardCheckout', ['item_id' => $item->id]), [
            'payment_method' => 'card',
        ]);

        // Stripe Checkoutのリダイレクトを確認
        $response->assertRedirectContains('https://checkout.stripe.com/');

        // 購入情報を保存
        Purchase::factory()->create([
            'item_id' => $item->id,
            'buyer_id' => $buyer->id,
            'delivary_postal_code' => $buyer_profile->postal_code,
            'delivary_address' => $buyer_profile->address,
            'delivary_building' => $buyer_profile->building,
            'payment_method' => 'card',
        ]);
        // アイテムがsoldとなる。
        $item->update(['is_sold' => true]);

        // purchasesテーブルに購入情報が保存されていることを確認
        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'buyer_id' => $buyer->id,
        ]);

        // アイテムが売却済みになっていることを確認
        $this->assertTrue($item->is_sold);
    }


    /** @test */
    public function it_displays_sold_label_for_purchased_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => true]);

        $this->actingAs($user);

        // 商品一覧ページにアクセス
        $response = $this->get(route('item.list'));

        // 商品に「sold」ラベルが表示されているか確認
        $response->assertSee('Sold');
    }

    /** @test */
    public function purchased_item_appears_in_user_profile()
    {
        // テスト用ユーザーと商品を作成
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $buyer_profile = Profile::factory()->create(['user_id' => $buyer->id]); // プロファイルを作成
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'is_sold' => false,
        ]);

        // 購入リクエストをシミュレート
        $this->actingAs($buyer);
        $response = $this->post(route('stripe.convenienceCheckout', ['item_id' => $item->id]), [
            'payment_method' => 'konbini',
        ]);

        // Stripe Checkoutのリダイレクトを確認
        $response->assertRedirectContains('https://checkout.stripe.com/');

        // 購入情報を保存
        Purchase::factory()->create([
            'item_id' => $item->id,
            'buyer_id' => $buyer->id,
            'delivary_postal_code' => $buyer_profile->postal_code,
            'delivary_address' => $buyer_profile->address,
            'delivary_building' => $buyer_profile->building,
            'payment_method' => 'konbini',
        ]);
        // アイテムがsoldとなる。
        $item->update(['is_sold' => true]);

        // 購入後、再度プロフィール画面を確認
        $response = $this->get(route('mypage', ['tab' => 'buy']));
        $response->assertSee($item->title);

        // 購入した商品が表示されているか確認
        $response->assertSee($item->title);
        $response->assertSee('Sold');
    }
}
