@extends('layouts.public')

@section('content')
<section class="public-section struk-page">
    <div class="public-container struk-page-container">
        <div class="struk-actions no-print">
            <a href="{{ route('transaction.history') }}" class="btn-struk btn-struk-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
            </a>
            <div class="struk-actions-right">
                <button type="button" onclick="window.print()" class="btn-struk btn-struk-print">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <a href="{{ route('transaction.struk.download', $transaction) }}" class="btn-struk btn-struk-pdf">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="public-alert success no-print mb-3">{{ session('success') }}</div>
        @endif

        <div class="struk-sheet" id="struk-content">
            {{-- Header --}}
            <div class="struk-header">
                <div class="struk-brand">Atelier Aruna</div>
                <div class="struk-subtitle">Pakaian Jahitan</div>
                <div class="struk-contact">Indonesia · halo@atelieraruna.id</div>
                <div class="struk-title">STRUK TRANSAKSI</div>
            </div>

            {{-- Meta --}}
            <div class="struk-meta">
                <div class="struk-meta-left">
                    <div class="struk-meta-item">
                        <span class="struk-label">Kode</span>
                        <span class="struk-value">{{ $transaction->transaction_code }}</span>
                    </div>
                    <div class="struk-meta-item">
                        <span class="struk-label">Tanggal</span>
                        <span class="struk-value">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if($transaction->cashier)
                    <div class="struk-meta-item">
                        <span class="struk-label">Diproses oleh</span>
                        <span class="struk-value">{{ $transaction->cashier->name }}</span>
                    </div>
                    @endif
                </div>
                <div class="struk-meta-right">
                    <div class="struk-meta-item struk-meta-item-right">
                        <span class="struk-label">Metode Bayar</span>
                        <span class="struk-value">{{ $transaction->payment_method ? ucwords(str_replace('_', ' ', $transaction->payment_method)) : '-' }}</span>
                    </div>
                    <div class="struk-meta-item struk-meta-item-right">
                        <span class="struk-label">Status</span>
                        <span class="status-pill status-{{ $transaction->status }}">{{ strtoupper($transaction->status) }}</span>
                    </div>
                </div>
            </div>

            {{-- Alamat --}}
            @if($transaction->shipping_recipient)
            <div class="struk-address">
                <div class="struk-label">Alamat Pengiriman</div>
                <div class="struk-address-body">
                    <strong>{{ $transaction->shipping_recipient }}</strong>
                    @if($transaction->shipping_phone)
                        · {{ $transaction->shipping_phone }}
                    @endif
                    <br>
                    {{ $transaction->shipping_address }}
                    @if($transaction->shipping_city), {{ $transaction->shipping_city }}@endif
                    @if($transaction->shipping_postal_code) {{ $transaction->shipping_postal_code }}@endif
                </div>
            </div>
            @endif

            {{-- Items --}}
            <table class="struk-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="col-qty">Qty</th>
                        <th class="col-price">Harga</th>
                        <th class="col-subtotal">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->transactionDetails as $detail)
                    <tr>
                        <td>{{ $detail->product->name ?? 'Produk tidak tersedia' }}</td>
                        <td class="col-qty">{{ $detail->quantity }}</td>
                        <td class="col-price">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td class="col-subtotal">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="col-total-label">Total</th>
                        <th class="col-total-value">Rp {{ number_format($transaction->transactionDetails->sum('subtotal'), 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>

            <div class="struk-footer">
                Terima kasih telah berbelanja di Atelier Aruna.<br>
                Simpan struk ini sebagai bukti transaksi Anda.
            </div>
        </div>
    </div>
</section>

<style>
    .struk-page {
        padding: 2rem 0 3.5rem;
        background: #f4f5f9;
    }
    .struk-page-container {
        max-width: 680px;
    }

    /* Actions */
    .struk-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }
    .struk-actions-right {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .btn-struk {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1.1rem;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        line-height: 1.3;
        transition: opacity 0.15s ease;
    }
    .btn-struk:hover { opacity: 0.9; text-decoration: none; }
    .btn-struk-back {
        background: #5b5fc7;
        color: #fff;
    }
    .btn-struk-back:hover { color: #fff; }
    .btn-struk-print {
        background: #fff;
        color: #333;
        border: 1px solid #d0d4dd;
    }
    .btn-struk-pdf {
        background: #5b5fc7;
        color: #fff;
    }
    .btn-struk-pdf:hover { color: #fff; }

    /* Sheet */
    .struk-sheet {
        background: #fff;
        border-radius: 14px;
        padding: 2.25rem 2.5rem;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        color: #222;
    }

    /* Header */
    .struk-header {
        text-align: center;
        border-bottom: 2px dashed #ddd;
        padding-bottom: 1.15rem;
        margin-bottom: 1.35rem;
    }
    .struk-brand {
        font-size: 1.65rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #111;
    }
    .struk-subtitle {
        font-size: 0.9rem;
        color: #666;
        margin-top: 0.15rem;
    }
    .struk-contact {
        font-size: 0.82rem;
        color: #888;
        margin-top: 0.15rem;
    }
    .struk-title {
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        margin-top: 0.85rem;
        text-transform: uppercase;
        color: #111;
    }

    /* Meta */
    .struk-meta {
        display: flex;
        justify-content: space-between;
        gap: 1.5rem;
        margin-bottom: 1.15rem;
    }
    .struk-meta-left,
    .struk-meta-right {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }
    .struk-meta-item-right {
        text-align: right;
        align-items: flex-end;
    }
    .struk-meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }
    .struk-label {
        font-size: 0.75rem;
        color: #888;
        text-transform: none;
        letter-spacing: 0.2px;
    }
    .struk-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: #111;
    }

    /* Address */
    .struk-address {
        background: #f5f6f8;
        border-radius: 8px;
        padding: 0.85rem 1rem;
        margin-bottom: 1.25rem;
    }
    .struk-address .struk-label {
        text-transform: uppercase;
        font-size: 0.72rem;
        letter-spacing: 0.4px;
        margin-bottom: 0.35rem;
        display: block;
    }
    .struk-address-body {
        font-size: 0.9rem;
        line-height: 1.45;
        color: #222;
    }

    /* Table */
    .struk-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.92rem;
        margin-bottom: 0.25rem;
    }
    .struk-table thead th {
        text-align: left;
        padding: 0.65rem 0.35rem;
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        font-size: 0.78rem;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .struk-table tbody td {
        padding: 0.7rem 0.35rem;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: top;
        color: #222;
    }
    .struk-table .col-qty {
        text-align: center;
        width: 52px;
    }
    .struk-table .col-price,
    .struk-table .col-subtotal {
        text-align: right;
        white-space: nowrap;
    }
    .struk-table .col-price { width: 110px; }
    .struk-table .col-subtotal { width: 120px; }
    .struk-table tfoot th {
        padding: 0.95rem 0.35rem 0.35rem;
        border-top: 2px solid #222;
        font-size: 1rem;
    }
    .struk-table .col-total-label {
        text-align: right;
        font-weight: 600;
        color: #333;
    }
    .struk-table .col-total-value {
        text-align: right;
        font-weight: 700;
        font-size: 1.1rem;
        color: #111;
        white-space: nowrap;
    }

    /* Footer */
    .struk-footer {
        text-align: center;
        color: #888;
        font-size: 0.82rem;
        border-top: 2px dashed #ddd;
        padding-top: 1.15rem;
        margin-top: 1.35rem;
        line-height: 1.5;
    }

    /* Status pill tweak on struk */
    .struk-sheet .status-pill {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        padding: 0.2rem 0.65rem;
        border-radius: 999px;
    }

    @media (max-width: 576px) {
        .struk-sheet { padding: 1.5rem 1.25rem; }
        .struk-meta { flex-direction: column; gap: 0.85rem; }
        .struk-meta-item-right { text-align: left; align-items: flex-start; }
        .struk-actions { flex-direction: column; align-items: stretch; }
        .struk-actions-right { width: 100%; }
        .btn-struk { justify-content: center; flex: 1; }
    }

    @media print {
        .no-print,
        .public-header,
        .public-footer,
        .public-alert {
            display: none !important;
        }
        body, .struk-page {
            background: #fff !important;
        }
        .struk-page {
            padding: 0 !important;
        }
        .struk-sheet {
            box-shadow: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
            max-width: 100%;
        }
        .struk-page-container {
            max-width: 100%;
        }
    }
</style>
@endsection
