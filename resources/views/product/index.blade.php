@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/product-index.css') }}">
@endsection

@section('content')
<div class="product-page">
    <div class="product-shell">
        <div class="product-heading">
            <div>
                <h1>Manajemen Produk</h1>
                <p>Kelola katalog, harga, dan ketersediaan stok produk Anda.</p>
            </div>
            <a href="{{ route('product.create') }}" class="btn btn-primary product-add"><i class="fas fa-plus mr-1"></i> <span>Tambah Produk</span></a>
        </div>

        @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
        @endif

        <div class="product-toolbar">
            <div class="toolbar-title"><i class="fas fa-sliders-h"></i> Cari dan filter produk</div>
            <form action="{{ route('product.index') }}" method="GET" class="product-filters">
                <input type="search" name="name" value="{{ request('name') }}" class="form-control" placeholder="Cari nama produk...">
                <select name="category" class="form-control">
                    <option value="">Semua kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <select name="sort" class="form-control">
                    <option value="">Urutkan harga</option>
                    <option value="asc" @selected(request('sort') === 'asc')>Harga terendah</option>
                    <option value="desc" @selected(request('sort') === 'desc')>Harga tertinggi</option>
                </select>
                <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control" placeholder="Harga minimum">
                <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control" placeholder="Harga maksimum">
                <button type="submit" class="btn btn-primary filter-button"><i class="fas fa-search mr-1"></i> Terapkan</button>
                <a href="{{ route('product.index') }}" class="clear-filter">Reset</a>
            </form>
        </div>

        <div class="product-table-card">
            <div class="product-table-head">
                <h2>Daftar Produk</h2>
                <span class="product-count">{{ $product->total() }} produk</span>
            </div>
            <table class="table product-table">
                <thead><tr><th>Produk</th><th>Kategori</th><th>Deskripsi</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($product as $products)
                @php($stockClass = $products->current_stock <= 0 ? 'empty' : ($products->current_stock <= 10 ? 'low' : ''))
                <tr>
                    <td><span class="product-title">{{ $products->name }}</span><span class="product-code">{{ $products->material ?: 'Bahan belum diisi' }} · {{ $products->available_sizes ?: 'Ukuran belum diisi' }}</span></td>
                    <td><span class="category-pill">{{ $products->category->name }}</span></td>
                    <td><div class="product-description">{{ $products->description ?: 'Belum ada deskripsi produk.' }}<small class="d-block mt-1">{{ $products->order_type === 'pre_order' ? 'Pre-order' : 'Ready stock' }}{{ $products->production_time ? ' · ' . $products->production_time : '' }}{{ $products->is_customizable ? ' · Bisa custom' : '' }}</small></div></td>
                    <td><span class="price-value">Rp {{ number_format($products->price, 0, ',', '.') }}</span></td>
                    <td><span class="stock-pill {{ $stockClass }}">{{ $products->current_stock }} tersedia</span></td>
                    <td><div class="product-actions">
                        <a href="{{ route('product.edit', $products->id) }}" class="action-button action-edit" title="Edit produk"><i class="fas fa-pen"></i></a>
                        <a href="{{ route('product.restock', $products->id) }}" class="action-button action-stock" title="Restock"><i class="fas fa-box-open"></i></a>
                        <a href="{{ route('product.history', $products->id) }}" class="action-button action-history" title="Riwayat stok"><i class="fas fa-history"></i></a>
                        <form action="{{ route('product.destroy', $products->id) }}" method="POST" onsubmit="return confirm('Hapus produk {{ $products->name }}?')">@csrf @method('DELETE')<button type="submit" class="action-button action-delete" title="Hapus produk"><i class="fas fa-trash"></i></button></form>
                    </div></td>
                </tr>
                @empty
                <tr><td colspan="6" class="empty-product"><i class="fas fa-box-open"></i>Belum ada produk yang sesuai dengan pencarian.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="product-footer">
            <div>Menampilkan {{ $product->firstItem() ?? 0 }}–{{ $product->lastItem() ?? 0 }} dari {{ $product->total() }} produk</div>
            <div>{{ $product->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>
@endsection
