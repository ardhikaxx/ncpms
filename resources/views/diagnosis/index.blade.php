@extends('layouts.app')
@section('title','Diagnosis Gizi')
@section('breadcrumb','Diagnosis Gizi')
@section('content')
<div class="page-header"><div><h1 class="page-title">Diagnosis Gizi</h1><p class="page-subtitle">Penegakan diagnosis dengan format PES.</p></div></div>
<div class="ncpms-card">
    <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-stethoscope"></i></span> Input Diagnosis PES</h2>
    <form method="POST" action="{{ route('diagnosis.store') }}" class="row g-3">
        @csrf
        <div class="col-md-4"><label class="form-label-ncpms">Kunjungan</label><select name="kunjungan_id" class="form-control-ncpms" required><option value="">Pilih</option>@foreach($kunjungans as $k)<option value="{{ $k->id }}">{{ $k->nomor_kunjungan }} - {{ $k->pasien->nama_lengkap }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label-ncpms">Terminologi</label><select name="terminologi_id" class="form-control-ncpms" required>@foreach($terminologis as $t)<option value="{{ $t->id }}" data-domain="{{ $t->domain }}">{{ $t->kode_diagnosis }} - {{ $t->nama_masalah }}</option>@endforeach</select></div>
        <div class="col-md-2"><label class="form-label-ncpms">Domain</label><select name="domain" class="form-control-ncpms"><option value="asupan">Asupan</option><option value="klinis">Klinis</option><option value="perilaku_lingkungan">Perilaku/Lingkungan</option></select></div>
        <div class="col-md-2"><label class="form-label-ncpms">Prioritas</label><input type="number" name="urutan_prioritas" class="form-control-ncpms" value="1" min="1" max="9"></div>
        <div class="col-md-6"><label class="form-label-ncpms">Etiologi</label><textarea name="etiologi_penyebab" class="form-control-ncpms" required></textarea></div>
        <div class="col-md-6"><label class="form-label-ncpms">Signs/Symptoms</label><textarea name="signs_symptoms" class="form-control-ncpms" required></textarea></div>
        <div class="col-12"><button class="btn-primary-ncpms"><i class="fas fa-save"></i> Simpan Diagnosis</button></div>
    </form>
</div>
<div class="ncpms-card">
    <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-list"></i></span> Daftar Diagnosis</h2>
    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Kunjungan</th><th>Pasien</th><th>Diagnosis PES</th><th>Status</th><th>Validasi</th></tr></thead><tbody>
    @foreach($diagnosas as $d)
        <tr><td class="text-mono">{{ $d->kunjungan->nomor_kunjungan }}</td><td>{{ $d->kunjungan->pasien->nama_tersamar }}</td><td>{{ $d->narasi_pes }}</td><td>{{ $d->status }}</td><td>
            @if($d->divalidasi_pada) <span class="badge text-bg-success">Valid</span>
            @elseif(Auth::user()->peran === 'spgk') 
                @if($d->kunjungan?->dokumen_terkunci)
                    <span class="text-danger"><i class="fas fa-lock"></i> Terkunci</span>
                @else
                    <form method="POST" action="{{ route('diagnosis.validasi', $d) }}">@csrf<button class="btn-primary-ncpms btn-sm-ncpms">Validasi</button></form>
                @endif
            @else <span class="text-muted">Menunggu SpGK</span>@endif
        </td></tr>
    @endforeach
    </tbody></table></div>{{ $diagnosas->links('pagination::bootstrap-5') }}
</div>
@endsection
