@extends('layouts.app')
@section('title','Dashboard Administrator')
@section('breadcrumb','Dashboard / Administrator')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Administrator TI</h1>
        <p class="page-subtitle">Pemantauan sistem, aktivitas log, dan manajemen akun tanpa akses data klinis.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.pengguna.index') }}" class="btn btn-primary">
            <i class="fas fa-users-cog me-1"></i> Manajemen Akun
        </a>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Akun</div>
                    <div class="stat-value">{{ $totalPengguna }}</div>
                </div>
                <div class="stat-icon" style="background: var(--color-primary-subtle); color: var(--color-primary);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Akun Aktif</div>
                    <div class="stat-value">{{ $penggunaAktif }}</div>
                </div>
                <div class="stat-icon" style="background: var(--color-primary-subtle); color: var(--color-primary);">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Login Hari Ini</div>
                    <div class="stat-value">{{ $loginHariIni }}</div>
                </div>
                <div class="stat-icon" style="background: var(--color-primary-subtle); color: var(--color-primary);">
                    <i class="fas fa-right-to-bracket"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Login Gagal</div>
                    <div class="stat-value" style="color: var(--color-risiko-tinggi);">{{ $loginGagalHariIni }}</div>
                </div>
                <div class="stat-icon" style="background: var(--color-primary-subtle); color: var(--color-primary);">
                    <i class="fas fa-ban"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Auth Log Table --}}
<div class="ncpms-card mb-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="card-title-custom mb-0 pb-0 border-0">
            <span class="card-title-icon"><i class="fas fa-shield-halved"></i></span>
            Aktivitas Autentikasi Terakhir
        </div>
        <span class="badge-pill badge-soft-gray">Log Keamanan</span>
    </div>
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Event</th>
                    <th>Alamat IP</th>
                    <th>User Agent</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loginTerakhir as $log)
                <tr>
                    <td>
                        <div class="fw-semibold" style="font-size: 0.87rem;">{{ $log->created_at?->format('d M Y') }}</div>
                        <div class="text-muted" style="font-size: 0.74rem;">{{ $log->created_at?->format('H:i:s') }}</div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-circle">{{ substr($log->pengguna->nama_lengkap ?? '?', 0, 1) }}</div>
                            <div>
                                <div class="fw-semibold" style="font-size: 0.87rem;">{{ $log->pengguna->nama_lengkap ?? 'Tidak diketahui' }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">ID: {{ $log->pengguna_id ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php $ev = $log->tipe_event; @endphp
                        <span class="badge-pill
                            @if($ev=='login_sukses') badge-soft-success
                            @elseif($ev=='login_gagal') badge-soft-warning
                            @elseif($ev=='logout') badge-soft-gray
                            @elseif($ev=='session_timeout') badge-soft-danger
                            @else badge-soft-gray @endif">
                            {{ str_replace('_',' ', strtoupper($ev)) }}
                        </span>
                    </td>
                    <td><span class="rm-badge">{{ $log->ip_address }}</span></td>
                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.78rem; color: var(--color-text-muted);" title="{{ $log->user_agent }}">
                        {{ \Illuminate\Support\Str::limit($log->user_agent, 40) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-4 text-center text-muted" style="font-size: 0.87rem;">
                        Belum ada aktivitas autentikasi tercatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
