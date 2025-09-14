<?php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
=======
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
<<<<<<< HEAD
 Category::factory()->count(10)->create();
=======
        DB::table('categories')->insert([
            ['name' => 'Áo phông/ Áo thun'],
            ['name' => 'Áo polo'],
            ['name' => 'Áo sơ mi'],
            ['name' => 'Áo chống nắng'],
        ]);
>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
    }
}
