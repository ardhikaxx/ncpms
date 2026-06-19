@extends('layouts.app')
@section('title','Monitoring & Evaluasi')
@section('breadcrumb','Monitoring & Evaluasi')

@push('styles')
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .monitoring-banner {
            background-color: var(--color-primary);
            border-radius: 20px;
            padding: 2.5rem 3rem;
            color: white;
            box-shadow: 0 10px 30px rgba(42, 82, 152, 0.2);
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .monitoring-banner::before {
            content: ''; position: absolute; right: -5%; top: -20%; width: 300px; height: 300px;
            background-color: rgba(255,255,255,0.05); border-radius: 50%;
        }
        .monitoring-banner::after {
            content: ''; position: absolute; right: 15%; bottom: -50%; width: 250px; height: 250px;
            background-color: rgba(255,255,255,0.05); border-radius: 50%;
        }
        .section-divider {
            display: flex; align-items: center; margin: 1.5rem 0 1rem; color: var(--color-primary); font-weight: 700;
        }
        .section-divider::after {
            content: ''; flex: 1; height: 1px; background: rgba(0,0,0,0.08); margin-left: 1rem;
        }
        .input-group-text-premium {
            background: rgba(42, 82, 152, 0.05);
            border: 1px solid var(--color-border);
            border-right: none;
            color: var(--color-primary);
        }
        .form-control-ncpms.with-icon { border-left: none; }
        .form-control-ncpms.with-icon:focus { border-left: 4px solid var(--color-primary); }
        .badge-kepatuhan { padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; }
        .kepatuhan-patuh { background: #d3f9d8; color: var(--color-primary); border: 1px solid #b2f2bb; }
        .kepatuhan-cukup_patuh { background: #fff3cd; color: var(--color-primary); border: 1px solid #ffec99; }
        .kepatuhan-tidak_patuh { background: #ffe3e3; color: var(--color-primary); border: 1px solid #ffc9c9; }
        .kepatuhan-default { background: #f8f9fa; color: var(--color-primary); border: 1px solid #e9ecef; }
    </style>
@endpush

@section('content')

<!-- Welcome Banner -->
<div class="monitoring-banner animate__animated animate__fadeInDown">
    <div class="row align-items-center position-relative z-index-1">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-2" style="font-size: 2.2rem; letter-spacing: -0.02em;">
                Monitoring & Evaluasi <span style="font-size: 1.8rem;">📈</span>
            </h1>
            <p class="fs-6 opacity-75 mb-0" style="font-family: var(--font-secondary);">Pemantauan luaran klinis, kepatuhan diet pasien, dan tindak lanjut PAGT.</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-4" data-aos="fade-right" data-aos-delay="100">
        <div class="ncpms-card h-100 mb-0 shadow-sm">
            <h2 class="card-title-custom"><span class="card-title-icon" style="color: var(--color-primary); background: rgba(42,82,152,0.1);"><i class="fas fa-tasks"></i></span> Instruksi</h2>
            <div class="alert bg-primary-subtle text-primary border-0 rounded-3 p-4">
                <h5 class="fw-bold"><i class="fas fa-stethoscope me-2"></i> Fokus Evaluasi</h5>
                <p class="mb-2" style="font-size: 0.9rem;">Evaluasi kemajuan diet berdasarkan diagnosis awal dan preskripsi yang diberikan.</p>
                <hr style="border-color: rgba(42, 82, 152, 0.2);">
                <ul class="mb-0 ps-3" style="font-size: 0.85rem;">
                    <li>Periksa persen sisa makanan (< 20% dianggap baik).</li>
                    <li>Evaluasi antropometri & biokimia wajib diisi jika ada perubahan signifikan.</li>
                    <li>Jadwalkan rujukan jika target asupan gagal tercapai.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-xl-8" data-aos="fade-left" data-aos-delay="200">
        <div class="ncpms-card mb-0 shadow-sm h-100">
            <h2 class="card-title-custom"><span class="card-title-icon" style="color: var(--color-primary); background: rgba(42,82,152,0.1);"><i class="fas fa-file-medical-alt"></i></span> Form Rekam Monitoring</h2>
            <form method="POST" action="{{ route('monitoring.store') }}" class="row g-3">
                @csrf
                
                <div class="col-md-12">
                    <label class="form-label-ncpms">Pilih Kunjungan / Pasien <span class="required-mark">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text input-group-text-premium"><i class="fas fa-user-check"></i></span>
                        <select name="kunjungan_id" class="form-select form-control-ncpms with-icon" required>
                            <option value="">-- Pilih Pasien Terjadwal --</option>
                            @foreach($kunjungans as $k)
                            <option value="{{ $k->id }}">{{ $k->nomor_kunjungan }} - {{ $k->pasien->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="section-divider"><i class="fas fa-clipboard-check me-2"></i> Evaluasi Kepatuhan & Parameter Utama</div>
                
                <div class="col-md-5">
                    <label class="form-label-ncpms">Parameter Dipantau <span class="required-mark">*</span></label>
                    <input name="parameter_dipantau" class="form-control-ncpms" value="berat badan, HbA1c, asupan energi, kepatuhan diet" placeholder="Pisahkan dengan koma" required>
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
                    <input type="number" step="0.1" name="persen_sisa_makanan" class="form-control-ncpms" value="15" placeholder="Contoh: 15">
                </div>

                <div class="section-divider"><i class="fas fa-vials me-2"></i> Evaluasi Komponen PAGT (A-B-C-D)</div>

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

                <div class="section-divider"><i class="fas fa-route me-2"></i> Kesimpulan & Rencana Tindak Lanjut</div>

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
                            <input class="form-check-input mt-0" type="checkbox" name="perlu_rujukan" value="1" id="perlu_rujukan" aria-label="Perlu rujukan">
                        </div>
                        <input name="tujuan_rujukan" class="form-control form-control-ncpms border-start-0 ps-2" placeholder="Cth: Spesialis Penyakit Dalam / SpGK...">
                    </div>
                </div>

                <div class="col-12 mt-4 text-end">
                    <button class="btn btn-primary-ncpms px-4 py-2" style="font-size: 1rem;"><i class="fas fa-save me-2"></i> Rekam Data Monitoring</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="ncpms-card mb-0 shadow-sm" data-aos="fade-up" data-aos-delay="400">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <h2 class="card-title-custom border-0 mb-0 pb-0">
            <span class="card-title-icon" style="background-color: var(--color-primary); color: white;">
                <i class="fas fa-table"></i>
            </span> 
            Riwayat Evaluasi
        </h2>
    </div>
    
    <div class="table-responsive" style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(0,0,0,0.05);">
        <table class="table align-middle table-hover-premium mb-0">
            <thead style="background: rgba(0,0,0,0.02);">
                <tr>
                    <th class="ps-4">Kunjungan</th>
                    <th>Pasien</th>
                    <th>Parameter Dipantau</th>
                    <th>Kepatuhan</th>
                    <th>Rencana Kontrol</th>
                    <th class="pe-4 text-end">Pelaksana</th>
                </tr>
            </thead>
            <tbody>
            @forelse($monitorings as $m)
                <tr>
                    <td class="ps-4">
                        <div class="text-mono fw-bold text-dark">{{ $m->kunjungan->nomor_kunjungan }}</div>
                        <div class="text-muted small"><i class="far fa-clock"></i> {{ $m->created_at?->format('d/m/Y') ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $m->kunjungan->pasien->nama_tersamar }}</div>
                    </td>
                    <td>
                        <div class="text-muted small" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ implode(', ', $m->parameter_dipantau ?? []) }}">
                            <i class="fas fa-chart-line opacity-50 me-1"></i> {{ implode(', ', $m->parameter_dipantau ?? []) }}
                        </div>
                    </td>
                    <td>
                        @php $kep = $m->evaluasi_kepatuhan_diet ?? ''; @endphp
                        <span class="badge-kepatuhan kepatuhan-{{ $kep ? $kep : 'default' }}">
                            {{ str_replace('_',' ', $kep ?: 'Belum dinilai') }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-bold text-primary">
                            <i class="far fa-calendar-alt me-1"></i> {{ $m->rencana_kunjungan_berikutnya?->format('d M Y') ?? '-' }}
                        </div>
                    </td>
                    <td class="pe-4 text-end">
                        <div class="text-dark fw-bold" style="font-size: 0.85rem;">{{ $m->pelaksana->nama_lengkap ?? '-' }}</div>
                        <div class="text-muted small" style="font-size: 0.7rem;">Dietisien</div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-heart-pulse fa-3x mb-3 opacity-25"></i><br>
                        Belum ada riwayat monitoring klinis.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $monitorings->links('pagination::bootstrap-5') }}
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
