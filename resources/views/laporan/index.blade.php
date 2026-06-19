@extends('layouts.app')
@section('title','Laporan')
@section('breadcrumb','Laporan Statistik')
@section('content')
<div class="page-header"><div><h1 class="page-title">Laporan Statistik</h1><p class="page-subtitle">Laporan kinerja, SPM gizi, dan audit mutu berbasis periode.</p></div><div><a href="{{ route('export.pasien.pdf') }}" target="_blank" class="btn-danger-ncpms" style="margin-right: 8px;"><i class="fas fa-file-pdf"></i> Export Pasien (PDF)</a><a href="{{ route('export.laporan.excel') }}" target="_blank" class="btn-primary-ncpms"><i class="fas fa-file-excel"></i> Export Laporan (Excel)</a></div></div>
<div class="ncpms-card">
    <form method="GET" action="{{ route('laporan.index') }}" class="row g-3">
        <div class="col-md-3"><label class="form-label-ncpms">Tipe Laporan</label><select name="tipe_laporan" class="form-control-ncpms"><option value="kinerja_harian">Kinerja Harian</option><option value="demografi_patologi">Demografi Patologi</option><option value="rasio_intervensi">Rasio Intervensi</option><option value="spm_gizi">SPM Gizi</option><option value="audit_mutu">Audit Mutu</option></select></div>
        <div class="col-md-3"><label class="form-label-ncpms">Periode Dari</label><input type="date" name="periode_dari" value="{{ $dari->format('Y-m-d') }}" class="form-control-ncpms"></div>
        <div class="col-md-3"><label class="form-label-ncpms">Periode Sampai</label><input type="date" name="periode_sampai" value="{{ $sampai->format('Y-m-d') }}" class="form-control-ncpms"></div>
        <div class="col-md-3 d-flex align-items-end"><button class="btn-primary-ncpms"><i class="fas fa-filter"></i> Tampilkan</button></div>
    </form>
</div>
<div class="row g-3 mb-4">
    @foreach($ringkasan as $label => $nilai)
        <div class="col-md-4"><div class="stat-card"><div class="stat-label">{{ str_replace('_',' ', $label) }}</div><div class="stat-value">{{ $nilai }}</div></div></div>
    @endforeach
</div>
<div class="ncpms-card">
    <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-file-medical"></i></span> Preview Laporan</h2>
    <p class="mb-1"><strong>Nomor Laporan:</strong> LAP-{{ str_pad($laporan->id, 5, '0', STR_PAD_LEFT) }}</p>
    <p class="mb-1"><strong>Periode:</strong> {{ $dari->format('d/m/Y') }} - {{ $sampai->format('d/m/Y') }}</p>
    <p class="mb-0"><strong>Data:</strong> {{ json_encode($ringkasan, JSON_PRETTY_PRINT) }}</p>
</div>
@endsection
