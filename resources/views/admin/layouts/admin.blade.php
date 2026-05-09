<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - RestoKu</title>

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
            width: 240px;
            min-height: 100vh;
            background-color: #0f172a;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-brand .brand-logo {
            width: 36px; height: 36px;
            background: #4ade80;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: #fff;
        }

        .sidebar-brand .brand-name {
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
            line-height: 1.2;
        }

        .sidebar-brand .brand-sub {
            color: #94a3b8;
            font-size: 0.7rem;
        }

        .sidebar-nav { padding: 12px 12px; flex: 1; }

        .sidebar-label {
            color: #475569;
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 8px 8px 4px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            color: #94a3b8;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 2px;
        }

        .sidebar-link:hover {
            background-color: rgba(255,255,255,0.06);
            color: #e2e8f0;
        }

        .sidebar-link.active {
            background-color: rgba(74,222,128,0.15);
            color: #4ade80;
        }

        .sidebar-link i { font-size: 1rem; width: 20px; text-align: center; }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-footer .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            color: #94a3b8;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            width: 100%;
        }

        .sidebar-footer .logout-btn:hover {
            background-color: rgba(239,68,68,0.12);
            color: #ef4444;
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: 240px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: #fff;
            padding: 14px 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .topbar-title h5 {
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0;
            color: #0f172a;
        }

        .topbar-title p {
            font-size: 0.75rem;
            color: #94a3b8;
            margin: 0;
        }

        .topbar-right { display: flex; align-items: center; gap: 12px; }

        .topbar-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: #f1f5f9;
            display: flex; align-items: center; justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .topbar-icon:hover { background: #e2e8f0; color: #0f172a; }

        .admin-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: #4ade80;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
        }

        /* ── PAGE CONTENT ── */
        .page-content { padding: 24px; }

        /* ── STAT CARD ── */
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
        }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-badge {
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 600;
        }

        /* ── TABLE ── */
        .admin-table { background: #fff; border-radius: 16px; overflow: hidden; }
        .admin-table .table { margin: 0; }
        .admin-table .table th {
            background: #f8fafc;
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 16px;
        }
        .admin-table .table td {
            padding: 12px 16px;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .admin-table .table tr:hover td { background-color: #fafafa; }
        .admin-table .table tr:last-child td { border-bottom: none; }

        /* ── BADGE STATUS ── */
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
        }
        .status-pending    { background: #fef9c3; color: #854d0e; }
        .status-diproses   { background: #dbeafe; color: #1e40af; }
        .status-selesai    { background: #dcfce7; color: #166534; }
        .status-dibatalkan { background: #fee2e2; color: #991b1b; }

        /* ── CARD UMUM ── */
        .admin-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
        }

        .admin-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-card-header h6 {
            font-weight: 700;
            font-size: 0.9rem;
            color: #0f172a;
            margin: 0;
        }

        .admin-card-body { padding: 20px; }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #4ade80; border-radius: 10px; }
    </style>

    @stack('styles')
</head>
<body>

{{-- ═══════════════════════════ --}}
{{--         SIDEBAR             --}}
{{-- ═══════════════════════════ --}}
<aside class="sidebar">

    {{-- Brand --}}
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

    {{-- Nav --}}
    <nav class="sidebar-nav">
        <div class="sidebar-label">Main Menu</div>

        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <a href="{{ route('admin.menu.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
            <i class="bi bi-journal-richtext"></i> Kelola Menu
        </a>

        <a href="{{ route('admin.pesanan.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i> Data Pesanan
            @php $pendingCount = \App\Models\Pesanan::where('status','pending')->count(); @endphp
            @if($pendingCount > 0)
            <span class="ms-auto badge rounded-pill" style="background:#4ade80; color:#fff; font-size:0.65rem;">
                {{ $pendingCount }}
            </span>
            @endif
        </a>

        <a href="{{ route('admin.statistik') }}"
           class="sidebar-link {{ request()->routeIs('admin.statistik') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Statistik
        </a>

        <div class="sidebar-label mt-2">Settings</div>

        <a href="{{ route('admin.admin.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.admin.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Manajemen Admin
        </a>

    </nav>

    {{-- Footer/Logout --}}
    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 px-2 mb-3">
            <div class="admin-avatar" style="width:32px; height:32px; font-size:0.75rem;">A</div>
            <div>
                <div style="color:#e2e8f0; font-size:0.8rem; font-weight:600;">Admin</div>
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

{{-- ═══════════════════════════ --}}
{{--       MAIN CONTENT          --}}
{{-- ═══════════════════════════ --}}
<div class="main-content">

    {{-- TOPBAR --}}
    <div class="topbar">
        <div class="topbar-title">
            <h5>@yield('page-title', 'Dashboard')</h5>
            <p>@yield('page-subtitle', 'Overview performa restoran hari ini')</p>
        </div>
        <div class="topbar-right">
            {{-- Notifikasi --}}
            <button class="topbar-icon position-relative">
                <i class="bi bi-bell"></i>
                @if($pendingCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                      style="font-size:0.55rem;">{{ $pendingCount }}</span>
                @endif
            </button>
            {{-- Settings --}}
            <button class="topbar-icon">
                <i class="bi bi-gear"></i>
            </button>
            {{-- Avatar --}}
            <div class="admin-avatar">A</div>
        </div>
    </div>

    {{-- PAGE CONTENT --}}
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