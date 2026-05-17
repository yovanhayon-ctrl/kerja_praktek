<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\RiwayatController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminPesananController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\ManajemenAdminController;

// --- RUTE CUSTOMER ---
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');
Route::get('/cart', function () { return view('cart.index'); })->name('cart');
Route::get('/checkout', function () { return view('checkout.index'); })->name('checkout');
Route::post('/checkout/simpan', [CheckoutController::class, 'simpan'])->name('checkout.simpan');
Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');
Route::get('/tentang', function () { return view('tentang.index'); })->name('tentang');

// --- RUTE LOGIN/LOGOUT ---
Route::get('/login', function () { return view('admin.login'); })->name('login');

// PASTIKAN: Di DashboardController harus ada method bernama 'authenticate' atau 'login'
// Jika Anda menggunakan Auth bawaan Laravel, biasanya ini lari ke LoginController
Route::post('/login', [DashboardController::class, 'login'])->name('login.post'); 

// --- RUTE ADMIN PANEL (DILINDUNGI AUTH) ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Menu
    Route::resource('menu', AdminMenuController::class)->except(['show']);
    Route::patch('/menu/{id}/toggle', [AdminMenuController::class, 'toggleStatus'])->name('menu.toggle');
    
    // DATA PESANAN
    Route::get('/pesanan/export', [AdminPesananController::class, 'export'])->name('pesanan.export'); // <-- TAMBAHKAN BARIS INI
    Route::get('/pesanan', [AdminPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/detail/{id}', [AdminPesananController::class, 'show'])->name('pesanan.show');
    Route::patch('/pesanan/{id}/status', [AdminPesananController::class, 'updateStatus'])->name('pesanan.updateStatus');
    
    // Statistik & Manajemen Admin
    Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik');
    
    // PERBAIKAN: Nama route disesuaikan agar tidak bentrok (admin.manajemen)
    Route::get('/manajemen-admin', [ManajemenAdminController::class, 'index'])->name('manajemen.index');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});