@extends('layouts.app')
@section('title','Preskripsi Diet')
@section('breadcrumb','Intervensi / Preskripsi Diet')

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

        .macro-pill {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px; border-radius: 6px; font-size: 0.78rem;
            background: #f8faf9; border: 1px solid var(--color-border);
        }

        .warning-box {
            background: rgba(18,130,96,0.06); border: 1px solid var(--color-primary-border);
            border-radius: 12px; padding: 1.25rem;
        }
        .warning-box h6 { font-weight: 700; color: var(--color-primary-dark); font-size: 0.9rem; }
        .warning-box p { font-size: 0.85rem; color: var(--color-text-secondary); margin-bottom: 0; }
        .warning-box ul { font-size: 0.82rem; color: var(--color-text-secondary); margin-bottom: 0; }
    </style>
@endpush

@section('content')

<div class="page-banner" data-aos="fade-down">
    <h1>Intervensi &amp; Preskripsi Diet <span style="font-size: 1.4rem;">🍽️</span></h1>
    <p>Kalkulator kalori, distribusi makro, bentuk makanan, dan otorisasi SpGK.</p>
</div>

<div class="row g-3 mb-4">
    {{-- Clinical Guide --}}
    <div class="col-xl-4" data-aos="fade-right" data-aos-delay="80">
        <div class="ncpms-card h-100 mb-0">
            <div class="card-title-custom">
                <span class="card-title-icon"><i class="fas fa-info-circle"></i></span>
                Petunjuk Klinis
            </div>
            <div class="warning-box mb-3">
                <h6><i class="fas fa-exclamation-triangle text-warning me-2"></i>Peringatan</h6>
                <p class="mt-2">Formulasi diet harus disetujui oleh Dokter SpGK sebelum diterapkan kepada pasien.</p>
            </div>
            <div class="p-3 rounded" style="background: #f8faf9; border: 1px solid var(--color-border);">
                <div class="fw-bold mb-2" style="font-size: 0.82rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.04em;">Catatan Penting</div>
                <ul class="ps-3 mb-0" style="font-size: 0.83rem; color: var(--color-text-secondary); line-height: 1.8;">
                    <li>Pastikan BB acuan sesuai dengan kondisi edema/obesitas pasien.</li>
                    <li>Faktor aktivitas &amp; stres menentukan Total Kebutuhan Energi (TEE).</li>
                    <li>Distribusi makro: KH + Protein + Lemak = 100%.</li>
                </ul>
            </div>
            <div class="mt-3 p-3 rounded" style="background: var(--color-primary-subtle); border: 1px dashed var(--color-primary-border);">
                <div class="fw-bold mb-1" style="font-size: 0.82rem; color: var(--color-primary);">Formula yang Tersedia</div>
                <div class="d-flex flex-wrap gap-1">
                    @foreach(['Harris-Benedict','Mifflin St Jeor','WHO','Konsensus DM','Konsensus CKD'] as $f)
                    <span class="badge" style="background: white; color: var(--color-primary-dark); border: 1px solid var(--color-primary-border); font-size: 0.72rem; font-weight: 600;">{{ $f }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Diet Form --}}
    <div class="col-xl-8" data-aos="fade-left" data-aos-delay="100">
        <div class="ncpms-card mb-0 h-100">
            <div class="card-title-custom">
                <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-calculator"></i></span>
                Form Perancangan Diet
            </div>
            <form method="POST" action="{{ route('intervensi.store') }}" class="row g-3">
                @csrf
                <div class="col-12">
                    <label class="form-label-ncpms">Pilih Kunjungan / Pasien <span class="required-mark">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" style="background: var(--color-primary-subtle); border-color: var(--color-border); color: var(--color-primary);">
                            <i class="fas fa-procedures"></i>
                        </span>
                        <select name="kunjungan_id" class="form-select form-control-ncpms" style="border-left: none;" required>
                            <option value="">-- Pilih Pasien Terjadwal --</option>
                            @foreach($kunjungans as $k)
                            <option value="{{ $k->id }}">{{ $k->nomor_kunjungan }} - {{ $k->pasien->nama_lengkap }} ({{ $k->pasien->nomor_rekam_medis }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12"><div class="section-divider"><i class="fas fa-fire-alt"></i> Parameter Energi</div></div>

                <div class="col-md-4">
                    <label class="form-label-ncpms">Formula Basal</label>
                    <select name="formula_basal" class="form-select form-control-ncpms">
                        <option value="harris_benedict">Harris-Benedict</option>
                        <option value="mifflin_st_jeor">Mifflin St Jeor</option>
                        <option value="who">WHO</option>
                        <option value="konsensus_dm">Konsensus DM</option>
                        <option value="konsensus_ckd">Konsensus CKD</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label-ncpms">BB Acuan</label>
                    <select name="berat_badan_acuan" class="form-select form-control-ncpms">
                        <option value="aktual">Aktual</option>
                        <option value="ideal">Ideal</option>
                        <option value="adjusted">Adjusted</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label-ncpms">Energi Basal (kkal) <span class="required-mark">*</span></label>
                    <input type="number" step="0.1" name="kebutuhan_energi_basal_kkal" class="form-control-ncpms" value="1400" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-ncpms">Faktor Aktivitas</label>
                    <input type="number" step="0.1" min="1.0" max="2.5" name="faktor_aktivitas" class="form-control-ncpms" value="1.3">
                </div>
                <div class="col-md-6">
                    <label class="form-label-ncpms">Faktor Stres Klinis</label>
                    <input type="number" step="0.1" min="1.0" max="2.5" name="faktor_stres" class="form-control-ncpms" value="1.1">
                </div>

                <div class="col-12"><div class="section-divider"><i class="fas fa-chart-pie"></i> Distribusi Makronutrien</div></div>

                <div class="col-md-3">
                    <label class="form-label-ncpms">Karbohidrat (%)</label>
                    <input type="number" name="persen_karbohidrat" class="form-control-ncpms" value="50">
                </div>
                <div class="col-md-3">
                    <label class="form-label-ncpms">Protein (%)</label>
                    <input type="number" name="persen_protein" class="form-control-ncpms" value="20">
                </div>
                <div class="col-md-3">
                    <label class="form-label-ncpms">Lemak (%)</label>
                    <input type="number" name="persen_lemak" class="form-control-ncpms" value="30">
                </div>
                <div class="col-md-3">
                    <label class="form-label-ncpms">Serat (gram)</label>
                    <input type="number" name="gram_serat" class="form-control-ncpms" value="25">
                </div>

                <div class="col-12"><div class="section-divider"><i class="fas fa-utensils"></i> Implementasi &amp; Tujuan</div></div>

                <div class="col-md-4">
                    <label class="form-label-ncpms">Bentuk Makanan</label>
                    <select name="bentuk_makanan" class="form-select form-control-ncpms">
                        <option value="biasa">Biasa</option>
                        <option value="lunak">Lunak</option>
                        <option value="saring">Saring</option>
                        <option value="cair_penuh">Cair Penuh</option>
                        <option value="cair_jernih">Cair Jernih</option>
                        <option value="formula_medis">Formula Medis</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label-ncpms">Frekuensi Utama</label>
                    <input type="number" name="frekuensi_makan_utama" class="form-control-ncpms" value="3">
                </div>
                <div class="col-md-2">
                    <label class="form-label-ncpms">Selingan</label>
                    <input type="number" name="frekuensi_selingan" class="form-control-ncpms" value="2">
                </div>
                <div class="col-md-2">
                    <label class="form-label-ncpms">Tgl Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control-ncpms" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label-ncpms">Durasi (Hari)</label>
                    <input type="number" name="durasi_hari" class="form-control-ncpms" value="14">
                </div>
                <div class="col-md-6">
                    <label class="form-label-ncpms">Tujuan Terapi <span class="required-mark">*</span></label>
                    <textarea name="tujuan_terapi" class="form-control-ncpms" rows="2" required>Memenuhi kebutuhan energi dan mengendalikan parameter metabolik pasien.</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label-ncpms">Catatan Klinis (Opsional)</label>
                    <textarea name="catatan_klinis" class="form-control-ncpms" rows="2" placeholder="Instruksi tambahan..."></textarea>
                </div>

                <div class="col-12 text-end mt-2">
                    <button class="btn fw-bold px-4 py-2" style="background: var(--color-primary); color: white; border: none; border-radius: 10px; font-size: 0.95rem;">
                        <i class="fas fa-paper-plane me-2"></i>Rumuskan Preskripsi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Prescription Log --}}
