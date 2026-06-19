@extends('layouts.app')
@section('title','Kunjungan PAGT')
@section('breadcrumb','Pasien / Kunjungan')
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
@endphp
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $kunjungan->nomor_kunjungan }}</h1>
        <p class="page-subtitle">{{ $kunjungan->pasien->nama_lengkap }} - {{ $kunjungan->tanggal_kunjungan?->format('d/m/Y') }}</p>
    </div>
    <div class="d-flex gap-2">
        @if(!$kunjungan->dokumen_terkunci && Auth::user()->peran === 'spgk')
            <form method="POST" action="{{ route('kunjungan.kunci', $kunjungan) }}" data-confirm-lock>@csrf<button class="btn-danger-ncpms"><i class="fas fa-lock"></i> Kunci Dokumen</button></form>
        @endif
        @if($bisaSelesai)
            <form method="POST" action="{{ route('kunjungan.selesai', $kunjungan) }}">@csrf<button class="btn-outline-ncpms"><i class="fas fa-check"></i> Selesai</button></form>
        @endif
    </div>
</div>
@if($kunjungan->dokumen_terkunci)
    <div class="locked-banner"><i class="fas fa-lock me-2"></i> DOKUMEN TERKUNCI - data klinis tidak dapat diubah.</div>
@endif

