<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin</title>

    {{-- Link Bootstrap & Icons sesuai permintaan Anda --}}
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
            background: #dcfce7; /* Hijau muda seperti layout utama */
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
        }

        .forgot-link {
            text-decoration: none;
            color: #16a34a; /* Mengikuti aksen hijau navbar */
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
            background: #86efac; /* Sesuai gambar referensi login */
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

        @if($errors->any())
            <div class="alert alert-danger py-2 small">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="admin@rmsaungtiga.com" required>
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label m-0">Password</label>
                    <a href="#" class="forgot-link">Forgot?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    <span class="input-group-text toggle-password">
                        <i class="bi bi-eye"></i>
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
        <span>Need help?</span> <a href="#">Contact System Admin</a>
    </div>
</div>

{{-- Bootstrap JS sesuai permintaan --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>