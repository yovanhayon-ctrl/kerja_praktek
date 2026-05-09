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

     {{-- Navbar underline fix --}}
    <style>
        .navbar-nav .nav-link {
            position: relative !important;
            border-radius: 0 !important;
            background: transparent !important;
            padding-bottom: 6px !important;
        }
        .navbar-nav .nav-link::after {
            content: '' !important;
            position: absolute !important;
            bottom: 0 !important;
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
        .navbar-nav .nav-link:hover {
            background: transparent !important;
            color: #16a34a !important;
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-light sticky-top" style="background-color: #ffffff; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div class="container">

            {{-- LOGO + NAMA --}}
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ url('/') }}" style="color: #000; position: relative; height: fit-content;">
                <img src="{{ asset('images/logo.png') }}"
                     alt="RestoKu Logo"
                     style="height: 110px; width: 110px; object-fit: contain; border-radius: 8px; margin: -25px 0 -25px -40px;">
                {{-- <span style="font-size: 1.25rem; letter-spacing: 0.5px;">RestoKu</span> --}}
            </a>

            {{-- TOGGLER (mobile) --}}
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- MENU --}}
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-1">
                    <li class="nav-item">
                        <a class="nav-link nav-link-hover px-3 py-2" style="color: #000;" href="{{ url('/') }}">
                            <i class="bi bi-house-door me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-hover px-3 py-2" style="color: #000;" href="{{ url('/menu') }}">
                            <i class="bi bi-grid me-1"></i> Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-hover px-3 py-2" style="color: #000;" href="{{ url('/tentang') }}">
                            <i class="bi bi-info-circle me-1"></i> Tentang
                        </a>
                    </li>

                    {{-- Divider --}}
                    <li class="nav-item mx-1">
                        <span style="border-left: 1px solid rgba(0,0,0,0.2); height: 24px; display: inline-block;"></span>
                    </li>

                    {{-- Cart --}}
                    <li class="nav-item">
                        <a class="nav-link nav-link-hover position-relative px-3 py-2" href="{{ url('/cart') }}" style="color: #000;">
                            <i class="bi bi-cart3 fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"
                                  style="font-size: 0.65rem;"
                                  id="cart-badge">0</span>
                        </a>
                    </li>

                    {{-- Riwayat --}}
                    <li class="nav-item">
                        <a class="btn btn-sm ms-1 fw-semibold"
                           href="{{ url('/riwayat') }}"
                           style="background-color: rgba(0,0,0,0.08); color: #000; border: 1.5px solid rgba(0,0,0,0.2); border-radius: 20px; padding: 5px 14px; transition: all 0.2s;"
                           onmouseover="this.style.backgroundColor='rgba(0,0,0,0.12)'"
                           onmouseout="this.style.backgroundColor='rgba(0,0,0,0.08)'">
                            <i class="bi bi-clock-history me-1"></i> Riwayat
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-dark text-white pt-4 pb-3 mt-5">
        <div class="container">
            <div class="row gy-3">
                <div class="col-md-4">
                    <h5 class="fw-bold"><i class="bi bi-shop"></i> RestoKu</h5>
                    <p class="text-secondary small">Menyajikan cita rasa terbaik dengan bahan-bahan segar pilihan setiap harinya.</p>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-semibold">Navigasi</h6>
                    <ul class="list-unstyled small">
                        <li><a href="{{ url('/') }}" class="text-secondary text-decoration-none">Beranda</a></li>
                        <li><a href="{{ url('/menu') }}" class="text-secondary text-decoration-none">Menu</a></li>
                        <li><a href="{{ url('/tentang') }}" class="text-secondary text-decoration-none">Tentang Kami</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-semibold">Kontak</h6>
                    <ul class="list-unstyled small text-secondary">
                        <li><i class="bi bi-geo-alt"></i> Jl. Contoh No. 123, Kota</li>
                        <li><i class="bi bi-clock"></i> 08.00 – 22.00 WIB</li>
                        <li><i class="bi bi-telephone"></i> 0812-3456-7890</li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary">
            <p class="text-center text-secondary small mb-0">&copy; {{ date('Y') }} RestoKu. All rights reserved.</p>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Custom JS --}}
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>