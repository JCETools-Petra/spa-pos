<x-app-layout>
    <x-slot name="header">
        {{ __('Log Aktivitas Sistem') }}
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm mb-6">
        <h3 class="font-semibold text-lg text-brand-dark-stone mb-4">Filter Log</h3>
        <form action="{{ route('admin.activity-logs.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="start_date" class="text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                </div>
                <div>
                    <label for="end_date" class="text-sm font-medium text-gray-700">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                </div>
                <div>
                    <label for="user_id" class="text-sm font-medium text-gray-700">User</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                        <option value="">Semua User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                 <div>
                    <label for="branch_id" class="text-sm font-medium text-gray-700">Cabang</label>
                    <select name="branch_id" id="branch_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                        <option value="">Semua Cabang</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 flex justify-end space-x-2">
                <a href="{{ route('admin.activity-logs.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg">Reset</a>
                <button type="submit" class="px-4 py-2 bg-brand-sand-green hover:bg-brand-sand-green-dark text-white rounded-lg">Cari</button>
            </div>
        </form>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-brand-ivory">
                    <tr>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Objek</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($activities as $activity)
                        <tr class="hover:bg-brand-ivory/50">
                            <td class="py-3 px-4 whitespace-nowrap text-sm">{{ $activity->causer->name ?? 'Sistem' }}</td>
                            <td class="py-3 px-4 text-sm"><span class="font-semibold">{{ $activity->description }}</span></td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm font-mono">{{ Str::afterLast($activity->subject_type, '\\') }} #{{ $activity->subject_id }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $activity->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm">
                                @if($activity->event === 'updated')
                                    <button class="text-indigo-600 hover:text-indigo-900 view-details-btn" data-url="{{ route('admin.activity-logs.show', $activity) }}">
                                        Detail
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-10 text-gray-500">Tidak ada aktivitas yang cocok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $activities->links() }}</div>
    </div>

    <div id="details-modal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl m-4">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">Detail Perubahan</h3>
                <button id="close-modal-btn" class="text-gray-500 hover:text-gray-800">&times;</button>
            </div>
            <div id="modal-body" class="p-6 max-h-[60vh] overflow-y-auto">
                </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('details-modal');
    const modalBody = document.getElementById('modal-body');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const detailButtons = document.querySelectorAll('.view-details-btn');

    detailButtons.forEach(button => {
        button.addEventListener('click', function() {
            const url = this.dataset.url;
            modalBody.innerHTML = '<p>Loading...</p>';
            modal.classList.remove('hidden');

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const old_props = data.properties.old ?? {};
                    const new_props = data.properties.attributes ?? {};
                    
                    let tableHtml = '<table class="min-w-full divide-y divide-gray-200"><thead><tr><th class="py-2 px-3 text-left">Kolom</th><th class="py-2 px-3 text-left">Sebelum</th><th class="py-2 px-3 text-left">Sesudah</th></tr></thead><tbody>';

                    const allKeys = [...new Set([...Object.keys(old_props), ...Object.keys(new_props)])];

                    allKeys.forEach(key => {
                        const oldValue = old_props[key] ?? '<em>N/A</em>';
                        const newValue = new_props[key] ?? '<em>N/A</em>';
                        if (String(oldValue) !== String(newValue)) {
                             tableHtml += `<tr class="bg-yellow-50"><td class="py-2 px-3 font-mono text-xs">${key}</td><td class="py-2 px-3 text-xs">${oldValue}</td><td class="py-2 px-3 text-xs">${newValue}</td></tr>`;
                        }
                    });

                    tableHtml += '</tbody></table>';
                    modalBody.innerHTML = tableHtml;
                })
                .catch(error => {
                    modalBody.innerHTML = '<p class="text-red-500">Gagal memuat detail.</p>';
                    console.error('Error:', error);
                });
        });
    });

    function closeModal() {
        modal.classList.add('hidden');
        modalBody.innerHTML = '';
    }

    closeModalBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
});
</script>
</x-app-layout>