<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCPMS &mdash; Nutrition Care and Patient Management System</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --color-primary: #1A7A64;
            --color-primary-dark: #145C4B;
            --color-primary-darker: #0E3F35;
            --color-primary-light: #2BA882;
            --color-primary-subtle: #E8F5F1;
            --color-primary-border: #B2DDD4;
            --color-accent: #F4A830;
            --color-bg-page: #F0F4F3;
            --color-bg-card: #FFFFFF;
            --color-bg-sidebar: #0E3F35;
            --color-bg-topbar: #FFFFFF;
            --color-text-primary: #1C2A27;
            --color-text-secondary: #4A6560;
            --color-text-muted: #8AA09A;
            --color-border: #D4E6E1;
            --sidebar-width: 240px;
            --topbar-height: 60px;
            --font-primary: 'Plus Jakarta Sans', sans-serif;
            --font-secondary: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-primary);
            background-color: var(--color-bg-page);
            color: var(--color-text-primary);
        }

        .sidebar {
            width: var(--sidebar-width);
            background: var(--color-bg-sidebar);
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1030;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand .brand-name {
            font-weight: 800;
            font-size: 1.125rem;
            color: #FFFFFF;
            text-decoration: none;
        }

        .sidebar-menu-item a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            color: rgba(255,255,255,0.7);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .sidebar-menu-item a:hover, .sidebar-menu-item a.active {
            background: var(--color-primary);
            color: #FFFFFF;
            font-weight: 600;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: var(--color-bg-topbar);
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            z-index: 1020;
            justify-content: flex-end;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 24px;
        }

        .ncpms-card {
            background: var(--color-bg-card);
            border-radius: 12px;
            border: 1px solid var(--color-border);
            box-shadow: 0 1px 3px rgba(26,122,100,0.06);
            padding: 24px;
            margin-bottom: 16px;
        }

        .stat-card {
            background: var(--color-bg-card);
            border-radius: 12px;
            border: 1px solid var(--color-border);
            padding: 20px 24px;
            position: relative;
            overflow: hidden;
            border-top: 3px solid var(--color-primary);
        }

        .stat-value {
            font-size: 1.875rem;
            font-weight: 800;
            color: var(--color-primary-dark);
            font-family: var(--font-secondary);
        }

        .btn-primary-ncpms {
            background: var(--color-primary);
            color: #FFFFFF;
            border: none;
            padding: 9px 20px;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .btn-primary-ncpms:hover {
            background: var(--color-primary-dark);
            color: #FFFFFF;
        }
    </style>
</head>
<body>
    @auth
    <div class="sidebar">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}" class="brand-name">NCPMS</a>
            <div style="color: rgba(255,255,255,0.5); font-size: 0.6875rem;">Presisi Gizi, Akurasi Asuhan</div>
        </div>
        <div class="sidebar-menu-label" style="font-size: 0.625rem; color: rgba(255,255,255,0.35); padding: 20px 20px 8px; text-transform: uppercase; font-weight: bold;">Menu Utama</div>
        <div class="sidebar-menu-item">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home menu-icon"></i> Dashboard
            </a>
        </div>
        <div class="sidebar-menu-item">
            <a href="{{ route('pasien.index') }}" class="{{ request()->routeIs('pasien.*') ? 'active' : '' }}">
                <i class="fas fa-users menu-icon"></i> Pasien & Kunjungan
            </a>
        </div>
    </div>
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <span class="fw-bold" style="color: var(--color-text-primary);">{{ Auth::user()->nama_lengkap }} ({{ strtoupper(Auth::user()->peran) }})</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Keluar</button>
            </form>
        </div>
    </div>
    <div class="main-content">
        @yield('content')
    </div>
    @else
        @yield('content')
    @endauth
</body>
</html>