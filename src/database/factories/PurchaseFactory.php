<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_id' => Item::factory(), // 関連するアイテムを生成
            'buyer_id' => User::factory(), // 関連するユーザーを生成
            'delivary_postal_code' => $this->faker->regexify('[0-9]{3}-[0-9]{4}'), // 日本形式の郵便番号 (例: 123-4567)
            'delivary_address' => $this->faker->address, // 住所
            'delivary_building' => $this->faker->optional()->secondaryAddress, // 建物名 (オプション)
            'payment_method' => $this->faker->randomElement(['card', 'konbini',]), // 購入方法をランダムに設定
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
