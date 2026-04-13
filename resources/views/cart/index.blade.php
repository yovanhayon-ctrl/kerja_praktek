@extends('layouts.app')

@section('title', 'Cart - RestoKu')

@section('content')
<div class="container py-5">

    {{-- Judul --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ url('/menu') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Keranjang Pesanan</h4>
    </div>

    {{-- KONTEN CART --}}
    <div class="row g-4">

        {{-- Daftar Item --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">

                    {{-- Jika cart kosong --}}
                    <div id="cartKosong" class="text-center py-5 d-none">
                        <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                        <p class="text-muted mt-3 mb-3">Keranjang kamu masih kosong.</p>
                        <a href="{{ url('/menu') }}" class="btn btn-danger">
                            <i class="bi bi-bag"></i> Pesan Sekarang
                        </a>
                    </div>

                    {{-- List item cart --}}
                    <div id="cartList"></div>

                </div>
            </div>
        </div>

        {{-- Ringkasan & Checkout --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" id="ringkasanCard">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Ringkasan Pesanan</h6>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Item</span>
                        <span id="totalItem" class="fw-semibold">0 item</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Harga</span>
                        <span id="totalHarga" class="fw-bold text-danger fs-5">Rp 0</span>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="{{ url('/checkout') }}" class="btn btn-danger" id="btnCheckout">
                            <i class="bi bi-bag-check"></i> Lanjut Checkout
                        </a>
                        <button class="btn btn-outline-danger btn-sm" id="btnKosongkan">
                            <i class="bi bi-trash"></i> Kosongkan Cart
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

{{-- Modal Konfirmasi Hapus Semua --}}
<div class="modal fade" id="modalKosongkan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-bold">Kosongkan Cart?</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-muted">
                Semua item di keranjang akan dihapus. Yakin ingin melanjutkan?
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-danger btn-sm" id="btnKonfirmasiKosong">Ya, Kosongkan</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function getCart() {
        return JSON.parse(localStorage.getItem('cart') || '[]');
    }

    function saveCart(cart) {
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    function updateCartBadge(cart) {
        const total = cart.reduce((sum, i) => sum + i.qty, 0);
        const badge = document.getElementById('cart-badge');
        if (badge) badge.textContent = total;
    }

    function renderCart() {
        const cart       = getCart();
        const cartList   = document.getElementById('cartList');
        const cartKosong = document.getElementById('cartKosong');
        const ringkasan  = document.getElementById('ringkasanCard');

        if (cart.length === 0) {
            cartKosong.classList.remove('d-none');
            cartList.innerHTML = '';
            ringkasan.classList.add('d-none');
            return;
        }

        cartKosong.classList.add('d-none');
        ringkasan.classList.remove('d-none');

        // Hitung total
        const totalQty   = cart.reduce((sum, i) => sum + i.qty, 0);
        const totalHarga = cart.reduce((sum, i) => sum + (i.harga * i.qty), 0);

        document.getElementById('totalItem').textContent  = totalQty + ' item';
        document.getElementById('totalHarga').textContent = formatRupiah(totalHarga);

        // Render tiap item
        let html = '';
        cart.forEach((item, idx) => {
            html += `
            <div class="d-flex align-items-center gap-3 p-3 border-bottom">

                {{-- Ikon --}}
                <div class="bg-danger bg-opacity-10 rounded d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:56px; height:56px;">
                    <i class="bi bi-egg-fried text-danger fs-4"></i>
                </div>

                {{-- Info --}}
                <div class="flex-grow-1">
                    <p class="fw-semibold mb-0">${item.nama}</p>
                    <small class="text-danger fw-bold">${formatRupiah(item.harga)}</small>
                </div>

                {{-- Kontrol Jumlah --}}
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-outline-danger btn-sm px-2 py-1 btn-kurang" data-idx="${idx}">
                        <i class="bi bi-dash"></i>
                    </button>
                    <span class="fw-bold" style="min-width:20px; text-align:center;">${item.qty}</span>
                    <button class="btn btn-outline-danger btn-sm px-2 py-1 btn-tambah" data-idx="${idx}">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>

                {{-- Subtotal --}}
                <div class="text-end" style="min-width: 90px;">
                    <small class="text-muted d-block">Subtotal</small>
                    <span class="fw-bold text-danger">${formatRupiah(item.harga * item.qty)}</span>
                </div>

                {{-- Hapus --}}
                <button class="btn btn-link text-danger p-0 btn-hapus" data-idx="${idx}">
                    <i class="bi bi-trash3"></i>
                </button>

            </div>`;
        });

        cartList.innerHTML = html;

        // Event tombol +
        document.querySelectorAll('.btn-tambah').forEach(btn => {
            btn.addEventListener('click', function () {
                const idx  = parseInt(this.dataset.idx);
                let cart   = getCart();
                cart[idx].qty += 1;
                saveCart(cart);
                updateCartBadge(cart);
                renderCart();
            });
        });

        // Event tombol -
        document.querySelectorAll('.btn-kurang').forEach(btn => {
            btn.addEventListener('click', function () {
                const idx = parseInt(this.dataset.idx);
                let cart  = getCart();
                if (cart[idx].qty > 1) {
                    cart[idx].qty -= 1;
                } else {
                    cart.splice(idx, 1); // hapus jika qty = 0
                }
                saveCart(cart);
                updateCartBadge(cart);
                renderCart();
            });
        });

        // Event tombol hapus
        document.querySelectorAll('.btn-hapus').forEach(btn => {
            btn.addEventListener('click', function () {
                const idx = parseInt(this.dataset.idx);
                let cart  = getCart();
                cart.splice(idx, 1);
                saveCart(cart);
                updateCartBadge(cart);
                renderCart();
            });
        });

        updateCartBadge(cart);
    }

    // Kosongkan semua
    document.getElementById('btnKosongkan').addEventListener('click', () => {
        new bootstrap.Modal(document.getElementById('modalKosongkan')).show();
    });

    document.getElementById('btnKonfirmasiKosong').addEventListener('click', () => {
        saveCart([]);
        updateCartBadge([]);
        bootstrap.Modal.getInstance(document.getElementById('modalKosongkan')).hide();
        renderCart();
    });

    // Jalankan saat halaman load
    renderCart();
</script>
@endpush