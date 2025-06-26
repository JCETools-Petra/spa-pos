<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: 'sans-serif'; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin: 0; }
        .header p { font-size: 12px; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 6px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; }
        ul { margin: 0; padding-left: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Penjualan</h1>
        <p>Periode: {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') : 'Semua Waktu' }} - {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d M Y') : 'Semua Waktu' }}</p>
        <p>Cabang: {{ $branch ? $branch->name : 'Semua Cabang' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Cabang</th>
                <th width="30%">Detail Item</th>
                <th>Kasir</th>
                <th>Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $trx)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $trx->invoice_number }}</td>
                    <td>{{ $trx->created_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $trx->branch->name }}</td>
                    <td>
                        {{-- PERBAIKAN DI SINI --}}
                        @if($trx->package)
                            <div><strong>Jasa:</strong> {{ $trx->package->name }}</div>
                        @endif
                        @if($trx->products->isNotEmpty())
                            <div><strong>Produk:</strong></div>
                            <ul>
                                @foreach($trx->products as $product)
                                    <li>{{ $product->name }} ({{ $product->pivot->quantity }}x)</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                    <td>{{ $trx->cashier->name }}</td>
                    <td style="text-align: right;">{{ number_format($trx->total_amount) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align: right; font-weight: bold;">TOTAL PENDAPATAN</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($totalRevenue) }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
