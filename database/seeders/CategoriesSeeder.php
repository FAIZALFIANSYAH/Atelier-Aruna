<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Atasan Jahitan'],
            ['name' => 'Bawahan Jahitan'],
            ['name' => 'Seragam & Custom'],
        ];
        DB::table('categories')->insert($categories);
    }
}
