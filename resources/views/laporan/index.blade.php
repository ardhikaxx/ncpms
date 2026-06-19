@extends('layouts.app')
@section('title','Laporan Statistik')
@section('breadcrumb','Laporan Statistik')

@push('styles')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .page-banner {
            background: var(--color-primary);
            border-radius: 16px; padding: 1.75rem 2rem;
            color: white; position: relative; overflow: hidden; margin-bottom: 1.5rem;
        }
        .page-banner::before { content: ''; position: absolute; right: -50px; top: -60px; width: 220px; height: 220px; background: rgba(255,255,255,0.05); border-radius: 50%; }
        .page-banner::after { content: ''; position: absolute; right: 80px; bottom: -80px; width: 160px; height: 160px; background: rgba(255,255,255,0.04); border-radius: 50%; }
        .page-banner h1 { font-size: 1.8rem; font-weight: 800; letter-spacing: -0.02em; position: relative; z-index: 1; margin-bottom: 0.3rem; }
        .page-banner p { opacity: 0.8; position: relative; z-index: 1; margin: 0; font-size: 0.9rem; }

        .btn-export-pdf {
            background: white; color: var(--color-primary-dark);
            border: 1px solid white; border-radius: 50px; font-weight: 700;
            padding: 8px 20px; font-size: 0.88rem;
            transition: all 0.25s; position: relative; z-index: 1;
        }
        .btn-export-pdf:hover { background: rgba(255,255,255,0.92); transform: translateY(-2px); color: var(--color-primary-dark); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .btn-export-excel {
            background: rgba(0,0,0,0.18); color: white;
            border: 1px solid rgba(255,255,255,0.3); border-radius: 50px; font-weight: 700;
            padding: 8px 20px; font-size: 0.88rem;
            transition: all 0.25s; position: relative; z-index: 1;
        }
        .btn-export-excel:hover { background: rgba(0,0,0,0.28); transform: translateY(-2px); color: white; }

        /* Stat cards */
        .laporan-stat-card {
            background: #fff; border-radius: 13px; padding: 1.25rem 1.4rem;
            border: 1px solid var(--color-border); box-shadow: var(--shadow-sm);
            transition: var(--transition-all); position: relative; overflow: hidden; height: 100%;
        }
        .laporan-stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }
        .laporan-stat-card::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
            background: var(--color-primary); border-radius: 13px 0 0 13px;
        }
        .laporan-stat-card .stat-icon {
            width: 44px; height: 44px; border-radius: 11px;
            background: rgba(18,130,96,0.1); color: var(--color-primary);
            display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
        }
        .laporan-stat-card .stat-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-text-muted); }
        .laporan-stat-card .stat-value { font-size: 1.8rem; font-weight: 800; color: var(--color-text-primary); line-height: 1; }

        .json-preview {
            background: #1e2432; color: #a8d8a8;
            padding: 1.5rem; border-radius: 10px;
            font-family: var(--font-mono); font-size: 0.82rem;
            overflow-x: auto; line-height: 1.7;
        }
        .json-key { color: #7dd3fc; }
        .json-value { color: #86efac; }

        .filter-card {
            background: #fff; border-radius: 14px; padding: 1.5rem;
            border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); margin-bottom: 1.5rem;
        }
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

<div class="page-banner" data-aos="fade-down">
    <div class="row align-items-center">
        <div class="col-lg-7">
            <h1>Laporan Statistik <span style="font-size: 1.4rem;">📊</span></h1>
            <p>Audit mutu, SPM gizi, dan kinerja harian berbasis periode.</p>
        </div>
        <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('export.pasien.pdf') }}" target="_blank" class="btn-export-pdf me-2">
                <i class="fas fa-file-pdf me-1"></i>Export Pasien (PDF)
            </a>
            <a href="{{ route('export.laporan.excel') }}" target="_blank" class="btn-export-excel">
                <i class="fas fa-file-excel me-1"></i>Export Laporan (Excel)
            </a>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="filter-card" data-aos="fade-up" data-aos-delay="80">
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
            <button class="btn fw-bold w-100 py-2" style="background: var(--color-primary); color: white; border-radius: 10px; border: none; font-size: 0.9rem;">
                <i class="fas fa-sync-alt me-2"></i>Buat Laporan
            </button>
        </div>
    </form>
</div>

{{-- Stats Grid --}}
<div class="row g-3 mb-4">
    @php $icons = ['fa-users','fa-hospital-user','fa-stethoscope','fa-utensils','fa-heart-pulse','fa-file-medical-alt']; $i = 0; @endphp
    @foreach($ringkasan as $label => $nilai)
    <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ 80 + ($i * 50) }}">
        <div class="laporan-stat-card">
            <div class="d-flex justify-content-between align-items-center ps-1">
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
<div class="ncpms-card" data-aos="fade-up" data-aos-delay="200">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="card-title-custom mb-0">
            <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-file-medical"></i></span>
            Preview Laporan Data
        </div>
        <span class="badge bg-light border text-dark px-3 py-2 rounded-pill">
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

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ duration: 700, once: true, offset: 40 });</script>
@endpush
