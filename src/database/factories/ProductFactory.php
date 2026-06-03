<?php

namespace Database\Factories;

use App\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => fake()->words(3, true),
            'description' => fake()->optional()->sentence(),
            'price'       => fake()->numberBetween(100, 50_000),
            'weight'      => fake()->optional()->randomFloat(3, 0.1, 50),
            'category'    => fake()->randomElement(['electronics', 'books', 'clothing', 'food']),
        ];
    }
}
