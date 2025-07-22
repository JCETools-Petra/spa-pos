<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage; // <-- Tambahkan ini

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
        if (Schema::hasTable('settings')) {
            $settings = Cache::rememberForever('app_settings', function () {
                $settingsCollection = Setting::all()->keyBy('key');

                // PERBAIKAN: Konversi path file menjadi URL yang benar sebelum di-cache
                if ($settingsCollection->has('app_logo') && $settingsCollection->get('app_logo')->value) {
                    $settingsCollection->get('app_logo')->value = Storage::url($settingsCollection->get('app_logo')->value);
                }
                if ($settingsCollection->has('app_favicon') && $settingsCollection->get('app_favicon')->value) {
                    $settingsCollection->get('app_favicon')->value = Storage::url($settingsCollection->get('app_favicon')->value);
                }

                // Transformasi ke format key => value sederhana
                return $settingsCollection->transform(function ($setting) {
                    return $setting->value;
                });
            });

            View::share('appSettings', $settings);
        }
    }
}
