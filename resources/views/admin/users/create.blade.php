<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pengguna Baru') }}
        </h2>
    </x-slot>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" id="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('name') }}">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('email') }}">
            </div>
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Peran</label>
                <select name="role" id="role" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="admin" @selected(old('role') == 'admin')>Admin</option>
                    {{-- OPSI BARU --}}
                    <option value="owner" @selected(old('role') == 'owner')>Owner</option>
                    <option value="branch_user" @selected(old('role') == 'branch_user')>User Cabang</option>
                </select>
            </div>
            <div id="branch-selection" class="hidden">
                <label for="branch_id" class="block text-sm font-medium text-gray-700">Cabang</label>
                <select name="branch_id" id="branch_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="flex justify-end">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 mr-2">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
            </div>
        </div>
    </form>
    <script>
        // Script untuk menampilkan/menyembunyikan pilihan cabang
        document.getElementById('role').addEventListener('change', function () {
            const branchSelection = document.getElementById('branch-selection');
            if (this.value === 'branch_user') {
                branchSelection.classList.remove('hidden');
            } else {
                branchSelection.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>