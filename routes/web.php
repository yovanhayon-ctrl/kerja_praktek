<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Customer/Frontend Controllers
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\PesanController; 

// Admin Panel Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminPesananController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\ManajemenAdminController;

/*
|-------------------------------------
| Web Routes - RestoKu (RM Saung Tiga)
|-------------------------------------
*/

//         ===== RUTE CUSTOMER (FRONTEND) =====
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

// Menu Restoran
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');

// Keranjang & Proses Pemesanan
Route::get('/cart', function () { return view('cart.index'); })->name('cart');
Route::get('/checkout', function () { return view('checkout.index'); })->name('checkout');
Route::post('/checkout/simpan', [CheckoutController::class, 'simpan'])->name('checkout.simpan');
Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');

// Halaman Tentang Kami & Kontak
Route::get('/tentang', function () { return view('tentang.index'); })->name('tentang');
Route::post('/kirim-pesan', [PesanController::class, 'store'])->name('pesan.store');


//         ===== AUTENTIKASI (LOGIN/LOGOUT) =====
Route::get('/login', function () { return view('admin.login'); })->name('login');
Route::post('/login', [DashboardController::class, 'login'])->name('login.post'); 

// Rute Tambahan Eksekusi Ganti Password Baru dari Modal Form Login
Route::post('/login/reset-password', [DashboardController::class, 'resetPasswordDirect'])->name('admin.password.reset_direct');


//         ===== RUTE ADMIN PANEL (SECURE AUTH) =====
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Menu Kuliner
    Route::resource('menu', AdminMenuController::class)->except(['show']);
    Route::patch('/menu/{id}/toggle', [AdminMenuController::class, 'toggleStatus'])->name('menu.toggle');
    
    // Manajemen Transaksi & Data Pesanan
    Route::get('/pesanan/export', [AdminPesananController::class, 'export'])->name('pesanan.export'); 
    Route::get('/pesanan', [AdminPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/detail/{id}', [AdminPesananController::class, 'show'])->name('pesanan.show');
    Route::patch('/pesanan/{id}/status', [AdminPesananController::class, 'updateStatus'])->name('pesanan.updateStatus');
    
    // Manajemen Hubungi Kami / Pesan Masuk (Mail)
    Route::get('/pesan-masuk', [PesanController::class, 'index'])->name('pesan.index');
    Route::patch('/pesan-masuk/{id}/baca', [PesanController::class, 'updateStatus'])->name('pesan.baca');
    Route::delete('/pesan-masuk/{id}', [PesanController::class, 'destroy'])->name('pesan.destroy');

    // Analisis & Statistik Penjualan
    Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik');
    
    // Manajemen Akun Pengelola / Staff Admin
    Route::get('/manajemen-admin', [ManajemenAdminController::class, 'index'])->name('manajemen_admin.index');
    Route::post('/manajemen-admin', [ManajemenAdminController::class, 'store'])->name('manajemen_admin.store');
    Route::delete('/manajemen-admin/{id}', [ManajemenAdminController::class, 'destroy'])->name('manajemen_admin.destroy');

    // Proses Keluar Sistem
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});