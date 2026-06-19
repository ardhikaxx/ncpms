<?php
namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Kunjungan;
use App\Models\PreskripsiDiet;
use App\Models\SkriningGizi;
use App\Models\LoginHistory;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        if (Auth::user()->peran === 'admin_ti') {
            return view('dashboard.admin', [
                'totalPengguna' => Pengguna::withTrashed()->count(),
                'penggunaAktif' => Pengguna::where('status_aktif', true)->count(),
                'loginHariIni' => LoginHistory::where('tipe_event', 'login')->whereDate('created_at', today())->count(),
                'loginGagalHariIni' => LoginHistory::where('tipe_event', 'login_gagal')->whereDate('created_at', today())->count(),
                'timeoutHariIni' => LoginHistory::where('tipe_event', 'timeout')->whereDate('created_at', today())->count(),
                'loginTerakhir' => LoginHistory::with('pengguna')->latest()->limit(10)->get(),
            ]);
        }

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
            ->paginate(5);

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
