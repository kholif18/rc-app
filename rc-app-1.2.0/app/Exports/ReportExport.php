<?php

namespace App\Exports;

use App\Models\Report;
use App\Models\Debt;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportExport implements FromView
{
    protected $from, $to, $orders;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }
    public function view(): View
    {
        $debts = Debt::whereBetween('debt_date', [$this->from, $this->to])->get();
        $payments = Payment::whereBetween('payment_date', [$this->from, $this->to])->get();

        $totalDebts = $debts->sum('amount');
        $totalPayments = $payments->sum('amount');

        return view('reports.export_excel', [
            'from' => $this->from,
            'to' => $this->to,
            'debts' => $debts,
            'payments' => $payments,
            'totalDebts' => $totalDebts,
            'totalPayments' => $totalPayments,
        ]);
    }
}
