@extends('admin.layouts.admin') 

@section('title', 'Admin Statistik')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h4 class="fw-bold mb-0" style="font-size: 1.25rem;">Statistik Restoran</h4>
        <small class="text-muted" style="font-size: 0.75rem;">Analisis performa bisnis dan penjualan RM Saung Tiga</small>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #198754 !important;">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="p-2 rounded-3" style="background: #f8fafc; color: #198754;">
                            <i class="bi bi-wallet2 fs-5"></i>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">TOTAL PENDAPATAN</small>
                        <h5 class="fw-bold mb-0" style="font-size: 1.05rem; color: #1e293b;">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #3b82f6 !important;">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="p-2 rounded-3" style="background: #f8fafc; color: #3b82f6;">
                            <i class="bi bi-bag-check fs-5"></i>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">TOTAL PESANAN</small>
                        <h5 class="fw-bold mb-0" style="font-size: 1.05rem; color: #1e293b;">{{ $total_pesanan }} Pesanan</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #fbbf24 !important;">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="p-2 rounded-3" style="background: #f8fafc; color: #fbbf24;">
                            <i class="bi bi-egg-fried fs-5"></i>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">VARIAN MENU</small>
                        <h5 class="fw-bold mb-0" style="font-size: 1.05rem; color: #1e293b;">{{ $total_menu }} Menu</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #a855f7 !important;">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="p-2 rounded-3" style="background: #f8fafc; color: #a855f7;">
                            <i class="bi bi-hourglass-split fs-5"></i>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">RATA-RATA PELAYANAN</small>
                        <h5 class="fw-bold mb-0" style="font-size: 1.05rem; color: #1e293b;">{{ $rata_waktu_tunggu }} Menit</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 16px;">
                <h6 class="fw-bold mb-3" style="font-size: 0.9rem;"><i class="bi bi-graph-up-arrow me-2 text-success"></i>Tren Pendapatan (7 Hari Terakhir)</h6>
                <div style="position: relative; height:300px;">
                    <canvas id="chartPendapatan"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 16px; height: 100%;">
                <h6 class="fw-bold mb-3" style="font-size: 0.9rem;"><i class="bi bi-fire me-2 text-danger"></i>5 Menu Terlaris (Top Selling)</h6>
                
                @if($menu_terlaris->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted fs-2"></i>
                        <p class="text-muted mt-2" style="font-size: 0.75rem;">Belum ada data penjualan selesai.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead>
                                <tr class="text-muted" style="font-size: 0.65rem;">
                                    <th>NAMA MENU</th>
                                    <th class="text-center">TERJUAL</th>
                                    <th class="text-end">TOTAL OMSET</th>
                                </tr>
                             Clyde </thead>
                            <tbody style="font-size: 0.75rem;">
                                @foreach($menu_terlaris as $menu)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-dark d-block">{{ $menu['nama_menu'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 fw-bold rounded-pill" style="font-size: 0.6rem;">
                                            {{ $menu['total_terjual'] }}x
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-dark">
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
                    label: 'Pendapatan (Rp)',
                    data: omsetData,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#198754',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 500000, 
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection