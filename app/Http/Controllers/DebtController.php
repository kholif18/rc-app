<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = 10;

        $latestDebts = DB::table('debts as d1')
        ->select('d1.customer_id', 'd1.note', 'd1.created_at', 'd1.id')
        ->whereRaw('d1.created_at = (
            SELECT MAX(d2.created_at)
            FROM debts d2
            WHERE d2.customer_id = d1.customer_id
        )');
        // Query dengan join dan aggregate
        $query = DB::table('customers')
            ->leftJoin('debts', 'debts.customer_id', '=', 'customers.id')
            ->leftJoin('payments', 'payments.debt_id', '=', 'debts.id')
            ->leftJoinSub($latestDebts, 'latest_debt', function ($join) {
                $join->on('customers.id', '=', 'latest_debt.customer_id');
            })
            ->select(
                'customers.id',
                'customers.name',
                'customers.phone',
                'customers.address',
                DB::raw('COALESCE(SUM(debts.amount), 0) as total_debt_amount'),
                DB::raw('COALESCE(SUM(payments.amount), 0) as total_paid_amount'),
                DB::raw('(COALESCE(SUM(debts.amount), 0) - COALESCE(SUM(payments.amount), 0)) as total_debt'),
                'latest_debt.created_at as last_debt_date',
                'latest_debt.note as last_debt_note',
                'latest_debt.id as last_debt_id'
            )
            ->groupBy('customers.id', 'customers.name', 'customers.phone', 'customers.address', 'latest_debt.created_at', 'latest_debt.note', 'latest_debt.id');

        // Filter search jika ada
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('customers.name', 'like', "%{$search}%")
                ->orWhere('customers.phone', 'like', "%{$search}%")
                ->orWhere('customers.address', 'like', "%{$search}%");
            });
        }

        // Filter hanya yang punya total hutang lebih dari 0
        $query->having('total_debt', '>', 0);

        // Pagination otomatis
        $customers = $query->paginate($perPage)->withQueryString();

        $totalRemainingDebt = $customers->sum('total_debt');
        return view('debts.index', compact('customers', 'search', 'totalRemainingDebt'));
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
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255'
        ]);
        
        // Simpan data hutang
        Debt::create([
            'customer_id' => $validated['customer_id'],
            'amount' => $validated['amount'],
            'note' => $validated['note'],
            'user_id' => Auth::id()
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
