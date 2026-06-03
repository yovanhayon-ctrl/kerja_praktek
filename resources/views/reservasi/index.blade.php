@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill mb-2">
            <i class="bi bi-shop me-1"></i> Booking Online
        </span>
        <h1 class="fw-bold text-dark display-5">Reservasi Meja & Saung</h1>
        <p class="text-muted text-center mx-auto" style="max-width: 600px;">
            Silakan tentukan jadwal dan pilih satu atau beberapa nomor meja favoritmu sesuai kapasitas rombongan di RM Saung Tiga.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 p-4 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="bg-success text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-check2-circle fs-4"></i>
                </div>
                <div>
                    <h5 class="alert-heading fw-bold mb-1">Berhasil!</h5>
                    <p class="mb-0 text-secondary">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="top: 25px; right: 20px;"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 p-4 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="bg-danger text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-exclamation-triangle fs-4"></i>
                </div>
                <div>
                    <h5 class="alert-heading fw-bold mb-1">Pemesanan Gagal!</h5>
                    <p class="mb-0 text-secondary">{{ session('error') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="top: 25px; right: 20px;"></button>
        </div>
    @endif

    <ul class="nav nav-pills justify-content-center gap-2 mb-4 bg-light p-2 rounded-4 shadow-sm mx-auto" style="max-width: 450px;" id="reservasiTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-index-tab nav-link active fw-bold px-4 py-2.5 rounded-3 border-0 transition-all" id="booking-tab" data-bs-toggle="tab" data-bs-target="#booking-panel" type="button" role="tab" aria-controls="booking-panel" aria-selected="true">
                <i class="bi bi-calendar-plus me-1"></i> Form Booking
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-index-tab nav-link fw-bold px-4 py-2.5 rounded-3 border-0 transition-all" id="status-tab" data-bs-toggle="tab" data-bs-target="#status-panel" type="button" role="tab" aria-controls="status-panel" aria-selected="false">
                <i class="bi bi-search me-1"></i> Cek Status Anda
            </button>
        </li>
    </ul>

    <div class="tab-content mt-2" id="reservasiTabContent">
        
        <div class="tab-pane fade show active" id="booking-panel" role="tabpanel" aria-labelledby="booking-tab">
            <form action="{{ route('reservasi.store') }}" method="POST" id="formBookingUtama">
                @csrf
                <div class="card border-0 shadow-sm rounded-4 p-4 p-sm-5 bg-white">
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-bold small text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="nama_lengkap" class="form-control bg-light border-start-0" placeholder="Masukkan nama lengkap Anda" value="{{ old('nama_lengkap') }}" required style="border-radius: 0 12px 12px 0;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Nomor WhatsApp <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-whatsapp text-muted"></i></span>
                                <input type="tel" name="whatsapp" class="form-control bg-light border-start-0" placeholder="Contoh: 08123456789" inputmode="numeric" pattern="[0-9]*" value="{{ old('whatsapp') }}" required style="border-radius: 0 12px 12px 0;">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label fw-bold small text-dark">Tanggal Kedatangan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-calendar3 text-muted"></i></span>
                                <input type="date" id="tanggal_booking" name="tanggal" class="form-control bg-light border-start-0" min="{{ date('Y-m-d') }}" value="{{ $tanggalDipilih }}" required style="border-radius: 0 12px 12px 0;">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold small text-dark">Jam Datang <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-clock text-muted"></i></span>
                                <input type="time" name="jam" class="form-control bg-light border-start-0" value="{{ old('jam') }}" required style="border-radius: 0 12px 12px 0;">
                            </div>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25 my-4">

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark mb-1">Pilih Nomor Meja / Saung <span class="text-danger">*</span> <span class="text-muted fw-normal small">(Bisa pilih lebih dari 1)</span></label>
                        
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 active" id="btn-filter-all">
                                <i class="bi bi-grid-fill me-1"></i> Semua Meja
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" id="btn-filter-cat1">
                                <i class="bi bi-people-fill me-1"></i> Meja 1-15 <span class="badge bg-success ms-1">10 Orang</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="btn-filter-cat2">
                                <i class="bi bi-people-fill me-1"></i> Meja 16-30 (Kec. 26,27) <span class="badge bg-primary ms-1">8 Orang</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-3 text-dark" id="btn-filter-cat3">
                                <i class="bi bi-gem me-1"></i> Meja 26 & 27 VIP <span class="badge bg-warning text-dark ms-1">25 Orang</span>
                            </button>
                        </div>

                        <div class="d-flex flex-wrap gap-3 mb-3 px-1" style="font-size: 0.75rem;">
                            <div class="d-flex align-items-center gap-1">
                                <span class="d-inline-block" style="width: 12px; height: 12px; background-color: #ffffff; border: 2px solid #198754; border-radius: 3px;"></span>
                                <span class="text-secondary fw-semibold">Ready / Kosong</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <span class="d-inline-block" style="width: 12px; height: 12px; background-color: #198754; border-radius: 3px;"></span>
                                <span class="text-secondary fw-semibold">Dipilih Anda</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <span class="d-inline-block" style="width: 12px; height: 12px; background-color: #e9ecef; border: 1px solid #ced4da; border-radius: 3px;"></span>
                                <span class="text-secondary fw-semibold">Booked / Penuh</span>
                            </div>
                        </div>

                        <div class="row g-2 text-center custom-table-grid">
                            @for ($i = 1; $i <= 30; $i++)
                                @php
                                    // Meja otomatis dinonaktifkan (abu-abu) jika nomornya tercatat masuk di database
                                    $isBooked = in_array($i, $mejaTerboking);
                                    $isOldSelected = is_array(old('nomor_meja')) && in_array($i, old('nomor_meja'));

                                    // Klasifikasi pembagian kapasitas dan kelas border CSS
                                    if ($i >= 1 && $i <= 15) {
                                        $kapasitas = '10 Pax'; 
                                        $filterClass = 'cat-10-pax';
                                        $borderClass = 'border-cat-10';
                                    } elseif ($i == 26 || $i == 27) {
                                        $kapasitas = '25 Pax'; 
                                        $filterClass = 'cat-25-pax';
                                        $borderClass = 'border-cat-25';
                                    } else {
                                        $kapasitas = '8 Pax'; 
                                        $filterClass = 'cat-8-pax';
                                        $borderClass = 'border-cat-8';
                                    }
                                @endphp
                                <div class="col-4 col-sm-3 col-md-2 item-meja-grid {{ $filterClass }}">
                                    <input type="checkbox" class="btn-check" name="nomor_meja[]" id="meja-{{ $i }}" value="{{ $i }}" {{ $isBooked ? 'disabled' : '' }} {{ $isOldSelected ? 'checked' : '' }}>
                                    <label class="btn w-100 py-2 d-flex flex-column align-items-center justify-content-center table-label {{ $borderClass }} {{ $isBooked ? 'meja-booked' : 'meja-ready' }}" for="meja-{{ $i }}">
                                        <span class="fw-bold text-number">#{{ $i }}</span>
                                        <small class="text-kapasitas">{{ $kapasitas }}</small>
                                        <small class="text-status mt-1">{{ $isBooked ? 'Booked' : 'Ready' }}</small>
                                    </label>
                                </div>
                            @endfor
                        </div>
                        @error('nomor_meja')
                            <span class="text-danger small mt-1 d-block"><i class="bi bi-exclamation-circle me-1"></i> Wajib memilih minimal satu nomor meja sebelum melanjutkan kirim formulir.</span>
                        @enderror
                    </div>

                    <div class="mb-4 mt-3">
                        <label class="form-label fw-bold small text-dark">Catatan Tambahan <span class="text-muted fw-normal small">(Opsional)</span></label>
                        <textarea name="catatan" rows="2" class="form-control bg-light" placeholder="Contoh: Lesehan dekat kolam, kursi bayi, request menu paket hemat, dsb." style="border-radius: 12px;">{{ old('catatan') }}</textarea>
                    </div>

                    <button type="submit" class="btn w-100 fw-bold py-2.5 text-white border-0 transition-all btn-submit-custom shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #198754 0%, #157347 100%);">
                        <i class="bi bi-calendar-check me-2"></i> Konfirmasi Booking Tempat
                    </button>
                </div>
            </form>
        </div>

        <div class="tab-pane fade" id="status-panel" role="tabpanel" aria-labelledby="status-tab">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-sm-5 bg-white">
                <div class="card-body p-0 p-sm-2">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-search text-success me-2"></i>Lacak Riwayat Reservasi</h5>
                    <p class="text-muted small">Masukkan nomor WhatsApp yang Anda gunakan saat mendaftar reservasi untuk melihat status persetujuan admin.</p>
                    
                    <form action="{{ route('reservasi.cekStatus') }}" method="GET" id="formCekStatusIntra">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-dark">Nomor WhatsApp Pelanggan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-tel text-muted"></i></span>
                                <input type="tel" name="whatsapp_cek" id="whatsappCekInput" class="form-control bg-light border-start-0" placeholder="Masukkan nomor tanpa spasi/karakter. Contoh: 0831167907820" required style="border-radius: 0 12px 12px 0;">
                                <button class="btn btn-success fw-bold px-4" type="submit" id="btnCekStatus" style="border-radius: 0 12px 12px 0;">
                                    <span class="spinner-border spinner-border-sm me-2 d-none" id="loadingCek" role="status" aria-hidden="true"></span>
                                    Cari Data
                                </button>
                            </div>
                        </div>
                    </form>

                    <div id="hasilPencarianArea" class="d-none mt-4 animate__animated animate__fadeIn">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-journal-text text-secondary me-2"></i>Daftar Riwayat Ditemukan:</h6>
                        <div class="table-responsive rounded-3 border">
                            <table class="table table-hover align-middle mb-0 text-nowrap">
                                <thead class="table-light small uppercase">
                                    <tr>
                                        <th class="ps-3 py-3">Nama Pelanggan</th>
                                        <th class="py-3">Jadwal / Waktu</th>
                                        <th class="py-3 text-center">Meja</th>
                                        <th class="py-3">Catatan</th>
                                        <th class="pe-3 py-3 text-center">Status Anda</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyHasilTabel" class="small text-secondary">
                                    </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="pesanKosongArea" class="d-none mt-4 text-center py-4 bg-light rounded-4 border border-dashed">
                        <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='48' height='48' fill='%236c757d' class='bi bi-folder-x' viewBox='0 0 16 16'><path d='M.5 3A1.5 1.5 0 0 1 2 1.5h3.879a1.5 1.5 0 0 1 1.06.44l1.415 1.414A.5.5 0 0 0 8.707 3.5H14A1.5 1.5 0 0 1 15.5 5v6a1.5 1.5 0 0 1-1.5 1.5H2A1.5 1.5 0 0 1 .5 11V3zM2 2.5a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h12a.5.5 0 0 0 .5-.5V5a.5.5 0 0 0-.5-.5H8.707a1.5 1.5 0 0 1-1.06-.44L6.232 2.646A.5.5 0 0 0 5.121 2.5H2zm3.354 5.146a.5.5 0 1 0-.708.708L6.293 10l-1.647 1.646a.5.5 0 1 0 .708.708L7 10.707l1.646 1.647a.5.5 0 0 0 .708-.708L7.707 10l1.647-1.646a.5.5 0 0 0-.708-.708L7 9.293 5.354 7.646z'/></svg>" class="mb-2 opacity-50" alt="kosong">
                        <p class="mb-0 fw-bold text-dark small" id="textPesanKosong">Tidak ada data reservasi terdaftar untuk nomor ini.</p>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Desain Grid Custom Kotak Nomor Meja */
    .table-label {
        background-color: #ffffff;
        border-radius: 12px !important;
        cursor: pointer;
        transition: all 0.25s ease-in-out;
        user-select: none;
        min-height: 80px;
    }
    .border-cat-10 { border: 2px solid #198754; }
    .border-cat-8  { border: 2px solid #0d6efd; }
    .border-cat-25 { border: 2px solid #ff9800; }
    
    .table-label .text-number { font-size: 1.15rem; color: #212529; }
    .table-label .text-kapasitas { font-size: 0.7rem; color: #6c757d; }
    .table-label .text-status { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; padding: 2px 8px; rounded: 20px; color: #6c757d; background-color: #f8f9fa; border-radius: 20px; }
    
    /* Efek hover khusus meja ready */
    .table-label.meja-ready:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.1) !important;
    }
    
    /* Perubahan Desain Ketika Input Checkbox Berstatus DICENTANG */
    .btn-check:checked + .table-label.meja-ready {
        background-color: #198754 !important;
        border-color: #198754 !important;
        color: #ffffff !important;
    }
    .btn-check:checked + .table-label.meja-ready .text-number { color: #ffffff !important; }
    .btn-check:checked + .table-label.meja-ready .text-kapasitas { color: rgba(255,255,255,0.8) !important; }
    .btn-check:checked + .table-label.meja-ready .text-status { color: #198754 !important; background-color: #ffffff !important; }

    /* Gaya Meja yang Dinonaktifkan (Booked/Terboking) */
    .table-label.meja-booked {
        background-color: #e9ecef !important;
        border-color: #ced4da !important;
        color: #adb5bd !important;
        cursor: not-allowed !important;
    }
    .table-label.meja-booked .text-number { color: #959da5 !important; }
    .table-label.meja-booked .text-kapasitas { color: #adb5bd !important; }
    .table-label.meja-booked .text-status { color: #ffffff !important; background-color: #adb5bd !important; }

    /* Tombol Interaksi & Nav Tab */
    .nav-index-tab.nav-link { color: #6c757d; background: transparent; }
    .nav-index-tab.nav-link.active { background-color: #ffffff !important; color: #198754 !important; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
    .btn-submit-custom:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(25,135,84,0.3) !important; }
    .transition-all { transition: all 0.2s ease; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // --- INTEGRASI FITUR 1: SISTEM AUTO-RELOAD INTERAKTIF GANTI TANGGAL ---
    const inputTanggal = document.getElementById('tanggal_booking');
    if (inputTanggal) {
        inputTanggal.addEventListener('change', function() {
            let tanggalBaru = this.value;
            let form = document.getElementById('formBookingUtama');
            
            // Ambil data input nama dan wa biar gak cape ngetik ulang pelanggan pas halaman ter-reload otomatis
            let nama = form.querySelector('input[name="nama_lengkap"]').value;
            let wa = form.querySelector('input[name="whatsapp"]').value;
            
            // Redirect ulang URL halaman ke tanggal target tujuan
            window.location.href = "{{ route('reservasi.index') }}?tanggal=" + tanggalBaru + "&nama_lengkap=" + encodeURIComponent(nama) + "&whatsapp=" + encodeURIComponent(wa);
        });
    }

    // --- INTEGRASI FITUR 2: FILTER GRID CHECKBOX MEJA BERDASARKAN KAPASITAS ---
    const allMejaItems = document.querySelectorAll('.item-meja-grid');
    const filterButtons = {
        all: document.getElementById('btn-filter-all'),
        cat1: document.getElementById('btn-filter-cat1'),
        cat2: document.getElementById('btn-filter-cat2'),
        cat3: document.getElementById('btn-filter-cat3')
    };

    function clearActiveFilter() {
        Object.values(filterButtons).forEach(btn => { if(btn) btn.classList.remove('active'); });
    }

    if (filterButtons.all) {
        filterButtons.all.addEventListener('click', function() {
            clearActiveFilter(); this.classList.add('active');
            allMejaItems.forEach(item => item.classList.remove('d-none'));
        });
        filterButtons.cat1.addEventListener('click', function() {
            clearActiveFilter(); this.classList.add('active');
            allMejaItems.forEach(item => item.classList.contains('cat-10-pax') ? item.classList.remove('d-none') : item.classList.add('d-none'));
        });
        filterButtons.cat2.addEventListener('click', function() {
            clearActiveFilter(); this.classList.add('active');
            allMejaItems.forEach(item => item.classList.contains('cat-8-pax') ? item.classList.remove('d-none') : item.classList.add('d-none'));
        });
        filterButtons.cat3.addEventListener('click', function() {
            clearActiveFilter(); this.classList.add('active');
            allMejaItems.forEach(item => item.classList.contains('cat-25-pax') ? item.classList.remove('d-none') : item.classList.add('d-none'));
        });
    }

    // --- INTEGRASI FITUR 3: PENGECEKAN STATUS RESERVASI VIA AJAX (LIVE ACTION) ---
    const formCekStatus = document.getElementById('formCekStatusIntra');
    if (formCekStatus) {
        formCekStatus.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const waInput = document.getElementById('whatsappCekInput').value;
            const btn = document.getElementById('btnCekStatus');
            const loading = document.getElementById('loadingCek');
            const areaHasil = document.getElementById('hasilPencarianArea');
            const areaKosong = document.getElementById('pesanKosongArea');
            const tbody = document.getElementById('bodyHasilTabel');

            // Aktifkan Efek Loading Animasi
            btn.setAttribute('disabled', true);
            loading.classList.remove('d-none');
            areaHasil.classList.add('d-none');
            areaKosong.classList.add('d-none');
            tbody.innerHTML = '';

            // Jalankan Fetch API AJAX Request ke Endpoint Laravel
            fetch(`${this.action}?whatsapp=${encodeURIComponent(waInput)}`)
                .then(response => response.json())
                .then(data => {
                    btn.removeAttribute('disabled');
                    loading.classList.add('d-none');

                    if (data.success && data.reservasi.length > 0) {
                        data.reservasi.forEach(item => {
                            let badgeStatus = '';
                            
                            // Logika badge warna status yang dinamis sesuai status database
                            if (item.status === 'PENDING') {
                                badgeStatus = '<span class="badge bg-warning text-dark px-2 py-1.5 rounded-3 fw-bold"><i class="bi bi-clock-history me-1"></i>PENDING</span>';
                            } else if (item.status === 'DISETUJUI') {
                                badgeStatus = '<span class="badge bg-info text-white px-2 py-1.5 rounded-3 fw-bold"><i class="bi bi-calendar-check me-1"></i>DISETUJUI</span>';
                            } else if (item.status === 'SELESAI') {
                                badgeStatus = '<span class="badge bg-success text-white px-2 py-1.5 rounded-3 fw-bold"><i class="bi bi-check-circle me-1"></i>SELESAI</span>';
                            } else {
                                badgeStatus = '<span class="badge bg-danger text-white px-2 py-1.5 rounded-3 fw-bold"><i class="bi bi-x-circle me-1"></i>CANCELLED</span>';
                            }

                            let row = `<tr>
                                <td class="ps-3 fw-bold text-dark">${item.nama_lengkap}</td>
                                <td>
                                    <div class="fw-semibold text-dark">${item.tanggal}</div>
                                    <div class="text-muted extra-small" style="font-size:0.75rem;">Jam ${item.jam}</div>
                                </td>
                                <td class="text-center"><span class="badge bg-secondary-subtle text-secondary px-2 py-1.5 fw-bold border border-secondary-subtle">Meja ${item.nomor_meja}</span></td>
                                <td class="text-wrap" style="max-width: 180px;">${item.catatan}</td>
                                <td class="text-center pe-3">${badgeStatus}</td>
                            </tr>`;
                            tbody.innerHTML += row;
                        });
                        areaHasil.classList.remove('d-none');
                    } else {
                        document.getElementById('textPesanKosong').innerText = "Tidak ditemukan data booking dengan nomor WhatsApp: " + waInput;
                        areaKosong.classList.remove('d-none');
                    }
                })
                .catch(error => {
                    btn.removeAttribute('disabled');
                    loading.classList.add('d-none');
                    document.getElementById('textPesanKosong').innerText = "Terjadi gangguan koneksi sistem. Silakan coba sesaat lagi.";
                    areaKosong.classList.remove('d-none');
                    console.error("Error Cek Status:", error);
                });
        });
    }
});
</script>
@endsection