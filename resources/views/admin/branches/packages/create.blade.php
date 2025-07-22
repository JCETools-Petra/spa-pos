<x-app-layout>
    <x-slot name="header">
        {{ __('Tambah Paket Baru untuk Cabang Anda') }}
    </x-slot>

    <form action="{{ route('branch.packages.store') }}" method="POST">
        @csrf
        <div class="space-y-4 max-w-lg mx-auto">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Paket</label>
                <input type="text" name="name" id="name" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('name') }}">
                @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">{{ old('description') }}</textarea>
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                <input type="number" name="price" id="price" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('price') }}">
                @error('price')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
             <div>
                <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Durasi (Menit)</label>
                <input type="number" name="duration_minutes" id="duration_minutes" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('duration_minutes') }}">
                @error('duration_minutes')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div class="flex justify-end pt-4">
                <a href="{{ route('branch.packages.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3">Batal</a>
                <button type="submit" class="px-4 py-2 bg-teal-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-teal-700">Simpan Paket</button>
            </div>
        </div>
    </form>
</x-app-layout>