<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-brand-dark-stone leading-tight">
                {{ __('Manajemen User') }}
            </h2>
            <!-- Warna tombol disesuaikan -->
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-sand-green hover:bg-brand-sand-green-dark border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest">
                + Tambah User Baru
            </a>
        </div>
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <!-- Header tabel disesuaikan -->
                <thead class="bg-brand-ivory">
                    <tr>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cabang</th>
                        <th class="py-3 px-6 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <!-- Hover baris disesuaikan -->
                        <tr class="hover:bg-brand-ivory/50">
                            <td class="py-4 px-6 whitespace-nowrap">{{ $user->name }}</td>
                            <td class="py-4 px-6 whitespace-nowrap">{{ $user->email }}</td>
                            <td class="py-4 px-6 whitespace-nowrap">{{ $user->branch->name ?? 'N/A' }}</td>
                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                <!-- Tombol aksi diperbarui -->
                                <div class="flex space-x-4 justify-end">
                                    <!-- PERBAIKAN LINK TOMBOL GAJI -->
                                    <a href="{{ route('admin.users.salary.edit', $user) }}" class="text-green-600 hover:text-green-900 font-medium">Gaji</a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Belum ada data user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
