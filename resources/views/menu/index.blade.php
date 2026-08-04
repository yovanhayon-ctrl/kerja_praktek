@extends('layouts.app')

@section('title', 'Menu - RM Saung Tiga')

@section('content')
<div class="container py-5">

    {{-- JUDUL --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold">Menu Kami</h2>
        <p class="text-muted">Pilih makanan & minuman favoritmu</p>
    </div>

    {{-- SEARCH & FILTER --}}
    <div class="row align-items-center mb-4 g-3">
        
        {{-- Search (Kiri) --}}
        <div class="col-md-5 col-lg-4">
            <form method="GET" action="{{ url('/menu') }}" id="formSearch">
                {{-- Dibuat rounded pill (50px) dengan overflow hidden agar bentuknya membulat di kedua ujung --}}
                <div class="input-group shadow-sm" style="border-radius: 50px; overflow: hidden; border: 1px solid #dee2e6;">
                    <span class="input-group-text bg-white border-0 ps-3">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text"
                           name="search"
                           id="searchInput"
                           class="form-control border-0 shadow-none"
                           placeholder="Cari menu..."
                           value="{{ request('search') }}"
                           style="box-shadow: none !important;">
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                </div>
            </form>
        </div>

        {{-- Filter Kategori (Kanan) --}}
        <div class="col-md-7 col-lg-8 d-flex justify-content-md-end">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ url('/menu') }}?search={{ request('search') }}"
                   class="btn filter-btn {{ !request('kategori') ? 'btn-success active' : 'btn-outline-success bg-white' }}"
                   style="border-radius: 50px; padding: 6px 20px; font-size: 0.85rem;">
                    Semua
                </a>
                <a href="{{ url('/menu') }}?kategori=Makanan&search={{ request('search') }}"
                   class="btn filter-btn {{ request('kategori') == 'Makanan' ? 'btn-success active' : 'btn-outline-success bg-white' }}"
                   style="border-radius: 50px; padding: 6px 20px; font-size: 0.85rem;">
                    Makanan
                </a>
                <a href="{{ url('/menu') }}?kategori=Minuman&search={{ request('search') }}"
                   class="btn filter-btn {{ request('kategori') == 'Minuman' ? 'btn-success active' : 'btn-outline-success bg-white' }}"
                   style="border-radius: 50px; padding: 6px 20px; font-size: 0.85rem;">
                    Minuman
                </a>
                <a href="{{ url('/menu') }}?kategori=Paketan&search={{ request('search') }}"
                   class="btn filter-btn {{ request('kategori') == 'Paketan' ? 'btn-success active' : 'btn-outline-success bg-white' }}"
                   style="border-radius: 50px; padding: 6px 20px; font-size: 0.85rem;">
                    Paketan
                </a>
            </div>
        </div>
        
    </div>

    {{-- NOTIFIKASI BERHASIL TAMBAH KE CART --}}
    <div id="alertCart" class="alert alert-success alert-dismissible d-none shadow-sm" role="alert" style="border-radius: 12px;">
        <i class="bi bi-check-circle-fill me-2"></i> <span id="alertMsg"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    {{-- LIST MENU --}}
    <div class="row g-4" id="menuList">
        @forelse($menus as $menu)
        <div class="col-6 col-md-4 col-lg-3 menu-item">
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
                    <span class="position-absolute top-0 start-0 m-2 badge shadow-sm"
                        style="background-color: @if($menu->kategori == 'Makanan') #198754 @elseif($menu->kategori == 'Minuman') #0d6efd @else #eb1414 @endif; color: #fff; border-radius: 10px; font-weight: normal; padding: 6px 10px;">
                        <i class="bi bi-tag-fill me-1"></i> {{ $menu->kategori }}
                    </span>
                </div>

                {{-- Body Card --}}
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title fw-bold mb-1 text-truncate">{{ $menu->nama_menu }}</h6>
                    <p class="text-muted small mb-2">{{ $menu->kategori }}</p>
                    
                    <p class="fw-bold mt-auto mb-3" style="color: #000000; font-size: 1.1rem;">
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
            <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
            <h5 class="fw-bold">Pencarian Tidak Ditemukan</h5>
            <p class="text-muted mt-2">Maaf, menu yang Anda cari belum tersedia.</p>
        </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($menus->hasPages())
    {{-- Menghapus d-flex justify-content-center agar styling bawaan Laravel memisahkan teks & angka --}}
    <div class="mt-5">
        {{ $menus->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>
@endsection

@push('styles')
<style>
    body { background-color: #f8f9fa; }
    
    .menu-card { transition: transform 0.2s, box-shadow 0.2s; border-radius: 16px; }
    .menu-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; }

    /* Menghilangkan efek biru pada input saat difokuskan */
    #searchInput:focus { outline: none; border: none; box-shadow: none; }

    /* Hover Tombol Pesan */
    .btn-tambah-cart:hover {
        background-color: #13633d !important;
        transform: none !important;
        box-shadow: none !important;
    }

    /* Styling pagination */
    .pagination { margin-bottom: 0; }
    .pagination .page-item.active .page-link { background-color: #198754; border-color: #198754; color: #fff; }
    .pagination .page-link { color: #198754; border-radius: 6px; margin: 0 3px; border: 1px solid #dee2e6;}
    .pagination .page-link:hover { color: #fff; background-color: #198754; border-color: #198754; }
    
    /* Warna aktif pada filter tombol kategori */
    .filter-btn { transition: all 0.2s; border: 1px solid #198754; }
    .filter-btn.btn-success { background-color: #198754; border-color: #198754; color: #fff; }
    .filter-btn.btn-outline-success { color: #198754; }
    .filter-btn.btn-outline-success:hover { background-color: #198754; color: #fff; }
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
            document.getElementById('alertMsg').textContent = nama + ' berhasil ditambahkan ke keranjang!';
            const alertEl = document.getElementById('alertCart');
            alertEl.classList.remove('d-none');
            setTimeout(() => alertEl.classList.add('d-none'), 3000);

            // Perubahan State Tombol
            const textEl = this.querySelector('span');
            const iconEl = this.querySelector('i');
            
            this.style.backgroundColor = '#13633d';
            iconEl.className = 'bi bi-check-lg';
            textEl.textContent = 'Sukses';
            
            setTimeout(() => {
                iconEl.className = 'bi bi-cart-plus';
                textEl.textContent = 'Pesan';
                this.style.backgroundColor = '#198754';
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