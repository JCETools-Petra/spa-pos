<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Package;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class POSController extends Controller
{
    /**
     * Menampilkan halaman utama POS untuk membuat transaksi.
     */
    public function create()
    {
        $branchId = session('branch_id');
        if (!$branchId) {
            abort(403, 'Anda harus login sebagai user cabang untuk mengakses halaman ini.');
        }

        $branch = Branch::with('products')->findOrFail($branchId);
        $availableProducts = $branch->products->where('pivot.stock_quantity', '>', 0);
        $packages = Package::where('branch_id', $branchId)->where('is_active', true)->get();
        $therapists = User::where('branch_id', $branchId)->get();

        return view('pos.create', compact('packages', 'therapists', 'availableProducts'));
    }

    /**
     * Menyimpan transaksi baru yang bisa berisi paket dan/atau produk.
     */
    public function store(Request $request)
    {
        $branchId = session('branch_id');

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'package_id' => 'nullable|exists:packages,id',
            'therapist_user_id' => 'required_with:package_id|nullable|exists:users,id', // Terapis wajib jika ada paket
            'products' => 'nullable|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if (empty($request->package_id) && empty($request->products)) {
            return back()->with('error', 'Tidak ada paket atau produk yang dipilih untuk transaksi.');
        }

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            
            if ($request->filled('package_id')) {
                $package = Package::find($request->package_id);
                $totalAmount += $package->price;
            }

            $soldProductsData = [];
            if ($request->filled('products')) {
                foreach ($request->products as $item) {
                    $product = Product::find($item['id']);
                    $branchProductInfo = $product->branches()->where('branch_id', $branchId)->first();
                    
                    if (!$branchProductInfo || $branchProductInfo->pivot->stock_quantity < $item['quantity']) {
                        throw new \Exception('Stok untuk produk ' . $product->name . ' tidak mencukupi.');
                    }
                    
                    $priceAtSale = $branchProductInfo->pivot->selling_price;
                    $totalAmount += $priceAtSale * $item['quantity'];
                    
                    $soldProductsData[$item['id']] = [
                        'quantity' => $item['quantity'],
                        'price_at_sale' => $priceAtSale
                    ];
                }
            }

            // Buat entri transaksi utama
            $transaction = Transaction::create([
                'invoice_number' => 'INV-' . now()->timestamp . '-' . strtoupper(Str::random(4)),
                'branch_id' => $branchId,
                'package_id' => $request->package_id,
                // ==========================================================
                // === PERBAIKAN UTAMA ADA DI SINI ===
                // Hanya isi therapist_user_id jika paket dipilih, jika tidak, isi dengan null.
                'therapist_user_id' => $request->filled('package_id') ? $request->therapist_user_id : null,
                // ==========================================================
                'cashier_user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
            ]);

            if (!empty($soldProductsData)) {
                $transaction->products()->attach($soldProductsData);
                foreach ($soldProductsData as $productId => $data) {
                    $branch = Branch::find($branchId);
                    $branch->products()->where('product_id', $productId)->decrement('stock_quantity', $data['quantity']);
                }
            }
            
            DB::commit();
            return redirect()->route('pos.history')->with('success', 'Transaksi berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan riwayat transaksi untuk cabang saat ini.
     */
    public function history()
    {
        $branchId = session('branch_id');
        $transactions = Transaction::where('branch_id', $branchId)
                            ->with(['package', 'products', 'therapist', 'cashier'])
                            ->latest()
                            ->paginate(15);

        return view('pos.history', compact('transactions'));
    }
}
