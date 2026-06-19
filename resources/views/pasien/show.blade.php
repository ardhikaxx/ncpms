@extends('layouts.app')
@section('title','Detail Pasien')
@section('breadcrumb','Pasien / Detail')

@push('styles')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .info-row {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 10px 0; border-bottom: 1px solid var(--color-divider);
        }
        .info-row:last-child { border-bottom: none; }
        .info-icon {
            width: 30px; height: 30px; flex-shrink: 0;
            background: var(--color-primary-subtle);
            color: var(--color-primary);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem;
        }
        .info-label { font-size: 0.72rem; font-weight: 700; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.04em; }
        .info-value { font-size: 0.9rem; color: var(--color-text-primary); font-weight: 600; margin-top: 1px; }
        .patient-avatar {
            width: 64px; height: 64px; border-radius: 16px;
            background: var(--color-primary);
            color: white; font-weight: 800; font-size: 1.8rem;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 12px rgba(18,130,96,0.3);
        }
        .kunjungan-table thead th {
            background: #f8faf9; font-size: 0.75rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--color-text-muted); border-bottom: 1px solid var(--color-border);
            padding: 10px 12px;
        }
        .kunjungan-table td { padding: 10px 12px; vertical-align: middle; border-bottom: 1px solid var(--color-divider); font-size: 0.88rem; }
        .kunjungan-table tbody tr:hover { background: var(--color-primary-subtle); }
    </style>
@endpush

