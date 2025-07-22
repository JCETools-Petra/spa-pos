<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - {{ $appSettings['app_title'] ?? config('app.name', 'Laravel') }}</title>

        @if(isset($appSettings['app_favicon']))
            <link rel="icon" href="{{ $appSettings['app_favicon'] }}" type="image/x-icon">
        @endif

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-brand-dark-stone antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-brand-deep-teal">
            <!-- Logo Aplikasi -->
            <div>
                <a href="/">
                    @if(isset($appSettings['app_logo']) && $appSettings['app_logo'])
                        <img src="{{ $appSettings['app_logo'] }}" alt="Logo" class="w-auto mx-auto" style="height: {{ $appSettings['logo_size_login'] ?? 80 }}px;">
                    @else
                        <h1 class="text-4xl font-bold text-white">{{ $appSettings['app_title'] ?? 'SPA POS' }}</h1>
                    @endif
                </a>
            </div>

            <!-- PERBAIKAN: Menambah padding vertikal (py-10) -->
            <div class="w-full sm:max-w-md mt-6 px-6 py-10 bg-white shadow-md overflow-hidden sm:rounded-lg">
                
                <h2 class="text-center text-2xl font-bold text-brand-dark-stone mb-2">Fortuna SPA Login</h2>
                <p class="text-center text-sm text-gray-600 mb-8">Silakan login untuk melanjutkan</p>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Alamat Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium">Email</label>
                        <input id="email" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-sand-green focus:ring-brand-sand-green" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <label for="password" class="block text-sm font-medium">Password</label>
                        <input id="password" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-sand-green focus:ring-brand-sand-green"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-brand-sand-green shadow-sm focus:ring-brand-sand-green" name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-brand-dark-stone rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                {{ __('Lupa password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- PERBAIKAN: Menambah jarak ke tombol (mt-8) -->
                    <div class="mt-8">
                        <button type="submit" class="w-full justify-center px-6 py-3 bg-brand-sand-green hover:bg-brand-sand-green-dark text-white rounded-lg font-bold">
                            Log In
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
