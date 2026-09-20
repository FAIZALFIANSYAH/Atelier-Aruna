@extends('layouts.auth')

@section('content')
<form action="{{ route('login.store') }}" method="POST">
    @csrf
    <div class="input-group mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
        <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope text-muted"></span></div>
        </div>
    </div>
    @error('email')<p class="text-danger small mt-n2 mb-3">{{ $message }}</p>@enderror

    <div class="input-group mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock text-muted"></span></div>
        </div>
    </div>
    @error('password')<p class="text-danger small mt-n2 mb-3">{{ $message }}</p>@enderror

    <div class="row mt-4">
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block shadow-sm font-weight-bold">
                <i class="fas fa-sign-in-alt mr-2"></i> Login
            </button>
        </div>
    </div>
</form>

<hr class="my-4">

<p class="mb-0 text-center">Belum punya akun?
    <a href="{{ route('register.index') }}" class="text-primary font-weight-bold">Daftar sekarang</a>
</p>
@endsection
