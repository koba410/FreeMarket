<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'seller_id' => User::factory(), // 関連するユーザーを生成
            'title' => $this->faker->words(3, true),
            'brand' => $this->faker->company,
            'price' => $this->faker->numberBetween(1000, 100000),
            'description' => $this->faker->paragraph,
            'item_status_id' => Status::factory(), // 関連するアイテムステータスを生成
            'item_image' => 'item_image/default.png', // ダミー画像パス
            'is_sold' => false, // デフォルトで未購入状態
        ];
    }
}
