@extends('layouts.app')
@section('title', 'Asesmen Antropometri')
@section('breadcrumb', 'Kunjungan / Asesmen Antropometri')

@push('styles')
<style>
    .imt-preview {
        background: var(--color-primary-subtle);
        border: 1px dashed var(--color-primary-border);
        border-radius: var(--radius-md);
        padding: 1rem 1.25rem;
    }

    .imt-ref-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: var(--radius-sm);
        font-size: 0.78rem;
        border: 1px solid rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')

@if($kunjungan->status === 'selesai' || $kunjungan->status === 'batal' || $kunjungan->dokumen_terkunci)
    <div class="locked-banner">
        <i class="fas fa-lock"></i>
        <div>
            <span class="fw-bold">Dokumen Klinis Terkunci</span> —
            Status kunjungan: <strong>{{ str_replace('_', ' ', strtoupper($kunjungan->status)) }}</strong>. Data antropometri tidak dapat diubah.
        </div>
    </div>
@endif

<div class="page-header mb-4">
    <div>
        <h1 class="page-title"><i class="fas fa-ruler-combined me-2" style="color: var(--color-primary);"></i>Asesmen Antropometri</h1>
        <p class="page-subtitle">Pencatatan dimensi fisik dan komposisi tubuh pasien.</p>
    </div>
    <a href="{{ route('kunjungan.show', $kunjungan) }}" class="btn-ncpms-outline">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row g-3">
    {{-- Form --}}
    <div class="col-lg-5">
        <div class="ncpms-card" style="position: sticky; top: 90px;">
            <div class="card-title-custom">
                <span class="card-title-icon"><i class="fas fa-ruler-vertical"></i></span>
                Input Pengukuran
            </div>
            <form action="{{ route('asesmen.antropometri.store') }}" method="POST">
                @csrf
                <input type="hidden" name="kunjungan_id" value="{{ $kunjungan->id }}">

                <div class="mb-3">
                    <label class="form-label-ncpms">Berat Badan Aktual</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0" style="border-color: var(--color-border);"><i class="fas fa-weight-scale text-muted"></i></span>
                        <input type="number" step="0.1" name="berat_badan_kg" id="bb_input"
                            class="form-control form-control-ncpms border-start-0 ps-1"
                            placeholder="0.0"
                            value="{{ $data ? decrypt($data->berat_badan_kg) : '' }}"
                            required {{ $kunjungan->dokumen_terkunci ? 'disabled' : '' }}>
                        <span class="input-group-text bg-light text-muted fw-bold" style="border-color: var(--color-border);">kg</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-ncpms">Tinggi Badan</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0" style="border-color: var(--color-border);"><i class="fas fa-arrows-up-down text-muted"></i></span>
                        <input type="number" step="0.1" name="tinggi_badan_cm" id="tb_input"
                            class="form-control form-control-ncpms border-start-0 ps-1"
                            placeholder="0.0"
                            value="{{ $data ? decrypt($data->tinggi_badan_cm) : '' }}"
                            required {{ $kunjungan->dokumen_terkunci ? 'disabled' : '' }}>
                        <span class="input-group-text bg-light text-muted fw-bold" style="border-color: var(--color-border);">cm</span>
                    </div>
                </div>

                {{-- Live IMT Preview --}}
                <div class="imt-preview mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold" style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.04em; color: var(--color-text-secondary);">Estimasi IMT (Otomatis)</span>
                        <span id="preview_status" class="badge bg-secondary" style="font-size: 0.72rem;">Menunggu Input</span>
                    </div>
                    <div class="fw-bold" id="preview_imt" style="font-size: 2rem; color: var(--color-primary-dark);">0.00</div>
                </div>

                @if(!$kunjungan->dokumen_terkunci)
                    <button type="submit" class="btn-ncpms w-100 py-2" style="font-size: 0.92rem;">
                        <i class="fas fa-save me-1"></i> Simpan Asesmen
                    </button>
                @endif
            </form>
        </div>
    </div>

    {{-- Results --}}
    <div class="col-lg-7">
        @if($data)
            <div class="ncpms-card mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="card-title-custom mb-0">
                        <span class="card-title-icon"><i class="fas fa-clipboard-check"></i></span>
                        Hasil Kalkulasi Sistem
                    </div>
                    <div class="text-muted" style="font-size: 0.78rem;"><i class="fas fa-clock me-1"></i>{{ $data->created_at->format('d M Y, H:i') }}</div>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-4">
                        <div class="stat-card text-center">
                            <div class="stat-label">Berat Aktual</div>
                            <div class="stat-value">{{ decrypt($data->berat_badan_kg) }}</div>
                            <div class="text-muted" style="font-size: 0.82rem;">kg</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-card text-center">
                            <div class="stat-label">Tinggi Badan</div>
                            <div class="stat-value">{{ decrypt($data->tinggi_badan_cm) }}</div>
                            <div class="text-muted" style="font-size: 0.82rem;">cm</div>
                        </div>
                    </div>
                    <div class="col-4">
                        @php
                            $status = $data->status_gizi_imt;
                            $bgCard = '#f8faf9';
                            $valColor = 'var(--color-text-primary)';
                            if($status == 'obesitas') { $bgCard = '#fff5f5'; $valColor = '#e03131'; }
                            elseif($status == 'kurang') { $bgCard = '#fffbeb'; $valColor = '#92400e'; }
                            elseif($status == 'normal') { $bgCard = '#f0fdf4'; $valColor = '#166534'; }
                            elseif($status == 'lebih') { $bgCard = '#fff7ed'; $valColor = '#c2410c'; }
                        @endphp
                        <div class="stat-card text-center" style="background: {{ $bgCard }};">
                            <div class="stat-label">Skor IMT</div>
                            <div class="stat-value" style="color: {{ $valColor }};">{{ decrypt($data->imt) }}</div>
                            <div class="text-muted" style="font-size: 0.82rem;">kg/m²</div>
                        </div>
                    </div>
                </div>

                <div class="section-divider">Kesimpulan Status Gizi</div>

                <div class="text-center p-3 rounded" style="background: var(--color-divider); border: 1px solid var(--color-border);">
                    <span class="badge-pill
                        @if($status == 'normal') badge-soft-success
                        @elseif($status == 'kurang') badge-soft-warning
                        @elseif($status == 'lebih') badge-soft-warning
                        @elseif($status == 'obesitas') badge-soft-danger
                        @else badge-soft-gray @endif"
                        style="font-size: 0.85rem; padding: 5px 16px;">
                        {{ strtoupper(str_replace('_', ' ', $status)) }}
                    </span>
                </div>
            </div>

            <div class="ncpms-card">
                <div class="card-title-custom">
                    <span class="card-title-icon"><i class="fas fa-info-circle"></i></span>
                    Referensi Klasifikasi IMT (WHO Asia-Pasifik)
                </div>
                <div class="row g-2">
                    @foreach([['< 18.5','Kurang','#fef3c7','#92400e'],['18.5 – 24.9','Normal','#dcfce7','#166534'],['25.0 – 29.9','Lebih','#ffedd5','#c2410c'],['≥ 30.0','Obesitas','#fee2e2','#991b1b']] as [$range, $label, $bg, $color])
                    <div class="col-6">
                        <div class="imt-ref-item" style="background: {{ $bg }};">
                            <span class="fw-bold" style="color: {{ $color }}; min-width: 80px;">{{ $range }}</span>
                            <span style="color: {{ $color }};">{{ $label }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-weight fa-3x mb-3"></i>
                <h5>Belum Ada Data Antropometri</h5>
                <p>Isi formulir di samping untuk menyimpan dan mengkalkulasi IMT secara otomatis.</p>
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bbInput = document.getElementById('bb_input');
        const tbInput = document.getElementById('tb_input');
        const previewImt = document.getElementById('preview_imt');
        const previewStatus = document.getElementById('preview_status');

        function calculateLiveImt() {
            let bb = parseFloat(bbInput.value);
            let tb = parseFloat(tbInput.value);
            if (bb > 0 && tb > 0) {
                let imt = (bb / Math.pow(tb / 100, 2)).toFixed(2);
                previewImt.innerText = imt;
                let statusText, statusClass;
                if (imt < 18.5) { statusText = 'KURANG'; statusClass = 'bg-warning text-dark'; }
                else if (imt <= 24.9) { statusText = 'NORMAL'; statusClass = 'bg-success'; }
                else if (imt <= 29.9) { statusText = 'LEBIH'; statusClass = 'bg-orange'; }
                else { statusText = 'OBESITAS'; statusClass = 'bg-danger'; }
                previewStatus.className = 'badge ' + statusClass;
                previewStatus.innerText = statusText;
            } else {
                previewImt.innerText = '0.00';
                previewStatus.className = 'badge bg-secondary';
                previewStatus.innerText = 'Menunggu Input';
            }
        }
        bbInput.addEventListener('input', calculateLiveImt);
        tbInput.addEventListener('input', calculateLiveImt);
        calculateLiveImt();
    });
</script>
@endpush
