<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'role' => 'superadmin', // atau 'admin' sesuai kebutuhan
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
        ]);

        // Admin
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // User 1
        User::create([
            'name' => 'Regular User 1',
            'username' => 'user1',
            'email' => 'user1@example.com',
            'role' => 'user',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // User 2
        User::create([
            'name' => 'Regular User 2',
            'username' => 'user2',
            'email' => 'user2@example.com',
            'role' => 'user',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
    }
}
