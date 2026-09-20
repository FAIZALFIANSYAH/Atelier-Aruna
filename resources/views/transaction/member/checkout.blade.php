@extends('layouts.public')

@section('content')
<section class="public-section checkout-page">
    <div class="public-container checkout-container">
        <h1>Checkout</h1>
        <p class="text-muted">Periksa alamat pengiriman dan pilih metode pembayaran.</p>
        <div class="management-card checkout-card mt-4"><div class="management-card-body">
            <h3>Alamat Pengiriman</h3>
            <p><strong>{{ $user->address_recipient }}</strong> · {{ $user->address_phone }}<br>{{ $user->address }}, {{ $user->address_city }}, {{ $user->address_postal_code }}</p>
            <a href="{{ route('profile.index') }}" class="btn btn-light border btn-sm">Ubah alamat di profil</a>
        </div></div>
        <div class="management-card checkout-card mt-3"><div class="management-card-body">
            <h3>Pesanan</h3>
            @php($total = 0)
            @foreach($items as $item)
                @php($product = is_array($item) ? $item['product'] : $item->product)
                @php($quantity = is_array($item) ? $item['quantity'] : $item->quantity)
                @php($total += $product->price * $quantity)
                <div class="d-flex justify-content-between border-bottom py-2"><span>{{ $product->name }} × {{ $quantity }}</span><strong>Rp {{ number_format($product->price * $quantity, 0, ',', '.') }}</strong></div>
            @endforeach
            <div class="d-flex justify-content-between mt-3"><strong>Total</strong><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></div>
        </div></div>
        <form action="{{ route('transaction.confirmCheckout') }}" method="POST" class="management-card checkout-card mt-3"><div class="management-card-body">
            @csrf
            <h3>Metode Pembayaran</h3>
            <select name="payment_method" id="payment_method" class="form-control mb-3" required>
                <option value="">Pilih metode pembayaran</option>
                <option value="bank_transfer">Transfer Bank</option>
                <option value="e_wallet">E-wallet</option>
                <option value="cod">Bayar di Tempat (COD)</option>
            </select>
            <div id="payment-detail" class="payment-detail mb-3" style="display:none">
                <strong id="payment-name"></strong>
                <div class="mt-2">Nomor: <strong id="payment-number"></strong></div>
                <div>Pemilik: <span id="payment-owner"></span></div>
                <small id="payment-note" class="text-muted d-block mt-2"></small>
            </div>
            <button class="btn btn-primary">Buat Pesanan</button>
        </div></form>
    </div>
</section>
<script>
    const paymentData = {
        bank_transfer: { name: 'Bank BCA', number: '1234567890', owner: 'Atelier Aruna', note: 'Silakan transfer sesuai total pesanan.' },
        e_wallet: { name: 'DANA', number: '0812-0000-0000', owner: 'Atelier Aruna', note: 'Gunakan nomor akun dummy ini untuk simulasi pembayaran.' },
        cod: { name: 'Cash on Delivery', number: '-', owner: 'Atelier Aruna', note: 'Pembayaran dilakukan saat pesanan diterima.' }
    };
    document.getElementById('payment_method').addEventListener('change', function () {
        const data = paymentData[this.value];
        const detail = document.getElementById('payment-detail');
        if (!data) { detail.style.display = 'none'; return; }
        document.getElementById('payment-name').textContent = data.name;
        document.getElementById('payment-number').textContent = data.number;
        document.getElementById('payment-owner').textContent = data.owner;
        document.getElementById('payment-note').textContent = data.note;
        detail.style.display = 'block';
    });
</script>
@endsection
