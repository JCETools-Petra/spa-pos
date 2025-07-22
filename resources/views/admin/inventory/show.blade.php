<x-app-layout>
    <x-slot name="header">
        {{ __('Atur Inventaris untuk Cabang: ') . $branch->name }}
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm">
        <form action="{{ route('admin.inventory.update', $branch) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-brand-ivory">
                        <tr>
                            <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                            <th class="py-2 px-4 text-center text-xs font-medium text-gray-500 uppercase">Tersedia</th>
                            <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Harga Jual (Rp)</th>
                            <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($allProducts as $product)
                            @php
                                $inventory = $branchProducts->get($product->id);
                                $isAvailable = !is_null($inventory);
                                $sellingPrice = $inventory->pivot->selling_price ?? $product->selling_price;
                                $stockQuantity = $inventory->pivot->stock_quantity ?? 0;
                            @endphp
                            <tr class="hover:bg-brand-ivory/50">
                                <td class="py-3 px-4 font-medium">{{ $product->name }}</td>
                                <td class="py-3 px-4 text-center">
                                    <input type="checkbox" name="products[{{ $product->id }}][is_available]" value="1" 
                                           class="rounded border-gray-300 text-brand-sand-green-dark shadow-sm focus:ring-brand-sand-green-dark"
                                           @if($isAvailable) checked @endif>
                                </td>
                                <td class="py-3 px-4">
                                    <input type="number" name="products[{{ $product->id }}][selling_price]" 
                                           value="{{ $sellingPrice }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm text-sm">
                                </td>
                                <td class="py-3 px-4">
                                    <input type="number" name="products[{{ $product->id }}][stock_quantity]" 
                                           value="{{ $stockQuantity }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm text-sm">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.inventory.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg mr-3">Kembali ke Daftar Cabang</a>
                <button type="submit" class="px-6 py-2 bg-brand-sand-green hover:bg-brand-sand-green-dark text-white rounded-lg">
                    Simpan Inventaris
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
