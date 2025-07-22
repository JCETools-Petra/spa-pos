<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Cabang') }}
            </h2>
            {{-- Tombol ini hanya muncul jika pengguna BUKAN Owner --}}
            @if(!Auth::user()->isOwner())
            <a href="{{ route('admin.branches.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                + Tambah Cabang Baru
            </a>
            @endif
        </div>
    </x-slot>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 border-b">Nama Cabang</th>
                    <th class="py-2 px-4 border-b">Alamat</th>
                    <th class="py-2 px-4 border-b">Telepon</th>
                    <th class="py-2 px-4 border-b">Jumlah User</th>
                    {{-- Kolom Aksi hanya muncul jika pengguna BUKAN Owner --}}
                    @if(!Auth::user()->isOwner())
                    <th class="py-2 px-4 border-b">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($branches as $branch)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b">{{ $branch->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $branch->address }}</td>
                        <td class="py-2 px-4 border-b">{{ $branch->phone_number }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $branch->users_count }}</td>
                        {{-- Sel Aksi hanya muncul jika pengguna BUKAN Owner --}}
                        @if(!Auth::user()->isOwner())
                        <td class="py-2 px-4 border-b">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.branches.edit', $branch) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus cabang ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Belum ada data cabang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $branches->links() }}
    </div>
</x-app-layout>