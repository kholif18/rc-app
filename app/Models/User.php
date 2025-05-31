<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'avatar',
        'last_activity', // pastikan ini ada supaya bisa diisi massal jika perlu
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_activity' => 'datetime', // supaya otomatis jadi Carbon instance
    ];

    // User dianggap online jika last_activity kurang dari 5 menit yang lalu
    public function getIsOnlineAttribute()
    {
        return $this->last_activity && $this->last_activity->gt(now()->subMinutes(5));
    }

    // Menampilkan status waktu terakhir aktif user
    public function getLastActiveStatusAttribute()
    {
        if (!$this->last_activity) {
            return 'Tidak ada data aktivitas';
        }

        $diffInMinutes = now()->diffInMinutes($this->last_activity);

        if ($diffInMinutes === 0) {
            return 'Aktif sekarang';
        } elseif ($diffInMinutes < 60) {
            return "Aktif $diffInMinutes menit yang lalu";
        } else {
            return "Aktif " . $this->last_activity->diffForHumans();
        }
    }
}
