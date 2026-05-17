<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatistikController extends Controller
{
    public function index()
    {
        // 1. Ringkasan Data (Cards)
        $total_pendapatan = Pesanan::where('status', 'SELESAI')->sum('total_harga');
        $total_pesanan = Pesanan::count();
        $total_menu = Menu::count();

        // Rata-rata waktu tunggu pesanan selesai (menit)
        $pesanan_selesai = Pesanan::where('status', 'SELESAI')->get();
        $rata_waktu_tunggu = $pesanan_selesai->count() > 0
            ? round($pesanan_selesai->map(function($p) {
                return $p->updated_at->diffInMinutes($p->created_at);
            })->avg())
            : 0;

        // 2. Data Grafik Pendapatan 7 Hari Terakhir
        $grafik_pendapatan = [];
        $grafik_label = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $grafik_label[] = $date->translatedFormat('d M');
            
            $pendapatan_harian = Pesanan::where('status', 'SELESAI')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_harga');
                
            $grafik_pendapatan[] = $pendapatan_harian;
        }

        // 3. Algoritma Mengurai Kolom JSON 'detail_menu' untuk Mencari 5 Menu Terlaris
        $pesanans_sukses = Pesanan::where('status', 'SELESAI')->get();
        $rekap_menu = [];

        foreach ($pesanans_sukses as $pesanan) {
            // Decode data JSON dari kolom detail_menu
            $items = json_decode($pesanan->detail_menu, true);
            
            if (is_array($items)) {
                foreach ($items as $item) {
                    $nama = $item['nama'] ?? ($item['nama_menu'] ?? 'Unknown');
                    $qty = (int)($item['qty'] ?? ($item['jumlah'] ?? 0));
                    $harga = (int)($item['harga'] ?? 0);
                    $subtotal = $qty * $harga;

                    if (isset($rekap_menu[$nama])) {
                        $rekap_menu[$nama]['total_terjual'] += $qty;
                        $rekap_menu[$nama]['total_uang'] += $subtotal;
                    } else {
                        $rekap_menu[$nama] = [
                            'nama_menu' => $nama,
                            'total_terjual' => $qty,
                            'total_uang' => $subtotal
                        ];
                    }
                }
            }
        }

        // Urutkan menu berdasarkan total_terjual terbanyak dan ambil 5 teratas
        usort($rekap_menu, function ($a, $b) {
            return $b['total_terjual'] <=> $a['total_terjual'];
        });
        $menu_terlaris = collect(array_slice($rekap_menu, 0, 5));

        // Diarahkan ke folder statistik dan file index.blade.php
        return view('admin.statistik.index', compact(
            'total_pendapatan', 'total_pesanan', 'total_menu', 'rata_waktu_tunggu',
            'grafik_label', 'grafik_pendapatan', 'menu_terlaris'
        ));
    }
}