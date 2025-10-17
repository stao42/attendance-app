<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'brand' => $this->faker->company(),
            'price' => $this->faker->numberBetween(100, 100000),
            'condition' => $this->faker->randomElement(['excellent', 'good', 'fair', 'poor']),
            'image' => 'products/' . $this->faker->uuid() . '.jpg',
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'is_sold' => false,
        ];
    }

    /**
     * Indicate that the product is sold.
     */
    public function sold(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_sold' => true,
        ]);
    }
}
