<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\StockHistory;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionService
{
    public function store(array $data): Transaction
    {
        $user = Auth::user();
        $product = Product::findOrFail($data['product_id']);
        $transactionCode = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(8));

        return DB::transaction(function () use ($transactionCode, $user, $product, $data) {
            $product = Product::lockForUpdate()->findOrFail($product->id);

            if ($product->current_stock <= 0) {
                throw new \Exception('stock product habis, tunggu restock ya');
            }

            if ($data['quantity'] > $product->current_stock) {
                throw new \Exception('quantity melebihi ketersidaan stock');
            }

            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'member_id' => $user->id,
                'status' => 'pending',
                'payment_method' => $data['payment_method'] ?? null,
                'shipping_recipient' => $user->address_recipient,
                'shipping_phone' => $user->address_phone,
                'shipping_address' => $user->address,
                'shipping_city' => $user->address_city,
                'shipping_postal_code' => $user->address_postal_code,
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'quantity' => $data['quantity'],
                'price' => $product->price,
                'subtotal' => $product->price * $data['quantity'],
            ]);

            StockHistory::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'type' => 'out',
                'quantity' => $data['quantity'],
                'description' => 'member membeli product',
            ]);

            return $transaction->load(['transactionDetails.product', 'member']);
        });
    }

    public function checkoutCart(array $data): Transaction
    {
        $user = Auth::user();

        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            throw new \Exception('Keranjang masih kosong');
        }

        $cartItems = $cart->cartItems;

        if ($cartItems->isEmpty()) {
            throw new \Exception('Keranjang masih kosong');
        }

        $transactionCode = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(8));

        return DB::transaction(function () use ($transactionCode, $user, $cartItems, $data) {

            $products = Product::whereIn('id', $cartItems->pluck('product_id')->sort()->values())
                ->lockForUpdate()->get()->keyBy('id');

            foreach ($cartItems as $cartItem) {
                $product = $products->get($cartItem->product_id);

                if (!$product || $product->current_stock <= 0) {
                    throw new \Exception("{$cartItem->product->name} stock habis");
                }

                if ($cartItem->quantity > $product->current_stock) {
                    throw new \Exception("Stock {$product->name} tidak mencukupi");
                }
            }

            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'member_id' => $user->id,
                'status' => 'pending',
                'payment_method' => $data['payment_method'] ?? null,
                'shipping_recipient' => $user->address_recipient,
                'shipping_phone' => $user->address_phone,
                'shipping_address' => $user->address,
                'shipping_city' => $user->address_city,
                'shipping_postal_code' => $user->address_postal_code,
            ]);

            foreach ($cartItems as $cartItem) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $products->get($cartItem->product_id)->price,
                    'subtotal' => $products->get($cartItem->product_id)->price * $cartItem->quantity,
                ]);
            }

            foreach ($cartItems as $cartItem) {
                StockHistory::create([
                    'product_id' => $cartItem->product_id,
                    'user_id' => $user->id,
                    'type' => 'out',
                    'quantity' => $cartItem->quantity,
                    'description' => 'member membeli product',
                ]);
            }

            $cartItems->each->delete();

            return $transaction->load(['transactionDetails.product', 'member']);
        });
    }
}
