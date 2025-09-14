<?php

namespace Database\Seeders;

<<<<<<< HEAD
=======
use App\Models\Product;
use App\Models\ProductVariant;
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

<<<<<<< HEAD



=======
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
<<<<<<< HEAD
        $this->call([
        CategorySeeder::class,
    ]);

=======

        $this->call(
            [
                CategorySeeder::class,
                ColorSeeder::class,
                SizeSeeder::class
            ]
        );

        Product::factory(20)->create();

        ProductVariant::factory(30)->create();
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
    }
}
