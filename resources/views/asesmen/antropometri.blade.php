@extends('layouts.app')
@section('title', 'Asesmen Antropometri')
@section('breadcrumb', 'Kunjungan / Asesmen Antropometri')
@section('content')

@if($kunjungan->status === 'selesai' || $kunjungan->status === 'batal' || $kunjungan->dokumen_terkunci)
    <div class="locked-banner shadow-sm">
        <i class="fas fa-lock fs-4"></i> 
        <div>
            <div>Dokumen Klinis Terkunci</div>
            <div class="small fw-normal text-white-50">Status kunjungan ini adalah <strong>{{ str_replace('_', ' ', strtoupper($kunjungan->status)) }}</strong>. Anda tidak dapat mengubah data antropometri.</div>
        </div>
    </div>
@endif

<div class="page-header mb-4">
    <div>
        <h1 class="page-title">Asesmen Antropometri (ADIME)</h1>
        <p class="page-subtitle">Pencatatan dimensi fisik dan komposisi tubuh pasien secara presisi.</p>
    </div>
    <a href="{{ route('kunjungan.show', $kunjungan) }}" class="btn-outline-ncpms">
        <i class="fas fa-arrow-left"></i> Kembali ke Rekam Kunjungan
    </a>
</div>

<div class="row g-4">
    <!-- Form Kolom -->
    <div class="col-lg-5">
        <div class="ncpms-card border-top border-4 border-primary shadow-sm" style="position: sticky; top: 90px;">
            <h2 class="card-title-custom">
                <span class="card-title-icon"><i class="fas fa-ruler-vertical"></i></span> 
                Input Pengukuran
            </h2>
            
            <form action="{{ route('asesmen.antropometri.store') }}" method="POST">
                @csrf
                <input type="hidden" name="kunjungan_id" value="{{ $kunjungan->id }}">
                
                <div class="mb-4">
                    <label class="form-label-ncpms">Berat Badan Aktual</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-weight-scale text-muted"></i></span>
                        <input type="number" step="0.1" name="berat_badan_kg" id="bb_input" class="form-control-ncpms border-start-0 ps-0" placeholder="0.0" value="{{ $data ? decrypt($data->berat_badan_kg) : '' }}" required {{ $kunjungan->dokumen_terkunci ? 'disabled' : '' }}>
                        <span class="input-group-text bg-light text-muted fw-bold">kg</span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label-ncpms">Tinggi Badan</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-arrows-up-down text-muted"></i></span>
                        <input type="number" step="0.1" name="tinggi_badan_cm" id="tb_input" class="form-control-ncpms border-start-0 ps-0" placeholder="0.0" value="{{ $data ? decrypt($data->tinggi_badan_cm) : '' }}" required {{ $kunjungan->dokumen_terkunci ? 'disabled' : '' }}>
                        <span class="input-group-text bg-light text-muted fw-bold">cm</span>
                    </div>
                </div>

                <!-- Live Preview IMT -->
                <div class="p-3 mb-4 rounded" style="background: var(--color-primary-subtle); border: 1px dashed var(--color-primary-border);">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary fw-bold text-uppercase" style="font-size: 0.75rem;">Estimasi IMT (Otomatis)</span>
                        <span id="preview_status" class="badge bg-secondary">Menunggu Input</span>
                    </div>
                    <div class="fs-2 fw-bold text-primary-dark" id="preview_imt">0.00</div>
                </div>
                
                @if(!$kunjungan->dokumen_terkunci)
                    <button type="submit" class="btn-primary-ncpms w-100 py-2 fs-6">
                        <i class="fas fa-save me-1"></i> Simpan Asesmen
                    </button>
                @endif
            </form>
        </div>
    </div>

    <!-- Riwayat Kolom -->
    <div class="col-lg-7">
        @if($data)
            <div class="ncpms-card shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <h2 class="card-title-custom border-bottom-0 mb-0 pb-0">
                        <span class="card-title-icon" style="background-color: var(--color-primary); color: white;"><i class="fas fa-clipboard-check"></i></span> 
                        Hasil Kalkulasi Sistem
                    </h2>
                    <div class="text-muted small"><i class="fas fa-clock"></i> {{ $data->created_at->format('d M Y, H:i') }}</div>
                </div>

                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="p-4 rounded shadow-sm border" style="background: #fff;">
                            <div class="text-muted fw-bold mb-2 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Berat Aktual</div>
                            <div class="fs-3 fw-bold text-dark">{{ decrypt($data->berat_badan_kg) }} <span class="fs-6 text-muted fw-normal">kg</span></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-4 rounded shadow-sm border" style="background: #fff;">
                            <div class="text-muted fw-bold mb-2 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Tinggi</div>
                            <div class="fs-3 fw-bold text-dark">{{ decrypt($data->tinggi_badan_cm) }} <span class="fs-6 text-muted fw-normal">cm</span></div>
                        </div>
                    </div>
                    <div class="col-4">
                        @php
                            $status = $data->status_gizi_imt;
                            $bgCard = '#fff'; $textClass = 'text-dark';
                            if($status == 'obesitas') { $bgCard = '#fff5f5'; $textClass = 'text-danger'; }
                            elseif($status == 'kurang') { $bgCard = '#fff9db'; $textClass = 'text-warning'; }
                            elseif($status == 'normal') { $bgCard = '#ebfbee'; $textClass = 'text-success'; }
                        @endphp
                        <div class="p-4 rounded shadow-sm border" style="background: {{ $bgCard }};">
                            <div class="text-muted fw-bold mb-2 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Skor IMT</div>
                            <div class="fs-3 fw-bold {{ $textClass }}">{{ decrypt($data->imt) }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 text-center">
                    <div class="fw-bold text-muted mb-2 text-uppercase" style="font-size: 0.8rem;">Kesimpulan Status Gizi IMT</div>
                    <div class="badge px-4 py-2 fs-6 rounded-pill text-uppercase shadow-sm
                        @if($status == 'normal') bg-success 
                        @elseif($status == 'kurang') bg-warning text-dark 
                        @elseif($status == 'lebih') bg-orange 
                        @elseif($status == 'obesitas') bg-danger 
                        @else bg-secondary @endif">
                        {{ str_replace('_', ' ', $status) }}
                    </div>
                </div>
            </div>
        @else
            <div class="ncpms-card d-flex flex-column align-items-center justify-content-center py-5 text-center" style="min-height: 400px; background: rgba(26,122,100,0.02); border: 1px dashed var(--color-primary-border);">
                <i class="fas fa-weight fa-4x text-muted opacity-25 mb-3"></i>
                <h4 class="fw-bold text-secondary">Belum Ada Data Antropometri</h4>
                <p class="text-muted">Silakan isi formulir di samping untuk menyimpan dan mengkalkulasi IMT secara otomatis.</p>
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
                let tbMeter = tb / 100;
                let imt = bb / (tbMeter * tbMeter);
                imt = imt.toFixed(2);
                
                previewImt.innerText = imt;
                
                let statusText = '';
                let statusClass = '';

                if (imt < 18.5) { statusText = 'KURANG'; statusClass = 'bg-warning text-dark'; }
                else if (imt >= 18.5 && imt <= 24.9) { statusText = 'NORMAL'; statusClass = 'bg-success'; }
                else if (imt >= 25.0 && imt <= 29.9) { statusText = 'LEBIH'; statusClass = 'bg-orange'; }
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
        
        // Initial calculation on load if values exist
        calculateLiveImt();
    });
</script>
@endpush
