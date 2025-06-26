<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Cek jika tabel settings ada sebelum menjalankan query
        if (Schema::hasTable('settings')) {
            // Menggunakan cache agar tidak query ke database setiap request
            $settings = Cache::rememberForever('app_settings', function () {
                // Ambil semua settings dan ubah jadi format key => value
                return Setting::all()->keyBy('key')->transform(function ($setting) {
                    return $setting->value;
                });
            });

            // Bagikan settings ke semua view
            View::share('appSettings', $settings);
        }
    }
}