<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_only_liked_items_in_mylist()
    {
        $user = User::factory()->create();
        $likedItem = Item::factory()->create();
        $nonLikedItem = Item::factory()->create();

        // いいねをした商品を設定
        Like::create(['user_id' => $user->id, 'item_id' => $likedItem->id]);

        // ユーザーとしてログイン
        $this->actingAs($user);

        // ログインしたユーザーとしてマイリストタブにアクセス
        $response = $this->actingAs($user)->get(route('item.list', ['tab' => 'mylist']));

        // いいねした商品のみが表示され、いいねしていない商品は表示されないことを確認
        $response->assertSee($likedItem->title);
        $response->assertDontSee($nonLikedItem->title);
    }

    /** @test */
    public function it_displays_sold_label_for_purchased_items_in_mylist()
    {
        $user = User::factory()->create();
        $purchasedItem = Item::factory()->create(['is_sold' => true]);

        // いいねした商品として購入済み商品を設定
        Like::create(['user_id' => $user->id, 'item_id' => $purchasedItem->id]);

        // ユーザーとしてログイン
        $this->actingAs($user);

        // ログインしたユーザーとしてマイリストタブにアクセス
        $response = $this->actingAs($user)->get(route('item.list', ['tab' => 'mylist']));

        // 購入済みの商品に "Sold" ラベルが表示されていることを確認
        $response->assertSee('Sold');
    }

    /** @test */
    public function it_does_not_display_items_listed_by_the_user_in_mylist()
    {
        $user = User::factory()->create();
        $userListedItem = Item::factory()->create(['seller_id' => $user->id]);
        $otherItem = Item::factory()->create();

        // 他の商品のみをいいねとして設定
        Like::create(['user_id' => $user->id, 'item_id' => $otherItem->id]);

        // ユーザーとしてログイン
        $this->actingAs($user);

        // ログインしたユーザーとしてマイリストタブにアクセス
        $response = $this->actingAs($user)->get(route('item.list', ['tab' => 'mylist']));

        // 自分が出品した商品は表示されず、他の商品のみが表示されることを確認
        $response->assertDontSee($userListedItem->title);
        $response->assertSee($otherItem->title);
    }

    /** @test */
    public function it_displays_nothing_if_user_is_not_logged_in()
    {
        $user = User::factory()->create();
        $likedItem = Item::factory()->create();

        // いいねをした商品を設定
        Like::create(['user_id' => $user->id, 'item_id' => $likedItem->id]);


        $response = $this->get(route('item.list', ['tab' => 'mylist']));

        // 未ログインユーザーには何も表示されないことを確認
        $response->assertDontSee($likedItem->title);
    }
}
