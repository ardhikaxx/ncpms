<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Kunjungan;
use App\Models\PreskripsiDiet;

class DashboardController extends Controller
{
    public function index() {
        $totalPasien = Pasien::count();
        $totalKunjunganHariIni = Kunjungan::whereDate('tanggal_kunjungan', today())->count();
        $totalPreskripsiAktif = PreskripsiDiet::where('status', 'aktif')->count();
        
        $kunjungans = Kunjungan::with('pasien')->whereDate('tanggal_kunjungan', today())->get();

        return view('dashboard.index', compact('totalPasien', 'totalKunjunganHariIni', 'totalPreskripsiAktif', 'kunjungans'));
    }
}
