<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin RM Saung Tiga</title>

    {{-- Link Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    {{-- Google Font Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }

        .login-content {
            padding: 40px 32px 32px;
        }

        .logo-box {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            background: #dcfce7; /* Hijau muda utama */
            color: #15803d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 24px;
            box-shadow: 0 4px 6px -1px rgba(220, 252, 231, 0.5);
        }

        .title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 32px;
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #475569;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .input-group-custom {
            display: flex;
            align-items: center;
            background-color: #f8fafc;
            border-radius: 10px;
            padding: 0 16px;
            transition: all 0.2s ease-in-out;
            border: 1px solid #e2e8f0;
        }

        .input-group-custom:focus-within {
            background-color: #fff;
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.12);
        }

        .input-icon {
            color: #94a3b8;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-control-custom {
            flex: 1;
            border: none;
            background: transparent;
            padding: 12px 12px;
            font-size: 0.875rem;
            color: #0f172a;
            outline: none;
            width: 100%;
        }

        .form-control-custom::placeholder {
            color: #94a3b8;
        }

        .toggle-password {
            cursor: pointer;
            color: #94a3b8;
            transition: color 0.2s;
            display: flex;
            align-items: center;
        }
        
        .toggle-password:hover {
            color: #475569;
        }

        .forgot-link {
            text-decoration: none;
            color: #16a34a; 
            font-size: 0.8rem;
            font-weight: 600;
        }

        .forgot-link:hover {
            color: #15803d;
        }

        .form-check-input {
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #16a34a;
            border-color: #16a34a;
        }

        .form-check-label {
            color: #64748b;
            font-size: 0.85rem;
            cursor: pointer;
            user-select: none;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: #16a34a; 
            color: #ffffff;
            font-weight: 600;
            font-size: 0.875rem;
            margin-top: 24px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(22, 163, 74, 0.2);
        }

        .btn-login:hover {
            background: #15803d;
            box-shadow: 0 4px 12px -1px rgba(21, 128, 61, 0.3);
        }

        .footer-box {
            border-top: 1px solid #f1f5f9;
            background: #f8fafc;
            padding: 18px;
            text-align: center;
            font-size: 0.825rem;
        }

        .footer-box span {
            color: #64748b;
        }

        .footer-box a {
            color: #166534;
            font-weight: 600;
            text-decoration: none;
            margin-left: 2px;
        }

        .footer-box a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-content">
        <div class="logo-box">
            <i class="bi bi-shield-lock-fill"></i>
        </div>

        <h1 class="title">Admin RM Saung Tiga</h1>
        <p class="subtitle">Operational management at your fingertips.</p>

        {{-- Alert Notifikasi Error Sistem --}}
        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm py-2 px-3 small d-flex align-items-center mb-4" style="border-radius: 10px; font-size: 0.8rem; background-color: #fef2f2; color: #991b1b;">
                <i class="bi bi-exclamation-circle-fill me-2 fs-6"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        {{-- Alert Notifikasi Sukses Ganti Password --}}
        @if(session('success_reset'))
            <div class="alert alert-success border-0 shadow-sm py-2 px-3 small d-flex align-items-center mb-4" style="border-radius: 10px; font-size: 0.8rem; background-color: #f0fdf4; color: #166534;">
                <i class="bi bi-check-circle-fill me-2 fs-6"></i>
                <div>{{ session('success_reset') }}</div>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            {{-- Email --}}
            <div class="mb-3.5">
                <label class="form-label">Email Address</label>
                <div class="input-group-custom">
                    <span class="input-icon"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control-custom" placeholder="admin@rmsaungtiga.com" value="{{ old('email') }}" required>
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-3 mt-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label m-0">Password</label>
                    <a href="#" class="forgot-link" data-bs-toggle="modal" data-bs-target="#forgotModal">Forgot?</a>
                </div>
                <div class="input-group-custom">
                    <span class="input-icon"><i class="bi bi-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control-custom" placeholder="••••••••" required>
                    <span class="toggle-password" id="togglePassword">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            {{-- Remember Me --}}
            <div class="form-check mt-3.5 d-flex align-items-center">
                <input class="form-check-input shadow-none" type="checkbox" id="remember" name="remember">
                <label class="form-check-label ms-2" for="remember">Keep me signed in</label>
            </div>

            <button type="submit" class="btn-login">
                Login Dashboard <i class="bi bi-arrow-right-short fs-5 align-middle ms-0.5"></i>
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
        <div class="modal-content shadow border-0" style="border-radius: 16px;">
            <div class="modal-header border-0 pt-4 px-4 pb-2">
                <h5 class="modal-title fw-bold text-dark fs-6" id="forgotModalLabel">Reset Password Admin</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('admin.password.reset_direct') }}" method="POST">
                @csrf
                <div class="modal-body px-4 pb-3">
                    <p class="text-secondary mb-4" style="font-size: 0.8rem; line-height: 1.5;">Silakan masukkan email terdaftar dan kata sandi baru untuk memperbarui kredensial akun admin Anda secara langsung.</p>
                    
                    {{-- Input Email Validasi --}}
                    <div class="mb-3">
                        <label class="form-label mb-1" style="font-size: 0.7rem;">Email Admin Terdaftar</label>
                        <input type="email" name="reset_email" class="form-control bg-light border-0 px-3 rounded-3 shadow-none" style="height: 44px; font-size: 0.85rem; color: #0f172a;" placeholder="Contoh: admin@rmsaungtiga.com" required>
                    </div>

                    {{-- Input Password Baru --}}
                    <div class="mb-3">
                        <label class="form-label mb-1" style="font-size: 0.7rem;">Password Baru</label>
                        <input type="password" name="new_password" class="form-control bg-light border-0 px-3 rounded-3 shadow-none" style="height: 44px; font-size: 0.85rem; color: #0f172a;" placeholder="Minimal 6 karakter" required>
                    </div>

                    {{-- Konfirmasi Password Baru --}}
                    <div class="mb-2">
                        <label class="form-label mb-1" style="font-size: 0.7rem;">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control bg-light border-0 px-3 rounded-3 shadow-none" style="height: 44px; font-size: 0.85rem; color: #0f172a;" placeholder="Ulangi password baru" required>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-2">
                    <button type="button" class="btn btn-light border-0 text-secondary me-2 px-3 evaluation-btn" style="border-radius: 10px; font-weight: 500; font-size: 0.85rem; background-color: #f1f5f9;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white px-4 border-0" style="border-radius: 10px; background-color: #16a34a; font-weight: 500; font-size: 0.85rem;">Simpan Sandi Baru</button>
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