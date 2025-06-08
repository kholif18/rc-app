<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Ahmad Hidayat',
                'phone' => '081234567890',
                'email' => 'ahmad@example.com',
                'address' => 'Jl. Melati No. 1, Jakarta'
            ],
            [
                'name' => 'Rina Marlina',
                'phone' => '081298765432',
                'email' => 'rina@example.com',
                'address' => 'Jl. Mawar No. 10, Bandung'
            ],
            [
                'name' => 'Budi Santoso',
                'phone' => '082112345678',
                'email' => 'budi@example.com',
                'address' => 'Jl. Anggrek No. 3, Surabaya'
            ],
            [
                'name' => 'Siti Aminah',
                'phone' => '082198765432',
                'email' => 'siti@example.com',
                'address' => 'Jl. Kenanga No. 8, Yogyakarta'
            ],
            [
                'name' => 'Dewi Kartika',
                'phone' => '081377889900',
                'email' => 'dewi@example.com',
                'address' => 'Jl. Teratai No. 5, Medan'
            ],
            [
                'name' => 'Fahmi Rahman',
                'phone' => '085212345678',
                'email' => 'fahmi@example.com',
                'address' => 'Jl. Cemara No. 2, Makassar'
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
