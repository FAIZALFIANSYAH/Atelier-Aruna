<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\HistoryStockController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckLogin;

Route::get('/', [TransactionController::class, 'home'])->name('home');
Route::get('/catalog', [TransactionController::class, 'index'])->name('transaction.index');
Route::get('/catalog/product/{product}', [ProductController::class, 'show'])->name('product.detail');

Route::resource('login', LoginController::class);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'index'])->name('register.index');
Route::post('register', [RegisterController::class, 'store'])->name('register.store');
Route::middleware([CheckLogin::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/address', [ProfileController::class, 'address'])->name('profile.address');
    Route::put('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.address.update');
    Route::get('/profile/regions/{level}/{parent?}', [ProfileController::class, 'regions'])->name('profile.regions');
});

Route::middleware([CheckLogin::class, 'role:admin'])->group(function () {
    Route::get('/dashboard', [TransactionController::class, 'dashboard'])->name('dashboard');
    Route::get('/sales-report', [TransactionController::class, 'report'])->name('transaction.report');
    Route::resource('category', CategoryController::class);

    Route::resource('product', ProductController::class);
    Route::get('/product/{product}/restock', [ProductController::class, 'restock'])->name('product.restock');
    Route::post('/product/{product}/restock', [ProductController::class, 'storeRestock'])->name('product.storeRestock');
    Route::get('/stock-history/{product}', [HistoryStockController::class, 'index'])->name('product.history');
});

Route::middleware([CheckLogin::class, 'role:member'])->group(function () {
    Route::post('/submit-transaction', [TransactionController::class, 'store'])->name('transaction.store');
    Route::post('/checkout-cart', [TransactionController::class, 'checkoutCart'])->name('transaction.checkoutCart');
    Route::get('/checkout', [TransactionController::class, 'checkout'])->name('transaction.checkout');
    Route::post('/checkout/confirm', [TransactionController::class, 'confirmCheckout'])->name('transaction.confirmCheckout');
    Route::get('/history-transaction', [TransactionController::class, 'history'])->name('transaction.history');
    Route::get('/transaction/{transaction}', [TransactionController::class, 'show'])->name('transaction.show');
    Route::get('/transaction/{transaction}/struk', [TransactionController::class, 'struk'])->name('transaction.struk');
    Route::get('/transaction/{transaction}/struk/download', [TransactionController::class, 'downloadStruk'])->name('transaction.struk.download');
    Route::patch('/transaction/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transaction.cancel');
    Route::resource('cart', CartController::class);
    Route::delete('/cart/item/{cartItem}', [CartController::class, 'removeItem'])->name('cart.removeItem');
});

Route::middleware([CheckLogin::class, 'role:cashier'])->group(function () {
    Route::get('/cashier/transaction', [TransactionController::class, 'pending'])->name('cashier.index');
    Route::patch('/cashier/transaction/{transaction}', [TransactionController::class, 'process'])->name('cashier.process');
});


//Route::resource('notes', Notes::class);
