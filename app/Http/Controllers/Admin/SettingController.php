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
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_title' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'app_favicon' => 'nullable|image|mimes:ico,png|max:512',
            // Validasi untuk ukuran logo
            'logo_size_sidebar' => 'required|integer|min:20|max:100',
            'logo_size_login' => 'required|integer|min:30|max:200',
        ]);

        Setting::updateOrCreate(['key' => 'app_title'], ['value' => $request->input('app_title')]);
        
        // Simpan pengaturan ukuran logo
        Setting::updateOrCreate(['key' => 'logo_size_sidebar'], ['value' => $request->input('logo_size_sidebar')]);
        Setting::updateOrCreate(['key' => 'logo_size_login'], ['value' => $request->input('logo_size_login')]);

        if ($request->hasFile('app_logo')) {
            if ($oldLogoPath = Setting::where('key', 'app_logo')->value('value')) {
                Storage::disk('public')->delete($oldLogoPath);
            }
            $path = $request->file('app_logo')->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $path]);
        }

        if ($request->hasFile('app_favicon')) {
             if ($oldFaviconPath = Setting::where('key', 'app_favicon')->value('value')) {
                Storage::disk('public')->delete($oldFaviconPath);
            }
            $path = $request->file('app_favicon')->store('favicons', 'public');
            Setting::updateOrCreate(['key' => 'app_favicon'], ['value' => $path]);
        }

        Cache::forget('app_settings');

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
