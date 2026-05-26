<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Restoran Kami')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Custom CSS --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    {{-- Navbar & Footer Custom Style --}}
    <style>
        /* Mengunci perilaku dasar nav-link agar flex dan sejajar vertikal dengan ikon */
        .navbar-nav .nav-link {
            position: relative !important;
            border-radius: 0 !important;
            background: transparent !important;
            display: flex !important;
            align-items: center !important; 
            gap: 0.5rem;
            
            /* Padding vertikal default untuk tampilan mobile menu */
            padding-top: 10px !important;
            padding-bottom: 10px !important;
        }
        
        /* Kustomisasi Khusus Tampilan Desktop (Monitor Besar) */
        @media (min-width: 992px) {
            .navbar-nav .nav-link {
                flex-direction: row !important;
                padding-top: 0 !important;
                /* Memberikan ruang bernapas bawah untuk garis underline hiasan */
                padding-bottom: 4px !important; 
            }

            /* Hiasan garis bawah (underline) interaktif saat hover di desktop */
            .navbar-nav .nav-link::after {
                content: '' !important;
                position: absolute !important;
                bottom: -4px !important; 
                left: 50% !important;
                width: 0 !important;
                height: 2.5px !important;
                background-color: #16a34a !important;
                border-radius: 2px !important;
                transition: left 0.3s ease, width 0.3s ease !important;
            }
            .navbar-nav .nav-link:hover::after {
                left: 25% !important;
                width: 50% !important;
            }

            /* Ukuran logo stabil di desktop */
            .responsive-logo {
                height: 60px !important; 
                width: auto !important;
                margin: 0 !important;
            }
        }
        
        .navbar-nav .nav-link:hover {
            background: transparent !important;
            color: #16a34a !important;
        }

        /* Kustomisasi Khusus Tampilan Mobile & Tablet (Layar HP) */
        @media (max-width: 991.98px) {
            .navbar {
                min-height: auto !important; /* Diubah dari 105px agar tinggi navbar mengikuti isi */
                padding-top: 8px !important;
                padding-bottom: 8px !important;
            }
            .responsive-logo {
                height: 45px !important; /* Diubah dari 85px agar pas dan rapi di HP/Tablet */
                width: auto !important;
                margin: 0 !important;
            }
            /* Merapikan posisi collapse menu drop-down di mobile */
            .navbar-collapse {
                margin-top: 10px;
                border-top: 1px solid rgba(0,0,0,0.08);
                padding-top: 10px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-light sticky-top" style="background-color: #ffffff; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div class="container d-flex align-items-center justify-content-between">

            {{-- LOGO RESTORAN --}}
            <a class="navbar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}" style="margin: 0; padding: 0; height: fit-content;">
                <img src="{{ asset('images/logo.png') }}"
                     alt="RestoKu Logo"
                     class="responsive-logo"
                     style="height: 60px; width: auto; object-fit: contain; border-radius: 8px;">
            </a>

            {{-- ELEMEN SISI KANAN UNTUK MOBILE VIEW (< 992px) --}}
            <div class="d-flex align-items-center gap-2 d-lg-none">
                {{-- Keranjang duplikat khusus Mobile agar user HP tidak perlu membuka menu collapse --}}
                <a class="nav-link position-relative px-3 py-2" href="{{ url('/cart') }}" style="color: #000;">
                    <i class="bi bi-cart3 fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"
                          style="font-size: 0.65rem;"
                          id="cart-badge-mobile">0</span>
                </a>
                {{-- Tombol Hamburger Mobile --}}
                <button class="navbar-toggler border-0 shadow-none p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            {{-- BLOK LINK NAVIGASI --}}
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto align-items-start align-items-lg-center gap-2 py-2 py-lg-0">

                    {{-- 1. Beranda --}}
                    <li class="nav-item w-100 w-lg-auto d-flex align-items-center">
                        <a class="nav-link nav-link-hover px-3 py-2" style="color: #000;" href="{{ url('/') }}">
                            <i class="bi bi-house-door"></i> Beranda
                        </a>
                    </li >

                    {{-- 2. Menu --}}
                    <li class="nav-item w-100 w-lg-auto d-flex align-items-center">
                        <a class="nav-link nav-link-hover px-3 py-2" style="color: #000;" href="{{ url('/menu') }}">
                            <i class="bi bi-grid"></i> Menu
                        </a>
                    </li>

                    {{-- 3. Reservasi --}}
                    <li class="nav-item w-100 w-lg-auto d-flex align-items-center">
                        <a class="nav-link nav-link-hover px-3 py-2" style="color: #000;" href="{{ route('reservasi.index') }}">
                            <i class="bi bi-calendar-check"></i> Reservasi
                        </a>
                    </li>

                    {{-- 4. Tentang Kami --}}
                    <li class="nav-item w-100 w-lg-auto d-flex align-items-center">
                        <a class="nav-link nav-link-hover px-3 py-2" style="color: #000;" href="{{ url('/tentang') }}">
                            <i class="bi bi-info-circle"></i> Tentang
                        </a>
                    </li>
                    
                    {{-- 5. Garis Pembatas Vertikal (Hanya Tampil di Desktop) --}}
                    <li class="nav-item mx-1 d-none d-lg-flex align-items-center justify-content-center">
                        <span style="border-left: 1px solid rgba(0,0,0,0.2); height: 22px; display: inline-block;"></span>
                    </li>

                    {{-- 6. Keranjang Belanja Asli (Hanya Tampil di Desktop) --}}
                    <li class="nav-item d-none d-lg-flex align-items-center">
                        <a class="nav-link position-relative px-3 py-2" href="{{ url('/cart') }}" style="color: #000;">
                            <i class="bi bi-cart3 fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"
                                  style="font-size: 0.65rem;"
                                  id="cart-badge">0</span>
                        </a>
                    </li>

                    {{-- 7. Tombol Riwayat --}}
                    {{-- <li class="nav-item mt-2 mt-lg-0 w-100 w-lg-auto d-flex align-items-center">
                        <a class="btn btn-sm ms-lg-1 fw-semibold d-inline-flex align-items-center justify-content-center gap-1"
                           href="{{ url('/riwayat') }}"
                           style="background-color: rgba(0,0,0,0.06); color: #000; border: 1.5px solid rgba(0,0,0,0.15); border-radius: 20px; padding: 6px 16px; transition: all 0.2s; height: fit-content;"
                           onmouseover="this.style.backgroundColor='rgba(0,0,0,0.12)'"
                           onmouseout="this.style.backgroundColor='rgba(0,0,0,0.06)'">
                            <i class="bi bi-clock-history"></i>
                            <span>Riwayat</span>
                        </a>
                    </li> --}}
                    <li class="nav-item mt-2 mt-lg-0 w-100 {{-- Mengizinkan lebar penuh di mobile --}} w-lg-auto d-flex align-items-center">
                        <a class="btn btn-sm w-100 {{-- Menambahkan w-100 agar memanjang penuh di layout collapse --}} ms-lg-1 fw-semibold d-inline-flex align-items-center justify-content-center gap-1"
                        href="{{ url('/riwayat') }}"
                        style="background-color: rgba(0,0,0,0.06); color: #000; border: 1.5px solid rgba(0,0,0,0.15); border-radius: 20px; padding: 10px 16px; {{-- Padding vertikal ditambah sedikit agar seimbang dengan nav-link --}} transition: all 0.2s; height: fit-content;"
                        onmouseover="this.style.backgroundColor='rgba(0,0,0,0.12)'"
                        onmouseout="this.style.backgroundColor='rgba(0,0,0,0.06)'">
                            <i class="bi bi-clock-history"></i>
                            <span>Riwayat</span>
                        </a>
                    </li>
                </div>
            </div>

        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER RATA TENGAH (CENTERED) --}}
    <footer class="bg-dark text-white pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row gy-4 text-center justify-content-center">
                
                {{-- Profil Singkat Toko --}}
                <div class="col-12 col-md-8 col-lg-4">
                    <h5 class="fw-bold text-success"><i class="bi bi-shop text-white"></i> RM Saung Tiga</h5>
                    <p class="text-secondary small mx-auto mt-2" style="max-width: 320px;">
                        Menyajikan cita rasa terbaik dengan bahan-bahan segar pilihan setiap harinya.
                    </p>
                </div>

                {{-- Tautan Navigasi --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <h6 class="fw-semibold text-light">Navigasi</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mt-2">
                        <li><a href="{{ url('/') }}" class="text-secondary text-decoration-none text-hover-white">Beranda</a></li>
                        <li><a href="{{ url('/menu') }}" class="text-secondary text-decoration-none text-hover-white">Menu</a></li>
                        <li><a href="{{ url('/tentang') }}" class="text-secondary text-decoration-none text-hover-white">Tentang Kami</a></li>
                        <li><a href="{{ route('reservasi.index') }}" class="text-secondary text-decoration-none text-hover-white">Reservasi Meja</a></li>
                    </ul>
                </div>

                {{-- Informasi Kontak --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <h6 class="fw-semibold text-light">Kontak Kami</h6>
                    <ul class="list-unstyled small text-secondary d-flex flex-column gap-2 mt-2 mx-auto" style="max-width: 300px;">
                        <li><i class="bi bi-geo-alt text-success me-1"></i> JL.Pemuda No.2 RT.02/RW.06, Sawangan, Kota Depok, Jawa Barat 16511</li>
                        <li><i class="bi bi-clock text-success me-1"></i> 08.00 – 22.00 WIB</li>
                        <li><i class="bi bi-telephone text-success me-1"></i> 081770003330</li>
                    </ul>
                </div>

            </div>

            <hr class="border-secondary mt-4">
            <p class="text-center text-secondary small mb-0">&copy; {{ date('Y') }} RM Saung Tiga. All rights reserved.</p>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Custom JS --}}
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>