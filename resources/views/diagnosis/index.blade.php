@extends('layouts.app')
@section('title','Diagnosis Gizi')
@section('breadcrumb','Diagnosis Gizi')

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

        .section-divider {
            display: flex; align-items: center; gap: 10px;
            margin: 1.25rem 0 0.75rem; color: var(--color-primary); font-weight: 700; font-size: 0.85rem;
        }
        .section-divider::after { content: ''; flex: 1; height: 1px; background: var(--color-border); }

        .pes-block {
            background: white; border-left: 3px solid var(--color-primary);
            padding: 10px 14px; border-radius: 8px;
            box-shadow: var(--shadow-sm);
        }
        .pes-kw { color: var(--color-primary); font-weight: 600; font-style: italic; font-size: 0.82rem; }

        .data-table thead th {
            background: #f8faf9; font-size: 0.73rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--color-text-muted); border-bottom: 1px solid var(--color-border);
            padding: 10px 14px;
        }
        .data-table td { padding: 11px 14px; vertical-align: middle; border-bottom: 1px solid var(--color-divider); font-size: 0.88rem; }
        .data-table tbody tr:hover { background: var(--color-primary-subtle); }

        .info-card {
            background: rgba(18,130,96,0.06); border: 1px solid var(--color-primary-border);
            border-radius: 12px; padding: 1.25rem; font-size: 0.85rem;
        }
        .info-card h6 { font-weight: 700; color: var(--color-primary-dark); margin-bottom: 0.5rem; }
        .info-card p { color: var(--color-text-secondary); margin-bottom: 0; }

        .pes-preview {
            background: white; border-radius: 10px;
            border: 1px solid var(--color-border);
            padding: 1rem; font-size: 0.85rem; line-height: 1.7; margin-top: 0.75rem;
        }
    </style>
@endpush

@section('content')

<div class="page-banner" data-aos="fade-down">
    <h1>Diagnosis Gizi <span style="font-size: 1.4rem;">🩺</span></h1>
    <p>Penegakan diagnosis dengan format PES (Problem, Etiology, Signs/Symptoms).</p>
</div>

<div class="row g-3 mb-4">
    {{-- Format PES Info --}}
    <div class="col-xl-4" data-aos="fade-right" data-aos-delay="80">
        <div class="ncpms-card h-100 mb-0">
            <div class="card-title-custom">
                <span class="card-title-icon" style="background: rgba(18,130,96,0.1); color: var(--color-primary);"><i class="fas fa-book-medical"></i></span>
                Format PES
            </div>
            <div class="info-card mb-3">
                <h6><i class="fas fa-magic me-1"></i> Auto-Narasi</h6>
                <p>Sistem merangkai input Anda secara otomatis menjadi kalimat baku klinis.</p>
            </div>
            <div class="pes-preview">
                <span class="fw-bold text-danger">[Problem]</span><br>
                <span class="pes-kw">berkaitan dengan</span><br>
                <span class="fw-bold" style="color: #b45309;">[Etiology]</span><br>
                <span class="pes-kw">ditandai dengan</span><br>
                <span class="fw-bold text-success">[Signs/Symptoms]</span>
            </div>

            <div class="mt-3 p-3 rounded" style="background: #f8faf9; border: 1px solid var(--color-border);">
                <div class="fw-bold mb-2" style="font-size: 0.82rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.04em;">Domain Diagnosis</div>
                <div class="d-flex flex-wrap gap-1">
                    <span class="badge" style="background: #dbeafe; color: #1e40af; font-size: 0.75rem; padding: 5px 10px; border-radius: 6px;">Asupan</span>
                    <span class="badge" style="background: #dcfce7; color: #166534; font-size: 0.75rem; padding: 5px 10px; border-radius: 6px;">Klinis</span>
                    <span class="badge" style="background: #fef3c7; color: #92400e; font-size: 0.75rem; padding: 5px 10px; border-radius: 6px;">Perilaku/Lingkungan</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Input Form --}}
    <div class="col-xl-8" data-aos="fade-left" data-aos-delay="100">
        <div class="ncpms-card mb-0 h-100">
            <div class="card-title-custom">
                <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-pen-nib"></i></span>
                Input Diagnosis Gizi
            </div>
            <form method="POST" action="{{ route('diagnosis.store') }}" class="row g-3">
                @csrf
                <div class="col-12">
                    <label class="form-label-ncpms">Pilih Kunjungan / Pasien <span class="required-mark">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" style="background: var(--color-primary-subtle); border-color: var(--color-border); color: var(--color-primary);">
                            <i class="fas fa-user-injured"></i>
                        </span>
                        <select name="kunjungan_id" class="form-select form-control-ncpms" style="border-left: none;" required>
                            <option value="">-- Pilih Pasien Terjadwal --</option>
                            @foreach($kunjungans as $k)
                            <option value="{{ $k->id }}">{{ $k->nomor_kunjungan }} - {{ $k->pasien->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12">
                    <div class="section-divider"><i class="fas fa-exclamation-circle"></i> P (Problem)</div>
                </div>
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
                        <option value="perilaku_lingkungan">Perilaku/Lingk.</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label-ncpms">Prioritas</label>
                    <input type="number" name="urutan_prioritas" class="form-control-ncpms" value="1" min="1" max="9">
                </div>

                <div class="col-12">
                    <div class="section-divider"><i class="fas fa-link"></i> E &amp; S (Etiology, Signs/Symptoms)</div>
                </div>
                <div class="col-12">
                    <label class="form-label-ncpms">Etiologi (Penyebab Dasar) <span class="required-mark">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text fst-italic text-muted" style="font-size: 0.82rem; background: #f8faf9; border-color: var(--color-border);">berkaitan dengan</span>
                        <textarea name="etiologi_penyebab" class="form-control form-control-ncpms" rows="2" required
                            placeholder="Jelaskan akar penyebab masalah gizi pasien..." style="border-left: none;"></textarea>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label-ncpms">Signs/Symptoms (Tanda &amp; Gejala) <span class="required-mark">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text fst-italic text-muted" style="font-size: 0.82rem; background: #f8faf9; border-color: var(--color-border);">ditandai dengan</span>
                        <textarea name="signs_symptoms" class="form-control form-control-ncpms" rows="2" required
                            placeholder="Data objektif/subjektif dari antropometri/biokimia/klinis/asupan..." style="border-left: none;"></textarea>
                    </div>
                </div>

                <div class="col-12 text-end mt-2">
                    <button class="btn fw-bold px-4 py-2" style="background: var(--color-primary); color: white; border: none; border-radius: 10px; font-size: 0.95rem;">
                        <i class="fas fa-stethoscope me-2"></i>Tegakkan Diagnosis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Diagnosis List --}}
