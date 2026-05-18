<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin RestoKu</title>

    {{-- Link Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }

        .login-wrapper {
            width: 100%;
            max-width: 430px;
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .login-content {
            padding: 42px 34px 34px;
        }

        .logo-box {
            width: 58px;
            height: 58px;
            border-radius: 14px;
            background: #dcfce7; /* Hijau muda utama */
            color: #166534;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 22px;
            font-size: 26px;
        }

        .title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            font-size: 0.92rem;
            margin-bottom: 34px;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 10px;
        }

        .input-group {
            border: 1px solid #d1d5db;
            border-radius: 12px;
            overflow: hidden;
            height: 48px;
            background: #fff;
        }

        .input-group:focus-within {
            border-color: #4ade80;
            box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.15);
        }

        .input-group-text {
            background: #fff;
            border: none;
            color: #6b7280;
            padding-left: 16px;
        }

        .form-control {
            border: none;
            box-shadow: none !important;
            font-size: 0.95rem;
            color: #111827;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .toggle-password {
            cursor: pointer;
            padding-right: 16px;
            color: #6b7280;
            transition: color 0.2s;
        }
        
        .toggle-password:hover {
            color: #111827;
        }

        .forgot-link {
            text-decoration: none;
            color: #16a34a; 
            font-size: 0.85rem;
            font-weight: 500;
        }

        .forgot-link:hover {
            color: #15803d;
        }

        .form-check-label {
            color: #6b7280;
            font-size: 0.92rem;
        }

        .btn-login {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 12px;
            background: #86efac; 
            color: #14532d;
            font-weight: 600;
            font-size: 1rem;
            margin-top: 24px;
            transition: .3s;
        }

        .btn-login:hover {
            background: #4ade80;
            color: #14532d;
        }

        .footer-box {
            border-top: 1px solid #f1f5f9;
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 0.9rem;
        }

        .footer-box span {
            color: #64748b;
        }

        .footer-box a {
            color: #166534;
            font-weight: 600;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-content">
        <div class="logo-box">
            <i class="bi bi-shield-lock-fill"></i>
        </div>

        <h1 class="title">Admin</h1>
        <p class="subtitle">Operational management at your fingertips.</p>

        {{-- Alert Notifikasi Error Sistem --}}
        @if($errors->any())
            <div class="alert alert-danger py-2 small">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Alert Notifikasi Sukses Ganti Password --}}
        @if(session('success_reset'))
            <div class="alert alert-success py-2 small">
                {{ session('success_reset') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="admin@rmsaungtiga.com" value="{{ old('email') }}" required>
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label m-0">Password</label>
                    <a href="#" class="forgot-link" data-bs-toggle="modal" data-bs-target="#forgotModal">Forgot?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                    <span class="input-group-text toggle-password" id="togglePassword">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            {{-- Remember Me --}}
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                <label class="form-check-label" for="remember">Keep me signed in</label>
            </div>

            <button type="submit" class="btn-login">
                Login <i class="bi bi-box-arrow-in-right ms-1"></i>
            </button>
        </form>
    </div>

    <div class="footer-box">
        <span>Need help?</span> <a href="#" data-bs-toggle="modal" data-bs-target="#forgotModal">Contact System Admin</a>
    </div>
</div>

{{-- MODAL RESET PASSWORD LANGSUNG --}}
<div class="modal fade" id="forgotModal" tabindex="-1" aria-labelledby="forgotModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="forgotModalLabel">Reset Password Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('admin.password.reset_direct') }}" method="POST">
                @csrf
                <div class="modal-body px-4 pb-4">
                    <p class="text-secondary small mb-3">Silakan masukkan email terdaftar dan kata sandi baru untuk memperbarui akun admin Anda.</p>
                    
                    {{-- Input Email Validasi --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Admin Terdaftar</label>
                        <input type="email" name="reset_email" class="form-control border px-3 rounded-3" style="height: 44px; font-size: 0.95rem;" placeholder="Contoh: admin@rmsaungtiga.com" required>
                    </div>

                    {{-- Input Password Baru --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password Baru</label>
                        <input type="password" name="new_password" class="form-control border px-3 rounded-3" style="height: 44px; font-size: 0.95rem;" placeholder="Minimal 6 karakter" required>
                    </div>

                    {{-- Konfirmasi Password Baru --}}
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control border px-3 rounded-3" style="height: 44px; font-size: 0.95rem;" placeholder="Ulangi password baru" required>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light border text-secondary me-2" style="border-radius: 10px; font-weight: 500;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white px-4" style="border-radius: 10px; background-color: #16a34a; font-weight: 500;">Simpan Sandi Baru</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- JavaScript Fitur Toggle Mata Password --}}
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        }
    });
</script>

</body>
</html>