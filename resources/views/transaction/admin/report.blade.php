@extends('layouts.app')

@section('content')
<div class="management-page">
    <div class="management-shell">
        <div class="management-heading">
            <div>
                <h1>Laporan Penjualan</h1>
                <p>Ringkasan penjualan berhasil berdasarkan periode yang dipilih.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('transaction.report') }}" class="report-filter">
            <div class="report-filter-period">
                <label for="period">Periode laporan</label>
                <select id="period" name="period" class="form-control">
                    @foreach($periods as $value => $label)
                        <option value="{{ $value }}" @selected($period === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="report-filter-date">
                <label for="start_date">Mulai</label>
                <input id="start_date" type="date" name="start_date" value="{{ $startDateInput }}" class="form-control">
            </div>
            <div class="report-filter-date">
                <label for="end_date">Sampai</label>
                <input id="end_date" type="date" name="end_date" value="{{ $endDateInput }}" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i> Terapkan</button>
        </form>
        @error('start_date')<div class="text-danger report-filter-error">{{ $message }}</div>@enderror
        @error('end_date')<div class="text-danger report-filter-error">{{ $message }}</div>@enderror

        <div class="report-summary-grid">
            <div class="summary-card">
                <span class="summary-icon"><i class="fas fa-wallet"></i></span>
                <span class="summary-label">Penghasilan {{ strtolower($periodLabel) }}</span>
                <div class="summary-value summary-currency">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
                <small class="report-summary-note">Hanya transaksi completed</small>
            </div>
            <div class="summary-card">
                <span class="summary-icon"><i class="fas fa-receipt"></i></span>
                <span class="summary-label">Transaksi berhasil</span>
                <div class="summary-value">{{ $transactionCount }}</div>
            </div>
            <div class="summary-card">
                <span class="summary-icon"><i class="fas fa-box"></i></span>
                <span class="summary-label">Produk terjual</span>
                <div class="summary-value">{{ $unitsSold }}</div>
            </div>
            <div class="summary-card">
                <span class="summary-icon"><i class="fas fa-calculator"></i></span>
                <span class="summary-label">Rata-rata transaksi</span>
                <div class="summary-value summary-currency">Rp {{ number_format($averageOrder, 0, ',', '.') }}</div>
            </div>
        </div>

        <section class="report-chart-card management-card">
            <div class="management-card-head">
                <div><h2>Tren Penghasilan</h2><small class="report-card-subtitle">{{ $periodLabel }} · transaksi completed</small></div>
                <span class="category-count">Rp {{ number_format($revenue, 0, ',', '.') }}</span>
            </div>
            <div class="report-chart">
                @foreach($chartValues as $key => $value)
                    @php($height = $value > 0 ? max(8, ($value / $chartMax) * 100) : 3)
                    <div class="report-chart-column" title="{{ $chartLabels[$loop->index] }}: Rp {{ number_format($value, 0, ',', '.') }}">
                        <div class="report-chart-bar-wrap"><span class="report-chart-bar" style="height: {{ $height }}%"></span></div>
                        <span class="report-chart-label">{{ $chartLabels[$loop->index] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="report-analysis-grid">
            <section class="management-card">
                <div class="management-card-head"><div><h2>Produk Terlaris</h2><small class="report-card-subtitle">Diurutkan berdasarkan penghasilan</small></div></div>
                <div class="report-ranking-list">
                    @forelse($topProducts as $item)
                        <div class="report-ranking-row"><span class="report-ranking-index">{{ $loop->iteration }}</span><span class="report-ranking-name">{{ $item['name'] }}<small>{{ $item['units'] }} produk terjual</small></span><strong>Rp {{ number_format($item['revenue'], 0, ',', '.') }}</strong></div>
                    @empty
                        <div class="empty-state">Belum ada data produk terjual.</div>
                    @endforelse
                </div>
            </section>
            <section class="management-card">
                <div class="management-card-head"><div><h2>Kategori Terlaris</h2><small class="report-card-subtitle">Diurutkan berdasarkan penghasilan</small></div></div>
                <div class="report-ranking-list">
                    @forelse($topCategories as $item)
                        <div class="report-ranking-row"><span class="report-ranking-index">{{ $loop->iteration }}</span><span class="report-ranking-name">{{ $item['name'] }}<small>{{ $item['units'] }} produk terjual</small></span><strong>Rp {{ number_format($item['revenue'], 0, ',', '.') }}</strong></div>
                    @empty
                        <div class="empty-state">Belum ada data kategori terjual.</div>
                    @endforelse
                </div>
            </section>
        </div>

        <section class="management-card">
            <div class="management-card-head"><h2>Riwayat Penjualan Berhasil</h2><span class="category-count">{{ $transactions->total() }} transaksi</span></div>
            <table class="table modern-table">
                <thead><tr><th>Kode Transaksi</th><th>Pelanggan</th><th>Status</th><th>Total</th><th>Tanggal</th></tr></thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr><td><strong>{{ $transaction->transaction_code }}</strong></td><td>{{ $transaction->member->name }}</td><td><span class="status-pill status-completed">Completed</span></td><td><strong>Rp {{ number_format($transaction->transactionDetails->sum('subtotal'), 0, ',', '.') }}</strong></td><td>{{ $transaction->created_at->format('d M Y, H:i') }}</td></tr>
                    @empty
                        <tr><td colspan="5"><div class="empty-state"><i class="fas fa-chart-line"></i>Belum ada transaksi completed pada periode ini.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>
        <div class="mt-3">{{ $transactions->links() }}</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const period = document.getElementById('period');
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');

        [startDate, endDate].forEach(function (input) {
            input?.addEventListener('change', function () {
                if (input.value && period) period.value = 'custom';
            });
        });
    });
</script>
@endpush
