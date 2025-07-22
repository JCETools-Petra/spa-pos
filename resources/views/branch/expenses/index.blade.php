<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-brand-dark-stone leading-tight">
                {{ __('Riwayat Pengeluaran Cabang') }}
            </h2>
            <a href="{{ route('branch.expenses.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-sand-green hover:bg-brand-sand-green-dark text-white rounded-lg font-semibold text-xs uppercase tracking-widest">
                + Catat Pengeluaran Baru
            </a>
        </div>
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-brand-ivory">
                    <tr>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diinput oleh</th>
                        <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-brand-ivory/50">
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $expense->expense_date->format('d M Y') }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm font-medium">
                                {{ $expense->category->name ?? 'N/A' }}
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $expense->description }}">
                                {{ $expense->description }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $expense->user->name ?? 'N/A' }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm font-semibold text-right">
                                Rp {{ number_format($expense->amount) }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-right text-sm">
                                <div class="flex space-x-2 justify-end">
                                    <a href="{{ route('branch.expenses.edit', $expense) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form action="{{ route('branch.expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-500">
                                <p>Belum ada data pengeluaran yang dicatat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $expenses->links() }}
        </div>
    </div>
</x-app-layout>