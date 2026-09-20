<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\StockHistory;

class StockHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'product_id' => $i,
                'user_id' => 2,
                'type' => 'in',
                'quantity' => rand(10, 50),
                'description' => 'stock awal product',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        StockHistory::insert($data);
    }
}
