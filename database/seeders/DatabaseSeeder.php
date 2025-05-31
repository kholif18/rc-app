<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin',
            'username' => 'admin', // pastikan kolom username sudah ada
            'email' => 'admin@example.com',
            'role' => 'superadmin',
            'password' => Hash::make('admin'), // ganti dengan password aman
            'email_verified_at' => now(),
        ]);
        
    }
}
