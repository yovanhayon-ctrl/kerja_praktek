@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4">
    {{-- Header Dashboard --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0" style="font-size: 1.25rem;">Dashboard</h4>
            <small class="text-muted" style="font-size: 0.75rem;">Overview performa restoran hari ini</small>
        </div>
        {{-- <button class="btn btn-success px-3 py-2 fw-bold shadow-sm" style="border-radius: 8px; font-size: 0.8rem;">
            <i class="bi bi-plus-lg me-1"></i> Pesanan Baru
        </button> --}}
    </div>

    {{-- 4 Card Utama (Statistik) --}}
    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['label' => 'TOTAL PESANAN', 'val' => $total_pesanan, 'color' => '#4ade80', 'icon' => 'bi-list-ul', 'pct' => $persen_pesanan . '%'],
                ['label' => 'TOTAL PENDAPATAN (SEMUA)', 'val' => 'Rp '.number_format($total_pendapatan,0,',','.'), 'color' => '#3b82f6', 'icon' => 'bi-wallet2', 'pct' => '+8.2%'],
                ['label' => 'MENU TERLARIS', 'val' => Str::limit($menu_terlaris, 15), 'color' => '#fbbf24', 'icon' => 'bi-star-fill', 'pct' => 'Today'],
                ['label' => 'JUMLAH PELANGGAN', 'val' => $total_pelanggan, 'color' => '#a855f7', 'icon' => 'bi-people-fill', 'pct' => '+14'],
            ];
        @endphp
        @foreach($cards as $c)
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid {{ $c['color'] }} !important;">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="p-2 rounded-3" style="background: #f8fafc; color: {{ $c['color'] }};">
                            <i class="bi {{ $c['icon'] }} fs-5"></i>
                        </div>
                        <span class="badge bg-light text-dark border-0" style="font-size: 0.65rem;">{{ $c['pct'] }}</span>
                    </div>
                    <div>
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">{{ $c['label'] }}</small>
                        <h5 class="fw-bold mb-0" style="font-size: 1.05rem; color: #1e293b;">{{ $c['val'] }}</h5>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4">
        {{-- Kolom Kiri: Tabel Pesanan Terbaru --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 15px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">Recent Orders</h6>
                    <a href="{{ route('admin.pesanan.index') }}" class="btn btn-outline-success btn-sm fw-bold" style="border-radius: 20px; font-size: 0.75rem; padding: 0.4rem 1rem;">Lihat Semua</a>
                </div>
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr class="text-muted" style="font-size: 0.65rem;">
                                <th>ID</th><th>NAMA</th><th>MEJA</th><th>CATATAN</th><th>TOTAL</th><th>STATUS</th><th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.75rem;">
                            @forelse($recent_orders as $o)
                            <tr>
                                <td class="fw-bold">ORD-{{ str_pad($o->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $o->nama_pelanggan }}</td>
                                <td>{{ $o->nomor_meja ?? '-' }}</td>
                                <td><small class="text-muted">{{ $o->catatan ? Str::limit($o->catatan, 20) : '-' }}</small></td>
                                <td class="fw-bold">Rp {{ number_format($o->total_harga,0,',','.') }}</td>
                                <td>
                                    @php
                                        $st = strtoupper($o->status);
                                        $statusMap = ['PENDING'=>'PENDING','DIPROSES'=>'PROCESSING','SELESAI'=>'COMPLETED','DIBATALKAN'=>'CANCELLED'];
                                        $displayStatus = $statusMap[$st] ?? $st;
                                        $bg = ['PENDING'=>'warning','PROCESSING'=>'info','COMPLETED'=>'success','CANCELLED'=>'danger'][$displayStatus] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $bg }}-subtle text-{{ $bg }} px-2 py-1" style="font-size: 0.6rem;">{{ $displayStatus }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.pesanan.show', $o->id) }}" class="btn btn-light border" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-light border dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 text-center" style="font-size: 0.75rem;">
                                            @if(strtoupper($o->status) !== 'SELESAI' && strtoupper($o->status) !== 'DIBATALKAN')
                                            <li>
                                                <form action="{{ route('admin.pesanan.updateStatus', $o->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="DIPROSES">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-gear me-1"></i> Processing
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                            @if(strtoupper($o->status) !== 'SELESAI' && strtoupper($o->status) !== 'DIBATALKAN')
                                            <li>
                                                <form action="{{ route('admin.pesanan.updateStatus', $o->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="SELESAI">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-check-circle me-1"></i> Completed
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada pesanan masuk hari ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Status Pesanan & Ringkasan Pendapatan --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 15px;">
                <h6 class="fw-bold mb-4" style="font-size: 0.9rem;">Status Pesanan</h6>
                
                {{-- Range Status (Progress Bars) --}}
                @foreach($status_data as $s)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1" style="font-size: 0.7rem;">
                        <span class="text-muted">{{ $s['label'] }}</span>
                        <span class="fw-bold">{{ $s['percent'] }}%</span>
                    </div>
                    <div class="progress" style="height: 6px; border-radius: 10px; background-color: #f1f5f9;">
                        <div class="progress-bar" style="width:{{ $s['percent'] }}%; background-color:{{ $s['color'] }}; border-radius: 10px;"></div>
                    </div>
                </div>
                @endforeach

                {{-- Ringkasan Pendapatan Hari Ini --}}
                <div class="mt-4 p-3 rounded-3" style="background: #f8fafc;">
                    <small class="text-muted d-block mb-1" style="font-size: 0.6rem;">PENDAPATAN HARI INI</small>
                    <h6 class="fw-bold mb-0">Rp {{ number_format($pendapatan_hari_ini, 0, ',', '.') }}</h6>
                </div>

                {{-- Tombol Unduh Laporan --}}
                {{-- <button class="btn btn-dark w-100 py-2 mt-4 fw-bold" style="border-radius: 10px; font-size: 0.75rem;">
                    <i class="bi bi-download me-2"></i> Unduh Laporan
                </button> --}}
            </div>
        </div>
    </div>
</div>
@endsection