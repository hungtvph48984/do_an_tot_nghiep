<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Color;
use App\Models\Size;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'color_id' => Color::inRandomOrder()->first()?->id ?? Color::factory(),
            'size_id' => Size::inRandomOrder()->first()?->id ?? Size::factory(),
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'sale' => $this->faker->randomFloat(2, 50, 500),
            'stock' => $this->faker->numberBetween(0, 100),
            'image' => $this->faker->imageUrl(400, 400, 'variants'),
        ];
    }
}
