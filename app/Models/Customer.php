<?php

namespace App\Models;

use App\Models\Debt;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone', 'address'];
    
    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(
            \App\Models\Payment::class,
            \App\Models\Debt::class,
            'customer_id',
            'debt_id',
            'id',
            'id'
        );
    }

    public function getTotalDebtAttribute()
    {
        $totalHutang = $this->debts()->sum('amount');
        $totalBayar = $this->payments()->selectRaw('SUM(payments.amount) as total')->value('total');

        return $totalHutang - $totalBayar;
    }

    public function debtAndPaymentHistory()
    {
        $debts = $this->debts()->get()->map(function($debt) {
            return (object) [
                'type' => 'debt',
                'amount' => $debt->amount,
                'note' => $debt->note,
                'date' => $debt->created_at,
            ];
        });

        $payments = $this->debts()->with('payments')->get()
            ->flatMap(function($debt) {
                return $debt->payments->map(function($payment) {
                    return (object) [
                        'type' => 'payment',
                        'amount' => $payment->amount,
                        'note' => $payment->note,
                        'date' => $payment->payment_date,
                    ];
                });
            });

        return $debts->merge($payments)->sortByDesc('date');
    }

}
