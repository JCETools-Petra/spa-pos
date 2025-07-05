<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan atau memperbarui pengaturan default
        Setting::updateOrCreate(
            ['key' => 'app_title'],
            ['value' => 'Fortuna SPA']
        );
        Setting::updateOrCreate(
            ['key' => 'logo_size_sidebar'],
            ['value' => '48'] // Tinggi default dalam pixels
        );
        Setting::updateOrCreate(
            ['key' => 'logo_size_login'],
            ['value' => '80'] // Tinggi default dalam pixels
        );
    }
}
