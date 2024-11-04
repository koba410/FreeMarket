<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Status;

class AllItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // カテゴリーデータの作成
        $categories = [
            'ファッション' => Category::create(['category' => 'ファッション']),
            '家電' => Category::create(['category' => '家電']),
            'インテリア' => Category::create(['category' => 'インテリア']),
            'レディース' => Category::create(['category' => 'レディース']),
            'メンズ' => Category::create(['category' => 'メンズ']),
            'コスメ' => Category::create(['category' => 'コスメ']),
            '本' => Category::create(['category' => '本']),
            'ゲーム' => Category::create(['category' => 'ゲーム']),
            'スポーツ' => Category::create(['category' => 'スポーツ']),
            'キッチン' => Category::create(['category' => 'キッチン']),
            'ハンドメイド' => Category::create(['category' => 'ハンドメイド']),
            'アクセサリー' => Category::create(['category' => 'アクセサリー']),
            'おもちゃ' => Category::create(['category' => 'おもちゃ']),
            'ベビー・キッズ' => Category::create(['category' => 'ベビー・キッズ']),
        ];

        // 商品状態データの作成
        $statuses = [
            '良好' => Status::create(['status' => '良好']),
            '目立った傷や汚れなし' => Status::create(['status' => '目立った傷や汚れなし']),
            'やや傷や汚れあり' => Status::create(['status' => 'やや傷や汚れあり']),
            '状態が悪い' => Status::create(['status' => '状態が悪い']),
        ];

        // 商品データの作成
        $items = [
            [
                'seller_id' => 1,
                'title' => '腕時計',
                'brand' => 'rolex',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'item_status_id' => 1,
                'item_image' => 'item_image/Armani+Mens+Clock.jpg',
                'categories' => ['ファッション', '家電', 'インテリア']
            ],
            [
                'seller_id' => 2,
                'title' => 'HDD',
                'brand' => 'canon',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'item_status_id' => 2,
                'item_image' => 'item_image/HDD+Hard+Disk.jpg',
                'categories' => ['レディース', 'メンズ', 'コスメ']
            ],
            [
                'seller_id' => 3,
                'title' => '玉ねぎ3束',
                'brand' => '宮城玉ねぎ',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'item_status_id' => 3,
                'item_image' => 'item_image/iLoveIMG+d.jpg',
                'categories' => ['コスメ', '本']
            ],
            [
                'seller_id' => 4,
                'title' => '革靴',
                'brand' => 'nike',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'item_status_id' => 4,
                'item_image' => 'item_image/Leather+Shoes+Product+Photo.jpg',
                'categories' => ['スポーツ', 'ハンドメイド']
            ],
            [
                'seller_id' => 5,
                'title' => 'ノートPC',
                'brand' => 'windows',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'item_status_id' => 1,
                'item_image' => 'item_image/Living+Room+Laptop.jpg',
                'categories' => ['キッチン', 'おもちゃ']
            ],
            [
                'seller_id' => 1,
                'title' => 'マイク',
                'brand' => 'sony',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'item_status_id' => 2,
                'item_image' => 'item_image/Music+Mic+4632231.jpg',
                'categories' => ['ベビー・キッズ', 'ファッション']
            ],
            [
                'seller_id' => 2,
                'title' => 'ショルダーバッグ',
                'brand' => 'viton',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'item_status_id' => 3,
                'item_image' => 'item_image/Purse+fashion+pocket.jpg',
                'categories' => ['インテリア', 'ゲーム']
            ],
            [
                'seller_id' => 3,
                'title' => 'タンブラー',
                'brand' => 'スリーコインズ',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'item_status_id' => 4,
                'item_image' => 'item_image/Tumbler+souvenir.jpg',
                'categories' => ['ハンドメイド', 'レディース']
            ],
            [
                'seller_id' => 4,
                'title' => 'コーヒーミル',
                'brand' => 'スターバックス',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'item_status_id' => 1,
                'item_image' => 'item_image/Waitress+with+Coffee+Grinder.jpg',
                'categories' => ['ファッション', 'キッチン']
            ],
            [
                'seller_id' => 5,
                'title' => 'メイクセット',
                'brand' => 'dior',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'item_status_id' => 2,
                'item_image' => 'item_image/外出メイクアップセット.jpg',
                'categories' => ['コスメ', 'レディース']
            ],

        ];

        foreach ($items as $itemData) {
            $item = item::create([
                'seller_id' => $itemData['seller_id'],
                'title' => $itemData['title'],
                'brand' => $itemData['brand'],
                'price' => $itemData['price'],
                'description' => $itemData['description'],
                'item_status_id' => $itemData['item_status_id'],
                'item_image' => $itemData['item_image'], // storageのURLで取得
            ]);

            // 関連するカテゴリーをアタッチ
            foreach ($itemData['categories'] as $category) {
                $item->categories()->attach($categories[$category]->id);
            }
        }
    }
}
