@extends('admin.layouts.admin')

@section('title', 'Admin Pesan')
@section('page-title', 'Pesan Masuk')
@section('page-subtitle', 'Kelola pesan dan masukan dari pelanggan RestoKu')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-white py-3 border-0 mt-2">
            <h5 class="fw-bold text-dark mb-0">Daftar Pesan Masuk</h5>
            <small class="text-muted">Menerima kritik, saran, dan pesan dari halaman Kontak Kami</small>
        </div>
        <div class="card-body">
            
            <div class="table-responsive">
                <table class="table table-hover align-middle" style="font-size: 0.875rem;">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th style="width: 5%; padding: 12px;">No</th>
                            <th style="width: 15%;">Nama</th>
                            <th style="width: 20%;">Email</th>
                            <th style="width: 35%;">Isi Pesan</th>
                            <th style="width: 10%;">Status</th>
                            <th style="width: 15%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesans as $index => $p)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px;">{{ $pesans->firstItem() + $index }}</td>
                            <td class="fw-semibold text-dark">{{ $p->nama }}</td>
                            <td>
                                <a href="mailto:{{ $p->email }}" class="text-decoration-none text-primary fw-medium">
                                    {{ $p->email }}
                                </a>
                            </td>
                            <td>
                                <div class="text-secondary text-truncate" 
                                     style="max-width: 320px; cursor: pointer;" 
                                     data-bs-toggle="modal" 
                                     data-bs-target="#modalPesan{{ $p->id }}"
                                     title="Klik untuk membaca pesan lengkap">
                                    {{ trim($p->pesan) }}
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                    <i class="bi bi-clock me-1"></i>{{ $p->created_at->diffForHumans() }}
                                </small>

                                <div class="modal fade" id="modalPesan{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
                                            <div class="modal-header border-0 bg-light py-3" style="border-radius: 12px 12px 0 0;">
                                                <h6 class="modal-title fw-bold text-dark">
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
                                                    <div class="text-dark fw-bold" style="font-size: 1rem;">{{ $p->nama }}</div>
                                                    <div class="text-muted small">{{ $p->email }}</div>
                                                    <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                                        <i class="bi bi-calendar3 me-1"></i>{{ $p->created_at->format('d M Y, H:i') }} WIB
                                                    </div>
                                                </div>
                                                <div class="p-3 bg-light rounded text-secondary text-start" style="font-size: 0.9rem; white-space: pre-wrap; line-height: 1.6; text-align: left;">{{ trim($p->pesan) }}</div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                @if($p->status == 'BELUM_DIBACA')
                                                    <form id="formBacaModal{{ $p->id }}" action="{{ route('admin.pesan.baca', $p->id) }}" method="POST" class="d-inline mb-0">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-primary px-4" style="border-radius: 8px;">
                                                            <i class="bi bi-check2-all me-1"></i> Tutup & Tandai Dibaca
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-secondary px-4" style="border-radius: 8px;" data-bs-dismiss="modal">Tutup</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                            <td>
                                @if($p->status == 'BELUM_DIBACA')
                                    <span class="badge px-2 py-1.5" style="background-color: #fef3c7; color: #d97706; font-size: 0.75rem; font-weight: 500; border-radius: 6px;">
                                        Belum Dibaca
                                    </span>
                                @else
                                    <span class="badge px-2 py-1.5" style="background-color: #dcfce7; color: #15803d; font-size: 0.75rem; font-weight: 500; border-radius: 6px;">
                                        Sudah Dibaca
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2 mx-auto" style="min-width: 120px;">
                                    @if($p->status == 'BELUM_DIBACA')
                                        <button type="button" class="btn btn-sm btn-light text-primary border" 
                                                style="border-radius: 8px; font-weight: 500; white-space: nowrap;" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalPesan{{ $p->id }}"
                                                title="Buka dan Baca Pesan">
                                            <i class="bi bi-envelope-open me-1"></i> Baca
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-light text-secondary border" 
                                                style="border-radius: 8px; font-weight: 500; white-space: nowrap;" 
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
                                <i class="bi bi-envelope-open d-block fs-2 mb-2 text-secondary"></i>
                                Kotak masuk pesan masih kosong.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination & Entry Info --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <span class="text-muted" style="font-size: 0.8rem;">
                    Showing {{ $pesans->firstItem() ?? 0 }} to {{ $pesans->lastItem() ?? 0 }} of {{ $pesans->total() }} entries
                </span>
                <div>
                    {{ $pesans->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection