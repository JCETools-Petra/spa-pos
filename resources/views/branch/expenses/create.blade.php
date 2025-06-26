<x-app-layout>
    <x-slot name="header">
        {{ __('Catat Pengeluaran Baru') }}
    </x-slot>

    <form action="{{ route('branch.expenses.store') }}" method="POST">
        @csrf
        <div class="space-y-4 max-w-lg mx-auto">
            <div>
                <label for="expense_date" class="block text-sm font-medium text-gray-700">Tanggal Pengeluaran</label>
                <input type="date" name="expense_date" id="expense_date" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" value="{{ now()->format('Y-m-d') }}">
            </div>

            <div>
                <label for="expense_category_id" class="block text-sm font-medium text-gray-700">Kategori Pengeluaran</label>
                <select name="expense_category_id" id="expense_category_id" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="amount_display" class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
                <input type="text" id="amount_display" required placeholder="Contoh: 150.000" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                <input type="hidden" name="amount" id="amount">
                @error('amount')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" id="description" rows="4" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" placeholder="Contoh: Pembelian 5 botol minyak massage lavender"></textarea>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-4 py-2 bg-brand-sand-green hover:bg-brand-sand-green-dark text-white rounded-lg">
                    Simpan Pengeluaran
                </button>
            </div>
        </div>
    </form>

    <script>
        const amountDisplay = document.getElementById('amount_display');
        const amountHidden = document.getElementById('amount');

        amountDisplay.addEventListener('input', function(e) {
            // 1. Ambil nilai, hapus semua karakter kecuali angka
            let rawValue = e.target.value.replace(/[^0-9]/g, '');

            // 2. Simpan nilai angka mentah ke input tersembunyi
            amountHidden.value = rawValue;

            // 3. Format nilai yang ditampilkan dengan titik
            if (rawValue) {
                e.target.value = new Intl.NumberFormat('id-ID').format(rawValue);
            } else {
                e.target.value = '';
            }
        });
    </script>
</x-app-layout>