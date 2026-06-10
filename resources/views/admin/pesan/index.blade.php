@extends('admin.layouts.admin')

@section('title', 'Admin Pesan')
@section('page-title', 'Pesan Masuk')
@section('page-subtitle', 'Kelola pesan dan masukan dari pelanggan RestoKu')

@section('content')
<div class="container-fluid py-3 py-sm-4 px-2 px-sm-3">
    {{-- Header Section --}}
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1" style="font-size: 1.25rem;">Daftar Pesan Masuk</h4>
        <p class="text-muted small mb-0">Menerima kritik, saran, dan masukan dari halaman Kontak Kami</p>
    </div>

    {{-- Main Card Table --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-3 p-sm-4">
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <th style="width: 5%; padding: 12px;" class="ps-3">NO</th>
                            <th style="width: 15%;">NAMA</th>
                            <th style="width: 20%;">EMAIL</th>
                            <th style="width: 35%;">ISI PESAN</th>
                            <th style="width: 10%;" class="text-center">STATUS</th>
                            <th style="width: 15%;" class="text-center pe-3">AKSI</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.85rem;">
                        @forelse($pesans as $index => $p)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td class="ps-3 text-secondary">{{ $pesans->firstItem() + $index }}</td>
                            <td class="fw-bold text-dark">{{ $p->nama }}</td>
                            <td>
                                <a href="mailto:{{ $p->email }}" class="text-decoration-none text-primary fw-medium">
                                    <i class="bi bi-envelope me-1" style="font-size: 0.8rem;"></i>{{ $p->email }}
                                </a>
                            </td>
                            <td>
                                <div class="text-secondary text-truncate" 
                                     style="max-width: 320px; cursor: pointer; font-size: 0.825rem;" 
                                     data-bs-toggle="modal" 
                                     data-bs-target="#modalPesan{{ $p->id }}"
                                     title="Klik untuk membaca pesan lengkap">
                                    {{ trim($p->pesan) }}
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.725rem;">
                                    <i class="bi bi-clock me-1"></i>{{ $p->created_at->diffForHumans() }}
                                </small>

                                {{-- Modal Detail Pesan --}}
                                <div class="modal fade" id="modalPesan{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                            <div class="modal-header border-0 bg-light py-3" style="border-radius: 16px 16px 0 0;">
                                                <h6 class="modal-title fw-bold text-dark mb-0">
                                                    <i class="bi bi-envelope-paper me-2 text-primary"></i>Detail Pesan Masuk
                                                </h6>
                                                @if($p->status == 'BELUM_DIBACA')
                                                    <button type="button" class="btn-close shadow-none" onclick="document.getElementById('formBacaModal{{ $p->id }}').submit();" aria-label="Close"></button>
                                                @else
                                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                @endif
                                            </div>
                                            <div class="modal-body p-4 text-start">
                                                <div class="mb-3 pb-2 border-bottom">
                                                    <div class="text-dark fw-bold" style="font-size: 1.05rem;">{{ $p->nama }}</div>
                                                    <div class="text-muted small">{{ $p->email }}</div>
                                                    <div class="text-muted mt-1" style="font-size: 0.725rem;">
                                                        <i class="bi bi-calendar3 me-1"></i>{{ $p->created_at->format('d M Y, H:i') }} WIB
                                                    </div>
                                                </div>
                                                <div class="p-3 rounded-3 text-secondary bg-light" style="font-size: 0.875rem; white-space: pre-wrap; line-height: 1.6; text-align: left;">{{ trim($p->pesan) }}</div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                @if($p->status == 'BELUM_DIBACA')
                                                    <form id="formBacaModal{{ $p->id }}" action="{{ route('admin.pesan.baca', $p->id) }}" method="POST" class="d-inline mb-0">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-primary px-4 py-2" style="border-radius: 8px; font-weight: 500;">
                                                            <i class="bi bi-check2-all me-1"></i> Tutup & Tandai Dibaca
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-secondary px-4 py-2" style="border-radius: 8px; font-weight: 500;" data-bs-dismiss="modal">Tutup</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($p->status == 'BELUM_DIBACA')
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1.5 fw-semibold" style="font-size: 0.725rem; border-radius: 6px;">
                                        Belum Dibaca
                                    </span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success px-2.5 py-1.5 fw-semibold" style="font-size: 0.725rem; border-radius: 6px;">
                                        Sudah Dibaca
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2 mx-auto" style="min-width: 120px;">
                                    @if($p->status == 'BELUM_DIBACA')
                                        <button type="button" class="btn btn-sm btn-light text-primary border" 
                                                style="border-radius: 8px; font-weight: 500; font-size: 0.75rem; white-space: nowrap; padding: 5px 10px;" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalPesan{{ $p->id }}"
                                                title="Buka dan Baca Pesan">
                                            <i class="bi bi-envelope-open me-1"></i> Baca
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-light text-secondary border" 
                                                style="border-radius: 8px; font-weight: 500; font-size: 0.75rem; white-space: nowrap; padding: 5px 10px;" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalPesan{{ $p->id }}"
                                                title="Lihat Detail Pesan">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </button>
                                    @endif
                                    
                                    <form action="{{ route('admin.pesan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesan ini?')" class="d-inline mb-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border" style="border-radius: 8px; padding: 5px 10px;" title="Hapus Pesan">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-envelope d-block fs-2 mb-2 text-secondary bg-opacity-10"></i>
                                Kotak masuk pesan masih kosong.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination & Entry Info --}}
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 mt-4">
                <span class="text-muted small">
                    Showing {{ $pesans->firstItem() ?? 0 }} to {{ $pesans->lastItem() ?? 0 }} of {{ $pesans->total() }} entries
                </span>
                <div class="mb-0">
                    {{ $pesans->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection