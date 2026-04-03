<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    // menampilkan semua menu
    public function index()
    {
        $menus = Menu::all();
        return view('menu.index', compact('menus'));
    }

    // menampilkan form tambah menu
    public function create()
    {
        return view('menu.create');
    }

    // menyimpan data menu ke database
    public function store(Request $request)
    {
        Menu::create($request->all());
        return redirect('/menu');
    }
}