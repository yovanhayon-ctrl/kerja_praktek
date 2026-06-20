<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;

class RiwayatController extends Controller
{
    public function index()
    {
        // 1. Ambil daftar ID pesanan yang pernah dibuat oleh browser ini
        $riwayatSession = session()->get('customer_orders', []);

        // 2. Filter data pesanan: Hanya ambil data yang ID-nya ada di dalam session
        $pesanans = Pesanan::with('details')
                           ->whereIn('id', $riwayatSession) // Menyaring agar tidak menampilkan semua isi tabel
                           ->latest()
                           ->paginate(5);

        return view('riwayat.index', compact('pesanans'));
    }
}