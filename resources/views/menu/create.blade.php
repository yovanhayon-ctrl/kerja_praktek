@extends('layouts.app')

@section('title', 'Tambah Menu - RestoKu')

@section('content')
<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="{{ url('/menu') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-bold mb-0">Tambah Menu Baru</h4>
            </div>

            {{-- Pesan error validasi --}}
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Pesan sukses --}}
            @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            <div class="card border-0 shadow-sm p-4">
                <form action="/menu/store" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Nama Menu --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Menu <span class="text-danger">*</span></label>
                        <input type="text"
                               name="nama_menu"
                               class="form-control @error('nama_menu') is-invalid @enderror"
                               placeholder="Contoh: Nasi Goreng Spesial"
                               value="{{ old('nama_menu') }}"
                               required>
                        @error('nama_menu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi"
                                  class="form-control @error('deskripsi') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Deskripsi singkat menu...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Harga --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number"
                                   name="harga"
                                   class="form-control @error('harga') is-invalid @enderror"
                                   placeholder="Contoh: 25000"
                                   value="{{ old('harga') }}"
                                   min="0"
                                   required>
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori"
                                class="form-select @error('kategori') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Makanan" {{ old('kategori') == 'Makanan' ? 'selected' : '' }}>
                                Makanan
                            </option>
                            <option value="Minuman" {{ old('kategori') == 'Minuman' ? 'selected' : '' }}>
                                Minuman
                            </option>
                            <option value="Paketan" {{ old('kategori') == 'Paketan' ? 'selected' : '' }}>
                                Paketan
                            </option>
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Upload Gambar --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Gambar Menu</label>
                        <input type="file"
                               name="gambar"
                               id="inputGambar"
                               class="form-control @error('gambar') is-invalid @enderror"
                               accept="image/*">
                        <div class="form-text">Format: JPG, PNG, JPEG. Maks 2MB.</div>
                        @error('gambar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Preview gambar --}}
                        <div id="previewWrapper" class="mt-2 d-none">
                            <img id="previewGambar" src="#" alt="Preview"
                                 class="img-thumbnail"
                                 style="max-height: 180px; object-fit: cover;">
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ url('/menu') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check-lg"></i> Simpan Menu
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview gambar sebelum upload
    document.getElementById('inputGambar').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('previewGambar').src = e.target.result;
                document.getElementById('previewWrapper').classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush