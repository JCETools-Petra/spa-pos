<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Branch; // Kita butuh ini untuk dropdown cabang
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Menampilkan daftar semua paket dari semua cabang.
     */
    public function index()
    {
        // Eager load relasi 'branch' untuk menghindari N+1 problem di view
        $packages = Package::with('branch')->latest()->paginate(10);
        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Menampilkan form untuk membuat paket baru.
     */
    public function create()
    {
        // Ambil semua cabang untuk ditampilkan di dropdown pilihan
        $branches = Branch::all();
        return view('admin.packages.create', compact('branches'));
    }

    /**
     * Menyimpan paket baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:0',
        ]);

        Package::create($request->all());

        return redirect()->route('admin.packages.index')
                         ->with('success', 'Paket baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit paket.
     */
    public function edit(Package $package)
    {
        $branches = Branch::all();
        return view('admin.packages.edit', compact('package', 'branches'));
    }

    /**
     * Memperbarui data paket di database.
     */
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $package->update($request->all());

        return redirect()->route('admin.packages.index')
                         ->with('success', 'Data paket berhasil diperbarui.');
    }

    /**
     * Menghapus paket dari database.
     */
    public function destroy(Package $package)
    {
        // Pengecekan penting: jangan hapus paket jika sudah pernah ada transaksi.
        // Sebaiknya, paket dinonaktifkan saja (is_active = false)
        if ($package->transactions()->exists()) {
            return back()->with('error', 'Paket tidak bisa dihapus karena sudah memiliki riwayat transaksi. Harap nonaktifkan saja.');
        }
        
        $package->delete();

        return redirect()->route('admin.packages.index')
                         ->with('success', 'Paket berhasil dihapus.');
    }
}