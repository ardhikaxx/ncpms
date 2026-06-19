@extends('layouts.app')
@section('title','Laporan')
@section('breadcrumb','Laporan Statistik')

@push('styles')
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .laporan-banner {
            background-color: var(--color-primary);
            border-radius: 20px;
            padding: 2.5rem 3rem;
            color: white;
            box-shadow: 0 10px 30px rgba(201, 75, 75, 0.2);
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .laporan-banner::before {
            content: ''; position: absolute; right: -5%; top: -20%; width: 300px; height: 300px;
            background-color: rgba(255,255,255,0.05); border-radius: 50%;
        }
        .laporan-banner::after {
            content: ''; position: absolute; right: 15%; bottom: -50%; width: 250px; height: 250px;
            background-color: rgba(255,255,255,0.05); border-radius: 50%;
        }
        .btn-export-pdf {
            background: #fff;
            color: var(--color-primary);
            border: 1px solid #fff;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-export-pdf:hover {
            background: rgba(255,255,255,0.9);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            color: var(--color-primary);
        }
        .btn-export-excel {
            background: rgba(0,0,0,0.2);
            color: var(--color-primary);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-export-excel:hover {
            background: rgba(0,0,0,0.3);
            border-color: var(--color-primary);
            color: var(--color-primary);
            transform: translateY(-2px);
        }
        
        .stat-card-laporan {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            height: 100%;
        }
        .stat-card-laporan:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(201, 75, 75, 0.1);
        }
        .stat-card-laporan::before {
            content: ''; position: absolute; left: 0; top: 0; height: 100%; width: 4px;
            background: #c94b4b;
        }
        
        .json-preview {
            background: #282c34;
            color: var(--color-primary);
            padding: 1.5rem;
            border-radius: 12px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.85rem;
            overflow-x: auto;
        }
        .json-key { color: var(--color-primary); }
        .json-value { color: var(--color-primary); }
    </style>
@endpush

@section('content')

<!-- Welcome Banner -->
<div class="laporan-banner animate__animated animate__fadeInDown">
    <div class="row align-items-center position-relative z-index-1">
        <div class="col-lg-7">
            <h1 class="fw-bold mb-2" style="font-size: 2.2rem; letter-spacing: -0.02em;">
                Laporan Statistik <span style="font-size: 1.8rem;">📊</span>
            </h1>
            <p class="fs-6 opacity-75 mb-0" style="font-family: var(--font-secondary);">Audit mutu, SPM gizi, dan kinerja harian berbasis periode.</p>
        </div>
        <div class="col-lg-5 text-lg-end mt-4 mt-lg-0">
            <a href="{{ route('export.pasien.pdf') }}" target="_blank" class="btn btn-export-pdf px-4 py-2 me-2">
                <i class="fas fa-file-pdf me-1"></i> Export Pasien (PDF)
            </a>
            <a href="{{ route('export.laporan.excel') }}" target="_blank" class="btn btn-export-excel px-4 py-2">
                <i class="fas fa-file-excel me-1"></i> Export Laporan (Excel)
            </a>
        </div>
    </div>
</div>

<div class="ncpms-card shadow-sm mb-4" data-aos="fade-up" data-aos-delay="100">
    <h2 class="card-title-custom border-bottom pb-3 mb-4">
        <span class="card-title-icon" style="color: white; background-color: var(--color-primary);">
            <i class="fas fa-filter"></i>
        </span> 
        Filter Laporan
    </h2>
    <form method="GET" action="{{ route('laporan.index') }}" class="row g-3">
        <div class="col-md-3">
            <label class="form-label-ncpms">Tipe Laporan</label>
            <select name="tipe_laporan" class="form-select form-control-ncpms" style="border-radius: 10px;">
                <option value="kinerja_harian" {{ request('tipe_laporan') == 'kinerja_harian' ? 'selected' : '' }}>Kinerja Harian</option>
                <option value="demografi_patologi" {{ request('tipe_laporan') == 'demografi_patologi' ? 'selected' : '' }}>Demografi Patologi</option>
                <option value="rasio_intervensi" {{ request('tipe_laporan') == 'rasio_intervensi' ? 'selected' : '' }}>Rasio Intervensi</option>
                <option value="spm_gizi" {{ request('tipe_laporan') == 'spm_gizi' ? 'selected' : '' }}>SPM Gizi</option>
                <option value="audit_mutu" {{ request('tipe_laporan') == 'audit_mutu' ? 'selected' : '' }}>Audit Mutu</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label-ncpms">Periode Dari</label>
            <input type="date" name="periode_dari" value="{{ $dari->format('Y-m-d') }}" class="form-control-ncpms" style="border-radius: 10px;">
        </div>
        <div class="col-md-3">
            <label class="form-label-ncpms">Periode Sampai</label>
            <input type="date" name="periode_sampai" value="{{ $sampai->format('Y-m-d') }}" class="form-control-ncpms" style="border-radius: 10px;">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary-ncpms w-100 py-2" style="border-radius: 10px; font-weight: bold;">
                <i class="fas fa-sync-alt me-2"></i> Buat Laporan
            </button>
        </div>
    </form>
</div>

<div class="row g-4 mb-4">
    @php $delay = 200; @endphp
    @foreach($ringkasan as $label => $nilai)
        <div class="col-md-4 col-sm-6" data-aos="zoom-in" data-aos-delay="{{ $delay }}">
            <div class="stat-card-laporan">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">{{ str_replace('_',' ', $label) }}</div>
                        <div class="fs-2 fw-bold text-dark">{{ $nilai }}</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 50px; height: 50px; background: rgba(201, 75, 75, 0.1); color: var(--color-primary); font-size: 1.4rem;">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
            </div>
        </div>
        @php $delay += 100; @endphp
    @endforeach
</div>

<div class="ncpms-card shadow-sm" data-aos="fade-up" data-aos-delay="400">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <h2 class="card-title-custom border-0 mb-0 pb-0">
            <span class="card-title-icon" style="color: white; background-color: var(--color-primary);">
                <i class="fas fa-file-medical"></i>
            </span> 
            Preview Laporan Data
        </h2>
        <div class="badge bg-light text-dark border px-3 py-2 fs-6 rounded-pill">
            <i class="fas fa-hashtag me-1"></i> LAP-{{ str_pad($laporan->id ?? 1, 5, '0', STR_PAD_LEFT) }}
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="d-flex align-items-center p-3 bg-light rounded-3 border">
                <i class="far fa-calendar-check fs-2 text-muted me-3"></i>
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Periode Audit</div>
                    <div class="fw-bold text-dark">{{ $dari->format('d F Y') }} <span class="text-muted mx-1">s/d</span> {{ $sampai->format('d F Y') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex align-items-center p-3 bg-light rounded-3 border mt-3 mt-md-0">
                <i class="fas fa-tags fs-2 text-muted me-3"></i>
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Tipe Laporan</div>
                    <div class="fw-bold text-dark">{{ ucwords(str_replace('_',' ', request('tipe_laporan', 'kinerja_harian'))) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-2">
        <h5 class="fw-bold mb-3 fs-6"><i class="fas fa-code text-muted me-2"></i>Raw Data Output (JSON)</h5>
        <div class="json-preview shadow-sm">
            @php
                $json = json_encode($ringkasan, JSON_PRETTY_PRINT);
                // Simple highlight trick for aesthetic
                $json = preg_replace('/"([^"]+)"\s*:/', '<span class="json-key">"$1"</span>:', $json);
                $json = preg_replace('/: \d+/', ': <span class="json-value">$0</span>', $json);
                // fix the space matched in the value regex
                $json = str_replace(': <span class="json-value">: ', ': <span class="json-value">', $json);
            @endphp
            <pre class="mb-0">{!! $json !!}</pre>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 50
    });
</script>
@endpush
