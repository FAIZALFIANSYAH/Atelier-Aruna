<?php

namespace App\Models;

use App\Models\Transaction;
use App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    //

    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'price',
        'subtotal'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
