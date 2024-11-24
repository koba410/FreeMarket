<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_all_items()
    {
        // 商品を複数作成
        Item::factory()->count(5)->create();

        // 商品ページにアクセス
        $response = $this->get(route('item.list'));

        // ステータスコードと商品表示を確認
        $response->assertStatus(200);
        $response->assertViewHas('items'); // ビューに 'items' 変数が渡されているか確認
    }
    /** @test */
    public function it_displays_sold_label_for_purchased_items()
    {
        // 購入済みの商品を作成
        $soldItem = Item::factory()->create(['is_sold' => true]);

        // 商品ページにアクセス
        $response = $this->get(route('item.list'));

        // 商品に「Sold」のラベルが表示されることを確認
        $response->assertSee('Sold');
    }
    /** @test */
    public function it_does_not_display_items_listed_by_the_user()
    {
        // ユーザーとその出品商品を作成
        $user = User::factory()->create();
        $userItem = Item::factory()->create(['seller_id' => $user->id]);

        // 他の商品の作成
        $otherItem = Item::factory()->create();

        // ユーザーとしてログインして商品ページにアクセス
        $response = $this->actingAs($user)->get(route('item.list'));

        // 自分が出品した商品は表示されないことを確認
        $response->assertDontSee($userItem->title);

        // 他のユーザーが出品した商品は表示されることを確認
        $response->assertSee($otherItem->title);
    }
}
