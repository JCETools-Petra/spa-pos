    <x-app-layout>
        <x-slot name="header">
            {{ __('Edit Profil') }}
        </x-slot>
    
        <div class="space-y-6">
            <!-- Form Informasi Profil -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Informasi Profil
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Perbarui informasi profil dan alamat email akun Anda.
                            </p>
                        </header>
    
                        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                            @csrf
                            @method('patch')
    
                            <!-- Foto Profil -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                                <div class="mt-2 flex items-center space-x-6">
                                    <div class="shrink-0">
                                        <img id="avatar_preview" class="h-20 w-20 object-cover rounded-full" 
                                             src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=random' }}" 
                                             alt="Current avatar">
                                    </div>
                                    <label class="block">
                                        <span class="sr-only">Pilih foto profil</span>
                                        <input type="file" name="avatar" id="avatar" onchange="document.getElementById('avatar_preview').src = window.URL.createObjectURL(this.files[0])"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-sand-green-light file:text-brand-sand-green-dark hover:file:bg-brand-ivory"/>
                                    </label>
                                </div>
                            </div>
    
                            <!-- Nama -->
                            <div>
                                <label for="name" class="block text-sm font-medium">Nama</label>
                                <input id="name" name="name" type="text" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>
    
                            <!-- Email (Read-only) -->
                            <div>
                                <label for="email" class="block text-sm font-medium">Email</label>
                                <input id="email" name="email" type="email" class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-lg shadow-sm" value="{{ $user->email }}" disabled />
                            </div>
    
                            <div class="flex items-center gap-4">
                                <button type="submit" class="px-4 py-2 bg-brand-sand-green hover:bg-brand-sand-green-dark text-white rounded-lg">Simpan</button>
                                @if (session('status') === 'profile-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">Tersimpan.</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>
    
            <!-- Form Ganti Password -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </x-app-layout>
    