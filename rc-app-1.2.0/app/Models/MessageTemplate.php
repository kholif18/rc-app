<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    protected $fillable = ['name', 'title', 'content'];

    public static function getContent($name)
    {
        return optional(static::where('name', $name)->first())->content;
    }
}
