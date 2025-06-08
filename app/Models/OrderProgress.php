<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProgress extends Model
{
    protected $fillable = ['order_id', 'user_id', 'status', 'note'];
    
    protected $casts = [
        'created_at' => 'datetime:d M Y, H:i',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(function () {
            return new User([
                'name' => 'Admin',
                'role' => 'Admin' // Default role yang valid
            ]);
        });
    }
}
