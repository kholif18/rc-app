<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'debt_id',
        'amount',
        'note',
        'payment_date',
    ];
    protected $casts = [
        'payment_date' => 'datetime',
    ];

    // Relasi ke model Debt (jika ingin)
    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
