@extends('layouts.app')
@section('title','Preskripsi Diet')
@section('breadcrumb','Intervensi / Preskripsi Diet')
@section('content')
<div class="page-header"><div><h1 class="page-title">Intervensi & Preskripsi Diet</h1><p class="page-subtitle">Kalkulator kalori, distribusi makro, bentuk makanan, dan otorisasi SpGK.</p></div></div>
<div class="ncpms-card">
    <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-calculator"></i></span> Form Preskripsi</h2>
    <form method="POST" action="{{ route('intervensi.store') }}" class="row g-3">
        @csrf
        <div class="col-md-4"><label class="form-label-ncpms">Kunjungan</label><select name="kunjungan_id" class="form-control-ncpms" required><option value="">Pilih</option>@foreach($kunjungans as $k)<option value="{{ $k->id }}">{{ $k->nomor_kunjungan }} - {{ $k->pasien->nama_lengkap }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label-ncpms">Formula Basal</label><select name="formula_basal" class="form-control-ncpms"><option value="harris_benedict">Harris-Benedict</option><option value="mifflin_st_jeor">Mifflin St Jeor</option><option value="who">WHO</option><option value="konsensus_dm">Konsensus DM</option><option value="konsensus_ckd">Konsensus CKD</option></select></div>
        <div class="col-md-2"><label class="form-label-ncpms">BB Acuan</label><select name="berat_badan_acuan" class="form-control-ncpms"><option value="aktual">Aktual</option><option value="ideal">Ideal</option><option value="adjusted">Adjusted</option></select></div>
        <div class="col-md-3"><label class="form-label-ncpms">Energi Basal</label><input type="number" step="0.1" name="kebutuhan_energi_basal_kkal" class="form-control-ncpms" value="1400" required></div>
        <div class="col-md-2"><label class="form-label-ncpms">Aktivitas</label><input type="number" step="0.1" min="1.2" max="1.9" name="faktor_aktivitas" class="form-control-ncpms" value="1.3"></div>
        <div class="col-md-2"><label class="form-label-ncpms">Stres</label><input type="number" step="0.1" min="1.0" max="2.0" name="faktor_stres" class="form-control-ncpms" value="1.1"></div>
        <div class="col-md-2"><label class="form-label-ncpms">KH %</label><input type="number" name="persen_karbohidrat" class="form-control-ncpms" value="50"></div>
        <div class="col-md-2"><label class="form-label-ncpms">Protein %</label><input type="number" name="persen_protein" class="form-control-ncpms" value="20"></div>
        <div class="col-md-2"><label class="form-label-ncpms">Lemak %</label><input type="number" name="persen_lemak" class="form-control-ncpms" value="30"></div>
        <div class="col-md-2"><label class="form-label-ncpms">Serat (g)</label><input type="number" name="gram_serat" class="form-control-ncpms" value="25"></div>
        <div class="col-md-3"><label class="form-label-ncpms">Bentuk Makanan</label><select name="bentuk_makanan" class="form-control-ncpms"><option value="biasa">Biasa</option><option value="lunak">Lunak</option><option value="saring">Saring</option><option value="cair_penuh">Cair Penuh</option><option value="cair_jernih">Cair Jernih</option><option value="formula_medis">Formula Medis</option></select></div>
        <div class="col-md-2"><label class="form-label-ncpms">Makan Utama</label><input type="number" name="frekuensi_makan_utama" class="form-control-ncpms" value="3"></div>
        <div class="col-md-2"><label class="form-label-ncpms">Selingan</label><input type="number" name="frekuensi_selingan" class="form-control-ncpms" value="2"></div>
        <div class="col-md-3"><label class="form-label-ncpms">Tanggal Mulai</label><input type="date" name="tanggal_mulai" class="form-control-ncpms" value="{{ date('Y-m-d') }}"></div>
        <div class="col-md-2"><label class="form-label-ncpms">Durasi</label><input type="number" name="durasi_hari" class="form-control-ncpms" value="14"></div>
        <div class="col-md-6"><label class="form-label-ncpms">Tujuan Terapi</label><textarea name="tujuan_terapi" class="form-control-ncpms" required>Memenuhi kebutuhan energi dan mengendalikan parameter metabolik.</textarea></div>
        <div class="col-md-6"><label class="form-label-ncpms">Catatan Klinis</label><textarea name="catatan_klinis" class="form-control-ncpms"></textarea></div>
        <div class="col-12"><button class="btn-accent-ncpms"><i class="fas fa-save"></i> Simpan Preskripsi</button></div>
    </form>
</div>
<div class="ncpms-card">
    <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-utensils"></i></span> Daftar Preskripsi</h2>
    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Kunjungan</th><th>Pasien</th><th>Energi</th><th>Makro</th><th>Status</th><th>Otorisasi</th></tr></thead><tbody>
    @foreach($preskripsis as $p)
        <tr><td class="text-mono">{{ $p->kunjungan->nomor_kunjungan }}</td><td>{{ $p->kunjungan->pasien->nama_tersamar }}</td><td>{{ number_format($p->total_kebutuhan_energi_kkal) }} kkal</td><td>KH {{ $p->gram_karbohidrat }}g / P {{ $p->gram_protein }}g / L {{ $p->gram_lemak }}g</td><td>{{ $p->status }}</td><td>
            @if($p->disetujui_pada) <span class="badge text-bg-success">Disetujui</span>
            @elseif(Auth::user()->peran === 'spgk') <form method="POST" action="{{ route('intervensi.setujui', $p) }}">@csrf<button class="btn-primary-ncpms btn-sm-ncpms">Setujui</button></form>
            @else <span class="text-muted">Menunggu SpGK</span>@endif
        </td></tr>
    @endforeach
    </tbody></table></div>{{ $preskripsis->links('pagination::bootstrap-5') }}
</div>
@endsection
