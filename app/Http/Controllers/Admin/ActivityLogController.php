<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\Expense;
use App\Models\Package;

class ActivityLogController extends Controller
{
    /**
     * Menampilkan halaman log dengan filter.
     */
    public function index(Request $request)
    {
        // Ambil data untuk dropdown filter
        $users = User::orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();

        // Mulai query builder
        $query = Activity::query()->with(['causer', 'subject']);

        // Filter berdasarkan User
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }
        
        // Filter berdasarkan Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter berdasarkan Cabang (ini sedikit lebih kompleks)
        if ($request->filled('branch_id')) {
            $branchId = $request->branch_id;
            $query->where(function ($q) use ($branchId) {
                // Cari log di mana subjeknya (Transaction, Expense, Package, User) memiliki branch_id yang cocok
                $q->whereHasMorph(
                    'subject',
                    [Transaction::class, Expense::class, Package::class, User::class],
                    function ($subQuery) use ($branchId) {
                        $subQuery->where('branch_id', $branchId);
                    }
                )
                // ATAU cari log di mana subjeknya adalah Cabang itu sendiri
                ->orWhere(function ($subQuery) use ($branchId) {
                    $subQuery->where('subject_type', Branch::class)
                             ->where('subject_id', $branchId);
                });
            });
        }

        $activities = $query->latest()->paginate(25)->withQueryString();
        
        return view('admin.logs.index', compact('activities', 'users', 'branches'));
    }

    /**
     * Mengambil detail satu log untuk ditampilkan di modal.
     */
    public function show(Activity $activity_log)
    {
        // Kita load relasi agar bisa diakses di frontend
        $activity_log->load(['causer', 'subject']);
        return response()->json($activity_log);
    }
}