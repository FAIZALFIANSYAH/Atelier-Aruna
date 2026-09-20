@extends('layouts.public')

@section('content')
@php
    $roleName = strtolower($user->role?->name ?? 'member');
    $roleLabel = match ($roleName) {
        'admin' => 'Administrator',
        'cashier' => 'Kasir',
        default => 'Member',
    };
    $homeRoute = match ($roleName) {
        'admin' => route('product.index'),
        'cashier' => route('cashier.index'),
        default => route('home'),
    };
    $savedRegions = ['province' => $user->address_province, 'district' => $user->address_district, 'subdistrict' => $user->address_subdistrict, 'village' => $user->address_village];
@endphp
<section class="profile-page">
    <div class="public-container profile-container">
        <header class="profile-heading">
            <div>
                <span class="public-eyebrow profile-eyebrow">Akun {{ $roleLabel }}</span>
                <h1>Profil Saya</h1>
                <p>Informasi akun yang digunakan di Atelier Aruna.</p>
            </div>
            <a href="{{ $homeRoute }}" class="profile-back"><i class="fas fa-arrow-left"></i> Kembali</a>
        </header>

        <article class="profile-card">
            <div class="profile-cover"></div>
            <div class="profile-overview">
                <div class="profile-avatar">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</div>
                <div class="profile-identity">
                    <div class="profile-title-row"><h2>{{ $user->name }}</h2><span class="profile-role">{{ $roleLabel }}</span></div>
                    <p><i class="far fa-envelope"></i> {{ $user->email }}</p>
                </div>
                <span class="profile-status"><i class="fas fa-circle"></i> Akun aktif</span>
            </div>

            <div class="profile-details">
                <div class="profile-details-heading"><span>Detail akun</span><p>Data dasar untuk akun Anda.</p></div>
                <dl class="profile-info-grid">
                    <div><dt><i class="far fa-user"></i> Nama lengkap</dt><dd>{{ $user->name }}</dd></div>
                    <div><dt><i class="far fa-envelope"></i> Email</dt><dd>{{ $user->email }}</dd></div>
                    <div><dt><i class="far fa-calendar-alt"></i> Bergabung sejak</dt><dd>{{ $user->created_at?->format('d M Y') ?? 'Data tanggal tidak tersedia' }}</dd></div>
                    <div><dt><i class="fas fa-user-shield"></i> Peran akun</dt><dd>{{ $roleLabel }}</dd></div>
                </dl>
                @if($roleName === 'member' && $section !== 'profile')
                    <div class="profile-address-nav mt-4 pt-3 border-top">
                        <a href="{{ route('profile.address', ['section' => 'edit']) }}" class="{{ $section === 'edit' ? 'active' : '' }}"><i class="fas fa-pen"></i> Edit Alamat</a>
                        <a href="{{ route('profile.address', ['section' => 'saved']) }}" class="{{ $section === 'saved' ? 'active' : '' }}"><i class="fas fa-map-marker-alt"></i> Alamat Tersimpan</a>
                    </div>
                    @if($section === 'address' || $section === 'edit')
                    <div class="profile-address-panel">
                        <h3 class="h5">Alamat Pengiriman</h3>
                        <p class="text-muted small">Alamat ini akan digunakan saat checkout.</p>
                        <form action="{{ route('profile.address.update') }}" method="POST" class="profile-address-form">
                            @csrf @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3"><label>Penerima</label><input name="address_recipient" class="form-control" value="{{ old('address_recipient', $user->address_recipient) }}" required></div>
                                <div class="col-md-6 mb-3"><label>No. Telepon</label><input name="address_phone" class="form-control" value="{{ old('address_phone', $user->address_phone) }}" required></div>
                                <div class="col-12 mb-3"><label>Alamat Lengkap</label><textarea name="address" class="form-control" rows="3" required>{{ old('address', $user->address) }}</textarea></div>
                                <div class="col-md-8 mb-3"><label>Provinsi</label><select id="address_province" name="address_province" class="form-control" required><option value="">Pilih provinsi</option></select></div>
                                <div class="col-md-8 mb-3"><label>Kabupaten/Kota</label><select id="address_district" name="address_district" class="form-control" required disabled><option value="">Pilih kabupaten/kota</option></select><input type="hidden" name="address_city" id="address_city" value="{{ old('address_city', $user->address_city) }}"></div>
                                <div class="col-md-8 mb-3"><label>Kecamatan</label><select id="address_subdistrict" name="address_subdistrict" class="form-control" required disabled><option value="">Pilih kecamatan</option></select></div>
                                <div class="col-md-8 mb-3"><label>Desa/Kelurahan</label><select id="address_village" name="address_village" class="form-control" required disabled><option value="">Pilih desa/kelurahan</option></select></div>
                                <div class="col-md-4 mb-3"><label>Kode Pos</label><input name="address_postal_code" class="form-control" value="{{ old('address_postal_code', $user->address_postal_code) }}" required></div>
                            </div>
                            <button class="btn btn-primary">Simpan Alamat</button>
                        </form>
                        <script>
                            const regionUrl = @json(url('/profile/regions'));
                            const saved = {!! json_encode($savedRegions) !!};
                            async function loadRegion(level, parent, target, selected = '') { const el = document.getElementById(target); el.innerHTML = '<option value="">Memuat...</option>'; el.disabled = true; const rows = await fetch(`${regionUrl}/${level}/${parent || ''}`).then(r => r.json()); el.innerHTML = '<option value="">Pilih...</option>' + rows.map(x => `<option value="${x.name}" data-code="${parent ? parent + '|' : ''}${x.code}">${x.name}</option>`).join(''); el.disabled = false; if (selected) { const o = [...el.options].find(x => x.value === selected); if (o) { el.value = selected; el.dispatchEvent(new Event('change')); } } }
                            loadRegion('provinces', '', 'address_province', saved.province);
                            document.getElementById('address_province').onchange = e => loadRegion('districts', e.target.selectedOptions[0]?.dataset.code, 'address_district', saved.district);
                            document.getElementById('address_district').onchange = e => { document.getElementById('address_city').value = e.target.value; loadRegion('subdistricts', e.target.selectedOptions[0]?.dataset.code, 'address_subdistrict', saved.subdistrict); };
                            document.getElementById('address_subdistrict').onchange = e => loadRegion('villages', e.target.selectedOptions[0]?.dataset.code, 'address_village', saved.village);
                        </script>
                    </div>
                    @else
                    <div class="profile-address-panel profile-address-saved">
                        <h3 class="h5">Alamat Tersimpan</h3>
                        @if($user->address && $user->address_recipient)
                            <div class="saved-address-card"><strong>{{ $user->address_recipient }}</strong><span>{{ $user->address_phone }}</span><p>{{ $user->address }}<br>{{ $user->address_village }}, {{ $user->address_subdistrict }}, {{ $user->address_district }}, {{ $user->address_province }} {{ $user->address_postal_code }}</p></div>
                        @else
                            <p class="text-muted">Belum ada alamat tersimpan.</p>
                            <a href="{{ route('profile.address', ['section' => 'edit']) }}" class="btn btn-primary">Tambah Alamat</a>
                        @endif
                    </div>
                    @endif
                @endif
            </div>

            <div class="profile-actions">
                @if($roleName === 'member')
                    <a href="{{ route('transaction.history') }}" class="profile-button profile-button-primary"><i class="fas fa-receipt"></i> Riwayat Transaksi</a>
                    <a href="{{ route('cart.index') }}" class="profile-button"><i class="fas fa-shopping-bag"></i> Lihat Keranjang</a>
                    <a href="{{ route('profile.address') }}" class="profile-button"><i class="fas fa-map-marker-alt"></i> Alamat Pengiriman</a>
                @elseif($roleName === 'admin')
                    <a href="{{ route('dashboard') }}" class="profile-button profile-button-primary"><i class="fas fa-tachometer-alt"></i> Dashboard Admin</a>
                    <a href="{{ route('product.index') }}" class="profile-button"><i class="fas fa-box"></i> Kelola Produk</a>
                @elseif($roleName === 'cashier')
                    <a href="{{ route('cashier.index') }}" class="profile-button profile-button-primary"><i class="fas fa-cash-register"></i> Transaksi Masuk</a>
                @endif
            </div>
        </article>
    </div>
</section>
@endsection
