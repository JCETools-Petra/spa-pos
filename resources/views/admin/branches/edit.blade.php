<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Cabang: ') . $branch->name }}
        </h2>
    </x-slot>

    <form action="{{ route('admin.branches.update', $branch) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Cabang</label>
                <input type="text" name="name" id="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('name', $branch->name) }}">
                @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="address" id="address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('address', $branch->address) }}</textarea>
                @error('address')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input type="text" name="phone_number" id="phone_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('phone_number', $branch->phone_number) }}">
                @error('phone_number')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="profit_sharing_percentage" class="block text-sm font-medium text-gray-700">Persentase Bagi Hasil Jasa (%)</label>
                <input type="number" name="profit_sharing_percentage" id="profit_sharing_percentage" 
                       step="0.01" min="0" max="100" placeholder="Contoh: 15.5"
                       class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" 
                       value="{{ old('profit_sharing_percentage', $branch->profit_sharing_percentage) }}">
                <p class="text-xs text-gray-500 mt-1">Isi dengan persentase bagi hasil dengan pemilik tempat. Hanya berlaku untuk pendapatan jasa.</p>
            </div>
            <div class="flex justify-end">
                <a href="{{ route('admin.branches.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 mr-2">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update</button>
            </div>
        </div>
    </form>
</x-app-layout>