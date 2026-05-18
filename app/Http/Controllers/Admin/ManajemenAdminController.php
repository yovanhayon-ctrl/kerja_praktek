<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManajemenAdminController extends Controller
{
    public function index()
    {
        // Menyaring user agar yang tampil hanya yang memiliki role pengelolaan/admin saja
        $admins = User::whereIn('role', ['SUPER ADMIN', 'SUPERADMIN', 'MANAGER', 'STAFF'])
                      ->latest()
                      ->paginate(5); 

        // Mengarah ke subfolder baru resources/views/admin/manajemen_admin/index.blade.php
        return view('admin.manajemen_admin.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => strtoupper($request->role), // Disimpan dalam format UPPERCASE
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