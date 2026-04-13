<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans';

    protected $fillable = [
        'nama_pemesan',
        'no_meja',
        'catatan',
        'metode_pembayaran',
        'total_harga',
        'status',
    ];

    // Relasi ke detail pesanan
    public function details()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }
}