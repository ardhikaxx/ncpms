@extends('layouts.app')
@section('title','Detail Pasien')
@section('breadcrumb','Pasien / Detail')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">{{ $pasien->nama_lengkap }}</h1><p class="page-subtitle text-mono">{{ $pasien->nomor_rekam_medis }} - {{ $pasien->tanggal_lahir?->age }} tahun</p></div>
    <div class="d-flex gap-2"><a href="{{ route('pasien.edit', $pasien) }}" class="btn-outline-ncpms"><i class="fas fa-pen"></i> Edit</a></div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-id-card"></i></span> Identitas</h2>
            <div class="mb-2"><strong>NIK:</strong> {{ $pasien->nik ?: '-' }}</div>
            <div class="mb-2"><strong>Telepon:</strong> {{ $pasien->nomor_telepon ?: '-' }}</div>
            <div class="mb-2"><strong>Alamat:</strong> {{ $pasien->alamat ?: '-' }}</div>
            <div><strong>BPJS:</strong> {{ $pasien->nomor_bpjs ?: '-' }}</div>
        </div>
        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-allergies"></i></span> Profil Alergi</h2>
            @forelse($pasien->riwayatAlergi as $alergi)
                <div class="alert alert-warning mb-2"><strong>{{ $alergi->nama_alergen }}</strong><br>{{ $alergi->reaksi }} ({{ $alergi->tingkat_keparahan }})</div>
            @empty
                <p class="text-muted mb-0">Tidak ada alergi tercatat.</p>
            @endforelse
        </div>
    </div>
    <div class="col-lg-8">
        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-calendar-plus"></i></span> Buat Kunjungan Baru</h2>
            <form method="POST" action="{{ route('pasien.kunjungan.store', $pasien) }}" class="row g-3">
                @csrf
                <div class="col-md-3"><label class="form-label-ncpms">Tanggal</label><input type="date" name="tanggal_kunjungan" class="form-control-ncpms" value="{{ date('Y-m-d') }}" required></div>
                <div class="col-md-3"><label class="form-label-ncpms">Tipe</label><select name="tipe_kunjungan" class="form-control-ncpms"><option value="mandiri">Mandiri</option><option value="rujukan_internal">Rujukan Internal</option><option value="rujukan_eksternal">Rujukan Eksternal</option></select></div>
                <div class="col-md-3"><label class="form-label-ncpms">Dietisien/SpGK</label><select name="dietisien_id" class="form-control-ncpms"><option value="">Belum ditentukan</option>@foreach($dietisiens as $d)<option value="{{ $d->id }}">{{ $d->nama_lengkap }}</option>@endforeach</select></div>
                <div class="col-md-3"><label class="form-label-ncpms">Diagnosis Medis</label><select name="diagnosis_medis_utama_id" class="form-control-ncpms"><option value="">-</option>@foreach($diagnosisMedis as $dm)<option value="{{ $dm->id }}">{{ $dm->kode_icd10 }} - {{ $dm->nama_diagnosis }}</option>@endforeach</select></div>
                <div class="col-12"><label class="form-label-ncpms">Asal Rujukan</label><input name="asal_rujukan" class="form-control-ncpms"></div>
                <div class="col-12"><button class="btn-primary-ncpms"><i class="fas fa-plus"></i> Daftarkan Kunjungan</button></div>
            </form>
        </div>
        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-timeline"></i></span> Riwayat Kunjungan</h2>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>No</th><th>Tanggal</th><th>Diagnosis Medis</th><th>Risiko</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                    @forelse($pasien->kunjungans as $k)
                        <tr>
                            <td class="text-mono">{{ $k->nomor_kunjungan }}</td>
                            <td>{{ $k->tanggal_kunjungan?->format('d/m/Y') }}</td>
                            <td>{{ $k->diagnosisMedisUtama->nama_diagnosis ?? '-' }}</td>
                            <td><span class="badge-risk risk-{{ $k->skriningGizi->kategori_risiko ?? 'risiko_rendah' }}">{{ str_replace('_',' ', $k->skriningGizi->kategori_risiko ?? 'belum') }}</span></td>
                            <td>{{ str_replace('_',' ', $k->status) }}</td>
                            <td><a href="{{ route('kunjungan.show', $k) }}" class="btn-outline-ncpms btn-sm-ncpms"><i class="fas fa-eye"></i> Buka</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada kunjungan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@if($pasien->riwayatAlergi->count())
@push('scripts')
<script>NCPMS_SWAL.peringatanKlinis('Peringatan Alergi Pasien', 'Pasien memiliki alergi tercatat. Periksa profil alergi sebelum menyusun menu atau preskripsi diet.');</script>
@endpush
@endif
@endsection
