<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    /**
     * Halaman daftar menu (customer/frontend)
     */
    public function index(Request $request)
    {
        $menus = Menu::query()
            // FILTER UTAMA: Hanya tampilkan menu dengan status 1 (Tersedia)
            // Menu yang di-set "Habis" di dashboard admin tidak akan muncul di sini
            ->where('status', 1) 
            
            // Tetap pertahankan logika filter kategori jika ada
            ->when($request->kategori, fn($q) => $q->where('kategori', $request->kategori))
            
            // Tetap pertahankan logika filter pencarian jika ada
            ->when($request->search, fn($q) => $q->where('nama_menu', 'like', '%' . $request->search . '%'))
            
            ->latest()
            ->paginate(8); // Tampilkan 8 menu per halaman agar rapi

        return view('menu.index', compact('menus'));
    }

    /**
     * Halaman detail menu (customer)
     */
    public function show($id)
    {
        // Pastikan menu yang diakses juga harus yang berstatus aktif
        $menu = Menu::where('status', 1)->findOrFail($id);
        return view('menu.detail', compact('menu'));
    }

    /**
     * Halaman form tambah menu (Logika Admin)
     * Catatan: Sebaiknya rute ini hanya bisa diakses admin melalui middleware
     */
    public function create()
    {
        return view('menu.create');
    }

    /**
     * Menyimpan data menu baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga'     => 'required|numeric|min:0',
            'kategori'  => 'required|in:Makanan,Minuman,Paket',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['nama_menu', 'deskripsi', 'harga', 'kategori']);

        // Default status saat input baru adalah 1 (Tersedia)
        $data['status'] = 1;

        // Logika upload gambar ke folder storage/app/public/menu
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('menu', 'public');
        }

        Menu::create($data);

        return redirect('/menu')->with('success', 'Menu berhasil ditambahkan!');
    }
}