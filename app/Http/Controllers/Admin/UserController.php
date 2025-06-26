<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna cabang.
     */
    public function index()
    {
        // Kita hanya akan menampilkan user dengan role 'branch_user'
        $users = User::where('role', 'branch_user')->with('branch')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat user baru.
     */
    public function create()
    {
        $branches = Branch::all();
        return view('admin.users.create', compact('branches'));
    }

    /**
     * Menyimpan user baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'branch_id' => ['required', 'exists:branches,id'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'branch_id' => $request->branch_id,
            'role' => 'branch_user', // Otomatis set sebagai pengguna cabang
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User baru berhasil dibuat.');
    }

    /**
     * Menampilkan form untuk mengedit user.
     */
    public function edit(User $user)
    {
        // Pastikan admin tidak bisa mengedit admin lain atau dirinya sendiri dari halaman ini
        if ($user->isAdmin()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $branches = Branch::all();
        return view('admin.users.edit', compact('user', 'branches'));
    }

    /**
     * Memperbarui data user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'branch_id' => ['required', 'exists:branches,id'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->branch_id = $request->branch_id;

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Menghapus user.
     */
    public function destroy(User $user)
    {
        // Tambahkan pengecekan untuk mencegah error atau penyalahgunaan
        if ($user->isAdmin()) {
            return back()->with('error', 'Akun Admin tidak bisa dihapus.');
        }

        if ($user->transactionsAsTherapist()->exists() || $user->transactionsAsCashier()->exists()) {
            return back()->with('error', 'User tidak bisa dihapus karena memiliki riwayat transaksi.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}