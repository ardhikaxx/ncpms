@extends('layouts.app')
@section('content')
<div class='page-header'>
    <h3 class='page-title'>Asesmen Antropometri</h3>
    <div class='page-subtitle'>Pasien: {{ decrypt($kunjungan->pasien->nama_lengkap) }} | Kunjungan: {{ $kunjungan->nomor_kunjungan }}</div>
</div>

@if(session('success'))
<div class='alert alert-success'>{{ session('success') }}</div>
@endif

<div class='row'>
    <div class='col-md-6'>
        <div class='ncpms-card'>
            <h5 class='card-title-custom mb-4'><i class='fas fa-weight card-title-icon'></i> Form Pengukuran</h5>
            <form action='{{ route("asesmen.antropometri.store") }}' method='POST'>
                @csrf
                <input type='hidden' name='kunjungan_id' value='{{ $kunjungan->id }}'>
                <div class='mb-3'>
                    <label class='form-label-ncpms'>Berat Badan (kg) <span class='required-mark'>*</span></label>
                    <input type='number' step='0.1' name='berat_badan_kg' class='form-control-ncpms form-control-clinical' required>
                </div>
                <div class='mb-4'>
                    <label class='form-label-ncpms'>Tinggi Badan (cm) <span class='required-mark'>*</span></label>
                    <input type='number' step='0.1' name='tinggi_badan_cm' class='form-control-ncpms form-control-clinical' required>
                </div>
                <button type='submit' class='btn-primary-ncpms w-100'><i class='fas fa-save'></i> Simpan & Kalkulasi IMT</button>
            </form>
        </div>
    </div>
    <div class='col-md-6'>
        @if($data)
        <div class='ncpms-card clinical-card risiko-{{ $data->status_gizi_imt == "normal" ? "rendah" : "tinggi" }}'>
            <h5 class='card-title-custom mb-4'>Hasil Asesmen Terakhir</h5>
            <div class='row text-center'>
                <div class='col-4'>
                    <div class='clinical-value-card'>
                        <div class='cv-label'>Berat</div>
                        <div class='cv-value text-primary'>{{ decrypt($data->berat_badan_kg) }}</div>
                        <div class='cv-unit'>kg</div>
                    </div>
                </div>
                <div class='col-4'>
                    <div class='clinical-value-card'>
                        <div class='cv-label'>Tinggi</div>
                        <div class='cv-value text-primary'>{{ decrypt($data->tinggi_badan_cm) }}</div>
                        <div class='cv-unit'>cm</div>
                    </div>
                </div>
                <div class='col-4'>
                    <div class='clinical-value-card' style='background: var(--color-primary-subtle);'>
                        <div class='cv-label'>IMT</div>
                        <div class='cv-value text-primary' style='font-size: 1.8rem;'>{{ decrypt($data->imt) }}</div>
                        <div class='cv-unit'>{{ strtoupper(str_replace('_', ' ', $data->status_gizi_imt)) }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
