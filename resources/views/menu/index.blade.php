@extends('layouts.app')

@section('title', 'Menu - RestoKu')

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

        {{-- Filter Kategori --}}
        <div class="col-md-6">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ url('/menu') }}?search={{ request('search') }}"
                   class="btn btn-sm filter-btn {{ !request('kategori') ? 'btn-success active' : 'btn-outline-success' }}"
                   style="border-radius:20px;">
                    Semua
                </a>
                <a href="{{ url('/menu') }}?kategori=Makanan&search={{ request('search') }}"
                   class="btn btn-sm filter-btn {{ request('kategori') == 'Makanan' ? 'btn-success active' : 'btn-outline-success' }}"
                   style="border-radius:20px;">
                    Makanan
                </a>
                <a href="{{ url('/menu') }}?kategori=Minuman&search={{ request('search') }}"
                   class="btn btn-sm filter-btn {{ request('kategori') == 'Minuman' ? 'btn-success active' : 'btn-outline-success' }}"
                   style="border-radius:20px;">
                    Minuman
                </a>
                <a href="{{ url('/menu') }}?kategori=Paket&search={{ request('search') }}"
                   class="btn btn-sm filter-btn {{ request('kategori') == 'Paket' ? 'btn-success active' : 'btn-outline-success' }}"
                   style="border-radius:20px;">
                    Paket
                </a>
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
                <div class="position-relative">
                    @if($menu->gambar)
                        <img src="{{ asset('storage/' . $menu->gambar) }}"
                             class="card-img-top"
                             alt="{{ $menu->nama_menu }}"
                             style="height:180px; object-fit:cover;">
                    @else
                        <div class="bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center"
                             style="height:180px; border-radius: 0.375rem 0.375rem 0 0;">
                            <i class="bi bi-image text-secondary fs-1"></i>
                        </div>
                    @endif

                    {{-- Badge Kategori --}}
                    <span class="position-absolute top-0 end-0 m-2 badge
                        {{ $menu->kategori == 'Makanan' ? 'bg-danger' : 'bg-info' }}">
                        {{ $menu->kategori }}
                    </span>
                </div>

                {{-- Body --}}
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title fw-bold mb-1">{{ $menu->nama_menu }}</h6>
                    <p class="text-muted small mb-2" style="min-height:36px;">
                        {{ Str::limit($menu->deskripsi, 50, '...') }}
                    </p>
                    <p class="text-dark fw-bold mb-3 mt-auto">
                        Rp {{ number_format($menu->harga, 0, ',', '.') }}
                    </p>

                    {{-- Tombol --}}
                    <div class="d-flex gap-2">
                        <a href="{{ url('/menu/' . $menu->id) }}"
                           class="btn btn-outline-secondary btn-sm flex-fill">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        <button class="btn btn-success btn-sm flex-fill btn-tambah-cart"
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
        <div class="col-12 text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted"></i>
            <p class="text-muted mt-2">Belum ada menu tersedia.</p>
        </div>
        @endforelse

    </div>

    {{-- PAGINATION --}}
    @if($menus->hasPages())
    <div class="d-flex justify-content-center mt-5">
        {{-- Pertahankan parameter search & kategori saat pindah halaman --}}
        {{ $menus->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>
@endsection

@push('styles')
<style>
    .menu-card { transition: transform 0.2s, box-shadow 0.2s; }
    .menu-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important; }

    /* Styling pagination aktif */
    .pagination .page-item.active .page-link { background-color: #dc3545; border-color: #dc3545; }
    .pagination .page-link { color: #dc3545; }
    .pagination .page-link:hover { color: #fff; background-color: #dc3545; border-color: #dc3545; }
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
        }, 500); // submit otomatis setelah berhenti mengetik 0.5 detik
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

            // Notifikasi
            document.getElementById('alertMsg').textContent = nama + ' berhasil ditambahkan ke cart!';
            const alertEl = document.getElementById('alertCart');
            alertEl.classList.remove('d-none');
            setTimeout(() => alertEl.classList.add('d-none'), 3000);

            // Feedback tombol
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
        const badge = document.getElementById('cart-badge');
        if (badge) badge.textContent = total;
    }

    updateCartBadge();
</script>
@endpush