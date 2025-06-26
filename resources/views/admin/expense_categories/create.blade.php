<x-app-layout>
    <x-slot name="header">
        {{ __('Tambah Kategori Pengeluaran Baru') }}
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm max-w-lg mx-auto">
        <form action="{{ route('admin.expense-categories.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium">Nama Kategori</label>
                    <input type="text" name="name" id="name" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('name') }}">
                    @error('name')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium">Deskripsi (Opsional)</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">{{ old('description') }}</textarea>
                </div>

                <div class="flex justify-end pt-4">
                    <a href="{{ route('admin.expense-categories.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg mr-3">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-brand-sand-green hover:bg-brand-sand-green-dark text-white rounded-lg">
                        Simpan Kategori
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>