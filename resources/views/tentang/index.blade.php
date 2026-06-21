@extends('layouts.app')

@section('title', 'Tentang Kami - RM Saung Tiga')

@section('content')

{{-- HERO TENTANG --}}
<section class="py-5 text-white d-flex align-items-center" style="min-height: 400px; background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('{{ asset('images/tentang.jpg') }}') center/cover no-repeat;">">
    <div class="container text-center py-3">
        <h1 class="fw-bold mb-2">Tentang Rumah Makan Saung Tiga</h1>
        <p class="mb-0 opacity-75 fs-5">Mengenal kami lebih dekat</p>
    </div>
</section>

{{-- DESKRIPSI RESTORAN --}}
<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-md-6">
                <span class="badge bg-primary-subtle text-primary mb-2">Tentang Kami</span>
                <h2 class="fw-bold mb-3">Saung Tiga Menggugah Selera</h2>
                <p class="text-muted mb-3">
                    RM Saung Tiga adalah rumah makan yang berdiri sejak tahun 2020 dengan misi sederhana:
                    menyajikan makanan lezat berkualitas tinggi dengan harga yang terjangkau untuk semua kalangan.
                </p>
                <p class="text-muted mb-3">
                    Setiap hidangan kami dimasak menggunakan bahan-bahan segar pilihan yang didapatkan langsung
                    dari petani lokal. Kami percaya bahwa makanan yang baik dimulai dari bahan baku yang berkualitas.
                </p>
                <p class="text-muted">
                    Dengan pengalaman lebih dari 4 tahun melayani pelanggan setia kami, RM Saung Tiga terus berinovasi
                    dalam menghadirkan menu-menu baru yang memanjakan lidah tanpa menguras kantong.
                </p>
            </div>
            <div class="col-md-6">
                <div class="row g-3">
                    @php
                        $stats = [
                            ['angka' => '500+',  'label' => 'Pelanggan Puas',   'icon' => 'bi-people-fill',      'warna' => 'danger'],
                            ['angka' => '50+',   'label' => 'Menu Tersedia',    'icon' => 'bi-journal-richtext',  'warna' => 'warning'],
                            ['angka' => '4 Th',  'label' => 'Pengalaman',       'icon' => 'bi-award-fill',        'warna' => 'success'],
                            ['angka' => '4.8',   'label' => 'Rating Pelanggan', 'icon' => 'bi-star-fill',         'warna' => 'info'],
                        ];
                    @endphp
                    @foreach($stats as $s)
                    <div class="col-6">
                        <div class="card border-0 shadow-sm text-center p-3 h-100">
                            <i class="bi {{ $s['icon'] }} fs-2 text-{{ $s['warna'] }} mb-2"></i>
                            <h3 class="fw-bold mb-0">{{ $s['angka'] }}</h3>
                            <small class="text-muted">{{ $s['label'] }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="my-0">

{{-- JAM OPERASIONAL & KONTAK & ALAMAT --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Informasi & Kontak</h2>
            <p class="text-muted">Temukan kami atau hubungi langsung</p>
        </div>

        <div class="row g-4">

            {{-- Alamat --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-success bg-opacity-10 rounded d-flex align-items-center justify-content-center"
                            style="width:48px; height:48px;">
                            <i class="bi bi-geo-alt-fill text-success fs-5"></i>
                        </div>
                        <h6 class="fw-bold mb-0">Alamat</h6>
                    </div>
                    <p class="text-muted mb-1">Jl. Pemuda No.2, RT.02/RW.06</p>
                    <p class="text-muted mb-1">Sawangan Baru, Kec. Sawangan</p>
                    <p class="text-muted mb-3">Kota Depok, Jawa Barat 16511</p>
                    <a href="https://www.google.com/maps/search/?api=1&query=RM+Saung+Tiga+Sawangan+Depok" target="_blank" class="btn btn-outline-success btn-sm w-100">
                        <i class="bi bi-map"></i> Lihat di Google Maps
                    </a>
                </div>
            </div>

            {{-- Jam Operasional --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-warning bg-opacity-10 rounded d-flex align-items-center justify-content-center"
                             style="width:48px; height:48px;">
                            <i class="bi bi-clock-fill text-warning fs-5"></i>
                        </div>
                        <h6 class="fw-bold mb-0">Jam Operasional</h6>
                    </div>
                    <table class="w-100 small">
                        <tbody>
                            @php
                                $jadwal = [
                                    ['hari' => 'Senin - Jumat',  'jam' => '10.00 – 20.00 WIB', 'buka' => true],
                                    ['hari' => 'Sabtu',          'jam' => '10.00 – 20.00 WIB', 'buka' => true],
                                    ['hari' => 'Minggu',         'jam' => '10.00 – 20.00 WIB', 'buka' => true],
                                    
                                ];
                            @endphp
                            @foreach($jadwal as $j)
                            <tr class="{{ !$loop->last ? 'border-bottom' : '' }}">
                                <td class="py-2 text-muted">{{ $j['hari'] }}</td>
                                <td class="py-2 text-end fw-semibold {{ $j['buka'] ? 'text-success' : 'text-danger' }}">
                                    {{ $j['jam'] }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Kontak & Email --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-success bg-opacity-10 rounded d-flex align-items-center justify-content-center"
                             style="width:48px; height:48px;">
                            <i class="bi bi-telephone-fill text-success fs-5"></i>
                        </div>
                        <h6 class="fw-bold mb-0">Hubungi Kami</h6>
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <small class="text-muted d-block mb-1">Telepon / WhatsApp</small>
                            <a href="tel:+6281770003330" class="text-decoration-none fw-semibold text-dark">
                                <i class="bi bi-telephone text-success me-1"></i>  081770003330
                            </a>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted d-block mb-1">Email</small>
                            <a href="mailto:info@rmsaungtiga.com" class="text-decoration-none fw-semibold text-dark">
                                <i class="bi bi-envelope text-danger me-1"></i> info@rmsaungtiga.com
                            </a>
                        </li>
                        <li>
                            <small class="text-muted d-block mb-2">Media Sosial</small>
                            <div class="d-flex gap-2">
                                <a href="https://www.instagram.com/rm.saungtiga/" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-instagram"></i>
                                </a>
                                <a href="https://www.facebook.com/rmsaungtiga/" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- FORM KIRIM PESAN --}}
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Kirim Pesan</h2>
                    <p class="text-muted">Ada pertanyaan atau saran? Kami siap mendengar!</p>
                </div>

                {{-- Alert sukses --}}
                @if(session('pesan_sukses'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> {{ session('pesan_sukses') }}
                </div>
                @endif

                <div class="card border-0 shadow-sm p-4">
                    <form action="{{ route('pesan.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama</label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                   placeholder="Nama kamu" value="{{ old('nama') }}" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   placeholder="email@contoh.com" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Pesan</label>
                            <textarea name="pesan" class="form-control @error('pesan') is-invalid @enderror"
                                      rows="4" placeholder="Tulis pesanmu di sini...">{{ old('pesan') }}</textarea>
                            @error('pesan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection