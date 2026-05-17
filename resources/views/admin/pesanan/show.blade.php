@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-white shadow-sm rounded-circle p-2">
            <i class="bi bi-arrow-left fs-5 text-dark"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-0" style="font-size: 1.25rem;">Rincian Pesanan ORD-{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}</h4>
            <small class="text-muted mb-0" style="font-size: 0.75rem;">Dibuat pada {{ $pesanan->created_at->format('d M Y, H:i') }} WIB</small>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body p-0">
                    <div class="p-4 border-bottom">
                        <h6 class="fw-bold mb-0 text-dark">Daftar Menu</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light text-muted">
                                <tr style="font-size: 0.65rem;">
                                    <th class="ps-4">NAMA MENU</th>
                                    <th class="text-center">HARGA</th>
                                    <th class="text-center">CATATAN</th>
                                    <th class="text-center">QTY</th>
                                    <th class="text-end pe-4">SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 0.75rem;">
                                @php 
                                    // Decode data rincian menu
                                    $items = json_decode($pesanan->detail_menu, true); 
                                @endphp

                                @if(!empty($items))
                                    @foreach($items as $item)
                                    <tr>
                                        <td class="ps-4 fw-medium text-dark">
                                            {{-- Cek semua kemungkinan key JSON: nama, nama_menu, atau name --}}
                                            {{ $item['nama'] ?? $item['nama_menu'] ?? $item['name'] ?? 'Menu Tidak Diketahui' }}
                                        </td>
                                        <td class="text-center">
                                            Rp {{ number_format($item['harga'] ?? $item['price'] ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center text-secondary">
                                            {{-- MODIFIKASI: Menampilkan catatan sebagai teks biasa yang serasi dengan nama dan harga menu --}}
                                            @if(!empty($pesanan->catatan) && $pesanan->catatan !== '-')
                                                {{ $pesanan->catatan }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center fw-bold">
                                            {{ $item['qty'] ?? $item['jumlah'] ?? $item['quantity'] ?? 0 }}
                                        </td>
                                        <td class="text-end pe-4 fw-bold">
                                            @php
                                                $harga = $item['harga'] ?? $item['price'] ?? 0;
                                                $qty = $item['qty'] ?? $item['jumlah'] ?? $item['quantity'] ?? 0;
                                            @endphp
                                            Rp {{ number_format($harga * $qty, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Rincian menu tidak ditemukan dalam database.</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot class="bg-light">
                                <tr style="font-size: 0.75rem;">
                                    <td colspan="4" class="ps-4 fw-bold py-3 text-dark">Total Pembayaran</td>
                                    <td class="text-end pe-4 fw-bold text-success py-3" style="font-size: 1rem;">
                                        Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Form Update Status --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-dark" style="font-size: 0.9rem;">Update Status</h6>
                    <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <select name="status" class="form-select border-0 bg-light mb-3">
                            <option value="PENDING" {{ $pesanan->status == 'PENDING' ? 'selected' : '' }}>Pending</option>
                            <option value="DIPROSES" {{ $pesanan->status == 'DIPROSES' ? 'selected' : '' }}>Processing</option>
                            <option value="SELESAI" {{ $pesanan->status == 'SELESAI' ? 'selected' : '' }}>Completed</option>
                            <option value="DIBATALKAN" {{ $pesanan->status == 'DIBATALKAN' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button type="submit" class="btn btn-success w-100 fw-bold">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection