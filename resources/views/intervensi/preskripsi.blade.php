@extends('layouts.app')
@section('title','Preskripsi Diet')
@section('breadcrumb','Intervensi / Preskripsi Diet')

@section('content')

<div class="page-header mb-4">
    <div>
        <h1 class="page-title"><i class="fas fa-utensils me-2" style="color: var(--color-primary);"></i>Intervensi &amp; Preskripsi Diet</h1>
        <p class="page-subtitle">Kalkulator kalori, distribusi makro, bentuk makanan, dan otorisasi SpGK.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Clinical Guide --}}
    <div class="col-xl-4">
        <div class="ncpms-card h-100 mb-0">
            <div class="card-title-custom">
                <span class="card-title-icon"><i class="fas fa-info-circle"></i></span>
                Petunjuk Klinis
            </div>

            <div class="warning-clinical">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <div class="fw-bold">Peringatan</div>
                    <div style="font-size: 0.82rem;">Formulasi diet harus disetujui oleh Dokter SpGK sebelum diterapkan kepada pasien.</div>
                </div>
            </div>

            <div class="section-divider"><i class="fas fa-clipboard-check"></i> Catatan Penting</div>
            <ul class="ps-3 mb-0" style="font-size: 0.83rem; color: var(--color-text-secondary); line-height: 1.8;">
                <li>Pastikan BB acuan sesuai dengan kondisi edema/obesitas pasien.</li>
                <li>Faktor aktivitas &amp; stres menentukan Total Kebutuhan Energi (TEE).</li>
                <li>Distribusi makro: KH + Protein + Lemak = 100%.</li>
            </ul>

            <div class="section-divider mt-3"><i class="fas fa-flask"></i> Formula yang Tersedia</div>
            <div class="d-flex flex-wrap gap-1">
                @foreach(['Harris-Benedict','Mifflin St Jeor','WHO','Konsensus DM','Konsensus CKD'] as $f)
                <span class="badge-pill badge-soft-primary">{{ $f }}</span>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Diet Form --}}
    <div class="col-xl-8">
        <div class="ncpms-card mb-0 h-100">
            <div class="card-title-custom">
                <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-calculator"></i></span>
                Form Perancangan Diet
            </div>
            <form method="POST" action="{{ route('intervensi.store') }}" class="row g-3">
                @csrf
                <div class="col-12">
                    <label class="form-label-ncpms">Pilih Kunjungan / Pasien <span class="required-mark">*</span></label>
                    <select name="kunjungan_id" class="form-select form-control-ncpms" required id="kunjungan_select">
                        <option value="">-- Pilih Pasien Terjadwal --</option>
                        @foreach($kunjungans as $k)
                        <option value="{{ $k->id }}" data-obat="{{ $k->obat_sedang_dikonsumsi }}">{{ $k->nomor_kunjungan }} - {{ $k->pasien->nama_lengkap }} ({{ $k->pasien->nomor_rekam_medis }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-12 d-none" id="obat_warning_container">
                    <div class="alert border-0" style="background: #fff3cd; color: #856404; font-size: 0.85rem; padding: 10px; border-radius: 6px;">
                        <i class="fas fa-pills me-2"></i><strong>Food-Drug Interaction Alert:</strong> Pasien sedang mengonsumsi: 
                        <span id="obat_text" class="fw-bold"></span>. 
                        <br><span class="text-muted"><small>Dietisien wajib mempertimbangkan interaksi zat gizi dengan obat-obatan ini saat merumuskan bentuk makanan dan pantangan.</small></span>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-between align-items-center">
                    <div class="section-divider flex-grow-1 me-3"><i class="fas fa-fire-alt"></i> Parameter Energi</div>
                    <button type="button" class="btn-sm-ncpms btn-ncpms-outline" data-bs-toggle="modal" data-bs-target="#kalkulatorModal">
                        <i class="fas fa-calculator me-1"></i> Kalkulator Energi PAGT
                    </button>
                </div>

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
                    <button class="btn btn-ncpms">
                        <i class="fas fa-paper-plane me-1"></i>Rumuskan Preskripsi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Prescription Log --}}
