@extends('layouts.app')

@section('title', 'Beranda - RM Saung Tiga')

@section('content')

{{-- ============================= --}}
{{-- 1. BANNER / HERO UTAMA        --}}
{{-- ============================= --}}
<section class="hero-section text-white d-flex align-items-center"
         style="min-height: 520px; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('images/banner.jpg') }}') center/cover no-repeat;">
    <div class="container text-center py-5">

        <span class="badge px-3 py-2 fs-6 mb-3"
              style="background-color: rgba(74,222,128,0.2); border: 1px solid #ffc107; color: #ffc107; border-radius: 20px;">
            <i class="bi bi-stars"></i> Promo Hari Ini
        </span>

        <h1 class="display-4 fw-bold mb-3">
            Selamat Datang di <span style="color: #ffc107;">Rumah Makan Kami</span>
        </h1>

        <p class="lead mb-4 col-md-7 mx-auto" style="opacity: 0.9;">
            Nikmati hidangan lezat pilihan chef terbaik kami. Pesan sekarang dan rasakan pengalaman makan yang tak terlupakan!
        </p>

        <div class="d-flex gap-3 justify-content-center flex-wrap mb-4">
            <a href="{{ url('/menu') }}" class="btn btn-lg px-5 fw-semibold hero-btn-primary">
                <i class="bi bi-bag-check me-1"></i> Pesan Sekarang
            </a>
            <a href="#menu-populer" class="btn btn-outline-light btn-lg px-5 fw-semibold hero-btn-outline">
                <i class="bi bi-grid me-1"></i> Lihat Menu
            </a>
        </div>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <span class="hero-badge"><i class="bi bi-people-fill me-1" style="color:#198754;"></i> Ramah Keluarga</span>
            <span class="hero-badge"><i class="bi bi-patch-check me-1" style="color:#198754;"></i> Bahan Segar</span>
            <span class="hero-badge"><i class="bi bi-lightning me-1" style="color:#fbbf24;"></i> Proses Cepat</span>
        </div>

    </div>
</section>

