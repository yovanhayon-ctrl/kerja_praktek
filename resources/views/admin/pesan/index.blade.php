@extends('admin.layouts.admin')

@section('title', 'Pesan Masuk')
@section('page-title', 'Pesan Masuk')
@section('page-subtitle', 'Kelola pesan dan masukan dari pelanggan RestoKu')

@section('content')
<div class="container-fluid">
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
                                <a href="mailto:{{ $p->email }}" class="text-decoration-none text-primary">
                                    {{ $p->email }}
                                </a>
                            </td>
                            <td>
                                <p class="mb-0 text-wrap text-secondary" style="max-width: 350px;">{{ $p->pesan }}</p>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-clock me-1"></i>{{ $p->created_at->diffForHumans() }}
                                </small>
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
                                        <form action="{{ route('admin.pesan.baca', $p->id) }}" method="POST" class="d-inline mb-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-light text-primary border" style="border-radius: 8px; font-weight: 500; white-space: nowrap;" title="Tandai Sudah Dibaca">
                                                <i class="bi bi-check2-all me-1"></i> Baca
                                            </button>
                                        </form>
                                    @else
                                        <div style="width: 73px;" class="d-none d-md-block"></div>
                                    @endif
                                    
                                    <form action="{{ route('admin.pesan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesan ini?')" class="d-inline mb-0">
                                        @csrf
                                        @method('DELETE')
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