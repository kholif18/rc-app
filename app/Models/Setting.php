<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'app_name', 
        'phone', 
        'email', 
        'website', 
        'address', 
        'logo', 
        'favicon'];
}
