<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'customer_id',
        'services',
        'doc_type',
        'page_count',
        'order_title',
        'design_type',
        'design_size',
        'print_type',
        'print_quantity',
        'bahan_cetak_id',
        'deadline',
        'estimate_time',
        'status',
        'priority',
        'special_notes',
    ];

    protected $casts = [
        'services' => 'array',
        'deadline' => 'datetime',
        'attachments' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function files()
    {
        return $this->hasMany(OrderFile::class);
    }

    public function progress()
    {
        return $this->hasMany(OrderProgress::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function internalNotes()
    {
        return $this->hasMany(InternalNote::class);
    }

    public function bahanCetak()
{
    return $this->belongsTo(BahanCetak::class, 'bahan_cetak_id');
}
}
