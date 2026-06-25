@extends('layouts.app')

@section('title', 'Checkout - RestoKu')

@section('content')
<div class="container py-5">

    {{-- Judul --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ url('/cart') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Konfirmasi Pesanan</h4>
    </div>

    {{-- Alert jika cart kosong --}}
    <div id="alertKosong" class="alert alert-warning d-none">
        <i class="bi bi-exclamation-triangle"></i>
        Keranjang kamu kosong. <a href="{{ url('/menu') }}" class="alert-link">Pesan menu dulu</a>.
    </div>

    <div class="row g-4" id="checkoutContent">

        {{-- FORM CHECKOUT --}}
        <div class="col-md-7">
            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold mb-4">
                    <i class="bi bi-person-fill text-danger me-2"></i>Informasi Pemesan
                </h6>

                {{-- Nama --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Nama Pemesan <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           id="inputNama"
                           class="form-control"
                           placeholder="Masukkan nama kamu"
                           required>
                </div>

                {{-- No Meja --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Nomor Meja <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-table text-danger"></i>
                        </span>
                        
                        <select id="inputMeja" class="form-select" required>
                            <option value="" selected disabled>-- Pilih Nomor Meja --</option>
                            @for($i = 1; $i <= 30; $i++)
                                @if(in_array($i, $mejaTerboking ?? []))
                                    <option value="{{ $i }}" disabled class="bg-light text-muted">
                                        Meja {{ $i }} (Sudah Terisi)
                                    </option>
                                @else
                                    <option value="{{ $i }}">
                                        Meja {{ $i }} (Kosong)
                                    </option>
                                @endif
                            @endfor
                        </select>

                    </div>
                    <div class="form-text text-danger" id="mejaError" style="display: none;">Wajib memilih meja yang tersedia!</div>
                    <div class="form-text">Pilihan meja yang berwarna abu-abu berarti sedang digunakan pelanggan lain atau sudah dibooking.</div>
                </div>

                {{-- Catatan Tambahan --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Catatan Tambahan</label>
                    <textarea id="inputCatatan"
                              class="form-control"
                              rows="3"
                              placeholder="Contoh: tidak pakai sambal, es sedikit, dll."></textarea>
                </div>

                <hr>

                {{-- Metode Pembayaran --}}
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-credit-card text-danger me-2"></i>Metode Pembayaran
                </h6>

                <div class="card border-danger bg-danger bg-opacity-10 p-3 mb-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-danger rounded d-flex align-items-center justify-content-center"
                             style="width:42px; height:42px;">
                            <i class="bi bi-cash-coin text-white fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="fw-semibold mb-0">Bayar via kasir</p>
                            <small class="text-muted">Pembayaran dilakukan langsung di kasir</small>
                        </div>
                        <i class="bi bi-check-circle-fill text-danger fs-5"></i>
                    </div>
                </div>
                <div class="form-text mb-3">
                    <i class="bi bi-info-circle"></i>
                    Saat ini hanya tersedia metode pembayaran Melalui Kasir Secara Langsung
                </div>

            </div>
        </div>

        {{-- RINGKASAN PESANAN --}}
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-bag text-danger me-2"></i>Ringkasan Pesanan
                    </h6>

                    {{-- List item dari cart --}}
                    <div id="ringkasanItems"></div>

                    <hr>

                    {{-- Total --}}
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Total Item</span>
                        <span id="ringkasanTotalItem" class="fw-semibold">0 item</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Total Bayar</span>
                        <span id="ringkasanTotalHarga" class="fw-bold text-danger fs-5">Rp 0</span>
                    </div>

                    {{-- Tombol Pesan --}}
                    <div class="d-grid">
                        <button class="btn btn-danger btn-lg" id="btnPesan">
                            <i class="bi bi-bag-check"></i> Konfirmasi & Pesan
                        </button>
                    </div>

                    <p class="text-muted small text-center mt-2 mb-0">
                        <i class="bi bi-shield-check"></i>
                        Pesanan akan segera diproses setelah dikonfirmasi
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- MODAL KONFIRMASI --}}
<div class="modal fade" id="modalKonfirmasi" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Konfirmasi Pesanan</h6>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:64px; height:64px;">
                        <i class="bi bi-bag-check text-danger fs-3"></i>
                    </div>
                    <h6 class="fw-bold">Pastikan pesanan sudah benar!</h6>
                </div>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">Nama</td>
                        <td class="fw-semibold" id="konfNama">-</td>
                    </tr>
                    <tr>
                        <td class="text-muted">No. Meja</td>
                        <td class="fw-semibold" id="konfMeja">-</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Item</td>
                        <td class="fw-semibold" id="konfItem">-</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Bayar</td>
                        <td class="fw-bold text-danger" id="konfHarga">-</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Pembayaran</td>
                        <td class="fw-semibold">(Bayar di Kasir)</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="bi bi-pencil"></i> Ubah
                </button>
                <form action="{{ url('/checkout/simpan') }}" method="POST" id="formCheckout">
                    @csrf
                    <input type="hidden" name="nama"    id="hiddenNama">
                    <input type="hidden" name="no_meja" id="hiddenMeja">
                    <input type="hidden" name="catatan" id="hiddenCatatan">
                    <input type="hidden" name="items"   id="hiddenItems">
                    <input type="hidden" name="total"   id="hiddenTotal">
                    <input type="hidden" name="metode_pembayaran" value="cash">

                    <button type="button" class="btn btn-danger btn-sm px-4" onclick="submitPesanan()">
                        <i class="bi bi-check-lg"></i> Ya, Pesan Sekarang!
                    </button>
                </form>
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

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    function renderRingkasan() {
        const cart = getCart();

        if (cart.length === 0) {
            document.getElementById('alertKosong').classList.remove('d-none');
            document.getElementById('checkoutContent').classList.add('d-none');
            return;
        }

        const totalQty   = cart.reduce((sum, i) => sum + i.qty, 0);
        const totalHarga = cart.reduce((sum, i) => sum + (i.harga * i.qty), 0);

        document.getElementById('ringkasanTotalItem').textContent  = totalQty + ' item';
        document.getElementById('ringkasanTotalHarga').textContent = formatRupiah(totalHarga);

        let html = '';
        cart.forEach(item => {
            html += `
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <p class="mb-0 fw-semibold small">${item.nama}</p>
                    <small class="text-muted">${item.qty} x ${formatRupiah(item.harga)}</small>
                </div>
                <span class="fw-semibold small text-danger">${formatRupiah(item.harga * item.qty)}</span>
            </div>`;
        });

        document.getElementById('ringkasanItems').innerHTML = html;
    }

    // Tombol Konfirmasi & Pesan
    document.getElementById('btnPesan').addEventListener('click', function () {
        const nama = document.getElementById('inputNama').value.trim();
        const mejaInput = document.getElementById('inputMeja');
        const meja = mejaInput.value; // Dapatkan value dari select dropdown
        const cart = getCart();

        // Validasi Nama
        if (!nama) {
            document.getElementById('inputNama').focus();
            document.getElementById('inputNama').classList.add('is-invalid');
            return;
        }
        
        // PERBAIKAN: Validasi nomor meja dropdown
        if (!meja) {
            mejaInput.focus();
            mejaInput.classList.add('is-invalid');
            document.getElementById('mejaError').style.display = 'block';
            return;
        } else {
            mejaInput.classList.remove('is-invalid');
            document.getElementById('mejaError').style.display = 'none';
        }

        if (cart.length === 0) {
            alert('Keranjang kosong!');
            return;
        }

        const totalQty   = cart.reduce((sum, i) => sum + i.qty, 0);
        const totalHarga = cart.reduce((sum, i) => sum + (i.harga * i.qty), 0);
        const catatan    = document.getElementById('inputCatatan').value;

        // Isi modal konfirmasi
        document.getElementById('konfNama').textContent  = nama;
        document.getElementById('konfMeja').textContent  = 'Meja ' + meja;
        document.getElementById('konfItem').textContent  = totalQty + ' item';
        document.getElementById('konfHarga').textContent = formatRupiah(totalHarga);

        // Isi hidden input form
        document.getElementById('hiddenNama').value    = nama;
        document.getElementById('hiddenMeja').value    = meja;
        document.getElementById('hiddenCatatan').value = catatan;
        document.getElementById('hiddenItems').value   = JSON.stringify(cart);
        document.getElementById('hiddenTotal').value   = totalHarga;

        new bootstrap.Modal(document.getElementById('modalKonfirmasi')).show();
    });

    function submitPesanan() {
        localStorage.removeItem('cart');
        const badge = document.getElementById('cart-badge');
        if (badge) badge.textContent = 0;
        document.getElementById('formCheckout').submit();
    }

    // Hapus is-invalid saat diketik/dipilih
    document.getElementById('inputNama').addEventListener('input', function () {
        this.classList.remove('is-invalid');
    });
    
    document.getElementById('inputMeja').addEventListener('change', function () {
        this.classList.remove('is-invalid');
        document.getElementById('mejaError').style.display = 'none';
    });

    renderRingkasan();
</script>
@endpush