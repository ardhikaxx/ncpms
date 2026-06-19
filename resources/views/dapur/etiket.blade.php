<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Etiket Makan</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; margin: 0; padding: 0; background: #fff; color: #000; }
        .label-container { width: 300px; padding: 15px; border: 2px dashed #333; margin: 20px auto; }
        .hospital-name { text-align: center; font-size: 16px; font-weight: bold; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 10px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 14px; }
        .fw-bold { font-weight: bold; }
        .large-text { font-size: 18px; font-weight: bold; text-align: center; margin: 15px 0; border: 1px solid #000; padding: 5px; }
        .alert-text { color: red; font-weight: bold; border: 2px solid red; padding: 5px; text-align: center; margin-top: 10px; }
        .footer { text-align: center; font-size: 11px; margin-top: 15px; border-top: 1px dashed #333; padding-top: 5px; }
        @media print {
            body { margin: 0; padding: 0; }
            .label-container { border: none; margin: 0; width: 100%; padding: 5px; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="label-container">
    <div class="hospital-name">RS BUMI SEHAT</div>
    <div style="text-align: center; font-size: 12px; margin-bottom: 15px;">INSTALASI GIZI</div>

    <div class="row">
        <span>Nama:</span>
        <span class="fw-bold">{{ strtoupper(substr($preskripsi->kunjungan->pasien->nama_lengkap, 0, 18)) }}</span>
    </div>
    <div class="row">
        <span>NRM:</span>
        <span class="fw-bold">{{ $preskripsi->kunjungan->pasien->nomor_rekam_medis }}</span>
    </div>
    <div class="row">
        <span>Kamar:</span>
        <span class="fw-bold">R. INAP (KJG-{{ substr($preskripsi->kunjungan->nomor_kunjungan, -4) }})</span>
    </div>

    <div class="large-text">
        {{ strtoupper(str_replace('_', ' ', $preskripsi->bentuk_makanan)) }}<br>
        <span style="font-size: 14px;">{{ $preskripsi->tujuan_terapi }}</span>
    </div>

    <div class="row" style="justify-content: center; font-size: 12px;">
        {{ $preskripsi->total_kebutuhan_energi_kkal }} kkal | Pro: {{ $preskripsi->gram_protein }}g | Na: {{ $preskripsi->batas_natrium_mg ?? '-' }}mg
    </div>

    @if($preskripsi->pantangan_spesifik)
    <div class="alert-text">
        ⚠ ALERGI / PANTANGAN ⚠<br>
        {{ strtoupper($preskripsi->pantangan_spesifik) }}
    </div>
    @endif

    <div class="footer">
        Dietisien: {{ $preskripsi->dibuat_oleh_nama ?? 'Sistem' }}<br>
        Dicetak: {{ now()->format('d/m/Y H:i') }}
    </div>
</div>

</body>
</html>
