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

        $total_orders_count = Pesanan::count();

        return view('riwayat.index', compact('pesanans', 'total_orders_count'));
    }
}