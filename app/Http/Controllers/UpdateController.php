<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UpdateController extends Controller
{
    public function check()
    {
        $currentVersion = config('app.version'); // atau ambil dari DB jika dinamis
        $versionUrl = 'https://raw.githubusercontent.com/kholif18/rc-app/main/version.json';

        try {
            $response = Http::get($versionUrl);
            if ($response->ok()) {
                $data = $response->json();
                $latestVersion = $data['version'];

                return response()->json([
                    'update_available' => version_compare($latestVersion, $currentVersion, '>'),
                    'version' => $latestVersion,
                    'message' => version_compare($latestVersion, $currentVersion, '>') ?
                        "Tersedia versi baru: v{$latestVersion}" :
                        "Aplikasi Anda sudah menggunakan versi terbaru ({$currentVersion}).",
                    'download_url' => $data['url'],
                    'changelog' => $data['changelog'],
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'update_available' => false,
                'message' => 'Gagal memeriksa pembaruan.',
            ], 500);
        }
    }
}
