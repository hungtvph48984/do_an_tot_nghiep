<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id'    => rand(1, 20),
            'color_id'  => rand(1, 4),
            'size_id'   => rand(1, 5),
            'price' => rand(10, 100),
            'sale'  => null,
            'stock' => rand(1, 1000),
            'image' => fake()->imageUrl(),
        ];
    }
}
