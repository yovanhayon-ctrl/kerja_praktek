<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\Pesanan; // Pastikan model ini diimpor
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil tanggal dari URL atau hari ini
        $tanggalDipilih = $request->get('tanggal', Carbon::today()->toDateString());

        // 2. Ambil meja dari tabel Reservasi (Booking Online)
        $mejaReservasi = Reservasi::whereDate('waktu_reservasi', $tanggalDipilih)
            ->whereIn('status', ['PENDING', 'DISETUJUI'])
            ->pluck('nomor_meja')
            ->toArray();

        // 3. Ambil meja dari tabel Pesanan (Transaksi Kasir/Web)
        $mejaPesanan = Pesanan::whereDate('created_at', $tanggalDipilih)
            ->whereIn('status', ['PENDING', 'DIPROSES'])
            ->pluck('nomor_meja')
            ->toArray();

        // 4. Gabungkan semua meja yang terpakai
        $mejaTerboking = [];
        $dataGabungan = array_merge($mejaReservasi, $mejaPesanan);

        foreach ($dataGabungan as $meja) {
            $pecah = explode(',', (string)$meja);
            foreach ($pecah as $m) {
                if (trim($m) !== '') {
                    $mejaTerboking[] = (string)trim($m);
                }
            }
        }
        $mejaTerboking = array_unique($mejaTerboking);

        return view('reservasi.index', compact('tanggalDipilih', 'mejaTerboking'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'whatsapp'     => 'required|numeric',
            'tanggal'      => 'required|date|after_or_equal:today',
            'jam'          => 'required',
            'nomor_meja'   => 'required|array',
            'nomor_meja.*' => 'integer|between:1,30',
        ]);

        $waktuReservasi = Carbon::parse($request->tanggal . ' ' . $request->jam);

        foreach ($request->nomor_meja as $noMeja) {
            // Cek bentrok di Reservasi
            $bentrokReservasi = Reservasi::whereDate('waktu_reservasi', $request->tanggal)
                ->where('nomor_meja', $noMeja)
                ->whereIn('status', ['PENDING', 'DISETUJUI'])->exists();

            // Cek bentrok di Pesanan
            $bentrokPesanan = Pesanan::whereDate('created_at', $request->tanggal)
                ->where('nomor_meja', $noMeja)
                ->whereIn('status', ['PENDING', 'DIPROSES'])->exists();

            if ($bentrokReservasi || $bentrokPesanan) {
                return redirect()->back()->with('error', 'Maaf, Meja #' . $noMeja . ' sudah terpakai.');
            }
        }

        foreach ($request->nomor_meja as $noMeja) {
            $res = new Reservasi();
            $res->nama_lengkap = $request->nama_lengkap;
            $res->whatsapp = $request->whatsapp;
            $res->waktu_reservasi = $waktuReservasi;
            $res->nomor_meja = $noMeja;
            $res->catatan = $request->catatan;
            $res->status = 'PENDING';
            $res->save();
        }

        return redirect()->back()->with('success', 'Reservasi berhasil dikirim!');
    }

    // (Fungsi cekStatusIntra, cekStatusForm, cekStatusProses biarkan tetap seperti kode lama Anda)
    public function cekStatusIntra(Request $request) { /* ... */ }
    public function cekStatusForm() { return view('reservasi.cek_status'); }
    public function cekStatusProses(Request $request) { /* ... */ }
}