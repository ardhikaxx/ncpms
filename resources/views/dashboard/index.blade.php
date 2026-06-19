@extends('layouts.app')
@section('title','Dashboard Klinis')
@section('breadcrumb','Dashboard')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard Klinis</h1>
        <p class="page-subtitle">Ringkasan pelayanan gizi klinis hari ini.</p>
    </div>
    @if(in_array(Auth::user()->peran, ['perawat','spgk']))
        <a href="{{ route('pasien.create') }}" class="btn-primary-ncpms"><i class="fas fa-plus"></i> Pasien Baru</a>
    @endif
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">Total Pasien</div><div class="stat-value">{{ $totalPasien }}</div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">Kunjungan Hari Ini</div><div class="stat-value">{{ $totalKunjunganHariIni }}</div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">Risiko Tinggi</div><div class="stat-value" style="color:var(--color-risiko-tinggi)">{{ $risikoTinggi }}</div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">Menunggu Asesmen</div><div class="stat-value">{{ $menungguAsesmen }}</div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-chart-line"></i></span> Tren Kunjungan 7 Hari</h2>
            <div class="chart-wrap"><canvas id="chartKunjungan"></canvas></div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-list-check"></i></span> Manifes Pasien Hari Ini</h2>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>No. Kunjungan</th><th>Pasien</th><th>Risiko</th><th>Penanganan</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                    @forelse($kunjungans as $k)
                        <tr>
                            <td class="text-mono">{{ $k->nomor_kunjungan }}</td>
                            <td>{{ $k->pasien->nama_tersamar }}</td>
                            <td><span class="badge-risk risk-{{ $k->skriningGizi->kategori_risiko ?? 'risiko_rendah' }}">{{ str_replace('_',' ', strtoupper($k->skriningGizi->kategori_risiko ?? 'belum')) }}</span></td>
                            <td>{{ $k->dietisien->nama_lengkap ?? '-' }}</td>
                            <td>{{ str_replace('_',' ', strtoupper($k->status)) }}</td>
                            <td><a href="{{ route('kunjungan.show', $k) }}" class="btn-outline-ncpms btn-sm-ncpms"><i class="fas fa-eye"></i></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada kunjungan hari ini.</td></tr>
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

<div class="row g-3 mt-1">
    <div class="col-lg-5">
        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-chart-pie"></i></span> Proporsi Risiko Malnutrisi</h2>
            <div class="chart-wrap"><canvas id="chartRisiko"></canvas></div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-chart-bar"></i></span> Top 5 Penyakit Utama</h2>
            <div class="chart-wrap"><canvas id="chartDiagnosis"></canvas></div>
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
