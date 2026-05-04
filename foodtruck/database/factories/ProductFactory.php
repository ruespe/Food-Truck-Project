<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name'        => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price'       => fake()->randomFloat(2, 2, 20),
            'image'       => null,
            'stock'       => true,
            'available'   => true,
        ];
    }

    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => ['available' => false]);
    }
}
