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

    {{-- Alert sukses dari checkout --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Jika tidak ada pesanan --}}
    @if($pesanans->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-clock-history text-muted" style="font-size: 4rem;"></i>
        <p class="text-muted mt-3 mb-3">Belum ada riwayat pesanan.</p>
        <a href="{{ url('/menu') }}" class="btn btn-danger">
            <i class="bi bi-bag"></i> Pesan Sekarang
        </a>
    </div>

    @else

    {{-- List Pesanan --}}
    <div class="row g-4">
        @foreach($pesanans as $pesanan)
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    {{-- Header pesanan --}}
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                        <div>
                            <h6 class="fw-bold mb-1">
                                <i class="bi bi-receipt text-danger me-1"></i>
                                Pesanan #{{ $pesanan->id }}
                            </h6>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                {{ $pesanan->created_at->format('d M Y, H:i') }} WIB
                            </small>
                        </div>

                        {{-- Badge status --}}
                        @php
                            $badge = [
                                'pending'     => 'warning',
                                'diproses'    => 'info',
                                'selesai'     => 'success',
                                'dibatalkan'  => 'danger',
                            ];
                            $icon = [
                                'pending'     => 'bi-hourglass-split',
                                'diproses'    => 'bi-fire',
                                'selesai'     => 'bi-check-circle-fill',
                                'dibatalkan'  => 'bi-x-circle-fill',
                            ];
                        @endphp
                        <span class="badge bg-{{ $badge[$pesanan->status] }} px-3 py-2 fs-6">
                            <i class="bi {{ $icon[$pesanan->status] }} me-1"></i>
                            {{ ucfirst($pesanan->status) }}
                        </span>
                    </div>

                    <hr class="my-2">

                    {{-- Info pemesan --}}
                    <div class="row g-2 mb-3">
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block">Nama</small>
                            <span class="fw-semibold">{{ $pesanan->nama_pemesan }}</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block">No. Meja</small>
                            <span class="fw-semibold">Meja {{ $pesanan->no_meja }}</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block">Pembayaran</small>
                            <span class="fw-semibold text-capitalize">{{ $pesanan->metode_pembayaran }}</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block">Total</small>
                            <span class="fw-bold text-danger">
                                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- Catatan --}}
                    @if($pesanan->catatan)
                    <div class="mb-3">
                        <small class="text-muted d-block">Catatan</small>
                        <span class="small fst-italic">"{{ $pesanan->catatan }}"</span>
                    </div>
                    @endif

                    {{-- Detail item --}}
                    <div class="bg-light rounded p-3">
                        <small class="text-muted fw-semibold d-block mb-2">Detail Item:</small>
                        @foreach($pesanan->details as $detail)
                        <div class="d-flex justify-content-between align-items-center
                            {{ !$loop->last ? 'border-bottom pb-2 mb-2' : '' }}">
                            <div>
                                <span class="fw-semibold small">{{ $detail->nama_menu }}</span>
                                <small class="text-muted d-block">
                                    {{ $detail->qty }} x Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                </small>
                            </div>
                            <span class="fw-bold text-danger small">
                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($pesanans->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $pesanans->links('pagination::bootstrap-5') }}
    </div>
    @endif

    @endif

</div>
@endsection