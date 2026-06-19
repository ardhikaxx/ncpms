@extends('layouts.app')
@section('title','Laporan Statistik')
@section('breadcrumb','Laporan Statistik')

@push('styles')
    <style>
        .json-preview {
            background: #1e2432; color: #a8d8a8;
            padding: 1.5rem; border-radius: 10px;
            font-family: var(--font-mono); font-size: 0.82rem;
            overflow-x: auto; line-height: 1.7;
        }
        .json-key { color: #7dd3fc; }
        .json-value { color: #86efac; }

        .period-info-block {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 16px; background: #f8faf9;
            border: 1px solid var(--color-border); border-radius: 10px;
        }
        .period-info-block i { font-size: 1.4rem; color: var(--color-text-muted); }
        .period-info-block .label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--color-text-muted); }
        .period-info-block .value { font-weight: 700; color: var(--color-text-primary); font-size: 0.92rem; }
    </style>
@endpush

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fas fa-chart-bar me-2" style="color: var(--color-primary);"></i>Laporan Statistik</h1>
        <p class="page-subtitle">Audit mutu, SPM gizi, dan kinerja harian berbasis periode.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('export.pasien.pdf') }}" target="_blank" class="btn-ncpms">
            <i class="fas fa-file-pdf me-1"></i>Export Pasien (PDF)
        </a>
        <a href="{{ route('export.laporan.excel') }}" target="_blank" class="btn-ncpms-outline">
            <i class="fas fa-file-excel me-1"></i>Export Laporan (Excel)
        </a>
    </div>
</div>

{{-- Filter --}}
<div class="ncpms-card mb-4">
    <div class="card-title-custom mb-3">
        <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-filter"></i></span>
        Filter Laporan
    </div>
    <form method="GET" action="{{ route('laporan.index') }}" class="row g-3">
        <div class="col-md-3">
            <label class="form-label-ncpms">Tipe Laporan</label>
            <select name="tipe_laporan" class="form-select form-control-ncpms">
                <option value="kinerja_harian" {{ request('tipe_laporan')=='kinerja_harian'?'selected':'' }}>Kinerja Harian</option>
                <option value="demografi_patologi" {{ request('tipe_laporan')=='demografi_patologi'?'selected':'' }}>Demografi Patologi</option>
                <option value="rasio_intervensi" {{ request('tipe_laporan')=='rasio_intervensi'?'selected':'' }}>Rasio Intervensi</option>
                <option value="spm_gizi" {{ request('tipe_laporan')=='spm_gizi'?'selected':'' }}>SPM Gizi</option>
                <option value="audit_mutu" {{ request('tipe_laporan')=='audit_mutu'?'selected':'' }}>Audit Mutu</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label-ncpms">Periode Dari</label>
            <input type="date" name="periode_dari" value="{{ $dari->format('Y-m-d') }}" class="form-control-ncpms">
        </div>
        <div class="col-md-3">
            <label class="form-label-ncpms">Periode Sampai</label>
            <input type="date" name="periode_sampai" value="{{ $sampai->format('Y-m-d') }}" class="form-control-ncpms">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn-ncpms w-100">
                <i class="fas fa-sync-alt me-2"></i>Buat Laporan
            </button>
        </div>
    </form>
</div>

{{-- Stats Grid --}}
<div class="row g-3 mb-4">
    @php $icons = ['fa-users','fa-hospital-user','fa-stethoscope','fa-utensils','fa-heart-pulse','fa-file-medical-alt']; $i = 0; @endphp
    @foreach($ringkasan as $label => $nilai)
    <div class="col-md-4 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">{{ str_replace('_',' ', $label) }}</div>
                    <div class="stat-value mt-1">{{ $nilai }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas {{ $icons[$i % count($icons)] }}"></i>
                </div>
            </div>
        </div>
    </div>
    @php $i++; @endphp
    @endforeach
</div>

{{-- Preview --}}
<div class="ncpms-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="card-title-custom mb-0">
            <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-file-medical"></i></span>
            Preview Laporan Data
        </div>
        <span class="badge-pill" style="background: #f3f4f6; color: var(--color-text-primary); border: 1px solid var(--color-border);">
            <i class="fas fa-hashtag me-1"></i>LAP-{{ str_pad($laporan->id ?? 1, 5, '0', STR_PAD_LEFT) }}
        </span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="period-info-block">
                <i class="far fa-calendar-check"></i>
                <div>
                    <div class="label">Periode Audit</div>
                    <div class="value">{{ $dari->format('d F Y') }} <span class="text-muted mx-1">s/d</span> {{ $sampai->format('d F Y') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="period-info-block">
                <i class="fas fa-tags"></i>
                <div>
                    <div class="label">Tipe Laporan</div>
                    <div class="value">{{ ucwords(str_replace('_',' ', request('tipe_laporan', 'kinerja_harian'))) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <h6 class="fw-bold mb-2" style="font-size: 0.82rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.04em;">
            <i class="fas fa-code me-1"></i>Raw Data Output (JSON)
        </h6>
        <div class="json-preview shadow-sm">
            @php
                $json = json_encode($ringkasan, JSON_PRETTY_PRINT);
                $json = preg_replace('/"([^"]+)"\s*:/', '<span class="json-key">"$1"</span>:', $json);
                $json = preg_replace('/: \d+/', ': <span class="json-value">$0</span>', $json);
                $json = str_replace(': <span class="json-value">: ', ': <span class="json-value">', $json);
            @endphp
            <pre class="mb-0" style="color: inherit;">{!! $json !!}</pre>
        </div>
    </div>
</div>

@endsection
