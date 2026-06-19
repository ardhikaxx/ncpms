@extends('layouts.app')
@section('title','Detail Pasien')
@section('breadcrumb','Pasien / Detail')

@push('styles')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .identity-card {
            border-left: 4px solid var(--color-primary);
            background-color: #fcfdfd;
        }
        .icon-box {
            width: 32px; height: 32px;
            display: inline-flex; align-items: center; justify-content: center;
            background-color: var(--color-primary-subtle);
            color: var(--color-primary-dark);
            border-radius: 8px;
            margin-right: 12px;
        }
    </style>
@endpush

@section('content')
<div class="page-header" data-aos="fade-down">
    <div>
        <h1 class="page-title text-primary"><i class="fas fa-id-badge me-2"></i>{{ $pasien->nama_lengkap }}</h1>
        <p class="page-subtitle text-mono">
            <span class="badge bg-light text-dark border border-secondary-subtle me-2">{{ $pasien->nomor_rekam_medis }}</span>
            <span class="text-muted"><i class="fas fa-birthday-cake mx-1"></i> {{ $pasien->tanggal_lahir?->age }} tahun | <i class="fas fa-venus-mars mx-1"></i> {{ $pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pasien.edit', $pasien) }}" class="btn btn-outline-primary fw-bold"><i class="fas fa-pen me-1"></i> Edit Profil</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4" data-aos="fade-right" data-aos-delay="100">
        <div class="ncpms-card identity-card shadow-sm mb-4">
            <h2 class="card-title-custom border-bottom pb-2">
                <span class="card-title-icon bg-primary text-white"><i class="fas fa-address-card"></i></span> Identitas Pribadi
            </h2>
            <ul class="list-unstyled mt-3 mb-0">
                <li class="d-flex align-items-center mb-3">
                    <div class="icon-box"><i class="fas fa-id-card"></i></div>
                    <div><div class="small text-muted fw-bold">NIK</div><div class="text-dark">{{ $pasien->nik ?: '-' }}</div></div>
                </li>
                <li class="d-flex align-items-center mb-3">
                    <div class="icon-box"><i class="fas fa-phone"></i></div>
                    <div><div class="small text-muted fw-bold">No. Telepon</div><div class="text-dark">{{ $pasien->nomor_telepon ?: '-' }}</div></div>
                </li>
                <li class="d-flex align-items-center mb-3">
                    <div class="icon-box"><i class="fas fa-map-marker-alt"></i></div>
                    <div><div class="small text-muted fw-bold">Alamat</div><div class="text-dark">{{ $pasien->alamat ?: '-' }}</div></div>
                </li>
                <li class="d-flex align-items-center">
                    <div class="icon-box"><i class="fas fa-hospital-user"></i></div>
                    <div><div class="small text-muted fw-bold">BPJS</div><div class="text-dark">{{ $pasien->nomor_bpjs ?: '-' }}</div></div>
                </li>
            </ul>
        </div>
        
        <div class="ncpms-card shadow-sm">
            <h2 class="card-title-custom border-bottom pb-2">
                <span class="card-title-icon bg-warning text-dark"><i class="fas fa-allergies"></i></span> Profil Alergi
            </h2>
            <div class="mt-3">
                @forelse($pasien->riwayatAlergi as $alergi)
                    <div class="alert bg-warning-subtle text-dark border border-warning-subtle mb-2 d-flex align-items-start">
                        <i class="fas fa-exclamation-triangle text-warning fs-4 me-3 mt-1"></i>
                        <div>
                            <strong class="d-block">{{ $alergi->nama_alergen }}</strong>
                            <span class="small">{{ $alergi->reaksi }} ({{ $alergi->tingkat_keparahan }})</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted p-3 bg-light rounded border">
                        <i class="fas fa-shield-alt text-success fs-3 mb-2 opacity-50"></i><br>
                        Tidak ada riwayat alergi tercatat.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-lg-8" data-aos="fade-left" data-aos-delay="200">
        <div class="ncpms-card shadow-sm mb-4" style="border-top: 4px solid var(--color-primary);">
            <h2 class="card-title-custom mb-3">
                <span class="card-title-icon bg-primary text-white"><i class="fas fa-calendar-plus"></i></span> Buat Kunjungan Baru
            </h2>
            <form method="POST" action="{{ route('pasien.kunjungan.store', $pasien) }}" class="row g-3 bg-light p-3 rounded border">
                @csrf
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted small">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_kunjungan" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted small">Tipe <span class="text-danger">*</span></label>
                    <select name="tipe_kunjungan" class="form-select form-select-sm">
                        <option value="mandiri">Mandiri</option>
                        <option value="rujukan_internal">Rujukan Internal</option>
                        <option value="rujukan_eksternal">Rujukan Eksternal</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted small">Dietisien / SpGK</label>
                    <select name="dietisien_id" class="form-select form-select-sm">
                        <option value="">-- Belum ditentukan --</option>
                        @foreach($dietisiens as $d)
                        <option value="{{ $d->id }}">{{ $d->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted small">Diagnosis Medis</label>
                    <select name="diagnosis_medis_utama_id" class="form-select form-select-sm">
                        <option value="">-- Kosong --</option>
                        @foreach($diagnosisMedis as $dm)
                        <option value="{{ $dm->id }}">{{ $dm->kode_icd10 }} - {{ $dm->nama_diagnosis }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-9">
                    <label class="form-label fw-bold text-muted small">Asal Rujukan / Catatan Awal</label>
                    <input name="asal_rujukan" class="form-control form-control-sm" placeholder="Contoh: Rujukan Poli Penyakit Dalam dr. X">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary btn-sm w-100 fw-bold py-2"><i class="fas fa-plus me-1"></i> Daftarkan</button>
                </div>
            </form>
        </div>
        
        <div class="ncpms-card shadow-sm mb-4">
            <h2 class="card-title-custom mb-3">
                <span class="card-title-icon bg-primary text-white"><i class="fas fa-chart-line"></i></span> Tren Antropometri
            </h2>
            <div class="chart-wrap" style="height: 250px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
        
        <div class="ncpms-card shadow-sm">
            <h2 class="card-title-custom mb-3">
                <span class="card-title-icon bg-primary text-white"><i class="fas fa-timeline"></i></span> Riwayat Kunjungan
            </h2>
            <div class="table-responsive border rounded">
                <table class="table align-middle table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>Tanggal</th>
                            <th>Diagnosis Medis</th>
                            <th>Risiko</th>
                            <th>Status</th>
                            <th class="pe-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($pasien->kunjungans as $k)
                        <tr>
                            <td class="ps-3 text-mono fw-bold text-primary">{{ $k->nomor_kunjungan }}</td>
                            <td>{{ $k->tanggal_kunjungan?->format('d/m/Y') }}</td>
                            <td><span class="text-dark">{{ $k->diagnosisMedisUtama->nama_diagnosis ?? '-' }}</span></td>
                            <td>
                                <span class="badge rounded-pill border @if($k->skriningGizi?->kategori_risiko == 'risiko_tinggi') bg-danger-subtle text-danger border-danger-subtle @elseif($k->skriningGizi?->kategori_risiko == 'risiko_sedang') bg-warning-subtle text-warning border-warning-subtle @else bg-success-subtle text-success border-success-subtle @endif">
                                    {{ str_replace('_',' ', $k->skriningGizi->kategori_risiko ?? 'belum') }}
                                </span>
                            </td>
                            <td><span class="text-muted small text-uppercase fw-bold">{{ str_replace('_',' ', $k->status) }}</span></td>
                            <td class="pe-3 text-end">
                                <a href="{{ route('kunjungan.show', $k) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-external-link-alt"></i> Buka</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada riwayat kunjungan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });
    
@if($pasien->riwayatAlergi->count())
    NCPMS_SWAL.peringatanKlinis('Peringatan Alergi Pasien', 'Pasien memiliki alergi tercatat. Periksa profil alergi sebelum menyusun menu atau preskripsi diet.');
@endif

    // Siapkan data untuk grafik tren antropometri (BB dan IMT)
    @php
        $kunjungansChart = $pasien->kunjungans->reverse()->filter(function($k) {
            return $k->antropometri !== null;
        })->values();
        $labels = $kunjungansChart->map(fn($k) => $k->tanggal_kunjungan->format('d/m/Y'))->toJson();
        $bbData = $kunjungansChart->map(fn($k) => $k->antropometri->berat_badan_kg)->toJson();
        $imtData = $kunjungansChart->map(fn($k) => $k->antropometri->indeks_massa_tubuh)->toJson();
    @endphp

    const ctxTrend = document.getElementById('trendChart');
    if(ctxTrend) {
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: {!! $labels !!},
                datasets: [
                    {
                        label: 'Berat Badan (kg)',
                        data: {!! $bbData !!},
                        borderColor: '#1A7A64',
                        backgroundColor: 'rgba(26, 122, 100, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        yAxisID: 'y'
                    },
                    {
                        label: 'IMT',
                        data: {!! $imtData !!},
                        borderColor: '#ff9800',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        tension: 0.3,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    x: { grid: { display: false } },
                    y: { 
                        type: 'linear', display: true, position: 'left',
                        title: { display: true, text: 'Berat Badan (kg)' }
                    },
                    y1: { 
                        type: 'linear', display: true, position: 'right',
                        title: { display: true, text: 'Indeks Massa Tubuh' },
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection
