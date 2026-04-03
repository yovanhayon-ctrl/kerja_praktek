<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu</title>

    {{-- Vite (WAJIB) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<div class="container mt-5">

    <h1 class="mb-4 text-center">Tambah Menu Rumah Makan</h1>

    <div class="card shadow p-4">
        <form action="/menu/store" method="POST">
            @csrf

            {{-- Nama Menu --}}
            <div class="mb-3">
                <label class="form-label">Nama Menu</label>
                <input type="text" name="nama_menu" class="form-control" placeholder="Masukkan nama menu" required>
            </div>

            {{-- Deskripsi --}}
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi menu"></textarea>
            </div>

            {{-- Harga --}}
            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" placeholder="Masukkan harga" required>
            </div>

            {{-- Kategori --}}
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="Makanan">Makanan</option>
                    <option value="Minuman">Minuman</option>
                </select>
            </div>

            {{-- Button --}}
            <div class="d-flex justify-content-between">
                <a href="/menu" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-success">Simpan Menu</button>
            </div>

        </form>
    </div>

    {{-- TEST Bootstrap --}}
    <div class="text-center mt-4">
        <button class="btn btn-primary">Test Bootstrap</button>
    </div>

</div>

</body>
</html>