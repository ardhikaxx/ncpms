<?php
namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Pasien;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportPasienPdf()
    {
        $pasiens = Pasien::latest()->take(100)->get();
        $pdf = Pdf::loadView('exports.pasien_pdf', compact('pasiens'));
        return $pdf->download('Data_Pasien_NCPMS.pdf');
    }

    public function cetakPagt(Kunjungan $kunjungan)
    {
        $kunjungan->load(['pasien', 'skriningGizi', 'antropometri', 'biokimia', 'fisik', 'asupan', 'diagnosaGizis', 'preskripsiDiets', 'monitoring']);
        $pdf = Pdf::loadView('exports.pagt_pdf', compact('kunjungan'));
        return $pdf->download('Resume_PAGT_'.$kunjungan->nomor_kunjungan.'.pdf');
    }

    public function exportLaporanExcel()
    {
        $pasiens = Pasien::with('kunjungans')->latest()->take(100)->get();
        $writer = SimpleExcelWriter::streamDownload('Laporan_Pasien_NCPMS.xlsx');
        
        foreach ($pasiens as $p) {
            $writer->addRow([
                'NRM' => $p->nomor_rekam_medis,
                'Nama' => $p->nama_lengkap,
                'Usia' => $p->tanggal_lahir?->age,
                'Jenis Kelamin' => $p->jenis_kelamin,
                'Total Kunjungan' => $p->kunjungans->count(),
            ]);
        }
        
        return $writer->toBrowser();
    }
}