{{-- ============================= --}}
{{-- 2. HIGHLIGHT KATEGORI MAKANAN --}}
{{-- ============================= --}}
<section class="py-5 fade-up" style="background-color: #f8fffe;">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold mb-1">Kategori Menu</h2>
            <p class="text-muted">Temukan makanan favoritmu dengan cepat</p>
        </div>

        <div class="row g-3 justify-content-center">
            @php
                $kategori = [
                    ['gambar' => 'makanan-berat.jpg', 'nama' => 'Makanan Berat', 'slug' => 'Makanan'],
                    ['gambar' => 'minuman.jpg',        'nama' => 'Minuman',       'slug' => 'Minuman'],
                    ['gambar' => 'dessert.jpg',        'nama' => 'Dessert',       'slug' => 'dessert'],
                    ['gambar' => 'snack.jpg',          'nama' => 'Snack',         'slug' => 'snack'],
                    ['gambar' => 'spesial.jpg',        'nama' => 'Spesial',       'slug' => 'spesial'],
                ];
            @endphp

            @foreach($kategori as $k)
            <div class="col-6 col-sm-4 col-md-2">
                <a href="{{ url('/menu?kategori=' . $k['slug']) }}" class="text-decoration-none">
                    <div class="kategori-card card border-0 shadow-sm text-center h-100 overflow-hidden">
                        <div class="kategori-img-wrapper">
                            <img src="{{ asset('images/kategori/' . $k['gambar']) }}"
                                 alt="{{ $k['nama'] }}"
                                 class="kategori-img"
                                 style="height:100px; width:100%; object-fit:cover;">
                        </div>
                        <div class="p-2 pt-2 pb-3">
                            <small class="fw-semibold text-dark">{{ $k['nama'] }}</small>
                        </div>
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
<section class="py-5 fade-up" id="menu-populer">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Menu Populer</h2>
                <p class="text-muted mb-0">Pilihan terfavorit pelanggan kami</p>
            </div>
            <a href="{{ url('/menu') }}" class="btn btn-sm fw-semibold px-3"
               style="border: 1.5px solid #198754; color: #0d3d1f; border-radius: 20px; transition: all 0.2s;"
               onmouseover="this.style.backgroundColor='#198754'; this.style.color='#fff';"
               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#0d3d1f';">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($menuPopuler ?? [] as $menu)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm menu-card">
                    <div class="position-relative overflow-hidden" style="border-radius: 16px 16px 0 0;">
                        <img src="{{ asset('storage/' . $menu->gambar) }}"
                             class="card-img-top menu-card-img"
                             alt="{{ $menu->nama_menu }}"
                             style="height: 180px; object-fit: cover;">
                        <span class="position-absolute top-0 start-0 m-2 badge"
                              style="background-color: #eb1414; color: #fff; border-radius: 10px;">
                            <i class="bi bi-fire"></i> Best Seller
                        </span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold mb-1">{{ $menu->nama_menu }}</h6>
                        <p class="text-muted small mb-2">{{ $menu->kategori }}</p>
                        <p class="fw-bold mt-auto mb-3" style="color: #000000; font-size: 1rem;">
                            Rp {{ number_format($menu->harga, 0, ',', '.') }}
                        </p>
                        <div class="d-flex gap-2">
                            <a href="{{ url('/menu/' . $menu->id) }}"
                               class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            <button class="btn btn-sm flex-fill btn-tambah-cart fw-semibold"
                                    style="background-color: #198754; color: #fff; border-radius: 8px;"
                                    data-id="{{ $menu->id }}"
                                    data-nama="{{ $menu->nama_menu }}"
                                    data-harga="{{ $menu->harga }}">
                                <i class="bi bi-cart-plus"></i> Pesan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @empty
            @foreach([
                ['nama' => 'Nasi Goreng Spesial', 'harga' => 25000, 'kat' => 'Makanan Berat'],
                ['nama' => 'Ayam Bakar Madu',     'harga' => 32000, 'kat' => 'Makanan Berat'],
                ['nama' => 'Es Teh Manis',         'harga' => 8000,  'kat' => 'Minuman'],
                ['nama' => 'Mie Goreng Pedas',     'harga' => 22000, 'kat' => 'Makanan Berat'],
            ] as $dummy)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm menu-card">
                    <div class="position-relative overflow-hidden" style="border-radius: 16px 16px 0 0;">
                        <div class="bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center"
                             style="height:180px;">
                            <i class="bi bi-image text-secondary fs-1"></i>
                        </div>
                        <span class="position-absolute top-0 start-0 m-2 badge"
                              style="background-color: #198754; color: #fff; border-radius: 10px;">
                            <i class="bi bi-fire"></i> Best Seller
                        </span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold mb-1">{{ $dummy['nama'] }}</h6>
                        <p class="text-muted small mb-2">{{ $dummy['kat'] }}</p>
                        <p class="fw-bold mt-auto mb-3" style="color: #0d3d1f; font-size: 1rem;">
                            Rp {{ number_format($dummy['harga'], 0, ',', '.') }}
                        </p>
                        <button class="btn btn-sm w-100 fw-semibold"
                                style="background-color: #198754; color: #fff; border-radius: 8px;">
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
{{-- 4. CTA PESAN SEKARANG         --}}
{{-- ============================= --}}
<section class="py-5 text-center fade-up"
         style="background: linear-gradient(135deg, #198754, #155a3a); position: relative; overflow: hidden;">
    {{-- Dekorasi lingkaran --}}
    <div style="position:absolute; top:-60px; left:-60px; width:200px; height:200px;
                border-radius:50%; background:rgba(255,255,255,0.08);"></div>
    <div style="position:absolute; bottom:-80px; right:-40px; width:250px; height:250px;
                border-radius:50%; background:rgba(255,255,255,0.06);"></div>

    <div class="container" style="position: relative; z-index: 1;">
        <h2 class="fw-bold mb-2 text-white">Lapar? Pesan Sekarang!</h2>
        <p class="mb-4 fs-5 text-white" style="opacity: 0.9;">
            Menu lezat siap tersaji untuk Anda. Cepat, mudah, dan langsung ke meja Anda.
        </p>
        <a href="{{ url('/menu') }}"
           class="btn btn-light btn-lg px-5 fw-bold shadow"
           style="color: #0d3d1f; border-radius: 50px; transition: all 0.3s;"
           onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.2)';"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
            <i class="bi bi-bag-heart-fill me-2"></i> Mulai Pesan Sekarang
        </a>
    </div>
</section>

