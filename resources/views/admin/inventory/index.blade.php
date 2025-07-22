<x-app-layout>
    <x-slot name="header">
        {{ __('Manajemen Inventaris - Pilih Cabang') }}
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm">
        <h3 class="font-semibold text-lg text-brand-dark-stone mb-4">Pilih Cabang</h3>
        <p class="text-sm text-gray-600 mb-6">Pilih salah satu cabang di bawah ini untuk mengatur produk, harga, dan stok yang tersedia di cabang tersebut.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($branches as $branch)
                <a href="{{ route('admin.inventory.show', $branch) }}" class="block p-4 border rounded-lg hover:bg-brand-ivory/50 hover:shadow-md transition-all">
                    <div class="font-semibold">{{ $branch->name }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ $branch->products_count }} Produk Tersedia</div>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
