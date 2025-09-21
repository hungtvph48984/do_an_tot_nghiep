<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


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

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(
            [
                CategorySeeder::class,
                ColorSeeder::class,
                SizeSeeder::class
            ]
        );

        Product::factory(20)->create();

        ProductVariant::factory(30)->create();

    }
}
