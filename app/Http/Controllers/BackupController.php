<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    public function index()
    {
        return view('admin.backup');
    }

    public function export()
    {
        $db = config('database.connections.mysql');
        $filename = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        if (!File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'));
        }

        $host = env('DB_HOST', '127.0.0.1'); // pastikan DB_HOST benar
        $command = sprintf(
            'mysqldump -h%s -u%s -p%s %s > %s',
            escapeshellarg($host),
            escapeshellarg($db['username']),
            escapeshellarg($db['password']),
            escapeshellarg($db['database']),
            escapeshellarg($path)
        );
        exec($command, $output, $result);

        if ($result !== 0 || !file_exists($path)) {
            return back()->with('error', 'Gagal membuat backup database.');
        }

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function import(Request $request)
    {
        // Validasi file upload harus ada dan berekstensi sql atau txt, max 5MB
        $request->validate([
            'sql_file' => 'required|file|mimes:sql,txt|max:5120',
        ]);

        if (!$request->hasFile('sql_file')) {
            return back()->withErrors(['sql_file' => 'File tidak ditemukan']);
        }

        $file = $request->file('sql_file');

        if (!$file->isValid()) {
            return back()->withErrors(['sql_file' => 'File tidak valid']);
        }

        $originalName = $file->getClientOriginalName();

        // Simpan file di storage/app/public/backups
        $path = $file->storeAs('backups', $originalName, 'public'); 
        // Catatan: 'public' disini artinya di storage/app/public/backups

        if (!$path) {
            return back()->withErrors(['sql_file' => 'Gagal menyimpan file']);
        }

        // Hitung full path file yang sudah disimpan
        $fullPath = storage_path('app/public/' . $path);

        if (!file_exists($fullPath)) {
            return back()->withErrors(['sql_file' => 'File tidak ditemukan di server setelah upload']);
        }

        // Ambil config database
        $db = config('database.connections.mysql');
        $host = env('DB_HOST', '127.0.0.1'); // pastikan DB_HOST benar

        // Command import database (pastikan mysql client tersedia)
        $command = sprintf(
            'mysql -h%s -u%s -p%s %s < %s',
            escapeshellarg($host),
            escapeshellarg($db['username']),
            escapeshellarg($db['password']),
            escapeshellarg($db['database']),
            escapeshellarg($fullPath)
        );

        session()->save();

        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            return back()->withErrors(['sql_file' => 'Gagal import database. Periksa file dan konfigurasi.']);
        }

        return back()->with('success', 'Database berhasil diimport!');
    }
}
