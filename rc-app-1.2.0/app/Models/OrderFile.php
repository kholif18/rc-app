<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderFile extends Model
{
    protected $fillable = ['order_id', 'filename', 'name', 'size'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
