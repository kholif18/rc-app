<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;


class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = 10;
        $page = $request->input('page', 1);

        // Ambil customer yang punya hutang dan sesuai filter
        $query = Customer::whereHas('debts')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->with('debts.payments')
            ->get();

        // Hitung total hutang dikurangi pembayaran
        $filtered = $query->map(function ($customer) {
            $totalDebt = $customer->debts->sum('amount');
            $totalPaid = $customer->debts->flatMap->payments->sum('amount');
            $customer->total_debt = $totalDebt - $totalPaid;
            return $customer;
        })->filter(function ($customer) {
            return $customer->total_debt > 0;
        })->values(); // pastikan index array rapi (0,1,2,...)

        // Buat pagination manual
        $paginated = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage),
            $filtered->count(),
            $perPage,
            $page,
            [
                'path' => url()->current(),
                'query' => $request->query(),
            ]
        );

        return view('debts.index', [
            'customers' => $paginated,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        return view('debts.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string',
        ]);

        // BUAT entri hutang baru, bukan update yang lama
        Debt::create([
            'customer_id' => $validated['customer_id'],
            'amount' => $validated['amount'],
            'note' => $validated['note'],
        ]);

        return redirect()->route('debts.index')->with('success', 'Hutang berhasil dicatat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Debt $debt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Debt $debt)
    {
        return view('debts.edit', compact('debt'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Debt $debt)
    {
        $request->validate([
        'payment_amount' => 'required|numeric|min:1|max:' . $debt->amount,
        ]);

        return redirect()->route('payments.create', ['debt_id' => $debt->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Debt $debt)
    {
        //
    }
}
