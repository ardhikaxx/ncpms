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

        .content-area {
            padding: 32px;
            min-height: calc(100vh - var(--topbar-height));
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--color-text-primary);
            letter-spacing: -0.02em;
            margin: 0;
        }

        .page-subtitle {
            color: var(--color-text-muted);
            font-size: 0.9rem;
            font-weight: 500;
            margin: 4px 0 0;
        }

        .ncpms-card,
        .stat-card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
            transition: var(--transition-all);
        }

        .ncpms-card:hover, .stat-card:hover {
            box-shadow: var(--shadow-md);
        }

        .ncpms-card {
            padding: 28px;
            margin-bottom: 24px;
        }

        .card-title-custom {
            font-size: 1.15rem;
            font-weight: 800;
            display: flex;
            gap: 12px;
            align-items: center;
            margin: 0 0 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--color-divider);
            color: var(--color-text-primary);
        }

        .card-title-icon {
            width: 38px;
            height: 38px;
            border-radius: var(--border-radius-md);
            background: linear-gradient(135deg, var(--color-primary-light) 0%, var(--color-primary) 100%);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(18, 130, 96, 0.2);
        }

        .stat-card {
            padding: 24px;
            border-top: 4px solid var(--color-primary);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; width: 40%;
            background: linear-gradient(90deg, transparent, rgba(26, 122, 100, 0.03));
            pointer-events: none;
        }

        .stat-label {
            color: var(--color-text-muted);
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 8px;
        }

        .stat-value {
            color: var(--color-primary-darker);
            font-family: var(--font-secondary);
            font-size: 2.25rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.03em;
        }

        .btn-primary-ncpms,
        .btn-accent-ncpms,
        .btn-danger-ncpms,
        .btn-outline-ncpms {
            border-radius: var(--border-radius-md);
            font-weight: 700;
            padding: 10px 20px;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            border: 0;
            transition: var(--transition-all);
            cursor: pointer;
        }

        .btn-primary-ncpms {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(18, 130, 96, 0.2);
        }

        .btn-primary-ncpms:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(18, 130, 96, 0.3);
            color: #fff;
        }

        .btn-accent-ncpms {
            background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-dark) 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(245, 159, 0, 0.2);
        }
        
        .btn-accent-ncpms:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(245, 159, 0, 0.3);
            color: #fff;
        }

        .btn-danger-ncpms {
            background: var(--color-risiko-tinggi);
            color: #fff;
            box-shadow: 0 4px 12px rgba(224, 49, 49, 0.2);
        }

        .btn-danger-ncpms:hover {
            transform: translateY(-2px);
            background: #c92a2a;
            box-shadow: 0 6px 16px rgba(224, 49, 49, 0.3);
            color: #fff;
        }

        .btn-outline-ncpms {
            border: 2px solid var(--color-primary-border);
            color: var(--color-primary-dark);
            background: transparent;
            font-weight: 700;
        }

        .btn-outline-ncpms:hover {
            border-color: var(--color-primary);
            background: var(--color-primary-subtle);
            color: var(--color-primary-dark);
        }

        .btn-sm-ncpms {
            padding: 6px 12px;
            font-size: 0.85rem;
            border-width: 1.5px;
        }

        .form-label-ncpms {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--color-text-secondary);
            margin-bottom: 8px;
            display: block;
        }

        .form-control-ncpms {
            border: 1.5px solid var(--color-border);
            border-radius: var(--border-radius-md);
            padding: 12px 16px;
            width: 100%;
            font-size: 0.95rem;
            font-family: var(--font-secondary);
            color: var(--color-text-primary);
            transition: var(--transition-all);
            background: #fff;
        }

        .form-control-ncpms:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px var(--color-primary-subtle);
        }

        .table {
            margin-bottom: 0;
        }
        
        .table > :not(caption) > * > * {
            padding: 16px;
            border-bottom-color: var(--color-divider);
        }

        .table thead th {
            background: var(--color-primary-subtle);
            color: var(--color-primary-darker);
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 0;
        }
        
        .table tbody tr {
            transition: var(--transition-all);
        }
        
        .table tbody tr:hover {
            background-color: #fafcfb;
        }

        .text-mono {
            font-family: var(--font-mono);
            font-size: 0.9em;
            background: var(--color-divider);
            padding: 2px 6px;
            border-radius: 4px;
            color: var(--color-text-secondary);
        }

        .badge-risk {
            border-radius: 20px;
            padding: 6px 12px;
            font-weight: 800;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .risk-risiko_tinggi {
            background: #fff5f5;
            color: var(--color-risiko-tinggi);
            border: 1px solid #ffe3e3;
        }

        .risk-risiko_sedang {
            background: #fff9db;
            color: var(--color-risiko-sedang);
            border: 1px solid #ffec99;
        }

        .risk-risiko_rendah {
            background: #ebfbee;
            color: var(--color-risiko-rendah);
            border: 1px solid #d3f9d8;
        }

        .locked-banner {
            background: linear-gradient(135deg, var(--color-primary-darker) 0%, #05261c 100%);
            color: #fff;
            border-radius: var(--border-radius-lg);
            padding: 16px 24px;
            font-weight: 700;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.05rem;
        }

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
            .sidebar-user-info,
            .sidebar-menu-label {
                display: none;
            }

            .sidebar-brand {
                padding: 24px 10px;
                text-align: center;
            }
            
            .brand-name {
                font-size: 1rem;
            }

            .sidebar-menu-item a {
                justify-content: center;
                padding: 14px;
            }
            
            .menu-icon {
                font-size: 1.3rem;
            }

            .topbar {
                padding: 0 20px;
            }
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
                <a href="{{ route('dashboard') }}" class="brand-name">NCPMS</a>
                <div class="brand-tagline">Presisi Gizi, Akurasi Asuhan</div>
            </div>
            <div class="sidebar-menu-label">Menu Utama</div>
            <div class="sidebar-menu-item"><a href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i
                        class="fas fa-chart-line menu-icon"></i><span class="menu-text">Dashboard</span></a></div>
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
                <div class="sidebar-menu-label"
                    style="font-size: 0.625rem; color: rgba(255,255,255,0.35); padding: 20px 20px 8px; text-transform: uppercase; font-weight: bold;">
                    Administrator</div>
                <div class="sidebar-menu-item">
                    <a href="{{ route('admin.pengguna') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        <i class="fas fa-user-cog menu-icon"></i> Manajemen Akun
                    </a>
                </div>
            @endif


            <div class="sidebar-user-info">
                <div class="fw-bold">{{ Auth::user()->nama_lengkap }}</div>
                <div class="small text-white-50">{{ Auth::user()->nama_peran }}</div>
            </div>
        </aside>
        <div class="main-wrapper">
            <header class="topbar">
                <div class="small text-muted">@yield('breadcrumb', 'NCPMS')</div>
                <form action="{{ route('logout') }}" method="POST" class="m-0">@csrf<button
                        class="btn btn-sm btn-outline-danger"><i class="fas fa-right-from-bracket"></i> Keluar</button>
                </form>
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
