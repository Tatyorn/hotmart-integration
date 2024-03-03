<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => fake()->name,
            'user_id' => User::factory(),
            'status' => Arr::random(['approved', 'cancelled', 'expired']),
            'product_id' => Product::factory(),
        ];
    }
}