<div class="row g-3">
    <div class="col-lg-4">
        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-user"></i></span> Ringkasan Pasien</h2>
            <div class="mb-2"><strong>Nama:</strong> {{ $kunjungan->pasien->nama_lengkap }}</div>
            <div class="mb-2"><strong>NRM:</strong> <span class="text-mono">{{ $kunjungan->pasien->nomor_rekam_medis }}</span></div>
            <div class="mb-2"><strong>Usia:</strong> {{ $kunjungan->pasien->tanggal_lahir?->age }} tahun</div>
            <div><strong>Diagnosis medis:</strong> {{ $kunjungan->diagnosisMedisUtama->nama_diagnosis ?? '-' }}</div>
        </div>
        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-flag"></i></span> Status Tahap PAGT</h2>
            @foreach(['Skrining'=>$kunjungan->skriningGizi, 'Antropometri'=>$kunjungan->antropometri, 'Fisik'=>$kunjungan->fisik, 'Biokimia'=>$kunjungan->biokimia, 'Asupan'=>$kunjungan->asupan, 'Diagnosis'=>$kunjungan->diagnosaGizis->count(), 'Preskripsi'=>$kunjungan->preskripsiDiets->count(), 'Konseling'=>$kunjungan->catatanKonselings->count(), 'Dokumen Edukasi'=>$kunjungan->dokumenEdukasiis->count(), 'Monitoring'=>$kunjungan->monitoring] as $label => $done)
                <div class="d-flex justify-content-between border-bottom py-2"><span>{{ $label }}</span><strong style="color:{{ $done ? 'var(--color-risiko-rendah)' : 'var(--color-text-muted)' }}">{{ $done ? 'Selesai' : 'Belum' }}</strong></div>
            @endforeach
        </div>
    </div>
    <div class="col-lg-8">
        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-clipboard-check"></i></span> 1. Skrining Gizi</h2>
            @if($bisaSkrining)
            <form method="POST" action="{{ route('kunjungan.skrining.store', $kunjungan) }}" class="row g-3">
                @csrf
                <div class="col-md-3"><label class="form-label-ncpms">Metode</label><select name="metode_skrining" class="form-control-ncpms"><option>MST</option><option>MNA</option><option>NRS2002</option><option>MUST</option><option>STAMP</option></select></div>
                <div class="col-md-2"><label class="form-label-ncpms">Skor BB</label><input type="number" name="skor_penurunan_bb" min="0" max="3" class="form-control-ncpms" value="{{ $kunjungan->skriningGizi?->skor_penurunan_bb ?? 0 }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Skor Asupan</label><input type="number" name="skor_penurunan_asupan" min="0" max="3" class="form-control-ncpms" value="{{ $kunjungan->skriningGizi?->skor_penurunan_asupan ?? 0 }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Penyakit</label><input type="number" name="skor_keparahan_penyakit" min="0" max="3" class="form-control-ncpms" value="{{ $kunjungan->skriningGizi?->skor_keparahan_penyakit ?? 0 }}"></div>
                <div class="col-md-3"><label class="form-label-ncpms">Rekomendasi</label><input name="rekomendasi_tindak_lanjut" class="form-control-ncpms" value="{{ $kunjungan->skriningGizi?->rekomendasi_tindak_lanjut ?? '' }}"></div>
                <div class="col-12"><button class="btn-primary-ncpms"><i class="fas fa-save"></i> Simpan Skrining</button></div>
            </form>
            @else
                <div class="text-muted">Skrining hanya dapat dicatat oleh perawat atau SpGK selama dokumen belum terkunci.</div>
            @endif
        </div>

        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-weight-scale"></i></span> 2. Antropometri</h2>
            @if($bisaAntropometri)
            <form method="POST" action="{{ route('kunjungan.antropometri.store', $kunjungan) }}" class="row g-3">
                @csrf
                <div class="col-md-3"><label class="form-label-ncpms">Tanggal</label><input type="date" name="tanggal_pengukuran" class="form-control-ncpms" value="{{ $kunjungan->antropometri?->tanggal_pengukuran?->format('Y-m-d') ?? date('Y-m-d') }}"></div>
                <div class="col-md-3"><label class="form-label-ncpms">Berat Badan (kg)</label><input type="number" step="0.1" name="berat_badan_kg" class="form-control-ncpms" value="{{ $kunjungan->antropometri?->berat_badan_kg ?? '' }}" required></div>
                <div class="col-md-3"><label class="form-label-ncpms">Tinggi Badan (cm)</label><input type="number" step="0.1" name="tinggi_badan_cm" class="form-control-ncpms" value="{{ $kunjungan->antropometri?->tinggi_badan_cm ?? '' }}" required></div>
                <div class="col-md-3"><label class="form-label-ncpms">LiLA (cm)</label><input type="number" step="0.1" name="lingkar_lengan_atas_cm" class="form-control-ncpms" value="{{ $kunjungan->antropometri?->lingkar_lengan_atas_cm ?? '' }}"></div>
                <div class="col-md-3"><label class="form-label-ncpms">Lingkar Perut (cm)</label><input type="number" step="0.1" name="lingkar_perut_cm" class="form-control-ncpms" value="{{ $kunjungan->antropometri?->lingkar_perut_cm ?? '' }}"></div>
                @if($kunjungan->antropometri)
                    <div class="col-md-3"><label class="form-label-ncpms">IMT</label><div class="stat-card py-2"><div class="stat-value fs-4">{{ $kunjungan->antropometri?->imt }}</div><div class="stat-label">{{ $kunjungan->antropometri?->status_gizi_imt }}</div></div></div>
                @endif
                <div class="col-12"><button class="btn-primary-ncpms"><i class="fas fa-save"></i> Simpan Antropometri</button></div>
            </form>
            @else
                <div class="text-muted">Antropometri hanya dapat dicatat oleh nutrisionis, dietisien, atau SpGK selama dokumen belum terkunci.</div>
            @endif
        </div>

        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-heart-pulse"></i></span> 3. Fisik Klinis & Vital Sign</h2>
            @if($bisaFisik)
            <form method="POST" action="{{ route('kunjungan.fisik.store', $kunjungan) }}" class="row g-3">
                @csrf
                <div class="col-md-2"><label class="form-label-ncpms">Sistolik</label><input type="number" name="tekanan_darah_sistolik" class="form-control-ncpms" value="{{ $kunjungan->fisik?->tekanan_darah_sistolik ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Diastolik</label><input type="number" name="tekanan_darah_diastolik" class="form-control-ncpms" value="{{ $kunjungan->fisik?->tekanan_darah_diastolik ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Nadi</label><input type="number" name="nadi_per_menit" class="form-control-ncpms" value="{{ $kunjungan->fisik?->nadi_per_menit ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">RR</label><input type="number" name="respirasi_per_menit" class="form-control-ncpms" value="{{ $kunjungan->fisik?->respirasi_per_menit ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Suhu</label><input type="number" step="0.1" name="suhu_celsius" class="form-control-ncpms" value="{{ $kunjungan->fisik?->suhu_celsius ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">SpO2</label><input type="number" name="saturasi_oksigen_persen" class="form-control-ncpms" value="{{ $kunjungan->fisik?->saturasi_oksigen_persen ?? '' }}"></div>
                <div class="col-md-6"><label class="form-label-ncpms">Defisiensi (pisahkan koma)</label><input name="tanda_defisiensi" class="form-control-ncpms" value="{{ isset($kunjungan->fisik) ? implode(',', $kunjungan->fisik?->tanda_defisiensi ?? []) : '' }}"></div>
                <div class="col-md-6"><label class="form-label-ncpms">Gangguan GI (pisahkan koma)</label><input name="gangguan_gastrointestinal" class="form-control-ncpms" value="{{ isset($kunjungan->fisik) ? implode(',', $kunjungan->fisik?->gangguan_gastrointestinal ?? []) : '' }}"></div>
                <div class="col-12"><label class="form-label-ncpms">Catatan Klinis</label><textarea name="catatan_klinis" class="form-control-ncpms">{{ $kunjungan->fisik?->catatan_klinis ?? '' }}</textarea></div>
                <div class="col-12"><button class="btn-primary-ncpms"><i class="fas fa-save"></i> Simpan Fisik</button></div>
            </form>
            @else
                <div class="text-muted">Vital sign dan pemeriksaan fisik hanya dapat dicatat oleh perawat, dietisien, atau SpGK selama dokumen belum terkunci.</div>
            @endif
        </div>

        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-vial"></i></span> 4. Biokimia</h2>
            @if($bisaBiokimia)
            <form method="POST" action="{{ route('kunjungan.biokimia.store', $kunjungan) }}" class="row g-3">
                @csrf
                <div class="col-md-3"><label class="form-label-ncpms">Tanggal</label><input type="date" name="tanggal_pemeriksaan" class="form-control-ncpms" value="{{ $kunjungan->biokimia?->tanggal_pemeriksaan?->format('Y-m-d') ?? date('Y-m-d') }}"></div>
                <div class="col-md-3"><label class="form-label-ncpms">Sumber</label><select name="sumber_data" class="form-control-ncpms"><option value="lab_internal">Lab Internal</option><option value="input_manual">Input Manual</option><option value="satusehat">SATUSEHAT</option></select></div>
                <div class="col-md-2"><label class="form-label-ncpms">GDP</label><input type="number" step="0.1" name="gula_darah_puasa" class="form-control-ncpms" value="{{ $kunjungan->biokimia?->gula_darah_puasa ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">HbA1c</label><input type="number" step="0.1" name="hba1c_persen" class="form-control-ncpms" value="{{ $kunjungan->biokimia?->hba1c_persen ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Albumin</label><input type="number" step="0.1" name="albumin" class="form-control-ncpms" value="{{ $kunjungan->biokimia?->albumin ?? '' }}"></div>
                <div class="col-12"><label class="form-label-ncpms">Catatan</label><textarea name="catatan_tambahan" class="form-control-ncpms">{{ $kunjungan->biokimia?->catatan_tambahan ?? '' }}</textarea></div>
                <div class="col-12"><button class="btn-primary-ncpms"><i class="fas fa-save"></i> Simpan Biokimia</button></div>
            </form>
            @else
                <div class="text-muted">Data biokimia hanya dapat dicatat oleh dietisien atau SpGK selama dokumen belum terkunci.</div>
            @endif
        </div>

        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-bowl-food"></i></span> 5. Riwayat Asupan</h2>
            @if($bisaAsupan)
            <form method="POST" action="{{ route('kunjungan.asupan.store', $kunjungan) }}" class="row g-3">
                @csrf
                <div class="col-md-3"><label class="form-label-ncpms">Metode</label><select name="metode" class="form-control-ncpms"><option value="food_recall_24h">Food Recall 24 jam</option><option value="food_recall_48h">Food Recall 48 jam</option><option value="food_recall_72h">Food Recall 72 jam</option><option value="ffq_semi_kuantitatif">FFQ Semi Kuantitatif</option></select></div>
                <div class="col-md-3"><label class="form-label-ncpms">Tanggal</label><input type="date" name="tanggal_recall" class="form-control-ncpms" value="{{ $kunjungan->asupan?->tanggal_recall?->format('Y-m-d') ?? date('Y-m-d') }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Energi</label><input type="number" step="0.1" name="total_energi_kkal" class="form-control-ncpms" value="{{ $kunjungan->asupan?->total_energi_kkal ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Protein</label><input type="number" step="0.1" name="total_protein_gram" class="form-control-ncpms" value="{{ $kunjungan->asupan?->total_protein_gram ?? '' }}"></div>
                <div class="col-md-2"><label class="form-label-ncpms">Lemak</label><input type="number" step="0.1" name="total_lemak_gram" class="form-control-ncpms" value="{{ $kunjungan->asupan?->total_lemak_gram ?? '' }}"></div>
                <div class="col-12"><label class="form-label-ncpms">Detail Asupan</label><textarea name="detail_asupan" class="form-control-ncpms" required>{{ $kunjungan->asupan?->detail_asupan[0]['catatan'] ?? '' }}</textarea></div>
                <div class="col-12"><label class="form-label-ncpms">Kesimpulan</label><textarea name="kesimpulan_asupan" class="form-control-ncpms">{{ $kunjungan->asupan?->kesimpulan_asupan ?? '' }}</textarea></div>
                <div class="col-12"><button class="btn-primary-ncpms"><i class="fas fa-save"></i> Simpan Asupan</button></div>
            </form>
            @else
                <div class="text-muted">Riwayat asupan hanya dapat dicatat oleh nutrisionis, dietisien, atau SpGK selama dokumen belum terkunci.</div>
            @endif
        </div>

        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-utensils"></i></span> 6. Detail Menu Harian</h2>
            @forelse($kunjungan->preskripsiDiets as $preskripsi)
                <div class="border rounded-3 p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>{{ number_format($preskripsi->total_kebutuhan_energi_kkal) }} kkal</strong>
                        <span class="text-muted small">{{ $preskripsi->tanggal_mulai?->format('d/m/Y') }}</span>
                    </div>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm align-middle">
                            <thead><tr><th>Waktu</th><th>Bahan</th><th>Porsi</th><th>Energi</th><th>Makro</th></tr></thead>
                            <tbody>
                            @forelse($preskripsi->detailMenuHarians as $menu)
                                <tr>
                                    <td>{{ str_replace('_',' ', $menu->waktu_makan) }}</td>
                                    <td>{{ $menu->bahanMakanan->nama_bahan ?? '-' }}</td>
                                    <td>{{ $menu->porsi_gram }} g</td>
                                    <td>{{ $menu->energi_kkal }} kkal</td>
                                    <td>KH {{ $menu->karbohidrat_gram }}g / P {{ $menu->protein_gram }}g / L {{ $menu->lemak_gram }}g</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-muted">Belum ada detail menu.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($bisaDokumen)
                        <form method="POST" action="{{ route('intervensi.menu.store', $preskripsi) }}" class="row g-3">
                            @csrf
                            <div class="col-md-3"><label class="form-label-ncpms">Waktu</label><select name="waktu_makan" class="form-control-ncpms"><option value="makan_pagi">Makan Pagi</option><option value="selingan_pagi">Selingan Pagi</option><option value="makan_siang">Makan Siang</option><option value="selingan_sore">Selingan Sore</option><option value="makan_malam">Makan Malam</option><option value="selingan_malam">Selingan Malam</option></select></div>
                            <div class="col-md-4"><label class="form-label-ncpms">Bahan Makanan</label><select name="bahan_makanan_id" class="form-control-ncpms">@foreach($bahanMakanans as $bahan)<option value="{{ $bahan->id }}">{{ $bahan->nama_bahan }}</option>@endforeach</select></div>
                            <div class="col-md-2"><label class="form-label-ncpms">Porsi (g)</label><input type="number" step="0.1" name="porsi_gram" class="form-control-ncpms" value="100"></div>
                            <div class="col-md-3"><label class="form-label-ncpms">Keterangan</label><input name="keterangan_penukar" class="form-control-ncpms"></div>
                            <div class="col-12"><button class="btn-primary-ncpms btn-sm-ncpms"><i class="fas fa-plus"></i> Tambah Menu</button></div>
                        </form>
                    @endif
                </div>
            @empty
                <div class="text-muted">Preskripsi diet belum dibuat.</div>
            @endforelse
        </div>

        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-comments"></i></span> 7. Catatan Konseling</h2>
            @if($kunjungan->catatanKonselings->count())
                <div class="mb-3">
                    @foreach($kunjungan->catatanKonselings as $konseling)
                        <div class="border-bottom py-2">
                            <div class="fw-bold">{{ $konseling->tanggal_konseling?->format('d/m/Y') }} - {{ str_replace('_',' ', $konseling->metode) }}</div>
                            <div>{{ $konseling->isi_konseling }}</div>
                            <div class="small text-muted">Oleh {{ $konseling->pelaksana->nama_lengkap ?? '-' }}; pemahaman {{ $konseling->tingkat_pemahaman_pasien ?? '-' }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
            @if($bisaKonseling)
                <form method="POST" action="{{ route('kunjungan.konseling.store', $kunjungan) }}" class="row g-3">
                    @csrf
                    <div class="col-md-3"><label class="form-label-ncpms">Tanggal</label><input type="date" name="tanggal_konseling" class="form-control-ncpms" value="{{ date('Y-m-d') }}"></div>
                    <div class="col-md-2"><label class="form-label-ncpms">Durasi</label><input type="number" name="durasi_menit" class="form-control-ncpms" value="30"></div>
                    <div class="col-md-3"><label class="form-label-ncpms">Metode</label><select name="metode" class="form-control-ncpms"><option value="tatap_muka">Tatap Muka</option><option value="telepon">Telepon</option><option value="video_call">Video Call</option></select></div>
                    <div class="col-md-4"><label class="form-label-ncpms">Pemahaman</label><select name="tingkat_pemahaman_pasien" class="form-control-ncpms"><option value="baik">Baik</option><option value="cukup">Cukup</option><option value="kurang">Kurang</option></select></div>
                    <div class="col-12"><label class="form-label-ncpms">Topik (pisahkan koma)</label><input name="topik_konseling" class="form-control-ncpms" required></div>
                    <div class="col-12"><label class="form-label-ncpms">Isi Konseling</label><textarea name="isi_konseling" class="form-control-ncpms" required></textarea></div>
                    <div class="col-md-6"><label class="form-label-ncpms">Hambatan Pasien</label><textarea name="hambatan_pasien" class="form-control-ncpms"></textarea></div>
                    <div class="col-md-6"><label class="form-label-ncpms">Kesepakatan Tindak Lanjut</label><textarea name="kesepakatan_tindak_lanjut" class="form-control-ncpms"></textarea></div>
                    <div class="col-12"><button class="btn-primary-ncpms"><i class="fas fa-save"></i> Simpan Konseling</button></div>
                </form>
            @else
                <div class="text-muted">Konseling hanya dapat dicatat oleh nutrisionis, dietisien, atau SpGK selama dokumen belum terkunci.</div>
            @endif
        </div>

        <div class="ncpms-card">
            <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-file-medical"></i></span> 8. Dokumen Edukasi</h2>
            @if($kunjungan->dokumenEdukasiis->count())
                <div class="table-responsive mb-3">
                    <table class="table table-sm align-middle">
                        <thead><tr><th>Judul</th><th>Tipe</th><th>Kedaluwarsa</th><th>Pembuat</th></tr></thead>
                        <tbody>
                        @foreach($kunjungan->dokumenEdukasiis as $dokumen)
                            <tr><td>{{ $dokumen->judul_dokumen }}</td><td>{{ str_replace('_',' ', $dokumen->tipe) }}</td><td>{{ $dokumen->token_expired_at?->format('d/m/Y H:i') }}</td><td>{{ $dokumen->pembuat->nama_lengkap ?? '-' }}</td></tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            @if($bisaDokumen)
                <form method="POST" action="{{ route('kunjungan.dokumen-edukasi.store', $kunjungan) }}" class="row g-3">
                    @csrf
                    <div class="col-md-5"><label class="form-label-ncpms">Judul</label><input name="judul_dokumen" class="form-control-ncpms" value="Rencana Makan dan Edukasi Gizi" required></div>
                    <div class="col-md-4"><label class="form-label-ncpms">Tipe</label><select name="tipe" class="form-control-ncpms"><option value="rencana_makan">Rencana Makan</option><option value="ringkasan_kalori">Ringkasan Kalori</option><option value="pantangan_alergi">Pantangan Alergi</option><option value="panduan_makan">Panduan Makan</option><option value="leaflet_diet">Leaflet Diet</option></select></div>
                    <div class="col-md-3"><label class="form-label-ncpms">Token Expired</label><input type="date" name="token_expired_at" class="form-control-ncpms" value="{{ now()->addDays(7)->format('Y-m-d') }}"></div>
                    <div class="col-12"><label class="form-label-ncpms">Ringkasan Konten</label><textarea name="ringkasan" class="form-control-ncpms" required></textarea></div>
                    <div class="col-12"><button class="btn-accent-ncpms"><i class="fas fa-file-circle-plus"></i> Buat Dokumen</button></div>
                </form>
            @else
                <div class="text-muted">Dokumen edukasi hanya dapat dibuat oleh dietisien atau SpGK selama dokumen belum terkunci.</div>
            @endif
        </div>
    </div>
</div>
@endsection
