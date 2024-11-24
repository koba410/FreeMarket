<?php

namespace Database\Factories;

use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatusFactory extends Factory
{
    protected $model = Status::class;

    public function definition()
    {
        return [
            'status' => $this->faker->randomElement(['良好', '目立った傷や汚れなし',  'やや傷や汚れあり', '状態が悪い',]), // 状態をランダムに設定
        ];
    }
}