{{-- ============================= --}}
{{-- 5. INFORMASI SINGKAT RESTORAN --}}
{{-- ============================= --}}
<section class="py-5 fade-up">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-md-6">
                <span class="badge px-3 py-2 mb-3"
                      style="background-color: rgba(25, 135, 84, 0.15); color: #0d3d1f; border-radius: 20px; font-size: 0.8rem;">
                    Tentang Kami
                </span>
                <h2 class="fw-bold mb-3">Kami Hadir untuk Memuaskan Selera Anda</h2>
                <p class="text-muted mb-3">
                    RM Saung Tiga hadir sejak 2020 dengan misi menyajikan makanan berkualitas tinggi dengan harga yang terjangkau.
                    Setiap hidangan dimasak dengan bahan-bahan segar pilihan dan penuh cinta.
                </p>
                <ul class="list-unstyled text-muted mb-4">
                    <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color:#198754;"></i> Bahan baku segar setiap hari</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color:#198754;"></i> Chef berpengalaman lebih dari 10 tahun</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color:#198754;"></i> Suasana nyaman dan bersih</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color:#198754;"></i> Pelayanan ramah dan cepat</li>
                </ul>
                <a href="{{ url('/tentang') }}"
                   class="btn px-4 fw-semibold"
                   style="border: 2px solid #198754; color: #0d3d1f; border-radius: 10px; transition: all 0.2s;"
                   onmouseover="this.style.backgroundColor='#198754'; this.style.color='#fff';"
                   onmouseout="this.style.backgroundColor='transparent'; this.style.color='#0d3d1f';">
                    Selengkapnya <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="col-md-6">
                <div class="row g-3">
                    @php
                        $stats = [
                            ['angka' => '500+', 'label' => 'Pelanggan Puas',   'icon' => 'bi-people-fill',     'color' => '#198754'],
                            ['angka' => '50+',  'label' => 'Menu Tersedia',    'icon' => 'bi-journal-richtext', 'color' => '#fbbf24'],
                            ['angka' => '4 Th', 'label' => 'Pengalaman',       'icon' => 'bi-award-fill',       'color' => '#34d399'],
                            ['angka' => '4.8',  'label' => 'Rating Pelanggan', 'icon' => 'bi-star-fill',        'color' => '#60a5fa'],
                        ];
                    @endphp
                    @foreach($stats as $s)
                    <div class="col-6">
                        <div class="card border-0 shadow-sm text-center p-4 h-100 stats-card">
                            <i class="bi {{ $s['icon'] }} fs-2 mb-2" style="color: {{ $s['color'] }};"></i>
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
    /* Hero Buttons */
    .hero-btn-primary {
        background-color: #198754;
        color: #fff;
        border: none;
        border-radius: 50px;
        transition: all 0.3s ease;
    }
    .hero-btn-primary:hover {
        background-color: #155a3a;
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(25, 135, 84, 0.4);
    }
    .hero-btn-outline {
        border-radius: 50px;
        transition: all 0.3s ease;
    }
    .hero-btn-outline:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255,255,255,0.2);
    }

    /* Hero Badge */
    .hero-badge {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Kategori Card */
    .kategori-card {
        border-radius: 16px !important;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .kategori-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 40px rgba(25, 135, 84, 0.2) !important;
        border-bottom: 2px solid #198754 !important;
    }
    .kategori-img {
        transition: transform 0.4s ease;
    }
    .kategori-card:hover .kategori-img {
        transform: scale(1.08);
    }

    /* Menu Card */
    .menu-card {
        border-radius: 16px !important;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .menu-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.12) !important;
    }
    .menu-card-img {
        transition: transform 0.4s ease;
    }
    .menu-card:hover .menu-card-img {
        transform: scale(1.08);
    }
    .btn-tambah-cart {
        transition: all 0.2s ease;
    }
    .btn-tambah-cart:hover {
        background-color: #155a3a !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.4);
    }

    /* Stats Card */
    .stats-card {
        border-radius: 16px !important;
        transition: all 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(25, 135, 84, 0.15) !important;
    }
</style>
@endpush

@push('scripts')
<script>
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
            const original = this.innerHTML;
            this.innerHTML = '<i class="bi bi-check-lg"></i> Ditambahkan';
            this.style.backgroundColor = '#22c55e';
            setTimeout(() => {
                this.innerHTML = original;
                this.style.backgroundColor = '#4ade80';
            }, 1500);
        });
    });

    function updateCartBadge() {
        const cart  = JSON.parse(localStorage.getItem('cart') || '[]');
        const total = cart.reduce((sum, i) => sum + i.qty, 0);
        const badge = document.getElementById('cart-badge');
        if (badge) badge.textContent = total;
    }

    updateCartBadge();
</script>
@endpush