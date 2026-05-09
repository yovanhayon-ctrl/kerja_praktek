<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu; // Pastikan Model diimpor
use Illuminate\Http\Request;

class AdminMenuController extends Controller
{
    public function index()
    {
        // Mengambil semua data menu dari database
        $menus = Menu::all();
        
        // Mengarahkan ke file view yang sudah Anda buat
        return view('admin.menu.index', compact('menus'));
    }
}