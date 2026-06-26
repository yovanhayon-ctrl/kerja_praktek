@extends('admin.layouts.admin')

@section('title', 'Admin Management')

@section('content')
<div class="container-fluid py-3 py-sm-4 px-2 px-sm-3">
    {{-- Header Halaman --}}
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1" style="font-size: 1.25rem;">Manajemen Admin</h4>
        <p class="text-muted small mb-0">Kelola akses dan hak istimewa pengguna administrator aplikasi RestoKu</p>
    </div>

    {{-- Alert Notification Success --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; font-size: 0.85rem;">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Alert Notification Error --}}
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; font-size: 0.85rem;">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>{{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row g-4">
        {{-- Kolom Kiri: Daftar Akun Admin --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm bg-white" style="border-radius: 16px;">
                <div class="card-body p-3 p-sm-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">Daftar Akun Admin</h6>
                        <button class="btn btn-success btn-sm fw-semibold d-lg-none px-3" data-bs-toggle="collapse" data-bs-target="#collapseTambahAdmin" style="border-radius: 8px; font-size: 0.75rem;">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Admin
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary">
                                <tr style="font-size: 0.725rem; letter-spacing: 0.5px;">
                                    <th style="padding: 12px;" class="ps-3">ADMIN NAME</th>
                                    <th>ROLE</th>
                                    <th>EMAIL ADDRESS</th>
                                    <th class="text-end pe-3">AKSI</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 0.85rem;">
                                @forelse($admins as $admin)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td class="py-3 ps-3">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center justify-content-center fw-bold text-primary rounded-circle me-3" 
                                                 style="width: 38px; height: 38px; background-color: #e0e7ff; font-size: 0.8rem; letter-spacing: 0.5px; flex-shrink: 0;">
                                                {{ strtoupper(substr($admin->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark d-block mb-0" style="font-size: 0.875rem;">{{ $admin->name }}</span>
                                                <small class="text-muted d-block mt-0.5" style="font-size: 0.725rem;">
                                                    <i class="bi bi-clock me-1"></i>{{ $admin->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{-- PERBAIKAN: Ubah warna dan jenis Role --}}
                                        @php
                                            $role = strtoupper($admin->role ?? 'KASIR');
                                            $badgeClass = [
                                                'SUPER ADMIN' => 'bg-success text-success bg-opacity-10',
                                                'KASIR'       => 'bg-primary text-primary bg-opacity-10'
                                            ][$role] ?? 'bg-secondary text-secondary bg-opacity-10';
                                        @endphp
                                        <span class="badge {{ $badgeClass }} fw-semibold px-2.5 py-1.5" style="font-size: 0.7rem; border-radius: 6px; letter-spacing: 0.3px;">
                                            <i class="{{ $role == 'SUPER ADMIN' ? 'bi-shield-fill-check' : 'bi-person-badge-fill' }} me-1"></i>{{ $role }}
                                        </span>
                                    </td>
                                    <td class="text-secondary font-monospace" style="font-size: 0.825rem;">{{ $admin->email }}</td>
                                    <td class="text-end pe-3">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-1 border-0 shadow-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical fs-5"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-2" style="font-size: 0.8rem; border-radius: 10px; min-width: 140px;">
                                                <li>
                                                    <form action="{{ route('admin.manajemen_admin.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger py-2">
                                                            <i class="bi bi-trash3 me-2"></i> Hapus Akun
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-shield-slash d-block fs-2 mb-2 text-secondary bg-opacity-10"></i>
                                        Belum ada data admin terdaftar.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 mt-4 pt-2" style="border-top: 1px solid #f1f5f9;">
                        <span class="text-muted small">Showing {{ $admins->firstItem() ?? 0 }} to {{ $admins->lastItem() ?? 0 }} of {{ $admins->total() }} admins</span>
                        <div class="mb-0">
                            {{ $admins->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Form Tambah Admin Baru --}}
        <div class="col-lg-4 collapse d-lg-block" id="collapseTambahAdmin">
            <div class="card border-0 shadow-sm bg-white mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Tambah Admin Baru</h6>
                    <p class="text-muted mb-4" style="font-size: 0.75rem;">Isi formulir untuk mendaftarkan akun administrator baru.</p>

                    <form action="{{ route('admin.manajemen_admin.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">FULL NAME</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0 text-muted px-3" style="border-radius: 10px 0 0 10px;"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" class="form-control bg-light border-0 py-2.5" placeholder="Admin / Kasir Baru" style="border-radius: 0 10px 10px 0; font-size: 0.825rem;" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">EMAIL ADDRESS</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0 text-muted px-3" style="border-radius: 10px 0 0 10px;"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control bg-light border-0 py-2.5" placeholder="kasir@rmsaungtiga.com" style="border-radius: 0 10px 10px 0; font-size: 0.825rem;" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">PASSWORD</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0 text-muted px-3" style="border-radius: 10px 0 0 10px;"><i class="bi bi-lock"></i></span>
                                <input type="password" id="passwordInput" name="password" class="form-control bg-light border-0 py-2.5" placeholder="••••••••" style="font-size: 0.825rem;" required>
                                <span class="input-group-text bg-light border-0 text-muted px-3 shadow-none" style="border-radius: 0 10px 10px 0; cursor: pointer;" onclick="togglePassword()">
                                    <i class="bi bi-eye" id="passwordIcon"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">ASSIGN ROLE</label>
                            <select name="role" class="form-select form-select-sm bg-light border-0 py-2.5" style="border-radius: 10px; font-size: 0.825rem; color: #475569;" required>
                                <option value="" selected disabled>Pilih hak akses...</option>
                                {{-- PERBAIKAN: Hanya ada opsi Super Admin dan Kasir --}}
                                <option value="SUPER ADMIN">SUPER ADMIN (Full Access)</option>
                                <option value="KASIR">KASIR (Limited Access)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2.5 fw-semibold shadow-sm" style="border-radius: 10px; font-size: 0.825rem; background-color: #198754;">
                            <i class="bi bi-shield-check me-1"></i> Simpan Akun Baru
                        </button>
                    </form>
                </div>
            </div>

            {{-- Info Box / Security Notice --}}
            <div class="p-3 border-0 d-flex align-items-start shadow-sm text-white" style="background-color: #1a3a3a; border-radius: 16px;">
                <div class="p-2 bg-white bg-opacity-10 rounded-3 text-white me-3 d-flex justify-content-center align-items-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-shield-lock-fill fs-5"></i>
                </div>
                <div style="flex: 1;">
                    <span class="fw-bold d-block mb-1" style="font-size: 0.8rem; color: #f8fafc;">Security Notice</span>
                    <p class="mb-0 text-white-50" style="font-size: 0.7rem; line-height: 1.4;">Every action taken by administrators is logged for security audits. Ensure strong passwords are used for all accounts.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('passwordInput');
        const passwordIcon = document.getElementById('passwordIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('bi-eye');
            passwordIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('bi-eye-slash');
            passwordIcon.classList.add('bi-eye');
        }
    }
</script>
@endsection