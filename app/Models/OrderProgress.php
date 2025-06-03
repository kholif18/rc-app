<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProgress extends Model
{
    protected $fillable = ['order_id', 'status', 'note'];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
