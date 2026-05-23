@extends('admin.layouts.admin')

@section('title', 'Admin Create Menu')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('admin.menu.index') }}" class="btn btn-white shadow-sm rounded-circle p-2" style="width: 40px; height: 40px;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="fw-bold mb-0">Tambah Menu Baru</h4>
                    <p class="text-muted small mb-0">Lengkapi detail informasi untuk menu baru Anda</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px;">
                        <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Nama Menu <span class="text-danger">*</span></label>
                                <input type="text" name="nama_menu" class="form-control @error('nama_menu') is-invalid @enderror" 
                                       placeholder="Masukkan nama menu..." value="{{ old('nama_menu') }}" required>
                                @error('nama_menu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                          rows="4" placeholder="Jelaskan detail menu...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Harga (Rp) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">Rp</span>
                                        <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror border-start-0" 
                                               placeholder="0" value="{{ old('harga') }}" required>
                                    </div>
                                    @error('harga') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                        <option value="" selected disabled>Pilih Kategori</option>
                                        <option value="Makanan" {{ old('kategori') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                                        <option value="Minuman" {{ old('kategori') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                                        <option value="Paket" {{ old('kategori') == 'Paket' ? 'selected' : '' }}>Paket</option>
                                    </select>
                                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.menu.index') }}" class="btn btn-light px-4">Reset</a>
                                <button type="submit" class="btn btn-success px-4" style="background-color: #2d6a4f; border-radius: 10px;">
                                    Simpan Menu
                                </button>
                            </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 20px;">
                        <label class="form-label fw-bold mb-3">Gambar Menu</label>
                        
                        <div class="upload-area border border-2 border-dashed rounded-4 p-4 text-center mb-3" style="border-style: dashed !important; background-color: #f8f9fa;">
                            <input type="file" name="gambar" id="gambarInput" class="d-none" accept="image/*" required>
                            <label for="gambarInput" class="cursor-pointer">
                                <div id="previewPlaceholder">
                                    <i class="bi bi-cloud-arrow-up text-muted" style="font-size: 3rem;"></i>
                                    <p class="small text-muted mt-2">Klik untuk unggah atau seret gambar<br>PNG, JPG up to 2MB</p>
                                </div>
                                <img id="previewImage" class="img-fluid rounded-3 d-none" style="max-height: 250px; object-fit: cover;">
                            </label>
                        </div>
                        
                        @error('gambar') <div class="text-danger small mb-3">{{ $message }}</div> @enderror

                        <div class="alert alert-info border-0 small" style="background-color: #f0fdf4; color: #166534;">
                            <i class="bi bi-info-circle me-1"></i> Status Ketersediaan
                            <p class="mb-0 mt-1 x-small text-muted">Menu akan langsung tampil di aplikasi setelah disimpan.</p>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview Gambar Otomatis
    const gambarInput = document.getElementById('gambarInput');
    const previewImage = document.getElementById('previewImage');
    const previewPlaceholder = document.getElementById('previewPlaceholder');

    gambarInput.onchange = evt => {
        const [file] = gambarInput.files;
        if (file) {
            previewImage.src = URL.createObjectURL(file);
            previewImage.classList.remove('d-none');
            previewPlaceholder.classList.add('d-none');
        }
    }
</script>
<style>
    .cursor-pointer { cursor: pointer; }
    .border-dashed { border-color: #dee2e6 !important; }
</style>
@endpush