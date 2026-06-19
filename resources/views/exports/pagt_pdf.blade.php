<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resume PAGT</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: var(--color-primary); line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #1A7A64; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: var(--color-primary); font-size: 20px; text-transform: uppercase; }
        .header p { margin: 2px 0; color: var(--color-primary); }
        .section-title { background: #1A7A64; color: white; padding: 5px 10px; font-weight: bold; margin-top: 15px; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f4f8f7; color: var(--color-primary); width: 30%; }
        .mb-2 { margin-bottom: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>RESUME ASUHAN GIZI KLINIS (PAGT/ADIME)</h1>
        <p>No. Kunjungan: <strong>{{ $kunjungan->nomor_kunjungan }}</strong> | Tanggal: <strong>{{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d/m/Y') }}</strong></p>
    </div>

    <div class="section-title">A. IDENTITAS PASIEN</div>
    <table>
        <tr><th>Nama Lengkap</th><td>{{ $kunjungan->pasien->nama_lengkap }}</td></tr>
        <tr><th>No. Rekam Medis</th><td>{{ $kunjungan->pasien->nomor_rekam_medis }}</td></tr>
        <tr><th>Tanggal Lahir / Usia</th><td>{{ \Carbon\Carbon::parse($kunjungan->pasien->tanggal_lahir)->format('d/m/Y') }} ({{ \Carbon\Carbon::parse($kunjungan->pasien->tanggal_lahir)->age }} tahun)</td></tr>
        <tr><th>Jenis Kelamin</th><td>{{ $kunjungan->pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
        <tr><th>Diagnosis Medis Utama</th><td>{{ $kunjungan->diagnosisMedisUtama?->nama_penyakit ?? '-' }}</td></tr>
    </table>

    <div class="section-title">B. ASESMEN GIZI (ASSESSMENT)</div>
    <table>
        <tr>
            <th>Antropometri</th>
            <td>
                @if($kunjungan->dataAntropometris->count())
                    @foreach($kunjungan->dataAntropometris as $a)
                        BB: {{ $a->berat_badan_kg }} kg, TB: {{ $a->tinggi_badan_cm }} cm, LILA: {{ $a->lila_cm ?? '-' }} cm<br>
                    @endforeach
                @else
                    Belum ada data.
                @endif
            </td>
        </tr>
        <tr>
            <th>Biokimia</th>
            <td>
                @if($kunjungan->dataBiokimias->count())
                    @foreach($kunjungan->dataBiokimias as $b)
                        Hb: {{ $b->hemoglobin ?? '-' }}, Albumin: {{ $b->albumin ?? '-' }}, GDS: {{ $b->gula_darah_sewaktu ?? '-' }}<br>
                    @endforeach
                @else
                    Belum ada data.
                @endif
            </td>
        </tr>
        <tr>
            <th>Klinis/Fisik</th>
            <td>
                @if($kunjungan->pemeriksaanFisikGizis->count())
                    @foreach($kunjungan->pemeriksaanFisikGizis as $f)
                        TD: {{ $f->tekanan_darah_sistolik }}/{{ $f->tekanan_darah_diastolik }} mmHg, Suhu: {{ $f->suhu_tubuh_celsius }} °C<br>
                        Kesan: {{ $f->kesan_umum }}
                    @endforeach
                @else
                    Belum ada data.
                @endif
            </td>
        </tr>
        <tr>
            <th>Riwayat Asupan</th>
            <td>
                @if($kunjungan->riwayatAsupanGizis->count())
                    @foreach($kunjungan->riwayatAsupanGizis as $r)
                        Energi: {{ $r->total_energi_kkal }} kkal, Protein: {{ $r->total_protein_gram }} g<br>
                        Alergi Makanan: {{ implode(', ', json_decode($r->alergi_makanan_json ?? '[]', true) ?: ['Tidak ada']) }}
                    @endforeach
                @else
                    Belum ada data.
                @endif
            </td>
        </tr>
    </table>

    <div class="section-title">C. DIAGNOSIS GIZI (DIAGNOSIS)</div>
    <table>
        @forelse($kunjungan->diagnosaGizis as $d)
        <tr>
            <th>Format PES ({{ $d->status }})</th>
            <td><strong>{{ \Illuminate\Support\Facades\Crypt::decryptString($d->narasi_pes) }}</strong></td>
        </tr>
        @empty
        <tr><td colspan="2">Belum ada diagnosis gizi dicatat.</td></tr>
        @endforelse
    </table>

    <div class="section-title">D. INTERVENSI GIZI (INTERVENTION)</div>
    <table>
        @forelse($kunjungan->preskripsiDiets as $p)
        <tr>
            <th>Jenis Diet</th>
            <td>{{ $p->tujuan_terapi }} ({{ $p->bentuk_makanan }})</td>
        </tr>
        <tr>
            <th>Target Asupan</th>
            <td>Energi: {{ $p->total_kebutuhan_energi_kkal }} kkal, Protein: {{ $p->gram_protein }} g, Lemak: {{ $p->gram_lemak }} g, Karbohidrat: {{ $p->gram_karbohidrat }} g</td>
        </tr>
        <tr>
            <th>Frekuensi Makan</th>
            <td>Utama: {{ $p->frekuensi_makan_utama }}x, Selingan: {{ $p->frekuensi_selingan }}x</td>
        </tr>
        @empty
        <tr><td colspan="2">Belum ada preskripsi diet diberikan.</td></tr>
        @endforelse
    </table>

    <div class="section-title">E. MONITORING & EVALUASI (MONITORING & EVALUATION)</div>
    <table>
        @forelse($kunjungan->monitorings as $m)
        <tr>
            <th>Tanggal Evaluasi</th>
            <td>{{ \Carbon\Carbon::parse($m->created_at)->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <th>Kepatuhan / Asupan</th>
            <td>{{ str_replace('_', ' ', strtoupper($m->evaluasi_kepatuhan_diet)) }} - {{ $m->evaluasi_asupan }}</td>
        </tr>
        <tr>
            <th>Kesimpulan</th>
            <td>{{ $m->kesimpulan }}</td>
        </tr>
        @empty
        <tr><td colspan="2">Belum ada catatan evaluasi.</td></tr>
        @endforelse
    </table>

    <div style="margin-top: 40px; text-align: right; font-size: 11px;">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} oleh {{ auth()->user()->nama_lengkap ?? 'Sistem' }}
    </div>

</body>
</html>
