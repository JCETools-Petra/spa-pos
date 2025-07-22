<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard Cabang') }}
    </x-slot>

    <!-- 1. KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <!-- ... (bagian card yang sudah ada, tidak perlu diubah) ... -->
        <div class="bg-white p-6 rounded-xl shadow-sm flex items-center space-x-4">
            <div class="bg-brand-sand-green-light p-3 rounded-full">
                @svg('heroicon-o-currency-dollar', 'w-8 h-8 text-brand-sand-green-dark')
            </div>
            <div>
                <p class="text-sm text-gray-500">Pendapatan Cabang Hari Ini</p>
                <p class="text-2xl font-bold">Rp {{ number_format($revenueToday) }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm flex items-center space-x-4">
            <div class="bg-brand-light-teal/50 p-3 rounded-full">
                @svg('heroicon-o-shopping-cart', 'w-8 h-8 text-brand-deep-teal')
            </div>
            <div>
                <p class="text-sm text-gray-500">Transaksi Cabang Hari Ini</p>
                <p class="text-2xl font-bold">{{ $transactionsToday }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm flex items-center space-x-4">
            <div class="bg-brand-sand/30 p-3 rounded-full">
                @svg('heroicon-o-gift', 'w-8 h-8 text-brand-sand')
            </div>
            <div>
                <p class="text-sm text-gray-500">Jumlah Paket Aktif</p>
                <p class="text-2xl font-bold">{{ $activePackages }}</p>
            </div>
        </div>
    </div>

    <!-- 2. Aktivitas Terbaru di Cabang (BAGIAN YANG DIPERBAIKI) -->
    <div class="bg-white p-6 rounded-xl shadow-sm">
        <h3 class="font-semibold text-lg text-brand-dark-stone mb-4">Aktivitas Terbaru di Cabang Anda</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail Item</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diinput Oleh</th>
                        <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentTransactions as $trx)
                        <tr class="hover:bg-brand-ivory/50">
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-500 align-top">{{ $trx->created_at->format('H:i') }}</td>
                            <td class="py-3 px-4 text-sm align-top">
                                <!-- Tampilkan Paket Jasa jika ada -->
                                @if($trx->package)
                                    <div>
                                        <strong>Jasa:</strong> {{ $trx->package->name }} 
                                        @if($trx->therapist) <span class="text-gray-500 text-xs">({{ $trx->therapist->name }})</span> @endif
                                    </div>
                                @endif
                                <!-- Tampilkan Produk jika ada -->
                                @if($trx->products->isNotEmpty())
                                    <ul class="list-disc list-inside mt-1">
                                        @foreach($trx->products as $product)
                                            <li>{{ $product->name }} ({{ $product->pivot->quantity }}x)</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-500 align-top">{{ $trx->cashier->name }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm font-semibold text-right align-top">Rp {{ number_format($trx->total_amount) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-sm text-gray-500">Belum ada transaksi hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
