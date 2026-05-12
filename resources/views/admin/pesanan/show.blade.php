@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-white shadow-sm rounded-circle p-2">
            <i class="bi bi-arrow-left fs-5 text-dark"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-0">Rincian Pesanan #{{ $pesanan->id_pesanan ?? $pesanan->id }}</h4>
            <p class="text-muted small mb-0">Dibuat pada {{ $pesanan->created_at->format('d M Y, H:i') }} WIB</p>
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
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="ps-4">NAMA MENU</th>
                                    <th class="text-center">HARGA</th>
                                    <th class="text-center">QTY</th>
                                    <th class="text-end pe-4">SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    // Decode data rincian menu
                                    $items = json_decode($pesanan->detail_menu, true); 
                                @endphp

                                @if(!empty($items))
                                    @foreach($items as $item)
                                    <tr>
                                        <td class="ps-4">
                                            {{-- Cek semua kemungkinan key JSON: nama, nama_menu, atau name --}}
                                            {{ $item['nama'] ?? $item['nama_menu'] ?? $item['name'] ?? 'Menu Tidak Diketahui' }}
                                        </td>
                                        <td class="text-center">
                                            Rp {{ number_format($item['harga'] ?? $item['price'] ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            {{ $item['qty'] ?? $item['jumlah'] ?? $item['quantity'] ?? 0 }}
                                        </td>
                                        <td class="text-end pe-4">
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
                                        <td colspan="4" class="text-center py-4 text-muted">Rincian menu tidak ditemukan dalam database.</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="ps-4 fw-bold py-3 text-dark">Total Pembayaran</td>
                                    <td class="text-end pe-4 fw-bold text-success fs-5 py-3">
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
                    <h6 class="fw-bold mb-3 text-dark">Update Status</h6>
                    <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <select name="status" class="form-select border-0 bg-light mb-3">
                            <option value="PENDING" {{ $pesanan->status == 'PENDING' ? 'selected' : '' }}>Pending</option>
                            <option value="DIPROSES" {{ $pesanan->status == 'DIPROSES' ? 'selected' : '' }}>Diproses</option>
                            <option value="SELESAI" {{ $pesanan->status == 'SELESAI' ? 'selected' : '' }}>Selesai</option>
                            <option value="DIBATALKAN" {{ $pesanan->status == 'DIBATALKAN' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        <button type="submit" class="btn btn-success w-100 fw-bold">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection