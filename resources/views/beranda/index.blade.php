@extends('layouts.app')

@section('title', 'Beranda - RestoKu')

@section('content')

{{-- ============================= --}}
{{-- 1. BANNER / HERO UTAMA        --}}
{{-- ============================= --}}
<section class="hero-section text-white d-flex align-items-center" style="min-height: 480px; background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('{{ asset('images/banner.jpg') }}') center/cover no-repeat;">
    <div class="container text-center py-5">
        <span class="badge bg-warning text-dark mb-3 px-3 py-2 fs-6">
            <i class="bi bi-stars"></i> Promo Hari Ini
        </span>
        <h1 class="display-4 fw-bold mb-3">Selamat Datang di <span class="text-warning">RestoKu</span></h1>
        <p class="lead mb-4 col-md-7 mx-auto">
            Nikmati hidangan lezat pilihan chef terbaik kami. Pesan sekarang dan rasakan pengalaman makan yang tak terlupakan!
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ url('/menu') }}" class="btn btn-danger btn-lg px-4 shadow">
                <i class="bi bi-bag-check"></i> Pesan Sekarang
            </a>
            <a href="#menu-populer" class="btn btn-outline-light btn-lg px-4">
                <i class="bi bi-grid"></i> Lihat Menu
            </a>
        </div>

        {{-- Promo badge kecil --}}
        <div class="mt-4 d-flex justify-content-center gap-3 flex-wrap">
            <span class="badge bg-white text-dark px-3 py-2">
                <i class="bi bi-truck text-danger"></i> Gratis Ongkir
            </span>
            <span class="badge bg-white text-dark px-3 py-2">
                <i class="bi bi-patch-check text-success"></i> Bahan Segar
            </span>
            <span class="badge bg-white text-dark px-3 py-2">
                <i class="bi bi-lightning text-warning"></i> Proses Cepat
            </span>
        </div>
    </div>
</section>

{{-- ============================= --}}
{{-- 2. HIGHLIGHT KATEGORI MAKANAN --}}
{{-- ============================= --}}
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="fw-bold text-center mb-1">Kategori Menu</h2>
        <p class="text-center text-muted mb-4">Temukan makanan favoritmu dengan cepat</p>
        <div class="row g-3 justify-content-center">

            @php
                $kategori = [
                    ['icon' => 'bi-egg-fried',   'nama' => 'Makanan Berat', 'warna' => 'danger',  'slug' => 'makanan-berat'],
                    ['icon' => 'bi-cup-straw',    'nama' => 'Minuman',      'warna' => 'info',    'slug' => 'minuman'],
                    ['icon' => 'bi-cake2',        'nama' => 'Dessert',      'warna' => 'warning', 'slug' => 'dessert'],
                    ['icon' => 'bi-basket2',      'nama' => 'Snack',        'warna' => 'success', 'slug' => 'snack'],
                    ['icon' => 'bi-fire',         'nama' => 'Spesial',      'warna' => 'dark',    'slug' => 'spesial'],
                ];
            @endphp

            @foreach($kategori as $k)
            <div class="col-6 col-sm-4 col-md-2">
                <a href="{{ url('/menu?kategori=' . $k['slug']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm text-center p-3 h-100 kategori-card">
                        <div class="rounded-circle bg-{{ $k['warna'] }} bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width:64px; height:64px;">
                            <i class="bi {{ $k['icon'] }} fs-3 text-{{ $k['warna'] }}"></i>
                        </div>
                        <small class="fw-semibold text-dark">{{ $k['nama'] }}</small>
                    </div>
                </a>
            </div>
            @endforeach

        </div>
    </div>
</section>

{{-- ============================= --}}
{{-- 3. MENU POPULER / BEST SELLER --}}
{{-- ============================= --}}
<section class="py-5" id="menu-populer">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Menu Populer</h2>
                <p class="text-muted mb-0">Pilihan terfavorit pelanggan kami</p>
            </div>
            <a href="{{ url('/menu') }}" class="btn btn-outline-danger btn-sm">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            {{-- Loop dari controller: $menuPopuler --}}
            @forelse($menuPopuler ?? [] as $menu)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm menu-card">
                    {{-- Badge best seller --}}
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $menu->gambar) }}"
                             class="card-img-top"
                             alt="{{ $menu->nama }}"
                             style="height: 180px; object-fit: cover;">
                        <span class="position-absolute top-0 start-0 m-2 badge bg-danger">
                            <i class="bi bi-fire"></i> Best Seller
                        </span>
                        @if(!$menu->tersedia)
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                             style="background: rgba(0,0,0,0.5); border-radius: 0.375rem 0.375rem 0 0;">
                            <span class="badge bg-secondary fs-6">Habis</span>
                        </div>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold mb-1">{{ $menu->nama }}</h6>
                        <p class="text-muted small mb-2">{{ $menu->kategori }}</p>
                        <p class="card-text text-danger fw-bold mt-auto mb-2">
                            Rp {{ number_format($menu->harga, 0, ',', '.') }}
                        </p>
                        <div class="d-flex gap-2">
                            <a href="{{ url('/menu/' . $menu->id) }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            @if($menu->tersedia)
                            <button class="btn btn-danger btn-sm flex-fill btn-tambah-cart"
                                    data-id="{{ $menu->id }}"
                                    data-nama="{{ $menu->nama }}"
                                    data-harga="{{ $menu->harga }}">
                                <i class="bi bi-cart-plus"></i> Pesan
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            {{-- Tampilan dummy saat data kosong / belum ada di DB --}}
            @foreach([
                ['nama' => 'Nasi Goreng Spesial', 'harga' => 25000, 'kat' => 'Makanan Berat'],
                ['nama' => 'Ayam Bakar Madu',     'harga' => 32000, 'kat' => 'Makanan Berat'],
                ['nama' => 'Es Teh Manis',         'harga' => 8000,  'kat' => 'Minuman'],
                ['nama' => 'Mie Goreng Pedas',     'harga' => 22000, 'kat' => 'Makanan Berat'],
            ] as $dummy)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm menu-card">
                    <div class="position-relative">
                        <div class="bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center"
                             style="height:180px; border-radius: 0.375rem 0.375rem 0 0;">
                            <i class="bi bi-image text-secondary fs-1"></i>
                        </div>
                        <span class="position-absolute top-0 start-0 m-2 badge bg-danger">
                            <i class="bi bi-fire"></i> Best Seller
                        </span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold mb-1">{{ $dummy['nama'] }}</h6>
                        <p class="text-muted small mb-2">{{ $dummy['kat'] }}</p>
                        <p class="card-text text-danger fw-bold mt-auto mb-2">
                            Rp {{ number_format($dummy['harga'], 0, ',', '.') }}
                        </p>
                        <button class="btn btn-danger btn-sm w-100">
                            <i class="bi bi-cart-plus"></i> Pesan
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
            @endforelse
        </div>
    </div>
