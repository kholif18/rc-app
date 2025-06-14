<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Debt;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
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
            ->whereNotIn('status', ['Selesai', 'Diambil']) // opsional, hanya tampilkan yang belum selesai
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

// class DashboardController extends Controller
// {
//     public function index()
//     {
//         try {
//             // 1. Data Hutang Pelanggan
//             $currentCustomerIds = Customer::with('debts.payments')
//                 ->get()
//                 ->filter(function ($customer) {
//                     $totalDebt = $customer->debts->sum('amount');
//                     $totalPaid = $customer->debts->flatMap->payments->sum('amount');
//                     return $totalDebt - $totalPaid > 0;
//                 })
//                 ->pluck('id');

//             $dateKey = 'customers_with_debt_ids_' . now()->toDateString();
//             $previousCustomerIds = cache()->get($dateKey);
            
//             // Hitung selisih pelanggan berhutang
//             $customerDebtDifference = $previousCustomerIds 
//                 ? $currentCustomerIds->count() - collect($previousCustomerIds)->count()
//                 : 0;

//             if (!cache()->has($dateKey)) {
//                 cache()->put($dateKey, $currentCustomerIds, now()->endOfDay());
//             }

//             // 2. Data Pelanggan dengan Hutang
//             $customers = Customer::with('debts.payments')->get();
//             $customersWithDebt = $customers->filter(function($customer) {
//                 $totalDebt = $customer->debts->sum('amount');
//                 $totalPaid = $customer->debts->flatMap->payments->sum('amount');
//                 return $totalDebt - $totalPaid > 0;
//             });

//             // Pagination
//             $page = request()->input('page', 1);
//             $perPage = 10;
//             $offset = ($page - 1) * $perPage;
//             $paginatedCustomers = new LengthAwarePaginator(
//                 $customersWithDebt->slice($offset, $perPage)->values(),
//                 $customersWithDebt->count(),
//                 $perPage,
//                 $page,
//                 ['path' => request()->url(), 'query' => request()->query()]
//             );

//             // 3. Total Hutang
//             $totalDebt = $customers->flatMap->debts->sum('amount');
//             $totalPaid = $customers->flatMap->debts->flatMap->payments->sum('amount');
//             $remainingDebt = $totalDebt - $totalPaid;
//             $customerCount = $customers->count();

//             // 4. Hutang Terbaru
//             $recentDebts = Debt::with('customer')->latest()->take(5)->get()->map(function ($debt) {
//                 return (object)[
//                     'type' => 'debt',
//                     'amount' => $debt->amount,
//                     'note' => $debt->note,
//                     'date' => $debt->created_at,
//                     'customer_name' => $debt->customer->name,
//                 ];
//             });

//             // 5. Order Terbaru
//             $latestOrders = Order::with('customer')->latest()->take(5)->get();
            
//             // 6. Order Terlambat (opsional)
//             $today = Carbon::today();
//             $lateOrders = Order::with('customer')
//                 ->whereDate('deadline', '<=', $today->copy()->addDay())
//                 ->where('status', '!=', 'Selesai')
//                 ->orderBy('deadline', 'asc')
//                 ->take(5)
//                 ->get();
            
//             // 7. Statistik Order per Layanan
//             $timezone = config('app.timezone');
//             $today = Carbon::today($timezone);
//             $yesterday = Carbon::yesterday($timezone);

//             $orders = Order::query()
//                 ->select(
//                     DB::raw('COUNT(*) as count'),
//                     DB::raw('DATE(created_at) as date'),
//                     DB::raw('CASE 
//                         WHEN JSON_CONTAINS(services, \'"Ketik"\') THEN "typing"
//                         WHEN JSON_CONTAINS(services, \'"Desain"\') THEN "design"
//                         WHEN JSON_CONTAINS(services, \'"Cetak"\') THEN "print"
//                         ELSE "other"
//                     END as service_type')
//                 )
//                 ->whereDate('created_at', '>=', $yesterday)
//                 ->whereDate('created_at', '<=', $today)
//                 ->groupBy('date', 'service_type')
//                 ->get()
//                 ->groupBy('date');

//             $serviceStats = [
//                 'typing' => ['today' => 0, 'yesterday' => 0, 'growth' => 0],
//                 'design' => ['today' => 0, 'yesterday' => 0, 'growth' => 0],
//                 'print' => ['today' => 0, 'yesterday' => 0, 'growth' => 0]
//             ];

//             foreach ($orders as $date => $group) {
//                 foreach ($group as $item) {
//                     $targetDate = Carbon::parse($date)->isToday() ? 'today' : 'yesterday';
//                     $serviceStats[$item->service_type][$targetDate] = $item->count;
//                 }
//             }

//             foreach ($serviceStats as $service => &$stats) {
//                 $stats['growth'] = $stats['yesterday'] > 0 
//                     ? (($stats['today'] - $stats['yesterday']) / $stats['yesterday']) * 100
//                     : ($stats['today'] > 0 ? 100 : 0);
//             }

//             $orderStats = [
//                 'typing' => [
//                     'today_count' => $serviceStats['typing']['today'],
//                     'yesterday_count' => $serviceStats['typing']['yesterday'],
//                     'growth_percentage' => round($serviceStats['typing']['growth'], 2)
//                 ],
//                 'design' => [
//                     'today_count' => $serviceStats['design']['today'],
//                     'yesterday_count' => $serviceStats['design']['yesterday'],
//                     'growth_percentage' => round($serviceStats['design']['growth'], 2)
//                 ],
//                 'print' => [
//                     'today_count' => $serviceStats['print']['today'],
//                     'yesterday_count' => $serviceStats['print']['yesterday'],
//                     'growth_percentage' => round($serviceStats['print']['growth'], 2)
//                 ],
//                 'meta' => [
//                     'timezone' => $timezone,
//                     'today' => $today->toDateString(),
//                     'yesterday' => $yesterday->toDateString()
//                 ]
//             ];

//             // 8. Data untuk view
//             return view('dashboard.index', [
//                 'customerDebtDifference' => $customerDebtDifference,
//                 'totalDebt' => $totalDebt,
//                 'totalPaid' => $totalPaid,
//                 'remainingDebt' => $remainingDebt,
//                 'customerCount' => $customerCount,
//                 'paginatedCustomers' => $paginatedCustomers,
//                 'latestOrders' => $latestOrders,
//                 'lateOrders' => $lateOrders,
//                 'recentDebts' => $recentDebts,
//                 'orderStats' => $orderStats,
//                 'typingToday' => $orderStats['typing']['today_count'],
//                 'typingChange' => $orderStats['typing']['today_count'] - $orderStats['typing']['yesterday_count'],
//                 'designToday' => $orderStats['design']['today_count'],
//                 'designChange' => $orderStats['design']['today_count'] - $orderStats['design']['yesterday_count'],
//                 'printToday' => $orderStats['print']['today_count'],
//                 'printChange' => $orderStats['print']['today_count'] - $orderStats['print']['yesterday_count']
//             ]);

//         } catch (Exception $e) {
//             return view('dashboard.index', [
//                 'error' => 'Failed to load dashboard data: ' . $e->getMessage()
//             ]);
//         }
//     }
// }