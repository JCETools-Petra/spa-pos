<x-app-layout>
    <x-slot name="header">
        {{ __('Edit User: ') . $user->name }}
    </x-slot>

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="max-w-3xl mx-auto space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" id="name" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('name', $user->name) }}">
                @error('name')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                <input type="email" name="email" id="email" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('email', $user->email) }}">
                 @error('email')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="branch_id" class="block text-sm font-medium text-gray-700">Tugaskan ke Cabang</label>
                <select name="branch_id" id="branch_id" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
                @error('branch_id')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
            </div>
            
            <div class="border-t border-gray-200 pt-6">
                <p class="text-sm text-gray-600">Ganti Password</p>
                <p class="text-xs text-gray-500">Kosongkan kolom password jika Anda tidak ingin mengubahnya.</p>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                <input type="password" name="password" id="password" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                @error('password')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
            </div>

            <div class="border-t border-gray-200 pt-6 flex justify-end">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3">Batal</a>
                <button type="submit" class="px-4 py-2 bg-teal-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-teal-700">
                    Update User
                </button>
            </div>
        </div>
    </form>
</x-app-layout>