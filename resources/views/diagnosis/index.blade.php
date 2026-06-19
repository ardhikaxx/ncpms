@extends('layouts.app')
@section('title','Diagnosis Gizi')
@section('breadcrumb','Diagnosis Gizi')

@push('styles')
    <style>
        .pes-block {
            border-left: 3px solid var(--color-primary);
            padding: 10px 14px;
            border-radius: 0 8px 8px 0;
            background: var(--color-divider);
        }
        .pes-kw { color: var(--color-primary); font-weight: 600; font-style: italic; font-size: 0.8rem; }

        .pes-preview {
            background: var(--color-divider); border-radius: 8px;
            border: 1px solid var(--color-border);
            padding: 1rem; font-size: 0.84rem; line-height: 1.8;
        }
    </style>
@endpush

@section('content')

<div class="page-header mb-4">
    <div>
        <h1 class="page-title"><i class="fas fa-stethoscope me-2" style="color: var(--color-primary);"></i>Diagnosis Gizi</h1>
        <p class="page-subtitle">Penegakan diagnosis dengan format PES (Problem, Etiology, Signs/Symptoms).</p>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Format PES Info --}}
    <div class="col-xl-4">
        <div class="ncpms-card h-100 mb-0">
            <div class="card-title-custom">
                <span class="card-title-icon"><i class="fas fa-book-medical"></i></span>
                Format PES
            </div>

            <div class="warning-clinical">
                <i class="fas fa-magic"></i>
                <div>
                    <div class="fw-bold" style="color: #92400e;">Auto-Narasi</div>
                    <div style="font-size: 0.82rem;">Sistem merangkai input Anda secara otomatis menjadi kalimat baku klinis.</div>
                </div>
            </div>

            <div class="pes-preview">
                <span class="fw-bold text-danger">[Problem]</span><br>
                <span class="pes-kw">berkaitan dengan</span><br>
                <span class="fw-bold" style="color: #b45309;">[Etiology]</span><br>
                <span class="pes-kw">ditandai dengan</span><br>
                <span class="fw-bold text-success">[Signs/Symptoms]</span>
            </div>

            <div class="section-divider mt-3"><i class="fas fa-tags"></i> Domain Diagnosis</div>
            <div class="d-flex flex-wrap gap-1">
                <span class="badge-pill badge-soft-primary">Asupan</span>
                <span class="badge-pill badge-soft-success">Klinis</span>
                <span class="badge-pill badge-soft-warning">Perilaku/Lingkungan</span>
            </div>
        </div>
    </div>

    {{-- Input Form --}}
    <div class="col-xl-8">
        <div class="ncpms-card mb-0 h-100">
            <div class="card-title-custom">
                <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-pen-nib"></i></span>
                Input Diagnosis Gizi
            </div>
            <form method="POST" action="{{ route('diagnosis.store') }}" class="row g-3">
                @csrf
                <div class="col-12">
                    <label class="form-label-ncpms">Pilih Kunjungan / Pasien <span class="required-mark">*</span></label>
                    <select name="kunjungan_id" class="form-select form-control-ncpms" required>
                        <option value="">-- Pilih Pasien Terjadwal --</option>
                        @foreach($kunjungans as $k)
                        <option value="{{ $k->id }}">{{ $k->nomor_kunjungan }} - {{ $k->pasien->nama_lengkap }}</option>
                        @endforeach
                    </select>
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
                    <textarea name="etiologi_penyebab" class="form-control form-control-ncpms" rows="2" required
                        placeholder="Jelaskan akar penyebab masalah gizi pasien..."></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label-ncpms">Signs/Symptoms (Tanda &amp; Gejala) <span class="required-mark">*</span></label>
                    <textarea name="signs_symptoms" class="form-control form-control-ncpms" rows="2" required
                        placeholder="Data objektif/subjektif dari antropometri/biokimia/klinis/asupan..."></textarea>
                </div>

                <div class="col-12 text-end mt-2">
                    <button class="btn btn-ncpms">
                        <i class="fas fa-stethoscope me-1"></i>Tegakkan Diagnosis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Diagnosis List --}}
<div class="ncpms-card mb-0">
    <div class="card-title-custom">
        <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-list-ul"></i></span>
        Daftar Diagnosis Gizi
    </div>
    <div class="table-responsive" style="border-radius: 8px; border: 1px solid var(--color-border);">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 14%;">Kunjungan</th>
                    <th style="width: 14%;">Pasien</th>
                    <th style="width: 44%;">Narasi PES</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 18%;" class="text-end">Validasi SpGK</th>
                </tr>
            </thead>
            <tbody>
                @forelse($diagnosas as $d)
                <tr>
                    <td>
                        <div class="fw-bold" style="font-family: var(--font-mono); font-size: 0.82rem; color: var(--color-primary);">{{ $d->kunjungan->nomor_kunjungan }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;"><i class="far fa-clock me-1"></i>{{ $d->created_at?->format('d/m/Y') ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark" style="font-size: 0.84rem;">{{ $d->kunjungan->pasien->nama_tersamar }}</div>
                        <span class="badge-pill badge-soft-gray" style="font-size: 0.68rem;">Prio: {{ $d->urutan_prioritas }}</span>
                    </td>
                    <td>
                        <div class="pes-block">
                            <div class="fw-bold text-dark" style="font-size: 0.84rem;">{{ $d->terminologi->nama_masalah ?? 'Problem' }}</div>
                            <div class="text-muted mt-1" style="font-size: 0.82rem; line-height: 1.5;">
                                <span class="pes-kw">berkaitan dengan</span> {{ $d->etiologi_penyebab }}<br>
                                <span class="pes-kw">ditandai dengan</span> {{ $d->signs_symptoms }}
                            </div>
                        </div>
                    </td>
                    <td>
                        @php $st = $d->status; @endphp
                        <span class="badge-pill
                            @if($st=='aktif') badge-soft-success
                            @elseif($st=='teratasi') badge-soft-gray
                            @else badge-soft-warning @endif">
                            {{ strtoupper($st) }}
                        </span>
                    </td>
                    <td class="text-end">
                        @if($d->divalidasi_pada)
                            <div class="text-success fw-bold" style="font-size: 0.82rem;"><i class="fas fa-check-double me-1"></i>Valid</div>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $d->spgk->nama_lengkap ?? '' }}</div>
                        @elseif(Auth::user()->peran === 'spgk')
                            @if($d->kunjungan?->dokumen_terkunci)
                                <span class="badge-pill badge-soft-danger"><i class="fas fa-lock me-1"></i>Terkunci</span>
                            @else
                                <form method="POST" action="{{ route('diagnosis.validasi', $d) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-ncpms btn-sm-ncpms">
                                        <i class="fas fa-check-circle me-1"></i>Validasi
                                    </button>
                                </form>
                            @endif
                        @else
                            <span class="badge-pill badge-soft-gray"><i class="fas fa-hourglass-half me-1"></i>Menunggu</span>
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
