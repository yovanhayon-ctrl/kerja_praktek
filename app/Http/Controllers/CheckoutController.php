<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\DetailPesanan;

class CheckoutController extends Controller
{
    public function simpan(Request $request)
    {
        $request->validate([
            'nama'    => 'required',
            'no_meja' => 'required|numeric',
            'items'   => 'required',
            'total'   => 'required|numeric',
        ]);

        // Simpan pesanan utama
        $pesanan = Pesanan::create([
            'nama_pemesan'      => $request->nama,
            'no_meja'           => $request->no_meja,
            'catatan'           => $request->catatan,
            'metode_pembayaran' => 'cash',
            'total_harga'       => $request->total,
            'status'            => 'pending',
        ]);

        // Simpan detail item pesanan
        $items = json_decode($request->items, true);
        foreach ($items as $item) {
            DetailPesanan::create([
                'pesanan_id' => $pesanan->id,
                'menu_id'    => $item['id'],
                'nama_menu'  => $item['nama'],
                'harga'      => $item['harga'],
                'qty'        => $item['qty'],
                'subtotal'   => $item['harga'] * $item['qty'],
            ]);
        }

        return redirect('/riwayat')->with('success', 'Pesanan berhasil dibuat! Silakan bayar di kasir.');
    }
}