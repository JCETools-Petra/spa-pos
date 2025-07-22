<x-app-layout>
    <x-slot name="header">
        {{ __('Laporan Penjualan') }}
    </x-slot>

    <!-- Form Filter -->
    <div class="bg-white p-6 rounded-xl shadow-sm mb-6">
        <h3 class="font-semibold text-lg text-brand-dark-stone mb-4">Filter Laporan</h3>
        <form action="{{ route('admin.reports.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                </div>
                <div>
                    <label for="branch_id" class="block text-sm font-medium text-gray-700">Cabang</label>
                    <select name="branch_id" id="branch_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                        <option value="">Semua Cabang</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="self-end grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <button type="submit" class="w-full bg-brand-sand-green hover:bg-brand-sand-green-dark text-white font-bold py-2 px-4 rounded-lg">
                        Filter
                    </button>
                    <a href="{{ route('admin.reports.export', request()->query()) }}" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg text-center">
                        Excel
                    </a>
                    <a href="{{ route('admin.reports.export.pdf', request()->query()) }}" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg text-center">
                        PDF
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Hasil Laporan -->
    <div class="bg-white p-6 rounded-xl shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-lg text-brand-dark-stone">Hasil Laporan</h3>
            <div class="text-right">
                <p class="text-sm text-gray-500">Total Pendapatan</p>
                <p class="text-xl font-bold">Rp {{ number_format($totalRevenue) }}</p>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-brand-ivory">
                    <tr>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Cabang</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Detail Item</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Kasir</th>
                        <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-brand-ivory/50">
                            <td class="py-3 px-4 whitespace-nowrap text-sm">{{ $trx->invoice_number }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm">{{ $trx->branch->name }}</td>
                            <td class="py-3 px-4 text-sm">
                                <!-- Tampilkan Paket Jasa jika ada -->
                                @if($trx->package)
                                    <div>
                                        <strong>Jasa:</strong> {{ $trx->package->name }}
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
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-500">{{ $trx->cashier->name }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm font-semibold text-right">Rp {{ number_format($trx->total_amount) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-500">
                                <p>Tidak ada data yang cocok dengan filter Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
