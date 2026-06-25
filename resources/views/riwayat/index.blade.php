@extends('layouts.app')

@section('title', 'Riwayat Pesanan - RestoKu')

@section('content')
<div class="container py-5">

    {{-- Judul --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm" title="Kembali ke Beranda">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Riwayat Pesanan</h4>
    </div>

    {{-- Alert Sukses --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Cek Jika Pesanan Kosong --}}
    @if(empty($pesanans) || $pesanans->isEmpty())
    <div class="text-center py-5">
        <div class="mb-3">
            <i class="bi bi-clock-history text-muted" style="font-size: 4rem;"></i>
        </div>
        <p class="text-muted mb-3">Belum ada riwayat pesanan.</p>
        <a href="{{ url('/menu') }}" class="btn btn-danger px-4">Pesan Sekarang</a>
    </div>
    @else

    <div class="row g-4">
        @foreach($pesanans as $pesanan)
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-4">
                    
                    {{-- Header Card --}}
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                        <div>
                            <h6 class="fw-bold mb-1">
                                <i class="bi bi-receipt me-1 text-success"></i> 
                                {{-- Disamakan dengan format Admin (Contoh: ORD-005) --}}
                                Pesanan ORD-{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}
                            </h6>
                            <small class="text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $pesanan->created_at ? $pesanan->created_at->format('d M Y, H:i') : '--/--/----' }} WIB
                            </small>
                        </div>

                        @php
                            $status_clean = strtolower($pesanan->status ?? 'pending');
                            $badge_color = [
                                'pending'    => 'warning text-dark',
                                'diproses'   => 'info text-white',
                                'selesai'    => 'success text-white',
                                'dibatalkan' => 'danger text-white'
                            ][$status_clean] ?? 'secondary text-white';
                        @endphp
                        <span class="badge bg-{{ $badge_color }} px-3 py-2 fs-6 shadow-sm rounded-pill">
                            {{ ucfirst($status_clean) }}
                        </span>
                    </div>

                    <hr class="my-3 opacity-50">

                    {{-- Info Pemesan --}}
                    <div class="row g-3 mb-3">
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Nama Pelanggan</small>
                            <span class="fw-semibold text-dark">{{ $pesanan->nama_pelanggan ?? $pesanan->nama_pemesan ?? '-' }}</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">No. Meja</small>
                            <span class="fw-semibold text-dark">
                                {{ isset($pesanan->nomor_meja) ? 'Meja ' . $pesanan->nomor_meja : '-' }}
                            </span>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Metode Bayar</small>
                            <span class="fw-semibold text-dark text-capitalize">
                                {{ str_replace('_', ' ', $pesanan->metode_pembayaran ?? 'Cash') }}
                            </span>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Total Bayar</small>
                            <span class="fw-bold text-danger">
                                Rp {{ number_format((float)($pesanan->total_harga ?? 0), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- Catatan Pelanggan --}}
                    @if(!empty($pesanan->catatan) && $pesanan->catatan !== '-')
                    <div class="alert alert-light border-start border-warning border-3 ps-3 mb-3 py-2 bg-light">
                        <small class="text-warning fw-bold d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">CATATAN PELANGGAN:</small>
                        <small class="text-dark italic">"{{ $pesanan->catatan }}"</small>
                    </div>
                    @endif

                    {{-- Detail Item Pesanan --}}
                    <div class="bg-light rounded-3 p-3 border border-light-subtle">
                        <small class="text-muted fw-bold d-block mb-3" style="font-size: 0.7rem; letter-spacing: 0.5px;">DETAIL PESANAN:</small>
                        
                        {{-- Ini yang memanggil relasi 'details' dari model --}}
                        @if(isset($pesanan->details) && $pesanan->details->isNotEmpty())
                            @foreach($pesanan->details as $index => $detail)
                                <div class="d-flex justify-content-between align-items-center {{ !$loop->last ? 'mb-2 pb-2 border-bottom border-white' : '' }}">
                                    <div>
                                        <span class="fw-semibold small text-dark d-block">{{ $detail->nama_menu ?? 'Menu Tidak Diketahui' }}</span>
                                        <small class="text-muted small">
                                            {{ $detail->qty ?? 0 }} x Rp {{ number_format((float)($detail->harga ?? 0), 0, ',', '.') }}
                                        </small>
                                    </div>
                                    <span class="fw-bold text-dark small">
                                        Rp {{ number_format((float)($detail->subtotal ?? 0), 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-2">
                                <small class="text-muted italic">Gagal memuat detail item belanjaan.</small>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    {{-- Pagination jika ada --}}
    @if(method_exists($pesanans, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $pesanans->links() }}
        </div>
    @endif
    
    @endif
</div>
@endsection