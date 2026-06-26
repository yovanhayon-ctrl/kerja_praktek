<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Reservasi; 

class CheckoutController extends Controller
{
    // Fungsi untuk menampilkan halaman checkout dan mendeteksi meja terpakai
    public function index()
    {
        // 1. Cari meja dari transaksi langsung (walk-in/web) yang statusnya masih PENDING/DIPROSES
        // PERBAIKAN: Hapus whereDate() agar pesanan yang melewati tengah malam tetap terkunci
        $pesananAktif = Pesanan::whereIn('status', ['PENDING', 'DIPROSES'])
            ->pluck('nomor_meja')
            ->toArray();

        // 2. Cari meja dari sistem Reservasi KHUSUS hari ini
        // PERBAIKAN: Gunakan kolom 'waktu_reservasi' yang lebih tepat untuk deteksi booking
        $reservasiAktif = Reservasi::whereDate('waktu_reservasi', now()->toDateString()) 
            ->whereIn('status', ['PENDING', 'DISETUJUI'])
            ->pluck('nomor_meja')
            ->toArray();

        // 3. Gabungkan kedua data. Pecah jika ada reservasi dengan format koma (contoh: "2,3,4")
        $mejaTerboking = [];
        foreach(array_merge($pesananAktif, $reservasiAktif) as $meja) {
            $pecah = explode(',', (string)$meja);
            foreach($pecah as $m) {
                // Pastikan hanya angka yang masuk dan tidak ada spasi kosong
                if (trim($m) !== '') {
                    $mejaTerboking[] = (string)trim($m);
                }
            }
        }
        
        // Hapus duplikat nomor meja agar array lebih bersih
        $mejaTerboking = array_unique($mejaTerboking);

        // Lempar data mejaTerboking ke view checkout
        return view('checkout.index', compact('mejaTerboking'));
    }

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
                'status'     => 'PENDING',
            ]);
        }

        // ===================================================================
        // KUNCI PENGAMAN: Simpan ID pesanan yang baru ke session browser user
        // ===================================================================
        $riwayatSession = session()->get('customer_orders', []);
        $riwayatSession[] = $pesanan->id; 
        session()->put('customer_orders', $riwayatSession);
        // ===================================================================

        return redirect('/riwayat')->with('success', 'Pesanan berhasil dibuat! Silakan bayar di kasir.');
    }
}