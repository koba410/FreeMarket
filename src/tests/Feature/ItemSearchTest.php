<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_search_items_by_name_with_partial_match()
    {
        // ユーザー作成と認証
        $user = User::factory()->create();
        $this->actingAs($user);

        // テストデータ作成
        Item::factory()->create(['title' => 'Test Item']);
        Item::factory()->create(['title' => 'Another Item']);
        Item::factory()->create(['title' => 'Sample Item']);
        Item::factory()->create(['title' => 'wrong title']);

        // 部分一致検索を実行
        $response = $this->get(route('item.list', ['search' => 'Item']));

        // 検索結果に部分一致した商品が含まれていることを確認
        $response->assertStatus(200);
        $response->assertSee('Test Item');
        $response->assertSee('Another Item');
        $response->assertSee('Sample Item');
        $response->assertDontSee('wrong title');
    }

    /** @test */
    public function it_preserves_search_keyword_in_mylist()
    {
        // ユーザー作成と認証
        $user = User::factory()->create();
        $this->actingAs($user);

        // テストデータ作成
        $item1 = Item::factory()->create(['title' => 'Test Item']);
        $item2 = Item::factory()->create(['title' => 'Another Item']);
        $item3 = Item::factory()->create(['title' => 'wrong title']);
        $user->likedItems()->attach($item1); // マイリストにいいねした商品を追加
        $user->likedItems()->attach($item2);
        $user->likedItems()->attach($item3);

        // マイリストページに移動し、検索キーワードが保持されていることを確認
        $response = $this->get(route('item.list', ['tab' => 'mylist', 'search' => 'Item']));

        $response->assertStatus(200);
        $response->assertSee('Test Item');
        $response->assertSee('Another Item');
        $response->assertDontSee('wrong title');
    }
}
