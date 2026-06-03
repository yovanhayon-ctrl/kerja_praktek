<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservasi;
use Illuminate\Support\Facades\DB;

class AdminReservasiController extends Controller
{
    public function index()
    {
        // Mengambil data reservasi yang dikelompokkan agar 1 transaksi rombongan hanya memakan 1 baris
        $reservasis = Reservasi::select(
                'nama_lengkap',
                'whatsapp',
                'waktu_reservasi',
                'catatan',
                'status',
                // GROUP_CONCAT akan menggabungkan nomor meja, misal: "1,2,3"
                DB::raw('GROUP_CONCAT(nomor_meja ORDER BY nomor_meja ASC) as kumpulan_meja'),
                // Ambil ID terkecil/terbesar sebagai perwakilan grup untuk aksi update status nanti
                DB::raw('MAX(id) as id') 
            )
            ->groupBy('nama_lengkap', 'whatsapp', 'waktu_reservasi', 'catatan', 'status')
            ->orderBy('waktu_reservasi', 'desc')
            ->get();

        return view('admin.reservasi.index', compact('reservasis'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:PENDING,DISETUJUI,SELESAI,DIBATALKAN'
        ]);

        // Temukan data perwakilan yang diklik oleh admin
        $reservasiUtama = Reservasi::findOrFail($id);

        // Kunci Perbaikan: Update status SEMUA meja yang dipesan oleh orang yang sama di waktu yang sama sekaligus!
        Reservasi::where('nama_lengkap', $reservasiUtama->nama_lengkap)
            ->where('whatsapp', $reservasiUtama->whatsapp)
            ->where('waktu_reservasi', $reservasiUtama->waktu_reservasi)
            ->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status rombongan reservasi berhasil diperbarui!');
    }
}