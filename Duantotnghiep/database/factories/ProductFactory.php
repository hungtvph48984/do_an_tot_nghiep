<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Category;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->ean13(),
            'name' => $this->faker->words(3, true),
            'image' => $this->faker->imageUrl(400, 400, 'products'),
            'description' => $this->faker->paragraph(),
            'metarial' => $this->faker->word(),
            'instrut' => $this->faker->sentence(),
            'status' => 1,
            // ✅ Random category_id đã tồn tại, nếu chưa thì tạo mới
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),

        ];
    }
}
