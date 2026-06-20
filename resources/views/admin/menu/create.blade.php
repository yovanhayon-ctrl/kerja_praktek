@extends('admin.layouts.admin')

@section('title', 'Admin Create Menu')

@section('content')
<div class="container-fluid py-2 py-sm-4 px-1 px-sm-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Header Layout --}}
            <div class="d-flex align-items-center gap-3 mb-4 px-2">
                <a href="{{ route('admin.menu.index') }}" class="btn btn-light shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <div>
                    <h4 class="fw-bold mb-0" style="font-size: 1.15rem; sm:font-size: 1.25rem;">Tambah Menu Baru</h4>
                    <p class="text-muted small mb-0" style="font-size: 0.75rem;">Lengkapi detail informasi untuk menu baru Anda</p>
                </div>
            </div>

            {{-- Form dibungkus di luar ROW agar struktur HTML valid & semua input terkirim sempurna --}}
            <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row g-4">
                    {{-- Kolom Kiri: Detail Informasi Menu --}}
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm p-3 p-sm-4" style="border-radius: 20px;">
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold" style="font-size: 0.85rem;">Nama Menu <span class="text-danger">*</span></label>
                                <input type="text" name="nama_menu" class="form-control @error('nama_menu') is-invalid @enderror" 
                                       placeholder="Masukkan nama menu..." value="{{ old('nama_menu') }}" required>
                                @error('nama_menu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold" style="font-size: 0.85rem;">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                          rows="4" placeholder="Jelaskan detail menu...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-sm-6 mb-4 mb-sm-0">
                                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Harga (Rp) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted" style="font-size: 0.85rem;">Rp</span>
                                        <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror border-start-0" 
                                               placeholder="0" value="{{ old('harga') }}" required>
                                    </div>
                                    @error('harga') <div class="text-danger small mt-1" style="font-size: 0.75rem;">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Kategori <span class="text-danger">*</span></label>
                                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                        <option value="" selected disabled>Pilih Kategori</option>
                                        <option value="Makanan" {{ old('kategori') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                                        <option value="Minuman" {{ old('kategori') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                                        <option value="Paketan" {{ old('kategori') == 'Paketan' ? 'selected' : '' }}>Paketan</option>
                                    </select>
                                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Kolom Kanan: Upload Gambar --}}
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm p-3 p-sm-4 h-100 d-flex flex-column justify-content-between" style="border-radius: 20px;">
                            <div>
                                <label class="form-label fw-bold mb-3" style="font-size: 0.85rem;">Gambar Menu <span class="text-danger">*</span></label>
                                
                                <div class="upload-area border border-2 rounded-4 p-4 text-center mb-3 transition-all" style="border-style: dashed !important; background-color: #f8fafc;">
                                    <input type="file" name="gambar" id="gambarInput" class="d-none" accept="image/*" required>
                                    <label for="gambarInput" class="w-100 m-0" style="cursor: pointer;">
                                        <div id="previewPlaceholder">
                                            <i class="bi bi-cloud-arrow-up text-muted" style="font-size: 2.5rem;"></i>
                                            <p class="small text-muted mt-2 mb-0" style="font-size: 0.75rem;">Klik untuk unggah gambar<br><b class="text-secondary">PNG, JPG up to 2MB</b></p>
                                        </div>
                                        <img id="previewImage" class="img-fluid rounded-3 d-none w-100 shadow-sm" style="max-height: 200px; object-fit: cover;">
                                    </label>
                                </div>
                                
                                @error('gambar') <div class="text-danger small mb-3" style="font-size: 0.75rem;">{{ $message }}</div> @enderror
                            </div>

                            <div class="alert alert-success border-0 small mb-0 mt-2" style="background-color: #f0fdf4; color: #166534; font-size: 0.725rem;">
                                <div class="fw-bold mb-1"><i class="bi bi-info-circle me-1"></i> Status Otomatis</div>
                                <span class="text-muted d-block" style="line-height: 1.3;">Menu baru yang Anda buat akan langsung berstatus <b class="text-success">Tersedia</b> di sistem.</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi: Diletakkan penuh di paling bawah agar alur pengisian sejalan --}}
                    <div class="col-12 text-end d-flex flex-column-reverse flex-sm-row justify-content-end gap-2 mt-2 px-3">
                        <a href="{{ route('admin.menu.index') }}" class="btn btn-light px-4 py-2 fw-bold text-secondary" style="border-radius: 10px; font-size: 0.8rem;">Batal</a>
                        <button type="submit" class="btn btn-success px-4 py-2 fw-bold shadow-sm" style="background-color: #2d6a4f; border-color: #2d6a4f; border-radius: 10px; font-size: 0.8rem;">
                            <i class="bi bi-check-lg me-1"></i> Simpan Menu
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview Gambar Otomatis (Aman & Ringan)
    const gambarInput = document.getElementById('gambarInput');
    const previewImage = document.getElementById('previewImage');
    const previewPlaceholder = document.getElementById('previewPlaceholder');

    gambarInput.onchange = () => {
        const [file] = gambarInput.files;
        if (file) {
            previewImage.src = URL.createObjectURL(file);
            previewImage.classList.remove('d-none');
            previewPlaceholder.classList.add('d-none');
        }
    }
</script>
<style>
    .upload-area:hover {
        background-color: #f1f5f9 !important;
        border-color: #2d6a4f !important;
    }
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
</style>
@endpush