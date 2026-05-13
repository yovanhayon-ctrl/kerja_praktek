<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminPesananController extends Controller
{
    public function index(Request $request)
    {
        // Filter by status
        $status = $request->query('status', null);
        $search = $request->query('search', null);
        
        $query = Pesanan::query();
        
        // Filter by search (ID pesanan)
        if ($search) {
            // Extract number from ORD-XXX format or just search by ID
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
        
        // Statistics
        $total_pesanan = Pesanan::count();
        $total_pendapatan = Pesanan::sum('total_harga');
        $pesanan_aktif = Pesanan::whereIn('status', ['PENDING', 'DIPROSES'])->count();
        
        // Average waiting time (dalam menit)
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
}