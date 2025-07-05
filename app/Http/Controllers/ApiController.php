<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        return view('admin.api.index', [
            'client_name' => $setting?->client_name,
            'api_token' => $setting?->api_token,
            'gateway_url' => $setting?->gateway_url,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'api_token' => 'nullable|string|max:255',
            'gateway_url' => 'nullable|url',
        ]);

        $setting = Setting::firstOrCreate([]);
        $setting->update([
            'client_name' => $request->client_name,
            'api_token' => $request->api_token,
            'gateway_url' => $request->gateway_url,
        ]);

        return redirect()->back()->with('success', 'API settings updated.');
    }

    public function testConnection(Request $request)
    {
        // Validasi input dari user
        $request->validate([
            'client_name' => 'required|string|max:255',
            'api_token' => 'required|string|max:255',
        ]);

        $setting = Setting::first();

        // Jika gateway_url belum dikonfigurasi
        if (!$setting || !$setting->gateway_url) {
            return response()->json([
                'success' => false,
                'message' => 'URL Gateway belum dikonfigurasi.',
            ], 422);
        }
        
        try {
            $response = Http::timeout(5)->post($setting->gateway_url . '/api/check-client', [
                'client_name' => $request->client_name,
                'api_token' => $request->api_token,
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => $response->json('message') ?? 'Terhubung',
                ]);
            }

            // Gagal (token salah atau tidak dikenali)
            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Token tidak valid',
            ], $response->status());

        } catch (\Exception $e) {
            // Gagal konek ke server r-gateway
            return response()->json([
                'success' => false,
                'message' => 'Gagal konek: ' . $e->getMessage(),
            ], 500);
        }
    }
}
