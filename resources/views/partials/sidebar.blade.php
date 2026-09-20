@php
$user = auth()->user();
@endphp
<aside class="main-sidebar sidebar-light-primary elevation-0">
    <a href="{{ route('home') }}" class="brand-link">
        <span class="sidebar-brand-mark">AA</span>
        <span class="brand-text"><strong>Atelier Aruna</strong><small>Pakaian Jahitan</small></span>
    </a>

    <div class="sidebar sidebar-shell">
        <div class="user-panel d-flex align-items-center">
            <span class="sidebar-user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            <div class="info"><span class="d-block">{{ $user->name }}</span><small>{{ ucfirst($user->role?->name ?? 'member') }}</small></div>
        </div>

        <nav class="mt-2 sidebar-navigation">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                @if($user->isAdmin())
                    <li class="nav-header">UTAMA</li>
                    <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link"><i class="nav-icon fas fa-th-large"></i><p>Dashboard</p></a></li>
                    <li class="nav-item has-treeview menu-open">
                        <a href="#" class="nav-link"><i class="nav-icon fas fa-box-open"></i><p>Produk<i class="right fas fa-angle-left"></i></p></a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a href="{{ route('product.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Daftar Produk</p></a></li>
                            <li class="nav-item"><a href="{{ route('product.create') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Tambah Produk</p></a></li>
                            <li class="nav-item"><a href="{{ route('category.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Kategori</p></a></li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link"><i class="nav-icon fas fa-receipt"></i><p>Penjualan<i class="right fas fa-angle-left"></i></p></a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a href="{{ route('transaction.report') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Laporan Penjualan</p></a></li>
                            <li class="nav-item"><a href="{{ route('cashier.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Kasir</p></a></li>
                        </ul>
                    </li>
                @elseif($user->isCashier())
                    <li class="nav-header">UTAMA</li>
                    <li class="nav-item"><a href="{{ route('cashier.index') }}" class="nav-link"><i class="nav-icon fas fa-cash-register"></i><p>Transaksi Kasir</p></a></li>
                @else
                    <li class="nav-header">BELANJA</li>
                    <li class="nav-item"><a href="{{ route('home') }}" class="nav-link"><i class="nav-icon fas fa-home"></i><p>Beranda</p></a></li>
                    <li class="nav-item"><a href="{{ route('transaction.index') }}" class="nav-link"><i class="nav-icon fas fa-store"></i><p>Katalog Produk</p></a></li>
                    <li class="nav-item has-treeview menu-open">
                        <a href="#" class="nav-link"><i class="nav-icon fas fa-shopping-bag"></i><p>Pesanan Saya<i class="right fas fa-angle-left"></i></p></a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a href="{{ route('cart.index') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Keranjang</p></a></li>
                            <li class="nav-item"><a href="{{ route('transaction.history') }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Riwayat Transaksi</p></a></li>
                        </ul>
                    </li>
                @endif

                <li class="nav-header">AKUN</li>
                <li class="nav-item"><a href="{{ route('profile.index') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"><i class="nav-icon far fa-user"></i><p>Profil Saya</p></a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">@csrf
                <button type="submit" class="nav-link sidebar-logout"><i class="nav-icon fas fa-power-off"></i><p>Keluar</p><i class="sidebar-logout-arrow fas fa-arrow-right"></i></button>
            </form>
        </div>
    </div>
</aside>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const current = window.location.pathname.replace(/\/$/, '') || '/';
    document.querySelectorAll('.main-sidebar a.nav-link[href]').forEach(function (link) {
        const path = new URL(link.href, window.location.origin).pathname.replace(/\/$/, '') || '/';
        if (path === current) {
            link.classList.add('active');
            const parent = link.closest('.has-treeview');
            if (parent) { parent.classList.add('menu-open'); parent.querySelector(':scope > a.nav-link')?.classList.add('active'); }
        }
    });
});
</script>
