<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Expense;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BranchPerformanceController extends Controller
{
    /**
     * Menampilkan halaman analisis performa cabang dengan filter.
     */
    public function index(Request $request)
    {
        // Ambil filter dari request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $period = $request->input('period');
        
        // Ambil filter checkbox pengurangan
        $includeExpenses = $request->has('include_expenses');
        $includeProfitSharing = $request->has('include_profit_sharing');
        $includeSalaries = $request->has('include_salaries');
        $includeTax = $request->has('include_tax');

        // Atur rentang tanggal berdasarkan filter periode cepat
        if ($period === 'daily') {
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
        } elseif ($period === 'monthly') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        } elseif ($period === 'yearly') {
            $startDate = now()->startOfYear();
            $endDate = now()->endOfYear();
        }

        $performanceData = [];
        $allBranches = Branch::all();

        foreach ($allBranches as $branch) {
            // Closure untuk query tanggal agar bisa dipakai ulang
            $dateQuery = function ($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->whereDate('transactions.created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate('transactions.created_at', '<=', $endDate);
                }
            };

            // 1. Hitung Pendapatan Jasa (dari paket)
            $serviceRevenue = DB::table('transactions')
                ->where('branch_id', $branch->id)
                ->whereNotNull('package_id')
                ->where(fn($q) => $dateQuery($q->from('transactions')))
                ->sum('total_amount');

            // 2. Hitung Pendapatan Produk
            $productRevenue = DB::table('product_transaction')
                ->join('transactions', 'product_transaction.transaction_id', '=', 'transactions.id')
                ->where('transactions.branch_id', $branch->id)
                ->where($dateQuery)
                ->sum(DB::raw('product_transaction.quantity * product_transaction.price_at_sale'));
                
            $grossRevenue = $serviceRevenue + $productRevenue;
            
            $deductions = [];
            $totalDeductions = 0;

            // 3. Kalkulasi semua jenis pengurangan berdasarkan filter checkbox
            if ($includeProfitSharing && $branch->profit_sharing_percentage > 0) {
                $sharingAmount = ($serviceRevenue * $branch->profit_sharing_percentage) / 100;
                $deductions['Bagi Hasil Tempat'] = $sharingAmount;
                $totalDeductions += $sharingAmount;
            }
            
            if ($includeSalaries) {
                // Asumsi gaji adalah per bulan, kita ambil totalnya saja.
                // Untuk perhitungan lebih akurat per periode, perlu logika pro-rata.
                $salaryAmount = Salary::where('branch_id', $branch->id)->sum('amount');
                $deductions['Gaji Karyawan'] = $salaryAmount;
                $totalDeductions += $salaryAmount;
            }

            if ($includeExpenses) {
                $expenseAmount = Expense::where('branch_id', $branch->id)
                                ->where(function($q) use ($startDate, $endDate){
                                    if ($startDate) {
                                        $q->whereDate('expense_date', '>=', $startDate);
                                    }
                                    if ($endDate) {
                                        $q->whereDate('expense_date', '<=', $endDate);
                                    }
                                })
                                ->sum('amount');
                $deductions['Pengeluaran Operasional'] = $expenseAmount;
                $totalDeductions += $expenseAmount;
            }

            if ($includeTax) {
                $taxAmount = $grossRevenue * 0.11;
                $deductions['Pajak (11%)'] = $taxAmount;
                $totalDeductions += $taxAmount;
            }
            
            $netRevenue = $grossRevenue - $totalDeductions;

            // Kumpulkan semua data untuk dikirim ke view
            $performanceData[$branch->id] = [
                'branch_name' => $branch->name,
                'service_revenue' => $serviceRevenue,
                'product_revenue' => $productRevenue,
                'gross_revenue' => $grossRevenue,
                'deductions' => $deductions,
                'total_deductions' => $totalDeductions,
                'net_revenue' => $netRevenue,
            ];
        }
        
        return view('admin.branch_performance.index', compact(
            'performanceData', 
            'startDate', 
            'endDate'
        ));
    }
}
