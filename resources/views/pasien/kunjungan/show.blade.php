@extends('layouts.app')
@section('title','Kunjungan PAGT')
@section('breadcrumb','Pasien / Kunjungan')

@push('styles')
<style>
    .pagt-step {
        display: flex; align-items: center; gap: 8px;
        padding: 8px 0; border-bottom: 1px solid var(--color-divider);
        font-size: 0.84rem;
    }
    .pagt-step:last-child { border-bottom: none; }
    .pagt-step .step-name { flex: 1; color: var(--color-text-secondary); }
    .konseling-item { border-bottom: 1px solid var(--color-divider); padding: 10px 0; }
    .konseling-item:last-child { border-bottom: none; }
    .imt-display {
        padding: 10px 14px; border-radius: var(--radius-sm); text-align: center;
        background: var(--color-primary-subtle); border: 1px solid var(--color-primary-border);
    }
</style>
@endpush

@section('content')
@php
    $peran = Auth::user()->peran;
    $terkunci = (bool) $kunjungan->dokumen_terkunci;
    $bisaSkrining = !$terkunci && in_array($peran, ['perawat','spgk'], true);
    $bisaAntropometri = !$terkunci && in_array($peran, ['nutrisionis','dietisien','spgk'], true);
    $bisaFisik = !$terkunci && in_array($peran, ['perawat','dietisien','spgk'], true);
    $bisaBiokimia = !$terkunci && in_array($peran, ['dietisien','spgk'], true);
    $bisaAsupan = !$terkunci && in_array($peran, ['nutrisionis','dietisien','spgk'], true);
    $bisaKonseling = !$terkunci && in_array($peran, ['nutrisionis','dietisien','spgk'], true);
    $bisaDokumen = !$terkunci && in_array($peran, ['dietisien','spgk'], true);
    $bisaSelesai = !$terkunci && in_array($peran, ['dietisien','spgk'], true);
    $bisaIntervensi = !$terkunci && in_array($peran, ['dietisien','spgk'], true);
    $komorbids = is_string($kunjungan->diagnosis_medis_penyerta) ? json_decode($kunjungan->diagnosis_medis_penyerta, true) : ($kunjungan->diagnosis_medis_penyerta ?? []);
    $risikoTinggi = $kunjungan->skriningGizi?->kategori_risiko === 'risiko_tinggi';
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">{{ $kunjungan->nomor_kunjungan }}</h1>
        <p class="page-subtitle">{{ $kunjungan->pasien->nama_lengkap }} &bull; {{ $kunjungan->tanggal_kunjungan?->format('d M Y') }}</p>
    </div>
    <div class="d-flex gap-2">
        @if(!$kunjungan->dokumen_terkunci && Auth::user()->peran === 'spgk')
            <button type="button" class="btn-danger-ncpms" data-bs-toggle="modal" data-bs-target="#modalTTE">
                <i class="fas fa-lock"></i> Kunci & TTE
            </button>
        @endif
        <a href="{{ route('kunjungan.cetak-pagt', $kunjungan) }}" target="_blank" class="btn-ncpms" style="text-decoration: none;">
            <i class="fas fa-file-pdf"></i> Cetak PAGT
        </a>
        @if($bisaSelesai)
            <form method="POST" action="{{ route('kunjungan.selesai', $kunjungan) }}">
                @csrf
                <button class="btn-ncpms-outline">
                    <i class="fas fa-check"></i> Selesai
                </button>
            </form>
        @endif
    </div>
</div>

{{-- Adendum RME Section --}}
@if($terkunci)
<div class="ncpms-card mt-4 border-danger" style="border-width: 2px;">
    <div class="card-title-custom text-danger">
        <span class="card-title-icon bg-danger text-white"><i class="fas fa-file-medical-alt"></i></span>
        Adendum & Catatan Koreksi RME
    </div>
    <div class="alert alert-warning border-0" style="background: #fff3cd; color: #856404; font-size: 0.85rem;">
        <i class="fas fa-exclamation-triangle me-1"></i> Berdasarkan Permenkes 24/2022 Pasal 20, rekam medis yang telah ditandatangani elektronik <strong>tidak dapat diubah</strong>. Jika terdapat kesalahan pencatatan, tenaga kesehatan harus membuat <strong>Adendum</strong>.
    </div>

    @if(in_array($peran, ['dietisien','spgk','perawat']))
    <form method="POST" action="{{ route('kunjungan.adendum.store', $kunjungan) }}" class="mb-4 bg-light p-3 rounded border">
        @csrf
        <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label-ncpms">Jenis Data yang Dikoreksi</label>
                <select name="jenis_data" class="form-select form-control-ncpms" required>
                    <option value="Skrining Gizi">Skrining Gizi</option>
                    <option value="Asesmen Antropometri">Asesmen Antropometri</option>
                    <option value="Asesmen Klinis/Fisik">Asesmen Klinis/Fisik</option>
                    <option value="Asesmen Asupan">Asesmen Asupan</option>
                    <option value="Diagnosis PES">Diagnosis PES</option>
                    <option value="Preskripsi Diet">Preskripsi Diet</option>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label-ncpms">Catatan Koreksi (Adendum) <span class="text-danger">*</span></label>
                <textarea name="catatan_koreksi" class="form-control-ncpms" rows="2" required placeholder="Tuliskan data yang benar di sini..."></textarea>
            </div>
            <div class="col-12 text-end mt-2">
                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-plus-circle me-1"></i> Lampirkan Adendum</button>
            </div>
        </div>
    </form>
    @endif

    <div class="mt-3">
        @forelse($kunjungan->adendums as $adendum)
        <div class="p-3 mb-2 border rounded" style="background: #fffafa; border-color: #f5c6cb !important;">
            <div class="d-flex justify-content-between">
                <strong class="text-danger"><i class="fas fa-check-double me-1"></i> Koreksi pada: {{ $adendum->jenis_data }}</strong>
                <small class="text-muted">{{ $adendum->created_at->format('d/m/Y H:i:s') }}</small>
            </div>
            <p class="mt-2 mb-1 fw-bold text-dark">{{ $adendum->catatan_koreksi }}</p>
            <small class="text-muted">Dilampirkan oleh: {{ $adendum->pembuat->nama_lengkap ?? 'Sistem' }}</small>
        </div>
        @empty
        <div class="text-center py-3 text-muted">Belum ada adendum pada rekam medis ini.</div>
        @endforelse
    </div>
