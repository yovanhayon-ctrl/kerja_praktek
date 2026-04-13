<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;

class RiwayatController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with('details')
                           ->latest()
                           ->paginate(5);

        return view('riwayat.index', compact('pesanans'));
    }
}