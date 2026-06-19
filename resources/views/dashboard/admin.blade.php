@extends('layouts.app')
@section('title','Dashboard Administrator')
@section('breadcrumb','Dashboard / Administrator')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard Administrator TI</h1>
        <p class="page-subtitle">Pemantauan akun dan aktivitas sistem tanpa data klinis pasien.</p>
    </div>
    <a href="{{ route('admin.pengguna.index') }}" class="btn-primary-ncpms"><i class="fas fa-user-shield"></i> Manajemen Akun</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">Total Akun</div><div class="stat-value">{{ $totalPengguna }}</div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">Akun Aktif</div><div class="stat-value">{{ $penggunaAktif }}</div></div></div>
    <div class="col-md-2"><div class="stat-card"><div class="stat-label">Login Hari Ini</div><div class="stat-value">{{ $loginHariIni }}</div></div></div>
    <div class="col-md-2"><div class="stat-card"><div class="stat-label">Login Gagal</div><div class="stat-value" style="color:var(--color-risiko-sedang)">{{ $loginGagalHariIni }}</div></div></div>
    <div class="col-md-2"><div class="stat-card"><div class="stat-label">Timeout</div><div class="stat-value" style="color:var(--color-risiko-tinggi)">{{ $timeoutHariIni }}</div></div></div>
</div>

<div class="ncpms-card">
    <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-clock-rotate-left"></i></span> Aktivitas Autentikasi Terakhir</h2>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Waktu</th><th>Pengguna</th><th>Event</th><th>IP</th><th>User Agent</th></tr></thead>
            <tbody>
            @forelse($loginTerakhir as $log)
                <tr>
                    <td>{{ $log->created_at?->format('d/m/Y H:i') }}</td>
                    <td>{{ $log->pengguna->nama_lengkap ?? 'Tidak diketahui' }}</td>
                    <td>{{ str_replace('_',' ', strtoupper($log->tipe_event)) }}</td>
                    <td class="text-mono">{{ $log->ip_address }}</td>
                    <td class="text-muted small">{{ \Illuminate\Support\Str::limit($log->user_agent, 90) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada aktivitas autentikasi.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