</div>
@endif

@if($kunjungan->dokumen_terkunci)
<div class="locked-banner d-flex justify-content-between align-items-center">
    <div>
        <i class="fas fa-lock me-2"></i> Dokumen asuhan gizi ini telah diverifikasi & dikunci pada {{ $kunjungan->dikunci_pada?->format('d/m/Y H:i') }}.
    </div>
    <span class="badge-pill" style="background: #dcfce7; color: #166534; font-size: 0.75rem;"><i class="fas fa-certificate text-success"></i> TTE TERSERTIFIKASI</span>
</div>
@endif

@if($risikoTinggi || count($komorbids) > 0)
    <div class="warning-clinical">
        <i class="fas fa-exclamation-triangle mt-1"></i>
        <div>
            <strong>PERINGATAN KLINIS:</strong> Pasien ini
            @if($risikoTinggi) memiliki <strong>Risiko Malnutrisi Tinggi</strong> @endif
            @if($risikoTinggi && count($komorbids) > 0) dan @endif
            @if(count($komorbids) > 0) memiliki <strong>{{ count($komorbids) }} komorbid penyerta</strong> @endif.
            Perhatikan interaksi obat-makanan saat meresepkan diet!
        </div>
    </div>
@endif

<div class="row g-3">
    {{-- Left Sidebar --}}
    <div class="col-lg-4">
        {{-- Patient Summary --}}
        <div class="ncpms-card mb-3">
            <div class="card-title-custom">
                <i class="fas fa-user" style="color: var(--color-primary);"></i>
                Ringkasan Pasien
            </div>
            <div class="info-row">
                <div>
                    <div class="info-label">Nama</div>
                    <div class="info-value">{{ $kunjungan->pasien->nama_lengkap }}</div>
                </div>
            </div>
            <div class="info-row">
                <div>
                    <div class="info-label">No. RM</div>
                    <div class="info-value">{{ $kunjungan->pasien->nomor_rekam_medis }}</div>
                </div>
            </div>
            <div class="info-row">
                <div>
                    <div class="info-label">Usia</div>
                    <div class="info-value">{{ $kunjungan->pasien->tanggal_lahir?->age }} tahun</div>
                </div>
            </div>
            <div class="info-row">
                <div>
                    <div class="info-label">Diagnosis</div>
                    <div class="info-value">{{ $kunjungan->diagnosisMedisUtama->nama_diagnosis ?? '-' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div>
                    <div class="info-label">Tipe</div>
                    <div class="info-value">{{ str_replace('_',' ', $kunjungan->tipe_kunjungan) }}</div>
                </div>
            </div>
            <div class="info-row">
                <div>
                    <div class="info-label">Dietisien</div>
                    <div class="info-value">{{ $kunjungan->dietisien->nama_lengkap ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- PAGT Progress --}}
        <div class="ncpms-card">
            <div class="card-title-custom">
                <i class="fas fa-flag" style="color: var(--color-primary);"></i>
                Status Tahap PAGT
            </div>
            @php
                $pagtSteps = [
                    ['Skrining Gizi', (bool)$kunjungan->skriningGizi, 'fa-clipboard-check'],
                    ['Antropometri', (bool)$kunjungan->antropometri, 'fa-ruler-vertical'],
                    ['Riwayat Medis', true, 'fa-notes-medical'],
                    ['Skrining Gizi', (bool)$kunjungan->skriningGizi, 'fa-clipboard-check'],
                    ['Riwayat Asupan', (bool)$kunjungan->asupan, 'fa-bowl-food'],
                    ['Diagnosis Gizi', (bool)$kunjungan->diagnosaGizis->count(), 'fa-stethoscope'],
                    ['Preskripsi Diet', (bool)$kunjungan->preskripsiDiets->count(), 'fa-utensils'],
                    ['Konseling', (bool)$kunjungan->catatanKonselings->count(), 'fa-comments'],
                    ['Dokumen Edukasi', (bool)$kunjungan->dokumenEdukasiis->count(), 'fa-file-medical'],
                    ['Monitoring', (bool)$kunjungan->monitoring, 'fa-heart-pulse'],
                ];
                $doneCount = collect($pagtSteps)->where(1, true)->count();
                $totalCount = count($pagtSteps);
            @endphp
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1" style="font-size: 0.78rem; font-weight: 600; color: var(--color-text-muted);">
                    <span>Progress PAGT</span><span>{{ $doneCount }}/{{ $totalCount }}</span>
                </div>
                <div class="progress" style="height: 6px; border-radius: 3px; background: var(--color-primary-subtle);">
                    <div class="progress-bar" style="width: {{ ($doneCount/$totalCount)*100 }}%; background: var(--color-primary); border-radius: 3px;"></div>
                </div>
            </div>
            @foreach($pagtSteps as [$label, $done, $icon])
            <div class="pagt-step">
                <i class="fas {{ $icon }}" style="width: 16px; font-size: 0.82rem; color: {{ $done ? 'var(--color-risiko-rendah)' : 'var(--color-text-muted)' }};"></i>
                <span class="step-name">{{ $label }}</span>
                <span class="badge-pill {{ $done ? 'badge-soft-success' : 'badge-soft-gray' }}">
                    {{ $done ? '✓ Selesai' : 'Belum' }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Right Forms --}}
    <div class="col-lg-8">

        {{-- 1. Skrining --}}
        <div class="ncpms-card">
            <div class="card-title-custom">
                <i class="fas fa-clipboard-check" style="color: var(--color-primary);"></i>
                Skrining Gizi
            </div>
            @if($bisaSkrining)
            <form method="POST" action="{{ route('kunjungan.skrining.store', $kunjungan) }}" class="row g-2">
                @csrf
                <div class="col-md-3">
                    <label class="form-label-ncpms">Metode</label>
                    <select name="metode_skrining" class="form-select form-control-ncpms">
                        <option value="MST" {{ ($kunjungan->skriningGizi?->metode_skrining=='MST')?'selected':'' }}>MST (Dewasa Umum)</option>
                        <option value="NRS2002" {{ ($kunjungan->skriningGizi?->metode_skrining=='NRS2002')?'selected':'' }}>NRS-2002 (Dewasa ICU)</option>
                        <option value="STRONGkids" {{ ($kunjungan->skriningGizi?->metode_skrining=='STRONGkids')?'selected':'' }}>STRONGkids (Pediatri)</option>
                        <option value="MNA" {{ ($kunjungan->skriningGizi?->metode_skrining=='MNA')?'selected':'' }}>MNA (Lansia > 65 thn)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label-ncpms">Skor BB</label>
                    <input type="number" name="skor_penurunan_bb" min="0" max="3" class="form-control-ncpms" value="{{ $kunjungan->skriningGizi?->skor_penurunan_bb ?? 0 }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label-ncpms">Skor Asupan</label>
                    <input type="number" name="skor_penurunan_asupan" min="0" max="3" class="form-control-ncpms" value="{{ $kunjungan->skriningGizi?->skor_penurunan_asupan ?? 0 }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label-ncpms">Penyakit</label>
                    <input type="number" name="skor_keparahan_penyakit" min="0" max="3" class="form-control-ncpms" value="{{ $kunjungan->skriningGizi?->skor_keparahan_penyakit ?? 0 }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label-ncpms">Rekomendasi</label>
                    <input name="rekomendasi_tindak_lanjut" class="form-control-ncpms" value="{{ $kunjungan->skriningGizi?->rekomendasi_tindak_lanjut ?? '' }}">
                </div>
                <div class="col-12">
                    <button class="btn-ncpms">
                        <i class="fas fa-save"></i> Simpan Skrining
                    </button>
                </div>
            </form>
            @else
                <p class="permission-note"><i class="fas fa-info-circle me-1"></i>Skrining hanya dapat dicatat oleh perawat atau SpGK selama dokumen belum terkunci.</p>
            @endif
        </div>

        {{-- Riwayat Pengobatan --}}
        <div class="ncpms-card mb-4" id="riwayat_medis">
            <div class="card-title-custom">
                <span class="card-title-icon"><i class="fas fa-pills"></i></span>
                Riwayat Pengobatan Medis
            </div>
            
            @if(!$terkunci && in_array($peran, ['perawat','dietisien','spgk']))
            <form method="POST" action="{{ route('kunjungan.obat.update', $kunjungan) }}" class="row g-2">
                @csrf
                <div class="col-12">
                    <label class="form-label-ncpms">Obat-obatan yang sedang dikonsumsi <span class="text-muted">(Penting untuk mendeteksi Food-Drug Interaction)</span></label>
                    <textarea name="obat_sedang_dikonsumsi" class="form-control-ncpms" rows="2" placeholder="Contoh: Warfarin, Captopril, Metformin...">{{ $kunjungan->obat_sedang_dikonsumsi }}</textarea>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn-ncpms btn-sm-ncpms"><i class="fas fa-save me-1"></i> Simpan Obat</button>
                </div>
            </form>
            @else
            <div class="info-row">
                <div class="info-label">Daftar Obat Aktif</div>
                <div class="info-value fw-bold text-danger">{{ $kunjungan->obat_sedang_dikonsumsi ?: 'Tidak ada catatan obat khusus.' }}</div>
            </div>
            @endif
        </div>

        {{-- 2. Antropometri --}}
        <div class="ncpms-card">
            <div class="card-title-custom">
                <i class="fas fa-weight-scale" style="color: var(--color-primary);"></i>
                Antropometri
            </div>
            @if($bisaAntropometri)
            <form method="POST" action="{{ route('kunjungan.antropometri.store', $kunjungan) }}" class="row g-2">
                @csrf
                <div class="col-md-3"><label class="form-label-ncpms">Tanggal</label><input type="date" name="tanggal_pengukuran" class="form-control-ncpms" value="{{ $kunjungan->antropometri?->tanggal_pengukuran?->format('Y-m-d') ?? date('Y-m-d') }}"></div>
                <div class="col-md-3"><label class="form-label-ncpms">Berat Badan (kg)</label><input type="number" step="0.1" name="berat_badan_kg" class="form-control-ncpms" value="{{ $kunjungan->antropometri?->berat_badan_kg ?? '' }}" required></div>
                <div class="col-md-3"><label class="form-label-ncpms">Tinggi Badan (cm)</label><input type="number" step="0.1" name="tinggi_badan_cm" class="form-control-ncpms" value="{{ $kunjungan->antropometri?->tinggi_badan_cm ?? '' }}" required></div>
                <div class="col-md-3"><label class="form-label-ncpms">LiLA (cm)</label><input type="number" step="0.1" name="lingkar_lengan_atas_cm" class="form-control-ncpms" value="{{ $kunjungan->antropometri?->lingkar_lengan_atas_cm ?? '' }}"></div>
                <div class="col-md-3"><label class="form-label-ncpms">Lingkar Perut (cm)</label><input type="number" step="0.1" name="lingkar_perut_cm" class="form-control-ncpms" value="{{ $kunjungan->antropometri?->lingkar_perut_cm ?? '' }}"></div>
                @if($kunjungan->antropometri)
                    <div class="col-md-4">
                        <div class="imt-display">
                            <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: var(--color-text-muted);">IMT</div>
                            <div style="font-size: 1.4rem; font-weight: 800; color: var(--color-primary);">{{ $kunjungan->antropometri?->imt }}</div>
                            <div style="font-size: 0.75rem; color: var(--color-text-secondary);">{{ $kunjungan->antropometri?->status_gizi_imt }}</div>
                        </div>
                    </div>
                @endif
                <div class="col-12">
                    <button class="btn-ncpms">
                        <i class="fas fa-save"></i> Simpan Antropometri
                    </button>
                </div>
            </form>
            @else
                <p class="permission-note"><i class="fas fa-info-circle me-1"></i>Antropometri hanya dapat dicatat oleh nutrisionis, dietisien, atau SpGK selama dokumen belum terkunci.</p>
            @endif
        </div>

        {{-- 3. Fisik --}}
        <div class="ncpms-card">
            <div class="card-title-custom">
                <i class="fas fa-heart-pulse" style="color: var(--color-primary);"></i>
                Fisik Klinis &amp; Vital Sign
            </div>
            @if($bisaFisik)
            <form method="POST" action="{{ route('kunjungan.fisik.store', $kunjungan) }}" class="row g-2">
                @csrf
                <div class="col-md-2"><label class="form-label-ncpms">Sistolik</label><input type="number" name="tekanan_darah_sistolik" class="form-control-ncpms" value="{{ $kunjungan->fisik?->tekanan_darah_sistolik ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Diastolik</label><input type="number" name="tekanan_darah_diastolik" class="form-control-ncpms" value="{{ $kunjungan->fisik?->tekanan_darah_diastolik ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Nadi</label><input type="number" name="nadi_per_menit" class="form-control-ncpms" value="{{ $kunjungan->fisik?->nadi_per_menit ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">RR</label><input type="number" name="respirasi_per_menit" class="form-control-ncpms" value="{{ $kunjungan->fisik?->respirasi_per_menit ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Suhu (°C)</label><input type="number" step="0.1" name="suhu_celsius" class="form-control-ncpms" value="{{ $kunjungan->fisik?->suhu_celsius ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">SpO₂ (%)</label><input type="number" name="saturasi_oksigen_persen" class="form-control-ncpms" value="{{ $kunjungan->fisik?->saturasi_oksigen_persen ?? '' }}"></div>
                <div class="col-md-6"><label class="form-label-ncpms">Defisiensi (pisahkan koma)</label><input name="tanda_defisiensi" class="form-control-ncpms" value="{{ isset($kunjungan->fisik) ? implode(',', $kunjungan->fisik?->tanda_defisiensi ?? []) : '' }}"></div>
                <div class="col-md-6"><label class="form-label-ncpms">Gangguan GI (pisahkan koma)</label><input name="gangguan_gastrointestinal" class="form-control-ncpms" value="{{ isset($kunjungan->fisik) ? implode(',', $kunjungan->fisik?->gangguan_gastrointestinal ?? []) : '' }}"></div>
                <div class="col-12"><label class="form-label-ncpms">Catatan Klinis</label><textarea name="catatan_klinis" class="form-control-ncpms" rows="2">{{ $kunjungan->fisik?->catatan_klinis ?? '' }}</textarea></div>
                <div class="col-12">
                    <button class="btn-ncpms">
                        <i class="fas fa-save"></i> Simpan Fisik
                    </button>
                </div>
            </form>
            @else
                <p class="permission-note"><i class="fas fa-info-circle me-1"></i>Vital sign hanya dapat dicatat oleh perawat, dietisien, atau SpGK selama dokumen belum terkunci.</p>
            @endif
        </div>

        {{-- 4. Biokimia --}}
        <div class="ncpms-card">
            <div class="card-title-custom">
                <i class="fas fa-vial" style="color: var(--color-primary);"></i>
                Biokimia
            </div>
            @if($bisaBiokimia)
            <form method="POST" action="{{ route('kunjungan.biokimia.store', $kunjungan) }}" class="row g-2">
                @csrf
                <div class="col-md-3"><label class="form-label-ncpms">Tanggal</label><input type="date" name="tanggal_pemeriksaan" class="form-control-ncpms" value="{{ $kunjungan->biokimia?->tanggal_pemeriksaan?->format('Y-m-d') ?? date('Y-m-d') }}"></div>
                <div class="col-md-3"><label class="form-label-ncpms">Sumber Data</label><select name="sumber_data" class="form-control-ncpms"><option value="lab_internal">Lab Internal</option><option value="input_manual">Input Manual</option><option value="satusehat">SATUSEHAT</option></select></div>
                <div class="col-md-2"><label class="form-label-ncpms">GDP (mg/dL)</label><input type="number" step="0.1" name="gula_darah_puasa" class="form-control-ncpms" value="{{ $kunjungan->biokimia?->gula_darah_puasa ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">HbA1c (%)</label><input type="number" step="0.1" name="hba1c_persen" class="form-control-ncpms" value="{{ $kunjungan->biokimia?->hba1c_persen ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Albumin (g/dL)</label><input type="number" step="0.1" name="albumin" class="form-control-ncpms" value="{{ $kunjungan->biokimia?->albumin ?? '' }}"></div>
                <div class="col-12"><label class="form-label-ncpms">Catatan Tambahan</label><textarea name="catatan_tambahan" class="form-control-ncpms" rows="2">{{ $kunjungan->biokimia?->catatan_tambahan ?? '' }}</textarea></div>
                <div class="col-12">
                    <button class="btn-ncpms">
                        <i class="fas fa-save"></i> Simpan Biokimia
                    </button>
                </div>
            </form>
            @else
                <p class="permission-note"><i class="fas fa-info-circle me-1"></i>Data biokimia hanya dapat dicatat oleh dietisien atau SpGK selama dokumen belum terkunci.</p>
            @endif
        </div>

        {{-- 5. Asupan --}}
        <div class="ncpms-card">
            <div class="card-title-custom">
                <i class="fas fa-bowl-food" style="color: var(--color-primary);"></i>
                Riwayat Asupan
            </div>
            @if($bisaAsupan)
            <form method="POST" action="{{ route('kunjungan.asupan.store', $kunjungan) }}" class="row g-2">
                @csrf
                <div class="col-md-3"><label class="form-label-ncpms">Metode</label><select name="metode" class="form-control-ncpms"><option value="food_recall_24h">Food Recall 24 jam</option><option value="food_recall_48h">Food Recall 48 jam</option><option value="food_recall_72h">Food Recall 72 jam</option><option value="ffq_semi_kuantitatif">FFQ Semi Kuantitatif</option></select></div>
                <div class="col-md-3"><label class="form-label-ncpms">Tanggal</label><input type="date" name="tanggal_recall" class="form-control-ncpms" value="{{ $kunjungan->asupan?->tanggal_recall?->format('Y-m-d') ?? date('Y-m-d') }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Energi (kkal)</label><input type="number" step="0.1" name="total_energi_kkal" class="form-control-ncpms" value="{{ $kunjungan->asupan?->total_energi_kkal ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Protein (g)</label><input type="number" step="0.1" name="total_protein_gram" class="form-control-ncpms" value="{{ $kunjungan->asupan?->total_protein_gram ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Lemak (g)</label><input type="number" step="0.1" name="total_lemak_gram" class="form-control-ncpms" value="{{ $kunjungan->asupan?->total_lemak_gram ?? '' }}"></div>
                <div class="col-12"><label class="form-label-ncpms">Detail Asupan</label><textarea name="detail_asupan" class="form-control-ncpms" rows="2" required>{{ $kunjungan->asupan?->detail_asupan[0]['catatan'] ?? '' }}</textarea></div>
                <div class="col-12"><label class="form-label-ncpms">Kesimpulan</label><textarea name="kesimpulan_asupan" class="form-control-ncpms" rows="2">{{ $kunjungan->asupan?->kesimpulan_asupan ?? '' }}</textarea></div>
                <div class="col-12">
                    <button class="btn-ncpms">
                        <i class="fas fa-save"></i> Simpan Asupan
                    </button>
                </div>
            </form>
            @else
                <p class="permission-note"><i class="fas fa-info-circle me-1"></i>Riwayat asupan hanya dapat dicatat oleh nutrisionis, dietisien, atau SpGK selama dokumen belum terkunci.</p>
            @endif
        </div>

        {{-- 6. Detail Menu --}}
        <div class="ncpms-card">
            <div class="card-title-custom">
                <i class="fas fa-utensils" style="color: var(--color-primary);"></i>
                Detail Menu Harian
            </div>
            @forelse($kunjungan->preskripsiDiets as $preskripsi)
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold" style="color: var(--color-primary); font-size: 1.05rem;">{{ number_format($preskripsi->total_kebutuhan_energi_kkal) }} <span style="font-size: 0.75rem; color: var(--color-text-muted);">kkal</span></span>
                        <span class="badge-pill badge-soft-gray"><i class="far fa-calendar me-1"></i>{{ $preskripsi->tanggal_mulai?->format('d/m/Y') }}</span>
                    </div>
                    <div class="table-responsive" style="border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
                        <table class="data-table mb-0">
                            <thead><tr><th>Waktu</th><th>Bahan</th><th>Porsi</th><th>Energi</th><th>Makro</th></tr></thead>
                            <tbody>
                                @forelse($preskripsi->detailMenuHarians as $menu)
                                    <tr>
                                        <td>{{ str_replace('_',' ', $menu->waktu_makan) }}</td>
                                        <td class="fw-bold">{{ $menu->bahanMakanan->nama_bahan ?? '-' }}</td>
                                        <td>{{ $menu->porsi_gram }} g</td>
                                        <td>{{ $menu->energi_kkal }} kkal</td>
                                        <td><span style="color: var(--color-primary); font-size: 0.78rem;">KH {{ $menu->karbohidrat_gram }}g / P {{ $menu->protein_gram }}g / L {{ $menu->lemak_gram }}g</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-muted text-center py-2" style="font-size: 0.83rem;">Belum ada detail menu.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($bisaDokumen)
                        <form method="POST" action="{{ route('intervensi.menu.store', $preskripsi) }}" class="row g-2 mt-2">
                            @csrf
                            <div class="col-md-3"><select name="waktu_makan" class="form-control-ncpms"><option value="makan_pagi">Makan Pagi</option><option value="selingan_pagi">Selingan Pagi</option><option value="makan_siang">Makan Siang</option><option value="selingan_sore">Selingan Sore</option><option value="makan_malam">Makan Malam</option><option value="selingan_malam">Selingan Malam</option></select></div>
                            <div class="col-md-4"><select name="bahan_makanan_id" class="form-control-ncpms">@foreach($bahanMakanans as $bahan)<option value="{{ $bahan->id }}">{{ $bahan->nama_bahan }}</option>@endforeach</select></div>
                            <div class="col-md-2"><input type="number" step="0.1" name="porsi_gram" class="form-control-ncpms" value="100" placeholder="Porsi (g)"></div>
                            <div class="col-md-3 d-flex gap-2">
                                <input name="keterangan_penukar" class="form-control-ncpms" placeholder="Keterangan">
                                <button class="btn-ncpms btn-sm-ncpms"><i class="fas fa-plus"></i></button>
                            </div>
                        </form>
                    @endif
                </div>
                @if(!$loop->last)
                    <div class="section-divider">Preskripsi Berikutnya</div>
                @endif
            @empty
                <p class="permission-note"><i class="fas fa-info-circle me-1"></i>Preskripsi diet belum dibuat.</p>
            @endforelse
        </div>
        {{-- 6.5. Nutrisi Kritis --}}
        <div class="ncpms-card">
            <div class="card-title-custom">
                <i class="fas fa-procedures" style="color: var(--color-primary);"></i>
                Nutrisi Enteral & Parenteral (ICU/Kritis)
            </div>
            @if($kunjungan->preskripsiKritis)
                @php $kritis = $kunjungan->preskripsiKritis; @endphp
                <div class="info-row"><div class="info-label">Jenis Nutrisi</div><div class="info-value"><span class="badge-pill badge-soft-danger">{{ strtoupper($kritis->jenis_nutrisi) }}</span></div></div>
                <div class="info-row"><div class="info-label">Rute Pemberian</div><div class="info-value">{{ $kritis->rute_pemberian }}</div></div>
                <div class="info-row"><div class="info-label">Nama Formula</div><div class="info-value">{{ $kritis->nama_formula }}</div></div>
                <div class="info-row"><div class="info-label">Volume & Frekuensi</div><div class="info-value">{{ $kritis->volume_ml }} ml x {{ $kritis->frekuensi_sehari }} /hari</div></div>
                <div class="info-row"><div class="info-label">Kecepatan Drip</div><div class="info-value">{{ $kritis->kecepatan_pemberian ?? '-' }}</div></div>
                <div class="info-row"><div class="info-label">Total Gizi</div><div class="info-value">E: {{ $kritis->total_kalori_kkal }}kkal | P: {{ $kritis->total_protein_gram }}g | L: {{ $kritis->total_lemak_gram }}g | KH: {{ $kritis->total_karbohidrat_gram }}g</div></div>
                @if($kritis->instruksi_khusus)<div class="info-row"><div class="info-label">Instruksi Khusus</div><div class="info-value">{{ $kritis->instruksi_khusus }}</div></div>@endif
            @endif
            
            @if($bisaIntervensi && !$kunjungan->preskripsiKritis)
            <div class="section-divider">Buat Preskripsi Kritis</div>
            <form method="POST" action="{{ route('kunjungan.kritis.store', $kunjungan) }}" class="row g-2">
                @csrf
                <div class="col-md-3"><label class="form-label-ncpms">Jenis</label><select name="jenis_nutrisi" class="form-control-ncpms"><option value="enteral">Enteral</option><option value="parenteral">Parenteral</option><option value="kombinasi">Kombinasi</option></select></div>
                <div class="col-md-4"><label class="form-label-ncpms">Rute Pemberian</label><input name="rute_pemberian" class="form-control-ncpms" placeholder="Misal: NGT, NDT, Central IV" required></div>
                <div class="col-md-5"><label class="form-label-ncpms">Nama Formula</label><input name="nama_formula" class="form-control-ncpms" required></div>
                
                <div class="col-md-3"><label class="form-label-ncpms">Volume (ml)</label><input type="number" step="0.1" name="volume_ml" class="form-control-ncpms" required></div>
                <div class="col-md-3"><label class="form-label-ncpms">Frekuensi /hari</label><input type="number" name="frekuensi_sehari" class="form-control-ncpms" required></div>
                <div class="col-md-6"><label class="form-label-ncpms">Kecepatan (tetes/mnt atau ml/jam)</label><input type="number" step="0.1" name="kecepatan_pemberian" class="form-control-ncpms"></div>
                
                <div class="col-md-3"><label class="form-label-ncpms">Tot. Kalori (kkal)</label><input type="number" step="0.1" name="total_kalori_kkal" class="form-control-ncpms" required></div>
                <div class="col-md-3"><label class="form-label-ncpms">Tot. Protein (g)</label><input type="number" step="0.1" name="total_protein_gram" class="form-control-ncpms" required></div>
                <div class="col-md-3"><label class="form-label-ncpms">Tot. Lemak (g)</label><input type="number" step="0.1" name="total_lemak_gram" class="form-control-ncpms" required></div>
                <div class="col-md-3"><label class="form-label-ncpms">Tot. KH (g)</label><input type="number" step="0.1" name="total_karbohidrat_gram" class="form-control-ncpms" required></div>
                
                <div class="col-12"><label class="form-label-ncpms">Instruksi Khusus</label><textarea name="instruksi_khusus" class="form-control-ncpms" rows="2"></textarea></div>
                <div class="col-12"><button class="btn-ncpms"><i class="fas fa-save"></i> Simpan Preskripsi Kritis</button></div>
            </form>
            @endif
        </div>

        {{-- 7. Konseling --}}
        <div class="ncpms-card">
            <div class="card-title-custom">
                <i class="fas fa-comments" style="color: var(--color-primary);"></i>
                Catatan Konseling
            </div>
            @if($kunjungan->catatanKonselings->count())
                <div class="mb-3">
                    @foreach($kunjungan->catatanKonselings as $konseling)
                    <div class="konseling-item">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-bold" style="font-size: 0.88rem;">{{ $konseling->tanggal_konseling?->format('d/m/Y') }}</span>
                            <span class="badge-pill badge-soft-primary">{{ str_replace('_',' ', $konseling->metode) }}</span>
                        </div>
                        <div style="font-size: 0.84rem; color: var(--color-text-secondary);">{{ $konseling->isi_konseling }}</div>
                        <div style="font-size: 0.75rem; color: var(--color-text-muted); margin-top: 4px;">Oleh {{ $konseling->pelaksana->nama_lengkap ?? '-' }} &bull; Pemahaman: <strong>{{ $konseling->tingkat_pemahaman_pasien ?? '-' }}</strong></div>
                    </div>
                    @endforeach
                </div>
            @endif
            @if($bisaKonseling)
            <div class="section-divider">Tambah Konseling</div>
            <form method="POST" action="{{ route('kunjungan.konseling.store', $kunjungan) }}" class="row g-2">
                @csrf
                <div class="col-md-3"><label class="form-label-ncpms">Tanggal</label><input type="date" name="tanggal_konseling" class="form-control-ncpms" value="{{ date('Y-m-d') }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Durasi (mnt)</label><input type="number" name="durasi_menit" class="form-control-ncpms" value="30"></div>
                <div class="col-md-3"><label class="form-label-ncpms">Metode</label><select name="metode" class="form-control-ncpms"><option value="tatap_muka">Tatap Muka</option><option value="telepon">Telepon</option><option value="video_call">Video Call</option></select></div>
                <div class="col-md-4"><label class="form-label-ncpms">Pemahaman Pasien</label><select name="tingkat_pemahaman_pasien" class="form-control-ncpms"><option value="baik">Baik</option><option value="cukup">Cukup</option><option value="kurang">Kurang</option></select></div>
                <div class="col-12"><label class="form-label-ncpms">Topik (pisahkan koma)</label><input name="topik_konseling" class="form-control-ncpms" required></div>
                <div class="col-12"><label class="form-label-ncpms">Isi Konseling</label><textarea name="isi_konseling" class="form-control-ncpms" rows="2" required></textarea></div>
                <div class="col-md-6"><label class="form-label-ncpms">Hambatan Pasien</label><textarea name="hambatan_pasien" class="form-control-ncpms" rows="2"></textarea></div>
                <div class="col-md-6"><label class="form-label-ncpms">Kesepakatan Tindak Lanjut</label><textarea name="kesepakatan_tindak_lanjut" class="form-control-ncpms" rows="2"></textarea></div>
                <div class="col-12">
                    <button class="btn-ncpms">
                        <i class="fas fa-save"></i> Simpan Konseling
                    </button>
                </div>
            </form>
            @else
                <p class="permission-note"><i class="fas fa-info-circle me-1"></i>Konseling hanya dapat dicatat oleh nutrisionis, dietisien, atau SpGK selama dokumen belum terkunci.</p>
            @endif
        </div>

        {{-- 8. Dokumen Edukasi --}}
        <div class="ncpms-card">
            <div class="card-title-custom">
                <i class="fas fa-file-medical" style="color: var(--color-primary);"></i>
                Dokumen Edukasi
            </div>
            @if($kunjungan->dokumenEdukasiis->count())
                <div class="table-responsive mb-3" style="border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
                    <table class="data-table mb-0">
                        <thead><tr><th>Judul</th><th>Tipe</th><th>Kedaluwarsa</th><th>Pembuat</th></tr></thead>
                        <tbody>
                        @foreach($kunjungan->dokumenEdukasiis as $dokumen)
                            <tr>
                                <td class="fw-bold">{{ $dokumen->judul_dokumen }}</td>
                                <td><span class="badge-pill badge-soft-primary">{{ str_replace('_',' ', $dokumen->tipe) }}</span></td>
                                <td>{{ $dokumen->token_expired_at?->format('d/m/Y H:i') }}</td>
                                <td>{{ $dokumen->pembuat->nama_lengkap ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            @if($bisaDokumen)
            <div class="section-divider">Buat Dokumen Baru</div>
            <form method="POST" action="{{ route('kunjungan.dokumen-edukasi.store', $kunjungan) }}" class="row g-2">
                @csrf
                <div class="col-md-5"><label class="form-label-ncpms">Judul</label><input name="judul_dokumen" class="form-control-ncpms" value="Rencana Makan dan Edukasi Gizi" required></div>
                <div class="col-md-4"><label class="form-label-ncpms">Tipe</label><select name="tipe" class="form-control-ncpms"><option value="rencana_makan">Rencana Makan</option><option value="ringkasan_kalori">Ringkasan Kalori</option><option value="pantangan_alergi">Pantangan Alergi</option><option value="panduan_makan">Panduan Makan</option><option value="leaflet_diet">Leaflet Diet</option></select></div>
                <div class="col-md-3"><label class="form-label-ncpms">Token Expired</label><input type="date" name="token_expired_at" class="form-control-ncpms" value="{{ now()->addDays(7)->format('Y-m-d') }}"></div>
                <div class="col-12"><label class="form-label-ncpms">Ringkasan Konten</label><textarea name="ringkasan" class="form-control-ncpms" rows="2" required></textarea></div>
                <div class="col-12">
                    <button class="btn-ncpms">
                        <i class="fas fa-file-circle-plus"></i> Buat Dokumen
                    </button>
                </div>
            </form>
            @else
                <p class="permission-note"><i class="fas fa-info-circle me-1"></i>Dokumen edukasi hanya dapat dibuat oleh dietisien atau SpGK selama dokumen belum terkunci.</p>
            @endif
        </div>

    </div>
</div>

{{-- Modal TTE --}}
<div class="modal fade" id="modalTTE" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" style="color: var(--color-primary);"><i class="fas fa-certificate me-2"></i> Tanda Tangan Elektronik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('kunjungan.kunci', $kunjungan) }}">
                @csrf
                <div class="modal-body">
                    <div class="p-3 rounded mb-3" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-size: 0.85rem;">
                        <i class="fas fa-info-circle text-primary me-1"></i> Anda akan memverifikasi dan mengunci permanen dokumen klinis ini menggunakan TTE (Tanda Tangan Elektronik) Tersertifikasi BSrE. Tindakan ini memenuhi standar RME Permenkes 24/2022.
                    </div>
                    <div class="mb-3 text-center">
                        <label class="form-label-ncpms d-block">Masukkan PIN TTE (6 Karakter) <span class="required-mark">*</span></label>
                        <input type="password" name="pin_tte" class="form-control-ncpms text-center mx-auto d-inline-block" style="max-width: 200px; font-size: 1.5rem; letter-spacing: 0.5rem;" maxlength="6" minlength="6" placeholder="******" required>
                        <small class="text-muted mt-2 d-block" style="font-size: 0.75rem;">Contoh Simulasi: 123456</small>
                    </div>
                </div>
                <div class="modal-footer border-0" style="background: #f8fafc;">
                    <button type="button" class="btn btn-secondary px-4 py-2 rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-ncpms px-4 py-2 rounded-3"><i class="fas fa-fingerprint me-1"></i> Verifikasi & Kunci</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Memindahkan modal TTE ke luar dari wrapper utama agar tidak terhalang z-index / overlay
    document.addEventListener('DOMContentLoaded', function() {
        const modalTTE = document.getElementById('modalTTE');
        if (modalTTE) {
            document.body.appendChild(modalTTE);
        }
    });
</script>
@endpush
@endsection
