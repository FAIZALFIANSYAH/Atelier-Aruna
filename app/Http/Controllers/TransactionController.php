<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Http\Requests\Transaction\TransactionRequest;
use App\Http\Requests\Transaction\CheckoutRequest;
use App\Models\StockHistory;
use App\Models\Category;
use App\Services\TransactionService;
use App\Services\CheckoutService;
use App\Services\ProductService;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function __construct(private TransactionService $transactionService, private CheckoutService $checkoutService, private ProductService $productService)
    {
        // throw new \Exception('Not implemented');
    }
    //
    public function index(Request $request)
    {
        //tampilkan seluruh product
        $product = $this->productService->getFilteredProducts($request->only(['name', 'category']));
        $categories = Category::orderBy('name')->get();

        //tampilkan halaman 
        return view('transaction.member.index', compact('product', 'categories'));
    }

    public function home()
    {
        $categories = Category::withCount('product')->orderBy('name')->get();
        $featuredProducts = Product::with('category')->latest()->take(4)->get();

        return view('transaction.member.home', compact('categories', 'featuredProducts'));
    }

    public function checkoutCart(Request $request)
    {
        $this->authorize('create', Transaction::class);
        if (!$this->checkoutService->beginCart(Auth::user())) return redirect()->back()->with('error', 'Keranjang masih kosong');
        return redirect()->route('transaction.checkout');
    }

    public function checkout()
    {
        $pending = session('pending_checkout');
        abort_unless($pending, 404);
        $user = Auth::user();
        if (!$this->checkoutService->ensureAddress($user)) {
            return redirect()->route('profile.index')->with('error', 'Lengkapi alamat terlebih dahulu sebelum membeli.');
        }
        $items = $this->checkoutService->items($user, $pending);
        return view('transaction.member.checkout', compact('items', 'user'));
    }

    public function confirmCheckout(CheckoutRequest $request)
    {
        $this->authorize('create', Transaction::class);
        $data = $request->validated();
        $user = Auth::user();
        if (!$this->checkoutService->ensureAddress($user)) {
            return redirect()->route('profile.index')->with('error', 'Lengkapi alamat terlebih dahulu sebelum membeli.');
        }
        try {
            $pending = session('pending_checkout');
            $transaction = $this->checkoutService->complete($user, $pending, $data['payment_method']);
            session()->forget('pending_checkout');
            return redirect()->route('transaction.struk', $transaction)->with('success', 'Checkout berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function struk(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        $transaction->load(['transactionDetails.product', 'member', 'cashier']);
        return view('transaction.member.struk', compact('transaction'));
    }

    public function downloadStruk(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        $transaction->load(['transactionDetails.product', 'member', 'cashier']);

        $pdf = Pdf::loadView('transaction.member.struk-pdf', compact('transaction'))
            ->setPaper('a4', 'portrait');

        $filename = 'struk-' . ($transaction->transaction_code ?? $transaction->id) . '.pdf';

        return $pdf->download($filename);
    }

    public function dashboard()
    {
        $recentTransactions = Transaction::with('member')->latest()->take(6)->get();
        $lowStockProducts = Product::with('category')->get()->filter(fn ($product) => $product->current_stock <= 5)->take(5);
        $data = [
            'productCount' => Product::count(),
            'transactionCount' => Transaction::count(),
            'pendingCount' => Transaction::where('status', 'pending')->count(),
            'completedCount' => Transaction::where('status', 'completed')->count(),
            'categoryCount' => Category::count(),
            'revenue' => TransactionDetail::sum('subtotal'),
        ];
        return view('dashboard.index', compact('data', 'recentTransactions', 'lowStockProducts'));
    }

    public function report(Request $request)
    {
        $periods = [
            'all' => 'Semua data',
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
            'custom' => 'Rentang tanggal',
        ];
        $period = $request->query('period', 'all');
        if (!array_key_exists($period, $periods)) {
            $period = 'all';
        }

        $startDateInput = $request->query('start_date');
        $endDateInput = $request->query('end_date');
        // Jika tanggal diisi, perlakukan filter sebagai rentang tanggal meskipun
        // pilihan periode masih berada pada opsi default "Semua data".
        if ($period !== 'custom' && (filled($startDateInput) || filled($endDateInput))) {
            $period = 'custom';
        }
        $start = null;
        $end = null;
        if ($period === 'daily') {
            $start = now()->startOfDay();
            $end = now()->endOfDay();
        } elseif ($period === 'weekly') {
            $start = now()->startOfWeek(Carbon::MONDAY);
            $end = now()->endOfWeek(Carbon::SUNDAY);
        } elseif ($period === 'monthly') {
            $start = now()->startOfMonth();
            $end = now()->endOfMonth();
        } elseif ($period === 'yearly') {
            $start = now()->startOfYear();
            $end = now()->endOfYear();
        } elseif ($period === 'custom') {
            $validated = $request->validate([
                'start_date' => ['required', 'date'],
                'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            ]);
            $start = Carbon::parse($validated['start_date'])->startOfDay();
            $end = Carbon::parse($validated['end_date'])->endOfDay();
        }

        $completedTransactionFilter = function ($query) use ($start, $end) {
            $query->where('status', 'completed')
                ->when($start && $end, fn ($query) => $query->whereBetween('created_at', [$start, $end]));
        };

        $transactionsQuery = Transaction::with(['member', 'transactionDetails.product'])
            ->where($completedTransactionFilter)
            ->latest();
        $reportDetails = TransactionDetail::with(['transaction', 'product.category'])
            ->whereHas('transaction', $completedTransactionFilter)
            ->get();

        $transactionCount = (clone $transactionsQuery)->count();
        $revenue = $reportDetails->sum('subtotal');
        $unitsSold = $reportDetails->sum('quantity');
        $averageOrder = $transactionCount > 0 ? $revenue / $transactionCount : 0;
        $transactions = $transactionsQuery->paginate(10)->withQueryString();
        $periodLabel = $periods[$period];

        $topProducts = $reportDetails->groupBy(fn ($detail) => $detail->product?->id ?? 0)
            ->map(fn ($items) => [
                'name' => $items->first()->product?->name ?? 'Produk tidak tersedia',
                'units' => $items->sum('quantity'),
                'revenue' => $items->sum('subtotal'),
            ])
            ->sortByDesc('revenue')
            ->take(5)
            ->values();
        $topCategories = $reportDetails->groupBy(fn ($detail) => $detail->product?->category?->id ?? 0)
            ->map(fn ($items) => [
                'name' => $items->first()->product?->category?->name ?? 'Kategori tidak tersedia',
                'units' => $items->sum('quantity'),
                'revenue' => $items->sum('subtotal'),
            ])
            ->sortByDesc('revenue')
            ->take(5)
            ->values();

        $chartMode = in_array($period, ['yearly', 'all'], true) ? 'month' : 'day';
        if ($period === 'custom' && $start->diffInDays($end) > 31) {
            $chartMode = 'month';
        }
        $chartStart = $start?->copy() ?? now()->subMonths(11)->startOfMonth();
        $chartEnd = $end?->copy() ?? now()->endOfMonth();
        if ($chartMode === 'month') {
            $chartStart->startOfMonth();
            $chartEnd->endOfMonth();
        }
        $chartLabels = [];
        $chartKeys = [];
        for ($cursor = $chartStart->copy(); $cursor->lte($chartEnd); $cursor = $chartMode === 'month' ? $cursor->addMonth() : $cursor->addDay()) {
            $chartKeys[] = $chartMode === 'month' ? $cursor->format('Y-m') : $cursor->format('Y-m-d');
            $chartLabels[] = $chartMode === 'month' ? $cursor->format('M Y') : $cursor->format('d M');
        }
        $chartValues = array_fill_keys($chartKeys, 0);
        foreach ($reportDetails as $detail) {
            $date = Carbon::parse($detail->transaction->created_at);
            $key = $chartMode === 'month' ? $date->format('Y-m') : $date->format('Y-m-d');
            if (array_key_exists($key, $chartValues)) {
                $chartValues[$key] += (float) $detail->subtotal;
            }
        }
        $chartMax = max($chartValues ?: [0]) ?: 1;

        return view('transaction.admin.report', compact(
            'transactions',
            'periods',
            'period',
            'periodLabel',
            'transactionCount',
            'revenue',
            'unitsSold',
            'averageOrder',
            'startDateInput',
            'endDateInput',
            'topProducts',
            'topCategories',
            'chartLabels',
            'chartValues',
            'chartMax'
        ));
    }

    public function store(TransactionRequest $request)
    {
        $this->authorize('create', Transaction::class);
        try {
            $this->checkoutService->beginSingle(Product::findOrFail($request->product_id), (int) $request->quantity);
            return redirect()->route('transaction.checkout');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function history()
    {
        $user = Auth::user();

        $transaction = Transaction::where('member_id', $user->id)->with('transactionDetails.product')->latest()->get();

        return view('transaction.member.history', compact('transaction'));
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        $transaction->load(['transactionDetails.product', 'cashier']);
        return view('transaction.member.show', compact('transaction'));
    }

    public function cancel(Transaction $transaction)
    {
        $this->authorize('cancel', $transaction);

        if ($transaction->status !== 'pending') {
            return redirect()->back();
        }

        foreach ($transaction->transactionDetails as $detail) {
            StockHistory::create([
                'product_id' => $detail->product_id,
                'user_id' => Auth::id(),
                'type' => 'in',
                'quantity' => $detail->quantity,
                'description' => 'member membatalkan transaksi',
            ]);
        }

        $transaction->status = 'cancelled';
        $transaction->save();

        return redirect()->back();
    }

    //cashier

    public function pending()
    {
        $transactions = Transaction::where('status', 'pending')->get();

        return view('transaction.cashier.index',compact('transactions'));
    }


    public function process(Transaction $transaction)
    {
        $this->authorize('complete', $transaction);

        if ($transaction->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi sudah diproses atau dibatalkan');
        }

        $transaction->handled_by = Auth::id();
        $transaction->status = 'completed';
        $transaction->save();

        return redirect()->back()->with('success', 'Transaksi berhasil diproses');
    }
}
