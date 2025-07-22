<x-app-layout>
    <x-slot name="header">
        {{ __('Edit Data Pengeluaran') }}
    </x-slot>
    
    <form action="{{ route('branch.expenses.update', $expense) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4 max-w-lg mx-auto">
            <div>
                <label for="expense_date" class="block text-sm font-medium text-gray-700">Tanggal Pengeluaran</label>
                <input type="date" name="expense_date" id="expense_date" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}">
            </div>

            <div>
                <label for="expense_category_id" class="block text-sm font-medium text-gray-700">Kategori Pengeluaran</label>
                <select name="expense_category_id" id="expense_category_id" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('expense_category_id', $expense->expense_category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="amount_display" class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
                <input type="text" id="amount_display" required placeholder="Contoh: 150.000" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                <input type="hidden" name="amount" id="amount" value="{{ old('amount', $expense->amount) }}">
                @error('amount')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" id="description" rows="4" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">{{ old('description', $expense->description) }}</textarea>
            </div>

            <div class="flex justify-end pt-4">
                 <a href="{{ route('branch.expenses.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3">Batal</a>
                <button type="submit" class="px-4 py-2 bg-brand-sand-green hover:bg-brand-sand-green-dark text-white rounded-lg">
                    Update Pengeluaran
                </button>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountDisplay = document.getElementById('amount_display');
            const amountHidden = document.getElementById('amount');

            // Fungsi untuk memformat angka
            function formatNumber(value) {
                if (!value) return '';
                return new Intl.NumberFormat('id-ID').format(value);
            }

            // Atur nilai awal saat halaman dimuat
            amountDisplay.value = formatNumber(amountHidden.value);

            amountDisplay.addEventListener('input', function(e) {
                let rawValue = e.target.value.replace(/[^0-9]/g, '');
                amountHidden.value = rawValue;
                e.target.value = formatNumber(rawValue);
            });
        });
    </script>
</x-app-layout>