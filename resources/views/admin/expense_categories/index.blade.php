<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-brand-dark-stone leading-tight">
                {{ __('Kategori Pengeluaran') }}
            </h2>
            <a href="{{ route('admin.expense-categories.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-sand-green hover:bg-brand-sand-green-dark text-white rounded-lg font-semibold text-xs uppercase tracking-widest">
                + Tambah Kategori Baru
            </a>
        </div>
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-brand-ivory">
                    <tr>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Nama Kategori</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                        <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr class="hover:bg-brand-ivory/50">
                            <td class="py-3 px-4 whitespace-nowrap font-medium">{{ $category->name }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $category->description }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-right text-sm">
                                <div class="flex space-x-2 justify-end">
                                    <a href="{{ route('admin.expense-categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form action="{{ route('admin.expense-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-10 text-gray-500">
                                Belum ada kategori yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>
</x-app-layout>