@extends('layouts.app')
@section('title','Dashboard Administrator')
@section('breadcrumb','Dashboard / Administrator')

@section('content')

<div class="page-banner">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="mb-1">Administrator TI 🛡️</h1>
            <p>Pemantauan sistem, aktivitas log, dan manajemen akun tanpa akses data klinis.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0 banner-cta">
            <a href="{{ route('admin.pengguna.index') }}"
               class="btn btn-light fw-bold px-4 py-2"
               style="border-radius: 50px; color: var(--color-primary-dark); box-shadow: 0 4px 12px rgba(0,0,0,0.12);">
                <i class="fas fa-user-shield me-2"></i>Manajemen Akun
            </a>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-accent" style="background: #3b82f6;"></div>
            <div class="d-flex justify-content-between align-items-start ps-1">
                <div><div class="stat-label">Total Akun</div><div class="stat-value">{{ $totalPengguna }}</div></div>
                <div class="stat-icon" style="background: #eff6ff; color: #3b82f6;"><i class="fas fa-users"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-accent" style="background: var(--color-primary);"></div>
            <div class="d-flex justify-content-between align-items-start ps-1">
                <div><div class="stat-label">Akun Aktif</div><div class="stat-value">{{ $penggunaAktif }}</div></div>
                <div class="stat-icon" style="background: var(--color-primary-subtle); color: var(--color-primary);"><i class="fas fa-user-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-card">
            <div class="stat-card-accent" style="background: #8b5cf6;"></div>
            <div class="d-flex justify-content-between align-items-start ps-1">
                <div><div class="stat-label">Login Hari Ini</div><div class="stat-value">{{ $loginHariIni }}</div></div>
                <div class="stat-icon" style="background: #f5f3ff; color: #8b5cf6;"><i class="fas fa-sign-in-alt"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-card">
            <div class="stat-card-accent" style="background: #f59f00;"></div>
            <div class="d-flex justify-content-between align-items-start ps-1">
                <div><div class="stat-label">Login Gagal</div><div class="stat-value" style="color: #f59f00;">{{ $loginGagalHariIni }}</div></div>
                <div class="stat-icon" style="background: #fef3c7; color: #f59f00;"><i class="fas fa-ban"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-card">
            <div class="stat-card-accent" style="background: var(--color-risiko-tinggi);"></div>
            <div class="d-flex justify-content-between align-items-start ps-1">
                <div><div class="stat-label">Session Timeout</div><div class="stat-value" style="color: var(--color-risiko-tinggi);">{{ $timeoutHariIni }}</div></div>
                <div class="stat-icon" style="background: #fee2e2; color: var(--color-risiko-tinggi);"><i class="fas fa-clock-rotate-left"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- Auth Log Table --}}
<div class="ncpms-card mb-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="card-title-custom mb-0 pb-0 border-0">
            <span class="card-title-icon" style="background: var(--color-primary); color: #fff;"><i class="fas fa-server"></i></span>
            Aktivitas Autentikasi Terakhir
        </div>
        <span class="badge-pill badge-soft-gray"><i class="fas fa-history me-1"></i>Log Keamanan</span>
    </div>
    <div class="table-responsive" style="border-radius: 10px; border: 1px solid var(--color-border);">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="padding-left: 16px;">Waktu</th>
                    <th>Pengguna</th>
                    <th>Event</th>
                    <th>Alamat IP</th>
                    <th style="padding-right: 16px;">User Agent</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loginTerakhir as $log)
                <tr>
                    <td style="padding-left: 16px;">
                        <div class="fw-bold" style="font-size: 0.87rem;">{{ $log->created_at?->format('d M Y') }}</div>
                        <div class="text-muted" style="font-size: 0.74rem;"><i class="far fa-clock me-1"></i>{{ $log->created_at?->format('H:i:s') }}</div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-circle" style="font-size: 0.85rem;">{{ substr($log->pengguna->nama_lengkap ?? '?', 0, 1) }}</div>
                            <div>
                                <div class="fw-bold" style="font-size: 0.87rem;">{{ $log->pengguna->nama_lengkap ?? 'Tidak diketahui' }}</div>
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
                    <td style="padding-right: 16px; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.78rem; color: var(--color-text-muted);" title="{{ $log->user_agent }}">
                        <i class="fas fa-desktop opacity-50 me-1"></i>{{ \Illuminate\Support\Str::limit($log->user_agent, 40) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state py-4">
                            <i class="fas fa-shield-alt fa-2x d-block"></i>
                            <h5 class="mt-2">Belum Ada Log</h5>
                            <p>Belum ada aktivitas autentikasi tercatat.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ duration: 600, once: true });</script>
@endpush
