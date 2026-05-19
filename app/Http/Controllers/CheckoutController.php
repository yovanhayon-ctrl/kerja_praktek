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
            'no_meja' => 'required|integer|min:1|max:30',
            'items'   => 'required',
            'total'   => 'required|numeric',
        ]);

        // Simpan pesanan utama
        $pesanan = Pesanan::create([
            'id_pesanan'        => 'ORD-' . strtoupper(uniqid()), // Tambahkan ini agar ID tidak kosong
            'nama_pelanggan'    => $request->nama,                // SESUAI MIGRATION
            'nomor_meja'        => $request->no_meja,             // SESUAI MIGRATION
            'catatan'           => $request->catatan,              // Simpan catatan dari checkout
            'detail_menu'       => $request->items,               // SESUAI MIGRATION (Simpan JSON)
            'total_harga'       => $request->total,
            'status'            => 'PENDING',                     // Gunakan Huruf Kapital sesuai Enum
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