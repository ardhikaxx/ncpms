@extends('layouts.app')
@section('title','Preskripsi Diet')
@section('breadcrumb','Intervensi / Preskripsi Diet')

@push('styles')
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .preskripsi-banner {
            background-color: var(--color-primary);
            border-radius: 20px;
            padding: 2.5rem 3rem;
            color: white;
            box-shadow: 0 10px 30px rgba(32, 166, 125, 0.2);
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .preskripsi-banner::before {
            content: ''; position: absolute; right: -5%; top: -20%; width: 300px; height: 300px;
            background-color: rgba(255,255,255,0.05); border-radius: 50%;
        }
        .preskripsi-banner::after {
            content: ''; position: absolute; right: 15%; bottom: -50%; width: 250px; height: 250px;
            background-color: rgba(255,255,255,0.05); border-radius: 50%;
        }
        .section-divider {
            display: flex; align-items: center; margin: 1.5rem 0 1rem; color: var(--color-primary-dark); font-weight: 700;
        }
        .section-divider::after {
            content: ''; flex: 1; height: 1px; background: rgba(0,0,0,0.08); margin-left: 1rem;
        }
        .input-group-text-premium {
            background: rgba(32, 166, 125, 0.05);
            border: 1px solid var(--color-border);
            border-right: none;
            color: var(--color-primary);
        }
        .form-control-ncpms.with-icon { border-left: none; }
        .form-control-ncpms.with-icon:focus { border-left: 1px solid var(--color-primary); }
        .badge-status { padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.75rem; }
    </style>
@endpush

@section('content')
<div class="preskripsi-banner animate__animated animate__fadeInDown">
    <div class="row align-items-center position-relative z-index-1">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-2" style="font-size: 2.2rem; letter-spacing: -0.02em;">
                Intervensi & Preskripsi Diet <span style="font-size: 1.8rem;">🍽️</span>
            </h1>
            <p class="fs-6 opacity-75 mb-0" style="font-family: var(--font-secondary);">Kalkulator kalori, distribusi makro, bentuk makanan, dan otorisasi SpGK.</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-4" data-aos="fade-right" data-aos-delay="100">
        <div class="ncpms-card h-100 mb-0 shadow-sm">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-info-circle"></i></span> Petunjuk Klinis</h2>
            <div class="alert bg-primary-subtle text-primary border-0 rounded-3 p-4">
                <h5 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Peringatan</h5>
                <p class="mb-2" style="font-size: 0.9rem;">Formulasi diet harus disetujui oleh Dokter Spesialis Gizi Klinik (SpGK) sebelum diterapkan kepada pasien.</p>
                <hr style="border-color: rgba(32, 166, 125, 0.2);">
                <ul class="mb-0 ps-3" style="font-size: 0.85rem;">
                    <li>Pastikan BB acuan sesuai dengan kondisi edema/obesitas pasien.</li>
                    <li>Faktor aktivitas & stres menentukan Total Kebutuhan Energi (TEE).</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-xl-8" data-aos="fade-left" data-aos-delay="200">
        <div class="ncpms-card mb-0 shadow-sm h-100">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-calculator"></i></span> Form Perancangan Diet</h2>
            <form method="POST" action="{{ route('intervensi.store') }}" class="row g-3">
                @csrf
                <div class="col-md-12">
                    <label class="form-label-ncpms">Pilih Kunjungan / Pasien <span class="required-mark">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text input-group-text-premium"><i class="fas fa-procedures"></i></span>
                        <select name="kunjungan_id" class="form-select form-control-ncpms with-icon" required>
                            <option value="">-- Pilih Pasien Terjadwal --</option>
                            @foreach($kunjungans as $k)
                            <option value="{{ $k->id }}">{{ $k->nomor_kunjungan }} - {{ $k->pasien->nama_lengkap }} ({{ $k->pasien->nomor_rekam_medis }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="section-divider"><i class="fas fa-fire-alt me-2"></i> Parameter Energi</div>
                
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
                
                <div class="col-md-4">
                    <label class="form-label-ncpms">Faktor Aktivitas</label>
                    <input type="number" step="0.1" min="1.0" max="2.5" name="faktor_aktivitas" class="form-control-ncpms" value="1.3">
                </div>
                <div class="col-md-4">
                    <label class="form-label-ncpms">Faktor Stres Klinis</label>
                    <input type="number" step="0.1" min="1.0" max="2.5" name="faktor_stres" class="form-control-ncpms" value="1.1">
                </div>

                <div class="section-divider"><i class="fas fa-chart-pie me-2"></i> Distribusi Makronutrien</div>

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

                <div class="section-divider"><i class="fas fa-utensils me-2"></i> Implementasi & Tujuan</div>

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

                <div class="col-12 mt-4 text-end">
                    <button class="btn-primary-ncpms px-4 py-2" style="font-size: 1rem;"><i class="fas fa-paper-plane me-2"></i> Rumuskan Preskripsi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="ncpms-card mb-0 shadow-sm" data-aos="fade-up" data-aos-delay="400">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <h2 class="card-title-custom border-0 mb-0 pb-0">
            <span class="card-title-icon" style="background-color: var(--color-primary); color: white;">
                <i class="fas fa-clipboard-list"></i>
            </span> 
            Log Preskripsi
        </h2>
    </div>
    <div class="table-responsive" style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(0,0,0,0.05);">
        <table class="table align-middle table-hover-premium mb-0">
            <thead style="background: rgba(0,0,0,0.02);">
                <tr>
                    <th class="ps-4">Kunjungan / Pasien</th>
                    <th>Target Kalori</th>
                    <th>Makronutrien</th>
                    <th>Bentuk & Jadwal</th>
                    <th>Status</th>
                    <th class="pe-4 text-end">Otorisasi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($preskripsis as $p)
                <tr>
                    <td class="ps-4">
                        <div class="text-mono fw-bold text-dark">{{ $p->kunjungan->nomor_kunjungan }}</div>
                        <div class="text-muted small">
                            <i class="fas fa-user-injured opacity-50 me-1"></i> {{ $p->kunjungan->pasien->nama_tersamar }}
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-primary" style="font-size: 1.1rem;">{{ number_format($p->total_kebutuhan_energi_kkal) }} <span class="text-muted" style="font-size: 0.75rem;">kkal</span></div>
                        <div class="text-muted small">TEE</div>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark border"><span class="text-primary">KH</span> {{ $p->gram_karbohidrat }}g</span>
                            <span class="badge bg-light text-dark border"><span class="text-danger">P</span> {{ $p->gram_protein }}g</span>
                            <span class="badge bg-light text-dark border"><span class="text-warning">L</span> {{ $p->gram_lemak }}g</span>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ strtoupper(str_replace('_',' ', $p->bentuk_makanan)) }}</div>
                        <div class="text-muted small">{{ $p->frekuensi_makan_utama }} Utama, {{ $p->frekuensi_selingan }} Selingan</div>
                    </td>
                    <td>
                        @php $st = $p->status; @endphp
                        <span class="badge-status border @if($st=='aktif') bg-success-subtle text-success border-success-subtle @elseif($st=='selesai') bg-secondary-subtle text-secondary border-secondary-subtle @else bg-warning-subtle text-warning border-warning-subtle @endif">
                            {{ strtoupper($st) }}
                        </span>
                    </td>
                    <td class="pe-4 text-end">
                        @if($p->disetujui_pada) 
                            <div class="text-success fw-bold" style="font-size: 0.85rem;"><i class="fas fa-check-circle"></i> Disetujui</div>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ $p->spgk->nama_lengkap ?? '' }}</div>
                        @elseif(Auth::user()->peran === 'spgk') 
                            <form method="POST" action="{{ route('intervensi.setujui', $p) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success fw-bold" style="border-radius: 8px;"><i class="fas fa-signature me-1"></i> ACC</button>
                            </form>
                        @else 
                            <span class="badge bg-light text-muted border"><i class="fas fa-hourglass-half"></i> Menunggu SpGK</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-file-signature fa-3x mb-3 opacity-25"></i><br>
                        Belum ada preskripsi diet yang dirumuskan.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $preskripsis->links('pagination::bootstrap-5') }}
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
