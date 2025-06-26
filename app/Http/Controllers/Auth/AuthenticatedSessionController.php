<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        
        $user = Auth::user();

        // Logika untuk menyimpan session cabang
        if ($user->role === 'branch_user') {
            // Jika user adalah pengguna cabang, simpan ID cabangnya di session
            $request->session()->put('branch_id', $user->branch_id);
            $request->session()->put('branch_name', $user->branch->name);
        } elseif ($user->isAdmin()) {
            // Jika admin login, awalnya tidak ada cabang yang dipilih.
            // Admin bisa memilih cabang dari dashboardnya.
            $request->session()->forget(['branch_id', 'branch_name']);
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
