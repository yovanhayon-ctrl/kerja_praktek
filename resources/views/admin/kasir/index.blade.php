@extends('admin.layouts.admin')

@section('title', 'Sistem Kasir - RM Saung Tiga')
@section('page-title', 'Sistem Kasir')
@section('page-subtitle', 'Manajemen transaksi penjualan langsung di counter restoran')

@section('content')
<div class="container-fluid py-2 px-1">
    <div class="row g-4">
        
        {{-- KOLOM KIRI: KATALOG MENU --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4 mb-3" style="border-radius: 15px;">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-1" style="font-size: 1.15rem;">Katalog Kasir</h5>
                        <small class="text-muted" style="font-size: 0.8rem;">Klik menu untuk memasukkan ke keranjang nota</small>
                    </div>
                    {{-- Form Cari Menu --}}
                    <form method="GET" action="{{ route('admin.kasir.index') }}" class="d-flex gap-1">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari menu..." value="{{ request('search') }}" style="font-size: 0.85rem;">
                        <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-search"></i></button>
                    </form>
                </div>

                {{-- Kategori Tabs Quick Filter --}}
                <div class="d-flex gap-2 overflow-x-auto pb-2 mb-3" style="white-space: nowrap;">
                    <a href="{{ route('admin.kasir.index') }}" class="btn btn-sm {{ !request('kategori') ? 'btn-success' : 'btn-light text-dark border' }} px-3 fw-medium" style="border-radius: 8px; font-size: 0.8rem;">Semua</a>
                    <a href="{{ route('admin.kasir.index', ['kategori' => 'Makanan']) }}" class="btn btn-sm {{ request('kategori') == 'Makanan' ? 'btn-success' : 'btn-light text-dark border' }} px-3 fw-medium" style="border-radius: 8px; font-size: 0.8rem;">Makanan</a>
                    <a href="{{ route('admin.kasir.index', ['kategori' => 'Minuman']) }}" class="btn btn-sm {{ request('kategori') == 'Minuman' ? 'btn-success' : 'btn-light text-dark border' }} px-3 fw-medium" style="border-radius: 8px; font-size: 0.8rem;">Minuman</a>
                    <a href="{{ route('admin.kasir.index', ['kategori' => 'Paketan']) }}" class="btn btn-sm {{ request('kategori') == 'Paketan' ? 'btn-success' : 'btn-light text-dark border' }} px-3 fw-medium" style="border-radius: 8px; font-size: 0.8rem;">Paketan</a>
                </div>

                {{-- Grid Daftar Menu Scrollable --}}
                <div class="row g-3 overflow-y-auto px-1" style="max-height: 520px;">
                    @forelse($menus as $menu)
                    <div class="col-6 col-sm-4 col-md-3">
                        <form action="{{ route('admin.kasir.add', $menu->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="card h-100 border-0 shadow-xs text-start w-100 p-0 position-relative text-decoration-none bg-white btn-menu-card" style="border-radius: 12px; overflow: hidden; transition: all 0.2s;">
                                <img src="{{ asset('storage/' . $menu->gambar) }}" class="w-100" style="height: 110px; object-fit: cover;" onerror="this.onerror=null; this.src='https://placehold.co/300x200?text=No+Image';">
                                <div class="p-3">
                                    <div class="fw-bold text-dark text-truncate mb-1" style="font-size: 0.85rem;">{{ $menu->nama_menu }}</div>
                                    <span class="badge bg-light text-secondary border mb-2 px-2 py-1" style="font-size: 0.65rem;">{{ $menu->kategori }}</span>
                                    <div class="fw-bold text-success" style="font-size: 0.9rem;">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                                </div>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="col-12 text-center text-muted py-5" style="font-size: 0.9rem;">Tidak ada menu aktif tersedia.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: STRUK NOTA & PEMBAYARAN --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 15px; background-color: #fff;">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;"><i class="bi bi-receipt me-2"></i>Keranjang Nota</h5>
                    
                    @if(count($cart) > 0 && !session()->has('pesanan_id_aktif'))
                    <form action="{{ route('admin.kasir.clear') }}" method="POST" onsubmit="return confirm('Kosongkan semua list item di nota?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none p-0 fw-medium" style="font-size: 0.85rem;"><i class="bi bi-trash"></i> Reset</button>
                    </form>
                    @endif
                </div>

                {{-- Indikator Pesanan Web --}}
                @if(session()->has('pesanan_id_aktif'))
                <div class="alert alert-info py-2 px-3 mb-4 d-flex align-items-start gap-2 shadow-xs" style="border-radius: 10px; background-color: #e0f2fe; border: none; color: #0369a1; font-size: 0.85rem;">
                    <i class="bi bi-info-circle-fill fs-5 mt-1"></i>
                    <div>
                        Memproses pembayaran untuk <strong class="fw-bold">Meja {{ session('nomor_meja_aktif') }}</strong> ({{ session('nama_pelanggan_aktif') ?? 'Tanpa Nama' }})
                    </div>
                </div>
                @endif

                {{-- List Item di Nota --}}
                <div class="overflow-y-auto mb-4 border-bottom pb-2 pe-1" style="max-height: 260px;">
                    @forelse($cart as $id => $item)
                    <div class="d-flex justify-content-between align-items-center mb-3 bg-light p-3 rounded-3 border border-light-subtle">
                        <div style="max-width: 60%;">
                            <div class="fw-bold text-dark text-truncate mb-1" style="font-size: 0.9rem;">{{ $item['nama_menu'] }}</div>
                            <small class="text-secondary fw-medium" style="font-size: 0.8rem;">Rp {{ number_format($item['harga'], 0, ',', '.') }}</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <form action="{{ route('admin.kasir.update', $id) }}" method="POST" class="d-flex align-items-center">
                                @csrf
                                <input type="number" name="jumlah" value="{{ $item['jumlah'] }}" min="1" onchange="this.form.submit()" class="form-control text-center p-1 fw-bold text-dark" style="width: 55px; font-size: 0.85rem; border-radius: 6px;">
                            </form>
                            <form action="{{ route('admin.kasir.remove', $id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger p-1 d-flex align-items-center justify-content-center border-0" style="border-radius: 6px; width: 32px; height: 32px;"><i class="bi bi-x-circle-fill fs-5"></i></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5"><i class="bi bi-cart-x text-secondary fs-1 d-block mb-2"></i><span style="font-size: 0.9rem;">Nota kosong.</span></div>
                    @endforelse
                </div>

                {{-- FORM FINAL CHECKOUT --}}
                <form action="{{ route('admin.kasir.checkout') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="fw-bold text-muted mb-2 d-block" style="font-size: 0.8rem; letter-spacing: 0.5px;">NAMA PELANGGAN</label>
                        {{-- Kunci Field Nama jika pesanan web aktif --}}
                        <input type="text" name="nama_pelanggan" class="form-control p-2 {{ session()->has('pesanan_id_aktif') ? 'bg-light' : '' }}" value="{{ session('nama_pelanggan_aktif', '') }}" {{ session()->has('pesanan_id_aktif') ? 'readonly' : '' }} style="font-size: 0.85rem; border-radius: 8px;">
                    </div>
                    
                    {{-- DROPDOWN SENSOR MEJA --}}
                    <div class="mb-4">
                        <label class="fw-bold text-muted mb-2 d-block" style="font-size: 0.8rem; letter-spacing: 0.5px;">NOMOR MEJA</label>
                        @if(session()->has('pesanan_id_aktif'))
                            {{-- Tampilan Terkunci --}}
                            <input type="hidden" name="nomor_meja" value="{{ session('nomor_meja_aktif') }}">
                            <input type="text" class="form-control p-2 bg-light fw-bold text-secondary" 
                                   value="Meja {{ session('nomor_meja_aktif') }}" 
                                   readonly style="font-size: 0.85rem; border-radius: 8px; cursor: not-allowed;">
                        @else
                            {{-- Dropdown Normal --}}
                            <select name="nomor_meja" class="form-select p-2" required style="font-size: 0.85rem; border-radius: 8px;">
                                <option value="" disabled selected>-- Pilih Nomor Meja --</option>
                                @for($i = 1; $i <= 30; $i++)
                                    @if(in_array($i, $mejaTerboking ?? []))
                                        <option value="{{ $i }}" disabled class="bg-light text-muted">Meja {{ $i }} (Sudah Terisi)</option>
                                    @else
                                        <option value="{{ $i }}">Meja {{ $i }} (Kosong)</option>
                                    @endif
                                @endfor
                            </select>
                        @endif
                    </div>

                    {{-- Ringkasan Perhitungan --}}
                    <div class="bg-light p-4 rounded-3 mb-4 border border-light-subtle">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-secondary fw-medium" style="font-size: 0.85rem;">Total Tagihan:</span>
                            <span class="fw-bold text-dark" style="font-size: 1.1rem;">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-secondary mb-2 d-block" style="font-size: 0.8rem;">UANG TUNAI BAYAR (Rp):</label>
                            <input type="number" id="uang_bayar" class="form-control fw-bold text-success border-success p-2" placeholder="Masukkan nominal uang..." min="{{ $total }}" required style="font-size: 0.95rem; border-radius: 8px;">
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2">
                            <span class="fw-bold text-dark" style="font-size: 0.9rem;">Kembalian:</span>
                            <span class="fw-bold text-danger" id="text_kembalian" style="font-size: 1.1rem;">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold text-center shadow-sm" style="background-color: #2d6a4f; border-color: #2d6a4f; border-radius: 10px; font-size: 0.9rem;" {{ count($cart) == 0 ? 'disabled' : '' }}>
                        <i class="bi bi-check2-circle me-2 fs-5"></i> Proses & Cetak Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('uang_bayar').addEventListener('input', function() {
        const totalTagihan = {{ $total }};
        const uangBayar = parseFloat(this.value) || 0;
        const kembalian = uangBayar - totalTagihan;
        const textKembalian = document.getElementById('text_kembalian');
        if (kembalian >= 0) {
            textKembalian.innerText = 'Rp ' + kembalian.toLocaleString('id-ID');
            textKembalian.className = 'fw-bold text-success';
        } else {
            textKembalian.innerText = 'Uang Kurang';
            textKembalian.className = 'fw-bold text-danger';
        }
    });
</script>
<style>.btn-menu-card:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; border-color: #2d6a4f !important; }</style>
@endsection