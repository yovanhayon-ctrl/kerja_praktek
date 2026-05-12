@extends('layouts.app')

@section('title', 'Riwayat Pesanan - RestoKu')

@section('content')
<div class="container py-5">

    {{-- Judul --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Riwayat Pesanan</h4>
    </div>

    {{-- Alert sukses --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($pesanans->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-clock-history text-muted" style="font-size: 4rem;"></i>
        <p class="text-muted mt-3 mb-3">Belum ada riwayat pesanan.</p>
        <a href="{{ url('/menu') }}" class="btn btn-danger">Pesan Sekarang</a>
    </div>
    @else

    <div class="row g-4">
        @foreach($pesanans as $pesanan)
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                        <div>
                            <h6 class="fw-bold mb-1">
                                <i class="bi bi-receipt text-danger me-1"></i> Pesanan ORD-{{ str_pad($total_orders_count - $loop->index + 1, 3, '0', STR_PAD_LEFT) }}
                            </h6>
                            <small class="text-muted">
                                {{ $pesanan->created_at->format('d M Y, H:i') }} WIB
                            </small>
                        </div>

                        @php
                            $status_clean = strtolower($pesanan->status);
                            $badge_color = [
                                'pending' => 'warning',
                                'diproses' => 'info',
                                'selesai' => 'success',
                                'dibatalkan' => 'danger'
                            ][$status_clean] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $badge_color }} px-3 py-2 fs-6">
                            {{ ucfirst($status_clean) }}
                        </span>
                    </div>

                    <hr class="my-2">

                    {{-- INFO PEMESAN (FIXED) --}}
                    <div class="row g-2 mb-3 mt-1">
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Nama Pelanggan</small>
                            <span class="fw-semibold">{{ $pesanan->nama_pelanggan ?? $pesanan->nama_pemesan ?? '-' }}</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">No. Meja</small>
                            <span class="fw-semibold">
                                Meja {{ $pesanan->nomor_meja ?? '-' }}
                            </span>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Metode Bayar</small>
                            <span class="fw-semibold text-capitalize">
                                {{ str_replace('_', ' ', $pesanan->metode_pembayaran ?? 'Cash') }}
                            </span>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Total Bayar</small>
                            <span class="fw-bold text-danger">
                                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    @if($pesanan->catatan)
                    <div class="alert alert-light border-start border-warning ps-3 mb-3">
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.75rem;">CATATAN PELANGGAN:</small>
                        <small class="text-dark">{{ $pesanan->catatan }}</small>
                    </div>
                    @endif

                    {{-- Detail Item --}}
                    <div class="bg-light rounded p-3">
                        <small class="text-muted fw-bold d-block mb-2" style="font-size: 0.7rem;">DETAIL PESANAN:</small>
                        @foreach($pesanan->details as $detail)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="fw-semibold small">{{ $detail->nama_menu }}</span>
                                <small class="text-muted d-block small">{{ $detail->qty }} x Rp {{ number_format($detail->harga, 0, ',', '.') }}</small>
                            </div>
                            <span class="fw-bold text-dark small">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection