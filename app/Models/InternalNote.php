<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InternalNote extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'user_id', 'note'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
