<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'image',
        'price',
        'stock',
        'material',
        'available_sizes',
        'production_time',
        'order_type',
        'artisan_name',
        'is_customizable',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }

    public function cartItems()
    {
       return $this->hasMany(CartItem::class);
    }

    protected function currentStock(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->stockHistories()->get()->sum(function ($history) {
                    return $history->type === 'in' ? $history->quantity : -$history->quantity;
                });
            }
        );
    }
}

