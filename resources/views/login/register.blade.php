@extends('layouts.auth')
@section('content')
<form action="{{ route('register.store') }}" method="POST">@csrf
    <div class="form-group"><label for="name">Nama lengkap</label><input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>@error('name')<small class="text-danger">{{ $message }}</small>@enderror</div>
    <div class="form-group"><label for="email">Email</label><input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="nama@email.com" required>@error('email')<small class="text-danger">{{ $message }}</small>@enderror</div>
    <div class="form-group"><label for="password">Password</label><input id="password" type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>@error('password')<small class="text-danger">{{ $message }}</small>@enderror</div>
    <button class="btn btn-primary btn-block mt-4"><i class="fas fa-user-plus mr-1"></i> Buat Akun</button>
</form><hr class="my-4"><p class="mb-0 text-center">Sudah punya akun? <a href="{{ route('login.index') }}" class="text-primary font-weight-bold">Masuk</a></p>
@endsection
