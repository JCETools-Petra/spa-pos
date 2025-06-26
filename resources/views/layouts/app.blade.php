<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $appSettings['app_title'] ?? config('app.name', 'Laravel') }}</title>
        
        @if(isset($appSettings['app_favicon']))
            <link rel="icon" href="{{ $appSettings['app_favicon'] }}" type="image/x-icon">
        @endif

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- ========================================================== -->
        <!-- == PERBAIKAN UTAMA ADA DI SINI == -->
        <!-- 1. Muat AlpineJS Core DULU -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <!-- 2. Muat Plugin Persist SETELAHNYA -->
        <script src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
        <!-- ========================================================== -->
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-brand-ivory text-brand-dark-stone">
        <!-- State sidebar sekarang cerdas: terbuka di desktop (>=1024px), tertutup di mobile/tablet -->
        <div x-data="{ sidebarOpen: $persist(window.innerWidth >= 1024).as('sidebar_open_state') }" class="relative min-h-screen md:flex">
            <!-- Sidebar -->
            <aside 
                class="fixed inset-y-0 left-0 z-30 w-64 bg-brand-deep-teal text-gray-300 transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0"
                :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
            >
                <!-- Konten Sidebar (tidak berubah) -->
                <div class="flex flex-col h-full">
                    <div class="h-20 flex-shrink-0 flex items-center justify-center px-4">
                        @if(isset($appSettings['app_logo']))
                            <img src="{{ $appSettings['app_logo'] }}" alt="Logo" class="h-12">
                        @else
                            <span class="text-2xl font-bold text-white">{{ $appSettings['app_title'] ?? 'SPA POS' }}</span>
                        @endif
                    </div>
                    
                    <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">
                        @php
                            function menuLink($routeName, $iconName, $label) {
                                $activeClasses = 'bg-black/20 text-white';
                                $inactiveClasses = 'text-gray-300 hover:bg-black/20 hover:text-white';
                                $classes = request()->routeIs($routeName) ? $activeClasses : $inactiveClasses;
                                return '<a href="' . route(str_replace('*', 'index', $routeName)) . '" class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 ' . $classes . '"><span class="w-6 h-6">' . svg($iconName, 'w-6 h-6')->toHtml() . '</span><span class="ml-3">' . $label . '</span></a>';
                            }
                        @endphp
                        
                        @if(Auth::user()->isAdmin())
                            {!! menuLink('admin.dashboard', 'heroicon-o-home', 'Dashboard') !!}
                            <div class="px-4 pt-4 pb-2 text-xs text-gray-400 uppercase tracking-wider">Master Data</div>
                            {!! menuLink('admin.branches.*', 'heroicon-o-share', 'Manajemen Cabang') !!}
                            {!! menuLink('admin.packages.*', 'heroicon-o-gift', 'Paket Jasa') !!}
                            {!! menuLink('admin.products.*', 'heroicon-o-cube', 'Produk') !!}
                            {!! menuLink('admin.users.*', 'heroicon-o-users', 'User') !!}
                            {!! menuLink('admin.expense-categories.*', 'heroicon-o-tag', 'Kategori Pengeluaran') !!}
                            <div class="px-4 pt-4 pb-2 text-xs text-gray-400 uppercase tracking-wider">Operasional</div>
                            {!! menuLink('admin.inventory.index', 'heroicon-o-archive-box', 'Manajemen Inventaris') !!}
                            <div class="px-4 pt-4 pb-2 text-xs text-gray-400 uppercase tracking-wider">Laporan</div>
                            {!! menuLink('admin.reports.index', 'heroicon-o-document-text', 'Laporan Transaksi') !!}
                            {!! menuLink('admin.branch-performance.index', 'heroicon-o-chart-bar-square', 'Performa Cabang') !!}
                            <div class="px-4 pt-4 pb-2 text-xs text-gray-400 uppercase tracking-wider">Sistem</div>
                            {!! menuLink('admin.activity-logs.*', 'heroicon-o-shield-check', 'Log Aktivitas') !!}
                            {!! menuLink('admin.settings.*', 'heroicon-o-cog-6-tooth', 'Pengaturan Web') !!}
                        @else
                            <div class="px-4 py-2 mt-4 text-xs text-gray-400 uppercase tracking-wider">{{ session('branch_name', 'Cabang') }}</div>
                            {!! menuLink('branch.dashboard', 'heroicon-o-home', 'Dashboard') !!}
                            {!! menuLink('pos.create', 'heroicon-o-shopping-cart', 'Kasir (POS)') !!}
                            {!! menuLink('pos.history', 'heroicon-o-clock', 'History Pembelian') !!}
                            {!! menuLink('branch.expenses.*', 'heroicon-o-arrow-trending-down', 'Catat Pengeluaran') !!}
                        @endif
                    </nav>

                    <div class="p-4 border-t border-white/10 flex-shrink-0">
                         <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-brand-sand flex items-center justify-center font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            <div class="ml-3">
                                 <div class="text-sm font-semibold text-white">{{ Auth::user()->name }}</div>
                                 <div class="text-xs text-gray-300">{{ Auth::user()->email }}</div>
                            </div>
                         </div>
                         <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-2 text-sm text-gray-300 hover:bg-red-600 hover:text-white rounded-lg transition-colors duration-200">
                                <span class="w-6 h-6 mr-3">@svg('heroicon-o-arrow-left-on-rectangle', 'w-5 h-5')</span>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </aside>
            
            <!-- Overlay untuk mobile saat menu terbuka -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black opacity-50 z-20 md:hidden" style="display: none;"></div>

            <!-- Konten Utama dengan margin dinamis -->
            <div class="flex-1 flex flex-col overflow-hidden transition-all duration-300 ease-in-out" :class="{'md:ml-64': sidebarOpen}">
                <header class="bg-white border-b border-gray-200 flex-shrink-0">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8 flex items-center">
                        <!-- Tombol Toggle untuk semua ukuran layar -->
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none">
                            @svg('heroicon-o-bars-3', 'w-6 h-6')
                        </button>

                        @if (isset($header))
                            <h1 class="text-2xl font-bold text-brand-dark-stone ml-4">
                                {{ $header }}
                            </h1>
                        @endif
                    </div>
                </header>
                
                <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 sm:p-6 lg:p-8">
                    @if (session('success'))
                        <div class="bg-brand-sand-green-light border border-brand-sand-green text-sm text-brand-sand-green-dark px-4 py-3 rounded-lg relative mb-6" role="alert">
                            <strong class="font-bold">Sukses!</strong> <span class="block sm:inline ml-2">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                         <div class="bg-red-50 border border-red-200 text-sm text-red-800 px-4 py-3 rounded-lg relative mb-6" role="alert">
                            <strong class="font-bold">Error!</strong> <span class="block sm:inline ml-2">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="bg-white p-4 sm:p-6 lg:p-8 rounded-xl shadow-sm">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
