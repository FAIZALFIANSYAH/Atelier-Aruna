<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atelier Aruna - Pakaian Jahitan</title>
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app-modern.css') }}">
    <link rel="stylesheet" href="{{ asset('css/management-pages.css') }}">
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
    <link rel="stylesheet" href="{{ asset('css/product-catalog.css') }}">
    <link rel="stylesheet" href="{{ asset('css/catalog-pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('css/public-store.css') }}">
    <link rel="stylesheet" href="{{ asset('css/public-account-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>
<body class="public-body">
    <header class="public-header">
        <div class="public-container public-nav">
            <a href="{{ route('home') }}" class="public-brand"><span>AA</span><div>Atelier Aruna<small>Pakaian Jahitan</small></div></a>
            <nav class="public-menu">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('transaction.index') }}">Katalog Produk</a>
            </nav>
            <div class="public-actions">
                @auth
                    @php($currentUser = auth()->user())
                    <details class="account-menu">
                        <summary class="account-trigger">
                            <span class="account-avatar">{{ strtoupper(substr($currentUser->name, 0, 1)) }}</span>
                            <span class="account-trigger-text"><small>Halo,</small>{{ $currentUser->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </summary>
                        <div class="account-dropdown">
                            <div class="account-dropdown-head">
                                <span class="account-avatar account-avatar-lg">{{ strtoupper(substr($currentUser->name, 0, 1)) }}</span>
                                <div><strong>{{ $currentUser->name }}</strong><small>{{ ucfirst($currentUser->role?->name ?? 'member') }}</small></div>
                            </div>
                            <nav class="account-dropdown-links">
                                @if($currentUser->isMember())
                                    <a href="{{ route('profile.index') }}"><i class="far fa-user"></i> Profil Saya</a>
                                    <a href="{{ route('cart.index') }}"><i class="fas fa-shopping-bag"></i> Keranjang</a>
                                    <a href="{{ route('transaction.history') }}"><i class="fas fa-history"></i> Riwayat Transaksi</a>
                                @elseif($currentUser->isAdmin())
                                    <a href="{{ route('dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a>
                                    <a href="{{ route('product.index') }}"><i class="fas fa-box"></i> Kelola Produk</a>
                                    <a href="{{ route('category.index') }}"><i class="fas fa-tags"></i> Kategori Produk</a>
                                    <a href="{{ route('transaction.report') }}"><i class="fas fa-chart-line"></i> Laporan Penjualan</a>
                                    <a href="{{ route('profile.index') }}"><i class="far fa-user"></i> Profil Saya</a>
                                @else
                                    <a href="{{ route('cashier.index') }}"><i class="fas fa-cash-register"></i> Transaksi Kasir</a>
                                    <a href="{{ route('profile.index') }}"><i class="far fa-user"></i> Profil Saya</a>
                                @endif
                            </nav>
                            <form action="{{ route('logout') }}" method="POST" class="account-logout">
                                @csrf
                                <button type="submit"><i class="fas fa-sign-out-alt"></i> Keluar</button>
                            </form>
                        </div>
                    </details>
                @else
                    <a href="{{ route('login.index') }}" class="public-login">Masuk</a>
                    <a href="{{ route('register.index') }}" class="public-register">Daftar</a>
                @endauth
            </div>
        </div>
    </header>
    @if(session('success'))<div class="public-container"><div class="public-alert success">{{ session('success') }}</div></div>@endif
    @if(session('error'))<div class="public-container"><div class="public-alert error">{{ session('error') }}</div></div>@endif
    <main>@yield('content')</main>
    <footer class="public-footer">
        <div class="public-container footer-grid">
            <div>
                <a href="{{ route('home') }}" class="public-brand footer-brand"><span>AA</span><div>Atelier Aruna<small>Pakaian Jahitan</small></div></a>
                <p>Pakaian jahitan dengan bahan pilihan, potongan nyaman, dan detail yang dapat disesuaikan dengan kebutuhan Anda.</p>
            </div>
            <div>
                <h4>Navigasi</h4>
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('transaction.index') }}">Katalog Produk</a>
            </div>
            <div><h4>Hubungi Kami</h4><p><i class="fas fa-map-marker-alt"></i> Indonesia</p><p><i class="fab fa-whatsapp"></i> +62 812-0000-0000</p><p><i class="far fa-envelope"></i> halo@atelieraruna.id</p></div>
        </div>
        <div class="public-container footer-bottom">© {{ date('Y') }} Atelier Aruna. Semua hak dilindungi.</div>
    </footer>
</body>
</html>
