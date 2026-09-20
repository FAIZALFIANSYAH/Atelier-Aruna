<?php

namespace App\Models;
use App\Models\User;
use App\Models\TransactionDetail;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_code',
        'member_id',
        'handle_by',
        'status',
        'payment_method',
        'shipping_recipient',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
    ];
    public function transactionDetails(){
        return $this->hasmany(TransactionDetail::class);
    }

    public function member(){
        return $this->belongsTo(User::class, 'member_id');
    }

    public function cashier(){
        return $this->belongsTo(User::class, 'handled_by');
    }
}
