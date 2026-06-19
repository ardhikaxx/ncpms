@extends('layouts.app')
@section('title','Dashboard Administrator')
@section('breadcrumb','Dashboard / Administrator')

@push('styles')
    <!-- Animate.css for premium entrance animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .admin-banner {
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            border-radius: 20px;
            padding: 2.5rem 3rem;
            color: white;
            box-shadow: 0 15px 35px rgba(15,32,39,0.3);
            position: relative;
            overflow: hidden;
        }
        .admin-banner::before {
            content: ''; position: absolute; right: -5%; top: -20%; width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%); border-radius: 50%;
        }
        .admin-banner::after {
            content: ''; position: absolute; right: 15%; bottom: -50%; width: 250px; height: 250px;
            background: radial-gradient(circle, rgba(0,255,255,0.05) 0%, transparent 70%); border-radius: 50%;
        }
        
        .stat-card-admin {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            height: 100%;
        }
        .stat-card-admin:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(15,32,39,0.1);
        }
        .stat-card-admin::before {
            content: ''; position: absolute; left: 0; top: 0; height: 100%; width: 4px;
        }
        
        .sca-blue::before { background: #007bff; }
        .sca-cyan::before { background: #17a2b8; }
        .sca-purple::before { background: #6f42c1; }
        .sca-orange::before { background: #fd7e14; }
        .sca-red::before { background: #dc3545; }

        .table-hover-admin tbody tr { transition: all 0.2s; }
        .table-hover-admin tbody tr:hover { background-color: rgba(44, 83, 100, 0.04) !important; transform: scale(1.002); }
        
        .event-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
        }
    </style>
@endpush

@section('content')

<!-- Welcome Banner -->
<div class="admin-banner mb-5 animate__animated animate__fadeInDown">
    <div class="row align-items-center position-relative z-index-1">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-2" style="font-size: 2.5rem; letter-spacing: -0.02em;">
                Administrator TI <span style="font-size: 2rem;">🛡️</span>
            </h1>
            <p class="fs-5 opacity-75 mb-0" style="font-family: var(--font-secondary);">Pemantauan sistem, aktivitas log, dan manajemen akun tanpa akses data klinis.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
            <a href="{{ route('admin.pengguna.index') }}" class="btn btn-light fw-bold px-4 py-3" style="border-radius: 50px; color: #0f2027; box-shadow: 0 8px 15px rgba(0,0,0,0.2); transition: transform 0.2s;">
                <i class="fas fa-user-shield me-2 fs-5"></i> Manajemen Akun
            </a>
        </div>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card-admin sca-blue">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Total Akun</div>
                    <div class="fs-2 fw-bold text-dark">{{ $totalPengguna }}</div>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px; background: rgba(0,123,255,0.1); color: #007bff; font-size: 1.5rem;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-card-admin sca-cyan">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Akun Aktif</div>
                    <div class="fs-2 fw-bold text-dark">{{ $penggunaAktif }}</div>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px; background: rgba(23,162,184,0.1); color: #17a2b8; font-size: 1.5rem;">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-card-admin sca-purple">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Login Hari Ini</div>
                    <div class="fs-3 fw-bold text-dark">{{ $loginHariIni }}</div>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 44px; height: 44px; background: rgba(111,66,193,0.1); color: #6f42c1; font-size: 1.2rem;">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2" data-aos="fade-up" data-aos-delay="400">
        <div class="stat-card-admin sca-orange">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-warning fw-bold text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Login Gagal</div>
                    <div class="fs-3 fw-bold text-warning">{{ $loginGagalHariIni }}</div>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 44px; height: 44px; background: rgba(253,126,20,0.1); color: #fd7e14; font-size: 1.2rem;">
                    <i class="fas fa-ban"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2" data-aos="fade-up" data-aos-delay="500">
        <div class="stat-card-admin sca-red">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-danger fw-bold text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Timeout</div>
                    <div class="fs-3 fw-bold text-danger">{{ $timeoutHariIni }}</div>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 44px; height: 44px; background: #dc3545; color: #fff; font-size: 1.2rem; {{ $timeoutHariIni > 0 ? 'animation: pulse 2s infinite;' : '' }}">
                    <i class="fas fa-clock-rotate-left"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="ncpms-card mb-0 shadow-sm" style="border-radius: 20px;" data-aos="fade-up" data-aos-delay="600">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <h2 class="card-title-custom border-0 mb-0 pb-0">
            <span class="card-title-icon" style="background: linear-gradient(135deg, #0f2027 0%, #203a43 100%); color: white;">
                <i class="fas fa-server"></i>
            </span> 
            Aktivitas Autentikasi Terakhir
        </h2>
        <div class="badge bg-light text-dark border px-3 py-2 fs-6 rounded-pill">
            <i class="fas fa-history me-1"></i> Log Keamanan
        </div>
    </div>
    
    <div class="table-responsive" style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(0,0,0,0.05);">
        <table class="table align-middle table-hover-admin mb-0">
            <thead style="background: rgba(15,32,39,0.03);">
                <tr>
                    <th class="ps-4">Waktu</th>
                    <th>Pengguna</th>
                    <th>Event</th>
                    <th>Alamat IP</th>
                    <th class="pe-4">User Agent</th>
                </tr>
            </thead>
            <tbody>
            @forelse($loginTerakhir as $log)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold text-dark">{{ $log->created_at?->format('d M Y') }}</div>
                        <div class="text-muted small"><i class="far fa-clock"></i> {{ $log->created_at?->format('H:i:s') }} WIB</div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #2c5364, #0f2027); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1rem;">
                                {{ substr($log->pengguna->nama_lengkap ?? '?', 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $log->pengguna->nama_lengkap ?? 'Tidak diketahui' }}</div>
                                <div class="text-muted small" style="font-size: 0.7rem;">ID: {{ $log->pengguna_id ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php $ev = $log->tipe_event; @endphp
                        <span class="event-badge @if($ev=='login_sukses') bg-success-subtle text-success border border-success-subtle @elseif($ev=='login_gagal') bg-warning-subtle text-warning border border-warning-subtle @elseif($ev=='logout') bg-secondary-subtle text-secondary border border-secondary-subtle @elseif($ev=='session_timeout') bg-danger-subtle text-danger border border-danger-subtle @else bg-light text-dark @endif">
                            {{ str_replace('_',' ', strtoupper($ev)) }}
                        </span>
                    </td>
                    <td>
                        <div class="text-mono fw-bold text-primary" style="font-size: 0.85rem; background: rgba(0,123,255,0.05); padding: 4px 8px; border-radius: 6px; display: inline-block;">
                            {{ $log->ip_address }}
                        </div>
                    </td>
                    <td class="pe-4">
                        <div class="text-muted small" style="font-size: 0.75rem; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $log->user_agent }}">
                            <i class="fas fa-desktop opacity-50 me-1"></i> {{ \Illuminate\Support\Str::limit($log->user_agent, 45) }}
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="fas fa-shield-alt fa-3x mb-3 opacity-25"></i><br>
                        Belum ada aktivitas autentikasi tercatat.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<!-- AOS Script -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 50
    });
</script>
@endpush
