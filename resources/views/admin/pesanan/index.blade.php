@extends('admin.layouts.admin')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-body p-4">
        <h5 class="fw-bold text-dark mb-4">Daftar Pesanan Masuk</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Meja</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesanans as $pesanan)
                    <tr>
                        <td class="ps-4 fw-bold">
                            {{-- Menampilkan ID Pesanan atau ID database sebagai cadangan --}}
                            {{ $pesanan->id_pesanan ?? '#' . $pesanan->id }}
                        </td>
                        <td>
                            {{-- Menampilkan nama_pelanggan sesuai migration --}}
                            {{ $pesanan->nama_pelanggan ?? $pesanan->nama_pemesan ?? 'Tanpa Nama' }}
                        </td>
                        <td>
                            {{-- Menampilkan nomor_meja sesuai migration --}}
                            {{ $pesanan->nomor_meja ?? $pesanan->no_meja ?? 'Meja -' }}
                        </td>
                        <td class="fw-bold">
                            Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                        </td>
                        <td>
                            @php
                                $status = strtoupper($pesanan->status);
                                $badgeColor = [
                                    'PENDING' => 'bg-secondary',
                                    'DIPROSES' => 'bg-warning text-dark',
                                    'SELESAI' => 'bg-success',
                                    'DIBATALKAN' => 'bg-danger'
                                ][$status] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $badgeColor }}">{{ $status }}</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-sm btn-light border">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <button type="button" class="btn btn-sm btn-light border dropdown-toggle" data-bs-toggle="dropdown">
                                    Status
                                </button>
                                <ul class="dropdown-menu shadow border-0 text-center">
                                    <li>
                                        <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="DIPROSES">
                                            <button type="submit" class="dropdown-item">Proses</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="SELESAI">
                                            <button type="submit" class="dropdown-item">Selesai</button>
                                        </form>
                                    </li>
                                </ul>
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