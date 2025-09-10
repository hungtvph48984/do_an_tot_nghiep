<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed danh mục, màu, size trước
        $this->call([
            CategorySeeder::class,
            ColorSeeder::class,
            SizeSeeder::class,
        ]);

        // Tạo 20 sản phẩm, mỗi sản phẩm có 3-5 biến thể
        Product::factory(20)->create()->each(function ($product) {
            $product->variants()->createMany(
                \App\Models\ProductVariant::factory(rand(3, 5))->make()->toArray()
            );
        });
    }
}