<div class="ncpms-card mb-0" data-aos="fade-up" data-aos-delay="200">
    <div class="card-title-custom border-bottom pb-3 mb-3">
        <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-clipboard-list"></i></span>
        Log Preskripsi Diet
    </div>
    <div class="table-responsive" style="border-radius: 10px; border: 1px solid var(--color-border);">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="padding-left: 16px;">Kunjungan / Pasien</th>
                    <th>Target Kalori</th>
                    <th>Makronutrien</th>
                    <th>Bentuk &amp; Jadwal</th>
                    <th>Status</th>
                    <th class="text-end" style="padding-right: 16px;">Otorisasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($preskripsis as $p)
                <tr>
                    <td style="padding-left: 16px;">
                        <div class="fw-bold" style="font-family: var(--font-mono); font-size: 0.82rem; color: var(--color-primary);">{{ $p->kunjungan->nomor_kunjungan }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;"><i class="fas fa-user-injured opacity-50 me-1"></i>{{ $p->kunjungan->pasien->nama_tersamar }}</div>
                    </td>
                    <td>
                        <div class="fw-bold" style="font-size: 1.05rem; color: var(--color-primary);">{{ number_format($p->total_kebutuhan_energi_kkal) }} <span class="text-muted" style="font-size: 0.72rem;">kkal</span></div>
                        <div class="text-muted" style="font-size: 0.72rem;">TEE</div>
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <span class="macro-pill"><span style="color: var(--color-primary); font-weight: 700;">KH</span> {{ $p->gram_karbohidrat }}g</span>
                            <span class="macro-pill"><span style="color: #e03131; font-weight: 700;">P</span> {{ $p->gram_protein }}g</span>
                            <span class="macro-pill"><span style="color: #f59f00; font-weight: 700;">L</span> {{ $p->gram_lemak }}g</span>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark" style="font-size: 0.82rem;">{{ strtoupper(str_replace('_',' ', $p->bentuk_makanan)) }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ $p->frekuensi_makan_utama }}x Utama, {{ $p->frekuensi_selingan }}x Selingan</div>
                    </td>
                    <td>
                        @php $st = $p->status; @endphp
                        <span class="badge border" style="padding: 5px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700;
                            @if($st=='aktif') background: #d1fae5; color: #065f46; border-color: #a7f3d0 !important;
                            @elseif($st=='selesai') background: #f3f4f6; color: #4b5563; border-color: #d1d5db !important;
                            @else background: #fef3c7; color: #92400e; border-color: #fde68a !important; @endif">
                            {{ strtoupper($st) }}
                        </span>
                    </td>
                    <td class="text-end" style="padding-right: 16px;">
                        @if($p->disetujui_pada)
                            <div class="text-success fw-bold" style="font-size: 0.82rem;"><i class="fas fa-check-circle me-1"></i>Disetujui</div>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $p->spgk->nama_lengkap ?? '' }}</div>
                        @elseif(Auth::user()->peran === 'spgk')
                            <form method="POST" action="{{ route('intervensi.setujui', $p) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm fw-bold px-3" style="background: #059669; color: white; border-radius: 8px; border: none; font-size: 0.8rem;">
                                    <i class="fas fa-signature me-1"></i>ACC
                                </button>
                            </form>
                        @else
                            <span class="badge bg-light text-muted border" style="font-size: 0.75rem;"><i class="fas fa-hourglass-half me-1"></i>Menunggu SpGK</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-file-signature fa-2x mb-2 d-block opacity-25"></i>
                        Belum ada preskripsi diet yang dirumuskan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $preskripsis->links('pagination::bootstrap-5') }}</div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ duration: 700, once: true, offset: 40 });</script>
@endpush
