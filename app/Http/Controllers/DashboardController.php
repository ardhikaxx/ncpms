<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Kunjungan;
use App\Models\PreskripsiDiet;
use App\Models\SkriningGizi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
        $totalPasien = Pasien::count();
        $totalKunjunganHariIni = Kunjungan::whereDate('tanggal_kunjungan', today())->count();
        $risikoTinggi = SkriningGizi::where('kategori_risiko', 'risiko_tinggi')
            ->whereHas('kunjungan', fn ($q) => $q->whereDate('tanggal_kunjungan', today()))
            ->count();
        $menungguAsesmen = Kunjungan::doesntHave('antropometri')->whereIn('status', ['terdaftar', 'dalam_pelayanan'])->count();
        $totalPreskripsiAktif = PreskripsiDiet::where('status', 'aktif')->count();
        
        $kunjungans = Kunjungan::with(['pasien', 'skriningGizi', 'dietisien'])
            ->whereDate('tanggal_kunjungan', today())
            ->latest('waktu_registrasi')
            ->get();

        $grafikKunjungan = collect(range(6, 0))->map(function ($mundur) {
            $tanggal = today()->subDays($mundur);
            return [
                'label' => $tanggal->format('d/m'),
                'total' => Kunjungan::whereDate('tanggal_kunjungan', $tanggal)->count(),
            ];
        });

        return view('dashboard.index', compact(
            'totalPasien',
            'totalKunjunganHariIni',
            'risikoTinggi',
            'menungguAsesmen',
            'totalPreskripsiAktif',
            'kunjungans',
            'grafikKunjungan'
        ));
    }
}
