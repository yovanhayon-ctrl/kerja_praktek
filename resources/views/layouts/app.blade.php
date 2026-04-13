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

    @stack('styles')
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger sticky-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="{{ url('/') }}">
                <i class="bi bi-shop"></i> RestoKu
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active fw-semibold' : '' }}" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('menu*') ? 'active fw-semibold' : '' }}" href="{{ url('/menu') }}">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('tentang*') ? 'active fw-semibold' : '' }}" href="{{ url('/tentang') }}">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ url('/cart') }}">
                            <i class="bi bi-cart3 fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark" id="cart-badge">
                                0
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm ms-2" href="{{ url('/riwayat') }}">
                            <i class="bi bi-clock-history"></i> Riwayat
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

    @stack('scripts')
</body>
</html>