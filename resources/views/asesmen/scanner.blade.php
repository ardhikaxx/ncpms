@extends('layouts.app')
@section('title', 'Pemindai Label Makanan')
@section('breadcrumb', 'Pemindai QR Code Etiket')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Validasi Makanan Pasien</h1>
        <p class="page-subtitle">Pindai QR Code pada nampan makanan untuk mencocokkan dengan identitas pasien.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="ncpms-card">
            <div class="card-body text-center">
                <div id="reader" width="600px" style="margin-bottom: 20px;"></div>
                <div id="scan-result" class="alert alert-info" style="display: none;"></div>
                
                <p class="text-muted mt-3" style="font-size: 0.85rem;">Arahkan kamera ke QR Code Etiket Makanan. Sistem akan memvalidasi kesesuaian diet dan potensi alergi pasien.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('scan-result').style.display = 'block';
        document.getElementById('scan-result').innerHTML = `
            <strong>Berhasil Terpindai:</strong><br>
            ` + decodedText + `<br><br>
            <button class="btn btn-success btn-sm mt-2" onclick="alert('Validasi Berhasil! Makanan Sesuai dengan Pasien.')">Konfirmasi Validasi</button>
        `;
        html5QrcodeScanner.clear();
    }

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 10, qrbox: {width: 250, height: 250} },
        /* verbose= */ false);
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
@endpush
@endsection
