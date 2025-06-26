<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = Branch::withCount('users')->latest()->paginate(10);
        return view('admin.branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            // Validasi ditambahkan di sini
            'profit_sharing_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        Branch::create($request->all());

        return redirect()->route('admin.branches.index')
                         ->with('success', 'Cabang baru berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        return redirect()->route('admin.branches.edit', $branch);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            // Validasi ditambahkan di sini
            'profit_sharing_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $branch->update($request->all());

        return redirect()->route('admin.branches.index')
                         ->with('success', 'Data cabang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        if ($branch->users()->count() > 0) {
            return back()->with('error', 'Cabang tidak bisa dihapus karena masih memiliki user terdaftar.');
        }

        $branch->delete();
        
        return redirect()->route('admin.branches.index')
                         ->with('success', 'Cabang berhasil dihapus.');
    }
}
