<?php

use Illuminate\Support\Facades\Route;
// Admin Controllers
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpenseCategoryController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
// Branch Controllers
use App\Http\Controllers\Branch\DashboardController as BranchDashboardController;
use App\Http\Controllers\Branch\ExpenseController as BranchExpenseController;
// Other Controllers
use App\Http\Controllers\POSController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BranchPerformanceController;
use App\Http\Controllers\Admin\SalaryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama, jika belum login akan ke halaman login.
Route::get('/', function () {
    if (!auth()->check()) {
        return view('auth.login');
    }
    return redirect()->route('dashboard');
});


// === DASHBOARD ROUTE UTAMA ===
// Route ini akan mengarahkan user ke dashboard yang sesuai berdasarkan rolenya.
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('branch.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');


// === PROFILE ROUTES (BAWAAN BREEZE) ===
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// === ADMIN ROUTES ===
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('branches', BranchController::class);
    Route::resource('packages', PackageController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('expense-categories', ExpenseCategoryController::class);
    Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show']);

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'exportExcel'])->name('reports.export');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');

    // DIPINDAHKAN: Inventory routes sekarang berada di dalam grup Admin.
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/{branch}', [InventoryController::class, 'show'])->name('inventory.show');
    Route::put('/inventory/{branch}', [InventoryController::class, 'update'])->name('inventory.update');

    Route::get('/branch-performance', [BranchPerformanceController::class, 'index'])->name('branch-performance.index');

    Route::get('users/{user}/salary', [SalaryController::class, 'edit'])->name('users.salary.edit');
    Route::put('users/{user}/salary', [SalaryController::class, 'update'])->name('users.salary.update');

});


// === BRANCH USER ROUTES ===
Route::middleware(['auth', 'branch_user'])->group(function () {
    
    // Grup untuk semua yang berhubungan dengan POS
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/create', [POSController::class, 'create'])->name('create');
        Route::post('/store', [POSController::class, 'store'])->name('store');
        Route::get('/history', [POSController::class, 'history'])->name('history');
    });

    // Grup untuk semua yang berhubungan dengan halaman internal Cabang
    Route::prefix('branch')->name('branch.')->group(function () {
        Route::get('/dashboard', [BranchDashboardController::class, 'index'])->name('dashboard');
        Route::resource('expenses', BranchExpenseController::class);
    });
});


// Ini akan mengimpor semua route otentikasi dari Breeze (login, register, dll)
require __DIR__.'/auth.php';