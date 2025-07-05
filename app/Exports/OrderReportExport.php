<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderReportExport implements FromCollection, WithHeadings
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders->map(function ($order) {
            $services = implode(', ', $order->services ?? []);
            $customer = $order->customer->name ?? '-';

            return [
                'ID Order' => $order->id,
                'Tanggal' => $order->created_at->format('Y-m-d'),
                'Pelanggan' => $customer,
                'Jenis Layanan' => $services,
                'Status' => $order->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Order',
            'Tanggal',
            'Pelanggan',
            'Jenis Layanan',
            'Status',
        ];
    }
}
