<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // 関連するユーザーを生成
            'postal_code' => $this->faker->regexify('[0-9]{3}-[0-9]{4}'), // 日本形式の郵便番号 (例: 123-4567)
            'address' => $this->faker->address, // 住所
            'building' => $this->faker->optional()->secondaryAddress, // 建物名 (オプション)
            'profile_image' => $this->faker->optional()->imageUrl(300, 300, 'people', true, 'Profile Image'), // プロフィール画像 (オプション)
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
