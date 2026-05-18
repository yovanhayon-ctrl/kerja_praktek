<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use Illuminate\Http\Request;

class PesanController extends Controller
{
    // 1. FRONTEND: Menyimpan pesan yang dikirim oleh pengunjung/user
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'pesan' => 'required|string',
        ]);

        Pesan::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'pesan' => $request->pesan,
            'status' => 'BELUM_DIBACA',
        ]);

        return redirect()->back()->with('success', 'Pesan Anda berhasil dikirim!');
    }

    // 2. BACKEND ADMIN: Menampilkan daftar pesan di halaman admin
    public function index()
    {
        // Mengambil pesan terbaru dan membaginya per 10 data (pagination)
        $pesans = Pesan::latest()->paginate(10);
        return view('admin.pesan.index', compact('pesans'));
    }

    // 3. BACKEND ADMIN: Mengubah status pesan menjadi DIBACA / Selesai ditangani
    public function updateStatus($id)
    {
        $pesan = Pesan::findOrFail($id);
        $pesan->update([
            'status' => 'DIBACA'
        ]);

        return redirect()->back()->with('success', 'Pesan telah ditandai sebagai dibaca.');
    }

    // 4. BACKEND ADMIN: Menghapus pesan jika diperlukan
    public function destroy($id)
    {
        $pesan = Pesan::findOrFail($id);
        $pesan->delete();

        return redirect()->back()->with('success', 'Pesan berhasil dihapus.');
    }
}