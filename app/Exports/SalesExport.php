<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $branchId;

    public function __construct($startDate, $endDate, $branchId)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->branchId = $branchId;
    }

    /**
     * Menyiapkan query ke database sesuai filter.
     */
    public function query()
    {
        $query = Transaction::query()->with(['branch', 'package', 'therapist', 'cashier', 'products']);

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }
        if ($this->branchId) {
            $query->where('branch_id', $this->branchId);
        }

        return $query->latest();
    }

    /**
     * Menentukan header untuk setiap kolom di file Excel.
     */
    public function headings(): array
    {
        return [
            'Invoice',
            'Tanggal Transaksi',
            'Cabang',
            'Nama Paket Jasa',
            'Terapis',
            'Kasir',
            'Nama Pelanggan',
            'Detail Produk Terjual',
            'Total Transaksi (Rp)',
        ];
    }

    /**
     * Memetakan data dari setiap baris transaksi ke kolom yang sesuai.
     * @param Transaction $transaction
     */
    public function map($transaction): array
    {
        // PERBAIKAN DI SINI:
        // Gabungkan nama produk yang terjual menjadi satu string
        $productsSold = $transaction->products->map(function ($product) {
            return $product->name . ' (' . $product->pivot->quantity . 'x)';
        })->implode(', ');

        return [
            $transaction->invoice_number,
            $transaction->created_at->format('d-m-Y H:i:s'),
            $transaction->branch->name ?? 'N/A',
            // Cek jika paket ada sebelum memanggil properti 'name'
            $transaction->package->name ?? 'N/A',
            // Cek jika terapis ada sebelum memanggil properti 'name'
            $transaction->therapist->name ?? 'N/A',
            $transaction->cashier->name ?? 'N/A',
            $transaction->customer_name,
            $productsSold, // Kolom baru untuk detail produk
            $transaction->total_amount,
        ];
    }
}
