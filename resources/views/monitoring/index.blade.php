@extends('layouts.app')
@section('title','Monitoring & Evaluasi')
@section('breadcrumb','Monitoring & Evaluasi')

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

        .data-table thead th {
            background: #f8faf9; font-size: 0.73rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--color-text-muted); border-bottom: 1px solid var(--color-border);
            padding: 10px 14px;
        }
        .data-table td { padding: 11px 14px; vertical-align: middle; border-bottom: 1px solid var(--color-divider); font-size: 0.88rem; }
        .data-table tbody tr:hover { background: var(--color-primary-subtle); }

        .kepatuhan-patuh { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .kepatuhan-cukup_patuh { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .kepatuhan-tidak_patuh { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .kepatuhan-default { background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db; }
        .kepatuhan-badge { padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 0.72rem; text-transform: uppercase; }

        .guide-box {
            background: rgba(18,130,96,0.06); border: 1px solid var(--color-primary-border);
            border-radius: 12px; padding: 1.25rem;
        }
        .guide-box h6 { font-weight: 700; color: var(--color-primary-dark); font-size: 0.9rem; }
    </style>
@endpush

@section('content')

<div class="page-banner" data-aos="fade-down">
    <h1>Monitoring &amp; Evaluasi <span style="font-size: 1.4rem;">📈</span></h1>
    <p>Pemantauan luaran klinis, kepatuhan diet pasien, dan tindak lanjut PAGT.</p>
</div>

<div class="row g-3 mb-4">
    {{-- Instruction Card --}}
    <div class="col-xl-4" data-aos="fade-right" data-aos-delay="80">
        <div class="ncpms-card h-100 mb-0">
            <div class="card-title-custom">
                <span class="card-title-icon" style="background: rgba(18,130,96,0.1); color: var(--color-primary);"><i class="fas fa-tasks"></i></span>
                Instruksi Evaluasi
            </div>
            <div class="guide-box mb-3">
                <h6><i class="fas fa-stethoscope me-2" style="color: var(--color-primary);"></i>Fokus Evaluasi</h6>
                <p class="mt-2 mb-0" style="font-size: 0.85rem; color: var(--color-text-secondary);">Evaluasi kemajuan diet berdasarkan diagnosis awal dan preskripsi yang diberikan.</p>
            </div>
            <div class="p-3 rounded" style="background: #f8faf9; border: 1px solid var(--color-border);">
                <div class="fw-bold mb-2" style="font-size: 0.82rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.04em;">Panduan</div>
                <ul class="ps-3 mb-0" style="font-size: 0.83rem; color: var(--color-text-secondary); line-height: 1.9;">
                    <li>Periksa persen sisa makanan (&lt; 20% dianggap baik).</li>
                    <li>Evaluasi antropometri &amp; biokimia wajib diisi jika ada perubahan signifikan.</li>
                    <li>Jadwalkan rujukan jika target asupan gagal tercapai.</li>
                </ul>
            </div>
            <div class="mt-3">
                <div class="fw-bold mb-2" style="font-size: 0.82rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.04em;">Skala Kepatuhan</div>
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center gap-2">
                        <span class="kepatuhan-badge kepatuhan-patuh">Patuh</span>
                        <span style="font-size: 0.78rem; color: var(--color-text-muted);">Asupan ≥ 80% target</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="kepatuhan-badge kepatuhan-cukup_patuh">Cukup Patuh</span>
                        <span style="font-size: 0.78rem; color: var(--color-text-muted);">Asupan 50–79%</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="kepatuhan-badge kepatuhan-tidak_patuh">Tidak Patuh</span>
                        <span style="font-size: 0.78rem; color: var(--color-text-muted);">Asupan &lt; 50%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Monitoring Form --}}
    <div class="col-xl-8" data-aos="fade-left" data-aos-delay="100">
        <div class="ncpms-card mb-0 h-100">
            <div class="card-title-custom">
                <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-file-medical-alt"></i></span>
                Form Rekam Monitoring
            </div>
            <form method="POST" action="{{ route('monitoring.store') }}" class="row g-3">
                @csrf
                <div class="col-12">
                    <label class="form-label-ncpms">Pilih Kunjungan / Pasien <span class="required-mark">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" style="background: var(--color-primary-subtle); border-color: var(--color-border); color: var(--color-primary);">
                            <i class="fas fa-user-check"></i>
                        </span>
                        <select name="kunjungan_id" class="form-select form-control-ncpms" style="border-left: none;" required>
                            <option value="">-- Pilih Pasien Terjadwal --</option>
                            @foreach($kunjungans as $k)
                            <option value="{{ $k->id }}">{{ $k->nomor_kunjungan }} - {{ $k->pasien->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12"><div class="section-divider"><i class="fas fa-clipboard-check"></i> Evaluasi Kepatuhan &amp; Parameter Utama</div></div>

                <div class="col-md-5">
                    <label class="form-label-ncpms">Parameter Dipantau <span class="required-mark">*</span></label>
                    <input name="parameter_dipantau" class="form-control-ncpms"
                        value="berat badan, HbA1c, asupan energi, kepatuhan diet"
                        placeholder="Pisahkan dengan koma" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label-ncpms">Kepatuhan Diet</label>
                    <select name="evaluasi_kepatuhan_diet" class="form-select form-control-ncpms">
                        <option value="patuh">Patuh</option>
                        <option value="cukup_patuh">Cukup Patuh</option>
                        <option value="tidak_patuh">Tidak Patuh</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-ncpms">Sisa Makanan (%)</label>
                    <input type="number" step="0.1" name="persen_sisa_makanan" class="form-control-ncpms" value="15">
                </div>

                <div class="col-12"><div class="section-divider"><i class="fas fa-vials"></i> Evaluasi Komponen PAGT (A-B-C-D)</div></div>

                <div class="col-md-4">
                    <label class="form-label-ncpms">Evaluasi Antropometri</label>
                    <textarea name="evaluasi_anthropometri" class="form-control-ncpms" rows="2" placeholder="Cth: BB naik 0.5kg..."></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label-ncpms">Evaluasi Biokimia</label>
                    <textarea name="evaluasi_biokimia" class="form-control-ncpms" rows="2" placeholder="Cth: GDS turun 20 poin..."></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label-ncpms">Evaluasi Asupan</label>
                    <textarea name="evaluasi_asupan" class="form-control-ncpms" rows="2" placeholder="Cth: Asupan kalori baru 80%..."></textarea>
                </div>

                <div class="col-12"><div class="section-divider"><i class="fas fa-route"></i> Kesimpulan &amp; Rencana Tindak Lanjut</div></div>

                <div class="col-md-6">
                    <label class="form-label-ncpms">Kesimpulan Evaluasi Klinis</label>
                    <textarea name="kesimpulan" class="form-control-ncpms" rows="2" placeholder="Cth: Pasien merespon diet dengan baik..."></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label-ncpms">Rekomendasi Lanjutan</label>
                    <textarea name="rekomendasi_lanjutan" class="form-control-ncpms" rows="2" placeholder="Cth: Tingkatkan kalori bertahap..."></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label-ncpms">Rencana Kontrol Berikutnya</label>
                    <input type="date" name="rencana_kunjungan_berikutnya" class="form-control-ncpms" value="{{ now()->addWeeks(2)->format('Y-m-d') }}">
                </div>
                <div class="col-md-8">
                    <label class="form-label-ncpms">Tujuan Rujukan (Bila Perlu)</label>
                    <div class="input-group">
                        <div class="input-group-text bg-light border-end-0">
                            <input class="form-check-input mt-0" type="checkbox" name="perlu_rujukan" value="1" id="perlu_rujukan">
                        </div>
                        <input name="tujuan_rujukan" class="form-control form-control-ncpms border-start-0 ps-2" placeholder="Cth: Spesialis Penyakit Dalam / SpGK...">
                    </div>
                </div>

                <div class="col-12 text-end mt-2">
                    <button class="btn fw-bold px-4 py-2" style="background: var(--color-primary); color: white; border: none; border-radius: 10px; font-size: 0.95rem;">
                        <i class="fas fa-save me-2"></i>Rekam Data Monitoring
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Monitoring History --}}
<div class="ncpms-card mb-0" data-aos="fade-up" data-aos-delay="200">
    <div class="card-title-custom border-bottom pb-3 mb-3">
        <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-table"></i></span>
        Riwayat Evaluasi Monitoring
    </div>
    <div class="table-responsive" style="border-radius: 10px; border: 1px solid var(--color-border);">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="padding-left: 16px;">Kunjungan</th>
                    <th>Pasien</th>
                    <th>Parameter Dipantau</th>
                    <th>Kepatuhan</th>
                    <th>Rencana Kontrol</th>
                    <th class="text-end" style="padding-right: 16px;">Pelaksana</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monitorings as $m)
                <tr>
                    <td style="padding-left: 16px;">
                        <div class="fw-bold" style="font-family: var(--font-mono); font-size: 0.82rem; color: var(--color-primary);">{{ $m->kunjungan->nomor_kunjungan }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;"><i class="far fa-clock me-1"></i>{{ $m->created_at?->format('d/m/Y') ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $m->kunjungan->pasien->nama_tersamar }}</div>
                    </td>
                    <td>
                        <div class="text-muted" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.82rem;" title="{{ implode(', ', $m->parameter_dipantau ?? []) }}">
                            <i class="fas fa-chart-line opacity-50 me-1"></i>{{ implode(', ', $m->parameter_dipantau ?? []) }}
                        </div>
                    </td>
                    <td>
                        @php $kep = $m->evaluasi_kepatuhan_diet ?? ''; @endphp
                        <span class="kepatuhan-badge kepatuhan-{{ $kep ?: 'default' }}">
                            {{ str_replace('_',' ', $kep ?: 'Belum dinilai') }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-bold" style="color: var(--color-primary); font-size: 0.85rem;">
                            <i class="far fa-calendar-alt me-1"></i>{{ $m->rencana_kunjungan_berikutnya?->format('d M Y') ?? '-' }}
                        </div>
                    </td>
                    <td class="text-end" style="padding-right: 16px;">
                        <div class="fw-bold text-dark" style="font-size: 0.82rem;">{{ $m->pelaksana->nama_lengkap ?? '-' }}</div>
                        <div class="text-muted" style="font-size: 0.72rem;">Dietisien</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-heart-pulse fa-2x mb-2 d-block opacity-25"></i>
                        Belum ada riwayat monitoring klinis.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $monitorings->links('pagination::bootstrap-5') }}</div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ duration: 700, once: true, offset: 40 });</script>
@endpush
