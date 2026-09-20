<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\Http\Request;

class HistoryStockController extends Controller
{
    public function index(Product $product)
    {
        $history = $product->stockHistories;

        return view('product.history', compact('history', 'product'));
    }
}
