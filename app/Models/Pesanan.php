<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans';

    // File: app/Models/Pesanan.php
    protected $fillable = [
        'id_pesanan',
        'nama_pelanggan',
        'nomor_meja',
        'catatan',
        'detail_menu',
        'total_harga',
        'status',
    ];

    public function details()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }
}