<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    /**
     * Menampilkan Form Reservasi untuk Pelanggan (Terintegrasi Pengecekan Meja)
     */
    public function index(Request $request)
    {
        // 1. Ambil tanggal yang dipilih dari URL (?tanggal=...), jika tidak ada default ke hari ini
        $tanggalDipilih = $request->get('tanggal', Carbon::today()->toDateString());

        // 2. Trik mengamankan ketikan nama & wa saat halaman reload pas ganti tanggal
        if ($request->has('nama_lengkap') || $request->has('whatsapp')) {
            session()->flashInput($request->all());
        }

        // 3. Ambil daftar nomor meja yang sudah dibooking pada tanggal tersebut
        // KUNCI UTAMA: Hanya kunci meja yang berstatus PENDING dan DISETUJUI.
        // Status 'SELESAI' dan 'CANCELLED' dilepas agar meja otomatis kembali READY (Hijau).
        try {
            $mejaTerboking = Reservasi::whereDate('waktu_reservasi', $tanggalDipilih)
                ->whereIn('status', ['PENDING', 'DISETUJUI']) 
                ->pluck('nomor_meja')
                ->toArray();
        } catch (\Exception $e) {
            $mejaTerboking = [];
        }

        // 4. Kirim semua variabel yang dibutuhkan oleh blade reservasi/index.blade.php
        return view('reservasi.index', compact('tanggalDipilih', 'mejaTerboking')); 
    }

    /**
     * Menyimpan Data Reservasi Baru ke Database (Mendukung Multi-Meja)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'whatsapp'     => 'required|numeric',
            'tanggal'      => 'required|date|after_or_equal:today',
            'jam'          => 'required',
            'nomor_meja'   => 'required|array', 
            'nomor_meja.*' => 'integer|between:1,30',
            'catatan'      => 'nullable|string',
        ]);

        $waktuReservasi = Carbon::parse($request->tanggal . ' ' . $request->jam);

        // Validasi double-booking secara looping: Hanya bentrok jika statusnya PENDING atau DISETUJUI
        foreach ($request->nomor_meja as $noMeja) {
            $cekDoubleBooking = Reservasi::whereDate('waktu_reservasi', $request->tanggal)
                ->where('nomor_meja', $noMeja)
                ->whereIn('status', ['PENDING', 'DISETUJUI'])
                ->exists();

            if ($cekDoubleBooking) {
                return redirect()->back()->with('error', 'Maaf, Meja #' . $noMeja . ' baru saja dibooking pelanggan lain pada tanggal tersebut. Silakan pilih kombinasi meja lainnya.');
            }
        }

        // Simpan satu baris baru di DB untuk setiap meja yang dipilih
        foreach ($request->nomor_meja as $noMeja) {
            $reservasi = new Reservasi();
            $reservasi->nama_lengkap = $request->nama_lengkap;
            $reservasi->whatsapp = $request->whatsapp;
            $reservasi->waktu_reservasi = $waktuReservasi;
            $reservasi->nomor_meja = $noMeja;
            $reservasi->catatan = $request->catatan;
            $reservasi->status = 'PENDING'; 
            $reservasi->save();
        }

        return redirect()->back()->with('success', 'Reservasi untuk ' . count($request->nomor_meja) . ' meja berhasil dikirim! Silakan tunggu konfirmasi selanjutnya.');
    }

    /**
     * Fitur Baru: Memproses Pencarian Status Menggunakan AJAX Langsung di Dalam Tab Halaman
     */
    public function cekStatusIntra(Request $request)
    {
        $whatsappInput = str_replace([' ', '-', '+'], '', $request->get('whatsapp'));

        if (empty($whatsappInput)) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor WhatsApp tidak boleh kosong.'
            ], 400);
        }

        $reservasi = Reservasi::select(
                'nama_lengkap',
                'waktu_reservasi',
                'catatan',
                'status',
                DB::raw('GROUP_CONCAT(nomor_meja ORDER BY nomor_meja ASC) as kumpulan_meja')
            )
            ->where('whatsapp', 'LIKE', '%' . $whatsappInput . '%')
            ->groupBy('nama_lengkap', 'waktu_reservasi', 'catatan', 'status')
            ->orderBy('waktu_reservasi', 'desc')
            ->get();

        $dataFormatted = $reservasi->map(function ($item) {
            $dt = Carbon::parse($item->waktu_reservasi);
            return [
                'nama_lengkap' => $item->nama_lengkap,
                'tanggal' => $dt->translatedFormat('d M Y'),
                'jam' => $dt->format('H:i') . ' WIB',
                'nomor_meja' => $item->kumpulan_meja,
                'catatan' => $item->catatan ?? '-',
                'status' => strtoupper($item->status)
            ];
        });

        return response()->json([
            'success' => true,
            'reservasi' => $dataFormatted
        ]);
    }

    /**
     * Menampilkan Halaman Form Cek Status Reservasi (Legacy Old Page)
     */
    public function cekStatusForm()
    {
        return view('reservasi.cek_status');
    }

    /**
     * Memproses Pencarian Status Reservasi Berdasarkan Nomor WhatsApp (Legacy Old Page)
     */
    public function cekStatusProses(Request $request)
    {
        $request->validate([
            'whatsapp' => 'required|string'
        ], [
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi untuk melakukan pelacakan.'
        ]);

        $whatsappInput = str_replace([' ', '-', '+'], '', $request->whatsapp);

        $hasil = Reservasi::select(
                'nama_lengkap',
                'waktu_reservasi',
                'catatan',
                'status',
                DB::raw('GROUP_CONCAT(nomor_meja ORDER BY nomor_meja ASC) as kumpulan_meja'),
                DB::raw('MAX(id) as id')
            )
            ->where('whatsapp', 'LIKE', '%' . $whatsappInput . '%')
            ->groupBy('nama_lengkap', 'waktu_reservasi', 'catatan', 'status')
            ->orderBy('waktu_reservasi', 'desc')
            ->get();

        return view('reservasi.cek_status', compact('hasil'))->with('input_wa', $request->whatsapp);
    }
}