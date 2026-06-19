<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NCPMS') - Nutrition Care and Patient Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary:#1A7A64; --color-primary-dark:#145C4B; --color-primary-darker:#0E3F35;
            --color-primary-light:#2BA882; --color-primary-subtle:#E8F5F1; --color-primary-border:#B2DDD4;
            --color-accent:#F4A830; --color-accent-dark:#D4891A; --color-risiko-tinggi:#D93025;
            --color-risiko-sedang:#E67E22; --color-risiko-rendah:#27AE60; --color-bg-page:#F0F4F3;
            --color-bg-card:#FFFFFF; --color-bg-sidebar:#0E3F35; --color-text-primary:#1C2A27;
            --color-text-secondary:#4A6560; --color-text-muted:#8AA09A; --color-border:#D4E6E1;
            --color-divider:#EAF2EF; --sidebar-width:240px; --topbar-height:60px;
            --font-primary:'Plus Jakarta Sans', sans-serif; --font-secondary:'Inter', sans-serif; --font-mono:'JetBrains Mono', monospace;
        }
        * { box-sizing: border-box; }
        body { margin:0; font-family:var(--font-primary); background:var(--color-bg-page); color:var(--color-text-primary); font-size:.875rem; }
        a { color: var(--color-primary); }
        .sidebar { width:var(--sidebar-width); background:var(--color-bg-sidebar); position:fixed; inset:0 auto 0 0; z-index:1030; display:flex; flex-direction:column; overflow-y:auto; }
        .sidebar-brand { padding:20px; border-bottom:1px solid rgba(255,255,255,.1); }
        .brand-name { color:#fff; font-weight:800; font-size:1.125rem; text-decoration:none; display:block; }
        .brand-tagline { color:rgba(255,255,255,.55); font-size:.6875rem; margin-top:2px; }
        .sidebar-menu-label { color:rgba(255,255,255,.38); font-size:.625rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; padding:18px 20px 8px; }
        .sidebar-menu-item a { display:flex; align-items:center; gap:10px; padding:10px 20px; color:rgba(255,255,255,.72); text-decoration:none; font-weight:500; }
        .sidebar-menu-item a:hover,.sidebar-menu-item a.active { background:var(--color-primary); color:#fff; }
        .menu-icon { width:18px; text-align:center; }
        .sidebar-user-info { margin-top:auto; padding:16px 20px; border-top:1px solid rgba(255,255,255,.1); color:#fff; }
        .topbar { position:fixed; top:0; left:var(--sidebar-width); right:0; height:var(--topbar-height); background:#fff; border-bottom:1px solid var(--color-border); display:flex; align-items:center; justify-content:space-between; padding:0 24px; z-index:1020; }
        .main-wrapper { margin-left:var(--sidebar-width); padding-top:var(--topbar-height); }
        .content-area { padding:24px; min-height:calc(100vh - var(--topbar-height)); }
        .page-header { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:24px; flex-wrap:wrap; }
        .page-title { font-size:1.375rem; font-weight:700; margin:0; }
        .page-subtitle { color:var(--color-text-muted); font-size:.8125rem; margin:2px 0 0; }
        .ncpms-card,.stat-card { background:#fff; border:1px solid var(--color-border); border-radius:12px; box-shadow:0 1px 3px rgba(26,122,100,.06),0 1px 2px rgba(0,0,0,.04); }
        .ncpms-card { padding:24px; margin-bottom:16px; }
        .card-title-custom { font-size:1.0625rem; font-weight:700; display:flex; gap:8px; align-items:center; margin:0 0 18px; padding-bottom:14px; border-bottom:1px solid var(--color-divider); }
        .card-title-icon { width:32px; height:32px; border-radius:8px; background:var(--color-primary-subtle); color:var(--color-primary); display:inline-flex; align-items:center; justify-content:center; }
        .stat-card { padding:20px 24px; border-top:3px solid var(--color-primary); position:relative; overflow:hidden; }
        .stat-label { color:var(--color-text-muted); font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; }
        .stat-value { color:var(--color-primary-dark); font-family:var(--font-secondary); font-size:1.875rem; font-weight:800; line-height:1.1; }
        .btn-primary-ncpms,.btn-accent-ncpms,.btn-danger-ncpms,.btn-outline-ncpms { border-radius:8px; font-weight:600; padding:9px 16px; display:inline-flex; align-items:center; gap:6px; text-decoration:none; border:0; }
        .btn-primary-ncpms { background:var(--color-primary); color:#fff; }
        .btn-primary-ncpms:hover { background:var(--color-primary-dark); color:#fff; }
        .btn-accent-ncpms { background:var(--color-accent); color:#fff; }
        .btn-danger-ncpms { background:var(--color-risiko-tinggi); color:#fff; }
        .btn-outline-ncpms { border:1.5px solid var(--color-primary); color:var(--color-primary); background:transparent; }
        .btn-sm-ncpms { padding:5px 10px; font-size:.8125rem; }
        .form-label-ncpms { font-size:.8125rem; font-weight:600; color:var(--color-text-secondary); margin-bottom:6px; }
        .form-control-ncpms { border:1.5px solid #C0D8D2; border-radius:8px; padding:9px 12px; width:100%; }
        .form-control-ncpms:focus { outline:none; border-color:var(--color-primary); box-shadow:0 0 0 3px rgba(26,122,100,.12); }
        .table thead th { background:var(--color-primary-subtle); color:var(--color-primary-dark); font-size:.75rem; text-transform:uppercase; letter-spacing:.04em; }
        .text-mono { font-family:var(--font-mono); }
        .badge-risk { border-radius:6px; padding:5px 8px; font-weight:700; font-size:.72rem; }
        .risk-risiko_tinggi { background:#FDF2F2; color:var(--color-risiko-tinggi); }
        .risk-risiko_sedang { background:#FEF9EC; color:var(--color-risiko-sedang); }
        .risk-risiko_rendah { background:#F0FAF4; color:var(--color-risiko-rendah); }
        .locked-banner { background:var(--color-primary-darker); color:#fff; border-radius:12px; padding:14px 18px; font-weight:700; margin-bottom:16px; }
        .chart-wrap { height:260px; }
        @media (max-width: 991px) { :root { --sidebar-width: 72px; } .sidebar span.menu-text,.brand-tagline,.sidebar-user-info,.sidebar-menu-label { display:none; } .sidebar-brand { padding:18px 10px; text-align:center; } .topbar { padding:0 12px; } }
        @media (max-width: 767px) { :root { --sidebar-width: 0px; } .sidebar { display:none; } .content-area { padding:16px; } }
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
        <div class="sidebar-menu-item"><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fas fa-chart-line menu-icon"></i><span class="menu-text">Dashboard</span></a></div>
        @if(in_array(Auth::user()->peran, ['perawat','nutrisionis','dietisien','spgk']))
            <div class="sidebar-menu-item"><a href="{{ route('pasien.index') }}" class="{{ request()->routeIs('pasien.*') || request()->routeIs('kunjungan.*') ? 'active' : '' }}"><i class="fas fa-hospital-user menu-icon"></i><span class="menu-text">Pasien & Kunjungan</span></a></div>
        @endif
        @if(in_array(Auth::user()->peran, ['dietisien','spgk']))
            <div class="sidebar-menu-item"><a href="{{ route('diagnosis.index') }}" class="{{ request()->routeIs('diagnosis.*') ? 'active' : '' }}"><i class="fas fa-stethoscope menu-icon"></i><span class="menu-text">Diagnosis Gizi</span></a></div>
            <div class="sidebar-menu-item"><a href="{{ route('intervensi.index') }}" class="{{ request()->routeIs('intervensi.*') ? 'active' : '' }}"><i class="fas fa-utensils menu-icon"></i><span class="menu-text">Preskripsi Diet</span></a></div>
            <div class="sidebar-menu-item"><a href="{{ route('monitoring.index') }}" class="{{ request()->routeIs('monitoring.*') ? 'active' : '' }}"><i class="fas fa-heart-pulse menu-icon"></i><span class="menu-text">Monitoring</span></a></div>
            <div class="sidebar-menu-item"><a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}"><i class="fas fa-file-medical-alt menu-icon"></i><span class="menu-text">Laporan</span></a></div>
        @endif
        @if(Auth::user()->peran === 'admin_ti')
            <div class="sidebar-menu-label">Administrator</div>
            <div class="sidebar-menu-item"><a href="{{ route('admin.pengguna.index') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}"><i class="fas fa-user-shield menu-icon"></i><span class="menu-text">Manajemen Akun</span></a></div>
        @endif
        <div class="sidebar-user-info">
            <div class="fw-bold">{{ Auth::user()->nama_lengkap }}</div>
            <div class="small text-white-50">{{ Auth::user()->nama_peran }}</div>
        </div>
    </aside>
    <div class="main-wrapper">
        <header class="topbar">
            <div class="small text-muted">@yield('breadcrumb', 'NCPMS')</div>
            <form action="{{ route('logout') }}" method="POST" class="m-0">@csrf<button class="btn btn-sm btn-outline-danger"><i class="fas fa-right-from-bracket"></i> Keluar</button></form>
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
        sukses: pesan => Swal.fire({ icon:'success', title:'Berhasil!', text:pesan, toast:true, position:'top-end', timer:2400, showConfirmButton:false, timerProgressBar:true }),
        error: pesan => Swal.fire({ icon:'error', title:'Terjadi Kesalahan', text:pesan }),
        konfirmasiHapus: (form, teks='Data yang dihapus tidak dapat dikembalikan.') => {
            Swal.fire({ title:'Hapus Data?', text:teks, icon:'warning', showCancelButton:true, confirmButtonText:'Ya, Hapus', cancelButtonText:'Batal', confirmButtonColor:'#D93025', reverseButtons:true }).then(r => { if (r.isConfirmed) form.submit(); });
        },
        konfirmasiKunci: form => {
            Swal.fire({ title:'Kunci Dokumen?', html:'Dokumen akan dikunci dan <strong>tidak dapat diubah</strong>.', icon:'warning', showCancelButton:true, confirmButtonText:'Ya, Kunci Dokumen', cancelButtonText:'Batal', confirmButtonColor:'#145C4B', reverseButtons:true }).then(r => { if (r.isConfirmed) form.submit(); });
        },
        peringatanKlinis: (judul, pesan) => Swal.fire({ icon:'warning', title:judul, html:pesan, confirmButtonText:'Saya Mengerti', confirmButtonColor:'#E67E22', allowOutsideClick:false })
    };
    document.addEventListener('submit', function(e) {
        if (e.target.matches('[data-confirm-delete]')) { e.preventDefault(); NCPMS_SWAL.konfirmasiHapus(e.target); }
        if (e.target.matches('[data-confirm-lock]')) { e.preventDefault(); NCPMS_SWAL.konfirmasiKunci(e.target); }
    });
    @if(session('swal_success')) NCPMS_SWAL.sukses(@json(session('swal_success'))); @endif
    @if(session('swal_error')) NCPMS_SWAL.error(@json(session('swal_error'))); @endif
    @if($errors->any()) NCPMS_SWAL.error(@json($errors->first())); @endif
</script>
@stack('scripts')
</body>
</html>
