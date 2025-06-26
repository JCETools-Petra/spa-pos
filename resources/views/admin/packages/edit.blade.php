<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Paket: ') . $package->name }}
        </h2>
    </x-slot>

    <form action="{{ route('admin.packages.update', $package) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4 max-w-lg mx-auto">
            <div>
                <label for="branch_id" class="block text-sm font-medium text-gray-700">Cabang</label>
                <select name="branch_id" id="branch_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id', $package->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
                @error('branch_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Paket</label>
                <input type="text" name="name" id="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('name', $package->name) }}">
                @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $package->description) }}</textarea>
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                <input type="number" name="price" id="price" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('price', $package->price) }}">
                @error('price')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
             <div>
                <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Durasi (Menit)</label>
                <input type="number" name="duration_minutes" id="duration_minutes" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('duration_minutes', $package->duration_minutes) }}">
                @error('duration_minutes')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
             <div>
                <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="is_active" id="is_active" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="1" {{ old('is_active', $package->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active', $package->is_active) == 0 ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="flex justify-end pt-4">
                <a href="{{ route('admin.packages.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 mr-2">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Paket</button>
            </div>
        </div>
    </form>
</x-app-layout>