@extends('layouts.app')
@section('title','Monitoring')
@section('breadcrumb','Monitoring & Evaluasi')
@section('content')
<div class="page-header"><div><h1 class="page-title">Monitoring & Evaluasi</h1><p class="page-subtitle">Evaluasi luaran PAGT, kepatuhan diet, dan rencana tindak lanjut.</p></div></div>
<div class="ncpms-card">
    <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-heart-pulse"></i></span> Input Monitoring</h2>
    <form method="POST" action="{{ route('monitoring.store') }}" class="row g-3">
        @csrf
        <div class="col-md-4"><label class="form-label-ncpms">Kunjungan</label><select name="kunjungan_id" class="form-control-ncpms" required><option value="">Pilih</option>@foreach($kunjungans as $k)<option value="{{ $k->id }}">{{ $k->nomor_kunjungan }} - {{ $k->pasien->nama_lengkap }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label-ncpms">Parameter Dipantau</label><input name="parameter_dipantau" class="form-control-ncpms" value="berat badan,HbA1c,asupan energi,kepatuhan diet" required></div>
        <div class="col-md-2"><label class="form-label-ncpms">Kepatuhan</label><select name="evaluasi_kepatuhan_diet" class="form-control-ncpms"><option value="patuh">Patuh</option><option value="cukup_patuh">Cukup Patuh</option><option value="tidak_patuh">Tidak Patuh</option></select></div>
        <div class="col-md-2"><label class="form-label-ncpms">Sisa Makanan %</label><input type="number" step="0.1" name="persen_sisa_makanan" class="form-control-ncpms" value="15"></div>
        <div class="col-md-4"><label class="form-label-ncpms">Evaluasi Antropometri</label><textarea name="evaluasi_anthropometri" class="form-control-ncpms"></textarea></div>
        <div class="col-md-4"><label class="form-label-ncpms">Evaluasi Biokimia</label><textarea name="evaluasi_biokimia" class="form-control-ncpms"></textarea></div>
        <div class="col-md-4"><label class="form-label-ncpms">Evaluasi Asupan</label><textarea name="evaluasi_asupan" class="form-control-ncpms"></textarea></div>
        <div class="col-md-6"><label class="form-label-ncpms">Kesimpulan</label><textarea name="kesimpulan" class="form-control-ncpms"></textarea></div>
        <div class="col-md-6"><label class="form-label-ncpms">Rekomendasi Lanjutan</label><textarea name="rekomendasi_lanjutan" class="form-control-ncpms"></textarea></div>
        <div class="col-md-4"><label class="form-label-ncpms">Rencana Kunjungan Berikutnya</label><input type="date" name="rencana_kunjungan_berikutnya" class="form-control-ncpms" value="{{ now()->addWeeks(2)->format('Y-m-d') }}"></div>
        <div class="col-md-8"><label class="form-label-ncpms">Tujuan Rujukan bila perlu</label><input name="tujuan_rujukan" class="form-control-ncpms"></div>
        <div class="col-12 form-check ms-2"><input class="form-check-input" type="checkbox" name="perlu_rujukan" value="1" id="perlu_rujukan"><label class="form-check-label" for="perlu_rujukan">Perlu rujukan lanjutan</label></div>
        <div class="col-12"><button class="btn-primary-ncpms"><i class="fas fa-save"></i> Simpan Monitoring</button></div>
    </form>
</div>
<div class="ncpms-card">
    <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-table"></i></span> Riwayat Monitoring</h2>
    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Kunjungan</th><th>Pasien</th><th>Parameter</th><th>Kepatuhan</th><th>Rencana Kontrol</th><th>Pelaksana</th></tr></thead><tbody>
    @foreach($monitorings as $m)
        <tr><td class="text-mono">{{ $m->kunjungan->nomor_kunjungan }}</td><td>{{ $m->kunjungan->pasien->nama_tersamar }}</td><td>{{ implode(', ', $m->parameter_dipantau ?? []) }}</td><td>{{ str_replace('_',' ', $m->evaluasi_kepatuhan_diet ?? '-') }}</td><td>{{ $m->rencana_kunjungan_berikutnya?->format('d/m/Y') ?? '-' }}</td><td>{{ $m->pelaksana->nama_lengkap ?? '-' }}</td></tr>
    @endforeach
    </tbody></table></div>{{ $monitorings->links('pagination::bootstrap-5') }}
</div>
@endsection
