<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pasien - NCPMS</title>
    <style>
        @page { margin: 120px 40px 60px 40px; }
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            color: var(--color-primary); 
            font-size: 10pt;
            line-height: 1.5;
        }
        header { 
            position: fixed; 
            top: -90px; 
            left: 0px; 
            right: 0px; 
            height: 70px; 
            border-bottom: 3px solid #1A7A64;
            padding-bottom: 10px;
        }
        .header-logo {
            float: left;
            width: 50%;
        }
        .header-logo h1 {
            color: var(--color-primary);
            font-size: 24pt;
            margin: 0;
            letter-spacing: 1px;
        }
        .header-logo p {
            color: var(--color-primary);
            font-size: 9pt;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-info {
            float: right;
            width: 50%;
            text-align: right;
            padding-top: 15px;
        }
        .header-info p {
            margin: 0;
            font-size: 9pt;
            color: var(--color-primary);
        }
        footer { 
            position: fixed; 
            bottom: -40px; 
            left: 0px; 
            right: 0px; 
            height: 30px; 
            border-top: 1px solid #D4E6E1;
            padding-top: 5px;
            font-size: 8pt;
            color: var(--color-primary);
        }
        .footer-left { float: left; }
        .footer-right { float: right; }
        .page-number:before { content: counter(page); }
        
        h2.document-title {
            text-align: center;
            color: var(--color-primary);
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 16pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th {
            background-color: var(--color-primary);
            color: var(--color-primary);
            font-weight: bold;
            padding: 10px 12px;
            text-align: left;
            font-size: 9pt;
            text-transform: uppercase;
        }
        table.data-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #EAF2EF;
            font-size: 9.5pt;
        }
        table.data-table tr:nth-child(even) {
            background-color: var(--color-primary);
        }
        .badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: bold;
        }
        .badge-l { background-color: var(--color-primary); color: var(--color-primary); }
        .badge-p { background-color: var(--color-primary); color: var(--color-primary); }
        .text-mono { font-family: 'Courier New', Courier, monospace; font-size: 9pt; }
        .meta-summary {
            background-color: var(--color-primary);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 9pt;
            border-left: 4px solid var(--color-primary);
        }
    </style>
</head>
<body>
    <header>
        <div class="header-logo">
            <h1>NCPMS</h1>
            <p>Nutrition Care & Patient Management</p>
        </div>
        <div class="header-info">
            <p><strong>Dicetak pada:</strong> {{ now()->format('d F Y, H:i') }}</p>
            <p><strong>Oleh:</strong> {{ Auth::user()->nama_lengkap }}</p>
        </div>
    </header>

    <footer>
        <div class="footer-left">
            &copy; {{ date('Y') }} Divisi TI & Sistem Informasi Gizi Klinis NCPMS. Dokumen ini bersifat Rahasia.
        </div>
        <div class="footer-right">
            Halaman <span class="page-number"></span>
        </div>
    </footer>

    <main>
        <h2 class="document-title">Direktori Master Data Pasien</h2>
        
        <div class="meta-summary">
            <strong>Ringkasan Dokumen:</strong><br>
            Menampilkan data {{ count($pasiens) }} pasien terbaru yang terdaftar dalam sistem rekam medis gizi.
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th width="20%">No. Rekam Medis</th>
                    <th width="35%">Nama Pasien</th>
                    <th width="20%">Tanggal Lahir</th>
                    <th width="10%">Usia</th>
                    <th width="10%">Gender</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pasiens as $index => $p)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td class="text-mono"><strong>{{ $p->nomor_rekam_medis }}</strong></td>
                    <td>{{ $p->nama_lengkap }}</td>
                    <td>{{ $p->tanggal_lahir?->format('d/m/Y') }}</td>
                    <td>{{ $p->tanggal_lahir?->age }} thn</td>
                    <td align="center">
                        <span class="badge {{ $p->jenis_kelamin === 'L' ? 'badge-l' : 'badge-p' }}">
                            {{ $p->jenis_kelamin }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>
</html>