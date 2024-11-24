<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_item_creation_requires_all_mandatory_fields()
    {
        // ユーザー作成
        $user = User::factory()->create();

        // カテゴリー作成
        $categories = Category::factory()->count(3)->create();
        // ステータス作成
        $status = Status::factory()->create([
            'status' => '良好',
        ]);

        // ユーザーでログイン
        $this->actingAs($user);
        // 商品出品画面を開く
        $response = $this->get(route('sell'));
        $response->assertStatus(200);
        $response->assertViewIs('listing');


        // 画像ストレージのモック
        Storage::fake('public');

        // ダミー画像データ作成
        $item_image = UploadedFile::fake()->create('test.jpg', 500, 'image/jpeg');

        // 商品データ
        $formData = [
            'title' => 'テスト商品',
            'description' => 'これはテスト商品の説明です。',
            'price' => 5000,
            'status' => $status->id, // 商品状態
            'brand' => 'tekito',
            'categories' => $categories->pluck('id')->toArray(), // 複数カテゴリ選択
            'item_image' => $item_image,
        ];

        // フォーム送信
        $response = $this->post(route('item.store'), $formData);

        $item = Item::latest('id')->first(); // 最新のItemを取得
        $item_id = $item->id;

        // 保存先はitem_imageフォルダ
        $filePath = "item_image/item_".$item_id.".jpg";
        
        // リダイレクトの確認
        $response->assertRedirect(route('item.show', ['item_id' => $item_id]));

        // データベースに正しく保存されているか確認
        $this->assertDatabaseHas('items', [
            'title' => 'テスト商品',
            'description' => 'これはテスト商品の説明です。',
            'price' => 5000,
            'item_status_id' => $status->id,
            'seller_id' => $user->id,
            'item_image' => $filePath,
        ]);

        // カテゴリーの関連付けが正しいことを確認
        $item = Item::where('title', 'テスト商品')->first();
        $this->assertEquals($categories->pluck('id')->toArray(), $item->categories->pluck('id')->toArray());

        // ストレージに保存されていることを確認
        Storage::disk('public')->assertExists($filePath); // 修正後
    }
}
