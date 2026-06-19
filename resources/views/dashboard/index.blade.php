@extends('layouts.app')
@section('title', 'Dashboard Klinis')
@section('breadcrumb', 'Dashboard')

@section('content')

{{-- Welcome Banner --}}
<div class="page-banner">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="mb-1">Selamat datang, {{ explode(' ', Auth::user()->nama_lengkap)[0] }}! 👋</h1>
            <p>Ringkasan analitik gizi klinis hari ini &mdash; {{ date('l, d F Y') }}</p>
        </div>
        @if(in_array(Auth::user()->peran, ['perawat','spgk']))
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0 banner-cta">
            <a href="{{ route('pasien.create') }}"
               class="btn btn-light fw-bold px-4 py-2"
               style="border-radius: 50px; color: var(--color-primary-dark); box-shadow: 0 4px 12px rgba(0,0,0,0.12);">
                <i class="fas fa-plus me-2"></i>Registrasi Pasien
            </a>
        </div>
        @endif
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-accent" style="background: var(--color-primary);"></div>
            <div class="d-flex justify-content-between align-items-start ps-1">
                <div>
                    <div class="stat-label">Total Pasien</div>
                    <div class="stat-value">{{ $totalPasien }}</div>
                </div>
                <div class="stat-icon" style="background: var(--color-primary-subtle); color: var(--color-primary);">
                    <i class="fas fa-hospital-user"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-accent" style="background: #f59f00;"></div>
            <div class="d-flex justify-content-between align-items-start ps-1">
                <div>
                    <div class="stat-label">Kunjungan Hari Ini</div>
                    <div class="stat-value">{{ $totalKunjunganHariIni }}</div>
                </div>
                <div class="stat-icon" style="background: #fef3c7; color: #f59f00;">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-accent" style="background: var(--color-risiko-tinggi);"></div>
            <div class="d-flex justify-content-between align-items-start ps-1">
                <div>
                    <div class="stat-label">Risiko Tinggi</div>
                    <div class="stat-value" style="color: var(--color-risiko-tinggi);">{{ $risikoTinggi }}</div>
                </div>
                <div class="stat-icon" style="background: #fee2e2; color: var(--color-risiko-tinggi);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-accent" style="background: #868e96;"></div>
            <div class="d-flex justify-content-between align-items-start ps-1">
                <div>
                    <div class="stat-label">Menunggu Asesmen</div>
                    <div class="stat-value">{{ $menungguAsesmen }}</div>
                </div>
                <div class="stat-icon" style="background: #f3f4f6; color: #868e96;">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts + Table Row --}}
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
                    <span class="card-title-icon" style="background: var(--color-primary); color: #fff;"><i class="fas fa-list-check"></i></span>
                    Manifes Pasien Hari Ini
                </div>
                <span class="badge-pill badge-soft-gray">
                    <i class="far fa-calendar-alt me-1"></i>{{ date('d M Y') }}
                </span>
            </div>
            <div class="table-responsive" style="border-radius: 10px; border: 1px solid var(--color-border);">
                <table class="table data-table mb-0">
                    <thead>
                        <tr>
                            <th style="padding-left: 16px;">Kunjungan</th>
                            <th>Pasien</th>
                            <th>Risiko</th>
                            <th>Status</th>
                            <th class="text-end" style="padding-right: 16px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kunjungans as $k)
                        <tr>
                            <td style="padding-left: 16px;">
                                <div class="rm-badge">{{ $k->nomor_kunjungan }}</div>
                                <div class="text-muted mt-1" style="font-size: 0.74rem;"><i class="far fa-clock me-1"></i>{{ $k->tanggal_kunjungan->format('H:i') }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle" style="font-size: 0.85rem;">{{ substr($k->pasien->nama_tersamar, 0, 1) }}</div>
                                    <span class="fw-bold" style="font-size: 0.88rem;">{{ $k->pasien->nama_tersamar }}</span>
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
                                <div class="fw-bold" style="font-size: 0.82rem; color: var(--color-text-secondary);">{{ str_replace('_',' ', strtoupper($k->status)) }}</div>
                            </td>
                            <td class="text-end" style="padding-right: 16px;">
                                <a href="{{ route('kunjungan.show', $k) }}" class="btn btn-sm btn-light border" style="border-radius: 7px; width: 28px; height: 28px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-chevron-right" style="font-size: 0.7rem; color: var(--color-primary);"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-muted" style="font-size: 0.87rem;">
                                <i class="fas fa-bed d-block mb-1 opacity-25 fa-lg"></i>Tidak ada pasien terjadwal hari ini.
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
                <span class="card-title-icon" style="background: var(--color-primary); color: #fff;"><i class="fas fa-chart-pie"></i></span>
                Proporsi Risiko
            </div>
            <div class="chart-wrap pt-2"><canvas id="chartRisiko"></canvas></div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="ncpms-card mb-0 h-100">
            <div class="card-title-custom">
                <span class="card-title-icon" style="background: var(--color-primary); color: #fff;"><i class="fas fa-chart-bar"></i></span>
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
                borderColor: '#128260', backgroundColor: 'rgba(18,130,96,0.10)',
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