<div class="ncpms-card mb-0">
    <div class="card-title-custom">
        <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-clipboard-list"></i></span>
        Log Preskripsi Diet
    </div>
    <div class="table-responsive" style="border-radius: 8px; border: 1px solid var(--color-border);">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th>Kunjungan / Pasien</th>
                    <th>Target Kalori</th>
                    <th>Makronutrien</th>
                    <th>Bentuk &amp; Jadwal</th>
                    <th>Status</th>
                    <th class="text-end">Otorisasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($preskripsis as $p)
                <tr>
                    <td>
                        <div class="fw-bold" style="font-family: var(--font-mono); font-size: 0.82rem; color: var(--color-primary);">{{ $p->kunjungan->nomor_kunjungan }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ $p->kunjungan->pasien->nama_tersamar }}</div>
                    </td>
                    <td>
                        <div class="fw-bold" style="font-size: 1.05rem; color: var(--color-primary);">{{ number_format($p->total_kebutuhan_energi_kkal) }} <span class="text-muted" style="font-size: 0.72rem;">kkal</span></div>
                        <div class="text-muted" style="font-size: 0.72rem;">TEE</div>
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <span class="badge-pill badge-soft-primary"><strong>KH</strong> {{ $p->gram_karbohidrat }}g</span>
                            <span class="badge-pill badge-soft-danger"><strong>P</strong> {{ $p->gram_protein }}g</span>
                            <span class="badge-pill badge-soft-warning"><strong>L</strong> {{ $p->gram_lemak }}g</span>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark" style="font-size: 0.82rem;">{{ strtoupper(str_replace('_',' ', $p->bentuk_makanan)) }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ $p->frekuensi_makan_utama }}x Utama, {{ $p->frekuensi_selingan }}x Selingan</div>
                    </td>
                    <td>
                        @php $st = $p->status; @endphp
                        <span class="badge-pill
                            @if($st=='aktif') badge-soft-success
                            @elseif($st=='selesai') badge-soft-gray
                            @else badge-soft-warning @endif">
                            {{ strtoupper($st) }}
                        </span>
                    </td>
                    <td class="text-end">
                        @if($p->disetujui_pada)
                            <div class="text-success fw-bold" style="font-size: 0.82rem;"><i class="fas fa-check-circle me-1"></i>Disetujui</div>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $p->spgk->nama_lengkap ?? '' }}</div>
                        @elseif(Auth::user()->peran === 'spgk')
                            <form method="POST" action="{{ route('intervensi.setujui', $p) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-ncpms btn-sm-ncpms">
                                    <i class="fas fa-signature me-1"></i>ACC
                                </button>
                            </form>
                        @else
                            <span class="badge-pill badge-soft-gray"><i class="fas fa-hourglass-half me-1"></i>Menunggu SpGK</span>
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
<!-- Modal Kalkulator Energi Dinamis -->
<div class="modal fade" id="kalkulatorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background: var(--color-primary);">
                <h5 class="modal-title fs-6"><i class="fas fa-calculator me-2"></i>Kalkulator Energi Dinamis PAGT</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label-ncpms">Jenis Kelamin</label>
                        <select id="calc_jk" class="form-select form-control-ncpms">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label-ncpms">Usia (Tahun)</label>
                        <input type="number" id="calc_usia" class="form-control-ncpms" value="30">
                    </div>
                    <div class="col-6">
                        <label class="form-label-ncpms">Berat Badan (kg)</label>
                        <input type="number" step="0.1" id="calc_bb" class="form-control-ncpms" value="60">
                    </div>
                    <div class="col-6">
                        <label class="form-label-ncpms">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" id="calc_tb" class="form-control-ncpms" value="160">
                    </div>
                    <div class="col-12">
                        <label class="form-label-ncpms">Formula</label>
                        <select id="calc_formula" class="form-select form-control-ncpms">
                            <option value="hb">Harris-Benedict</option>
                            <option value="mifflin">Mifflin-St Jeor</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-4 p-3 rounded text-center" style="background: #e6fcf5; border: 1px dashed #20c997;">
                    <div style="font-size: 0.8rem; font-weight: 600; color: #0ca678; text-transform: uppercase;">Estimasi Energi Basal (BEE)</div>
                    <div id="calc_result" style="font-size: 2rem; font-weight: 800; color: var(--color-primary); line-height: 1.2;">0</div>
                    <div style="font-size: 0.85rem; color: #495057;">kkal / hari</div>
                </div>
            </div>
            <div class="modal-footer bg-white border-top-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-ncpms" id="btnApplyCalc"><i class="fas fa-check me-1"></i>Gunakan Hasil</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = ['calc_jk', 'calc_usia', 'calc_bb', 'calc_tb', 'calc_formula'];
    
    function hitungBEE() {
        let jk = document.getElementById('calc_jk').value;
        let usia = parseFloat(document.getElementById('calc_usia').value) || 0;
        let bb = parseFloat(document.getElementById('calc_bb').value) || 0;
        let tb = parseFloat(document.getElementById('calc_tb').value) || 0;
        let formula = document.getElementById('calc_formula').value;
        
        let bee = 0;
        if (formula === 'hb') {
            if (jk === 'L') {
                bee = 66.5 + (13.75 * bb) + (5.003 * tb) - (6.75 * usia);
            } else {
                bee = 655.1 + (9.563 * bb) + (1.850 * tb) - (4.676 * usia);
            }
        } else if (formula === 'mifflin') {
            bee = (10 * bb) + (6.25 * tb) - (5 * usia) + (jk === 'L' ? 5 : -161);
        }
        
        document.getElementById('calc_result').innerText = Math.max(0, Math.round(bee));
    }

    inputs.forEach(id => {
        document.getElementById(id).addEventListener('input', hitungBEE);
    });
    
    document.getElementById('btnApplyCalc').addEventListener('click', function() {
        let bee = document.getElementById('calc_result').innerText;
        document.querySelector('input[name="kebutuhan_energi_basal_kkal"]').value = bee;
        let formula = document.getElementById('calc_formula').value === 'hb' ? 'harris_benedict' : 'mifflin_st_jeor';
        document.querySelector('select[name="formula_basal"]').value = formula;
        
        bootstrap.Modal.getInstance(document.getElementById('kalkulatorModal')).hide();
    });
    
    // Food-Drug Interaction Warning Logic
    document.getElementById('kunjungan_select').addEventListener('change', function() {
        let selectedOption = this.options[this.selectedIndex];
        let obat = selectedOption.getAttribute('data-obat');
        let warningContainer = document.getElementById('obat_warning_container');
        let obatText = document.getElementById('obat_text');
        
        if (obat && obat.trim() !== '') {
            obatText.innerText = obat;
            warningContainer.classList.remove('d-none');
        } else {
            warningContainer.classList.add('d-none');
            obatText.innerText = '';
        }
    });

    hitungBEE();
});
</script>
@endpush
