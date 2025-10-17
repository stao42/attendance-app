<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'status' => 'pending',
            'payment_method' => 'card',
            'shipping_address' => $this->faker->address(),
            'shipping_postal_code' => $this->faker->postcode(),
            'shipping_building' => $this->faker->optional()->secondaryAddress(),
        ];
    }

    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'delivered',
            ];
        });
    }
}
