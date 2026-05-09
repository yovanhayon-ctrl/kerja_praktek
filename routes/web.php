<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\RiwayatController;

// Import Controller Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminPesananController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\ManajemenAdminController;

use App\Mail\KontakKami;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTE CUSTOMER (FRONTEND) ---
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/menu', [MenuController::class, 'index']);
Route::get('/menu/create', [MenuController::class, 'create']);
Route::get('/menu/{id}', [MenuController::class, 'show']);
Route::post('/menu/store', [MenuController::class, 'store']);
Route::get('/cart', function () { return view('cart.index'); });
Route::get('/checkout', function () { return view('checkout.index'); });
Route::post('/checkout/simpan', [CheckoutController::class, 'simpan']);
Route::get('/riwayat', [RiwayatController::class, 'index']);
Route::get('/tentang', function () { return view('tentang.index'); });

Route::post('/tentang/kirim', function (\Illuminate\Http\Request $request) {
    $data = $request->validate([
        'nama'  => 'required',
        'email' => 'required|email',
        'pesan' => 'required',
    ]);
    Mail::to('yhsec2004@gmail.com')->send(new KontakKami($data));
    return back()->with('pesan_sukses', 'Pesan kamu berhasil dikirim!');
});

// --- RUTE LOGIN ADMIN ---
Route::get('/login', function () {
    return view('admin.login'); // Memanggil file login.blade.php
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('admin/dashboard');
    }

    return back()->withErrors(['email' => 'Email atau password salah.']);
})->name('login.post');


// --- RUTE ADMIN PANEL (BACKEND) DENGAN PENGAMAN ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kelola Menu
    Route::get('/menu', [AdminMenuController::class, 'index'])->name('menu.index');
    
    // Data Pesanan
    Route::get('/pesanan', [AdminPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/detail/{id}', [AdminPesananController::class, 'show'])->name('pesanan.detail');
    
    // Statistik
    Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik');
    
    // Manajemen Admin
    Route::get('/manajemen-admin', [ManajemenAdminController::class, 'index'])->name('admin.index');

    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});