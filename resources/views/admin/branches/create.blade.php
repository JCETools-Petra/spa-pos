<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Cabang Baru') }}
        </h2>
    </x-slot>

    <form action="{{ route('admin.branches.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Cabang</label>
                <input type="text" name="name" id="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('name') }}">
                @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="address" id="address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('address') }}</textarea>
                @error('address')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input type="text" name="phone_number" id="phone_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('phone_number') }}">
                @error('phone_number')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div class="flex justify-end">
                <a href="{{ route('admin.branches.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 mr-2">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
            </div>
        </div>
    </form>
</x-app-layout>