<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index()
    {
        // Menampilkan semua pengguna dan mengurutkan berdasarkan peran
        $users = User::with('branch')->orderBy('role')->latest()->paginate(10);
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
            // Menambahkan validasi untuk peran
            'role' => ['required', Rule::in(['admin', 'owner', 'branch_user'])],
            // branch_id hanya wajib jika perannya adalah branch_user
            'branch_id' => ['required_if:role,branch_user', 'nullable', 'exists:branches,id'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            // Atur branch_id hanya jika perannya adalah 'branch_user', jika tidak, set ke null
            'branch_id' => $request->role === 'branch_user' ? $request->branch_id : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User baru berhasil dibuat.');
    }

    /**
     * Menampilkan form untuk mengedit user.
     */
    public function edit(User $user)
    {
        // Admin tidak bisa mengedit akunnya sendiri melalui menu ini untuk keamanan
        if ($user->id === auth()->id()) {
            abort(403, 'Anda tidak dapat mengedit akun Anda sendiri dari halaman ini.');
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
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            // Menambahkan validasi untuk peran saat update
            'role' => ['required', Rule::in(['admin', 'owner', 'branch_user'])],
            'branch_id' => ['required_if:role,branch_user', 'nullable', 'exists:branches,id'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        // Atur branch_id sesuai dengan peran yang dipilih
        $user->branch_id = $request->role === 'branch_user' ? $request->branch_id : null;

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
        // Mencegah admin menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Cek riwayat transaksi sebelum menghapus
        if ($user->transactionsAsTherapist()->exists() || $user->transactionsAsCashier()->exists()) {
            return back()->with('error', 'User tidak bisa dihapus karena memiliki riwayat transaksi.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}