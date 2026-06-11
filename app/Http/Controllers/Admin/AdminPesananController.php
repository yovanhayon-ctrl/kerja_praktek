<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\PesananExport;          // Import class Export
use Maatwebsite\Excel\Facades\Excel;    // Import Facade Excel

class AdminPesananController extends Controller
{
    public function index(Request $request)
    {
        // Filter by status dan search
        $status = $request->query('status', null);
        $search = $request->query('search', null);
        
        $query = Pesanan::query();
        
        // Filter by search (ID pesanan)
        if ($search) {
            // Mengambil angka saja dari format ORD-XXX atau langsung berdasarkan ID
            $searchId = preg_replace('/[^0-9]/', '', $search);
            if ($searchId) {
                $query->where('id', $searchId);
            }
        }
        
        // Filter by status
        if ($status && $status !== 'semua') {
            $query->where('status', strtoupper($status));
        }
        
        $pesanans = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // --- STATISTIK ---
        $total_pesanan = Pesanan::count();
        
        // Hanya menghitung total_harga jika status pesanan adalah 'SELESAI'
        $total_pendapatan = Pesanan::where('status', 'SELESAI')->sum('total_harga');
        
        // Menghitung pesanan aktif (PENDING + DIPROSES) agar sinkron dengan lonceng
        $pesanan_aktif = Pesanan::whereIn('status', ['PENDING', 'DIPROSES'])->count();
        
        // Rata-rata waktu tunggu (dalam menit)
        $pesanan_selesai = Pesanan::where('status', 'SELESAI')->get();
        $waktu_tunggu = $pesanan_selesai->count() > 0
            ? round($pesanan_selesai->map(function($p) {
                return $p->updated_at->diffInMinutes($p->created_at);
            })->avg())
            : 0;
        
        $total_orders_count = Pesanan::count();
        
        return view('admin.pesanan.index', compact(
            'pesanans', 'total_orders_count', 'status', 'search',
            'total_pesanan', 'total_pendapatan', 'pesanan_aktif', 'waktu_tunggu'
        ));
    }

    public function show($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required']);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status = $request->status;
        $pesanan->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    // --- EXPORT EXCEL ---
    public function export(Request $request)
    {
        $status = $request->query('status', null);
        $search = $request->query('search', null);
        
        // Nama file unduhan otomatis mengikuti tanggal & jam saat diklik
        $namaFile = 'data_pesanan_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new PesananExport($status, $search), $namaFile);
    }
}