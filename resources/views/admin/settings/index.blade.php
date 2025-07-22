<x-app-layout>
    <x-slot name="header">
        {{ __('Pengaturan Website') }}
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm max-w-3xl mx-auto">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-8">
                <div>
                    <label for="app_title" class="block text-sm font-medium">Judul Aplikasi</label>
                    <input type="text" name="app_title" id="app_title" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('app_title', $settings['app_title']->value ?? '') }}">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium">Logo Aplikasi</label>
                        <div class="mt-2 flex items-center space-x-6">
                            <div class="shrink-0">
                                <!-- PERBAIKAN: Gunakan Storage::url() untuk menampilkan preview -->
                                @if(isset($settings['app_logo']) && $settings['app_logo']->value)
                                    <img id="logo_preview" class="h-16 w-auto object-contain" src="{{ Storage::url($settings['app_logo']->value) }}" alt="Current Logo">
                                @else
                                    <div class="h-16 w-16 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">@svg('heroicon-o-photo', 'w-8 h-8')</div>
                                @endif
                            </div>
                            <input type="file" name="app_logo" id="app_logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gray-50 hover:file:bg-gray-100">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Favicon Aplikasi</label>
                        <div class="mt-2 flex items-center space-x-6">
                             <div class="shrink-0">
                                @if(isset($settings['app_favicon']) && $settings['app_favicon']->value)
                                    <img id="favicon_preview" class="h-10 w-10 object-contain" src="{{ Storage::url($settings['app_favicon']->value) }}" alt="Current Favicon">
                                @else
                                    <div class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">@svg('heroicon-o-sparkles', 'w-6 h-6')</div>
                                @endif
                            </div>
                            <input type="file" name="app_favicon" id="app_favicon" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gray-50 hover:file:bg-gray-100">
                        </div>
                    </div>
                </div>

                <!-- PENGATURAN UKURAN LOGO -->
                <div class="border-t pt-6">
                    <h3 class="text-base font-semibold leading-6 text-gray-900">Pengaturan Ukuran Logo</h3>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                         <div>
                            <label for="logo_size_sidebar" class="block text-sm font-medium">Tinggi Logo di Sidebar (px)</label>
                            <input type="number" name="logo_size_sidebar" id="logo_size_sidebar" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('logo_size_sidebar', $settings['logo_size_sidebar']->value ?? 48) }}">
                        </div>
                        <div>
                            <label for="logo_size_login" class="block text-sm font-medium">Tinggi Logo di Halaman Login (px)</label>
                            <input type="number" name="logo_size_login" id="logo_size_login" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('logo_size_login', $settings['logo_size_login']->value ?? 80) }}">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t">
                    <button type="submit" class="px-6 py-2 bg-brand-sand-green hover:bg-brand-sand-green-dark text-white rounded-lg">Simpan Pengaturan</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
