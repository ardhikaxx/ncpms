@extends('layouts.app')
@section('title','Diagnosis Gizi')
@section('breadcrumb','Diagnosis Gizi')

@push('styles')
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .diagnosis-banner {
            background: linear-gradient(135deg, #c62828 0%, #e53935 100%);
            border-radius: 20px;
            padding: 2.5rem 3rem;
            color: white;
            box-shadow: 0 10px 30px rgba(198, 40, 40, 0.2);
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .diagnosis-banner::before {
            content: ''; position: absolute; right: -5%; top: -20%; width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%;
        }
        .diagnosis-banner::after {
            content: ''; position: absolute; right: 15%; bottom: -50%; width: 250px; height: 250px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%); border-radius: 50%;
        }
        .section-divider {
            display: flex; align-items: center; margin: 1.5rem 0 1rem; color: #c62828; font-weight: 700;
        }
        .section-divider::after {
            content: ''; flex: 1; height: 1px; background: rgba(0,0,0,0.08); margin-left: 1rem;
        }
        .pes-block {
            background: #fff;
            border-left: 4px solid #e53935;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            margin-bottom: 1.5rem;
        }
        .pes-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #c62828;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }
        .pes-keyword {
            color: #1976d2;
            font-weight: bold;
            font-style: italic;
        }
        .input-group-text-premium {
            background: rgba(198, 40, 40, 0.05);
            border: 1px solid var(--color-border);
            border-right: none;
            color: #c62828;
        }
        .form-control-ncpms.with-icon { border-left: none; }
        .form-control-ncpms.with-icon:focus { border-left: 1px solid #c62828; }
        .badge-status { padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; }
    </style>
@endpush

@section('content')

<!-- Welcome Banner -->
<div class="diagnosis-banner animate__animated animate__fadeInDown">
    <div class="row align-items-center position-relative z-index-1">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-2" style="font-size: 2.2rem; letter-spacing: -0.02em;">
                Diagnosis Gizi <span style="font-size: 1.8rem;">🩺</span>
            </h1>
            <p class="fs-6 opacity-75 mb-0" style="font-family: var(--font-secondary);">Penegakan diagnosis dengan format PES (Problem, Etiology, Signs/Symptoms).</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-4" data-aos="fade-right" data-aos-delay="100">
        <div class="ncpms-card h-100 mb-0 shadow-sm">
            <h2 class="card-title-custom"><span class="card-title-icon" style="color: #c62828; background: rgba(198,40,40,0.1);"><i class="fas fa-book-medical"></i></span> Format PES</h2>
            <div class="alert bg-danger-subtle text-danger border-0 rounded-3 p-4">
                <h5 class="fw-bold"><i class="fas fa-magic me-2"></i> Auto-Narasi</h5>
                <p class="mb-2" style="font-size: 0.9rem;">Sistem akan merangkai input Anda secara otomatis menjadi kalimat baku klinis:</p>
                <div class="bg-white p-3 rounded-3 shadow-sm mt-3" style="font-size: 0.85rem; line-height: 1.6; color: #333;">
                    <span class="text-danger fw-bold">[Problem]</span> <br>
                    <span class="pes-keyword">berkaitan dengan</span> <br>
                    <span class="text-warning fw-bold text-dark">[Etiology]</span> <br>
                    <span class="pes-keyword">ditandai dengan</span> <br>
                    <span class="text-success fw-bold text-dark">[Signs/Symptoms]</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8" data-aos="fade-left" data-aos-delay="200">
        <div class="ncpms-card mb-0 shadow-sm h-100">
            <h2 class="card-title-custom"><span class="card-title-icon" style="color: #c62828; background: rgba(198,40,40,0.1);"><i class="fas fa-pen-nib"></i></span> Input Diagnosis</h2>
            <form method="POST" action="{{ route('diagnosis.store') }}" class="row g-3">
                @csrf
                
                <div class="col-md-12">
                    <label class="form-label-ncpms">Pilih Kunjungan / Pasien <span class="required-mark">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text input-group-text-premium"><i class="fas fa-user-injured"></i></span>
                        <select name="kunjungan_id" class="form-select form-control-ncpms with-icon" required>
                            <option value="">-- Pilih Pasien Terjadwal --</option>
                            @foreach($kunjungans as $k)
                            <option value="{{ $k->id }}">{{ $k->nomor_kunjungan }} - {{ $k->pasien->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="section-divider"><i class="fas fa-exclamation-circle me-2"></i> P (Problem)</div>
                
                <div class="col-md-8">
                    <label class="form-label-ncpms">Terminologi Masalah <span class="required-mark">*</span></label>
                    <select name="terminologi_id" class="form-select form-control-ncpms" required>
                        <option value="">-- Pilih Kode Terminologi --</option>
                        @foreach($terminologis as $t)
                        <option value="{{ $t->id }}" data-domain="{{ $t->domain }}">{{ $t->kode_diagnosis }} - {{ $t->nama_masalah }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label-ncpms">Domain</label>
                    <select name="domain" class="form-select form-control-ncpms">
                        <option value="asupan">Asupan</option>
                        <option value="klinis">Klinis</option>
                        <option value="perilaku_lingkungan">Perilaku/Lingkungan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label-ncpms">Prioritas</label>
                    <input type="number" name="urutan_prioritas" class="form-control-ncpms" value="1" min="1" max="9" placeholder="1 = Tertinggi">
                </div>

                <div class="section-divider"><i class="fas fa-link me-2"></i> E & S (Etiology, Signs/Symptoms)</div>

                <div class="col-md-12">
                    <label class="form-label-ncpms">Etiologi (Penyebab Dasar) <span class="required-mark">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0 fst-italic" style="font-size: 0.85rem;">berkaitan dengan</span>
                        <textarea name="etiologi_penyebab" class="form-control form-control-ncpms border-start-0 ps-2" rows="2" required placeholder="Jelaskan akar penyebab masalah gizi pasien..."></textarea>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <label class="form-label-ncpms">Signs/Symptoms (Tanda & Gejala) <span class="required-mark">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0 fst-italic" style="font-size: 0.85rem;">ditandai dengan</span>
                        <textarea name="signs_symptoms" class="form-control form-control-ncpms border-start-0 ps-2" rows="2" required placeholder="Data objektif/subjektif dari antropometri/biokimia/klinis/asupan..."></textarea>
                    </div>
                </div>

                <div class="col-12 mt-4 text-end">
                    <button class="btn btn-primary-ncpms px-4 py-2" style="background: linear-gradient(135deg, #c62828 0%, #e53935 100%); border: none; font-size: 1rem;">
                        <i class="fas fa-stethoscope me-2"></i> Tegakkan Diagnosis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="ncpms-card mb-0 shadow-sm" data-aos="fade-up" data-aos-delay="400">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <h2 class="card-title-custom border-0 mb-0 pb-0">
            <span class="card-title-icon" style="background: linear-gradient(135deg, #c62828 0%, #e53935 100%); color: white;">
                <i class="fas fa-list-ul"></i>
            </span> 
            Daftar Diagnosis Gizi
        </h2>
    </div>
    
    <div class="table-responsive" style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(0,0,0,0.05);">
        <table class="table align-middle table-hover-premium mb-0">
            <thead style="background: rgba(0,0,0,0.02);">
                <tr>
                    <th class="ps-4" style="width: 15%">Kunjungan</th>
                    <th style="width: 15%">Pasien</th>
                    <th style="width: 45%">Narasi PES</th>
                    <th style="width: 10%">Status</th>
                    <th class="pe-4 text-end" style="width: 15%">Validasi SpGK</th>
                </tr>
            </thead>
            <tbody>
            @forelse($diagnosas as $d)
                <tr>
                    <td class="ps-4">
                        <div class="text-mono fw-bold text-dark">{{ $d->kunjungan->nomor_kunjungan }}</div>
                        <div class="text-muted small"><i class="far fa-clock"></i> {{ $d->created_at?->format('d/m/Y') ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $d->kunjungan->pasien->nama_tersamar }}</div>
                        <div class="badge bg-light text-dark border mt-1">Prio: {{ $d->urutan_prioritas }}</div>
                    </td>
                    <td>
                        <div class="pes-block m-0 p-3 shadow-none bg-light border-0" style="border-left: 3px solid #e53935 !important;">
                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">
                                {{ $d->terminologi->nama_masalah ?? 'Problem' }}
                            </div>
                            <div class="text-muted mt-1" style="font-size: 0.85rem; line-height: 1.5;">
                                <span class="pes-keyword">berkaitan dengan</span> {{ $d->etiologi_penyebab }}
                                <br>
                                <span class="pes-keyword">ditandai dengan</span> {{ $d->signs_symptoms }}
                            </div>
                        </div>
                    </td>
                    <td>
                        @php $st = $d->status; @endphp
                        <span class="badge-status border @if($st=='aktif') bg-success-subtle text-success border-success-subtle @elseif($st=='teratasi') bg-secondary-subtle text-secondary border-secondary-subtle @else bg-warning-subtle text-warning border-warning-subtle @endif">
                            {{ strtoupper($st) }}
                        </span>
                    </td>
                    <td class="pe-4 text-end">
                        @if($d->divalidasi_pada) 
                            <div class="text-success fw-bold" style="font-size: 0.85rem;"><i class="fas fa-check-double"></i> Valid</div>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ $d->spgk->nama_lengkap ?? '' }}</div>
                        @elseif(Auth::user()->peran === 'spgk') 
                            @if($d->kunjungan?->dokumen_terkunci)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="fas fa-lock"></i> Terkunci</span>
                            @else
                                <form method="POST" action="{{ route('diagnosis.validasi', $d) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-primary fw-bold" style="border-radius: 8px;"><i class="fas fa-check-circle me-1"></i> Validasi</button>
                                </form>
                            @endif
                        @else 
                            <span class="badge bg-light text-muted border"><i class="fas fa-hourglass-half"></i> Menunggu</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="fas fa-stethoscope fa-3x mb-3 opacity-25"></i><br>
                        Belum ada diagnosis gizi yang ditegakkan.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $diagnosas->links('pagination::bootstrap-5') }}
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
