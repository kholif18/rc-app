<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Debt;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    public function index()
    {
        $currentCustomerIds = Customer::with('debts.payments')
            ->get()
            ->filter(function ($customer) {
                $totalDebt = $customer->debts->sum('amount');
                $totalPaid = $customer->debts->flatMap->payments->sum('amount');
                return $totalDebt - $totalPaid > 0;
            })
            ->pluck('id');

        $dateKey = 'customers_with_debt_ids_' . now()->toDateString();

        $previousCustomerIds = cache()->get($dateKey);
        // Hitung selisih
        if ($previousCustomerIds) {
            $customerDebtDifference = $currentCustomerIds->count() - collect($previousCustomerIds)->count();
        } else {
            $customerDebtDifference = 0;
            cache()->put($dateKey, $currentCustomerIds, now()->endOfDay());
        }
        // Simpan hanya jika belum tersimpan hari ini
        if (!cache()->has('previous_customers_with_debt_ids')) {
            cache()->put('previous_customers_with_debt_ids', $currentCustomerIds, now()->addDay());
        }
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
            ->whereNotIn('status', ['Selesai', 'Diambil', 'Batal']) // opsional, hanya tampilkan yang belum selesai
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