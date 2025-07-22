<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Paket Cabang Anda') }}
            </h2>
            <a href="{{ route('branch.packages.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700">
                + Tambah Paket Baru
            </a>
        </div>
    </x-slot>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Paket</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi (Menit)</th>
                    <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="py-3 px-6 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($packages as $package)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-6 whitespace-nowrap">{{ $package->name }}</td>
                        <td class="py-4 px-6 whitespace-nowrap">Rp {{ number_format($package->price) }}</td>
                        <td class="py-4 px-6 whitespace-nowrap">{{ $package->duration_minutes }}</td>
                        <td class="py-4 px-6 whitespace-nowrap text-center">
                            @if($package->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap text-right">
                            <div class="flex space-x-2 justify-end">
                                <a href="{{ route('branch.packages.edit', $package) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('branch.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Belum ada data paket untuk cabang Anda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $packages->links() }}
    </div>
</x-app-layout>