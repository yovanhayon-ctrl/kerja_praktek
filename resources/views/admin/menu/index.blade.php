@extends('admin.layouts.admin')

@section('title', 'Admin Menu')

@section('content')
<div class="container-fluid py-2 py-sm-4 px-1 px-sm-3">
    {{-- Header Layout Responsive --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4 px-2">
        <div>
            <h4 class="fw-bold mb-0" style="font-size: 1.15rem; sm:font-size: 1.25rem;">Daftar Menu</h4>
            <p class="text-muted small mb-0" style="font-size: 0.75rem;">Kelola semua menu makanan dan minuman restoran Anda</p>
        </div>
        {{-- Tombol Add Menu otomatis melebar penuh di HP agar mudah diklik --}}
        <a href="{{ route('admin.menu.create') }}" class="btn btn-success px-4 py-2 fw-bold w-100 w-sm-auto text-center shadow-sm" style="background-color: #2d6a4f; border-color: #2d6a4f; border-radius: 10px; font-size: 0.8rem;">
            <i class="bi bi-plus-lg me-2"></i> Add Menu
        </a>
    </div>

    {{-- Mengganti .admin-card dengan standarisasi Card Bootstrap agar bayangan & border radius stabil --}}
    <div class="card border-0 shadow-sm p-2 p-sm-4" style="border-radius: 15px;">
        <div class="table-responsive w-100" style="border: none;">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-muted" style="font-size: 0.65rem; white-space: nowrap;">
                        <th class="ps-3">Gambar</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status</th> 
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.75rem; white-space: nowrap;">
                    @foreach($menus as $menu)
                    <tr>
                        <td class="ps-3">
                            <img src="{{ asset('storage/' . $menu->gambar) }}" class="rounded-3 shadow-sm" style="width: 55px; height: 55px; object-fit: cover;">
                        </td>
                        <td>
                            <div class="fw-bold text-dark text-wrap" style="max-width: 180px;">{{ $menu->nama_menu }}</div>
                            {{-- Mengamankan deskripsi agar tidak merusak layout jika teks terlalu panjang --}}
                            <div class="text-muted text-wrap text-truncate" style="max-width: 220px; font-size: 0.65rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; white-space: normal;">
                                {{ $menu->deskripsi }}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.6rem;">{{ $menu->kategori }}</span>
                        </td>
                        <td class="fw-bold text-dark">Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                        
                        <td>
                            <form action="{{ route('admin.menu.toggle', $menu->id) }}" method="POST" id="status-form-{{ $menu->id }}">
                                @csrf
                                @method('PATCH')
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="switch{{ $menu->id }}"
                                           onchange="document.getElementById('status-form-{{ $menu->id }}').submit()"
                                           {{ $menu->status ? 'checked' : '' }}
                                           style="cursor: pointer;">
                                    <label class="form-check-label fw-bold ms-1 {{ $menu->status ? 'text-success' : 'text-danger' }}" for="switch{{ $menu->id }}" style="font-size: 0.7rem; white-space: nowrap;">
                                        {{ $menu->status ? 'Tersedia' : 'Habis' }}
                                    </label>
                                </div>
                            </form>
                        </td>

                        <td class="text-end pe-3">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.menu.edit', $menu->id) }}" class="btn btn-sm btn-outline-primary rounded-3 px-2 py-1" title="Edit Menu">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.menu.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 px-2 py-1" title="Hapus Menu">
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
        
        {{-- PAGINATION YANG SUDAH DIPERBAIKI --}}
        @if($menus->hasPages())
        <div class="mt-4">
            {{ $menus->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
        
    </div>
</div>
@endsection

{{-- STYLE KHUSUS UNTUK MERAPIKAN PAGINATION --}}
@push('styles')
<style>
    /* Styling pagination agar serasi dan rapi */
    .pagination { margin-bottom: 0; }
    .pagination .page-item.active .page-link { background-color: #198754; border-color: #198754; color: #fff; }
    .pagination .page-link { color: #198754; border-radius: 6px; margin: 0 3px; border: 1px solid #dee2e6;}
    .pagination .page-link:hover { color: #fff; background-color: #198754; border-color: #198754; }
</style>
@endpush