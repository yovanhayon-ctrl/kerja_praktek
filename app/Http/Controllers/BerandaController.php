<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pesanan;

class BerandaController extends Controller
{
    public function index()
    {
        // 1. Ambil semua pesanan dengan status SELESAI
        $pesanans_sukses = Pesanan::where('status', 'SELESAI')->get();
        $rekap_menu = [];

        // 2. Ekstrak JSON detail_menu untuk menghitung total qty per nama menu
        foreach ($pesanans_sukses as $pesanan) {
            $items = json_decode($pesanan->detail_menu, true);
            
            if (is_array($items)) {
                foreach ($items as $item) {
                    $nama = $item['nama'] ?? ($item['nama_menu'] ?? 'Unknown');
                    $qty = (int)($item['qty'] ?? ($item['jumlah'] ?? 0));

                    if (isset($rekap_menu[$nama])) {
                        $rekap_menu[$nama] += $qty;
                    } else {
                        $rekap_menu[$nama] = $qty;
                    }
                }
            }
        }

        // 3. Urutkan dari yang paling banyak terjual ke yang paling sedikit
        arsort($rekap_menu);
        
        // Ambil 4 nama menu teratas (Diambil 4 agar pas dengan grid layout di halaman depan)
        $top_menu_names = array_slice(array_keys($rekap_menu), 0, 4);

        $menuPopuler = collect();

        // 4. Tarik data utuh model Menu dari database berdasarkan nama menu terlaris
        if (!empty($top_menu_names)) {
            $menus = Menu::whereIn('nama_menu', $top_menu_names)
                         ->where('status', 1) // Pastikan hanya mengambil yang statusnya Tersedia
                         ->get();
            
            // Urutkan kembali hasil query agar sesuai dengan urutan rank terlaris dari array $top_menu_names
            $menuPopuler = $menus->sortBy(function($model) use ($top_menu_names) {
                return array_search($model->nama_menu, $top_menu_names);
            })->values();
        }

        // 5. FALLBACK (Pencegah Bug Desain): 
        // Jika menu yang laku kurang dari 4 jenis (misal baru buka), 
        // lengkapi kekosongannya dengan menu-menu terbaru yang berstatus tersedia.
        if ($menuPopuler->count() < 4) {
            $excludeIds = $menuPopuler->pluck('id')->toArray();
            $sisaKebutuhan = 4 - $menuPopuler->count();
            
            $tambahanMenu = Menu::where('status', 1)
                                ->whereNotIn('id', $excludeIds)
                                ->latest()
                                ->take($sisaKebutuhan)
                                ->get();
                                
            $menuPopuler = $menuPopuler->merge($tambahanMenu);
        }

        // Kirim data yang sudah diolah ke view beranda/index.blade.php
        return view('beranda.index', compact('menuPopuler'));
    }
}