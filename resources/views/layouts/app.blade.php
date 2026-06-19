<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NCPMS') - Nutrition Care and Patient Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --color-primary: #128260;
            --color-primary-dark: #0d5f46;
            --color-primary-darker: #083c2c;
            --color-primary-light: #20a67d;
            --color-primary-subtle: #e6f5ef;
            --color-primary-border: #bce3d4;
            --color-accent: #f59f00;
            --color-accent-dark: #d98a00;
            --color-risiko-tinggi: #e03131;
            --color-risiko-sedang: #f08c00;
            --color-risiko-rendah: #2f9e44;
            --color-bg-page: #f4f7f6;
            --color-bg-card: #ffffff;
            --color-bg-sidebar: linear-gradient(180deg, #083c2c 0%, #0d5f46 100%);
            --color-text-primary: #1a2220;
            --color-text-secondary: #4a5c57;
            --color-text-muted: #849691;
            --color-border: #e2e8e6;
            --color-divider: #f0f4f2;
            --sidebar-width: 260px;
            --topbar-height: 70px;
            --font-primary: 'Plus Jakarta Sans', sans-serif;
            --font-secondary: 'Inter', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --shadow-sm: 0 2px 8px rgba(18, 130, 96, 0.04), 0 1px 2px rgba(0, 0, 0, 0.02);
            --shadow-md: 0 8px 24px rgba(18, 130, 96, 0.06), 0 2px 6px rgba(0, 0, 0, 0.03);
            --shadow-hover: 0 12px 32px rgba(18, 130, 96, 0.1), 0 4px 12px rgba(0, 0, 0, 0.04);
            --border-radius-lg: 16px;
            --border-radius-md: 10px;
            --border-radius-sm: 6px;
            --transition-all: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: var(--font-primary);
            background: var(--color-bg-page);
            color: var(--color-text-primary);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        a {
            color: var(--color-primary);
            text-decoration: none;
            transition: var(--transition-all);
        }
        
        a:hover {
            color: var(--color-primary-dark);
        }

        .sidebar {
            width: var(--sidebar-width);
            background: var(--color-bg-sidebar);
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1030;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.06);
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-brand {
            padding: 24px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
        }

        .brand-name {
            color: #fff;
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: -0.02em;
            display: block;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .brand-tagline {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            margin-top: 4px;
            font-weight: 500;
        }

        .sidebar-menu-label {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 24px 24px 10px;
        }

        .sidebar-menu-item {
            padding: 2px 16px;
        }

        .sidebar-menu-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.75);
            font-weight: 600;
            border-radius: var(--border-radius-md);
            transition: var(--transition-all);
        }

        .sidebar-menu-item a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            transform: translateX(4px);
        }

        .sidebar-menu-item a.active {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .menu-icon {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sidebar-user-info {
            margin-top: auto;
            padding: 20px 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0,0,0,0.15);
        }

        .sidebar-user-info .fw-bold {
            font-size: 0.95rem;
            color: #fff;
        }

        .sidebar-user-info .small {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.6);
            margin-top: 2px;
            font-weight: 500;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            z-index: 1020;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }

        .topbar .text-muted {
            font-weight: 600;
            color: var(--color-text-secondary) !important;
            letter-spacing: 0.02em;
        }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .content-area {
            padding: 32px;
            min-height: calc(100vh - var(--topbar-height));
            max-width: 1400px;
            margin: 0 auto;
            animation: fadeInUp 0.4s ease-out forwards;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #063023 0%, #0d5f46 100%);
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1030;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.08);
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at top right, rgba(255,255,255,0.05) 0%, transparent 40%);
            pointer-events: none;
        }

        .sidebar-brand {
            padding: 28px 24px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #20a67d, #0d5f46);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .brand-logo::after {
            content: '';
            position: absolute;
            top: -50%; left: -50%; width: 200%; height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: rotate(45deg);
            animation: shimmer 3s infinite linear;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }

        .brand-text-wrapper {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            color: #fff;
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -0.02em;
            line-height: 1;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .brand-tagline {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.7rem;
            margin-top: 4px;
            font-weight: 500;
            letter-spacing: 0.02em;
        }

        .sidebar-menu-label {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 24px 24px 10px;
        }

        .sidebar-menu-item {
            padding: 2px 16px;
            position: relative;
        }

        .sidebar-menu-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.75);
            font-weight: 600;
            border-radius: var(--border-radius-md);
            transition: var(--transition-all);
            position: relative;
            overflow: hidden;
        }

        .sidebar-menu-item a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            transform: translateX(4px);
        }

        .sidebar-menu-item a.active {
            background: linear-gradient(90deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.05) 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-left: 3px solid var(--color-accent);
        }

        .menu-icon {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }
        
        .sidebar-menu-item a:hover .menu-icon {
            transform: scale(1.1);
            color: var(--color-accent);
        }

        .sidebar-user-info {
            margin-top: auto;
            padding: 20px 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: var(--color-primary-light);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: bold; font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .sidebar-user-info .fw-bold {
            font-size: 0.95rem;
            color: #fff;
        }

        .sidebar-user-info .small {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.6);
            margin-top: 2px;
            font-weight: 500;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 230, 0.6);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            z-index: 1020;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        }
        
        .topbar-right {
            display: flex; align-items: center; gap: 16px;
        }

        .btn-logout {
            background: transparent;
            color: var(--color-risiko-tinggi);
            border: 1.5px solid rgba(224, 49, 49, 0.3);
            border-radius: 20px;
            padding: 6px 16px;
            font-weight: 700;
            font-size: 0.85rem;
            transition: var(--transition-all);
        }
        
        .btn-logout:hover {
            background: var(--color-risiko-tinggi);
            color: #fff;
            border-color: var(--color-risiko-tinggi);
            box-shadow: 0 4px 12px rgba(224, 49, 49, 0.2);
            transform: translateY(-1px);
        }

        .topbar .text-muted {
            font-weight: 600;
            color: var(--color-text-secondary) !important;
            letter-spacing: 0.02em;
            display: flex; align-items: center; gap: 8px;
        }
        
        .topbar .text-muted i { color: var(--color-primary-light); }

        .ncpms-card {
            background: var(--color-bg-card);
            border-radius: var(--border-radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
            border: 1px solid var(--color-border);
            transition: var(--transition-all);
        }

        .ncpms-card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-title-custom {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--color-text-primary);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px dashed var(--color-border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title-icon {
            background: var(--color-primary-subtle);
            color: var(--color-primary);
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .btn-primary-ncpms {
            background: var(--color-primary);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            transition: var(--transition-all);
            box-shadow: 0 4px 12px rgba(18, 130, 96, 0.2);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-ncpms:hover {
            background: var(--color-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(18, 130, 96, 0.3);
            color: #fff;
        }

        .btn-outline-ncpms {
            background: transparent;
            color: var(--color-primary);
            border: 1px solid var(--color-primary-border);
            padding: 10px 20px;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            transition: var(--transition-all);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-outline-ncpms:hover {
            background: var(--color-primary-subtle);
            border-color: var(--color-primary);
            color: var(--color-primary-dark);
        }

        .btn-danger-ncpms {
            background: #fff0f0;
            color: var(--color-risiko-tinggi);
            border: 1px solid #ffd8d8;
            padding: 10px 20px;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            transition: var(--transition-all);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-danger-ncpms:hover {
            background: var(--color-risiko-tinggi);
            color: #fff;
            box-shadow: 0 4px 12px rgba(224, 49, 49, 0.2);
            border-color: var(--color-risiko-tinggi);
        }

        .btn-sm-ncpms {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .form-control-ncpms {
            background: #fafcfb;
            border: 1px solid var(--color-border);
            border-radius: var(--border-radius-sm);
            padding: 12px 16px;
            color: var(--color-text-primary);
            transition: var(--transition-all);
            font-family: var(--font-secondary);
            font-size: 0.95rem;
            width: 100%;
        }

        .form-control-ncpms:focus {
            outline: none;
            border-color: var(--color-primary);
            background: #fff;
            box-shadow: 0 0 0 4px var(--color-primary-subtle);
        }

        .form-label-ncpms {
            font-weight: 600;
            color: var(--color-text-secondary);
            margin-bottom: 6px;
            font-size: 0.85rem;
            display: block;
        }

        .required-mark {
            color: var(--color-risiko-tinggi);
            margin-left: 2px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--color-text-primary);
            margin: 0;
            letter-spacing: -0.02em;
        }

        .page-subtitle {
            color: var(--color-text-muted);
            font-size: 0.95rem;
            margin-top: 4px;
            margin-bottom: 0;
            font-family: var(--font-secondary);
        }

        .badge-risk {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .risk-risiko_tinggi { background: #fff0f0; color: #e03131; border: 1px solid #ffd8d8; }
        .risk-risiko_sedang { background: #fff8e6; color: #f08c00; border: 1px solid #ffecb3; }
        .risk-risiko_rendah { background: #ebfbee; color: #2f9e44; border: 1px solid #d3f9d8; }
        .risk-belum { background: #f8f9fa; color: #6c757d; border: 1px solid #e9ecef; }
        
        .chart-wrap {
            height: 280px;
            position: relative;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(132, 150, 145, 0.4);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(132, 150, 145, 0.7);
        }

        @media (max-width: 991px) {
            :root {
                --sidebar-width: 80px;
            }

            .sidebar span.menu-text,
            .brand-tagline,
            .sidebar-user-info .brand-text-wrapper,
            .sidebar-menu-label {
                display: none;
            }

            .sidebar-brand {
                padding: 24px 10px;
                justify-content: center;
            }
            
            .brand-logo { margin: 0; }
            .brand-name { display: none; }

            .sidebar-menu-item a {
                justify-content: center;
                padding: 14px;
                border-left: none;
            }
            .sidebar-menu-item a.active { border-left: none; border-bottom: 3px solid var(--color-accent); }
            
            .menu-icon {
                font-size: 1.3rem;
            }

            .topbar {
                padding: 0 20px;
            }
            .sidebar-user-info { justify-content: center; padding: 20px 0;}
            .user-avatar { width: 32px; height: 32px; font-size: 0.9rem;}
        }

        @media (max-width: 767px) {
            :root {
                --sidebar-width: 0px;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .content-area {
                padding: 20px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    @auth
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="brand-logo"><i class="fas fa-leaf"></i></div>
                <div class="brand-text-wrapper">
                    <a href="{{ route('dashboard') }}" class="brand-name">NCPMS</a>
                    <div class="brand-tagline">Presisi Gizi, Akurasi Asuhan</div>
                </div>
            </div>
            <div class="sidebar-menu-label">Menu Utama</div>
            <div class="sidebar-menu-item"><a href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i
                        class="fas fa-chart-pie menu-icon"></i><span class="menu-text">Dashboard</span></a></div>
            @if (in_array(Auth::user()->peran, ['perawat', 'nutrisionis', 'dietisien', 'spgk']))
                <div class="sidebar-menu-item"><a href="{{ route('pasien.index') }}"
                        class="{{ request()->routeIs('pasien.*') || request()->routeIs('kunjungan.*') ? 'active' : '' }}"><i
                            class="fas fa-hospital-user menu-icon"></i><span class="menu-text">Pasien & Kunjungan</span></a>
                </div>
            @endif
            @if (in_array(Auth::user()->peran, ['dietisien', 'spgk']))
                <div class="sidebar-menu-item"><a href="{{ route('diagnosis.index') }}"
                        class="{{ request()->routeIs('diagnosis.*') ? 'active' : '' }}"><i
                            class="fas fa-stethoscope menu-icon"></i><span class="menu-text">Diagnosis Gizi</span></a></div>
                <div class="sidebar-menu-item"><a href="{{ route('intervensi.index') }}"
                        class="{{ request()->routeIs('intervensi.*') ? 'active' : '' }}"><i
                            class="fas fa-utensils menu-icon"></i><span class="menu-text">Preskripsi Diet</span></a></div>
                <div class="sidebar-menu-item"><a href="{{ route('monitoring.index') }}"
                        class="{{ request()->routeIs('monitoring.*') ? 'active' : '' }}"><i
                            class="fas fa-heart-pulse menu-icon"></i><span class="menu-text">Monitoring</span></a></div>
                <div class="sidebar-menu-item"><a href="{{ route('laporan.index') }}"
                        class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}"><i
                            class="fas fa-file-medical-alt menu-icon"></i><span class="menu-text">Laporan</span></a></div>
            @endif
            @if (Auth::user()->peran === 'admin_ti')
                <div class="sidebar-menu-label">Administrator</div>
                <div class="sidebar-menu-item">
                    <a href="{{ route('admin.pengguna') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield menu-icon"></i> <span class="menu-text">Manajemen Akun</span>
                    </a>
                </div>
            @endif


            <div class="sidebar-user-info">
                <div class="user-avatar">{{ substr(Auth::user()->nama_lengkap, 0, 1) }}</div>
                <div class="brand-text-wrapper">
                    <div class="fw-bold">{{ Auth::user()->nama_lengkap }}</div>
                    <div class="small">{{ Auth::user()->nama_peran }}</div>
                </div>
            </div>
        </aside>
        <div class="main-wrapper">
            <header class="topbar">
                <div class="small text-muted"><i class="fas fa-map-marker-alt"></i> @yield('breadcrumb', 'NCPMS')</div>
                <div class="topbar-right">
                    <form action="{{ route('logout') }}" method="POST" class="m-0">@csrf<button
                            class="btn-logout"><i class="fas fa-power-off me-1"></i> Keluar</button>
                    </form>
                </div>
            </header>
            <main class="content-area">@yield('content')</main>
        </div>
    @else
        @yield('content')
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const NCPMS_SWAL = {
            sukses: pesan => Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: pesan,
                toast: true,
                position: 'top-end',
                timer: 2400,
                showConfirmButton: false,
                timerProgressBar: true
            }),
            error: pesan => Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: pesan
            }),
            konfirmasiHapus: (form, teks = 'Data yang dihapus tidak dapat dikembalikan.') => {
                Swal.fire({
                    title: 'Hapus Data?',
                    text: teks,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#D93025',
                    reverseButtons: true
                }).then(r => {
                    if (r.isConfirmed) form.submit();
                });
            },
            konfirmasiKunci: form => {
                Swal.fire({
                    title: 'Kunci Dokumen?',
                    html: 'Dokumen akan dikunci dan <strong>tidak dapat diubah</strong>.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kunci Dokumen',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#145C4B',
                    reverseButtons: true
                }).then(r => {
                    if (r.isConfirmed) form.submit();
                });
            },
            peringatanKlinis: (judul, pesan) => Swal.fire({
                icon: 'warning',
                title: judul,
                html: pesan,
                confirmButtonText: 'Saya Mengerti',
                confirmButtonColor: '#E67E22',
                allowOutsideClick: false
            })
        };
        document.addEventListener('submit', function(e) {
            if (e.target.matches('[data-confirm-delete]')) {
                e.preventDefault();
                NCPMS_SWAL.konfirmasiHapus(e.target);
            }
            if (e.target.matches('[data-confirm-lock]')) {
                e.preventDefault();
                NCPMS_SWAL.konfirmasiKunci(e.target);
            }
        });
        @if (session('swal_success'))
            NCPMS_SWAL.sukses(@json(session('swal_success')));
        @endif
        @if (session('swal_error'))
            NCPMS_SWAL.error(@json(session('swal_error')));
        @endif
        @if ($errors->any())
            NCPMS_SWAL.error(@json($errors->first()));
        @endif
    </script>
    @stack('scripts')
</body>

</html>
