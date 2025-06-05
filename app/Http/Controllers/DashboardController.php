<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Order;
// use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    public function index()
    {
        
        // Ambil daftar pelanggan yang punya hutang saat ini
        $currentCustomersWithDebt = Customer::whereHas('debts', function ($query) {
            $query->selectRaw('customer_id, SUM(amount) as total_debt')
                ->groupBy('customer_id');
        })->get()->filter(function ($customer) {
            $totalDebt = $customer->debts->sum('amount');
            $totalPaid = $customer->debts->flatMap->payments->sum('amount');
            return $totalDebt - $totalPaid > 0;
        });

        // Misal kamu simpan snapshot data pelanggan berhutang kemarin di session/cache/db (contoh ambil dari cache):
        $previousCustomersWithDebt = cache()->get('previous_customers_with_debt', collect());

        // Hitung jumlah pelanggan berhutang sekarang dan sebelumnya
        $currentCount = $currentCustomersWithDebt->count();
        $previousCount = $previousCustomersWithDebt->count();

        // Hitung selisih perubahan pelanggan berhutang
        $customerDebtDifference = $currentCount - $previousCount;

        // Simpan snapshot untuk perhitungan berikutnya (misal untuk keesokan hari)
        cache()->put('previous_customers_with_debt', $currentCustomersWithDebt, now()->addDay());
        
        $customers = Customer::with('debts.payments')->get();

        // Filter yang punya sisa hutang > 0
        $customersWithDebt = $customers->filter(function($customer) {
            $totalDebt = $customer->debts->sum('amount');
            $totalPaid = $customer->debts->flatMap->payments->sum('amount');
            return $totalDebt - $totalPaid > 0;
        });

        // Manual pagination
        $page = request()->input('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $paginatedCustomers = new LengthAwarePaginator(
            $customersWithDebt->slice($offset, $perPage)->values(),
            $customersWithDebt->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Hitung total hutang, total pembayaran dan sisa hutang (untuk semua customers)
        $totalDebt = $customers->flatMap->debts->sum('amount');
        $totalPaid = $customers->flatMap->debts->flatMap->payments->sum('amount');
        $remainingDebt = $totalDebt - $totalPaid;

        $customerCount = $customers->count();

        // Ambil 5 hutang terbaru
        $recentDebts = Debt::with('customer')->latest()->take(5)->get()->map(function ($debt) {
            return (object)[
                'type' => 'debt',
                'amount' => $debt->amount,
                'note' => $debt->note,
                'date' => $debt->created_at,
                'customer_name' => $debt->customer->name,
            ];
        });

        // Ambil 5 order terakhir
        $latestOrders = Order::with('customer')
            ->latest()
            ->take(5)
            ->get();
            
        // Ambil 5 order terlambat (H-1 sampai lewat deadline)
        $today = Carbon::today();
        $lateOrders = Order::with('customer')
            ->whereDate('deadline', '<=', $today->copy()->addDay()) // H-1 atau lebih
            ->where('status', '!=', 'Selesai') // opsional, hanya tampilkan yang belum selesai
            ->orderBy('deadline', 'asc')
            ->take(5)
            ->get();
            
        // Hitung jumlah order hari ini dan kemarin berdasarkan jenis layanan
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $typingToday = Order::whereJsonContains('services', 'Ketik')
            ->whereDate('created_at', $today)
            ->count();

        $typingYesterday = Order::whereJsonContains('services', 'Ketik')
            ->whereDate('created_at', $yesterday)
            ->count();

        $designToday = Order::whereJsonContains('services', 'Desain')
            ->whereDate('created_at', $today)
            ->count();

        $designYesterday = Order::whereJsonContains('services', 'Desain')
            ->whereDate('created_at', $yesterday)
            ->count();

        $printToday = Order::whereJsonContains('services', 'Cetak')
            ->whereDate('created_at', $today)
            ->count();

        $printYesterday = Order::whereJsonContains('services', 'Cetak')
            ->whereDate('created_at', $yesterday)
            ->count();

        // Selisih perubahan
        $typingChange = $typingToday - $typingYesterday;
        $designChange = $designToday - $designYesterday;
        $printChange = $printToday - $printYesterday;

        return view('dashboard.index', compact(
            'customerDebtDifference',
            'totalDebt',
            'totalPaid',
            'remainingDebt',
            'customerCount',
            'paginatedCustomers',
            'latestOrders',
            'lateOrders',
            'typingToday', 'typingChange',
            'designToday', 'designChange',
            'printToday', 'printChange'
        ));
    }
}
