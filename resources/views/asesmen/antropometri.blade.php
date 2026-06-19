@extends('layouts.app')
@section('title', 'Asesmen Antropometri')
@section('breadcrumb', 'Kunjungan / Asesmen Antropometri')

@push('styles')
<style>
    .measure-card {
        background: white; border-radius: 12px; padding: 1.25rem;
        border: 1px solid var(--color-border); text-align: center;
        box-shadow: var(--shadow-sm);
    }
    .measure-card .label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-text-muted); margin-bottom: 6px; }
    .measure-card .value { font-size: 1.8rem; font-weight: 800; color: var(--color-text-primary); line-height: 1; }
    .measure-card .unit { font-size: 0.85rem; color: var(--color-text-muted); font-weight: 500; }

    .imt-preview {
        background: var(--color-primary-subtle);
        border: 1px dashed var(--color-primary-border);
        border-radius: 12px; padding: 1.1rem 1.25rem;
    }
    .locked-banner {
        background: linear-gradient(135deg, #e03131, #c92a2a);
        color: white; border-radius: 12px; padding: 1rem 1.5rem;
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 1.5rem; box-shadow: 0 4px 12px rgba(224,49,49,0.2);
    }
    .empty-state {
        background: rgba(18,130,96,0.03);
        border: 1px dashed var(--color-primary-border);
        border-radius: 14px; padding: 3rem; text-align: center;
        min-height: 380px; display: flex; flex-direction: column; align-items: center; justify-content: center;
    }
</style>
@endpush

@section('content')

@if($kunjungan->status === 'selesai' || $kunjungan->status === 'batal' || $kunjungan->dokumen_terkunci)
    <div class="locked-banner">
        <i class="fas fa-lock fa-lg"></i>
        <div>
            <div class="fw-bold">Dokumen Klinis Terkunci</div>
            <div class="small" style="opacity: 0.8;">Status kunjungan ini adalah <strong>{{ str_replace('_', ' ', strtoupper($kunjungan->status)) }}</strong>. Anda tidak dapat mengubah data antropometri.</div>
        </div>
    </div>
@endif

<div class="page-header mb-4">
    <div>
        <h1 class="page-title"><i class="fas fa-ruler-combined me-2" style="color: var(--color-primary);"></i>Asesmen Antropometri (ADIME)</h1>
        <p class="page-subtitle">Pencatatan dimensi fisik dan komposisi tubuh pasien secara presisi.</p>
    </div>
    <a href="{{ route('kunjungan.show', $kunjungan) }}"
        class="btn fw-bold px-3 py-2"
        style="background: transparent; border: 1.5px solid var(--color-border); color: var(--color-text-secondary); border-radius: 10px; font-size: 0.88rem;">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Rekam Kunjungan
    </a>
</div>

<div class="row g-3">
    {{-- Form --}}
    <div class="col-lg-5">
        <div class="ncpms-card shadow-sm" style="position: sticky; top: 90px; border-top: 3px solid var(--color-primary);">
            <div class="card-title-custom">
                <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-ruler-vertical"></i></span>
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
                    <button type="submit" class="btn fw-bold w-100 py-2" style="background: var(--color-primary); color: white; border: none; border-radius: 10px; font-size: 0.95rem;">
                        <i class="fas fa-save me-1"></i> Simpan Asesmen
                    </button>
                @endif
            </form>
        </div>
    </div>

    {{-- Results --}}
    <div class="col-lg-7">
        @if($data)
            <div class="ncpms-card shadow-sm mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="card-title-custom mb-0">
                        <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-clipboard-check"></i></span>
                        Hasil Kalkulasi Sistem
                    </div>
                    <div class="text-muted" style="font-size: 0.78rem;"><i class="fas fa-clock me-1"></i>{{ $data->created_at->format('d M Y, H:i') }}</div>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-4">
                        <div class="measure-card">
                            <div class="label">Berat Aktual</div>
                            <div class="value">{{ decrypt($data->berat_badan_kg) }}</div>
                            <div class="unit">kg</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="measure-card">
                            <div class="label">Tinggi Badan</div>
                            <div class="value">{{ decrypt($data->tinggi_badan_cm) }}</div>
                            <div class="unit">cm</div>
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
                        <div class="measure-card" style="background: {{ $bgCard }};">
                            <div class="label">Skor IMT</div>
                            <div class="value" style="color: {{ $valColor }};">{{ decrypt($data->imt) }}</div>
                            <div class="unit">kg/m²</div>
                        </div>
                    </div>
                </div>

                <div class="text-center p-3 rounded" style="background: #f8faf9; border: 1px solid var(--color-border);">
                    <div class="fw-bold mb-2" style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.04em; color: var(--color-text-muted);">Kesimpulan Status Gizi IMT</div>
                    <span class="badge px-4 py-2 rounded-pill" style="font-size: 0.88rem; letter-spacing: 0.04em;
                        @if($status == 'normal') background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;
                        @elseif($status == 'kurang') background: #fef3c7; color: #92400e; border: 1px solid #fde68a;
                        @elseif($status == 'lebih') background: #ffedd5; color: #c2410c; border: 1px solid #fed7aa;
                        @elseif($status == 'obesitas') background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;
                        @else background: #f3f4f6; color: #4b5563; border: 1px solid #e5e7eb; @endif">
                        {{ strtoupper(str_replace('_', ' ', $status)) }}
                    </span>
                </div>
            </div>

            <div class="p-3 rounded" style="background: var(--color-primary-subtle); border: 1px dashed var(--color-primary-border);">
                <div class="fw-bold mb-2" style="font-size: 0.8rem; color: var(--color-primary-dark);">
                    <i class="fas fa-info-circle me-1"></i>Referensi Klasifikasi IMT (WHO Asia-Pasifik)
                </div>
                <div class="row g-1" style="font-size: 0.78rem;">
                    @foreach([['< 18.5','Kurang','#fef3c7','#92400e'],['18.5 – 24.9','Normal','#dcfce7','#166534'],['25.0 – 29.9','Lebih','#ffedd5','#c2410c'],['≥ 30.0','Obesitas','#fee2e2','#991b1b']] as [$range, $label, $bg, $color])
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2 p-2 rounded" style="background: {{ $bg }}; border: 1px solid rgba(0,0,0,0.05);">
                            <span class="fw-bold" style="color: {{ $color }}; min-width: 80px;">{{ $range }}</span>
                            <span style="color: {{ $color }};">{{ $label }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-weight fa-3x mb-3 opacity-20" style="color: var(--color-primary);"></i>
                <h5 class="fw-bold text-muted mb-1">Belum Ada Data Antropometri</h5>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">Isi formulir di samping untuk menyimpan dan mengkalkulasi IMT secara otomatis.</p>
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
