@extends('admin.layouts.admin')

@section('title', 'Admin Detail Pesanan')

@section('content')
<div class="container-fluid py-3 py-sm-4 px-2 px-sm-3">
    {{-- Header Layout dengan Tombol Kembali --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-white bg-white border shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; transition: all 0.2s;">
            <i class="bi bi-arrow-left fs-5 text-dark"></i>
        </a>
        <div>
            <h4 class="fw-bold text-dark mb-0" style="font-size: 1.25rem;">Rincian Pesanan ORD-{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}</h4>
            <p class="text-muted small mb-0">Dibuat pada {{ $pesanan->created_at->format('d M Y, H:i') }} WIB</p>
        </div>
    </div>

    <div class="row g-4">
        {{-- Kolom Kiri: Daftar Menu --}}
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-0">
                    <div class="p-3 p-sm-4 border-bottom">
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Daftar Menu Yang Dipesan</h6>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    <th class="ps-4 text-muted fw-bold border-0">NAMA MENU</th>
                                    <th class="text-center text-muted fw-bold border-0">HARGA</th>
                                    <th class="text-center text-muted fw-bold border-0" style="width: 25%;">CATATAN</th>
                                    <th class="text-center text-muted fw-bold border-0">QTY</th>
                                    <th class="text-end pe-4 text-muted fw-bold border-0">SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 0.75rem;">
                                @php 
                                    // Decode data rincian menu sesuai bawaan database Anda
                                    $items = json_decode($pesanan->detail_menu, true); 
                                    
                                    // Hitung jumlah item untuk rowspan kolom catatan
                                    $jumlah_item = !empty($items) && is_array($items) ? count($items) : 1;
                                @endphp

                                @if(!empty($items))
                                    @foreach($items as $item)
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark border-bottom-0">
                                            {{ $item['nama'] ?? $item['nama_menu'] ?? $item['name'] ?? 'Menu Tidak Diketahui' }}
                                        </td>
                                        <td class="text-center fw-medium text-secondary border-bottom-0">
                                            Rp {{ number_format($item['harga'] ?? $item['price'] ?? 0, 0, ',', '.') }}
                                        </td>
                                        
                                        {{-- Rowspan tanpa garis border vertikal agar mulus --}}
                                        @if($loop->first)
                                        <td rowspan="{{ $jumlah_item }}" class="text-center text-secondary align-middle" style="background-color: #fcfcfc; border-bottom: none;">
                                            @if(!empty($pesanan->catatan) && $pesanan->catatan !== '-')
                                                <span class="text-dark small fst-italic">"{{ $pesanan->catatan }}"</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        @endif

                                        <td class="text-center fw-bold text-dark border-bottom-0">
                                            {{ $item['qty'] ?? $item['jumlah'] ?? $item['quantity'] ?? 0 }}
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-dark border-bottom-0">
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
                                        <td colspan="5" class="text-center py-4 text-muted fw-semibold border-bottom-0">Rincian menu tidak ditemukan dalam database.</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot class="bg-light-subtle border-top">
                                <tr style="font-size: 0.8rem;">
                                    <td colspan="4" class="ps-4 fw-bold py-3 text-dark border-0">Total Pembayaran</td>
                                    <td class="text-end pe-4 fw-bold text-success py-3 border-0" style="font-size: 1.05rem;">
                                        Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Informasi & Aksi Status --}}
        <div class="col-12 col-lg-4">
            {{-- Card Ringkasan Informasi Pelanggan --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-dark" style="font-size: 0.9rem;">Informasi Pelanggan</h6>
                    <hr class="text-muted my-2" style="opacity: 0.15;">
                    
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-muted small">Nama Pelanggan</span>
                        <span class="fw-bold text-dark small">{{ $pesanan->nama_pelanggan ?? 'Tanpa Nama' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-muted small">Lokasi Meja</span>
                        <span class="badge bg-light text-dark border px-2 py-1 fw-bold">Meja {{ $pesanan->nomor_meja ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-muted small">Status Saat Ini</span>
                        @php
                            $st = strtoupper($pesanan->status);
                            $statusMap = ['PENDING'=>'PENDING','DIPROSES'=>'PROCESSING','SELESAI'=>'COMPLETED','DIBATALKAN'=>'CANCELLED','BATAL'=>'CANCELLED'];
                            $displayStatus = $statusMap[$st] ?? $st;
                            $badgeColor = [
                                'PENDING' => 'warning',
                                'PROCESSING' => 'info',
                                'COMPLETED' => 'success',
                                'CANCELLED' => 'danger'
                            ][$displayStatus] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $badgeColor }}-subtle text-{{ $badgeColor }} px-2 py-1 fw-bold" style="font-size: 0.65rem;">
                            {{ $displayStatus }}
                        </span>
                    </div>

                    {{-- INTEGRASI INTEGRAL: Tombol Lempar Data ke Kasir --}}
                    @if($st !== 'SELESAI' && $st !== 'DIBATALKAN' && $st !== 'BATAL')
                        <div class="mt-3 pt-3 border-top">
                            <a href="{{ route('admin.kasir.prosesPesanan', $pesanan->id) }}" class="btn btn-success w-100 fw-bold py-2 d-flex align-items-center justify-content-center gap-2 shadow-sm" style="border-radius: 8px; font-size: 0.85rem; background-color: #2d6a4f; border-color: #2d6a4f;">
                                <i class="bi bi-cash-coin fs-6"></i> Proses & Bayar di Kasir
                            </a>
                            <small class="text-muted d-block text-center mt-2" style="font-size: 0.7rem; line-height: 1.3;">
                                *Klik untuk memuat item & nama pelanggan meja ini langsung ke nota kasir aktif.
                            </small>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card Form Update Status --}}
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-dark" style="font-size: 0.9rem;">Perbarui Status Pesanan</h6>
                    <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST">
                        @csrf @method('PATCH')
                        
                        <div class="mb-3">
                            <select name="status" class="form-select border px-3 py-2 text-dark" style="border-radius: 8px; font-size: 0.8rem; background-color: #f8fafc; border-color: #dee2e6 !important;">
                                <option value="PENDING" {{ $pesanan->status == 'PENDING' ? 'selected' : '' }}>Pending</option>
                                <option value="DIPROSES" {{ $pesanan->status == 'DIPROSES' ? 'selected' : '' }}>Processing (Diproses)</option>
                                <option value="SELESAI" {{ $pesanan->status == 'SELESAI' ? 'selected' : '' }}>Completed (Selesai)</option>
                                <option value="DIBATALKAN" {{ $pesanan->status == 'DIBATALKAN' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-dark w-100 fw-bold py-2 shadow-sm" style="border-radius: 8px; font-size: 0.8rem; transition: all 0.2s;">
                            <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection