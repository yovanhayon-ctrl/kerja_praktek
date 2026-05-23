<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi; // Pastikan kamu sudah membuat model/migration untuk tabel reservasis
use Carbon\Carbon;

class ReservasiController extends Controller
{
    public function index(Request $request)
    {
        // Default mengambil tanggal hari ini menggunakan timezone Asia/Jakarta yang sudah kita perbaiki kemarin
        $tanggalDipilih = $request->get('tanggal', Carbon::today()->toDateString());

        // Mengambil daftar nomor meja yang statusnya masih aktif (belum selesai/batal) di tanggal tersebut
        // Menggunakan block try-catch agar jika tabel database belum di-migrate, aplikasi tidak langsung crash
        try {
            $mejaTerboking = Reservasi::whereDate('waktu_reservasi', $tanggalDipilih)
                ->whereIn('status', ['PENDING', 'DISETUJUI'])
                ->pluck('nomor_meja')
                ->toArray();
        } catch (\Exception $e) {
            $mejaTerboking = [];
        }

        // Mengarah ke folder resources/views/reservasi/index.blade.php yang sudah kamu buat
        return view('reservasi.index', compact('mejaTerboking', 'tanggalDipilih'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'whatsapp'     => 'required|numeric',
            'tanggal'      => 'required|date|after_or_equal:today',
            'jam'          => 'required',
            'nomor_meja'   => 'required|integer|between:1,30',
            'catatan'      => 'nullable|string',
        ]);

        $waktuReservasi = Carbon::parse($request->tanggal . ' ' . $request->jam);

        // Validasi double-booking untuk keamanan data di tingkat database
        $cekDoubleBooking = Reservasi::whereDate('waktu_reservasi', $request->tanggal)
            ->where('nomor_meja', $request->nomor_meja)
            ->whereIn('status', ['PENDING', 'DISETUJUI'])
            ->exists();

        if ($cekDoubleBooking) {
            return redirect()->back()->with('error', 'Maaf, meja ini baru saja di-booking oleh pelanggan lain pada tanggal tersebut. Silakan pilih meja atau tanggal lainnya.');
        }

        // Proses simpan data reservasi baru
        $reservasi = new Reservasi();
        $reservasi->nama_lengkap = $request->nama_lengkap;
        $reservasi->whatsapp = $request->whatsapp;
        $reservasi->waktu_reservasi = $waktuReservasi;
        $reservasi->nomor_meja = $request->nomor_meja;
        $reservasi->catatan = $request->catatan;
        $reservasi->status = 'PENDING'; 
        $reservasi->save();

        return redirect()->back()->with('success', 'Reservasi meja berhasil dikirim! Silakan tunggu konfirmasi selanjutnya.');
    }
}