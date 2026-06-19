@extends('layouts.app')
@section('title','Detail Pasien')
@section('breadcrumb','Pasien / Detail')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $pasien->nama_lengkap }}</h1>
        <p class="page-subtitle">
            <span class="rm-badge">{{ $pasien->nomor_rekam_medis }}</span>
            {{ $pasien->tanggal_lahir?->age }} thn &bull;
            {{ $pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pasien.cppt', $pasien) }}" class="btn-ncpms">
            <i class="fas fa-notes-medical me-1"></i> Buka CPPT
        </a>
        <a href="{{ route('pasien.edit', $pasien) }}" class="btn-ncpms-outline">
            Edit Profil
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Left: Identity + Allergy --}}
    <div class="col-lg-4">
        {{-- Patient Card --}}
        <div class="ncpms-card mb-3">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="avatar-circle avatar-lg">{{ substr($pasien->nama_tersamar, 0, 1) }}</div>
                <div>
                    <div class="fw-bold">{{ $pasien->nama_tersamar }}</div>
                    <div class="text-muted small">{{ $pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ $pasien->tanggal_lahir?->age }} tahun</div>
                    <span class="badge-pill mt-1 {{ $pasien->status_aktif ? 'badge-soft-success' : 'badge-soft-gray' }}">
                        {{ $pasien->status_aktif ? 'AKTIF' : 'NONAKTIF' }}
                    </span>
                </div>
            </div>

            <div class="section-divider"></div>

            <h6 class="card-title-custom">Identitas Pribadi</h6>
            <div class="info-row">
                <div>
                    <div class="info-label">NIK</div>
                    <div class="info-value">{{ $pasien->nik ?: '-' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div>
                    <div class="info-label">No. Telepon</div>
                    <div class="info-value">{{ $pasien->nomor_telepon ?: '-' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div>
                    <div class="info-label">Alamat</div>
                    <div class="info-value">{{ $pasien->alamat ?: '-' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div>
                    <div class="info-label">No. BPJS</div>
                    <div class="info-value">{{ $pasien->nomor_bpjs ?: '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Allergy --}}
        <div class="ncpms-card">
            <div class="card-title-custom">Profil Alergi</div>
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
    <div class="col-lg-8">
        {{-- New Visit Form --}}
        <div class="ncpms-card mb-3">
            <div class="card-title-custom">Buat Kunjungan Baru</div>
            <form method="POST" action="{{ route('pasien.kunjungan.store', $pasien) }}" class="row g-2">
                @csrf
                <div class="col-md-3">
                    <label class="form-label-ncpms">Tanggal <span class="required-mark">*</span></label>
                    <input type="date" name="tanggal_kunjungan" class="form-control-ncpms" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label-ncpms">Tipe <span class="required-mark">*</span></label>
                    <select name="tipe_kunjungan" class="form-control-ncpms">
                        <option value="mandiri">Mandiri</option>
                        <option value="rujukan_internal">Rujukan Internal</option>
                        <option value="rujukan_eksternal">Rujukan Eksternal</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-ncpms">Dietisien / SpGK</label>
                    <select name="dietisien_id" class="form-control-ncpms">
                        <option value="">-- Belum ditentukan --</option>
                        @foreach($dietisiens as $d)
                        <option value="{{ $d->id }}">{{ $d->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-ncpms">Diagnosis Medis</label>
                    <select name="diagnosis_medis_utama_id" class="form-control-ncpms">
                        <option value="">-- Kosong --</option>
                        @foreach($diagnosisMedis as $dm)
                        <option value="{{ $dm->id }}">{{ $dm->kode_icd10 }} - {{ $dm->nama_diagnosis }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-9">
                    <label class="form-label-ncpms">Asal Rujukan / Catatan Awal</label>
                    <input name="asal_rujukan" class="form-control-ncpms" placeholder="Contoh: Rujukan Poli Penyakit Dalam dr. X">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn-ncpms w-100">Daftarkan</button>
                </div>
            </form>
        </div>

        {{-- Trend Chart --}}
        <div class="ncpms-card mb-3">
            <div class="card-title-custom">Tren Antropometri</div>
            <div class="chart-wrap" style="height: 220px;"><canvas id="trendChart"></canvas></div>
        </div>

        {{-- Visit History --}}
        <div class="ncpms-card mb-0">
            <div class="card-title-custom">Riwayat Kunjungan</div>
            <div class="table-responsive">
                <table class="table data-table mb-0">
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
                            <td><span class="rm-badge">{{ $k->nomor_kunjungan }}</span></td>
                            <td>{{ $k->tanggal_kunjungan?->format('d/m/Y') }}</td>
                            <td>{{ $k->diagnosisMedisUtama->nama_diagnosis ?? '-' }}</td>
                            <td>
                                <span class="badge-pill
                                    @if($k->skriningGizi?->kategori_risiko=='risiko_tinggi') badge-soft-danger
                                    @elseif($k->skriningGizi?->kategori_risiko=='risiko_sedang') badge-soft-warning
                                    @else badge-soft-success @endif">
                                    {{ str_replace('_',' ', $k->skriningGizi->kategori_risiko ?? 'belum') }}
                                </span>
                            </td>
                            <td><span class="text-muted fw-bold small">{{ str_replace('_',' ',strtoupper($k->status)) }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('kunjungan.show', $k) }}" class="btn-sm-ncpms">Buka</a>
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
<script>
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
