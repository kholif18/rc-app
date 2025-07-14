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
                'title' => 'Pemberitahuan Pesanan Selesai',
                'content' => "Yth. [name],\nPesanan Anda dengan nomor #[order_number] untuk layanan [services] telah selesai dan siap untuk diambil.\nMohon untuk mengambilnya sebelum tanggal [deadline].\nTerima kasih atas kepercayaan Anda menggunakan layanan kami.",
            ],
            [
                'name' => 'order_progress',
                'title' => 'Status Pesanan: Sedang Diproses',
                'content' => "Yth. [name],\nPesanan Anda dengan nomor #[order_number] saat ini sedang dalam proses pengerjaan oleh tim kami.\nKami akan segera memberikan informasi lebih lanjut setelah pesanan selesai diproses.",
            ],
            [
                'name' => 'order_late',
                'title' => 'Pemberitahuan Keterlambatan Pengambilan Pesanan',
                'content' => "Yth. [name],\nKami ingin menginformasikan bahwa pesanan Anda dengan nomor #[order_number] belum diambil hingga batas waktu yang telah ditentukan, yaitu [deadline].\nJika terdapat kendala, silakan hubungi kami segera.",
            ],
            [
                'name' => 'order_cancelled',
                'title' => 'Pemberitahuan Pembatalan Pesanan',
                'content' => "Yth. [name],\nPesanan Anda dengan nomor #[order_number] telah dibatalkan.\nApabila Anda merasa ini merupakan kesalahan, silakan hubungi tim kami untuk konfirmasi lebih lanjut.",
            ],
            [
                'name' => 'order_reminder',
                'title' => 'Pengingat Pengambilan Pesanan',
                'content' => "Yth. [name],\nIni merupakan pengingat bahwa pesanan Anda dengan nomor #[order_number] telah selesai dan siap diambil.\nMohon untuk mengambil pesanan tersebut sebelum tanggal [deadline].\nTerima kasih atas perhatian Anda.",
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
