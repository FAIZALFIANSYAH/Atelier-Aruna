@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/category-index.css') }}">
@endsection

@section('content')
<div class="category-page">
    <div class="category-shell">
        <div class="category-heading">
            <div><h1>Manajemen Kategori</h1><p>Atur kelompok produk agar katalog tetap rapi dan mudah ditemukan.</p></div>
        </div>

        @if(session('success'))<div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-warning border-0 shadow-sm">{{ session('error') }}</div>@endif

        <section class="category-create">
            <div class="category-label"><i class="fas fa-plus-circle"></i> Tambah kategori baru</div>
            <form action="{{ route('category.store') }}" method="POST" class="category-form">
                @csrf
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Contoh: Pakaian, Makanan, atau Minuman" required>
                <button type="submit" class="btn btn-primary category-submit"><i class="fas fa-plus mr-1"></i> Tambah</button>
            </form>
            @error('name')<small class="text-danger d-block mt-2">{{ $message }}</small>@enderror
        </section>

        <section class="category-list">
            <div class="category-list-head"><h2>Daftar Kategori</h2><span class="category-count">{{ $category->count() }} kategori</span></div>
            <table class="table category-table">
                <thead><tr><th>Kategori</th><th>Jumlah Produk</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($category as $item)
                    <tr>
                        <td><span class="category-name"><span class="category-icon"><i class="fas fa-tag"></i></span>{{ $item->name }}</span></td>
                        <td><span class="category-product-count">{{ $item->product_count }} produk</span></td>
                        <td><div class="category-actions">
                            <a href="{{ route('category.edit', $item->id) }}" class="category-action category-edit" title="Edit kategori"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('category.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $item->name }}?')">@csrf @method('DELETE')<button type="submit" class="category-action category-delete" title="Hapus kategori"><i class="fas fa-trash"></i></button></form>
                        </div></td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="category-empty"><i class="fas fa-tags d-block mb-2"></i>Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>
</div>
@endsection
