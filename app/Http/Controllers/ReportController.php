<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Report;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;


class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->input('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->input('to') ?: now()->endOfMonth()->toDateString();

        // Ambil data hutang dengan pagination
        $debts = Debt::with('customer')
                    ->whereBetween('debt_date', [$from, $to])
                    ->orderBy('debt_date', 'desc')
                    ->paginate(10, ['*'], 'debts_page');

        // Ambil data pembayaran dengan pagination
        $payments = Payment::with('debt.customer')
                    ->whereBetween('payment_date', [$from, $to])
                    ->orderBy('payment_date', 'desc')
                    ->paginate(10, ['*'], 'payments_page');

        // Total tetap dihitung dari keseluruhan data (bukan yang hanya tampil di halaman ini)
        $totalDebts = Debt::whereBetween('debt_date', [$from, $to])->sum('amount');
        $totalPayments = Payment::whereBetween('payment_date', [$from, $to])->sum('amount');
        $sisaHutang = $totalDebts - $totalPayments;

        return view('reports.index', compact(
            'debts', 'payments', 'totalDebts', 'totalPayments', 'sisaHutang', 'from', 'to'
        ));
    }
    
    public function export(Request $request)
    {
        $format = $request->input('format');
        $from = $request->input('from');
        $to = $request->input('to');

    if ($format == 'pdf') {
        $debts = Debt::whereBetween('debt_date', [$from, $to])->get();
        $payments = Payment::whereBetween('payment_date', [$from, $to])->get();

        $totalDebts = $debts->sum('amount');
        $totalPayments = $payments->sum('amount');

        $pdf = PDF::loadView('reports.export_pdf', compact(
            'from', 'to', 'debts', 'payments', 'totalDebts', 'totalPayments'
        ));

        return $pdf->stream("laporan_{$from}_sd_{$to}.pdf");
    }

    if ($format == 'excel') {
        return Excel::download(new ReportExport($from, $to), "laporan_{$from}_sd_{$to}.xlsx");
    }

    return redirect()->back()->with('error', 'Format tidak dikenali.');
    }
}
