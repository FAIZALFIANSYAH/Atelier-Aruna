<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\StoreCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Cart::class);
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $this->authorize('view', $cart);
        $cartItem = $cart->cartItems()->with('product')->get();
        return view('cart.index', compact('cartItem'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('cart.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartRequest $request)
    {
        $this->authorize('create', Cart::class);

        $validated = $request->validated();
        $user = Auth::user();

        $product = \App\Models\Product::findOrFail($validated['product_id']);
        if ($validated['quantity'] > $product->current_stock) {
            return redirect()->back()->with('error', 'Jumlah product melebihi stock yang tersedia');
        }

        $cart = Cart::where('user_id', $user->id)->first();
        if (!$cart) {
            $cart = Cart::create(['user_id' => $user->id]);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $validated['product_id'])->first();

        $newQuantity = $cartItem
            ? $cartItem->quantity + $validated['quantity']
            : $validated['quantity'];

        if ($newQuantity > $product->current_stock) {
            return redirect()->back()->with('error', 'Jumlah product di keranjang melebihi stock yang tersedia');
        }

        if (!$cartItem) {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        }
        if ($cartItem) {
            $cartItem->update(['quantity' => $newQuantity]);
        }

        return redirect()->route('transaction.index')->with('success', 'Product berhasil ditambahkan ke keranjang');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, string $id)
    {
        $validated = $request->validated();
        $cartItem = CartItem::whereHas('cart', fn ($query) => $query->where('user_id', Auth::id()))
            ->with('product')->findOrFail($id);
        $this->authorize('update', $cartItem);

        if ($validated['quantity'] > $cartItem->product->current_stock) {
            return redirect()->back()->with('error', 'Jumlah product melebihi stock yang tersedia');
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return redirect()->route('cart.index')->with('success', 'Jumlah product berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem)
    {
        $this->authorize('delete', $cartItem);
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'product berhasil di hapus dari keranjang');
    }

    public function removeItem(CartItem $cartItem)
    {
        $this->authorize('delete', $cartItem);
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'product berhasil di hapus dari keranjang');
    }
}
