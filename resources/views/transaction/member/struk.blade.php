@extends('layouts.public')

@section('content')
<section class="public-section" style="padding-top: 2rem; padding-bottom: 3rem;">
    <div class="public-container" style="max-width: 720px;">
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a href="{{ route('transaction.history') }}" class="btn btn-light border">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Riwayat
            </a>
            <div>
                <button type="button" onclick="window.print()" class="btn btn-outline-secondary mr-2">
                    <i class="fas fa-print mr-1"></i> Cetak
                </button>
                <a href="{{ route('transaction.struk.download', $transaction) }}" class="btn btn-primary">
                    <i class="fas fa-file-pdf mr-1"></i> Download PDF
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="public-alert success no-print mb-3">{{ session('success') }}</div>
        @endif

        <div class="management-card struk-card" id="struk-content">
            <div class="management-card-body" style="padding: 2rem;">
                {{-- Header --}}
                <div class="text-center mb-4" style="border-bottom: 2px dashed #ddd; padding-bottom: 1.25rem;">
                    <div style="font-size: 1.75rem; font-weight: 700; letter-spacing: 1px;">Atelier Aruna</div>
                    <div class="text-muted" style="font-size: 0.9rem;">Pakaian Jahitan</div>
                    <div class="text-muted mt-1" style="font-size: 0.85rem;">Indonesia · halo@atelieraruna.id</div>
                    <div class="mt-2" style="font-size: 1.1rem; font-weight: 600;">STRUK TRANSAKSI</div>
                </div>

                {{-- Info Transaksi --}}
                <div class="row mb-3" style="font-size: 0.95rem;">
                    <div class="col-6">
                        <div class="mb-1"><span class="text-muted">Kode</span><br><strong>{{ $transaction->transaction_code }}</strong></div>
                        <div class="mb-1"><span class="text-muted">Tanggal</span><br><strong>{{ $transaction->created_at->format('d M Y, H:i') }}</strong></div>
                        <div><span class="text-muted">Status</span><br>
                            <span class="status-pill status-{{ $transaction->status }}">{{ ucfirst($transaction->status) }}</span>
                        </div>
                    </div>
                    <div class="col-6 text-right">
                        <div class="mb-1"><span class="text-muted">Metode Bayar</span><br>
                            <strong>{{ $transaction->payment_method ? ucwords(str_replace('_', ' ', $transaction->payment_method)) : '-' }}</strong>
                        </div>
                        @if($transaction->cashier)
                            <div><span class="text-muted">Diproses oleh</span><br><strong>{{ $transaction->cashier->name }}</strong></div>
                        @endif
                    </div>
                </div>

                {{-- Penerima --}}
                @if($transaction->shipping_recipient)
                <div class="mb-3" style="background: #f8f9fa; border-radius: 8px; padding: 0.85rem 1rem; font-size: 0.9rem;">
                    <div class="text-muted mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Alamat Pengiriman</div>
                    <strong>{{ $transaction->shipping_recipient }}</strong>
                    @if($transaction->shipping_phone)
                        · {{ $transaction->shipping_phone }}
                    @endif
                    <br>
                    {{ $transaction->shipping_address }}
                    @if($transaction->shipping_city)
                        , {{ $transaction->shipping_city }}
                    @endif
                    @if($transaction->shipping_postal_code)
                        {{ $transaction->shipping_postal_code }}
                    @endif
                </div>
                @endif

                {{-- Items --}}
                <table class="table table-sm" style="font-size: 0.95rem; margin-bottom: 0;">
                    <thead>
                        <tr style="border-top: 1px solid #eee; border-bottom: 1px solid #eee;">
                            <th style="border: none; padding: 0.6rem 0;">Produk</th>
                            <th style="border: none; padding: 0.6rem 0; text-align: center;">Qty</th>
                            <th style="border: none; padding: 0.6rem 0; text-align: right;">Harga</th>
                            <th style="border: none; padding: 0.6rem 0; text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->transactionDetails as $detail)
                        <tr>
                            <td style="border: none; padding: 0.5rem 0; vertical-align: top;">
                                {{ $detail->product->name ?? 'Produk tidak tersedia' }}
                            </td>
                            <td style="border: none; padding: 0.5rem 0; text-align: center; vertical-align: top;">
                                {{ $detail->quantity }}
                            </td>
                            <td style="border: none; padding: 0.5rem 0; text-align: right; vertical-align: top;">
                                Rp {{ number_format($detail->price, 0, ',', '.') }}
                            </td>
                            <td style="border: none; padding: 0.5rem 0; text-align: right; vertical-align: top;">
                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="border-top: 2px solid #333;">
                            <th colspan="3" style="border: none; padding: 0.85rem 0; text-align: right;">Total</th>
                            <th style="border: none; padding: 0.85rem 0; text-align: right; font-size: 1.15rem;">
                                Rp {{ number_format($transaction->transactionDetails->sum('subtotal'), 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>

                <div class="text-center text-muted mt-4" style="font-size: 0.85rem; border-top: 2px dashed #ddd; padding-top: 1.25rem;">
                    Terima kasih telah berbelanja di Atelier Aruna.<br>
                    Simpan struk ini sebagai bukti transaksi Anda.
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    @media print {
        .no-print,
        .public-header,
        .public-footer,
        .public-alert {
            display: none !important;
        }
        body {
            background: #fff !important;
        }
        .public-section {
            padding: 0 !important;
        }
        .struk-card {
            box-shadow: none !important;
            border: none !important;
        }
        #struk-content {
            max-width: 100%;
        }
    }

    .struk-card {
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border-radius: 12px;
        overflow: hidden;
    }
</style>
@endsection
