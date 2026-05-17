<?php

namespace App\Exports;

use App\Models\Pesanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PesananExport implements FromCollection, WithHeadings, WithMapping
{
    protected $status;
    protected $search;

    // Konstruktor untuk menangkap filter status dan pencarian dari halaman web
    public function __construct($status = null, $search = null)
    {
        $this->status = $status;
        $this->search = $search;
    }

    /**
    * Mengambil data pesanan dari database sesuai filter yang aktif
    */
    public function collection()
    {
        // Load juga relasi details agar tidak berat (N+1 Problem)
        $query = Pesanan::with('details')->latest();

        // Filter berdasarkan Status Tab jika ada
        if ($this->status && $this->status !== 'semua') {
            // Menyesuaikan string status di database Anda (PENDING, DIPROSES, dll)
            $statusDb = [
                'pending' => 'PENDING',
                'diproses' => 'DIPROSES',
                'selesai' => 'SELESAI',
                'dibatalkan' => 'DIBATALKAN'
            ][$this->status] ?? $this->status;

            $query->where('status', $statusDb);
        }

        // Filter berdasarkan kolom Pencarian jika ada
        if ($this->search) {
            $query->where(function($q) {
                $q->where('id', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('nama_pelanggan', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('nomor_meja', 'LIKE', '%' . $this->search . '%');
            });
        }

        return $query->get();
    }

    /**
    * Membuat Baris Judul / Header di Excel
    */
    public function headings(): array
    {
        return [
            'ID Pesanan',
            'Pelanggan',
            'Meja',
            'Menu Pesanan',
            'Waktu Pesanan',
            'Total Harga',
            'Status'
        ];
    }

    /**
    * Memetakan data dari database ke kolom Excel masing-masing
    */
    public function map($pesanan): array
    {
        // Menggabungkan menu-menu pesanan menjadi satu baris teks dipisahkan koma
        $menus = $pesanan->details->pluck('nama_menu')->toArray();
        $textMenu = count($menus) > 0 ? implode(', ', $menus) : '-';

        return [
            'ORD-' . str_pad($pesanan->id, 3, '0', STR_PAD_LEFT),
            $pesanan->nama_pelanggan ?? 'Tanpa Nama',
            'Meja ' . ($pesanan->nomor_meja ?? '-'),
            $textMenu,
            $pesanan->created_at->format('H:i') . ' WIB',
            $pesanan->total_harga,
            strtoupper($pesanan->status)
        ];
    }
}