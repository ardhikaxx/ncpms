@extends('layouts.app')
@section('title','Catatan Terintegrasi (CPPT)')
@section('breadcrumb','Pasien / Detail / CPPT')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Catatan Perkembangan Pasien Terintegrasi (CPPT)</h1>
        <p class="page-subtitle">{{ $pasien->nama_lengkap }} ({{ $pasien->nomor_rekam_medis }}) &bull; {{ $pasien->tanggal_lahir?->age }} tahun</p>
    </div>
    <a href="{{ route('pasien.show', $pasien) }}" class="btn-ncpms-outline"><i class="fas fa-arrow-left"></i> Kembali ke Profil</a>
</div>

<div class="ncpms-card mb-4" style="background: #f8fafc;">
    <div class="card-title-custom mb-3"><i class="fas fa-info-circle" style="color: var(--color-primary);"></i> Informasi Integrasi RME</div>
    <p class="text-muted mb-0" style="font-size: 0.9rem;">
        Catatan di bawah ini menggunakan metode penulisan terintegrasi (S-O-A-P / ADIME) antar PPA (Profesional Pemberi Asuhan). 
        Dokumen yang bertanda <i class="fas fa-certificate text-success"></i> telah diverifikasi dengan <strong>Tanda Tangan Elektronik (TTE) Tersertifikasi</strong> sesuai Permenkes No. 24 Tahun 2022.
    </p>
</div>

<div class="timeline-cppt">
    @forelse($pasien->kunjungans as $k)
    <div class="ncpms-card mb-4" style="border-left: 4px solid var(--color-primary);">
        <div class="d-flex justify-content-between align-items-start mb-3 pb-3" style="border-bottom: 1px solid var(--color-border);">
            <div>
                <h6 class="fw-bold mb-1"><i class="fas fa-calendar-alt text-muted me-2"></i>{{ $k->tanggal_kunjungan->format('l, d F Y') }} <span class="badge-pill badge-soft-gray ms-2">{{ $k->nomor_kunjungan }}</span></h6>
                <div class="text-muted" style="font-size: 0.85rem;">Diagnosis Medis: <strong>{{ $k->diagnosisMedisUtama->nama_diagnosis ?? '-' }}</strong></div>
            </div>
            @if($k->dokumen_terkunci)
            <div class="text-end">
                <span class="badge-pill badge-soft-success mb-1" style="font-size: 0.75rem;"><i class="fas fa-certificate"></i> TTE TERSERTIFIKASI</span>
                <div style="font-size: 0.7rem; color: #64748b;">Oleh: dr. {{ $k->spgk->nama_lengkap ?? 'SpGK' }}<br>{{ $k->dikunci_pada?->format('d/m/Y H:i') }}</div>
            </div>
            @else
            <span class="badge-pill badge-soft-warning" style="font-size: 0.75rem;"><i class="fas fa-exclamation-circle"></i> BELUM DIVERIFIKASI (DRAFT)</span>
            @endif
        </div>

        {{-- Perawat (Skrining & TTV) --}}
        @if($k->skriningGizi || $k->fisik)
        <div class="mb-3">
            <div class="fw-bold text-primary mb-2" style="font-size: 0.9rem;"><i class="fas fa-user-nurse me-1"></i> Perawat / Pengkajian Awal</div>
            <div class="p-3 rounded" style="background: #f1f5f9; font-size: 0.85rem;">
                @if($k->skriningGizi)
                <strong>Skrining Gizi ({{ $k->skriningGizi->metode_skrining }}):</strong> Risiko {{ str_replace('_',' ',$k->skriningGizi->kategori_risiko) }} (Skor: {{ $k->skriningGizi->total_skor }}). <br>
                @endif
                @if($k->fisik)
                <strong>TTV:</strong> TD {{ $k->fisik->tekanan_darah_sistolik }}/{{ $k->fisik->tekanan_darah_diastolik }} mmHg, Nadi {{ $k->fisik->nadi_per_menit }}x/mnt. Kondisi Mulut: {{ $k->fisik->kondisi_mulut }}.
                @endif
            </div>
        </div>
        @endif

        {{-- Dietisien (ADIME) --}}
        <div class="mb-3">
            <div class="fw-bold text-success mb-2" style="font-size: 0.9rem;"><i class="fas fa-user-md me-1"></i> Dietisien / Asuhan Gizi (ADIME)</div>
            <div class="p-3 rounded" style="background: #f0fdf4; font-size: 0.85rem;">
                <strong>A (Assessment):</strong> 
                @if($k->antropometri)
                BB: {{ $k->antropometri->berat_badan_kg }} kg, TB: {{ $k->antropometri->tinggi_badan_cm }} cm, IMT: {{ $k->antropometri->imt }} ({{ str_replace('_',' ',$k->antropometri->status_gizi_imt) }}).
                @endif
                @if($k->biokimia)
                Data Biokimia tercatat.
                @endif
                @if($k->asupan)
                Asupan ({{ str_replace('_',' ',$k->asupan->metode) }}): {{ $k->asupan->kesimpulan_asupan ?? 'Belum ada kesimpulan' }}.
                @endif
                <br><br>

                <strong>D (Diagnosis):</strong><br>
                @forelse($k->diagnosaGizis as $dx)
                - {{ $dx->problem_masalah }} berkaitan dengan {{ $dx->etiologi_penyebab }} dibuktikan dengan {{ $dx->signs_symptoms }}.<br>
                @empty
                Belum ada diagnosis gizi.<br>
                @endforelse
                <br>

                <strong>I (Intervensi):</strong><br>
                @if($k->preskripsiDiets->count())
                Diet Oral: {{ $k->preskripsiDiets->first()->total_kebutuhan_energi_kkal }} kkal, Bentuk Makanan: {{ str_replace('_',' ',$k->preskripsiDiets->first()->bentuk_makanan) }}.
                @endif
                @if($k->preskripsiKritis)
                Diet {{ ucfirst($k->preskripsiKritis->jenis_nutrisi) }}: {{ $k->preskripsiKritis->nama_formula }} ({{ $k->preskripsiKritis->volume_ml }}ml), {{ $k->preskripsiKritis->total_kalori_kkal }} kkal.
                @endif
                <br><br>

                <strong>M & E (Monitoring & Evaluasi):</strong><br>
                @if($k->monitoring)
                Kepatuhan: {{ str_replace('_',' ',$k->monitoring->evaluasi_kepatuhan_diet) }}. Kesimpulan: {{ $k->monitoring->kesimpulan }}
                @else
                Belum ada evaluasi.
                @endif
            </div>
        </div>
        
    </div>
    @empty
    <div class="text-center p-5 ncpms-card text-muted">Belum ada riwayat CPPT untuk pasien ini.</div>
    @endforelse
</div>
@endsection
