<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    /**
     * Menampilkan form untuk mengatur gaji seorang user.
     */
    public function edit(User $user)
    {
        // Keamanan: Pastikan kita hanya mengatur gaji untuk pengguna cabang.
        if ($user->isAdmin()) {
            abort(403, 'Tidak bisa mengatur gaji untuk Admin.');
        }

        // Cari data gaji yang sudah ada untuk user ini di cabangnya, atau buat objek baru jika belum ada.
        $salary = Salary::firstOrNew([
            'user_id' => $user->id,
            'branch_id' => $user->branch_id,
        ]);

        return view('admin.salaries.edit', compact('user', 'salary'));
    }

    /**
     * Menyimpan atau memperbarui data gaji.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Gunakan updateOrCreate untuk membuat data gaji jika belum ada, atau memperbaruinya jika sudah ada.
        Salary::updateOrCreate(
            [
                'user_id' => $user->id,
                'branch_id' => $user->branch_id,
            ],
            [
                'amount' => $request->amount,
                'notes' => $request->notes,
            ]
        );

        return redirect()->route('admin.users.index')->with('success', 'Gaji untuk ' . $user->name . ' berhasil diatur.');
    }
}
