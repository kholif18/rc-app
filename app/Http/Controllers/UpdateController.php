<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UpdateController extends Controller
{
    public function check()
    {
        $currentVersion = config('app.version'); // atau ambil dari DB
        $versionUrl = 'https://raw.githubusercontent.com/kholif18/rc-app/main/version.json';

        try {
            $response = Http::get($versionUrl);
            if ($response->ok()) {
                $data = $response->json();
                $latestVersion = $data['version'];

                // Cek apakah update tersedia
                $updateAvailable = version_compare($latestVersion, $currentVersion, '>');

                // Simpan update_url dan app_version jika ada update
                if ($updateAvailable) {
                    AppSetting::set('update_url', $data['url']);
                    AppSetting::set('app_version', $latestVersion);
                }

                return response()->json([
                    'update_available' => $updateAvailable,
                    'version' => $latestVersion,
                    'message' => $updateAvailable ?
                        "Tersedia versi baru: v{$latestVersion}" :
                        "Aplikasi Anda sudah menggunakan versi terbaru ({$currentVersion}).",
                    'download_url' => $data['url'],
                    'changelog' => $data['changelog'] ?? [],
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'update_available' => false,
                'message' => 'Gagal memeriksa pembaruan.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function install()
    {
        $updateUrl = AppSetting::get('update_url');
        $tempPath = storage_path('app/update.zip');

        if (!$updateUrl) {
            return response()->json(['success' => false, 'message' => 'URL pembaruan tidak ditemukan.']);
        }

        try {
            // Download ZIP
            file_put_contents($tempPath, file_get_contents($updateUrl));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengunduh file pembaruan.']);
        }

        // Ekstrak ke base_path
        $zip = new \ZipArchive;
        if ($zip->open($tempPath) === TRUE) {
            $zip->extractTo(base_path());
            $zip->close();

            // Hapus zip setelah ekstrak
            unlink($tempPath);

            // Ambil versi terbaru dari version.json online
            try {
                $versionCheck = Http::get('https://raw.githubusercontent.com/kholif18/rc-app/main/version.json');
                if ($versionCheck->ok()) {
                    $newVersion = $versionCheck->json('version');
                    AppSetting::set('app_version', $newVersion);
                }
            } catch (\Exception $e) {
                // Tidak fatal, lanjutkan
            }

            return response()->json(['success' => true, 'message' => 'Pembaruan berhasil diinstal.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Gagal mengekstrak file pembaruan.']);
        }
    }
}
