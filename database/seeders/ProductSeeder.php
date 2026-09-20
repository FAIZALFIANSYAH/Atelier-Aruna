<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $product = [
            ['category_id' => 1, 'name' => 'Kemeja Linen Aruna', 'description' => 'Kemeja lengan panjang dengan jahitan rapi untuk tampilan kasual maupun formal.', 'price' => 185000, 'image' => 'products/kemeja-linen.jpg'],
            ['category_id' => 2, 'name' => 'Celana Chino Custom', 'description' => 'Celana chino dengan ukuran yang dapat disesuaikan untuk kenyamanan terbaik.', 'price' => 225000, 'image' => 'products/celana-chinos.jpg'],
            ['category_id' => 3, 'name' => 'Seragam Kemeja Kerja', 'description' => 'Kemeja kerja berpotongan rapi untuk kebutuhan tim atau usaha.', 'price' => 165000, 'image' => 'products/kemeja-kerja.webp'],
            ['category_id' => 1, 'name' => 'Hoodie Fleece Bordir', 'description' => 'Hoodie fleece hangat dengan pilihan bordir nama atau logo.', 'price' => 245000, 'image' => 'products/hoodie-fleece.jpeg'],
        ];

        DB::table('products')->insert($product);
    }
}
