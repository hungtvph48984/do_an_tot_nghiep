<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('colors')->insert([
            ['name' => 'Trắng', 'code'  => '#f0f0f0'],
            ['name' => 'Xanh', 'code'  => '#46cf4b'],
            ['name' => 'Đỏ', 'code'  => '#cf5046'],
            ['name' => 'Tím', 'code'  => '#cb26cc'],
        ]);
    }
}
