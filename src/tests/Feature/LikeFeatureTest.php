<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_like_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーとしてログイン
        $this->actingAs($user);

        // 商品詳細ページにアクセスし、いいねボタンが表示されているか確認
        $response = $this->get(route('item.show', $item->id));
        $crawler = new Crawler($response->getContent());
        $response->assertStatus(200);
        $response->assertSee('bi-heart'); // いいねアイコンが表示されている
        // いいね数の検証
        $likeCount = $crawler->filter('.like-count')->text();
        $this->assertEquals('0', $likeCount); //初期値0

        // いいねアイコンを押下（いいねを追加）
        $this->post(route('item.like', $item->id))->assertStatus(302); // リダイレクト確認

        // 商品詳細ページを再度取得して、いいねが追加されていることを確認
        $response = $this->get(route('item.show', $item->id));
        $crawler = new Crawler($response->getContent());

        // いいね数の検証
        $likeCount = $crawler->filter('.like-count')->text();
        $this->assertEquals('1', $likeCount); 
    }

    /** @test */
    public function the_like_icon_changes_color_after_liking()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーとしてログイン
        $this->actingAs($user);

        // いいねを追加
        $this->post(route('item.like', $item->id));

        // 商品詳細ページにアクセスし、いいね済みアイコンの色が変わっていることを確認
        $response = $this->get(route('item.show', $item->id));
        $response->assertSee('bi-heart-fill'); // いいね済みのアイコンが表示されている（例: 塗りつぶされたアイコン）
    }

    /** @test */
    public function a_user_can_unlike_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーとしてログインし、いいねを追加
        $this->actingAs($user);
        $this->post(route('item.like', $item->id));
        $response = $this->get(route('item.show', $item->id));
        $crawler = new Crawler($response->getContent());
        $likeCount = $crawler->filter('.like-count')->text();
        $this->assertEquals('1', $likeCount); // いいねの合計数が1になる

        // いいねを削除
        $response = $this->delete(route('item.unlike', $item->id));
        $response->assertStatus(302); // リダイレクト確認

        // 商品詳細ページを確認し、いいねが削除されていることを確認
        $response = $this->get(route('item.show', $item->id));
        $crawler = new Crawler($response->getContent());
        $likeCount = $crawler->filter('.like-count')->text();
        $this->assertEquals('0', $likeCount); // いいねの合計数が0に戻っている
    }
}
