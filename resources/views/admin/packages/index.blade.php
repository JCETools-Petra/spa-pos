<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Paket SPA') }}
            </h2>
            <a href="{{ route('admin.packages.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                + Tambah Paket Baru
            </a>
        </div>
    </x-slot>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 border-b">Nama Paket</th>
                    <th class="py-2 px-4 border-b">Cabang</th>
                    <th class="py-2 px-4 border-b">Harga</th>
                    <th class="py-2 px-4 border-b">Durasi (Menit)</th>
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($packages as $package)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b">{{ $package->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $package->branch->name ?? 'N/A' }}</td>
                        <td class="py-2 px-4 border-b text-right">Rp {{ number_format($package->price) }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $package->duration_minutes }}</td>
                        <td class="py-2 px-4 border-b text-center">
                            @if($package->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-2 px-4 border-b">
                            <div class="flex space-x-2 justify-center">
                                <a href="{{ route('admin.packages.edit', $package) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Belum ada data paket.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $packages->links() }}
    </div>
</x-app-layout>