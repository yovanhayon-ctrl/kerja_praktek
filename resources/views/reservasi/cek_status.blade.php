@extends('layouts.app') 

@section('content')
<div class="container py-5" style="min-height: 70vh;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="mb-3">
                <a href="{{ route('beranda') }}" class="text-decoration-none text-success small fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>

            <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; bg-white">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-dark mb-2">Lacak Status Reservasi</h3>
                    <p class="text-muted small">Masukkan nomor WhatsApp yang Anda gunakan saat melakukan booking tempat di RM Saung Tiga</p>
                </div>
                
                <form action="{{ route('reservasi.cekProses') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="input-group input-group-lg border rounded p-1 shadow-sm" style="background: #f8f9fa;">
                        <span class="input-group-text bg-transparent border-0 text-success"><i class="bi bi-whatsapp"></i></span>
                        <input type="text" name="whatsapp" class="form-control bg-transparent border-0 shadow-none" 
                               placeholder="Contoh: 08123456789" value="{{ $input_wa ?? '' }}" required>
                        <button class="btn btn-success px-4" type="submit" style="border-radius: 8px;">Cari Data</button>
                    </div>
                </form>

                @if(isset($hasil))
                    <hr class="text-muted opacity-25 my-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-card-list me-2 text-success"></i>Riwayat Booking Anda:</h5>
                    
                    @forelse($hasil as $row)
                        <div class="card border border-light-subtle shadow-none mb-3 p-3 bg-light" style="border-radius: 12px;">
                            <div class="row align-items-center g-3">
                                <div class="col-sm-8">
                                    <h6 class="fw-bold text-dark mb-1">{{ $row->nama_lengkap }}</h6>
                                    
                                    <div class="mb-2">
                                        <span class="badge bg-white border text-dark py-1 px-2.5 small fw-medium">
                                            <i class="bi bi-grid-1x2 me-1 text-secondary"></i>Meja: {{ str_replace(',', ', ', $row->kumpulan_meja) }}
                                        </span>
                                    </div>
                                    
                                    <div class="text-muted small">
                                        <div class="mb-1"><i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($row->waktu_reservasi)->format('d M Y') }}</div>
                                        <div><i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($row->waktu_reservasi)->format('H:i') }} WIB</div>
                                    </div>

                                    @if($row->catatan)
                                        <div class="mt-2 bg-white p-2 rounded border-start border-3 border-muted text-muted small" style="font-style: italic;">
                                            "{{ $row->catatan }}"
                                        </div>
                                    @endif
                                </div>
                                <div class="col-sm-4 text-sm-end">
                                    @if($row->status == 'PENDING')
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm small"><i class="bi bi-hourglass-split me-1"></i> Pending</span>
                                    @elseif($row->status == 'DISETUJUI')
                                        <span class="badge bg-info text-white px-3 py-2 rounded-pill shadow-sm small"><i class="bi bi-check-circle me-1"></i> Disetujui</span>
                                    @elseif($row->status == 'SELESAI')
                                        <span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm small"><i class="bi bi-calendar-check me-1"></i> Selesai</span>
                                    @else
                                        <span class="badge bg-danger text-white px-3 py-2 rounded-pill shadow-sm small"><i class="bi bi-x-circle me-1"></i> Dibatalkan</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-light border text-center py-4 rounded-3 text-muted">
                            <i class="bi bi-exclamation-circle d-block display-6 text-warning mb-2"></i>
                            Maaf, tidak ada data reservasi aktif ditemukan untuk nomor ini.
                        </div>
                    @endforelse
                @endif

            </div>
        </div>
    </div>
</div>
@endsection