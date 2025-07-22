<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Branch;
use Carbon\Carbon;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf; // <-- INI BARIS YANG HILANG

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::all();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $branchId = $request->input('branch_id');

        $query = Transaction::query()->with(['branch', 'package', 'therapist', 'cashier', 'products']);

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $transactions = $query->latest()->get();
        $totalRevenue = $transactions->sum('total_amount');

        return view('admin.reports.index', compact(
            'transactions', 
            'branches', 
            'totalRevenue',
            'startDate',
            'endDate',
            'branchId'
        ));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $branchId = $request->input('branch_id');
        $fileName = 'laporan-penjualan-' . now()->format('d-m-Y') . '.xlsx';
        return Excel::download(new SalesExport($startDate, $endDate, $branchId), $fileName);
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $branchId = $request->input('branch_id');

        // PERBAIKAN: Tambahkan 'products' ke eager loading
        $query = Transaction::query()->with(['branch', 'package', 'cashier', 'products']);
        if ($startDate) $query->whereDate('created_at', '>=', $startDate);
        if ($endDate) $query->whereDate('created_at', '<=', $endDate);
        if ($branchId) $query->where('branch_id', $branchId);
        
        $transactions = $query->latest()->get();
        $totalRevenue = $transactions->sum('total_amount');
        $branch = $branchId ? Branch::find($branchId) : null;

        $data = [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branch' => $branch,
        ];

        $pdf = Pdf::loadView('admin.reports.pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        $fileName = 'laporan-penjualan-' . now()->format('d-m-Y') . '.pdf';
        return $pdf->download($fileName);
    }
}
