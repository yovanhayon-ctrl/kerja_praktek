@extends('admin.layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview performa restoran hari ini')

@section('content')

{{-- ── STAT CARDS ── --}}
<div class="row g-4 mb-4">
    {{-- Total Pesanan --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm" style="border-radius: 16px; border-left: 4px solid #16a34a !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background:#dcfce7;">
                        <i class="bi bi-receipt" style="color:#16a34a; font-size: 1.25rem;"></i>
                    </div>
                    <span class="badge rounded-pill" style="background:#dcfce7; color:#16a34a; font-weight: 600;">
                        +{{ $persen_pesanan }}%
                    </span>
                </div>
                <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Pesanan</div>
                <div class="h3 fw-bold mb-0" style="color: #0f172a;">{{ number_format($total_pesanan, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Total Pendapatan --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm" style="border-radius: 16px; border-left: 4px solid #1d4ed8 !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background:#dbeafe;">
                        <i class="bi bi-wallet2" style="color:#1d4ed8; font-size: 1.25rem;"></i>
                    </div>
                    <span class="badge rounded-pill" style="background:#dbeafe; color:#1d4ed8; font-weight: 600;">
                        Hari Ini
                    </span>
                </div>
                <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Pendapatan</div>
                <div class="h4 fw-bold mb-0" style="color: #0f172a;">
                    Rp {{ number_format($total_pendapatan, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Menu Terlaris --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm" style="border-radius: 16px; border-left: 4px solid #ca8a04 !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background:#fef9c3;">
                        <i class="bi bi-star-fill" style="color:#ca8a04; font-size: 1.25rem;"></i>
                    </div>
                    <span class="text-muted fw-bold" style="font-size: 0.8rem;">Favorit</span>
                </div>
                <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Menu Terlaris</div>
                <div class="h4 fw-bold mb-0" style="color: #0f172a;">{{ $menu_terlaris }}</div>
            </div>
        </div>
    </div>

    {{-- Jumlah Pelanggan --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm" style="border-radius: 16px; border-left: 4px solid #7c3aed !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background:#f3e8ff;">
                        <i class="bi bi-people-fill" style="color:#7c3aed; font-size: 1.25rem;"></i>
                    </div>
                </div>
                <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Pelanggan</div>
                <div class="h3 fw-bold mb-0" style="color: #0f172a;">{{ number_format($jumlah_pelanggan, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Recent Orders Table --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0" style="color: #475569;">Recent Orders</h6>
                <a href="{{ route('admin.pesanan.index') }}" class="text-decoration-none" style="color: #10b981; font-weight: 500;">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light border-0">
                            <tr style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                                <th class="ps-4 py-3 border-0">ID</th>
                                <th class="py-3 border-0">Nama</th>
                                <th class="py-3 border-0">Meja</th>
                                <th class="py-3 border-0">Total</th>
                                <th class="py-3 border-0">Status</th>
                                <th class="pe-4 py-3 border-0 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody style="color: #334155; font-size: 0.9rem;">
                            @forelse($pesanan_terbaru as $p)
                            <tr class="border-bottom">
                                <td class="ps-4 py-3 fw-bold">#ORD-{{ $p->id }}</td>
                                <td class="py-3">{{ $p->nama_pemesan }}</td>
                                <td class="py-3 text-muted">Table {{ sprintf('%02d', $p->no_meja) }}</td>
                                <td class="py-3">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                                <td class="py-3">
                                    @php
                                        $badgeStyle = [
                                            'pending'    => ['bg' => '#fef9c3', 'text' => '#ca8a04', 'label' => 'Tunggu'],
                                            'diproses'   => ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => 'Proses'],
                                            'selesai'    => ['bg' => '#dcfce7', 'text' => '#16a34a', 'label' => 'Selesai'],
                                            'dibatalkan' => ['bg' => '#fee2e2', 'text' => '#ef4444', 'label' => 'Batal'],
                                        ][$p->status] ?? ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => 'Unknown'];
                                    @endphp
                                    <span class="badge px-3 py-2" style="background: {{ $badgeStyle['bg'] }}; color: {{ $badgeStyle['text'] }}; border-radius: 12px; font-weight: 500; font-size: 0.75rem;">
                                        {{ $badgeStyle['label'] }}
                                    </span>
                                </td>
                                <td class="pe-4 py-3 text-center">
                                    <a href="{{ route('admin.pesanan.detail', $p->id) }}" class="text-muted" style="font-size: 1.1rem;"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-4">Belum ada pesanan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Pesanan & Pendapatan Hari Ini --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0" style="color: #475569;">Status Pesanan</h6>
                <div class="d-flex gap-1">
                    <span class="rounded-circle" style="width: 8px; height: 8px; background: #10b981;"></span>
                    <span class="rounded-circle" style="width: 8px; height: 8px; background: #d1d5db;"></span>
                </div>
            </div>
            <div class="card-body px-4 pt-0">
                {{-- Progress Bars --}}
                <div class="mb-4">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.85rem; color: #64748b;">
                            <span>Pending</span>
                            <span class="fw-bold text-dark">{{ $count_pending }}</span>
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 10px; background-color: #f1f5f9;">
                            <div class="progress-bar" style="width: {{ $total_pesanan > 0 ? ($count_pending/$total_pesanan)*100 : 0 }}%; background-color: #ca8a04;"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.85rem; color: #64748b;">
                            <span>Preparing</span>
                            <span class="fw-bold text-dark">{{ $count_diproses }}</span>
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 10px; background-color: #f1f5f9;">
                            <div class="progress-bar" style="width: {{ $total_pesanan > 0 ? ($count_diproses/$total_pesanan)*100 : 0 }}%; background-color: #64748b;"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.85rem; color: #64748b;">
                            <span>Delivered</span>
                            <span class="fw-bold text-dark">{{ $count_selesai }}</span>
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 10px; background-color: #f1f5f9;">
                            <div class="progress-bar" style="width: {{ $total_pesanan > 0 ? ($count_selesai/$total_pesanan)*100 : 0 }}%; background-color: #10b981;"></div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.85rem; color: #64748b;">
                            <span>Cancelled</span>
                            <span class="fw-bold text-dark">{{ $count_dibatalkan }}</span>
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 10px; background-color: #f1f5f9;">
                            <div class="progress-bar" style="width: {{ $total_pesanan > 0 ? ($count_dibatalkan/$total_pesanan)*100 : 0 }}%; background-color: #f87171;"></div>
                        </div>
                    </div>
                </div>

                <hr class="text-muted opacity-25">

                <div class="my-4">
                    <p class="text-uppercase mb-1" style="font-size: 0.75rem; color: #94a3b8; letter-spacing: 0.5px;">Pendapatan Hari Ini</p>
                    <h3 class="fw-bold mb-0" style="color: #1e293b;">Rp {{ number_format($pendapatan_hari_ini, 0, ',', '.') }}</h3>
                </div>

                <button class="btn btn-success w-100 py-2 d-flex align-items-center justify-content-center gap-2 fw-semibold" style="background-color: #2d6a4f; border: none; border-radius: 10px;">
                    <i class="bi bi-download"></i> Unduh Data
                </button>
            </div>
        </div>
    </div>
</div>

@endsection