@section('content')
<div class="page-header" data-aos="fade-down">
    <div>
        <h1 class="page-title" style="font-size: 1.5rem;">
            <i class="fas fa-id-badge me-2" style="color: var(--color-primary);"></i>{{ $pasien->nama_lengkap }}
        </h1>
        <p class="page-subtitle">
            <span class="badge bg-light border text-dark me-2" style="font-family: var(--font-mono);">{{ $pasien->nomor_rekam_medis }}</span>
            <i class="fas fa-birthday-cake text-muted me-1"></i>{{ $pasien->tanggal_lahir?->age }} thn &bull;
            <i class="fas fa-venus-mars text-muted mx-1"></i>{{ $pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
        </p>
    </div>
    <a href="{{ route('pasien.edit', $pasien) }}"
        class="btn fw-bold px-3 py-2"
        style="background: transparent; border: 1.5px solid var(--color-primary); color: var(--color-primary); border-radius: 10px; font-size: 0.9rem;">
        <i class="fas fa-pen me-1"></i> Edit Profil
    </a>
</div>

<div class="row g-3">
    {{-- Left: Identity + Allergy --}}
    <div class="col-lg-4" data-aos="fade-right" data-aos-delay="80">
        {{-- Patient Card --}}
        <div class="ncpms-card mb-3" style="border-top: 3px solid var(--color-primary);">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="patient-avatar">{{ substr($pasien->nama_tersamar, 0, 1) }}</div>
                <div>
                    <div class="fw-bold text-dark" style="font-size: 1rem;">{{ $pasien->nama_tersamar }}</div>
                    <div class="text-muted" style="font-size: 0.82rem;">{{ $pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ $pasien->tanggal_lahir?->age }} tahun</div>
                    <span class="badge {{ $pasien->status_aktif ? 'bg-success-subtle text-success border-success-subtle' : 'bg-secondary-subtle text-secondary' }} border rounded-pill mt-1" style="font-size: 0.72rem;">
                        {{ $pasien->status_aktif ? 'AKTIF' : 'NONAKTIF' }}
                    </span>
                </div>
            </div>

            <h6 class="fw-bold text-muted mb-2" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em;">Identitas Pribadi</h6>
            <div class="info-row">
                <div class="info-icon"><i class="fas fa-id-card"></i></div>
                <div><div class="info-label">NIK</div><div class="info-value">{{ $pasien->nik ?: '-' }}</div></div>
            </div>
            <div class="info-row">
                <div class="info-icon"><i class="fas fa-phone"></i></div>
                <div><div class="info-label">No. Telepon</div><div class="info-value">{{ $pasien->nomor_telepon ?: '-' }}</div></div>
            </div>
            <div class="info-row">
                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div><div class="info-label">Alamat</div><div class="info-value">{{ $pasien->alamat ?: '-' }}</div></div>
            </div>
            <div class="info-row">
                <div class="info-icon"><i class="fas fa-hospital-user"></i></div>
                <div><div class="info-label">No. BPJS</div><div class="info-value">{{ $pasien->nomor_bpjs ?: '-' }}</div></div>
            </div>
        </div>

        {{-- Allergy --}}
        <div class="ncpms-card">
            <div class="card-title-custom">
                <span class="card-title-icon" style="background: #fff3cd; color: #b45309;"><i class="fas fa-allergies"></i></span>
                Profil Alergi
            </div>
            @forelse($pasien->riwayatAlergi as $alergi)
                <div class="d-flex align-items-start gap-2 p-2 mb-2 rounded" style="background: #fffbeb; border: 1px solid #fde68a;">
                    <i class="fas fa-exclamation-triangle text-warning mt-1"></i>
                    <div>
                        <div class="fw-bold" style="font-size: 0.88rem;">{{ $alergi->nama_alergen }}</div>
                        <div class="text-muted" style="font-size: 0.78rem;">{{ $alergi->reaksi }} &bull; <strong>{{ $alergi->tingkat_keparahan }}</strong></div>
                    </div>
                </div>
            @empty
                <div class="text-center p-3 rounded" style="background: #f0fdf4; border: 1px dashed #86efac;">
                    <i class="fas fa-shield-alt text-success mb-1 d-block fa-lg opacity-60"></i>
                    <span class="text-muted" style="font-size: 0.85rem;">Tidak ada riwayat alergi.</span>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Right: New Visit + Trend + History --}}
    <div class="col-lg-8" data-aos="fade-left" data-aos-delay="100">
        {{-- New Visit Form --}}
        <div class="ncpms-card mb-3" style="border-top: 3px solid var(--color-primary);">
            <div class="card-title-custom">
                <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-calendar-plus"></i></span>
                Buat Kunjungan Baru
            </div>
            <form method="POST" action="{{ route('pasien.kunjungan.store', $pasien) }}" class="row g-2 p-3 rounded" style="background: #f8faf9; border: 1px solid var(--color-border);">
                @csrf
                <div class="col-md-3">
                    <label class="form-label-ncpms">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_kunjungan" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label-ncpms">Tipe <span class="text-danger">*</span></label>
                    <select name="tipe_kunjungan" class="form-select form-select-sm">
                        <option value="mandiri">Mandiri</option>
                        <option value="rujukan_internal">Rujukan Internal</option>
                        <option value="rujukan_eksternal">Rujukan Eksternal</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-ncpms">Dietisien / SpGK</label>
                    <select name="dietisien_id" class="form-select form-select-sm">
                        <option value="">-- Belum ditentukan --</option>
                        @foreach($dietisiens as $d)
                        <option value="{{ $d->id }}">{{ $d->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-ncpms">Diagnosis Medis</label>
                    <select name="diagnosis_medis_utama_id" class="form-select form-select-sm">
                        <option value="">-- Kosong --</option>
                        @foreach($diagnosisMedis as $dm)
                        <option value="{{ $dm->id }}">{{ $dm->kode_icd10 }} - {{ $dm->nama_diagnosis }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-9">
                    <label class="form-label-ncpms">Asal Rujukan / Catatan Awal</label>
                    <input name="asal_rujukan" class="form-control form-control-sm" placeholder="Contoh: Rujukan Poli Penyakit Dalam dr. X">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn w-100 fw-bold py-1" style="background: var(--color-primary); color: white; border-radius: 8px; font-size: 0.88rem; border: none;">
                        <i class="fas fa-plus me-1"></i> Daftarkan
                    </button>
                </div>
            </form>
        </div>

        {{-- Trend Chart --}}
        <div class="ncpms-card mb-3">
            <div class="card-title-custom">
                <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-chart-line"></i></span>
                Tren Antropometri
            </div>
            <div class="chart-wrap" style="height: 220px;"><canvas id="trendChart"></canvas></div>
        </div>

        {{-- Visit History --}}
        <div class="ncpms-card mb-0">
            <div class="card-title-custom">
                <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-timeline"></i></span>
                Riwayat Kunjungan
            </div>
            <div class="table-responsive" style="border-radius: 10px; border: 1px solid var(--color-border);">
                <table class="table kunjungan-table mb-0">
                    <thead>
                        <tr>
                            <th>No. Kunjungan</th>
                            <th>Tanggal</th>
                            <th>Diagnosis Medis</th>
                            <th>Risiko</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pasien->kunjungans as $k)
                        <tr>
                            <td class="fw-bold" style="font-family: var(--font-mono); color: var(--color-primary); font-size: 0.82rem;">{{ $k->nomor_kunjungan }}</td>
                            <td>{{ $k->tanggal_kunjungan?->format('d/m/Y') }}</td>
                            <td><span class="text-dark">{{ $k->diagnosisMedisUtama->nama_diagnosis ?? '-' }}</span></td>
                            <td>
                                <span class="badge rounded-pill border
                                    @if($k->skriningGizi?->kategori_risiko=='risiko_tinggi') bg-danger-subtle text-danger border-danger-subtle
                                    @elseif($k->skriningGizi?->kategori_risiko=='risiko_sedang') bg-warning-subtle text-warning border-warning-subtle
                                    @else bg-success-subtle text-success border-success-subtle @endif"
                                    style="font-size: 0.72rem;">
                                    {{ str_replace('_',' ', $k->skriningGizi->kategori_risiko ?? 'belum') }}
                                </span>
                            </td>
                            <td><span class="text-muted fw-bold" style="font-size: 0.78rem;">{{ str_replace('_',' ',strtoupper($k->status)) }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('kunjungan.show', $k) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    style="border-radius: 7px; font-size: 0.78rem; padding: 4px 10px;">
                                    <i class="fas fa-external-link-alt me-1"></i>Buka
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4" style="font-size: 0.88rem;">Belum ada riwayat kunjungan.</td></tr>
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
    AOS.init({ duration: 700, once: true });

    @if($pasien->riwayatAlergi->count())
    NCPMS_SWAL.peringatanKlinis('Peringatan Alergi Pasien', 'Pasien memiliki alergi tercatat. Periksa profil alergi sebelum menyusun menu atau preskripsi diet.');
    @endif

    @php
        $kunjungansChart = $pasien->kunjungans->reverse()->filter(fn($k) => $k->antropometri !== null)->values();
        $labels  = $kunjungansChart->map(fn($k) => $k->tanggal_kunjungan->format('d/m/Y'))->toJson();
        $bbData  = $kunjungansChart->map(fn($k) => $k->antropometri->berat_badan_kg)->toJson();
        $imtData = $kunjungansChart->map(fn($k) => $k->antropometri->indeks_massa_tubuh)->toJson();
    @endphp

    const ctxTrend = document.getElementById('trendChart');
    if(ctxTrend) {
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: {!! $labels !!},
                datasets: [
                    { label: 'BB (kg)', data: {!! $bbData !!}, borderColor: '#128260', backgroundColor: 'rgba(18,130,96,0.08)', borderWidth: 2, tension: 0.3, fill: true, yAxisID: 'y' },
                    { label: 'IMT', data: {!! $imtData !!}, borderColor: '#f59f00', backgroundColor: 'transparent', borderWidth: 2, borderDash: [5,5], tension: 0.3, yAxisID: 'y1' }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { labels: { font: { size: 11 } } } },
                scales: {
                    x: { grid: { display: false } },
                    y: { type: 'linear', display: true, position: 'left', title: { display: true, text: 'BB (kg)', font: { size: 11 } } },
                    y1: { type: 'linear', display: true, position: 'right', title: { display: true, text: 'IMT', font: { size: 11 } }, grid: { drawOnChartArea: false } }
                }
            }
        });
    }
</script>
@endpush
@endsection
