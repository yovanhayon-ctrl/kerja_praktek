<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu; // ← import Model Menu kamu

class BerandaController extends Controller
{
    public function index()
    {
        // Ambil menu untuk ditampilkan di section "Menu Populer"
        $menuPopuler = Menu::latest()
                           ->take(4)
                           ->get();

        // Kirim data ke view beranda/index.blade.php
        return view('beranda.index', compact('menuPopuler'));
    }
}