<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_payment_method_selection_page()
    {
        // ユーザー、プロフィール、商品を作成
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $item = Item::factory()->create();

        // ユーザーとしてログイン
        $this->actingAs($user);

        // 支払い方法選択画面にアクセス
        $response = $this->get(route('purchase.form', ['item_id' => $item->id]));

        // ステータスコード200が返されることを確認
        $response->assertStatus(200);

        // 支払い方法選択の要素が含まれていることを確認
        $response->assertSee('支払い方法');
        $response->assertSee('カード支払い');
        $response->assertSee('コンビニ支払い');

        // 配送先情報が正しく表示されていることを確認
        $response->assertSee($profile->postal_code);
        $response->assertSee($profile->address);
    }

    /** @test */
    public function it_correctly_sets_form_action_based_on_payment_method()
    {
        // ユーザー、プロフィール、商品を作成
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);
        $item = Item::factory()->create();

        // ユーザーとしてログイン
        $this->actingAs($user);

        // 支払い方法「カード」を選択したときのフォームアクションを確認
        $response = $this->post(route('stripe.cardCheckout', ['item_id' => $item->id]), [
            'payment_method' => 'card',
        ]);

        $response->assertRedirectContains('https://checkout.stripe.com/c/pay');

        // 支払い方法「コンビニ」を選択したときのフォームアクションを確認
        $response = $this->post(route('stripe.convenienceCheckout', ['item_id' => $item->id]), [
            'payment_method' => 'convenience',
        ]);

        $response->assertRedirectContains('https://checkout.stripe.com/c/pay');
    }
}
