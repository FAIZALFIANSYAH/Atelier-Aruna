@extends('layouts.app')

@section('content')
<h1>Restock Product</h1>

<div class="card">
    <div class="card-body">
        <h3>{{ $product->name }}</h3>
        <p>{{ $product->description }}</p>
        <p>Stock saat ini : {{ $product->current_stock }}</p>

        <form action="{{ route('product.storeRestock',$product->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Quantity</label>
                <input type="number" name="quantity" class="form-control" min="1" required>
                @error('quantity')<p class="text-danger small">{{ $message }}</p>@enderror
            </div>

            <div class="mb-3">
                <label>Description (Keterangan)</label>
                <input type="text" name="description" class="form-control" placeholder="Contoh: Restock bulanan, Koreksi stok, dll.">
                @error('description')<p class="text-danger small">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn btn-success">Tambah Stock</button>
        </form>
    </div>
</div>
@endsection