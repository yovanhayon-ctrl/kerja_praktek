@extends('layouts.app')

@section('title', 'Menu - RM Saung Tiga')

@section('content')
<div class="container py-5">

    {{-- JUDUL --}}
    <div class="text-center mb-4">
        <h2 class="fw-bold">Menu Kami</h2>
        <p class="text-muted">Pilih makanan & minuman favoritmu</p>
    </div>

    {{-- SEARCH & FILTER --}}
    <div class="row g-2 mb-4">
        {{-- Search --}}
        <div class="col-md-6">
            <form method="GET" action="{{ url('/menu') }}" id="formSearch">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text"
                           name="search"
                           id="searchInput"
                           class="form-control border-start-0"
                           placeholder="Cari menu..."
                           value="{{ request('search') }}">
                    {{-- Pertahankan filter kategori saat search --}}
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                </div>
            </form>
        </div>

        {{-- Filter Kategori (Terbagi Rata/Space Evenly di Mobile, Rapi di Laptop) --}}
        <div class="col-md-6">
            <div class="row g-2 row-cols-4 w-100 m-0 p-0 filter-container">
                <div class="col p-0 px-1">
                    <a href="{{ url('/menu') }}?search={{ request('search') }}"
                       class="btn btn-sm filter-btn w-100 text-center text-nowrap {{ !request('kategori') ? 'btn-success active' : 'btn-outline-success' }}"
                       style="border-radius:20px; font-size: 0.85rem; padding: 6px 0;">
                        Semua
                    </a>
                </div>
                <div class="col p-0 px-1">
                    <a href="{{ url('/menu') }}?kategori=Makanan&search={{ request('search') }}"
                       class="btn btn-sm filter-btn w-100 text-center text-nowrap {{ request('kategori') == 'Makanan' ? 'btn-success active' : 'btn-outline-success' }}"
                       style="border-radius:20px; font-size: 0.85rem; padding: 6px 0;">
                        Makanan
                    </a>
                </div>
                <div class="col p-0 px-1">
                    <a href="{{ url('/menu') }}?kategori=Minuman&search={{ request('search') }}"
                       class="btn btn-sm filter-btn w-100 text-center text-nowrap {{ request('kategori') == 'Minuman' ? 'btn-success active' : 'btn-outline-success' }}"
                       style="border-radius:20px; font-size: 0.85rem; padding: 6px 0;">
                        Minuman
                    </a>
                </div>
                <div class="col p-0 px-1">
                    <a href="{{ url('/menu') }}?kategori=Paket&search={{ request('search') }}"
                       class="btn btn-sm filter-btn w-100 text-center text-nowrap {{ request('kategori') == 'Paket' ? 'btn-success active' : 'btn-outline-success' }}"
                       style="border-radius:20px; font-size: 0.85rem; padding: 6px 0;">
                        Paket
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Info jumlah hasil --}}
    <p class="text-muted small mb-3">
        Menampilkan <strong>{{ $menus->firstItem() ?? 0 }}</strong> -
        <strong>{{ $menus->lastItem() ?? 0 }}</strong>
        dari <strong>{{ $menus->total() }}</strong> menu
        @if(request('search'))
            untuk pencarian "<strong>{{ request('search') }}</strong>"
        @endif
    </p>

    {{-- NOTIFIKASI BERHASIL TAMBAH KE CART --}}
    <div id="alertCart" class="alert alert-success alert-dismissible d-none" role="alert">
        <i class="bi bi-check-circle"></i> <span id="alertMsg"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    {{-- LIST MENU --}}
    <div class="row g-4" id="menuList">

        @forelse($menus as $menu)
        <div class="col-6 col-md-4 col-lg-3 menu-item"
             data-kategori="{{ $menu->kategori }}"
             data-nama="{{ strtolower($menu->nama_menu) }}">
            <div class="card h-100 border-0 shadow-sm menu-card">

                {{-- Gambar --}}
                <div class="position-relative overflow-hidden" style="border-radius: 16px 16px 0 0;">
                    @if($menu->gambar)
                        <img src="{{ asset('storage/' . $menu->gambar) }}"
                             class="card-img-top menu-card-img"
                             alt="{{ $menu->nama_menu }}"
                             style="height: 180px; object-fit: cover;">
                    @else
                        <div class="bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center"
                             style="height:180px;">
                            <i class="bi bi-image text-secondary fs-1"></i>
                        </div>
                    @endif

                    {{-- Badge Kategori --}}
                    <span class="position-absolute top-0 start-0 m-2 badge"
                          style="background-color: #eb1414; color: #fff; border-radius: 10px;">
                        <i class="bi bi-fire"></i> {{ $menu->kategori }}
                    </span>
                </div>

                {{-- Body Card --}}
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title fw-bold mb-1 text-truncate">{{ $menu->nama_menu }}</h6>
                    <p class="text-muted small mb-2">{{ $menu->kategori }}</p>
                    
                    <p class="fw-bold mt-auto mb-3" style="color: #000000; font-size: 1rem;">
                        Rp {{ number_format($menu->harga, 0, ',', '.') }}
                    </p>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex gap-2">
                        <a href="{{ url('/menu/' . $menu->id) }}"
                           class="btn btn-outline-secondary btn-sm flex-fill d-flex align-items-center justify-content-center gap-1"
                           style="border-radius: 8px; height: 38px;">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        <button class="btn btn-sm flex-fill btn-tambah-cart fw-semibold d-flex align-items-center justify-content-center gap-1 text-nowrap"
                                style="background-color: #198754; color: #fff; border-radius: 8px; border: none; height: 38px; transition: background-color 0.2s ease;"
                                data-id="{{ $menu->id }}"
                                data-nama="{{ $menu->nama_menu }}"
                                data-harga="{{ $menu->harga }}">
                            <i class="bi bi-cart-plus"></i> <span>Pesan</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted"></i>
            <p class="text-muted mt-2">Belum ada menu tersedia.</p>
        </div>
        @endforelse

    </div>

    {{-- PAGINATION --}}
    @if($menus->hasPages())
    <div class="d-flex justify-content-center mt-5">
        {{ $menus->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>
@endsection

@push('styles')
<style>
    .menu-card { transition: transform 0.2s, box-shadow 0.2s; border-radius: 16px; }
    .menu-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important; }

    /* Hover Tombol Pesan: Hanya ganti warna background, tanpa animasi gerak/shadow keluar */
    .btn-tambah-cart:hover {
        background-color: #13633d !important;
        transform: none !important;
        box-shadow: none !important;
    }

    /* Styling pagination aktif agar serasi dengan nuansa hijau Saung Tiga */
    .pagination .page-item.active .page-link { background-color: #198754; border-color: #198754; color: #fff; }
    .pagination .page-link { color: #198754; }
    .pagination .page-link:hover { color: #fff; background-color: #198754; border-color: #198754; }
    
    /* Warna aktif pada filter tombol kategori */
    .filter-btn.btn-success { background-color: #198754; border-color: #198754; }
    .filter-btn.btn-outline-success { color: #198754; border-color: #198754; background-color: transparent; }
    .filter-btn.btn-outline-success:hover { background-color: #198754; color: #fff; }

    /* Membatasi lebar maksimum baris tombol di desktop agar tidak terlalu molor panjang */
    @media (min-width: 768px) {
        .filter-container {
            max-width: 440px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // ── AUTO SUBMIT SEARCH ──
    let searchTimer;
    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            document.getElementById('formSearch').submit();
        }, 500);
    });

    // ── TAMBAH KE CART ──
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

            // Notifikasi atas
            document.getElementById('alertMsg').textContent = nama + ' berhasil ditambahkan ke cart!';
            const alertEl = document.getElementById('alertCart');
            alertEl.classList.remove('d-none');
            setTimeout(() => alertEl.classList.add('d-none'), 3000);

            // Perubahan State Tombol: Dikunci murni hanya ganti teks & warna tanpa efek gerak layout
            const textEl = this.querySelector('span');
            const iconEl = this.querySelector('i');
            
            this.style.backgroundColor = '#13633d'; // warna hover/aktif sukses
            iconEl.className = 'bi bi-check-lg';
            textEl.textContent = 'Sukses';
            
            setTimeout(() => {
                iconEl.className = 'bi bi-cart-plus';
                textEl.textContent = 'Pesan';
                this.style.backgroundColor = '#198754'; // kembalikan ke warna asli awal
            }, 1200);
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