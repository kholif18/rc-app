<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::with('debts.payments')->get();

        $customersLunas = $customers->filter(function ($customer) {
            $totalDebt = $customer->debts->sum('amount');
            $totalPaid = $customer->debts->flatMap->payments->sum('amount');
            return $totalDebt > 0 && ($totalDebt - $totalPaid) <= 0;
        });

        $page = request()->input('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $paginatedCustomers = new \Illuminate\Pagination\LengthAwarePaginator(
            $customersLunas->slice($offset, $perPage)->values(),
            $customersLunas->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('payments.index', compact('paginatedCustomers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $debt = Debt::with('customer', 'payments')->findOrFail($request->debt_id);

        // Ambil history hutang + pembayaran gabungan untuk customer ini
        $history = $debt->customer->debtAndPaymentHistory();
        
        return view('payments.create', compact('debt', 'history'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'debt_id' => 'required|exists:debts,id',
            'amount' => 'required|integer|min:1',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        // Simpan pembayaran baru
        Payment::create([
            'debt_id' => $request->debt_id,
            'customer_id' => Debt::find($request->debt_id)->customer_id,
            'amount' => $request->amount,
            'note' => $request->note,
            'payment_date' => now(),
        ]);

        return redirect()->route('debts.index')->with('success', 'Pembayaran berhasil dicatat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    public function detail(Request $request)
    {
        $debt = Debt::with('customer', 'payments')->findOrFail($request->debt_id);
        $history = $debt->customer->debtAndPaymentHistory();

        return view('payments.detail', compact('debt', 'history'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
