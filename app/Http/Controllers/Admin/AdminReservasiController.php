<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservasi;

class AdminReservasiController extends Controller
{
    public function index()
    {
        // Mengambil semua data reservasi terbaru
        $reservasis = Reservasi::orderBy('waktu_reservasi', 'desc')->get();
        return view('admin.reservasi.index', compact('reservasis'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:PENDING,DISETUJUI,SELESAI,DIBATALKAN'
        ]);

        $reservasi = Reservasi::findOrFail($id);
        $reservasi->status = $request->status;
        $reservasi->save();

        return redirect()->back()->with('success', 'Status reservasi berhasil diperbarui!');
    }
}