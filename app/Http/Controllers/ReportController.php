<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Debt;
use App\Models\Order;
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

    public function incomeReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        
        // Data pendapatan per layanan
        $servicesIncome = Order::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                SUM(CASE WHEN services LIKE "%Ketik%" THEN 
                    (page_count * harga_per_halaman) + additional_fee 
                ELSE 0 END) as ketik_income,
                
                SUM(CASE WHEN services LIKE "%Desain%" THEN 
                    (design_fee) + additional_fee 
                ELSE 0 END) as desain_income,
                
                SUM(CASE WHEN services LIKE "%Cetak%" THEN 
                    (print_quantity * harga_cetak) + additional_fee 
                ELSE 0 END) as cetak_income,
                
                SUM(total_amount) as total_income
            ')
            ->first();
            
        // Data pembayaran
        $payments = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->with('order')
            ->orderBy('payment_date', 'desc')
            ->get();
            
        return view('reports.income', compact('servicesIncome', 'payments', 'startDate', 'endDate'));
    }
}
