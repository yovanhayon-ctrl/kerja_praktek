@extends('admin.layouts.admin') 

@section('title', 'Admin Statistik')

@section('content')
<div class="container-fluid py-3 py-sm-4 px-2 px-sm-3">
    {{-- Header Section --}}
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1" style="font-size: 1.25rem;">Statistik Restoran</h4>
        <p class="text-muted small mb-0">Analisis performa bisnis dan penjualan RM Saung Tiga</p>
    </div>

    {{-- Info Cards Grid --}}
    <div class="row g-3 mb-4">
        {{-- Total Pendapatan --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #198754 !important;">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="p-2 rounded-3 bg-success-subtle text-success" style="font-size: 1.1rem;">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">TOTAL PENDAPATAN</small>
                        <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1e293b;">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Pesanan --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #0dcaf0 !important;">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="p-2 rounded-3 bg-info-subtle text-info" style="font-size: 1.1rem;">
                            <i class="bi bi-bag-check shadow-none"></i>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">TOTAL PESANAN</small>
                        <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1e293b;">{{ $total_pesanan }} <span class="text-muted fw-normal" style="font-size: 0.75rem;">Pesanan</span></h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- Varian Menu --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #ffc107 !important;">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="p-2 rounded-3 bg-warning-subtle text-warning" style="font-size: 1.1rem;">
                            <i class="bi bi-egg-fried"></i>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">VARIAN MENU</small>
                        <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1e293b;">{{ $total_menu }} <span class="text-muted fw-normal" style="font-size: 0.75rem;">Menu</span></h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rata-rata Pelayanan --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #a855f7 !important;">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="p-2 rounded-3" style="background: #f3e8ff; color: #a855f7; font-size: 1.1rem;">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">RATA-RATA PELAYANAN</small>
                        <h5 class="fw-bold mb-0" style="font-size: 1.1rem; color: #1e293b;">{{ $rata_waktu_tunggu }} <span class="text-muted fw-normal" style="font-size: 0.75rem;">Menit</span></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Graphs --}}
    <div class="row g-4">
        {{-- Left: Tren Pendapatan --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-3 p-sm-4 bg-white" style="border-radius: 15px;">
                <h6 class="fw-bold text-dark mb-3" style="font-size: 0.85rem;">
                    <i class="bi bi-graph-up-arrow me-2 text-success"></i>Tren Pendapatan (7 Hari Terakhir)
                </h6>
                <div style="position: relative; height: 300px;">
                    <canvas id="chartPendapatan"></canvas>
                </div>
            </div>
        </div>

        {{-- Right: Top Selling Menu --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-3 p-sm-4 bg-white h-100" style="border-radius: 15px;">
                <h6 class="fw-bold text-dark mb-3" style="font-size: 0.85rem;">
                    <i class="bi bi-fire me-2 text-danger"></i>5 Menu Terlaris (Top Selling)
                </h6>
                
                @if($menu_terlaris->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted fs-2 d-block mb-2"></i>
                        <p class="text-muted mb-0" style="font-size: 0.75rem;">Belum ada data penjualan selesai.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    <th class="text-muted fw-bold ps-2">NAMA MENU</th>
                                    <th class="text-center text-muted fw-bold">TERJUAL</th>
                                    <th class="text-end text-muted fw-bold pe-2">TOTAL OMSET</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 0.75rem;">
                                @foreach($menu_terlaris as $menu)
                                <tr>
                                    <td class="ps-2 fw-bold text-dark">{{ $menu['nama_menu'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-danger-subtle text-danger px-2.5 py-1 fw-bold rounded-pill" style="font-size: 0.65rem;">
                                            {{ $menu['total_terjual'] }}x
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-dark pe-2">
                                        Rp {{ number_format($menu['total_uang'], 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Scripts Canvas --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('chartPendapatan').getContext('2d');
        
        const labelsData = {!! json_encode($grafik_label) !!};
        const omsetData = {!! json_encode($grafik_pendapatan) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsData,
                datasets: [{
                    label: 'Pendapatan',
                    data: omsetData,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.06)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#198754',
                    pointHoverRadius: 6,
                    pointRadius: 3.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        padding: 10,
                        backgroundColor: '#1e293b',
                        titleFont: { size: 11, weight: '600' },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 }, color: '#64748b' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { size: 10 },
                            color: '#64748b',
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                }
                                return 'Rp ' + value;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection