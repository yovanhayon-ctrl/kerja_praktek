@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0" style="font-size: 1.25rem;">Data Pesanan</h4>
        </div>
        <button class="btn btn-dark px-3 fw-bold" style="border-radius: 8px; font-size: 0.8rem;">
            <i class="bi bi-download me-2"></i> Export to Excel
        </button>
    </div>

    {{-- 4 Card Statistik --}}
    <div class="row g-3 mb-4">
        @php
            $stats = [
                ['label' => 'Total Pesanan', 'val' => number_format($total_pesanan), 'icon' => 'bi-list-ul', 'color' => '#4ade80', 'change' => '+126 Bulanin'],
                ['label' => 'Pendapatan', 'val' => 'Rp ' . number_format($total_pendapatan, 0, ',', '.'), 'icon' => 'bi-wallet2', 'color' => '#3b82f6', 'change' => '+8% vs kemarin'],
                ['label' => 'Pesanan Aktif', 'val' => $pesanan_aktif, 'icon' => 'bi-clock', 'color' => '#f59e0b', 'change' => 'Butuh diproses'],
                ['label' => 'Waktu Tunggu', 'val' => $waktu_tunggu . '.2m', 'icon' => 'bi-hourglass-split', 'color' => '#8b5cf6', 'change' => 'Rata-rata pelayanan'],
            ];
        @endphp
        @foreach($stats as $stat)
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="p-2 rounded-3" style="background: #f8fafc; color: {{ $stat['color'] }};">
                            <i class="bi {{ $stat['icon'] }} fs-5"></i>
                        </div>
                    </div>
                    <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">{{ $stat['label'] }}</small>
                    <h6 class="fw-bold mb-1" style="color: #1e293b; font-size: 1.05rem;">{{ $stat['val'] }}</h6>
                    <small class="text-muted" style="font-size: 0.65rem;">{{ $stat['change'] }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Tab Filter & Search --}}
    <div class="d-flex justify-content-between align-items-center mb-4 gap-3">
        <div class="btn-group" role="group">
            <a href="{{ route('admin.pesanan.index', ['status' => 'semua']) }}" 
               class="btn btn-sm fw-bold px-3 {{ is_null($status) || $status === 'semua' ? 'btn-success' : 'btn-light text-dark' }}"
               style="border-radius: 6px; font-size: 0.8rem;">
                All
            </a>
            <a href="{{ route('admin.pesanan.index', ['status' => 'pending']) }}" 
               class="btn btn-sm fw-bold px-3 {{ $status === 'pending' ? 'btn-success' : 'btn-light text-dark' }}"
               style="border-radius: 6px; font-size: 0.8rem;">
                Pending
            </a>
            <a href="{{ route('admin.pesanan.index', ['status' => 'diproses']) }}" 
               class="btn btn-sm fw-bold px-3 {{ $status === 'diproses' ? 'btn-success' : 'btn-light text-dark' }}"
               style="border-radius: 6px; font-size: 0.8rem;">
                Processing
            </a>
            <a href="{{ route('admin.pesanan.index', ['status' => 'selesai']) }}" 
               class="btn btn-sm fw-bold px-3 {{ $status === 'selesai' ? 'btn-success' : 'btn-light text-dark' }}"
               style="border-radius: 6px; font-size: 0.8rem;">
                Completed
            </a>
            <a href="{{ route('admin.pesanan.index', ['status' => 'dibatalkan']) }}" 
               class="btn btn-sm fw-bold px-3 {{ $status === 'dibatalkan' ? 'btn-success' : 'btn-light text-dark' }}"
               style="border-radius: 6px; font-size: 0.8rem;">
                Cancelled
            </a>
        </div>
        <form method="GET" action="{{ route('admin.pesanan.index') }}" class="d-flex gap-2">
            @if($status && $status !== 'semua')
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <input type="text" name="search" class="form-control form-control-sm" 
                   placeholder="Cari ID/Pesanan..." value="{{ $search ?? '' }}"
                   style="width: 200px; border-radius: 6px; font-size: 0.8rem;">
            <button type="submit" class="btn btn-sm btn-outline-secondary" style="border-radius: 6px;">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr style="font-size: 0.65rem;">
                            <th class="ps-4 text-muted fw-bold">ID PESANAN</th>
                            <th class="text-muted fw-bold">PELANGGAN</th>
                            <th class="text-muted fw-bold">MEJA</th>
                            <th class="text-muted fw-bold">MENU</th>
                            <th class="text-muted fw-bold">WAKTU</th>
                            <th class="text-muted fw-bold">TOTAL</th>
                            <th class="text-muted fw-bold">STATUS</th>
                            <th class="text-center text-muted fw-bold">AKSI</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.75rem;">
                        @forelse($pesanans as $pesanan)
                        <tr>
                            {{-- ID PESANAN Sebelumnya --}}
                            {{-- <td class="ps-4 fw-bold">
                                ORD-{{ str_pad($total_orders_count - $loop->index + 1, 3, '0', STR_PAD_LEFT) }}
                            </td> --}}
                            {{-- SESUDAH (benar — pakai id langsung dari database) --}}
                            <td class="ps-4 fw-bold">
                                ORD-{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="fw-bold">{{ $pesanan->nama_pelanggan ?? 'Tanpa Nama' }}</td>
                            <td>Meja {{ $pesanan->nomor_meja ?? '-' }}</td>
                            <td>
                                @php
                                    $menus = $pesanan->details()->pluck('nama_menu')->toArray();
                                @endphp
                                @if(count($menus) > 0)
                                    <span>{{ implode(', ', array_slice($menus, 0, 1)) }}</span>
                                    @if(count($menus) > 1)
                                        <br><small class="text-muted">+{{ count($menus) - 1 }} menu lainnya</small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold">{{ $pesanan->created_at->format('H:i') }}</span>
                                <br>
                                <small class="text-muted">WIB</small>
                            </td>
                            <td class="fw-bold">
                                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                            </td>
                            <td>
                                @php
                                    $st = strtoupper($pesanan->status);
                                    $statusMap = ['PENDING'=>'PENDING','DIPROSES'=>'PROCESSING','SELESAI'=>'COMPLETED','DIBATALKAN'=>'CANCELLED'];
                                    $displayStatus = $statusMap[$st] ?? $st;
                                    $badgeColor = [
                                        'PENDING' => 'warning',
                                        'PROCESSING' => 'info',
                                        'COMPLETED' => 'success',
                                        'CANCELLED' => 'danger'
                                    ][$displayStatus] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badgeColor }}-subtle text-{{ $badgeColor }} px-2 py-1" style="font-size: 0.6rem;">{{ $displayStatus }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-sm btn-light border" title="Detail" style="font-size: 0.65rem;">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-light border dropdown-toggle" 
                                                data-bs-toggle="dropdown" style="font-size: 0.65rem;">
                                            <i class="bi bi-chevron-down"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="font-size: 0.7rem;">
                                            @if(strtoupper($pesanan->status) !== 'SELESAI' && strtoupper($pesanan->status) !== 'DIBATALKAN')
                                            <li>
                                                <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="DIPROSES">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-gear me-2"></i> Processing
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                            @if(strtoupper($pesanan->status) !== 'SELESAI' && strtoupper($pesanan->status) !== 'DIBATALKAN')
                                            <li>
                                                <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="SELESAI">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-check-circle me-2"></i> Completed
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                            <li>
                                                <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="DIBATALKAN">
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-x-circle me-2"></i> Cancelled
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Tidak ada pesanan ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            {{-- Pagination --}}
            @if($pesanans->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <small class="text-muted">
                    Menampilkan {{ $pesanans->firstItem() }} - {{ $pesanans->lastItem() }} dari {{ $total_pesanan }} pesanan
                </small>
                <nav>
                    <ul class="pagination pagination-sm mb-0 align-items-center">
                        {{-- Previous Link --}}
                        @if($pesanans->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link border-0 shadow-sm rounded-3 me-2 d-flex align-items-center justify-content-center" 
                                    style="width: 32px; height: 32px; background: #f8fafc; color: #ccc;">
                                    <i class="bi bi-chevron-left"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link border-0 shadow-sm rounded-3 me-2 d-flex align-items-center justify-content-center text-dark" 
                                style="width: 32px; height: 32px; background: #fff;" 
                                href="{{ $pesanans->appends(request()->query())->previousPageUrl() }}">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Logika Sliding Pagination (Tampilkan 3 angka saja) --}}
                        @php
                            $currentPage = $pesanans->currentPage();
                            $lastPage = $pesanans->lastPage();

                            // Menentukan titik mulai: 
                            // Jika di halaman 1 atau 2, mulai dari 1. 
                            // Jika sudah di halaman 3 atau lebih, angka mulai bergeser (sliding)
                            $start = max(1, $currentPage - 1);
                            
                            // Pastikan jika di halaman akhir, range tetap konsisten tampil 3 angka
                            if ($lastPage - $start < 2) {
                                $start = max(1, $lastPage - 2);
                            }

                            // Tampilkan maksimal 3 angka saja
                            $end = min($start + 2, $lastPage);
                        @endphp

                        @foreach ($pesanans->getUrlRange($start, $end) as $page => $url)
                            <li class="page-item">
                                <a class="page-link border-0 mx-1 rounded-3 d-flex align-items-center justify-content-center {{ $page == $currentPage ? 'bg-success text-white' : 'text-dark bg-transparent' }}" 
                                style="width: 32px; height: 32px; transition: all 0.2s;" 
                                href="{{ $pesanans->appends(request()->query())->url($page) }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endforeach

                        {{-- Next Link --}}
                        @if($pesanans->hasMorePages())
                            <li class="page-item">
                                <a class="page-link border-0 shadow-sm rounded-3 ms-2 d-flex align-items-center justify-content-center text-dark" 
                                style="width: 32px; height: 32px; background: #fff;" 
                                href="{{ $pesanans->appends(request()->query())->nextPageUrl() }}">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link border-0 shadow-sm rounded-3 ms-2 d-flex align-items-center justify-content-center" 
                                    style="width: 32px; height: 32px; background: #f8fafc; color: #ccc;">
                                    <i class="bi bi-chevron-right"></i>
                                </span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        @endif
        </div>
    </div>
</div>
@endsection