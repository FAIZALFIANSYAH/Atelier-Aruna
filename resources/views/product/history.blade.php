@extends('layouts.app')

@section('content')
<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>User</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($history as $item)
            <tr>
                {{-- Menggunakan {{ }} agar data PHP-nya tereksekusi --}}

                <td>{{ $item->product->name }}</td>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->type }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection