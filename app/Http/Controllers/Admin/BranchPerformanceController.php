<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Expense;
use App\Models\Transaction;
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
        $includePpn = $request->has('include_ppn');
        $includeService = $request->has('include_service');
        $includeExpenses = $request->has('include_expenses');
        $includeProfitSharing = $request->has('include_profit_sharing');
        $includeCommission = $request->has('include_commission');

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
            // =================================================================
            // START PERHITUNGAN SEKUENSIAL
            // =================================================================
            
            // Closure untuk query tanggal agar bisa dipakai ulang
            $dateQuery = fn($query) => $query->when($startDate, fn($q) => $q->whereDate('transactions.created_at', '>=', $startDate))
                                             ->when($endDate, fn($q) => $q->whereDate('transactions.created_at', '<=', $endDate));

            // LANGKAH 1: Pendapatan Kotor
            $serviceRevenue = DB::table('transactions')->where('branch_id', $branch->id)->whereNotNull('package_id')->where($dateQuery)->sum('total_amount');
            $productRevenue = DB::table('product_transaction')->join('transactions', 'product_transaction.transaction_id', '=', 'transactions.id')->where('transactions.branch_id', $branch->id)->where($dateQuery)->sum(DB::raw('product_transaction.quantity * product_transaction.price_at_sale'));
            $grossRevenue = $serviceRevenue + $productRevenue;

            // Variabel untuk menyimpan detail perhitungan
            $calculationSteps = [
                'service_revenue' => $serviceRevenue,
                'product_revenue' => $productRevenue,
                'gross_revenue' => $grossRevenue,
            ];

            // LANGKAH 2: Kurangi Pajak & Service
            $revenueAfterTaxes = $grossRevenue;
            if ($includePpn) {
                $taxAmount = $grossRevenue * 0.11;
                $revenueAfterTaxes -= $taxAmount;
                $calculationSteps['tax_amount'] = $taxAmount;
            }
            if ($includeService) {
                $serviceAmount = $grossRevenue * 0.10;
                $revenueAfterTaxes -= $serviceAmount;
                $calculationSteps['service_amount'] = $serviceAmount;
            }
            $calculationSteps['revenue_after_taxes'] = $revenueAfterTaxes;

            // LANGKAH 3: Bagi Hasil Tempat (dinamis berdasarkan data cabang)
            $revenueForNextStep = $revenueAfterTaxes;
            if ($includeProfitSharing && $branch->profit_sharing_percentage > 0) {
                // Menggunakan persentase dinamis dari data cabang
                $profitSharingAmount = ($revenueAfterTaxes * $branch->profit_sharing_percentage) / 100;
                $revenueForNextStep -= $profitSharingAmount;
                $calculationSteps['profit_sharing_amount'] = $profitSharingAmount;
                $calculationSteps['profit_sharing_percentage'] = $branch->profit_sharing_percentage; // Simpan persentase untuk ditampilkan
            }
            $calculationSteps['revenue_after_sharing'] = $revenueForNextStep;
            
            // LANGKAH 4: Kurangi Operasional & Komisi dari sisa bagi hasil
            $finalNetRevenue = $revenueForNextStep;
            if ($includeCommission) {
                $totalMinutes = DB::table('transactions')->join('packages', 'transactions.package_id', '=', 'packages.id')->where('transactions.branch_id', $branch->id)->whereNotNull('transactions.package_id')->where($dateQuery)->sum('packages.duration_minutes');
                $commissionAmount = ($totalMinutes / 60) * 20000;
                $finalNetRevenue -= $commissionAmount;
                $calculationSteps['commission_amount'] = $commissionAmount;
            }
            if ($includeExpenses) {
                $expenseAmount = Expense::where('branch_id', $branch->id)
                    ->when($startDate, fn($q) => $q->whereDate('expense_date', '>=', $startDate))
                    ->when($endDate, fn($q) => $q->whereDate('expense_date', '<=', $endDate))
                    ->sum('amount');
                $finalNetRevenue -= $expenseAmount;
                $calculationSteps['expense_amount'] = $expenseAmount;
            }
            $calculationSteps['final_net_revenue'] = $finalNetRevenue;


            // LANGKAH 5: Pembagian hasil akhir
            $shareSodiq = $finalNetRevenue > 0 ? $finalNetRevenue * 0.10 : 0;
            $shareDila = $finalNetRevenue > 0 ? $finalNetRevenue * 0.40 : 0;
            $shareArini = $finalNetRevenue > 0 ? $finalNetRevenue * 0.50 : 0;

            $performanceData[$branch->id] = [
                'branch_name' => $branch->name,
                'steps' => $calculationSteps,
                'share_sodiq' => $shareSodiq,
                'share_dila' => $shareDila,
                'share_arini' => $shareArini,
            ];
        }
        
        return view('admin.branch_performance.index', compact(
            'performanceData', 
            'startDate', 
            'endDate'
        ));
    }
}