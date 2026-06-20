@extends('admin.layouts.admin')

@section('title', 'Admin Reservasi')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview performa restoran hari ini')

@section('content')
<div class="container-fluid py-3 py-sm-4 px-2 px-sm-3">
    {{-- Header Layout Responsive --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="font-size: 1.25rem;">Data Reservasi Tempat</h4>
            <p class="text-muted small mb-0">Overview booking meja dan acara dari pelanggan hari ini.</p>
        </div>
    </div>

    {{-- Alert Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm small d-flex align-items-center mb-4" style="border-radius: 8px;" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    {{-- Table Card Layout --}}
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-3 p-sm-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr style="font-size: 0.65rem; letter-spacing: 0.5px;">
                            <th class="ps-3 text-muted fw-bold">NAMA PELANGGAN</th>
                            <th class="text-muted fw-bold">WHATSAPP</th>
                            <th class="text-muted fw-bold">MEJA</th>
                            <th class="text-muted fw-bold">WAKTU & TANGGAL</th>
                            <th class="text-muted fw-bold">CATATAN</th>
                            <th class="text-muted fw-bold">STATUS</th>
                            <th class="text-center text-muted fw-bold">AKSI</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.75rem;">
                        @forelse($reservasis as $index => $res)
                        @php
                            // Ambil ID atau fallback ke index loop agar ID DOM HTML/Modal tidak bentrok/kosong
                            $resId = $res->id ?? $index;

                            // Normalisasi nomor WA (Konversi format lokal 08xx ke internasional 628xx agar link wa.me tidak broken)
                            $rawWhatsapp = $res->whatsapp ?? '';
                            $cleanWhatsapp = preg_replace('/[^0-9]/', '', $rawWhatsapp);
                            if (str_starts_with($cleanWhatsapp, '0')) {
                                $cleanWhatsapp = '62' . substr($cleanWhatsapp, 1);
                            }

                            // Defensif parsing tanggal untuk menghindari fatal error Carbon
                            $waktuReservasi = $res->waktu_reservasi ? \Carbon\Carbon::parse($res->waktu_reservasi) : null;
                        @endphp
                        <tr>
                            {{-- Nama Pelanggan --}}
                            <td class="ps-3 fw-bold text-dark">{{ $res->nama_lengkap ?? '-' }}</td>
                            
                            {{-- WhatsApp Link --}}
                            <td>
                                @if(!empty($cleanWhatsapp))
                                    <a href="https://wa.me/{{ $cleanWhatsapp }}" target="_blank" class="text-decoration-none text-success fw-bold d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-whatsapp"></i> {{ $rawWhatsapp }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            
                            {{-- Pilihan Meja --}}
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 fw-semibold">
                                    Mejs {{ $res->kumpulan_meja ? str_replace(',', ', ', $res->kumpulan_meja) : '-' }}
                                </span>
                            </td>
                            
                            {{-- Tanggal & Waktu --}}
                            <td>
                                @if($waktuReservasi)
                                    <div class="fw-bold text-dark">{{ $waktuReservasi->format('H:i') }} <small class="text-muted fw-normal">WIB</small></div>
                                    <div class="text-muted style-date" style="font-size: 0.65rem;">{{ $waktuReservasi->format('d M Y') }}</div>
                                @else
                                    <span class="text-muted">Waktu tidak valid</span>
                                @endif
                            </td>
                            
                            {{-- Catatan dengan Trigger Modal --}}
                            <td>
                                @if(!empty($res->catatan))
                                    <span class="text-secondary d-inline-block text-truncate fw-medium bg-light px-2 py-1 rounded" 
                                          style="max-width: 130px; cursor: pointer; font-size: 0.7rem;" 
                                          data-bs-toggle="modal" 
                                          data-bs-target="#modalCatatan{{ $resId }}"
                                          title="Klik untuk lihat lengkap">
                                        <i class="bi bi-file-text me-1 text-muted"></i>{{ $res->catatan }}
                                    </span>

                                    {{-- Modal Box Catatan --}}
                                    <div class="modal fade" id="modalCatatan{{ $resId }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow" style="border-radius: 12px;">
                                                <div class="modal-header border-0 bg-light py-3" style="border-radius: 12px 12px 0 0;">
                                                    <h6 class="modal-title fw-bold text-dark" style="font-size: 0.9rem;">
                                                        <i class="bi bi-chat-left-text-fill me-2 text-success"></i>Catatan Pelanggan
                                                    </h6>
                                                    <button type="button" class="btn-close shadow-none" style="font-size: 0.75rem;" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3 text-muted" style="font-size: 0.75rem;">
                                                        <i class="bi bi-person-fill me-1"></i> <strong>{{ $res->nama_lengkap ?? '-' }}</strong> <span class="mx-1">|</span> Meja {{ $res->kumpulan_meja ? str_replace(',', ', ', $res->kumpulan_meja) : '-' }}
                                                    </div>
                                                    <div class="p-3 bg-light rounded text-dark border-start border-success border-3 mb-0" 
                                                         style="font-size: 0.85rem; white-space: pre-wrap; line-height: 1.6; text-align: left;">
                                                        {{ $res->catatan }}
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-sm btn-secondary px-3 py-1.5 fw-bold" style="border-radius: 6px; font-size: 0.7rem;" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            
                            {{-- Status Badge (Subtle Premium Color System) --}}
                            <td>
                                @php
                                    $st = strtoupper($res->status ?? 'PENDING');
                                    $badgeStyle = [
                                        'PENDING'   => 'bg-warning-subtle text-warning',
                                        'DISETUJUI' => 'bg-info-subtle text-info',
                                        'SELESAI'   => 'bg-success-subtle text-success',
                                    ][$st] ?? 'bg-danger-subtle text-danger';
                                    
                                    $displayStatus = in_array($st, ['PENDING', 'DISETUJUI', 'SELESAI']) ? $st : 'CANCELLED';
                                @endphp
                                <span class="badge {{ $badgeStyle }} px-2 py-1" style="font-size: 0.625rem; font-weight: 600;">{{ $displayStatus }}</span>
                            </td>
                            
                            {{-- Aksi Menu --}}
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border dropdown-toggle py-1 px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.65rem; border-radius: 6px;">
                                        <i class="bi bi-gear me-1"></i> Atur
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="font-size: 0.7rem; border-radius: 8px;">
                                        <li>
                                            <form action="{{ route('admin.reservasi.updateStatus', $resId) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="DISETUJUI">
                                                <button type="submit" class="dropdown-item py-2 text-info"><i class="bi bi-check2-circle me-2"></i>Setujui Booking</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.reservasi.updateStatus', $resId) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="SELESAI">
                                                <button type="submit" class="dropdown-item py-2 text-success"><i class="bi bi-calendar-check me-2"></i>Selesai / Meja Ready</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.reservasi.updateStatus', $resId) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="DIBATALKAN">
                                                <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-x-circle me-2"></i>Batalkan</button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        
                                        {{-- Logika Kirim WhatsApp Berbasis Tautan Aman --}}
                                        <li>
                                            @php
                                                $namaPelanggan = $res->nama_lengkap ?? 'Pelanggan';
                                                $mejaInfo = $res->kumpulan_meja ? str_replace(',', ', ', $res->kumpulan_meja) : '-';
                                                $waktuInfo = $waktuReservasi ? $waktuReservasi->format('d M Y (H:i)') : '--';
                                                $statusInfo = ($st === 'PENDING') ? 'MENUNGGU KONFIRMASI' : $st;

                                                $pesanWA = "Halo Kak " . $namaPelanggan . ",\n\n" .
                                                           "Kami dari *RM Saung Tiga* ingin mengonfirmasi status booking tempat Anda:\n\n" .
                                                           "• *Meja* : Meja " . $mejaInfo . "\n" .
                                                           "• *Waktu* : " . $waktuInfo . " WIB\n" .
                                                           "• *Status* : *" . $statusInfo . "*\n\n" .
                                                           "Silakan datang sesuai jadwal atau Anda bisa mengecek status berkala langsung di website resmi kami.\n" .
                                                           "Terima kasih Banyak, Kak!";
                                                
                                                $urlWA = !empty($cleanWhatsapp) ? "https://wa.me/" . $cleanWhatsapp . "?text=" . urlencode($pesanWA) : '#';
                                            @endphp
                                            @if(!empty($cleanWhatsapp))
                                                <a href="{{ $urlWA }}" target="_blank" class="dropdown-item py-2 text-success fw-bold">
                                                    <i class="bi bi-send-check-fill me-2"></i>Kirim Notif WA
                                                </a>
                                            @else
                                                <button type="button" class="dropdown-item py-2 text-muted disabled">
                                                    <i class="bi bi-send-x-fill me-2"></i>No WA Kosong
                                                </button>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4 fw-semibold">Belum ada data reservasi masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection