<x-app-layout>
    <x-slot name="header">
        {{ __('Analisis Performa Cabang') }}
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow-sm mb-6">
        <h3 class="font-semibold text-lg text-brand-dark-stone mb-4">Filter Data</h3>
        <form id="filter-form" action="{{ route('admin.branch-performance.index') }}" method="GET">
            <div class="space-y-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Periode Cepat</label>
                        <div class="mt-1 flex rounded-lg shadow-sm">
                            <button type="button" data-period="daily" class="period-btn relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">Hari Ini</button>
                            <button type="button" data-period="monthly" class="period-btn -ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">Bulan Ini</button>
                            <button type="button" data-period="yearly" class="period-btn -ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">Tahun Ini</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Filter per Bulan (Tahun Ini)</label>
                        <div class="mt-1 grid grid-cols-6 gap-1">
                            @php
                                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                            @endphp
                            @foreach($months as $index => $month)
                                <button type="button" data-month="{{ $index + 1 }}" class="month-btn px-2 py-2 border border-gray-300 bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 rounded-md">{{ $month }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-start">
                    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai (Bebas)</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date', $startDate instanceof \Carbon\Carbon ? $startDate->format('Y-m-d') : $startDate) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai (Bebas)</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date', $endDate instanceof \Carbon\Carbon ? $endDate->format('Y-m-d') : $endDate) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sertakan Pengurangan</label>
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="include_ppn" value="1" class="rounded border-gray-300 text-brand-sand-green focus:ring-brand-sand-green" @checked(request('include_ppn'))>
                                <span class="ml-2 text-sm">Pajak (11%)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="include_service" value="1" class="rounded border-gray-300 text-brand-sand-green focus:ring-brand-sand-green" @checked(request('include_service'))>
                                <span class="ml-2 text-sm">Service (10%)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="include_profit_sharing" value="1" class="rounded border-gray-300 text-brand-sand-green focus:ring-brand-sand-green" @checked(request('include_profit_sharing'))>
                                <span class="ml-2 text-sm">Bagi Hasil Tempat</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="include_commission" value="1" class="rounded border-gray-300 text-brand-sand-green focus:ring-brand-sand-green" @checked(request('include_commission'))>
                                <span class="ml-2 text-sm">Komisi Terapis</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="include_expenses" value="1" class="rounded border-gray-300 text-brand-sand-green focus:ring-brand-sand-green" @checked(request('include_expenses'))>
                                <span class="ml-2 text-sm">Pengeluaran Operasional</span>
                            </label>
                        </div>
                    </div>
                    <div class="pt-6">
                        <button type="submit" class="w-full bg-brand-sand-green hover:bg-brand-sand-green-dark text-white font-bold py-2 px-4 rounded-lg">Terapkan Filter</button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="period" id="period-input">
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($performanceData as $data)
            <div class="bg-white p-6 rounded-xl shadow-sm flex flex-col">
                <h3 class="font-bold text-lg text-brand-deep-teal border-b pb-2 mb-3">{{ $data['branch_name'] }}</h3>
                
                @php $steps = $data['steps']; @endphp

                <div class="space-y-2 flex-grow">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 pl-4">+ Pendapatan Jasa</span>
                        <span class="font-medium">Rp {{ number_format($steps['service_revenue']) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 pl-4">+ Pendapatan Produk</span>
                        <span class="font-medium">Rp {{ number_format($steps['product_revenue']) }}</span>
                    </div>

                    <div class="flex justify-between items-center border-t border-dashed mt-1 pt-1">
                        <span class="text-sm text-gray-600">Pendapatan Kotor</span>
                        <span class="font-semibold">Rp {{ number_format($steps['gross_revenue']) }}</span>
                    </div>

                    @if(isset($steps['tax_amount']) || isset($steps['service_amount']))
                        @if(isset($steps['tax_amount']))
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 pl-4">- Pajak (11%)</span>
                            <span class="font-medium text-red-600">(Rp {{ number_format($steps['tax_amount']) }})</span>
                        </div>
                        @endif
                        @if(isset($steps['service_amount']))
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 pl-4">- Service (10%)</span>
                            <span class="font-medium text-red-600">(Rp {{ number_format($steps['service_amount']) }})</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center border-t border-dashed mt-1 pt-1">
                            <span class="text-sm text-gray-600 font-medium">Subtotal (Setelah Pajak & Service)</span>
                            <span class="font-semibold">Rp {{ number_format($steps['revenue_after_taxes']) }}</span>
                        </div>
                    @endif

                    @if(isset($steps['profit_sharing_amount']))
                        <div class="flex justify-between items-center text-sm mt-2">
                            <span class="text-gray-500 pl-4">- Bagi Hasil Tempat ({{ $steps['profit_sharing_percentage'] }}%)</span>
                            <span class="font-medium text-red-600">(Rp {{ number_format($steps['profit_sharing_amount']) }})</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-dashed mt-1 pt-1">
                            <span class="text-sm text-gray-600 font-medium">Sisa Untuk Dikelola</span>
                            <span class="font-semibold">Rp {{ number_format($steps['revenue_after_sharing']) }}</span>
                        </div>
                    @endif
                    
                    @if(isset($steps['commission_amount']) || isset($steps['expense_amount']))
                        @if(isset($steps['commission_amount']))
                        <div class="flex justify-between items-center text-sm mt-2">
                            <span class="text-gray-500 pl-4">- Komisi Terapis</span>
                            <span class="font-medium text-red-600">(Rp {{ number_format($steps['commission_amount']) }})</span>
                        </div>
                        @endif
                        @if(isset($steps['expense_amount']))
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 pl-4">- Pengeluaran Operasional</span>
                            <span class="font-medium text-red-600">(Rp {{ number_format($steps['expense_amount']) }})</span>
                        </div>
                        @endif
                    @endif
                </div>

                <div class="flex justify-between items-center border-t-2 border-brand-sand-green mt-4 pt-2">
                    <span class="font-bold text-base">Pendapatan Bersih (Final)</span>
                    <span class="font-bold text-xl text-brand-sand-green-dark">Rp {{ number_format($steps['final_net_revenue']) }}</span>
                </div>

                <div class="mt-4 pt-3 border-t border-dashed">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Pembagian Hasil Bersih</h4>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Sodiq (10%)</span>
                            <span class="font-medium text-blue-600">Rp {{ number_format($data['share_sodiq']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Dila (40%)</span>
                            <span class="font-medium text-blue-600">Rp {{ number_format($data['share_dila']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Arini (50%)</span>
                            <span class="font-medium text-blue-600">Rp {{ number_format($data['share_arini']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filter-form');
            const periodInput = document.getElementById('period-input');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            
            document.querySelectorAll('.period-btn').forEach(button => {
                button.addEventListener('click', function() {
                    periodInput.value = this.dataset.period;
                    startDateInput.value = '';
                    endDateInput.value = '';
                    form.submit();
                });
            });

            document.querySelectorAll('.month-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const year = new Date().getFullYear();
                    const month = this.dataset.month;
                    
                    const startOfMonth = new Date(year, month - 1, 1);
                    const endOfMonth = new Date(year, month, 0);
                    
                    startDateInput.value = startOfMonth.toISOString().split('T')[0];
                    endDateInput.value = endOfMonth.toISOString().split('T')[0];
                    
                    periodInput.value = '';
                    form.submit();
                });
            });

            startDateInput.addEventListener('change', () => periodInput.value = '');
            endDateInput.addEventListener('change', () => periodInput.value = '');
        });
    </script>
</x-app-layout>