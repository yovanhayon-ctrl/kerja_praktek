<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
        body { background-color: #f1f5f9; margin: 0; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 240px; min-height: 100vh;
            background-color: #0f172a; position: fixed;
            top: 0; left: 0; display: flex; flex-direction: column;
            z-index: 100; transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-brand .brand-logo {
            width: 36px; height: 36px; background: #4ade80;
            border-radius: 10px; display: flex; align-items: center; 
            justify-content: center; font-size: 1.1rem; color: #fff;
        }

        .sidebar-brand .brand-name {
            color: #fff; font-weight: 700; font-size: 0.95rem; line-height: 1.2;
        }

        .sidebar-brand .brand-sub { color: #94a3b8; font-size: 0.7rem; }
        .sidebar-nav { padding: 12px 12px; flex: 1; }

        .sidebar-label {
            color: #475569; font-size: 0.65rem; font-weight: 600;
            letter-spacing: 1px; text-transform: uppercase; padding: 8px 8px 4px;
        }

        .sidebar-link {
            display: flex; align-items: center; gap: 10px; padding: 9px 12px;
            border-radius: 10px; color: #94a3b8; font-size: 0.875rem;
            font-weight: 500; text-decoration: none; transition: all 0.2s ease;
            margin-bottom: 2px;
        }

        .sidebar-link:hover { background-color: rgba(255,255,255,0.06); color: #e2e8f0; }
        .sidebar-link.active { background-color: rgba(74,222,128,0.15); color: #4ade80; }
        .sidebar-link i { font-size: 1rem; width: 20px; text-align: center; }

        .sidebar-footer { padding: 12px; border-top: 1px solid rgba(255,255,255,0.08); }

        .sidebar-footer .logout-btn {
            display: flex; align-items: center; gap: 10px; padding: 9px 12px;
            border-radius: 10px; color: #94a3b8; font-size: 0.875rem;
            font-weight: 500; text-decoration: none; transition: all 0.2s;
            width: 100%; background: transparent; border: none; text-align: left;
        }

        .sidebar-footer .logout-btn:hover { background-color: rgba(239,68,68,0.12); color: #ef4444; }

        /* ── MAIN CONTENT ── */
        .main-content { margin-left: 240px; min-height: 100vh; transition: all 0.3s ease; }

        /* ── TOPBAR ── */
        .topbar {
            background: #fff; padding: 14px 24px; border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 99;
        }

        .topbar-title h5 { font-weight: 700; font-size: 1.1rem; margin: 0; color: #0f172a; }
        .topbar-title p { font-size: 0.75rem; color: #94a3b8; margin: 0; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }

        .topbar-icon {
            width: 36px; height: 36px; border-radius: 10px; background: #f1f5f9;
            display: flex; align-items: center; justify-content: center;
            color: #64748b; cursor: pointer; transition: all 0.2s; border: none;
        }

        .topbar-icon:hover { background: #e2e8f0; color: #0f172a; }

        .admin-avatar {
            width: 36px; height: 36px; border-radius: 50%; background: #4ade80;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 0.85rem; cursor: pointer;
        }

        /* ── PAGE CONTENT ── */
        .page-content { padding: 24px; }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #4ade80; border-radius: 10px; }
    </style>

    @stack('styles')
</head>
<body>

{{-- Menghitung Pesanan Pending, Pesan yang Belum Dibaca, & Reservasi Pending --}}
@php 
    $pendingCount = \App\Models\Pesanan::where('status', 'PENDING')->count(); 
    $unreadMessages = \App\Models\Pesan::where('status', 'BELUM_DIBACA')->count();
    $pendingReservasi = \App\Models\Reservasi::where('status', 'PENDING')->count(); // Hitung reservasi baru
@endphp

{{-- SIDEBAR --}}
<aside class="sidebar">

    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <div class="brand-logo">
                <i class="bi bi-shop"></i>
            </div>
            <div>
                <div class="brand-name">RM Saung Tiga</div>
                <div class="brand-sub">Admin Panel</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-label">Main Menu</div>

        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <a href="{{ route('admin.menu.index') }}"
           class="sidebar-link {{ request()->is('admin/menu*') ? 'active' : '' }}">
            <i class="bi bi-journal-richtext"></i> Kelola Menu
        </a>

        <a href="{{ route('admin.pesanan.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i> Data Pesanan
            @if($pendingCount > 0)
            <span class="ms-auto badge rounded-pill" style="background:#4ade80; color:#fff; font-size:0.65rem;">
                {{ $pendingCount }}
            </span>
            @endif
        </a>

        {{-- LINK MENU DATA RESERVASI MEJA BARU --}}
        <a href="{{ route('admin.reservasi.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.reservasi.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event"></i> Data Reservasi
            @if($pendingReservasi > 0)
            <span class="ms-auto badge rounded-pill bg-warning text-dark" style="font-size:0.65rem; font-weight:600;">
                {{ $pendingReservasi }}
            </span>
            @endif
        </a>

        <a href="{{ route('admin.statistik') }}"
           class="sidebar-link {{ request()->routeIs('admin.statistik') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Statistik
        </a>

        <a href="{{ route('admin.pesan.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.pesan.*') ? 'active' : '' }}">
            <i class="bi bi-envelope"></i> Pesan Masuk
            @if($unreadMessages > 0)
            <span class="ms-auto badge rounded-pill bg-danger" style="color:#fff; font-size:0.65rem;">
                {{ $unreadMessages }}
            </span>
            @endif
        </a>

        <div class="sidebar-label mt-2">Settings</div>

        <a href="{{ route('admin.manajemen_admin.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.manajemen_admin.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Manajemen Admin
        </a>

    </nav>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 px-2 mb-3">
            <div class="admin-avatar" style="width:32px; height:32px; font-size:0.75rem;">
                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
            </div>
            <div>
                <div style="color:#e2e8f0; font-size:0.8rem; font-weight:600;">{{ Auth::user()->name ?? 'Admin' }}</div>
                <div style="color:#64748b; font-size:0.68rem;">Super Admin</div>
            </div>
        </div>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>
    </div>

</aside>

{{-- MAIN CONTENT --}}
<div class="main-content">

    <div class="topbar">
        <div class="topbar-title">
            <h5>@yield('page-title', 'Dashboard')</h5>
            <p>@yield('page-subtitle', 'Overview performa restoran hari ini')</p>
        </div>
        <div class="topbar-right">
            {{-- Lonceng khusus untuk memantau data pesanan masuk makanan --}}
            <a href="{{ route('admin.pesanan.index') }}" class="topbar-icon position-relative text-decoration-none">
                <i class="bi bi-bell"></i>
                @if($pendingCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    style="font-size:0.55rem;">{{ $pendingCount }}</span>
                @endif
            </a>

            {{-- Pesan Masuk --}}
            <a href="{{ route('admin.pesan.index') }}" class="topbar-icon position-relative text-decoration-none">
                <i class="bi bi-envelope"></i>
                @if($unreadMessages > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    style="font-size:0.55rem;">{{ $unreadMessages }}</span>
                @endif
            </a>
            
            {{-- Shortcut ikon orang langsung ke Manajemen Akun Admin --}}
            <a href="{{ route('admin.manajemen_admin.index') }}" class="topbar-icon text-decoration-none">
                <i class="bi bi-person"></i>
            </a>
            <div class="admin-avatar">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</div>
        </div>
    </div>

    <div class="page-content">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" style="border-radius:12px; border:none; background:#dcfce7; color:#166534;">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible mb-4" style="border-radius:12px; border:none;">
            <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>

</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>