@extends('layouts.app')
@section('title','Dashboard Klinis')
@section('breadcrumb','Dashboard')
@section('content')
@extends('layouts.app')
@section('title','Dashboard Klinis')
@section('breadcrumb','Dashboard')
@section('content')

<!-- Welcome Banner -->
<div class="welcome-banner mb-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--color-primary-darker) 0%, var(--color-primary) 100%); border-radius: 20px; padding: 2.5rem 3rem; color: white; box-shadow: 0 10px 30px rgba(26,122,100,0.2);">
    <!-- Abstract Shapes -->
    <div style="position: absolute; right: -5%; top: -20%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%); border-radius: 50%;"></div>
    <div style="position: absolute; right: 15%; bottom: -50%; width: 200px; height: 200px; background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0) 70%); border-radius: 50%;"></div>
    
    <div class="row align-items-center position-relative z-index-1">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-2" style="font-size: 2.5rem; letter-spacing: -0.02em;">
                Selamat datang kembali, {{ explode(' ', Auth::user()->nama_lengkap)[0] }}! <span style="font-size: 2rem;">👋</span>
            </h1>
            <p class="fs-5 opacity-75 mb-0">Berikut adalah ringkasan analitik gizi klinis secara langsung hari ini.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
            @if(in_array(Auth::user()->peran, ['perawat','spgk']))
                <a href="{{ route('pasien.create') }}" class="btn btn-light fw-bold px-4 py-3" style="border-radius: 50px; color: var(--color-primary-dark); box-shadow: 0 8px 15px rgba(0,0,0,0.1); transition: transform 0.2s;">
                    <i class="fas fa-plus-circle me-2 fs-5"></i> Registrasi Pasien Baru
                </a>
            @endif
        </div>
    </div>
</div>

