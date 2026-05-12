@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Daftar Menu</h4>
            <p class="text-muted small">Kelola semua menu makanan dan minuman restoran Anda</p>
        </div>
        <a href="{{ route('admin.menu.create') }}" class="btn btn-success px-4" style="background-color: #2d6a4f; border-radius: 10px;">
            <i class="bi bi-plus-lg me-2"></i> Add Menu
        </a>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Gambar</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status</th> <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                    <tr>
                        <td class="ps-4">
                            <img src="{{ asset('storage/' . $menu->gambar) }}" class="rounded-3" style="width: 60px; height: 60px; object-fit: cover;">
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $menu->nama_menu }}</div>
                            <div class="text-muted x-small text-truncate" style="max-width: 200px;">{{ $menu->deskripsi }}</div>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $menu->kategori }}</span></td>
                        <td class="fw-bold">Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                        
                        <td>
                            <form action="{{ route('admin.menu.toggle', $menu->id) }}" method="POST" id="status-form-{{ $menu->id }}">
                                @csrf
                                @method('PATCH')
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="switch{{ $menu->id }}"
                                           onchange="document.getElementById('status-form-{{ $menu->id }}').submit()"
                                           {{ $menu->status ? 'checked' : '' }}
                                           style="cursor: pointer;">
                                    <label class="form-check-label small ms-1 {{ $menu->status ? 'text-success' : 'text-danger' }}" for="switch{{ $menu->id }}">
                                        {{ $menu->status ? 'Tersedia' : 'Habis' }}
                                    </label>
                                </div>
                            </form>
                        </td>

                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.menu.edit', $menu->id) }}" class="btn btn-sm btn-outline-primary rounded-3">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.menu.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection