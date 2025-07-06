<?php

namespace Database\Seeders;

use App\Models\MessageTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MessageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'order_ready',
                'title' => 'Pesanan Selesai',
                'content' => 'Halo {{name}}, pesanan Anda dengan nomor #{{order_number}} untuk layanan {{services}} telah selesai dan siap diambil. Silakan ambil sebelum {{deadline}}. Terima kasih telah menggunakan layanan kami!',
            ],
            [
                'name' => 'order_progress',
                'title' => 'Pesanan Sedang Dikerjakan',
                'content' => 'Halo {{name}}, pesanan #{{order_number}} Anda sedang dalam proses pengerjaan oleh tim kami. Kami akan memberi tahu Anda kembali setelah selesai.',
            ],
            [
                'name' => 'order_late',
                'title' => 'Pesanan Terlambat Diambil',
                'content' => 'Halo {{name}}, kami ingin mengingatkan bahwa pesanan Anda #{{order_number}} belum diambil hingga batas waktu {{deadline}}. Silakan hubungi kami jika ada kendala.',
            ],
            [
                'name' => 'order_cancelled',
                'title' => 'Pesanan Dibatalkan',
                'content' => 'Halo {{name}}, pesanan Anda #{{order_number}} telah dibatalkan. Jika ini tidak sesuai, silakan hubungi tim kami untuk konfirmasi lebih lanjut.',
            ],
            [
                'name' => 'order_reminder',
                'title' => 'Pengingat Pengambilan Pesanan',
                'content' => 'Hai {{name}}, ini adalah pengingat bahwa pesanan Anda #{{order_number}} siap diambil. Mohon segera diambil sebelum {{deadline}}. Terima kasih!',
            ],
            [
                'name' => 'order_new',
                'title' => 'Pesanan Baru Diterima',
                'content' => 'Halo {{name}}, kami telah menerima pesanan baru Anda dengan nomor #{{order_number}}. Tim kami akan segera memproses layanan {{services}} yang Anda pilih.',
            ],
        ];

        foreach ($templates as $template) {
            MessageTemplate::updateOrCreate(
                ['name' => $template['name']],
                ['title' => $template['title'], 'content' => $template['content']]
            );
        }
    }
}
