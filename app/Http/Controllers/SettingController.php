<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'favicon' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        $setting = Setting::firstOrNew([]);

        if ($request->hasFile('logo')) {
        // Hapus logo lama jika ada
        if ($setting->logo && file_exists(storage_path('app/public/logo/' . $setting->logo))) {
            unlink(storage_path('app/public/logo/' . $setting->logo));
        }

        // Simpan logo baru
        $logoPath = $request->file('logo')->store('logo', 'public');
        $setting->logo = basename($logoPath); // Simpan hanya nama file
    }
    // Upload Favicon
    if ($request->hasFile('favicon')) {
        // Hapus favicon lama jika ada
        if ($setting->favicon && file_exists(storage_path('app/public/favicon/' . $setting->favicon))) {
            unlink(storage_path('app/public/favicon/' . $setting->favicon));
        }

        // Simpan favicon baru
        $faviconPath = $request->file('favicon')->store('favicon', 'public');
        $setting->favicon = basename($faviconPath); // Simpan hanya nama file
    }

    $setting->app_name = $request->app_name;
    $setting->save();

    return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
