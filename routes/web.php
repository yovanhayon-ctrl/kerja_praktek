<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\RiwayatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

// Route menu
Route::get('/menu', [MenuController::class, 'index']);
Route::get('/menu/create', [MenuController::class, 'create']);
Route::get('/menu/{id}', [MenuController::class, 'show']);
Route::post('/menu/store', [MenuController::class, 'store']);

// Route cart
Route::get('/cart', function () {
    return view('cart.index');
});

// Route checkout
Route::get('/checkout', function () {
    return view('checkout.index');
});
Route::post('/checkout/simpan', [CheckoutController::class, 'simpan']);

// Route riwayat ← ini yang kurang!
Route::get('/riwayat', [RiwayatController::class, 'index']);

// Route tentang
Route::get('/tentang', function () {
    return view('tentang.index');
});
Route::post('/tentang/kirim', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'nama'  => 'required',
        'email' => 'required|email',
        'pesan' => 'required',
    ]);
    return back()->with('pesan_sukses', 'Pesan kamu berhasil dikirim! Kami akan segera menghubungi kamu.');
});