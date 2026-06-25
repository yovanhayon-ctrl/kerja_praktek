<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans'; // Menghubungkan ke tabel pesanans di database

    protected $fillable = [
        'id_pesanan',
        'nama_pelanggan',
        'nomor_meja',
        'catatan',
        'detail_menu',
        'total_harga',
        'status',
    ];

    // PERBAIKAN 1: Nama fungsi diubah menjadi details()
    public function details()
    {
        // PERBAIKAN 2: Gunakan huruf kapital D untuk DetailPesanan::class
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }
}