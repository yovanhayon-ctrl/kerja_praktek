@extends('layouts.app')

@section('title', $menu->nama_menu . ' - RestoKu')

@section('content')
<div class="container py-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-danger text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/menu') }}" class="text-danger text-decoration-none">Menu</a></li>
            <li class="breadcrumb-item active">{{ $menu->nama_menu }}</li>
        </ol>
    </nav>

    <div class="row g-5 align-items-start">

        {{-- Gambar --}}
        <div class="col-md-5">
            <div class="card border-0 shadow-sm overflow-hidden">
                @if($menu->gambar)
                    <img src="{{ asset('storage/' . $menu->gambar) }}"
                         alt="{{ $menu->nama_menu }}"
                         class="img-fluid w-100"
                         style="max-height: 380px; object-fit: cover;">
                @else
                    <div class="bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center"
                         style="height: 380px;">
                        <i class="bi bi-image text-secondary" style="font-size: 5rem;"></i>
                    </div>
                @endif
            </div>
        </div>

        {{-- Detail Info --}}
        <div class="col-md-7">
            {{-- Badge Kategori --}}
            <span class="badge {{ $menu->kategori == 'Makanan' ? 'bg-danger' : 'bg-info' }} mb-2">
                {{ $menu->kategori }}
            </span>

            <h2 class="fw-bold mb-2">{{ $menu->nama_menu }}</h2>

            <h3 class="text-dark fw-bold mb-3">
                Rp {{ number_format($menu->harga, 0, ',', '.') }}
            </h3>

            {{-- Desk Awal --}}
            {{-- <p class="text-muted mb-4">
                {{ $menu->deskripsi ?? 'Tidak ada deskripsi untuk menu ini.' }}
            </p> --}}

            <p class="text-muted mb-4">
                {!! nl2br(e($menu->deskripsi ?? 'Tidak ada deskripsi untuk menu ini.')) !!}
            </p>

            <hr>

            {{-- Kontrol Jumlah --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Jumlah</label>
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-dark btn-sm px-3" id="btnKurang">
                        <i class="bi bi-dash-lg"></i>
                    </button>
                    <span class="fs-5 fw-bold" id="jumlahItem">1</span>
                    <button class="btn btn-outline-dark btn-sm px-3" id="btnTambah">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </div>

            {{-- Subtotal --}}
            <div class="mb-4">
                <span class="text-muted">Subtotal: </span>
                <span class="fw-bold text-dark fs-5" id="subtotal">
                    Rp {{ number_format($menu->harga, 0, ',', '.') }}
                </span>
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex gap-3 flex-wrap">
                <button class="btn btn-success px-4" id="btnAddCart"
                        data-id="{{ $menu->id }}"
                        data-nama="{{ $menu->nama_menu }}"
                        data-harga="{{ $menu->harga }}">
                    <i class="bi bi-cart-plus"></i> Tambah ke Cart
                </button>
                <a href="{{ url('/menu') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            {{-- Alert sukses --}}
            <div id="alertsukses" class="alert alert-success mt-3 d-none">
                <i class="bi bi-check-circle"></i> Berhasil ditambahkan ke cart!
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const harga  = {{ $menu->harga }};
    let jumlah   = 1;

    const elJumlah  = document.getElementById('jumlahItem');
    const elSubtotal = document.getElementById('subtotal');

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    function updateUI() {
        elJumlah.textContent   = jumlah;
        elSubtotal.textContent = formatRupiah(harga * jumlah);
    }

    document.getElementById('btnTambah').addEventListener('click', () => {
        jumlah++;
        updateUI();
    });

    document.getElementById('btnKurang').addEventListener('click', () => {
        if (jumlah > 1) { jumlah--; updateUI(); }
    });

    document.getElementById('btnAddCart').addEventListener('click', function () {
        const id   = this.dataset.id;
        const nama = this.dataset.nama;

        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const idx = cart.findIndex(i => i.id == id);

        if (idx > -1) {
            cart[idx].qty += jumlah;
        } else {
            cart.push({ id, nama, harga, qty: jumlah });
        }

        localStorage.setItem('cart', JSON.stringify(cart));

        // Update badge navbar
        const total = cart.reduce((sum, i) => sum + i.qty, 0);
        const badge = document.getElementById('cart-badge');
        if (badge) badge.textContent = total;

        // Tampilkan alert
        const alert = document.getElementById('alertsukses');
        alert.classList.remove('d-none');
        setTimeout(() => alert.classList.add('d-none'), 3000);
    });
</script>
@endpush