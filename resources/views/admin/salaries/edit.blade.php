<x-app-layout>
    <x-slot name="header">
        {{ __('Atur Gaji Karyawan') }}
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm max-w-lg mx-auto">
        <!-- Info User -->
        <div class="mb-6 pb-4 border-b">
            <p class="text-sm text-gray-500">Karyawan</p>
            <p class="font-semibold text-lg">{{ $user->name }}</p>
            <p class="text-sm text-gray-500">{{ $user->branch->name ?? 'Belum ada cabang' }}</p>
        </div>

        <form action="{{ route('admin.users.salary.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label for="amount_display" class="block text-sm font-medium">Gaji Bulanan (Rp)</label>
                    <input type="text" id="amount_display" required placeholder="Contoh: 3.500.000" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                    <input type="hidden" name="amount" id="amount" value="{{ old('amount', $salary->amount) }}">
                    @error('amount')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                </div>
                
                <div>
                    <label for="notes" class="block text-sm font-medium">Catatan (Opsional)</label>
                    <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">{{ old('notes', $salary->notes) }}</textarea>
                </div>

                <div class="flex justify-end pt-4">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg mr-3">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-brand-sand-green hover:bg-brand-sand-green-dark text-white rounded-lg">
                        Simpan Gaji
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountDisplay = document.getElementById('amount_display');
            const amountHidden = document.getElementById('amount');

            function formatNumber(value) {
                if (!value) return '';
                return new Intl.NumberFormat('id-ID').format(value);
            }
            
            amountDisplay.value = formatNumber(amountHidden.value);

            amountDisplay.addEventListener('input', function(e) {
                let rawValue = e.target.value.replace(/[^0-9]/g, '');
                amountHidden.value = rawValue;
                e.target.value = formatNumber(rawValue);
            });
        });
    </script>
</x-app-layout>
