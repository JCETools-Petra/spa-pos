<x-app-layout>
    <x-slot name="header">
        {{ __('Tambah Master Produk Baru') }}
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm max-w-2xl mx-auto">
        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium">Nama Produk</label>
                    <input type="text" name="name" id="name" required placeholder="Contoh: Minyak Esensial Lavender 100ml" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('name') }}">
                    @error('name')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                </div>
                
                <div>
                    <label for="sku" class="block text-sm font-medium">SKU (Stock Keeping Unit) - Opsional</label>
                    <input type="text" name="sku" id="sku" placeholder="Contoh: SPA-LVD-100" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('sku') }}">
                    @error('sku')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label for="selling_price_display" class="block text-sm font-medium">Harga Jual Default (Rp)</label>
                    <input type="text" id="selling_price_display" required placeholder="Contoh: 150.000" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                    <input type="hidden" name="selling_price" id="selling_price" value="{{ old('selling_price') }}">
                    @error('selling_price')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium">Deskripsi (Opsional)</label>
                    <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">{{ old('description') }}</textarea>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg mr-3">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-brand-sand-green hover:bg-brand-sand-green-dark text-white rounded-lg">
                        Simpan Produk
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        const priceDisplay = document.getElementById('selling_price_display');
        const priceHidden = document.getElementById('selling_price');

        priceDisplay.addEventListener('input', function(e) {
            let rawValue = e.target.value.replace(/[^0-9]/g, '');
            priceHidden.value = rawValue;
            if (rawValue) {
                e.target.value = new Intl.NumberFormat('id-ID').format(rawValue);
            } else {
                e.target.value = '';
            }
        });
    </script>
</x-app-layout>
