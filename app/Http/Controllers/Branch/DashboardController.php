<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Package;

class DashboardController extends Controller
{
    public function index()
    {
        $branchId = session('branch_id');
        if (!$branchId) {
            return redirect('/login')->with('error', 'Sesi cabang Anda tidak valid.');
        }

        // 1. Data untuk Kartu Statistik (KPI Cards)
        $revenueToday = Transaction::where('branch_id', $branchId)
                                   ->whereDate('created_at', today())
                                   ->sum('total_amount');

        $transactionsToday = Transaction::where('branch_id', $branchId)
                                        ->whereDate('created_at', today())
                                        ->count();

        $activePackages = Package::where('branch_id', $branchId)
                                 ->where('is_active', true)
                                 ->count();

        // 2. Data untuk Tabel Aktivitas Terbaru (hanya dari cabang ini)
        $recentTransactions = Transaction::where('branch_id', $branchId)
                                         // PERBAIKAN: Tambahkan 'products' ke eager loading
                                         ->with(['package', 'therapist', 'products'])
                                         ->latest()
                                         ->take(5)
                                         ->get();

        return view('branch.dashboard', [
            'revenueToday' => $revenueToday,
            'transactionsToday' => $transactionsToday,
            'activePackages' => $activePackages,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