<div class="ncpms-card mb-0" data-aos="fade-up" data-aos-delay="200">
    <div class="card-title-custom border-bottom pb-3 mb-3">
        <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-list-ul"></i></span>
        Daftar Diagnosis Gizi
    </div>
    <div class="table-responsive" style="border-radius: 10px; border: 1px solid var(--color-border);">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 14%; padding-left: 16px;">Kunjungan</th>
                    <th style="width: 14%;">Pasien</th>
                    <th style="width: 44%;">Narasi PES</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 18%;" class="text-end" style="padding-right: 16px;">Validasi SpGK</th>
                </tr>
            </thead>
            <tbody>
                @forelse($diagnosas as $d)
                <tr>
                    <td style="padding-left: 16px;">
                        <div class="fw-bold" style="font-family: var(--font-mono); font-size: 0.82rem; color: var(--color-primary);">{{ $d->kunjungan->nomor_kunjungan }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;"><i class="far fa-clock me-1"></i>{{ $d->created_at?->format('d/m/Y') ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $d->kunjungan->pasien->nama_tersamar }}</div>
                        <span class="badge bg-light border text-dark" style="font-size: 0.72rem;">Prio: {{ $d->urutan_prioritas }}</span>
                    </td>
                    <td>
                        <div class="pes-block">
                            <div class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $d->terminologi->nama_masalah ?? 'Problem' }}</div>
                            <div class="text-muted mt-1" style="font-size: 0.82rem; line-height: 1.5;">
                                <span class="pes-kw">berkaitan dengan</span> {{ $d->etiologi_penyebab }}<br>
                                <span class="pes-kw">ditandai dengan</span> {{ $d->signs_symptoms }}
                            </div>
                        </div>
                    </td>
                    <td>
                        @php $st = $d->status; @endphp
                        <span class="badge border" style="padding: 5px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700;
                            @if($st=='aktif') background: #d1fae5; color: #065f46; border-color: #a7f3d0 !important;
                            @elseif($st=='teratasi') background: #f3f4f6; color: #4b5563; border-color: #d1d5db !important;
                            @else background: #fef3c7; color: #92400e; border-color: #fde68a !important; @endif">
                            {{ strtoupper($st) }}
                        </span>
                    </td>
                    <td class="text-end" style="padding-right: 16px;">
                        @if($d->divalidasi_pada)
                            <div class="text-success fw-bold" style="font-size: 0.82rem;"><i class="fas fa-check-double me-1"></i>Valid</div>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $d->spgk->nama_lengkap ?? '' }}</div>
                        @elseif(Auth::user()->peran === 'spgk')
                            @if($d->kunjungan?->dokumen_terkunci)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="fas fa-lock me-1"></i>Terkunci</span>
                            @else
                                <form method="POST" action="{{ route('diagnosis.validasi', $d) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm fw-bold px-3" style="background: var(--color-primary); color: white; border-radius: 8px; border: none; font-size: 0.8rem;">
                                        <i class="fas fa-check-circle me-1"></i>Validasi
                                    </button>
                                </form>
                            @endif
                        @else
                            <span class="badge bg-light text-muted border" style="font-size: 0.75rem;"><i class="fas fa-hourglass-half me-1"></i>Menunggu</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="fas fa-stethoscope fa-2x mb-2 d-block opacity-25"></i>
                        Belum ada diagnosis gizi yang ditegakkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $diagnosas->links('pagination::bootstrap-5') }}</div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ duration: 700, once: true, offset: 40 });</script>
@endpush
