@extends('admin.layouts.admin')

@section('title', 'Admin Reservasi')
@section('page-title', 'Data Reservasi Tempat')
@section('page-subtitle', 'Overview booking meja dan acara dari pelanggan hari ini')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Data Reservasi Tempat</h4>
            <p class="text-muted small mb-0">Overview booking meja dan acara dari pelanggan hari ini</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm small">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle" style="font-size: 0.9rem;">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>NAMA PELANGGAN</th>
                            <th>WHATSAPP</th>
                            <th>MEJA</th>
                            <th>WAKTU & TANGGAL</th>
                            <th>CATATAN</th>
                            <th>STATUS</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservasis as $res)
                        <tr>
                            <td class="fw-bold text-dark">{{ $res->nama_lengkap }}</td>
                            <td>
                                <a href="https://wa.me/{{ $res->whatsapp }}" target="_blank" class="text-decoration-none text-success fw-semibold">
                                    <i class="bi bi-whatsapp me-1"></i>{{ $res->whatsapp }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-outline-dark border text-dark py-1.5 px-3">
                                    Meja {{ str_replace(',', ', ', $res->kumpulan_meja) }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($res->waktu_reservasi)->format('H:i') }} WIB</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($res->waktu_reservasi)->format('d M Y') }}</small>
                            </td>
                            <td>
                                @if($res->catatan)
                                    <span class="text-muted d-inline-block text-truncate fw-medium" 
                                          style="max-width: 150px; cursor: pointer; text-decoration: none;" 
                                          data-bs-toggle="modal" 
                                          data-bs-target="#modalCatatan{{ $res->id }}"
                                          title="Klik untuk lihat lengkap">
                                        {{ $res->catatan }}
                                    </span>

                                    <div class="modal fade" id="modalCatatan{{ $res->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow" style="border-radius: 12px;">
                                                <div class="modal-header border-0 bg-light py-3" style="border-radius: 12px 12px 0 0;">
                                                    <h6 class="modal-title fw-bold text-dark">
                                                        <i class="bi bi-chat-left-text me-2 text-success"></i>Catatan Pelanggan
                                                    </h6>
                                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3 small text-muted">
                                                        <i class="bi bi-person me-1"></i> <strong>{{ $res->nama_lengkap }}</strong> | Meja {{ str_replace(',', ', ', $res->kumpulan_meja) }}
                                                    </div>
                                                    <div class="p-3 bg-light rounded text-dark border-start border-success border-3" 
                                                         style="font-size: 0.95rem; white-space: pre-wrap; line-height: 1.6; text-align: left;">
                                                        {{ $res->catatan }}
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-sm btn-secondary px-3" style="border-radius: 8px;" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($res->status == 'PENDING')
                                    <span class="badge bg-warning text-dark py-1.5 px-2.5">PENDING</span>
                                @elseif($res->status == 'DISETUJUI')
                                    <span class="badge bg-info text-white py-1.5 px-2.5">DISETUJUI</span>
                                @elseif($res->status == 'SELESAI')
                                    <span class="badge bg-success text-white py-1.5 px-2.5">SELESAI</span>
                                @else
                                    <span class="badge bg-danger text-white py-1.5 px-2.5">CANCELLED</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-gear me-1"></i> Atur
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li>
                                            <form action="{{ route('admin.reservasi.updateStatus', $res->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="DISETUJUI">
                                                <button type="submit" class="dropdown-item text-info"><i class="bi bi-check2-circle me-2"></i>Setujui Booking</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.reservasi.updateStatus', $res->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="SELESAI">
                                                <button type="submit" class="dropdown-item text-success"><i class="bi bi-calendar-check me-2"></i>Selesai / Meja Ready</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.reservasi.updateStatus', $res->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="DIBATALKAN">
                                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-x-circle me-2"></i>Batalkan</button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        
                                        <li>
                                            @php
                                                // Template pesan teks WhatsApp
                                                $pesanWA = "Halo Kak " . $res->nama_lengkap . ",\n\n" .
                                                           "Kami dari *RM Saung Tiga* ingin mengonfirmasi status booking tempat Anda:\n\n" .
                                                           "• *Meja* : Meja " . str_replace(',', ', ', $res->kumpulan_meja) . "\n" .
                                                           "• *Waktu* : " . \Carbon\Carbon::parse($res->waktu_reservasi)->format('d M Y (H:i)') . " WIB\n" .
                                                           "• *Status* : *" . ($res->status == 'PENDING' ? 'MENUNGGU KONFIRMASI' : $res->status) . "*\n\n" .
                                                           "Silakan datang sesuai jadwal atau Anda bisa mengecek status berkala langsung di website resmi kami.\n" .
                                                           "Terima kasih Banyak, Kak!";
                                                
                                                $urlWA = "https://wa.me/" . preg_replace('/[^0-9]/', '', $res->whatsapp) . "?text=" . urlencode($pesanWA);
                                            @endphp
                                            <a href="{{ $urlWA }}" target="_blank" class="dropdown-item text-success fw-bold">
                                                <i class="bi bi-send-check-fill me-2"></i>Kirim Notif WA
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data reservasi masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection