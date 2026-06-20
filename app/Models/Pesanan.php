<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pesanan',
        'nama_pelanggan',
        'nomor_meja',
        'catatan',
        'detail_menu',
        'total_harga',
        'status',
    ];

    /**
     * Hubungkan tabel pesanan ke tabel detail_pesanans
     * (Satu pesanan bisa memiliki banyak detail item/menu)
     */
    public function details()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }
}