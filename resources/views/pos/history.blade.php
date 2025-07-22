<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-brand-dark-stone leading-tight">
            {{ __('Riwayat Transaksi') }}
        </h2>
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-brand-ivory">
                    <tr>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Detail Item</th>
                        <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Kasir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($transactions as $trx)
                        <tr class="hover:bg-brand-ivory/50">
                            <td class="py-3 px-4 whitespace-nowrap text-sm font-mono">{{ $trx->invoice_number }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm">{{ $trx->customer_name }}</td>
                            <td class="py-3 px-4 text-sm">
                                <!-- Tampilkan Paket Jasa jika ada -->
                                @if($trx->package)
                                    <div>
                                        <strong>Jasa:</strong> {{ $trx->package->name }} 
                                        @if($trx->therapist) (Terapis: {{ $trx->therapist->name }}) @endif
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
                            <td class="py-3 px-4 whitespace-nowrap text-right text-sm font-semibold">Rp {{ number_format($trx->total_amount) }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm">{{ $trx->cashier->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-500">
                                Belum ada data transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</x-app-layout>
