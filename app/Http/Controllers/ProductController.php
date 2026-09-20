<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Models\User;
use App\Models\Category;
use App\Models\StockHistory;
use App\Services\ProductService;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{

    public function __construct(private ProductService $productService)
    {
        // throw new \Exception('Not implemented');
    }
    public function index(Request $request)
    {
        $product = $this->productService->getFilteredProducts($request->all());
        $categories = Category::orderBy('name')->get();

        return view('product.index', compact('product', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Product::class);
        $categories = Category::all();

        return view('product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $this->authorize('create', Product::class);
        $validated = $request->validated();

        if ($request->hasFile('image')) $validated['image'] = $request->file('image')->store('products', 'public');
        Product::create($validated);

        return redirect()->route('product.index')->with('success', 'product berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category');

        return view('product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        //
        //$product = Product::findOrfail($id);
        return view('product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product) //Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        $product->update($validated);

        return redirect()->route('product.index')->with('success', 'berhasil update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();

        return redirect()->route('product.index')->with('success', 'hapus berhasil');
    }

    public function restock(Product $product)
    {
        $this->authorize('restock', $product);

        return view('product.restock', compact('product'));
    }

    public function storeRestock(Request $request, Product $product)
    {
        $this->authorize('restock', $product);

        StockHistory::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'type' => 'in',
            'quantity' => $request->quantity,
            'description' => $request->description ?? 'admin melakukan restock',
        ]);

        return redirect()->route('product.index')->with('success', 'Stock berhasil ditambahkan');
    }

}
