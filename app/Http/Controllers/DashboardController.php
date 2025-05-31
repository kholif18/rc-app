<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Customer;
// use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    public function index()
    {
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

        // Ambil 5 pembayaran terbaru
        $recentPayments = Payment::with('debt.customer')->latest()->take(5)->get()->map(function ($payment) {
            return (object)[
                'type' => 'payment',
                'amount' => $payment->amount,
                'note' => $payment->note,
                'date' => $payment->payment_date,
                'customer_name' => $payment->debt->customer->name,
            ];
        });

        // Gabungkan dan urutkan aktivitas terbaru
        $recentActivities = $recentDebts->merge($recentPayments)->sortByDesc('date')->take(10);

        return view('dashboard.index', compact(
            'totalDebt',
            'totalPaid',
            'remainingDebt',
            'customerCount',
            'recentActivities',
            'paginatedCustomers'  // ganti dari 'customers' ke 'paginatedCustomers'

        ));
    }
}
