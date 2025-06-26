<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <!-- 1. KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl shadow-sm flex items-center space-x-4">
            <div class="bg-brand-sand-green-light p-3 rounded-full">
                @svg('heroicon-o-currency-dollar', 'w-8 h-8 text-brand-sand-green-dark')
            </div>
            <div>
                <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
                <p class="text-2xl font-bold">Rp {{ number_format($revenueToday) }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm flex items-center space-x-4">
            <div class="bg-brand-light-teal/50 p-3 rounded-full">
                @svg('heroicon-o-shopping-cart', 'w-8 h-8 text-brand-deep-teal')
            </div>
            <div>
                <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
                <p class="text-2xl font-bold">{{ $transactionsToday }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm flex items-center space-x-4">
            <div class="bg-brand-sand/30 p-3 rounded-full">
                @svg('heroicon-o-share', 'w-8 h-8 text-brand-sand')
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Cabang</p>
                <p class="text-2xl font-bold">{{ $totalBranches }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm flex items-center space-x-4">
            <div class="bg-gray-200 p-3 rounded-full">
                @svg('heroicon-o-users', 'w-8 h-8 text-gray-600')
            </div>
            <div>
                <p class="text-sm text-gray-500">Total User</p>
                <p class="text-2xl font-bold">{{ $totalUsers }}</p>
            </div>
        </div>
    </div>

    <!-- 2. Grafik dan Aktivitas Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik Penjualan -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm">
            <h3 class="font-semibold text-lg mb-4">Penjualan 7 Hari Terakhir</h3>
            <div class="relative h-96">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Aktivitas Terbaru (BAGIAN YANG DIPERBAIKI) -->
        <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-sm">
            <h3 class="font-semibold text-lg mb-4">Aktivitas Terbaru</h3>
            <div class="space-y-4">
                @forelse($recentTransactions as $trx)
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 bg-brand-ivory p-2 rounded-full">
                            @svg('heroicon-o-receipt-percent', 'w-5 h-5 text-brand-sand')
                        </div>
                        <div class="flex-1 min-w-0">
                            {{-- Tampilkan detail item dengan cerdas --}}
                            <div class="text-sm font-medium truncate">
                                @if($trx->package)
                                    Jasa: {{ $trx->package->name }}
                                @elseif($trx->products->isNotEmpty())
                                    Produk: {{ $trx->products->first()->name }}
                                    @if($trx->products->count() > 1)
                                        <span class="text-gray-500 text-xs">+ {{ $trx->products->count() - 1 }} lainnya</span>
                                    @endif
                                @else
                                    Transaksi
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 truncate">{{ $trx->branch->name }}</p>
                        </div>
                        <div class="text-sm font-semibold text-right">
                            Rp {{ number_format($trx->total_amount) }}
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada transaksi.</p>
                @endforelse
            </div>
        </div>
    </div>
    <!-- =================================================================== -->
    <!-- BAGIAN BARU: Ringkasan Penjualan per Cabang (Hari Ini) -->
    <!-- =================================================================== -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-brand-dark-stone mb-4">Ringkasan Penjualan per Cabang (Hari Ini)</h2>
        @if(empty($salesByBranch))
            <div class="bg-white p-6 rounded-xl shadow-sm text-center text-gray-500">
                Belum ada penjualan di cabang manapun hari ini.
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($salesByBranch as $branchData)
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <div class="flex justify-between items-center border-b pb-3 mb-3">
                            <h3 class="font-bold text-lg text-brand-deep-teal">{{ $branchData['branch_name'] }}</h3>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Total Pendapatan</p>
                                <p class="font-bold text-brand-dark-stone">Rp {{ number_format($branchData['total_revenue']) }}</p>
                            </div>
                        </div>

                        <!-- Detail Penjualan -->
                        <div class="space-y-4">
                            <!-- Penjualan Jasa -->
                            <div>
                                <h4 class="font-semibold text-sm mb-2">Jasa Terjual</h4>
                                <ul class="space-y-1 text-sm">
                                    @forelse($branchData['packages'] as $package)
                                        <li class="flex justify-between items-center">
                                            <span>{{ $package->name }} <span class="text-gray-500">({{ $package->quantity }}x)</span></span>
                                            <span class="font-medium">Rp {{ number_format($package->revenue) }}</span>
                                        </li>
                                    @empty
                                        <li class="text-gray-400 text-xs">Tidak ada jasa terjual hari ini.</li>
                                    @endforelse
                                </ul>
                            </div>

                            <!-- Penjualan Produk -->
                            <div>
                                <h4 class="font-semibold text-sm mb-2">Produk Terjual</h4>
                                <ul class="space-y-1 text-sm">
                                    @forelse($branchData['products'] as $product)
                                        <li class="flex justify-between items-center">
                                            <span>{{ $product->name }} <span class="text-gray-500">({{ $product->quantity }}x)</span></span>
                                            <span class="font-medium">Rp {{ number_format($product->revenue) }}</span>
                                        </li>
                                    @empty
                                        <li class="text-gray-400 text-xs">Tidak ada produk terjual hari ini.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Pendapatan',
                        data: @json($chartData),
                        backgroundColor: '#C4C5B3',
                        borderColor: '#a2a48f',
                        borderWidth: 1,
                        borderRadius: 5,
                        hoverBackgroundColor: '#a2a48f',
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, ticks: { callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value) }}},
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: context => 'Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y) }}
                    }
                }
            });
        });
    </script>
</x-app-layout>
