<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class UpdateController extends Controller
{
    public function check()
    {
        $currentVersion = AppSetting::get('app_version', '1.0.0');
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
        $tempZip = storage_path('app/update.zip');
        $tmpExtractPath = storage_path('app/tmp');
        $backupPath = storage_path('app/backup');
        $timestamp = now()->format('Ymd_His');
        $backupZip = $backupPath . "/rc-app-backup-{$timestamp}.zip";

        if (!$updateUrl) {
            return response()->json(['success' => false, 'message' => 'URL pembaruan tidak ditemukan.']);
        }

        // Step 0: Backup sebelum update (kecualikan folder berat)
        try {
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $zip = new \ZipArchive;
            if ($zip->open($backupZip, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
                $exclude = ['vendor', 'node_modules', 'storage', '.git'];

                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator(base_path(), \RecursiveDirectoryIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $file) {
                    $filePath = $file->getRealPath();
                    $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $filePath);

                    $skip = false;
                    foreach ($exclude as $folder) {
                        if (str_starts_with($relativePath, $folder . '/')) {
                            $skip = true;
                            break;
                        }
                    }

                    if (!$skip) {
                        $zip->addFile($filePath, $relativePath);
                    }
                }

                $zip->close();
            } else {
                return response()->json(['success' => false, 'message' => 'Gagal membuat file backup.']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal melakukan backup sebelum update.']);
        }

        // Step 1: Download ZIP
        try {
            file_put_contents($tempZip, file_get_contents($updateUrl));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengunduh file pembaruan.']);
        }

        // Step 2: Ekstrak ke tmp/
        $zip = new \ZipArchive;
        if ($zip->open($tempZip) === TRUE) {
            $zip->extractTo($tmpExtractPath);
            $zip->close();
        } else {
            return response()->json(['success' => false, 'message' => 'Gagal mengekstrak file update.']);
        }

        // Step 3: Ambil folder hasil ekstrak
        $folders = glob($tmpExtractPath . '/*', GLOB_ONLYDIR);
        if (empty($folders)) {
            return response()->json(['success' => false, 'message' => 'Folder hasil ekstrak tidak ditemukan.']);
        }

        $extractedPath = $folders[0];

        // Step 4: Validasi isi update
        if (!file_exists($extractedPath . '/artisan')) {
            return response()->json(['success' => false, 'message' => 'File Laravel tidak lengkap dalam update.']);
        }

        // Step 5: Salin isi ke base_path
        try {
            $this->copyAll($extractedPath, base_path());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyalin file update: ' . $e->getMessage()
            ]);
        }

        // Step 6: Hapus file zip dan tmp
        File::delete($tempZip);
        File::deleteDirectory($tmpExtractPath);

        // Step 7: Update versi
        try {
            $versionCheck = Http::get('https://raw.githubusercontent.com/kholif18/rc-app/main/version.json');
            if ($versionCheck->ok()) {
                $newVersion = $versionCheck->json('version');
                AppSetting::set('app_version', $newVersion);
            }
        } catch (\Exception $e) {
            // Lewati jika gagal ambil versi
        }

        return response()->json(['success' => true, 'message' => 'Pembaruan berhasil diinstal. Backup otomatis juga telah disimpan.']);
    }


    private function copyAll($source, $destination)
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            $sourcePath = $file->getRealPath();
            $relativePath = str_replace($source . DIRECTORY_SEPARATOR, '', $sourcePath);
            $targetPath = $destination . DIRECTORY_SEPARATOR . $relativePath;

            if ($file->isDir()) {
                if (!is_dir($targetPath)) {
                    mkdir($targetPath, 0755, true);
                }
            } else {
                // Pastikan direktori target ada sebelum copy file
                $targetDir = dirname($targetPath);
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                if (!@copy($sourcePath, $targetPath)) {
                    throw new \Exception("Gagal menyalin file: {$relativePath}");
                }
            }
        }
    }

}
