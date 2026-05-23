@extends('layouts.app') {{-- Sesuaikan dengan master layout utama frontend kamu --}}

@section('content')
<div class="container py-5" style="margin-top: 60px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-dark mb-1">Reservasi Meja & Acara</h4>
                        <p class="text-muted small">Silakan isi formulir di bawah untuk melakukan booking tempat di RM Saung Tiga.</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm small">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger border-0 shadow-sm small">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('reservasi.store') }}" method="POST">
                        @csrf
                        
                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukkan nama kamu" style="border-radius: 8px; font-size: 0.9rem;" required>
                        </div>

                        {{-- WhatsApp --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">Nomor WhatsApp <span class="text-danger">*</span></label>
                            <input type="tel" name="whatsapp" class="form-control" placeholder="Contoh: 08123456789" style="border-radius: 8px; font-size: 0.9rem;" required>
                        </div>

                        {{-- Tanggal dan Waktu --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Tanggal Acara <span class="text-danger">*</span></label>
                                {{-- min="..." otomatis menonaktifkan/mengabu-abukan tanggal kemarin --}}
                                <input type="date" id="tanggal_booking" name="tanggal" class="form-control" 
                                       min="{{ date('Y-m-d') }}" value="{{ $tanggalDipilih }}" style="border-radius: 8px; font-size: 0.9rem;" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">Jam Datang <span class="text-danger">*</span></label>
                                <input type="time" name="jam" class="form-control" style="border-radius: 8px; font-size: 0.9rem;" required>
                            </div>
                        </div>

                        {{-- Pilihan Nomor Meja (Batas 30 Meja) --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-secondary d-block mb-2">Pilih Nomor Meja <span class="text-danger">*</span></label>
                            
                            <div class="row g-2 text-center" style="max-height: 220px; overflow-y: auto; padding: 4px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                                @for ($i = 1; $i <= 30; $i++)
                                    @php
                                        $isBooked = in_array($i, $mejaTerboking);
                                    @endphp
                                    <div class="col-3 col-sm-2">
                                        <input type="radio" class="btn-check" name="nomor_meja" id="meja-{{ $i }}" value="{{ $i }}" {{ $isBooked ? 'disabled' : '' }} required>
                                        <label class="btn w-100 py-2 d-flex flex-column align-items-center justify-content-center {{ $isBooked ? 'btn-secondary opacity-50' : 'btn-outline-success' }}" 
                                               for="meja-{{ $i }}" 
                                               style="border-radius: 8px; font-size: 0.75rem; min-height: 50px; {{ $isBooked ? 'cursor: not-allowed; background-color: #cbd5e1; border-color: #94a3b8; color: #64748b;' : '' }}">
                                            <span class="fw-bold">Meja {{ $i }}</span>
                                            <small style="font-size: 0.55rem;">{{ $isBooked ? 'Booked' : 'Ready' }}</small>
                                        </label>
                                    </div>
                                @endfor
                            </div>
                            <small class="text-muted" style="font-size: 0.7rem;">*Meja abu-abu (Booked) tidak bisa dipilih karena sudah di-booking pada tanggal tersebut.</small>
                        </div>

                        {{-- Catatan --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-secondary">Catatan Tambahan (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Untuk acara ulang tahun keluarga, minta dekat saung tengah." style="border-radius: 8px; font-size: 0.9rem;"></textarea>
                        </div>

                        {{-- Button Submit --}}
                        <button type="submit" class="btn btn-danger w-100 fw-bold py-2 shadow-sm" style="border-radius: 8px; background-color: #c94a4a; border-color: #c94a4a;">
                            <i class="bi bi-calendar-check me-2"></i> Konfirmasi Booking Tempat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script ganti tanggal otomatis filter ulang --}}
<script>
    document.getElementById('tanggal_booking').addEventListener('change', function() {
        let tanggal = this.value;
        window.location.href = "{{ route('reservasi.index') }}?tanggal=" + tanggal;
    });
</script>
@endsection