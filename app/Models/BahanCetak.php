<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BahanCetak extends Model
{
    use HasFactory;

    protected $table = 'bahan_cetak';

    protected $fillable = [
        'nama_bahan',
        'jenis_bahan',
        'gramatur',
        'ukuran',
    ];


    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        return $query->where('nama_bahan', 'like', '%'.$search.'%')
                    ->orWhere('jenis_bahan', 'like', '%'.$search.'%')
                    ->orWhere('ukuran', 'like', '%'.$search.'%');
    }

    // Scope untuk filter jenis bahan
    public function scopeJenis($query, $jenis)
    {
        if ($jenis) {
            return $query->where('jenis_bahan', $jenis);
        }
        return $query;
    }
}
