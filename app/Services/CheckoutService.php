<?php
namespace App\Services;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Collection;
class CheckoutService
{
    public function __construct(private AddressService $addressService, private TransactionService $transactionService) {}
    public function beginSingle(Product $product, int $quantity): void { session(['pending_checkout' => ['type' => 'single', 'product_id' => $product->id, 'quantity' => $quantity]]); }
    public function beginCart(User $user): bool { $cart = $user->carts()->with('cartItems.product')->first(); if (!$cart || $cart->cartItems->isEmpty()) return false; session(['pending_checkout' => ['type' => 'cart']]); return true; }
    public function ensureAddress(User $user): bool { return $this->addressService->isComplete($user); }
    public function items(User $user, array $pending): Collection { return $pending['type'] === 'single' ? collect([['product' => Product::findOrFail($pending['product_id']), 'quantity' => $pending['quantity']]]) : optional($user->carts()->with('cartItems.product')->first())->cartItems ?? collect(); }
    public function complete(User $user, array $pending, string $paymentMethod): Transaction
    {
        $payload = ['payment_method' => $paymentMethod];
        if ($pending['type'] === 'single') {
            return $this->transactionService->store($payload + ['product_id' => $pending['product_id'], 'quantity' => $pending['quantity']]);
        }
        return $this->transactionService->checkoutCart($payload);
    }
}
