<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;



class ReportController extends Controller
{
    public function debtReport(Request $request)
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

        return view('reports.debt', compact(
            'debts', 'payments', 'totalDebts', 'totalPayments', 'sisaHutang', 'from', 'to'
        ));
    }

    public function orderReport(Request $request)
    {
        $from = $request->input('from') ?? now()->startOfMonth()->toDateString();
        $to = $request->input('to') ?? now()->toDateString();

        // Debug tanggal
        logger("Filter date: $from - $to");

        $query = Order::with(['customer', 'bahanCetak']);

        // Filter tanggal (lebih robust)
        $query->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        // Filter jenis layanan jika ada
        if ($request->filled('services')) {
            $query->whereJsonContains('services', $request->services);
        }

        // Filter status jika ada
        if ($request->filled('status') && $request->status !== 'Semua Status') {
            $query->where('status', $request->status);
        }

        // Ambil data order
        $orders = $query->latest()->paginate(10);

        // Summary data
        $totalOrders = $query->count();
        $completedStatuses = ['Selesai', 'Diambil'];
        $completedOrders = (clone $query)->whereIn('status', $completedStatuses)->count();
        $processingOrders = (clone $query)->where('status', 'Dikerjakan')->count();
        $canceledOrders = (clone $query)->where('status', 'Batal')->count();

        return view('reports.order', compact(
            'orders', 'from', 'to',
            'totalOrders', 'completedOrders',
            'processingOrders', 'canceledOrders'
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

    public function exportReportOrder(Request $request)
    {
        // Ambil filter
        $from = $request->get('from');
        $to = $request->get('to');
        $services = $request->get('services');
        $status = $request->get('status');
        $format = $request->get('format'); // pdf / excel

        // Query orders dengan filter yang sama seperti di halaman report
        $query = Order::query();

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }
        if ($services) {
            $query->whereJsonContains('services', $services);
        }
        if ($status) {
            $query->where('status', $status);
        }

        if ($format == 'pdf') {
            $orders = $query->get(); // Ambil data order-nya

            $totalOrders = $orders->count();
            $completedStatuses = ['Selesai', 'Diambil'];
            $completedOrders = $orders->whereIn('status', $completedStatuses)->count();
            $processingOrders = $orders->where('status', 'Dikerjakan')->count();
            $canceledOrders = $orders->where('status', 'Batal')->count();

            $pdf = PDF::loadView('reports.order-pdf', compact(
                'from', 'to', 'services', 'status', 'orders',
                'totalOrders', 'completedOrders', 'processingOrders', 'canceledOrders'
            ));

            return $pdf->stream("laporan_{$from}_sd_{$to}.pdf");
        }

        if ($format == 'excel') {
            // Export ke Excel
            $orders = $query->get();
            return Excel::download(new \App\Exports\OrderReportExport($orders), 'order-report.xlsx');
        } 

        return redirect()->route('reports.order')->with('error', 'Format export tidak valid.');
    }
}
