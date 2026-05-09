<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // --- STATISTIK PESANAN ---
        $total_pesanan    = Pesanan::count();
        $pesanan_kemarin  = Pesanan::whereDate('created_at', Carbon::yesterday())->count();
        $pesanan_hari_ini = Pesanan::whereDate('created_at', $today)->count();
        
        $persen_pesanan = $pesanan_kemarin > 0 
            ? round((($pesanan_hari_ini - $pesanan_kemarin) / $pesanan_kemarin) * 100) 
            : ($pesanan_hari_ini > 0 ? 100 : 0);

        // --- STATISTIK PENDAPATAN ---
        // Menghapus filter status 'selesai' agar angka Rp 0 di dashboard Anda terisi
        $total_pendapatan = Pesanan::sum('total_harga'); 
        $pendapatan_hari_ini = Pesanan::whereDate('created_at', $today)->sum('total_harga');

        // --- MENU & PELANGGAN ---
        $menu_terlaris = DetailPesanan::select('nama_menu', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('nama_menu')
            ->orderByDesc('total_qty')
            ->first()?->nama_menu ?? '-';

        $jumlah_pelanggan = Pesanan::distinct('nama_pemesan')->count('nama_pemesan');

        // --- STATUS COUNT ---
        $count_pending    = Pesanan::where('status', 'pending')->count();
        $count_diproses   = Pesanan::where('status', 'diproses')->count();
        $count_selesai    = Pesanan::where('status', 'selesai')->count();
        $count_dibatalkan = Pesanan::where('status', 'dibatalkan')->count();

        // --- TABEL PESANAN TERBARU ---
        $pesanan_terbaru = Pesanan::latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'total_pesanan', 'persen_pesanan',
            'total_pendapatan', 'pendapatan_hari_ini',
            'menu_terlaris', 'jumlah_pelanggan',
            'count_pending', 'count_diproses', 'count_selesai', 'count_dibatalkan',
            'pesanan_terbaru'
        ));
    }
}