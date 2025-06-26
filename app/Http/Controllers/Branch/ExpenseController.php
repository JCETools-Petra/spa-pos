<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Menampilkan daftar pengeluaran untuk cabang ini.
     */
    public function index()
    {
        $branchId = session('branch_id');
        $expenses = Expense::where('branch_id', $branchId)
                            ->with(['category', 'user'])
                            ->latest('expense_date')
                            ->paginate(15);
                            
        return view('branch.expenses.index', compact('expenses'));
    }

    /**
     * Menampilkan form untuk membuat pengeluaran baru.
     */
    public function create()
    {
        $categories = ExpenseCategory::orderBy('name')->get();
        return view('branch.expenses.create', compact('categories'));
    }

    /**
     * Menyimpan pengeluaran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:1000',
        ]);

        Expense::create([
            'branch_id' => session('branch_id'),
            'user_id' => Auth::id(),
            'expense_date' => $request->expense_date,
            'expense_category_id' => $request->expense_category_id,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        return redirect()->route('branch.expenses.index')->with('success', 'Pengeluaran berhasil dicatat.');
    }

    /**
     * Menampilkan form untuk mengedit pengeluaran.
     */
    public function edit(Expense $expense)
    {
        // Keamanan: Pastikan user hanya bisa mengedit pengeluaran milik cabangnya.
        if ($expense->branch_id != session('branch_id')) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        $categories = ExpenseCategory::orderBy('name')->get();
        return view('branch.expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Memperbarui data pengeluaran.
     */
    public function update(Request $request, Expense $expense)
    {
        // Keamanan: Pastikan user hanya bisa mengupdate pengeluaran milik cabangnya.
        if ($expense->branch_id != session('branch_id')) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        $request->validate([
            'expense_date' => 'required|date',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:1000',
        ]);

        $expense->update($request->all());

        return redirect()->route('branch.expenses.index')->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    /**
     * Menghapus data pengeluaran.
     */
    public function destroy(Expense $expense)
    {
        // Keamanan: Pastikan user hanya bisa menghapus pengeluaran milik cabangnya.
        if ($expense->branch_id != session('branch_id')) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        $expense->delete();

        return redirect()->route('branch.expenses.index')->with('success', 'Data pengeluaran berhasil dihapus.');
    }
}