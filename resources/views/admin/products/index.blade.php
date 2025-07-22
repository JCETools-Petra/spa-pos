<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-brand-dark-stone leading-tight">
                {{ __('Master Produk') }}
            </h2>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-sand-green hover:bg-brand-sand-green-dark text-white rounded-lg font-semibold text-xs uppercase tracking-widest">
                + Tambah Produk Baru
            </a>
        </div>
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-brand-ivory">
                    <tr>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                        <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase">Harga Jual Default</th>
                        <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr class="hover:bg-brand-ivory/50">
                            <td class="py-3 px-4 whitespace-nowrap text-sm font-mono text-gray-600">{{ $product->sku ?? '-' }}</td>
                            <td class="py-3 px-4 whitespace-nowrap font-medium">{{ $product->name }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-right font-semibold">Rp {{ number_format($product->selling_price) }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-right text-sm">
                                <div class="flex space-x-2 justify-end">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-10 text-gray-500">
                                Belum ada master produk yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>
