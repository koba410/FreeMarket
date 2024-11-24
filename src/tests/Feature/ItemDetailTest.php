<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Status;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\DomCrawler\Crawler;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_all_required_item_details()
    {
        // テストデータの作成
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        // プロフィールの作成
        $user1_profile = Profile::factory()->create(['user_id' => $user1->id, 'profile_image' => 'profile_image/default.jpg']);

        // ステータスを作成
        $status = Status::factory()->create(['status' => '良好']);
        $item = Item::factory()->create([
            'seller_id' => $user1->id,
            'title' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 5000,
            'description' => 'テスト説明文',
            'item_image' => 'item_image/default.jpg', // 商品画像のパス
            'is_sold' => false,
            'item_status_id' => $status->id,
        ]);

        // カテゴリと商品を関連付け
        $category = Category::factory()->create(['category' => 'ファッション']);
        $item->categories()->attach($category->id);

        // いいねとコメントを作成
        $user1->likedItems()->attach($item);
        $user2->likedItems()->attach($item);
        $user3->likedItems()->attach($item);
        $commentUser = User::factory()->create(['name' => 'コメントユーザー']);
        $item->comments()->create([
            'user_id' => $commentUser->id,
            'comment' => 'テストコメント',
        ]);

        // 商品詳細ページにアクセス
        $response = $this->get(route('item.show', $item->id));

        // 必要な情報が表示されているかを確認
        $response->assertStatus(200);
        $response->assertSee($item->title);               // 商品名
        $response->assertSee($item->brand);               // ブランド
        $response->assertSee("¥" . number_format($item->price)); // カンマ付き価格
        $response->assertSee($item->description);         // 商品説明
        $response->assertSee($category->category);        // カテゴリ名
        $response->assertSee('bi-heart');                 // いいねアイコン
        $response->assertSee('bi-chat');                  // コメントアイコン
        $response->assertSee('良好');                     // 商品の状態
        $response->assertSee($commentUser->name);         // コメントしたユーザー名
        $response->assertSee('テストコメント');           // コメント内容

        // HTMLレスポンスの内容を取得して解析
        $crawler = new Crawler($response->getContent());

        // いいね数の検証
        $likeCount = $crawler->filter('.like-count')->text();
        $this->assertEquals('3', $likeCount);

        // コメント数の検証
        $commentCount = $crawler->filter('.comment-count')->text();
        $this->assertEquals('1', $commentCount);

        // 商品画像の検証
        $itemImage = $crawler->filter('img[src="/storage/item_image/default.jpg"]');
        $this->assertCount(1, $itemImage); // 商品画像が存在することを確認

        // コメントユーザー画像の検証
        $commentUserImage = $crawler->filter('img[src="/storage/profile_image/default.jpg"]');
        $this->assertCount(1, $commentUserImage); // コメントユーザー画像が存在することを確認
    }

    /** @test */
    public function it_displays_multiple_categories_associated_with_the_item()
    {
        // テストデータの作成
        $user = User::factory()->create();
        $item = Item::factory()->create(['seller_id' => $user->id]);

        // 複数のカテゴリを作成し、商品に関連付け
        $categories = Category::factory()->count(3)->create();
        $item->categories()->attach($categories->pluck('id'));

        // 商品詳細ページにアクセス
        $response = $this->get(route('item.show', $item->id));

        // すべてのカテゴリが表示されているかを確認
        foreach ($categories as $category) {
            $response->assertSee($category->category);
        }
    }
}
