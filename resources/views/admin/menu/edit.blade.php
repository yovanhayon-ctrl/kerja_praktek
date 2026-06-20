@extends('admin.layouts.admin')

@section('title', 'Admin Edit Menu')

@section('content')
<div class="container-fluid py-2 py-sm-4 px-1 px-sm-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Header Layout Responsive --}}
            <div class="d-flex align-items-center gap-3 mb-4 px-2">
                <a href="{{ route('admin.menu.index') }}" class="btn btn-light shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <div>
                    <h4 class="fw-bold mb-0" style="font-size: 1.15rem; sm:font-size: 1.25rem;">Edit Menu</h4>
                    <p class="text-muted small mb-0" style="font-size: 0.75rem;">Perbarui detail informasi menu <strong class="text-dark">{{ $menu->nama_menu }}</strong></p>
                </div>
            </div>

            {{-- Form dibungkus di luar agar mencakup seluruh input teks dan file gambar secara valid --}}
            <form action="{{ route('admin.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    {{-- Kolom Kiri: Form Detail Menu --}}
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm p-3 p-sm-4" style="border-radius: 20px;">
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold" style="font-size: 0.85rem;">Nama Menu <span class="text-danger">*</span></label>
                                <input type="text" name="nama_menu" class="form-control @error('nama_menu') is-invalid @enderror" 
                                       placeholder="Masukkan nama menu..." value="{{ old('nama_menu', $menu->nama_menu) }}" required>
                                @error('nama_menu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold" style="font-size: 0.85rem;">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                          rows="4" placeholder="Jelaskan detail menu...">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
                                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-sm-6 mb-4 mb-sm-0">
                                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Harga (Rp) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted" style="font-size: 0.85rem;">Rp</span>
                                        <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror border-start-0" 
                                               placeholder="0" value="{{ old('harga', $menu->harga) }}" required>
                                    </div>
                                    @error('harga') <div class="text-danger small mt-1" style="font-size: 0.75rem;">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Kategori <span class="text-danger">*</span></label>
                                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                        <option value="Makanan" {{ old('kategori', $menu->kategori) == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                                        <option value="Minuman" {{ old('kategori', $menu->kategori) == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                                        <option value="Paketan" {{ old('kategori', $menu->kategori) == 'Paketan' ? 'selected' : '' }}>Paketan</option>
                                    </select>
                                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Kolom Kanan: Upload & Preview Gambar --}}
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm p-3 p-sm-4 h-100 d-flex flex-column justify-content-between" style="border-radius: 20px;">
                            <div>
                                <label class="form-label fw-bold mb-3" style="font-size: 0.85rem;">Gambar Menu</label>
                                
                                <div class="upload-area border border-2 rounded-4 p-4 text-center mb-3 transition-all" style="border-style: dashed !important; background-color: #f8fafc;">
                                    <input type="file" name="gambar" id="gambarInput" class="d-none" accept="image/*">
                                    <label for="gambarInput" class="w-100 m-0" style="cursor: pointer;">
                                        {{-- Placeholder otomatis tersembunyi jika gambar bawaan database ada --}}
                                        <div id="previewPlaceholder" class="{{ $menu->gambar ? 'd-none' : '' }}">
                                            <i class="bi bi-cloud-arrow-up text-muted" style="font-size: 2.5rem;"></i>
                                            <p class="small text-muted mt-2 mb-0" style="font-size: 0.75rem;">Klik untuk ganti gambar</p>
                                        </div>
                                        <img id="previewImage" src="{{ asset('storage/' . $menu->gambar) }}" 
                                             class="img-fluid rounded-3 w-100 shadow-sm {{ $menu->gambar ? '' : 'd-none' }}" 
                                             style="max-height: 200px; object-fit: cover;">
                                    </label>
                                </div>
                                
                                <p class="text-muted text-center mb-0 px-2" style="font-size: 0.7rem; line-height: 1.3;">Biarkan kosong jika tidak ingin mengubah gambar lama Anda.</p>
                                @error('gambar') <div class="text-danger small mt-2 text-center" style="font-size: 0.75rem;">{{ $message }}</div> @enderror
                            </div>

                            {{-- Alert Informasi Ringkas --}}
                            <div class="alert alert-warning border-0 small mb-0 mt-3" style="background-color: #fffbeb; color: #b45309; font-size: 0.725rem;">
                                <i class="bi bi-exchanged me-1"></i> Perubahan data akan langsung diterapkan secara realtime pada menu pelanggan.
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi: Diletakkan penuh di bawah agar urutan scroll mobile lebih natural --}}
                    <div class="col-12 text-end d-flex flex-column-reverse flex-sm-row justify-content-end gap-2 mt-2 px-3">
                        <a href="{{ route('admin.menu.index') }}" class="btn btn-light px-4 py-2 fw-bold text-secondary" style="border-radius: 10px; font-size: 0.8rem;">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm text-white" style="background-color: #0f172a; border-color: #0f172a; border-radius: 10px; font-size: 0.8rem;">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
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
    // Preview Gambar Otomatis Tanpa Reload
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
        border-color: #0f172a !important;
    }
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
</style>
@endpush