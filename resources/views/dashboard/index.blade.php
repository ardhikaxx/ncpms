@extends('layouts.app')
@section('title', 'Dashboard Klinis')
@section('breadcrumb', 'Dashboard')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Selamat datang, {{ Auth::user()->nama_lengkap }}</h1>
        <p class="page-subtitle">Ringkasan analitik gizi klinis — {{ date('l, d F Y') }}</p>
    </div>
    <div class="d-flex gap-2">
        @if(in_array(Auth::user()->peran, ['perawat','spgk']))
        <a href="{{ route('pasien.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Registrasi Pasien
        </a>
        @endif
    </div>
</div>

@if($pasienPuasa->count() > 0)
<div class="alert alert-danger shadow-sm border-0 d-flex align-items-center mb-4" style="animation: pulse-danger 2s infinite; background: #fee2e2;">
    <i class="fas fa-exclamation-triangle fa-2x me-3 text-danger"></i>
    <div>
        <h5 class="fw-bold mb-1 text-danger">⚠️ STATUS PUASA DARURAT (NPO)</h5>
        <p class="mb-0 text-danger" style="font-size: 0.9rem;">
            Hentikan persiapan / penyajian makanan untuk <strong>{{ $pasienPuasa->count() }}</strong> pasien berikut:
            @foreach($pasienPuasa as $p)
                <span class="badge bg-danger ms-1">{{ $p->pasien->nama_lengkap }} ({{ $p->asal_rujukan }})</span>
            @endforeach
        </p>
    </div>
</div>
<style>
    @keyframes pulse-danger {
        0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(220, 38, 38, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
    }
</style>
@endif

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Pasien</div>
                    <div class="stat-value">{{ $totalPasien }}</div>
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
                    <div class="stat-label">Kunjungan Hari Ini</div>
                    <div class="stat-value">{{ $totalKunjunganHariIni }}</div>
                </div>
                <div class="stat-icon" style="background: var(--color-primary-subtle); color: var(--color-primary);">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Risiko Tinggi</div>
                    <div class="stat-value" style="color: var(--color-risiko-tinggi);">{{ $risikoTinggi }}</div>
                </div>
                <div class="stat-icon" style="background: var(--color-primary-subtle); color: var(--color-primary);">
                    <i class="fas fa-triangle-exclamation"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Menunggu Asesmen</div>
                    <div class="stat-value">{{ $menungguAsesmen }}</div>
                </div>
                <div class="stat-icon" style="background: var(--color-primary-subtle); color: var(--color-primary);">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts + Table --}}
<div class="row g-3 mb-3">
    <div class="col-lg-5">
        <div class="ncpms-card mb-0 h-100">
            <div class="card-title-custom">
                <span class="card-title-icon"><i class="fas fa-chart-line"></i></span>
                Tren Kunjungan (7 Hari)
            </div>
            <div class="chart-wrap"><canvas id="chartKunjungan"></canvas></div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="ncpms-card mb-0 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="card-title-custom mb-0 pb-0 border-0">
                    <span class="card-title-icon"><i class="fas fa-list-check"></i></span>
                    Manifes Pasien Hari Ini
                </div>
                <span class="badge-pill badge-soft-gray">{{ date('d M Y') }}</span>
            </div>
            <div class="table-responsive">
                <table class="table data-table mb-0">
                    <thead>
                        <tr>
                            <th>No. Kunjungan</th>
                            <th>Pasien</th>
                            <th>Risiko</th>
                            <th>Status</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kunjungans as $k)
                        <tr>
                            <td>
                                <div class="rm-badge">{{ $k->nomor_kunjungan }}</div>
                                <div class="text-muted mt-1" style="font-size: 0.74rem;">{{ $k->tanggal_kunjungan->format('H:i') }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle">{{ substr($k->pasien->nama_tersamar, 0, 1) }}</div>
                                    <span class="fw-semibold">{{ $k->pasien->nama_tersamar }}</span>
                                </div>
                            </td>
                            <td>
                                @php $r = $k->skriningGizi->kategori_risiko ?? 'belum'; @endphp
                                <span class="badge-pill
                                    @if($r=='risiko_tinggi') badge-soft-danger
                                    @elseif($r=='risiko_sedang') badge-soft-warning
                                    @elseif($r=='risiko_rendah') badge-soft-success
                                    @else badge-soft-gray @endif">
                                    {{ str_replace('_',' ', $r) }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted fw-semibold" style="font-size: 0.82rem;">{{ str_replace('_',' ', strtoupper($k->status)) }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('kunjungan.show', $k) }}" class="btn btn-sm btn-light border" style="border-radius: 7px; width: 28px; height: 28px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-chevron-right" style="font-size: 0.7rem; color: var(--color-primary);"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-muted" style="font-size: 0.87rem;">
                                Tidak ada pasien terjadwal hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $kunjungans->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="ncpms-card mb-0 h-100">
            <div class="card-title-custom">
                <span class="card-title-icon"><i class="fas fa-chart-pie"></i></span>
                Proporsi Risiko Gizi
            </div>
            <div class="chart-wrap pt-2"><canvas id="chartRisiko"></canvas></div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="ncpms-card mb-0 h-100">
            <div class="card-title-custom">
                <span class="card-title-icon"><i class="fas fa-chart-bar"></i></span>
                Top 5 Penyakit Rujukan
            </div>
            <div class="chart-wrap pt-2"><canvas id="chartDiagnosis"></canvas></div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#849691';

    new Chart(document.getElementById('chartKunjungan'), {
        type: 'line',
        data: {
            labels: @json($grafikKunjungan->pluck('label')),
            datasets: [{ label: 'Kunjungan', data: @json($grafikKunjungan->pluck('total')),
                borderColor: '#128260', backgroundColor: 'rgba(18,130,96,0.08)',
                fill: true, tension: 0.4, borderWidth: 2.5,
                pointBackgroundColor: '#fff', pointBorderColor: '#128260',
                pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)', borderDash:[4,4] }, border: { display: false }, ticks: { precision: 0, padding: 8 } },
                x: { grid: { display: false }, border: { display: false }, ticks: { padding: 8 } }
            },
            interaction: { mode: 'index', intersect: false }
        }
    });

    new Chart(document.getElementById('chartRisiko'), {
        type: 'doughnut',
        data: {
            labels: @json($proporsiRisiko->pluck('kategori_risiko')->map(fn($k) => str_replace('_',' ', strtoupper($k)))),
            datasets: [{ data: @json($proporsiRisiko->pluck('total')),
                backgroundColor: ['#128260','#f59f00','#e03131','#868e96'],
                borderWidth: 0, hoverOffset: 8 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '68%',
            plugins: { legend: { position: 'right', labels: { padding: 16, usePointStyle: true, pointStyle: 'circle' } } }
        }
    });

    new Chart(document.getElementById('chartDiagnosis'), {
        type: 'bar',
        data: {
            labels: @json($topDiagnosis->pluck('nama_diagnosis')),
            datasets: [{ label: 'Kunjungan', data: @json($topDiagnosis->pluck('kunjungans_count')),
                backgroundColor: 'rgba(18,130,96,0.82)', borderRadius: 7, borderSkipped: false, barThickness: 26 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)', borderDash:[4,4] }, border: { display: false }, ticks: { precision: 0, padding: 8 } },
                x: { grid: { display: false }, border: { display: false }, ticks: { display: false } }
            }
        }
    });
</script>
@endpush
