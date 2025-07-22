<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpenseCategoryController extends Controller
{
    /**
     * Menampilkan daftar semua kategori pengeluaran.
     */
    public function index()
    {
        $categories = ExpenseCategory::latest()->paginate(10);
        return view('admin.expense_categories.index', compact('categories'));
    }

    /**
     * Menampilkan form untuk membuat kategori baru.
     */
    public function create()
    {
        return view('admin.expense_categories.create');
    }

    /**
     * Menyimpan kategori baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:expense_categories,name|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        ExpenseCategory::create($validated);

        return redirect()->route('admin.expense-categories.index')->with('success', 'Kategori baru berhasil dibuat.');
    }
    
    /**
     * Menampilkan form untuk mengedit kategori.
     */
    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('admin.expense_categories.edit', compact('expenseCategory'));
    }

    /**
     * Memperbarui data kategori.
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('expense_categories')->ignore($expenseCategory->id)],
            'description' => 'nullable|string|max:1000',
        ]);

        $expenseCategory->update($validated);

        return redirect()->route('admin.expense-categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Menghapus kategori.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        // PENTING: Cek apakah kategori ini sudah digunakan.
        if ($expenseCategory->expenses()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena sudah digunakan dalam pencatatan pengeluaran.');
        }

        $expenseCategory->delete();

        return redirect()->route('admin.expense-categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}