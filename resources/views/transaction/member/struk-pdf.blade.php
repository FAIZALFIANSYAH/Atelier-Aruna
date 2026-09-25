<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk {{ $transaction->transaction_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            line-height: 1.45;
            padding: 28px 32px;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #ccc;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }
        .brand {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .subtitle { color: #666; font-size: 11px; margin-top: 2px; }
        .title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .meta {
            width: 100%;
            margin-bottom: 16px;
        }
        .meta td {
            vertical-align: top;
            padding: 3px 0;
        }
        .meta .label { color: #777; font-size: 10px; }
        .meta .value { font-weight: bold; }
        .address-box {
            background: #f5f5f5;
            padding: 10px 12px;
            margin-bottom: 16px;
            border-radius: 4px;
        }
        .address-box .label {
            color: #777;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 4px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        table.items th {
            text-align: left;
            padding: 8px 4px;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
            color: #555;
        }
        table.items th.right, table.items td.right { text-align: right; }
        table.items th.center, table.items td.center { text-align: center; }
        table.items td {
            padding: 7px 4px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }
        table.items tfoot th {
            border-top: 2px solid #222;
            border-bottom: none;
            padding-top: 12px;
            font-size: 13px;
        }
        .footer {
            text-align: center;
            color: #777;
            font-size: 10px;
            border-top: 2px dashed #ccc;
            padding-top: 14px;
            margin-top: 20px;
        }
        .status {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">Atelier Aruna</div>
        <div class="subtitle">Pakaian Jahitan</div>
        <div class="subtitle">Indonesia · halo@atelieraruna.id</div>
        <div class="title">Struk Transaksi</div>
    </div>

    <table class="meta">
        <tr>
            <td width="50%">
                <div class="label">Kode</div>
                <div class="value">{{ $transaction->transaction_code }}</div>
            </td>
            <td width="50%" style="text-align: right;">
                <div class="label">Metode Bayar</div>
                <div class="value">{{ $transaction->payment_method ? ucwords(str_replace('_', ' ', $transaction->payment_method)) : '-' }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="label">Tanggal</div>
                <div class="value">{{ $transaction->created_at->format('d M Y, H:i') }}</div>
            </td>
            <td style="text-align: right;">
                <div class="label">Status</div>
                <div class="value">
                    <span class="status status-{{ $transaction->status }}">{{ ucfirst($transaction->status) }}</span>
                </div>
            </td>
        </tr>
        @if($transaction->cashier)
        <tr>
            <td colspan="2">
                <div class="label">Diproses oleh</div>
                <div class="value">{{ $transaction->cashier->name }}</div>
            </td>
        </tr>
        @endif
    </table>

    @if($transaction->shipping_recipient)
    <div class="address-box">
        <div class="label">Alamat Pengiriman</div>
        <strong>{{ $transaction->shipping_recipient }}</strong>
        @if($transaction->shipping_phone)
            · {{ $transaction->shipping_phone }}
        @endif
        <br>
        {{ $transaction->shipping_address }}
        @if($transaction->shipping_city), {{ $transaction->shipping_city }}@endif
        @if($transaction->shipping_postal_code) {{ $transaction->shipping_postal_code }}@endif
    </div>
    @endif

    <table class="items">
        <thead>
            <tr>
                <th>Produk</th>
                <th class="center" width="50">Qty</th>
                <th class="right" width="100">Harga</th>
                <th class="right" width="110">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->transactionDetails as $detail)
            <tr>
                <td>{{ $detail->product->name ?? 'Produk tidak tersedia' }}</td>
                <td class="center">{{ $detail->quantity }}</td>
                <td class="right">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                <td class="right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="right">Total</th>
                <th class="right">Rp {{ number_format($transaction->transactionDetails->sum('subtotal'), 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Terima kasih telah berbelanja di Atelier Aruna.<br>
        Simpan struk ini sebagai bukti transaksi Anda.
    </div>
</body>
</html>
