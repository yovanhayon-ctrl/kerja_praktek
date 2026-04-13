<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    // Halaman daftar menu (customer)
    public function index(Request $request)
    {
        $menus = Menu::query()
            // Filter kategori jika ada
            ->when($request->kategori, fn($q) => $q->where('kategori', $request->kategori))
            // Filter search jika ada
            ->when($request->search, fn($q) => $q->where('nama_menu', 'like', '%' . $request->search . '%'))
            ->latest()
            ->paginate(8); // tampilkan 8 menu per halaman

        return view('menu.index', compact('menus'));
    }

    // Halaman detail menu (customer)
    public function show($id)
    {
        $menu = Menu::findOrFail($id);
        return view('menu.detail', compact('menu'));
    }

    // Halaman form tambah menu (admin)
    public function create()
    {
        return view('menu.create');
    }

    // Simpan menu baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required',
            'harga'     => 'required|numeric',
            'kategori'  => 'required',
            'gambar'    => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['nama_menu', 'deskripsi', 'harga', 'kategori']);

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('menu', 'public');
        }

        Menu::create($data);

        return redirect('/menu')->with('success', 'Menu berhasil ditambahkan!');
    }
}