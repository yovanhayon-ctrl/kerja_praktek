<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class BerandaController extends Controller
{
    public function index()
    {
        // Ambil menu untuk ditampilkan di section "Menu Populer"
        $menuPopuler = Menu::where('status', 1) // Hanya ambil yang berstatus 'Tersedia'
                           ->latest()
                           ->take(4)
                           ->get();

        // Kirim data ke view beranda/index.blade.php
        return view('beranda.index', compact('menuPopuler'));
    }
}