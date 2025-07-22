<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Menampilkan daftar semua cabang untuk dipilih.
     */
    public function index()
    {
        $branches = Branch::withCount('products')->latest()->get();
        return view('admin.inventory.index', compact('branches'));
    }

    /**
     * Menampilkan halaman untuk mengelola inventaris produk di satu cabang.
     */
    public function show(Branch $branch)
    {
        // Ambil semua master produk
        $allProducts = Product::orderBy('name')->get();

        // Ambil produk yang sudah ada di inventaris cabang ini untuk pre-fill data
        $branchProducts = $branch->products->keyBy('id');

        return view('admin.inventory.show', compact('branch', 'allProducts', 'branchProducts'));
    }

    /**
     * Memperbarui data inventaris untuk satu cabang.
     */
    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.is_available' => 'sometimes|boolean',
            'products.*.selling_price' => 'required_if:products.*.is_available,true|numeric|min:0',
            'products.*.stock_quantity' => 'required_if:products.*.is_available,true|integer|min:0',
        ]);

        $syncData = [];
        foreach ($request->products as $productId => $data) {
            // Hanya proses produk yang dicentang "Tersedia"
            if (isset($data['is_available']) && $data['is_available']) {
                $syncData[$productId] = [
                    'selling_price' => $data['selling_price'],
                    'stock_quantity' => $data['stock_quantity'],
                ];
            }
        }

        // Gunakan sync untuk memperbarui pivot table.
        // Data yang tidak ada di $syncData akan otomatis dihapus dari inventaris cabang.
        $branch->products()->sync($syncData);

        return redirect()->route('admin.inventory.show', $branch)->with('success', 'Inventaris untuk cabang ' . $branch->name . ' berhasil diperbarui.');
    }
}

