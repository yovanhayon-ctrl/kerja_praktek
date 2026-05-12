<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request; 
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. STATISTIK UTAMA
        $total_pesanan    = Pesanan::count();
        $total_pendapatan = DetailPesanan::sum('subtotal'); 
        
        $menu_terlaris = DetailPesanan::select('nama_menu', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('nama_menu')
            ->orderByDesc('total_qty')
            ->first()?->nama_menu ?? '-';

        // Menggunakan 'nama_pelanggan' sesuai kolom database Anda
        $total_pelanggan = Pesanan::distinct('nama_pelanggan')->count('nama_pelanggan');

        // 2. LOGIKA PERSENTASE KENAIKAN
        $pesanan_kemarin = Pesanan::whereDate('created_at', Carbon::yesterday())->count();
        $pesanan_hari_ini = Pesanan::whereDate('created_at', $today)->count();
        $persen_pesanan = $pesanan_kemarin > 0 
            ? round((($pesanan_hari_ini - $pesanan_kemarin) / $pesanan_kemarin) * 100, 1) 
            : ($pesanan_hari_ini > 0 ? 100 : 0);

        // 3. DATA STATUS
        $count_pending    = Pesanan::where('status', 'PENDING')->count();
        $count_diproses   = Pesanan::where('status', 'DIPROSES')->count();
        $count_selesai    = Pesanan::where('status', 'SELESAI')->count();
        $count_dibatalkan = Pesanan::where('status', 'DIBATALKAN')->count();

        $total_all = Pesanan::count() ?: 1;
        $status_data = [
            ['label' => 'Pending',    'color' => '#fbbf24', 'percent' => round(($count_pending / $total_all) * 100)],
            ['label' => 'Processing', 'color' => '#3b82f6', 'percent' => round(($count_diproses / $total_all) * 100)],
            ['label' => 'Completed',  'color' => '#4ade80', 'percent' => round(($count_selesai / $total_all) * 100)],
            ['label' => 'Cancelled',  'color' => '#ef4444', 'percent' => round(($count_dibatalkan / $total_all) * 100)],
        ];

        // 4. DATA HARI INI
        $pendapatan_hari_ini = DetailPesanan::join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
            ->whereDate('pesanans.created_at', $today)
            ->sum('detail_pesanans.subtotal');
        
        $jumlah_pesanan_hari_ini = $pesanan_hari_ini;

        // 5. TABEL PESANAN TERBARU (Pastikan mengambil kolom yang diperlukan)
        $recent_orders = Pesanan::latest()->take(5)->get();

        // 6. GET TOTAL ORDERS FOR SEQUENTIAL NUMBERING
        $total_orders_count = Pesanan::count();

        return view('admin.dashboard.index', compact(
            'total_pesanan', 'total_pendapatan', 'menu_terlaris', 'total_pelanggan',
            'persen_pesanan', 'status_data', 'pendapatan_hari_ini',
            'jumlah_pesanan_hari_ini', 'recent_orders', 'total_orders_count'
        ));
    }
}