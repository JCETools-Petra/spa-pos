<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Branch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data untuk Kartu Statistik (KPI Cards) - Ini tetap sama
        $revenueToday = Transaction::whereDate('created_at', today())->sum('total_amount');
        $transactionsToday = Transaction::whereDate('created_at', today())->count();
        $totalBranches = Branch::count();
        $totalUsers = User::where('role', 'branch_user')->count();

        // 2. Data untuk Tabel Aktivitas Terbaru - Ini tetap sama
        $recentTransactions = Transaction::with(['branch', 'package', 'products'])->latest()->take(5)->get();

        // 3. Data untuk Grafik Penjualan 7 Hari Terakhir - Ini tetap sama
        $salesData = Transaction::where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            ]);
            
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::parse($date)->format('D, d M');
            $sale = $salesData->firstWhere('date', $date);
            $chartData[] = $sale ? $sale->total : 0;
        }

        // ===================================================================
        // 4. LOGIKA BARU: Data Penjualan Terperinci per Cabang untuk Hari Ini
        // ===================================================================
        $salesByBranch = [];
        $allBranches = Branch::all();

        foreach ($allBranches as $branch) {
            // Ambil data penjualan JASA (Paket)
            $packagesSold = DB::table('transactions')
                ->join('packages', 'transactions.package_id', '=', 'packages.id')
                ->where('transactions.branch_id', $branch->id)
                ->whereDate('transactions.created_at', today())
                ->whereNotNull('transactions.package_id')
                ->select('packages.name', 'packages.price', DB::raw('count(transactions.package_id) as quantity'))
                ->groupBy('packages.id', 'packages.name', 'packages.price')
                ->get()
                ->map(function ($item) {
                    $item->revenue = $item->quantity * $item->price;
                    return $item;
                });

            // Ambil data penjualan PRODUK
            $productsSold = DB::table('product_transaction')
                ->join('transactions', 'product_transaction.transaction_id', '=', 'transactions.id')
                ->join('products', 'product_transaction.product_id', '=', 'products.id')
                ->where('transactions.branch_id', $branch->id)
                ->whereDate('transactions.created_at', today())
                ->select('products.name', DB::raw('sum(product_transaction.quantity) as quantity'), DB::raw('sum(product_transaction.quantity * product_transaction.price_at_sale) as revenue'))
                ->groupBy('products.id', 'products.name')
                ->get();
            
            // Hitung total pendapatan untuk cabang ini
            $totalBranchRevenue = $packagesSold->sum('revenue') + $productsSold->sum('revenue');

            // Hanya tampilkan cabang jika ada penjualan hari ini
            if ($totalBranchRevenue > 0) {
                $salesByBranch[$branch->id] = [
                    'branch_name' => $branch->name,
                    'total_revenue' => $totalBranchRevenue,
                    'packages' => $packagesSold,
                    'products' => $productsSold,
                ];
            }
        }
        
        // Kirim semua data ke view
        return view('admin.dashboard', compact(
            'revenueToday', 
            'transactionsToday', 
            'totalBranches', 
            'totalUsers', 
            'recentTransactions', 
            'chartLabels', 
            'chartData',
            'salesByBranch' // <-- DATA BARU
        ));
    }
}