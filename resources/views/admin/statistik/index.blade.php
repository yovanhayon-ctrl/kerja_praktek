@extends('admin.layouts.admin') {{-- Pastikan nama layout ini sesuai dengan pembungkus admin Anda --}}

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark">Statistik Restoran</h2>
        <p class="text-muted">Analisis performa bisnis dan penjualan RM Saung Tiga</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 12px;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block mb-1">Total Pendapatan</span>
                        <h4 class="fw-bold mb-0 text-success">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</h4>
                    </div>
                    <div class="p-3 bg-success bg-opacity-10 text-success rounded-3">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 12px;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block mb-1">Total Pesanan</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ $total_pesanan }} Pesanan</h4>
                    </div>
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3">
                        <i class="bi bi-bag-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 12px;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block mb-1">Varian Menu</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ $total_menu }} Menu</h4>
                    </div>
                    <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3">
                        <i class="bi bi-egg-fried fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 12px;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block mb-1">Rata-rata Pelayanan</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ $rata_waktu_tunggu }} Menit</h4>
                    </div>
                    <div class="p-3 bg-info bg-opacity-10 text-info rounded-3">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 16px;">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-graph-up-arrow me-2 text-success"></i>Tren Pendapatan (7 Hari Terakhir)</h5>
                <div style="position: relative; height:300px;">
                    <canvas id="chartPendapatan"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 16px; height: 100%;">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-fire me-2 text-danger"></i>5 Menu Terlaris (Top Selling)</h5>
                
                @if($menu_terlaris->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted fs-2"></i>
                        <p class="text-muted mt-2">Belum ada data penjualan selesai.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead>
                                <tr class="text-muted small">
                                    <th>NAMA MENU</th>
                                    <th class="text-center">TERJUAL</th>
                                    <th class="text-end">TOTAL OMSET</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($menu_terlaris as $menu)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-dark d-block">{{ $menu['nama_menu'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-2.5 py-1.5 fw-bold rounded-pill">
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