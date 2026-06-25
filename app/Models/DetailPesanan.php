<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pesanans';

    protected $fillable = [
        'pesanan_id',
        'menu_id',
        'nama_menu',
        'harga',
        'qty', 
        'subtotal',
    ];

    // Relasi balik ke nota induk
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    // TAMBAHAN: Relasi ke data Master Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}