</section>

{{-- ============================= --}}
{{-- 4. TOMBOL PESAN SEKARANG (CTA)--}}
{{-- ============================= --}}
<section class="py-5 bg-danger text-white text-center">
    <div class="container">
        <h2 class="fw-bold mb-2">Lapar? Pesan Sekarang!</h2>
        <p class="mb-4 fs-5 opacity-75">Menu lezat siap tersaji untuk Anda. Cepat, mudah, dan langsung ke meja Anda.</p>
        <a href="{{ url('/menu') }}" class="btn btn-light btn-lg px-5 fw-semibold text-danger shadow">
            <i class="bi bi-bag-heart-fill"></i> Mulai Pesan Sekarang
        </a>
    </div>
</section>

{{-- ============================= --}}
{{-- 5. INFORMASI SINGKAT RESTORAN --}}
{{-- ============================= --}}
<section class="py-5">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-md-6">
                <span class="badge bg-danger-subtle text-danger mb-2">Tentang Kami</span>
                <h2 class="fw-bold mb-3">Kami Hadir untuk Memuaskan Selera Anda</h2>
                <p class="text-muted mb-3">
                    RestoKu hadir sejak 2020 dengan misi menyajikan makanan berkualitas tinggi dengan harga yang terjangkau.
                    Setiap hidangan dimasak dengan bahan-bahan segar pilihan dan penuh cinta.
                </p>
                <ul class="list-unstyled text-muted">
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Bahan baku segar setiap hari</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Chef berpengalaman lebih dari 10 tahun</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Suasana nyaman dan bersih</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Pelayanan ramah dan cepat</li>
                </ul>
                <a href="{{ url('/tentang') }}" class="btn btn-outline-danger mt-2">
                    Selengkapnya <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="col-md-6">
                <div class="row g-3">
                    @php
                        $stats = [
                            ['angka' => '500+',  'label' => 'Pelanggan Puas',   'icon' => 'bi-people-fill',     'warna' => 'danger'],
                            ['angka' => '50+',   'label' => 'Menu Tersedia',    'icon' => 'bi-journal-richtext', 'warna' => 'warning'],
                            ['angka' => '4 Th',  'label' => 'Pengalaman',       'icon' => 'bi-award-fill',       'warna' => 'success'],
                            ['angka' => '4.8',   'label' => 'Rating Pelanggan', 'icon' => 'bi-star-fill',        'warna' => 'info'],
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

@endsection

@push('styles')
<style>
    .menu-card { transition: transform 0.2s, box-shadow 0.2s; }
    .menu-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important; }
    .kategori-card { transition: transform 0.2s; }
    .kategori-card:hover { transform: translateY(-3px); }
</style>
@endpush

@push('scripts')
<script>
    // Tombol tambah ke cart (simpan di localStorage)
    document.querySelectorAll('.btn-tambah-cart').forEach(btn => {
        btn.addEventListener('click', function () {
            const id    = this.dataset.id;
            const nama  = this.dataset.nama;
            const harga = parseInt(this.dataset.harga);

            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const idx = cart.findIndex(i => i.id == id);

            if (idx > -1) {
                cart[idx].qty += 1;
            } else {
                cart.push({ id, nama, harga, qty: 1 });
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartBadge();

            // Feedback visual
            this.innerHTML = '<i class="bi bi-check-lg"></i> Ditambahkan';
            this.classList.replace('btn-danger', 'btn-success');
            setTimeout(() => {
                this.innerHTML = '<i class="bi bi-cart-plus"></i> Pesan';
                this.classList.replace('btn-success', 'btn-danger');
            }, 1500);
        });
    });

    function updateCartBadge() {
        const cart  = JSON.parse(localStorage.getItem('cart') || '[]');
        const total = cart.reduce((sum, i) => sum + i.qty, 0);
        document.getElementById('cart-badge').textContent = total;
    }

    updateCartBadge(); // Panggil saat halaman load
</script>
@endpush