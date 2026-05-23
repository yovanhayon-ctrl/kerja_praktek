@extends('admin.layouts.admin')

@section('title', 'Admin Management')

@section('content')
<div class="container-fluid py-4">
    {{-- Header Halaman --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-0" style="font-size: 1.25rem;">Manajemen Admin</h4>
        <small class="text-muted" style="font-size: 0.75rem;">Kelola akses dan hak istimewa pengguna administrator.</small>
    </div>

    {{-- Alert Notification Success --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 10px; font-size: 0.8rem;">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-alert="close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Alert Notification Error --}}
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 10px; font-size: 0.8rem;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row g-4">
        {{-- Kolom Kiri: Daftar Akun Admin --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 15px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">Daftar Akun Admin</h6>
                    <button class="btn btn-success btn-sm fw-bold d-lg-none" data-bs-toggle="collapse" data-bs-target="#collapseTambahAdmin" style="border-radius: 8px; font-size: 0.75rem;">
                        <i class="bi bi-plus-lg me-1"></i> Add Admin
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted" style="font-size: 0.65rem; border-bottom: 1px solid #f1f5f9;">
                                <th style="padding-bottom: 12px;">ADMIN NAME</th>
                                <th style="padding-bottom: 12px;">ROLE</th>
                                <th style="padding-bottom: 12px;">EMAIL ADDRESS</th>
                                <th class="text-end" style="padding-bottom: 12px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.75rem;">
                            @forelse($admins as $admin)
                            <tr style="border-bottom: 1px solid #f8fafc;">
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        {{-- Avatar Inisial Bulat --}}
                                        <div class="d-flex align-items-center justify-content-center fw-bold text-primary rounded-circle me-3" 
                                             style="width: 36px; height: 36px; background-color: #f0f4ff; font-size: 0.75rem; letter-spacing: 0.5px;">
                                            {{ strtoupper(substr($admin->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block mb-0" style="font-size: 0.8rem;">{{ $admin->name }}</span>
                                            <small class="text-muted" style="font-size: 0.65rem;">Terdaftar: {{ $admin->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $role = strtoupper($admin->role ?? 'STAFF');
                                        $bg = [
                                            'SUPER ADMIN' => 'success',
                                            'SUPERADMIN' => 'success',
                                            'MANAGER' => 'primary',
                                            'STAFF' => 'secondary'
                                        ][$role] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $bg }}-subtle text-{{ $bg }} fw-bold px-2.5 py-1.5" style="font-size: 0.6rem; letter-spacing: 0.5px; border-radius: 4px;">
                                        {{ $role }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $admin->email }}</td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-link text-muted p-0 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical fs-6"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="font-size: 0.75rem; border-radius: 8px;">
                                            <li>
                                                <form action="{{ route('admin.manajemen_admin.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
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
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada data admin terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-4 pt-2" style="border-top: 1px solid #f1f5f9; font-size: 0.7rem;">
                    <span class="text-muted">Showing {{ $admins->firstItem() ?? 0 }} to {{ $admins->lastItem() ?? 0 }} of {{ $admins->total() }} admins</span>
                    <div>
                        {{ $admins->links() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Form Tambah Admin Baru --}}
        <div class="col-lg-4 collapse d-lg-block" id="collapseTambahAdmin">
            <div class="card border-0 shadow-sm p-4 bg-white mb-4" style="border-radius: 15px;">
                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Tambah Admin Baru</h6>
                <p class="text-muted mb-4" style="font-size: 0.7rem;">Isi formulir untuk mendaftarkan akun administrator baru.</p>

                <form action="{{ route('admin.manajemen_admin.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size: 0.65rem;">FULL NAME</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 8px 0 0 8px;"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control bg-light border-start-0" placeholder="Admin / User" style="border-radius: 0 8px 8px 0; font-size: 0.75rem;" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size: 0.65rem;">EMAIL ADDRESS</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 8px 0 0 8px;"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control bg-light border-start-0" placeholder="admin@rmsaungtiga.com" style="border-radius: 0 8px 8px 0; font-size: 0.75rem;" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size: 0.65rem;">PASSWORD</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 8px 0 0 8px;"><i class="bi bi-lock"></i></span>
                            <input type="password" id="passwordInput" name="password" class="form-control bg-light border-start-0 border-end-0" placeholder="••••••••" style="font-size: 0.75rem;" required>
                            <span class="input-group-text bg-light border-start-0 text-muted" style="border-radius: 0 8px 8px 0; cursor: pointer;" onclick="togglePassword()"><i class="bi bi-eye" id="passwordIcon"></i></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted fw-semibold" style="font-size: 0.65rem;">ASSIGN ROLE</label>
                        <select name="role" class="form-select form-select-sm bg-light" style="border-radius: 8px; font-size: 0.75rem; color: #475569;" required>
                            <option value="" selected disabled>Pilih hak akses...</option>
                            <option value="SUPER ADMIN">SUPER ADMIN</option>
                            <option value="MANAGER">MANAGER</option>
                            <option value="STAFF">STAFF</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm" style="border-radius: 8px; font-size: 0.75rem; background-color: #198754;">
                        <i class="bi bi-shield-check me-1"></i> Simpan Akun Baru
                    </button>
                </form>
            </div>

            {{-- Info Box / Security Notice --}}
            <div class="p-3 border-0 rounded-3 text-white d-flex align-items-start shadow-sm" style="background-color: #1a3a3a; border-radius: 12px;">
                <div class="p-2 bg-white bg-opacity-10 rounded-2 text-white me-3">
                    <i class="bi bi-shield-lock-fill fs-5"></i>
                </div>
                <div>
                    <span class="fw-bold d-block mb-1" style="font-size: 0.75rem; color: #e2e8f0;">Security Notice</span>
                    <p class="mb-0 text-white-50" style="font-size: 0.65rem; line-height: 1.4;">Every action taken by administrators is logged for security audits. Ensure strong passwords are used for all accounts.</p>
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