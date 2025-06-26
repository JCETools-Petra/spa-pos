<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Ambil semua settings dan ubah ke collection agar mudah diakses di view
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_title' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048', // max 2MB
            'app_favicon' => 'nullable|image|mimes:ico,png|max:512', // max 512KB
        ]);

        // Simpan atau update judul aplikasi
        Setting::updateOrCreate(
            ['key' => 'app_title'],
            ['value' => $request->input('app_title')]
        );

        // Handle upload Logo
        if ($request->hasFile('app_logo')) {
            $path = $request->file('app_logo')->store('public/logos');
            // Simpan path yang bisa diakses publik
            $url = Storage::url($path);
            Setting::updateOrCreate(
                ['key' => 'app_logo'],
                ['value' => $url]
            );
        }

        // Handle upload Favicon
        if ($request->hasFile('app_favicon')) {
            $path = $request->file('app_favicon')->store('public/favicons');
            // Simpan path yang bisa diakses publik
            $url = Storage::url($path);
            Setting::updateOrCreate(
                ['key' => 'app_favicon'],
                ['value' => $url]
            );
        }

        // Hapus cache settings agar perubahan langsung terlihat
        Cache::forget('app_settings');

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}