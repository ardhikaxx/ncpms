@extends('layouts.app')
@section('title','Dashboard Klinis')
@section('breadcrumb','Dashboard')
@section('content')
@extends('layouts.app')
@section('title','Dashboard Klinis')
@section('breadcrumb','Dashboard')
@section('content')
<div class="page-header mb-5">
    <div>
        <h1 class="page-title">Dashboard Klinis Terpadu</h1>
        <p class="page-subtitle">Ringkasan analitik pelayanan gizi klinis secara langsung (real-time).</p>
    </div>
    @if(in_array(Auth::user()->peran, ['perawat','spgk']))
        <a href="{{ route('pasien.create') }}" class="btn-primary-ncpms px-4 py-2" style="border-radius: 20px;">
            <i class="fas fa-plus"></i> Pasien Baru
        </a>
    @endif
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="stat-card" style="border-top-color: var(--color-primary-light);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Pasien</div>
                    <div class="stat-value">{{ $totalPasien }}</div>
                </div>
                <div class="card-title-icon bg-light text-primary border"><i class="fas fa-hospital-user"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-top-color: var(--color-accent);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Kunjungan Hari Ini</div>
                    <div class="stat-value">{{ $totalKunjunganHariIni }}</div>
                </div>
                <div class="card-title-icon bg-light text-warning border"><i class="fas fa-calendar-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-top-color: var(--color-risiko-tinggi); background: linear-gradient(180deg, #fff 0%, #fff5f5 100%);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label text-danger">Risiko Tinggi</div>
                    <div class="stat-value text-danger">{{ $risikoTinggi }}</div>
                </div>
                <div class="card-title-icon bg-white text-danger shadow-sm"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-top-color: var(--color-text-secondary);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Menunggu Asesmen</div>
                    <div class="stat-value">{{ $menungguAsesmen }}</div>
                </div>
                <div class="card-title-icon bg-light text-secondary border"><i class="fas fa-hourglass-half"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Main Dashboard Charts & Table -->
<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="ncpms-card h-100 mb-0">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <h2 class="card-title-custom border-0 mb-0 pb-0">
                    <span class="card-title-icon"><i class="fas fa-chart-line"></i></span> 
                    Tren Kunjungan (7 Hari)
                </h2>
            </div>
            <div class="chart-wrap"><canvas id="chartKunjungan"></canvas></div>
        </div>
    </div>
    
    <div class="col-lg-7">
        <div class="ncpms-card h-100 mb-0">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <h2 class="card-title-custom border-0 mb-0 pb-0">
                    <span class="card-title-icon" style="background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-dark) 100%);"><i class="fas fa-list-check"></i></span> 
                    Manifes Pasien Hari Ini
                </h2>
                <div class="text-muted small">{{ date('d M Y') }}</div>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Kunjungan</th><th>Pasien</th><th>Risiko</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                    <tbody>
                    @forelse($kunjungans as $k)
                        <tr>
                            <td>
                                <div class="text-mono fw-bold text-dark">{{ $k->nomor_kunjungan }}</div>
                                <div class="text-muted small">{{ $k->tanggal_kunjungan->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-primary-subtle); color: var(--color-primary-dark); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.8rem;">
                                        {{ substr($k->pasien->nama_tersamar, 0, 1) }}
                                    </div>
                                    <div class="fw-bold">{{ $k->pasien->nama_tersamar }}</div>
                                </div>
                            </td>
                            <td>
                                @php $r = $k->skriningGizi->kategori_risiko ?? 'belum'; @endphp
                                <span class="badge px-3 py-2 rounded-pill @if($r == 'risiko_tinggi') bg-danger @elseif($r == 'risiko_sedang') bg-warning text-dark @elseif($r == 'risiko_rendah') bg-success @else bg-secondary @endif">
                                    {{ strtoupper(str_replace('_',' ', $r)) }}
                                </span>
                            </td>
                            <td>
                                <div class="text-dark fw-bold" style="font-size: 0.85rem;">{{ str_replace('_',' ', strtoupper($k->status)) }}</div>
                                <div class="text-muted small" style="font-size: 0.7rem;">{{ $k->dietisien->nama_lengkap ?? '-' }}</div>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('kunjungan.show', $k) }}" class="btn-outline-ncpms btn-sm-ncpms" style="border-radius: 8px;">Buka <i class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-5"><i class="fas fa-bed-empty fa-3x mb-3 opacity-25"></i><br>Tidak ada pasien terjadwal hari ini.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $kunjungans->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-lg-5">
        <div class="ncpms-card h-100">
            <h2 class="card-title-custom"><span class="card-title-icon" style="background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);"><i class="fas fa-chart-pie"></i></span> Proporsi Risiko Malnutrisi</h2>
            <div class="chart-wrap pt-3"><canvas id="chartRisiko"></canvas></div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="ncpms-card h-100">
            <h2 class="card-title-custom"><span class="card-title-icon" style="background: linear-gradient(135deg, #6c757d 0%, #343a40 100%);"><i class="fas fa-chart-bar"></i></span> Top 5 Penyakit Rujukan Terbanyak</h2>
            <div class="chart-wrap pt-3"><canvas id="chartDiagnosis"></canvas></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('chartKunjungan'), {
    type: 'line',
    data: {
        labels: @json($grafikKunjungan->pluck('label')),
        datasets: [{ label: 'Kunjungan', data: @json($grafikKunjungan->pluck('total')), borderColor: '#1A7A64', backgroundColor: 'rgba(26,122,100,.12)', fill: true, tension: .35 }]
    },
    options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true, ticks:{ precision:0 } } } }
});

new Chart(document.getElementById('chartRisiko'), {
    type: 'pie',
    data: {
        labels: @json($proporsiRisiko->pluck('kategori_risiko')->map(fn($k) => str_replace('_', ' ', strtoupper($k)))),
        datasets: [{
            data: @json($proporsiRisiko->pluck('total')),
            backgroundColor: ['#1A7A64', '#ff9800', '#dc3545', '#6c757d']
        }]
    },
    options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position: 'right' } } }
});

new Chart(document.getElementById('chartDiagnosis'), {
    type: 'bar',
    data: {
        labels: @json($topDiagnosis->pluck('nama_diagnosis')),
        datasets: [{
            label: 'Jumlah Kunjungan',
            data: @json($topDiagnosis->pluck('kunjungans_count')),
            backgroundColor: '#1A7A64',
            borderRadius: 6
        }]
    },
    options: { 
        responsive:true, maintainAspectRatio:false, 
        plugins:{ legend:{ display:false } }, 
        scales:{ 
            y:{ beginAtZero:true, ticks:{ precision:0 } },
            x: { ticks: { display: false } }
        } 
    }
});
</script>
@endpush
