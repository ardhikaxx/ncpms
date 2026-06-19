<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NCPMS') - Nutrition Care and Patient Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ============================
           DESIGN TOKENS
        ============================ */
        :root {
            --color-primary: #0f766e;
            --color-primary-dark: #0d6459;
            --color-primary-darker: #083c2c;
            --color-primary-light: #14b8a6;
            --color-primary-subtle: #f0fdfa;
            --color-primary-border: #99f6e4;
            --color-accent: #f59e0b;
            --color-accent-dark: #d98a00;
            --color-risiko-tinggi: #dc2626;
            --color-risiko-sedang: #f59e0b;
            --color-risiko-rendah: #16a34a;
            --color-bg-page: #f8fafa;
            --color-bg-card: #ffffff;
            --color-text-primary: #111827;
            --color-text-secondary: #4b5563;
            --color-text-muted: #9ca3af;
            --color-border: #e5e7eb;
            --color-divider: #f3f4f6;
            --sidebar-width: 250px;
            --topbar-height: 60px;
            --font-primary: 'Inter', sans-serif;
            --shadow-xs: 0 1px 2px rgba(0,0,0,0.04);
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.07);
            --shadow-hover: 0 8px 24px rgba(0,0,0,0.1);
            --radius-lg: 14px;
            --radius-md: 10px;
            --radius-sm: 6px;
            --transition: all 0.2s ease;
        }

        /* ============================
           RESET & BASE
        ============================ */
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: var(--font-primary);
            background: var(--color-bg-page);
            color: var(--color-text-primary);
            font-size: 0.875rem;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: var(--color-primary);
            text-decoration: none;
            transition: var(--transition);
        }
        a:hover { color: var(--color-primary-dark); }

        /* ============================
           SIDEBAR
        ============================ */
        .sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1030;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            border-right: 1px solid var(--color-border);
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-logo {
            width: 34px;
            height: 34px;
            background: var(--color-primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .brand-text-wrapper {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            color: var(--color-text-primary);
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .brand-tagline {
            color: var(--color-text-muted);
            font-size: 0.68rem;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        .sidebar-menu-label {
            color: var(--color-text-muted);
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 20px 20px 6px;
        }

        .sidebar-menu-item {
            padding: 1px 10px;
        }

        .sidebar-menu-item a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            color: var(--color-text-secondary);
            font-weight: 500;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            font-size: 0.855rem;
        }

        .sidebar-menu-item a:hover {
            background: var(--color-primary-subtle);
            color: var(--color-primary);
        }

        .sidebar-menu-item a.active {
            background: var(--color-primary-subtle);
            color: var(--color-primary);
            font-weight: 600;
        }

        .menu-icon {
            width: 18px;
            text-align: center;
            font-size: 0.9rem;
            opacity: 0.7;
        }

        .sidebar-menu-item a.active .menu-icon,
        .sidebar-menu-item a:hover .menu-icon {
            opacity: 1;
        }

        .sidebar-user-info {
            margin-top: auto;
            padding: 16px 20px;
            border-top: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: var(--color-primary);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 600; font-size: 0.85rem;
            flex-shrink: 0;
        }

        .sidebar-user-info .fw-bold {
            font-size: 0.82rem;
            color: var(--color-text-primary);
            font-weight: 600;
        }

        .sidebar-user-info .small {
            font-size: 0.72rem;
            color: var(--color-text-muted);
            font-weight: 500;
        }

        /* ============================
           TOPBAR
        ============================ */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            z-index: 1020;
        }

        .topbar .breadcrumb-text {
            font-weight: 500;
            color: var(--color-text-muted);
            font-size: 0.82rem;
        }

        .topbar-right {
            display: flex; align-items: center; gap: 12px;
        }

        .btn-logout {
            background: transparent;
            color: var(--color-text-muted);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
            padding: 6px 14px;
            font-weight: 600;
            font-size: 0.8rem;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-logout:hover {
            background: #fef2f2;
            color: var(--color-risiko-tinggi);
            border-color: #fecaca;
        }

        /* ============================
           MAIN LAYOUT
        ============================ */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .content-area {
            padding: 28px;
            min-height: calc(100vh - var(--topbar-height));
            max-width: 1360px;
            margin: 0 auto;
            animation: fadeIn 0.3s ease-out forwards;
        }

        /* ============================
           PAGE HEADER
        ============================ */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--color-text-primary);
            margin: 0;
            letter-spacing: -0.02em;
        }

        .page-subtitle {
            color: var(--color-text-muted);
            font-size: 0.85rem;
            margin-top: 2px;
            margin-bottom: 0;
        }

        /* ============================
           CARDS
        ============================ */
        .ncpms-card {
            background: var(--color-bg-card);
            border-radius: var(--radius-lg);
            padding: 22px;
            box-shadow: var(--shadow-xs);
            margin-bottom: 20px;
            border: 1px solid var(--color-border);
            transition: var(--transition);
        }

        .ncpms-card:hover {
            box-shadow: var(--shadow-sm);
        }

        .card-title-custom {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--color-text-primary);
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--color-divider);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-title-icon {
            background: var(--color-primary-subtle);
            color: var(--color-primary);
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.78rem;
        }

        /* ============================
           BUTTONS
        ============================ */
        .btn-ncpms {
            background: var(--color-primary);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            padding: 8px 18px;
            font-weight: 600;
            font-size: 0.84rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: var(--transition);
        }
        .btn-ncpms:hover {
            background: var(--color-primary-dark);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-ncpms-outline {
            background: transparent;
            color: var(--color-text-secondary);
            border: 1.5px solid var(--color-border);
            border-radius: var(--radius-sm);
            padding: 7px 16px;
            font-weight: 600;
            font-size: 0.84rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: var(--transition);
        }
        .btn-ncpms-outline:hover {
            border-color: var(--color-primary);
            color: var(--color-primary);
            background: var(--color-primary-subtle);
        }

        .btn-primary-ncpms {
            background: var(--color-primary);
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.84rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }
        .btn-primary-ncpms:hover {
            background: var(--color-primary-dark);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-outline-ncpms {
            background: transparent;
            color: var(--color-primary);
            border: 1.5px solid var(--color-primary-border);
            padding: 7px 16px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.84rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }
        .btn-outline-ncpms:hover {
            background: var(--color-primary-subtle);
            border-color: var(--color-primary);
            color: var(--color-primary-dark);
        }

        .btn-danger-ncpms {
            background: #fef2f2;
            color: var(--color-risiko-tinggi);
            border: 1px solid #fecaca;
            padding: 8px 18px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.84rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }
        .btn-danger-ncpms:hover {
            background: var(--color-risiko-tinggi);
            color: #fff;
            border-color: var(--color-risiko-tinggi);
        }

        .btn-sm-ncpms {
            padding: 5px 10px;
            font-size: 0.78rem;
        }

        /* ============================
           FORMS
        ============================ */
        .form-control-ncpms {
            background: #fff;
            border: 1.5px solid var(--color-border);
            border-radius: var(--radius-sm);
            padding: 9px 14px;
            color: var(--color-text-primary);
            transition: var(--transition);
            font-family: var(--font-primary);
            font-size: 0.87rem;
            width: 100%;
        }
        .form-control-ncpms:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.08);
        }

        .form-label-ncpms {
            font-weight: 600;
            color: var(--color-text-secondary);
            margin-bottom: 4px;
            font-size: 0.8rem;
            display: block;
        }

        .required-mark {
            color: var(--color-risiko-tinggi);
            margin-left: 2px;
        }

        /* ============================
           DATA TABLES
        ============================ */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead th {
            background: var(--color-divider);
            font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--color-text-muted);
            border-bottom: 1px solid var(--color-border);
            padding: 10px 14px; white-space: nowrap;
        }
        .data-table tbody tr { transition: background 0.15s; }
        .data-table tbody tr:hover { background: var(--color-primary-subtle); }
        .data-table td {
            padding: 10px 14px; vertical-align: middle;
            border-bottom: 1px solid var(--color-divider); font-size: 0.84rem;
        }
        .data-table tbody tr:last-child td { border-bottom: none; }

        /* ============================
           STAT CARDS
        ============================ */
        .stat-card {
            background: #fff; border-radius: var(--radius-md);
            padding: 18px 20px;
            border: 1px solid var(--color-border);
            box-shadow: var(--shadow-xs);
            transition: var(--transition);
            height: 100%;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-label {
            font-size: 0.7rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.05em; color: var(--color-text-muted); margin-bottom: 4px;
        }
        .stat-value {
            font-size: 1.7rem; font-weight: 800;
            color: var(--color-text-primary); line-height: 1.1;
        }
        .stat-icon {
            width: 40px; height: 40px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center; font-size: 1rem;
        }

        /* ============================
           BADGES
        ============================ */
        .badge-risk {
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .risk-risiko_tinggi { background: #fef2f2; color: var(--color-risiko-tinggi); border: 1px solid #fecaca; }
        .risk-risiko_sedang { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .risk-risiko_rendah { background: #f0fdf4; color: var(--color-risiko-rendah); border: 1px solid #bbf7d0; }
        .risk-belum { background: var(--color-divider); color: var(--color-text-muted); border: 1px solid var(--color-border); }

        .badge-pill {
            padding: 3px 10px; border-radius: 20px;
            font-size: 0.7rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.04em; display: inline-block;
        }
        .badge-soft-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-soft-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-soft-danger  { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .badge-soft-gray    { background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db; }
        .badge-soft-primary { background: var(--color-primary-subtle); color: var(--color-primary-dark); border: 1px solid var(--color-primary-border); }

        /* ============================
           AVATAR & MISC
        ============================ */
        .avatar-circle {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--color-primary); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 0.82rem; flex-shrink: 0;
        }

        .rm-badge {
            font-family: 'JetBrains Mono', monospace; font-size: 0.78rem; font-weight: 600;
            background: var(--color-primary-subtle); color: var(--color-primary);
            padding: 2px 8px; border-radius: 4px; display: inline-block;
        }

        .chart-wrap {
            height: 260px;
            position: relative;
        }

        /* ============================
           SECTION DIVIDER
        ============================ */
        .section-divider {
            display: flex; align-items: center; gap: 10px;
            margin: 18px 0 10px;
            color: var(--color-text-secondary); font-weight: 700; font-size: 0.8rem;
        }
        .section-divider::after {
            content: ''; flex: 1; height: 1px; background: var(--color-border);
        }

        /* ============================
           INFO ROW
        ============================ */
        .info-row {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 8px 0; border-bottom: 1px solid var(--color-divider);
            font-size: 0.84rem;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row .info-icon {
            width: 28px; height: 28px; flex-shrink: 0;
            background: var(--color-primary-subtle); color: var(--color-primary);
            border-radius: 6px; display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem;
        }
        .info-row .info-label {
            font-size: 0.68rem; font-weight: 600; color: var(--color-text-muted);
            text-transform: uppercase; letter-spacing: 0.04em;
        }
        .info-row .info-value {
            font-size: 0.84rem; color: var(--color-text-primary); font-weight: 600; margin-top: 1px;
        }

        /* ============================
           ALERTS & STATES
        ============================ */
        .locked-banner {
            background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;
            border-radius: 8px; padding: 10px 14px;
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 16px; font-weight: 600; font-size: 0.84rem;
        }

        .warning-clinical {
            background: #fffbeb; border: 1px solid #fde68a;
            border-radius: 8px; padding: 10px 14px;
            display: flex; align-items: flex-start; gap: 8px;
            margin-bottom: 16px; font-size: 0.84rem; color: #92400e;
        }

        .empty-state {
            text-align: center; padding: 2.5rem 1rem;
            background: var(--color-divider);
            border: 1px dashed var(--color-border);
            border-radius: 10px;
        }
        .empty-state i { color: var(--color-text-muted); opacity: 0.3; margin-bottom: 0.5rem; }
        .empty-state h5 { font-weight: 700; color: var(--color-text-muted); font-size: 0.92rem; }
        .empty-state p { font-size: 0.84rem; color: var(--color-text-muted); margin: 0; }

        .permission-note {
            color: var(--color-text-muted); font-size: 0.82rem; font-style: italic;
            padding: 10px 14px; background: var(--color-divider);
            border-radius: 6px; border: 1px solid var(--color-border); margin: 0;
        }

        /* ============================
           PAGE BANNER (simplified)
        ============================ */
        .page-banner {
            background: var(--color-primary);
            border-radius: var(--radius-lg);
            padding: 1.5rem 1.75rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.25rem;
        }
        .page-banner h1 {
            font-size: 1.35rem; font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 0.15rem;
        }
        .page-banner p {
            opacity: 0.75; margin: 0; font-size: 0.84rem;
        }
        .page-banner .banner-cta { position: relative; z-index: 1; }

        /* ============================
           SCROLLBAR
        ============================ */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.12); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(0,0,0,0.2); }

        /* ============================
           RESPONSIVE
        ============================ */
        @media (max-width: 991px) {
            :root { --sidebar-width: 68px; }

            .sidebar span.menu-text,
            .brand-tagline,
            .sidebar-user-info .brand-text-wrapper,
            .sidebar-menu-label { display: none; }

            .sidebar-brand { padding: 16px 10px; justify-content: center; }
            .brand-logo { margin: 0; }
            .brand-name { display: none; }

            .sidebar-menu-item a {
                justify-content: center;
                padding: 10px;
            }
            .menu-icon { font-size: 1.1rem; }

            .topbar { padding: 0 16px; }
            .sidebar-user-info { justify-content: center; padding: 16px 0; }
            .user-avatar { width: 30px; height: 30px; font-size: 0.78rem; }
        }

        @media (max-width: 767px) {
            :root { --sidebar-width: 0px; }
            .sidebar { transform: translateX(-100%); }
            .content-area { padding: 16px; }
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
                    <div class="brand-tagline">Nutrition Care & Patient Management</div>
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
                            class="fas fa-notes-medical menu-icon"></i><span class="menu-text">Diagnosis Gizi</span></a></div>
                <div class="sidebar-menu-item"><a href="{{ route('bahan-makanan.index') }}"
                        class="{{ request()->routeIs('bahan-makanan.*') ? 'active' : '' }}"><i
                            class="fas fa-book-medical menu-icon"></i><span class="menu-text">Database DKPI</span></a></div>
                <div class="sidebar-menu-item"><a href="{{ route('intervensi.index') }}"
                        class="{{ request()->routeIs('intervensi.*') ? 'active' : '' }}"><i
                            class="fas fa-file-prescription menu-icon"></i><span class="menu-text">Preskripsi Diet</span></a></div>
                <div class="sidebar-menu-item"><a href="{{ route('monitoring.index') }}"
                        class="{{ request()->routeIs('monitoring.*') ? 'active' : '' }}"><i
                            class="fas fa-heart-pulse menu-icon"></i><span class="menu-text">Monitoring</span></a></div>
                <div class="sidebar-menu-item"><a href="{{ route('laporan.index') }}"
                        class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}"><i
                            class="fas fa-file-waveform menu-icon"></i><span class="menu-text">Laporan</span></a></div>
                <div class="sidebar-menu-item"><a href="{{ route('dapur.index') }}"
                        class="{{ request()->routeIs('dapur.*') ? 'active' : '' }}"><i
                            class="fas fa-concierge-bell menu-icon"></i><span class="menu-text">Dapur & Etiket</span></a></div>
            @endif
            @if (Auth::user()->peran === 'admin_ti')
                <div class="sidebar-menu-label">Administrator</div>
                <div class="sidebar-menu-item">
                    <a href="{{ route('admin.pengguna') }}" class="{{ request()->routeIs('admin.pengguna') ? 'active' : '' }}">
                        <i class="fas fa-users-cog menu-icon"></i> <span class="menu-text">Manajemen Akun</span>
                    </a>
                </div>
                <div class="sidebar-menu-item">
                    <a href="{{ route('admin.audit-logs') }}" class="{{ request()->routeIs('admin.audit-logs') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt menu-icon"></i> <span class="menu-text">Log Audit RME</span>
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
                <div class="breadcrumb-text">@yield('breadcrumb', 'NCPMS')</div>
                <div class="topbar-right">
                    <form action="{{ route('logout') }}" method="POST" class="m-0">@csrf<button
                            class="btn-logout"><i class="fas fa-sign-out-alt"></i> Keluar</button>
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
                    confirmButtonColor: '#DC2626',
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
                    confirmButtonColor: '#0f766e',
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
                confirmButtonColor: '#f59e0b',
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
