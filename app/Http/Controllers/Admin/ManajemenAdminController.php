<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManajemenAdminController extends Controller
{
    // PENGAMANAN: Pastikan hanya SUPER ADMIN yang bisa membuka controller ini
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && strtoupper(auth()->user()->role) !== 'SUPER ADMIN') {
                abort(403, 'AKSES DITOLAK: Halaman ini khusus Super Admin.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        // Menyaring user agar yang tampil hanya SUPER ADMIN dan KASIR
        $admins = User::whereIn('role', ['SUPER ADMIN', 'KASIR'])
                      ->latest()
                      ->paginate(5); 

        return view('admin.manajemen_admin.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:SUPER ADMIN,KASIR',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => strtoupper($request->role), 
        ]);

        return redirect()->back()->with('success', 'Akun admin baru berhasil didaftarkan!');
    }

    public function destroy($id)
    {
        $admin = User::findOrFail($id);
        
        // Mencegah admin menghapus akunnya sendiri yang sedang dipakai login
        if ($admin->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri yang sedang aktif!');
        }

        $admin->delete();

        return redirect()->back()->with('success', 'Akun admin berhasil dihapus!');
    }
}