<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo 10 sản phẩm
        Product::factory(10)->create()->each(function ($product) {
            // Mỗi sản phẩm có 3-5 biến thể
            ProductVariant::factory(rand(3, 5))->create([
                'product_id' => $product->id
            ]);
        });
    }
}