<style>
    .stat-card-premium {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }
    .stat-card-premium:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(26,122,100,0.1);
        border-color: rgba(26,122,100,0.2);
    }
    .stat-card-premium::before {
        content: ''; position: absolute; left: 0; top: 0; height: 100%; width: 4px;
    }
    .scp-primary::before { background: var(--color-primary); }
    .scp-warning::before { background: #ff9800; }
    .scp-danger::before { background: #dc3545; }
    .scp-secondary::before { background: #6c757d; }
    
    .table-hover-premium tbody tr { transition: all 0.2s; }
    .table-hover-premium tbody tr:hover { background-color: var(--color-primary-subtle) !important; transform: scale(1.005); }
</style>

<!-- Stat Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="stat-card-premium scp-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Total Pasien</div>
                    <div class="fs-2 fw-bold text-dark">{{ $totalPasien }}</div>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px; background: rgba(26,122,100,0.1); color: var(--color-primary); font-size: 1.5rem;">
                    <i class="fas fa-hospital-user"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-premium scp-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Kunjungan Hari Ini</div>
                    <div class="fs-2 fw-bold text-dark">{{ $totalKunjunganHariIni }}</div>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px; background: rgba(255,152,0,0.1); color: #ff9800; font-size: 1.5rem;">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-premium scp-danger" style="background: linear-gradient(135deg, #fff 0%, #fff5f5 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-danger fw-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Risiko Tinggi</div>
                    <div class="fs-2 fw-bold text-danger">{{ $risikoTinggi }}</div>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 54px; height: 54px; background: #dc3545; color: #fff; font-size: 1.5rem; animation: pulse 2s infinite;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-premium scp-secondary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Menunggu Asesmen</div>
                    <div class="fs-2 fw-bold text-dark">{{ $menungguAsesmen }}</div>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px; background: rgba(108,117,125,0.1); color: #6c757d; font-size: 1.5rem;">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Dashboard Charts & Table -->
<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="ncpms-card h-100 mb-0 shadow-sm" style="border-radius: 20px;">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <h2 class="card-title-custom border-0 mb-0 pb-0">
                    <span class="card-title-icon bg-light text-primary"><i class="fas fa-chart-line"></i></span> 
                    Tren Kunjungan (7 Hari)
                </h2>
            </div>
            <div class="chart-wrap"><canvas id="chartKunjungan"></canvas></div>
        </div>
    </div>
    
    <div class="col-lg-7">
        <div class="ncpms-card h-100 mb-0 shadow-sm" style="border-radius: 20px;">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <h2 class="card-title-custom border-0 mb-0 pb-0">
                    <span class="card-title-icon" style="background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-dark) 100%);"><i class="fas fa-list-check"></i></span> 
                    Manifes Pasien Hari Ini
                </h2>
                <div class="badge bg-light text-dark border px-3 py-2 fs-6 rounded-pill"><i class="far fa-calendar-alt me-1"></i> {{ date('d M Y') }}</div>
            </div>
            <div class="table-responsive" style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(0,0,0,0.05);">
                <table class="table align-middle table-hover-premium mb-0">
                    <thead style="background: rgba(0,0,0,0.02);">
                        <tr><th class="ps-4">Kunjungan</th><th>Pasien</th><th>Risiko</th><th>Status</th><th class="text-end pe-4">Aksi</th></tr>
                    </thead>
                    <tbody>
                    @forelse($kunjungans as $k)
                        <tr>
                            <td class="ps-4">
                                <div class="text-mono fw-bold text-dark">{{ $k->nomor_kunjungan }}</div>
                                <div class="text-muted small"><i class="far fa-clock"></i> {{ $k->tanggal_kunjungan->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, var(--color-primary-light), var(--color-primary)); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1rem; box-shadow: 0 4px 8px rgba(26,122,100,0.2);">
                                        {{ substr($k->pasien->nama_tersamar, 0, 1) }}
                                    </div>
                                    <div class="fw-bold text-dark">{{ $k->pasien->nama_tersamar }}</div>
                                </div>
                            </td>
                            <td>
                                @php $r = $k->skriningGizi->kategori_risiko ?? 'belum'; @endphp
                                <span class="badge px-3 py-2 rounded-pill shadow-sm @if($r == 'risiko_tinggi') bg-danger @elseif($r == 'risiko_sedang') bg-warning text-dark @elseif($r == 'risiko_rendah') bg-success @else bg-secondary @endif">
                                    {{ strtoupper(str_replace('_',' ', $r)) }}
                                </span>
                            </td>
                            <td>
                                <div class="text-dark fw-bold" style="font-size: 0.85rem;">{{ str_replace('_',' ', strtoupper($k->status)) }}</div>
                                <div class="text-muted small" style="font-size: 0.7rem;">{{ $k->dietisien->nama_lengkap ?? '-' }}</div>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('kunjungan.show', $k) }}" class="btn btn-sm btn-light border fw-bold text-primary" style="border-radius: 8px; transition: all 0.2s;">
                                    Buka <i class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-5"><i class="fas fa-bed-empty fa-3x mb-3 opacity-25"></i><br>Tidak ada pasien terjadwal hari ini.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $kunjungans->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-lg-5">
        <div class="ncpms-card h-100 shadow-sm" style="border-radius: 20px;">
            <div class="d-flex align-items-center mb-4">
                <div class="card-title-icon me-3 text-white" style="background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);"><i class="fas fa-chart-pie"></i></div>
                <h2 class="card-title-custom border-0 mb-0 pb-0">Proporsi Risiko</h2>
            </div>
            <div class="chart-wrap pt-2"><canvas id="chartRisiko"></canvas></div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="ncpms-card h-100 shadow-sm" style="border-radius: 20px;">
            <div class="d-flex align-items-center mb-4">
                <div class="card-title-icon me-3 text-white" style="background: linear-gradient(135deg, #6c757d 0%, #343a40 100%);"><i class="fas fa-chart-bar"></i></div>
                <h2 class="card-title-custom border-0 mb-0 pb-0">Top 5 Penyakit Rujukan</h2>
            </div>
            <div class="chart-wrap pt-2"><canvas id="chartDiagnosis"></canvas></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Styling Charts for Premium Look
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.color = '#6c757d';

new Chart(document.getElementById('chartKunjungan'), {
    type: 'line',
    data: {
        labels: @json($grafikKunjungan->pluck('label')),
        datasets: [{ 
            label: 'Kunjungan', 
            data: @json($grafikKunjungan->pluck('total')), 
            borderColor: '#1A7A64', 
            backgroundColor: (context) => {
                const ctx = context.chart.ctx;
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(26,122,100,0.4)');
                gradient.addColorStop(1, 'rgba(26,122,100,0.0)');
                return gradient;
            },
            fill: true, 
            tension: 0.4,
            borderWidth: 3,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#1A7A64',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: { 
        responsive:true, 
        maintainAspectRatio:false, 
        plugins:{ legend:{ display:false } }, 
        scales:{ 
            y:{ beginAtZero:true, grid: { borderDash: [5, 5], color: 'rgba(0,0,0,0.05)' }, border: { display: false }, ticks:{ precision:0, padding: 10 } },
            x:{ grid: { display: false }, border: { display: false }, ticks: { padding: 10 } }
        },
        interaction: { mode: 'index', intersect: false }
    }
});

new Chart(document.getElementById('chartRisiko'), {
    type: 'doughnut',
    data: {
        labels: @json($proporsiRisiko->pluck('kategori_risiko')->map(fn($k) => str_replace('_', ' ', strtoupper($k)))),
        datasets: [{
            data: @json($proporsiRisiko->pluck('total')),
            backgroundColor: ['#1A7A64', '#ff9800', '#dc3545', '#6c757d'],
            borderWidth: 0,
            hoverOffset: 10
        }]
    },
    options: { 
        responsive:true, 
        maintainAspectRatio:false, 
        cutout: '70%',
        plugins:{ 
            legend:{ position: 'right', labels: { padding: 20, usePointStyle: true, pointStyle: 'circle' } } 
        } 
    }
});

new Chart(document.getElementById('chartDiagnosis'), {
    type: 'bar',
    data: {
        labels: @json($topDiagnosis->pluck('nama_diagnosis')),
        datasets: [{
            label: 'Jumlah Kunjungan',
            data: @json($topDiagnosis->pluck('kunjungans_count')),
            backgroundColor: (context) => {
                const ctx = context.chart.ctx;
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, '#1A7A64');
                gradient.addColorStop(1, '#115545');
                return gradient;
            },
            borderRadius: 8,
            borderSkipped: false,
            barThickness: 30
        }]
    },
    options: { 
        responsive:true, 
        maintainAspectRatio:false, 
        plugins:{ legend:{ display:false } }, 
        scales:{ 
            y:{ beginAtZero:true, grid: { borderDash: [5, 5], color: 'rgba(0,0,0,0.05)' }, border: { display: false }, ticks:{ precision:0, padding: 10 } },
            x:{ grid: { display: false }, border: { display: false }, ticks: { display: false } }
        } 
    }
});
</script>
@endpush
