<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Reservasi;

class KasirController extends Controller
{
    // 1. Menampilkan Halaman Utama Kasir beserta Katalog Menu
    public function index(Request $request)
    {
        // PERBAIKAN: Hanya mengambil menu yang statusnya Aktif/Tersedia (status = 1)
        $query = Menu::where('status', 1);

        // Fitur Pencarian Menu
        if ($request->filled('search')) {
            $query->where('nama_menu', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter Kategori Menu (Makanan, Minuman, Paketan)
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $menus = $query->get();

        // Mengambil data keranjang nota dari session (default array kosong)
        $cart = session()->get('cart', []);

        // Hitung total tagihan belanja realtime di backend
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }

        // --- LOGIKA SENSOR MEJA TERBOKING ---
        
        // PERBAIKAN 1: Hapus whereDate pada Pesanan agar pesanan yang lewat tengah malam tetap terdeteksi
        $pesananAktif = Pesanan::whereIn('status', ['PENDING', 'DIPROSES'])
            ->pluck('nomor_meja')->toArray();

        // Untuk reservasi, tetap gunakan filter tanggal hari ini (pastikan nama kolomnya sesuai database Anda, misal 'waktu_reservasi' atau 'created_at')
        $reservasiAktif = Reservasi::whereDate('created_at', now()->toDateString())
            ->whereIn('status', ['PENDING', 'DISETUJUI'])
            ->pluck('nomor_meja')->toArray();

        // PERBAIKAN 2: Keamanan array ekstra untuk mencegah error data kosong/tipe data
        $mejaTerboking = [];
        foreach(array_merge($pesananAktif, $reservasiAktif) as $meja) {
            $pecah = explode(',', (string)$meja);
            foreach($pecah as $m) {
                if (trim($m) !== '') {
                    $mejaTerboking[] = (string)trim($m);
                }
            }
        }
        $mejaTerboking = array_unique($mejaTerboking);
        // ------------------------------------

        return view('admin.kasir.index', compact('menus', 'cart', 'total', 'mejaTerboking'));
    }

    // INTERGRASI: Memuat data dari Halaman Pesanan ke dalam Keranjang Kasir (Autofill)
    public function prosesPesanan($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        
        // Ambil semua item menu yang ada di dalam pesanan tersebut
        $details = DetailPesanan::where('pesanan_id', $id)->get();
        
        $cart = [];
        foreach ($details as $detail) {
            // Ambil detail gambar & kategori dari model Menu (jika ada relasi data)
            $menu = Menu::find($detail->menu_id);
            
            $cart[$detail->menu_id] = [
                'nama_menu' => $detail->nama_menu,
                'kategori'  => $menu ? $menu->kategori : 'Makanan',
                'harga'     => $detail->harga,
                'jumlah'    => $detail->qty, // Mapping kolom qty dari DB ke 'jumlah' session kasir
                'gambar'    => $menu ? $menu->gambar : ''
            ];
        }
        
        // Daftarkan data pesanan ke dalam session kasir aktif
        session()->put('cart', $cart);
        session()->put('pesanan_id_aktif', $pesanan->id);
        session()->put('nama_pelanggan_aktif', $pesanan->nama_pelanggan);
        session()->put('nomor_meja_aktif', $pesanan->nomor_meja);
        
        return redirect()->route('admin.kasir.index')->with('success', 'Data pesanan pelanggan berhasil dimuat ke kasir!');
    }

    // 2. Memasukkan Menu ke dalam Keranjang Nota
    public function add($id)
    {
        $menu = Menu::findOrFail($id);
        $cart = session()->get('cart', []);

        // Jika menu sudah ada di keranjang, naikkan jumlahnya (Qty)
        if (isset($cart[$id])) {
            $cart[$id]['jumlah']++;
        } else {
            // Jika belum ada, buat baris item baru di dalam session
            $cart[$id] = [
                'nama_menu' => $menu->nama_menu,
                'kategori'  => $menu->kategori,
                'harga'     => $menu->harga,
                'jumlah'    => 1,
                'gambar'    => $menu->gambar
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('admin.kasir.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    // 3. Memperbarui Jumlah Kuantitas (Qty) Item dari Input Form
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['jumlah'] = $request->jumlah;
            session()->put('cart', $cart);
        }

        return redirect()->route('admin.kasir.index');
    }

    // 4. Menghapus Salah Satu Item dari Keranjang Nota
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('admin.kasir.index')->with('success', 'Item berhasil dihapus dari nota.');
    }

    // 5. Mengosongkan / Reset Seluruh Isi Keranjang Nota & Data Pesanan Aktif
    public function clear()
    {
        // Hapus seluruh session kasir
        session()->forget(['cart', 'pesanan_id_aktif', 'nama_pelanggan_aktif', 'nomor_meja_aktif']);
        return redirect()->route('admin.kasir.index')->with('success', 'Nota belanja dikosongkan.');
    }

    // 6. Menyimpan Checkout Transaksi ke Database (Mendukung Transaksi Baru & Update Pesanan Selesai)
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (count($cart) == 0) {
            return redirect()->back()->with('error', 'Gagal memproses, keranjang nota masih kosong!');
        }

        // Hitung akumulasi total harga dari session akhir
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }

        // INTEGRASI LOGIKA: Cek apakah memproses pesanan meja yang sudah ada atau transaksi counter baru
        if (session()->has('pesanan_id_aktif')) {
            $pesananId = session()->get('pesanan_id_aktif');
            $pesanan = Pesanan::findOrFail($pesananId);
            
            // Langkah A1: Update data induk pesanan yang sudah ada
            $pesanan->update([
                'nama_pelanggan' => $request->nama_pelanggan ?? 'Pembeli Umum',
                'nomor_meja'     => $request->nomor_meja ?? 'Takeaway',
                'total_harga'    => $total,
                'status'         => 'SELESAI', // Ubah status menjadi SELESAI karena sudah dibayar di kasir
            ]);

            // Langkah B1: Hapus detail pesanan lama untuk ditulis ulang (sinkronisasi jika kasir mengubah qty/item)
            DetailPesanan::where('pesanan_id', $pesananId)->delete();
        } else {
            // Langkah A2: Jika transaksi baru langsung dari counter kasir, buat data induk baru
            $pesanan = Pesanan::create([
                'id_pesanan'     => 'ORD-' . strtoupper(uniqid()), 
                'nama_pelanggan' => $request->nama_pelanggan ?? 'Pembeli Umum',
                'nomor_meja'     => $request->nomor_meja ?? 'Takeaway',
                'total_harga'    => $total,
                'status'         => 'SELESAI',
            ]);
        }

        // Langkah C: Simpan/Tulis ulang item belanja ke tabel detail_pesanans
        foreach ($cart as $menuId => $item) {
            DetailPesanan::create([
                'pesanan_id' => $pesanan->id,
                'menu_id'    => $menuId,
                'nama_menu'  => $item['nama_menu'],
                'harga'      => $item['harga'],
                'qty'        => $item['jumlah'],
                'subtotal'   => $item['harga'] * $item['jumlah'],
            ]);
        }

        // Langkah D: Bersihkan seluruh session kasir setelah sukses menyimpan transaksi
        session()->forget(['cart', 'pesanan_id_aktif', 'nama_pelanggan_aktif', 'nomor_meja_aktif']);

        return redirect()->route('admin.kasir.index')->with('success', 'Transaksi berhasil diproses dan dicetak!');
    }
}