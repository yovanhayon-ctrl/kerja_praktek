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
            'id_pesanan'        => 'ORD-' . strtoupper(uniqid()), 
            'nama_pelanggan'    => $request->nama,                
            'nomor_meja'        => $request->no_meja,             
            'catatan'           => $request->catatan,              
            'detail_menu'       => $request->items,               
            'total_harga'       => $request->total,
            'status'            => 'PENDING',                     
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

        // ===================================================================
        // KUNCI PENGAMAN: Simpan ID pesanan yang baru ke session browser user
        // ===================================================================
        $riwayatSession = session()->get('customer_orders', []);
        $riwayatSession[] = $pesanan->id; // Menyimpan ID primary key pesanan
        session()->put('customer_orders', $riwayatSession);
        // ===================================================================

        return redirect('/riwayat')->with('success', 'Pesanan berhasil dibuat! Silakan bayar di kasir.');
    }
}