<x-app-layout>
    <x-slot name="header">
        {{ __('Pengaturan Website') }}
    </x-slot>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="max-w-3xl mx-auto space-y-8">
            
            <div>
                <label for="app_title" class="block text-sm font-medium text-gray-700 mb-1">Judul Aplikasi</label>
                <input type="text" name="app_title" id="app_title" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm" value="{{ old('app_title', $settings['app_title']->value ?? '') }}">
                @error('app_title')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Logo Aplikasi</label>
                <div class="mt-2 flex items-center space-x-6">
                    <div class="shrink-0">
                         @if(isset($settings['app_logo']))
                            <img id="logo_preview" class="h-16 w-auto object-contain" src="{{ $settings['app_logo']->value }}" alt="Current Logo">
                        @else
                            <div id="logo_preview" class="h-16 w-16 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                @svg('heroicon-o-photo', 'w-8 h-8')
                            </div>
                        @endif
                    </div>
                    <label class="block">
                        <span class="sr-only">Choose logo file</span>
                        <input type="file" name="app_logo" id="app_logo" onchange="document.getElementById('logo_preview').src = window.URL.createObjectURL(this.files[0])"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100"/>
                    </label>
                </div>
                 <p class="text-xs text-gray-500 mt-2">Kosongkan jika tidak ingin mengganti. Disarankan format PNG transparan.</p>
                @error('app_logo')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
            </div>
            
            <div>
                 <label class="block text-sm font-medium text-gray-700 mb-1">Favicon Aplikasi</label>
                 <div class="mt-2 flex items-center space-x-6">
                    <div class="shrink-0">
                         @if(isset($settings['app_favicon']))
                            <img id="favicon_preview" class="h-10 w-10 object-contain" src="{{ $settings['app_favicon']->value }}" alt="Current Favicon">
                        @else
                            <div id="favicon_preview" class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                @svg('heroicon-o-sparkles', 'w-6 h-6')
                            </div>
                        @endif
                    </div>
                     <label class="block">
                        <span class="sr-only">Choose favicon file</span>
                        <input type="file" name="app_favicon" id="app_favicon" onchange="document.getElementById('favicon_preview').src = window.URL.createObjectURL(this.files[0])"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100"/>
                    </label>
                </div>
                <p class="text-xs text-gray-500 mt-2">Kosongkan jika tidak ingin mengganti. Format .ico atau .png (disarankan 32x32px).</p>
                @error('app_favicon')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
            </div>

            <div class="border-t border-gray-200 pt-6 flex justify-end">
                <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all">
                    @svg('heroicon-o-check-circle', 'w-5 h-5 mr-2')
                    Simpan Pengaturan
                </button>
            </div>
        </div>
    </form>
</x-app-layout>