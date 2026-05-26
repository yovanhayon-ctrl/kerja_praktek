@extends('layouts.app')

@section('title', 'Reservasi Tempat - RM Saung Tiga')

@section('content')
<div class="container py-5" style="margin-top: 40px;">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            
            {{-- HEADER HALAMAN --}}
            <div class="text-center mb-4">
                <span class="badge px-3 py-2 mb-2 text-success fw-semibold" style="background-color: #e8f5e9; border-radius: 20px;">
                    <i class="bi bi-shop me-1"></i> Booking Online
                </span>
                <h2 class="fw-bold text-dark">Reservasi Meja & Saung</h2>
                <p class="text-muted small px-3">Silakan tentukan jadwal dan pilih nomor meja favoritmu untuk pengalaman kuliner terbaik di RM Saung Tiga.</p>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 20px; background: #ffffff;">
                <div class="card-body p-4 p-sm-5">

                    {{-- ALERTS --}}
                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm small d-flex align-items-center" style="border-radius: 10px; background-color: #d1e7dd;">
                            <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger border-0 shadow-sm small d-flex align-items-center" style="border-radius: 10px; background-color: #f8d7da;">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 text-danger"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    <form action="{{ route('reservasi.store') }}" method="POST" id="formReservasi">
                        @csrf
                        
                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="nama_lengkap" class="form-control bg-light border-start-0 custom-input" placeholder="Masukkan nama lengkap Anda" value="{{ request('nama_lengkap', old('nama_lengkap')) }}" required>
                            </div>
                        </div>

                        {{-- WhatsApp (Hanya bisa angka & tidak memicu reset) --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-dark">Nomor WhatsApp <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;"><i class="bi bi-whatsapp text-muted"></i></span>
                                <input type="tel" 
                                       name="whatsapp" 
                                       id="whatsappInput"
                                       class="form-control bg-light border-start-0 custom-input" 
                                       placeholder="Contoh: 08123456789" 
                                       inputmode="numeric" 
                                       pattern="[0-9]*"
                                       value="{{ request('whatsapp', old('whatsapp')) }}"
                                       required>
                            </div>
                        </div>

                        {{-- Tanggal dan Waktu --}}
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <label class="form-label fw-bold small text-dark">Tanggal Kedatangan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;"><i class="bi bi-calendar3 text-muted"></i></span>
                                    <input type="date" id="tanggal_booking" name="tanggal" class="form-control bg-light border-start-0 custom-input" 
                                           min="{{ date('Y-m-d') }}" value="{{ $tanggalDipilih }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-bold small text-dark">Jam Datang <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;"><i class="bi bi-clock text-muted"></i></span>
                                    <input type="time" name="jam" class="form-control bg-light border-start-0 custom-input" value="{{ old('jam') }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Pilihan Nomor Meja --}}
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold small text-dark mb-0">Pilih Nomor Meja / Saung <span class="text-danger">*</span></label>
                                <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-info-circle me-1"></i>Kapasitas 4-6 orang/meja</span>
                            </div>
                            
                            {{-- Indikator Warna Status Meja --}}
                            <div class="d-flex gap-3 mb-2 px-1" style="font-size: 0.75rem;">
                                <div class="d-flex align-items-center gap-1">
                                    <span class="d-inline-block" style="width: 12px; height: 12px; background-color: #ffffff; border: 2px solid #198754; border-radius: 3px;"></span> Ready
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="d-inline-block" style="width: 12px; height: 12px; background-color: #198754; border-radius: 3px;"></span> Dipilih
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="d-inline-block" style="width: 12px; height: 12px; background-color: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 3px;"></span> Terisi (Booked)
                                </div>
                            </div>

                            {{-- Grid Meja --}}
                            <div class="row g-2 text-center custom-table-grid">
                                @for ($i = 1; $i <= 30; $i++)
                                    @php
                                        $isBooked = in_array($i, $mejaTerboking);
                                        $isOldSelected = old('nomor_meja') == $i;
                                    @endphp
                                    <div class="col-3 col-sm-2">
                                        <input type="radio" class="btn-check" name="nomor_meja" id="meja-{{ $i }}" value="{{ $i }}" {{ $isBooked ? 'disabled' : '' }} {{ $isOldSelected ? 'checked' : '' }} required>
                                        <label class="btn w-100 py-2 d-flex flex-column align-items-center justify-content-center table-label {{ $isBooked ? 'meja-booked' : 'meja-ready' }}" 
                                               for="meja-{{ $i }}">
                                            <span class="fw-bold text-number">#{{ $i }}</span>
                                            <small class="text-status">{{ $isBooked ? 'Booked' : 'Ready' }}</small>
                                        </label>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-dark">Catatan Tambahan (Opsional)</label>
                            <textarea name="catatan" class="form-control bg-light custom-input" rows="3" placeholder="Contoh: Minta saung lesehan dekat kolam ikan, atau untuk acara ulang tahun..." style="border-radius: 10px;">{{ old('catatan') }}</textarea>
                        </div>

                        {{-- Button Submit --}}
                        <button type="submit" class="btn w-100 fw-bold py-2.5 shadow-sm text-white border-0 btn-submit-reservation">
                            <i class="bi bi-calendar-check me-2"></i> Konfirmasi Booking Tempat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Styling Dasar Input Custom */
    .custom-input {
        border: 1px solid #e2e8f0;
        border-radius: 0 10px 10px 0 !important;
        font-size: 0.9rem;
        padding: 10px 12px;
        transition: all 0.2s ease;
    }
    textarea.custom-input {
        border-radius: 10px !important;
    }
    .custom-input:focus {
        background-color: #ffffff !important;
        border-color: #198754 !important;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.15) !important;
    }

    /* Grid Container Scrollbar Minimalis */
    .custom-table-grid {
        max-height: 240px; 
        overflow-y: auto; 
        padding: 6px; 
        border: 1px solid #e2e8f0; 
        border-radius: 12px; 
        background: #f8fafc;
    }
    .custom-table-grid::-webkit-scrollbar {
        width: 6px;
    }
    .custom-table-grid::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 10px;
    }

    /* Styling Grid Box Meja */
    .table-label {
        border-radius: 10px; 
        min-height: 52px;
        transition: all 0.2s ease;
    }
    .table-label .text-number { font-size: 0.85rem; }
    .table-label .text-status { font-size: 0.55rem; text-transform: uppercase; letter-spacing: 0.5px; }

    /* State: Meja Kosong (Ready) */
    .meja-ready {
        border: 1px solid #198754;
        background-color: #ffffff;
        color: #198754;
    }
    .meja-ready:hover {
        background-color: #e8f5e9;
        color: #198754;
    }

    /* State: Meja Terpilih saat di-klik */
    .btn-check:checked + .meja-ready {
        background-color: #198754 !important;
        border-color: #198754 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3);
    }

    /* State: Meja Sudah Terboking (Booked) */
    .meja-booked {
        cursor: not-allowed; 
        background-color: #f1f5f9; 
        border: 1px solid #e2e8f0; 
        color: #94a3b8;
    }
    .meja-booked .text-number { color: #94a3b8; }
    .meja-booked .text-status { color: #cbd5e1; background-color: #94a3b8; padding: 1px 4px; border-radius: 4px; color: #ffffff; }

    /* Tombol Submit */
    .btn-submit-reservation {
        background-color: #198754;
        border-radius: 10px;
        padding: 12px;
        font-size: 0.95rem;
        transition: background-color 0.2s ease;
    }
    .btn-submit-reservation:hover {
        background-color: #13633d;
    }
</style>
@endpush

@push('scripts')
<script>
    // FILTER ANGKA WA - Menghapus otomatis karakter non-angka secara real-time murni
    document.getElementById('whatsappInput').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // SISTEM PINDAH TANGGAL - Mengirim ulang parameter dengan tetap mempertahankan inputan teks
    document.getElementById('tanggal_booking').addEventListener('change', function() {
        let tanggal = this.value;
        
        // Ambil ketikan saat ini agar tidak terbuang
        let nama = document.querySelector('input[name="nama_lengkap"]').value;
        let wa = document.getElementById('whatsappInput').value;
        
        // Buat url tujuan reload data meja
        let url = "{{ route('reservasi.index') }}?tanggal=" + tanggal;
        
        // Masukkan data ketikan ke url query string jika ada isinya
        if (nama) { url += "&nama_lengkap=" + encodeURIComponent(nama); }
        if (wa) { url += "&whatsapp=" + encodeURIComponent(wa); }
        
        // Eksekusi pembaruan status meja
        window.location.href = url;
    });
</script>
@endpush