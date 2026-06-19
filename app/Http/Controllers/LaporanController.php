<?php

namespace App\Http\Controllers;

use App\Models\DiagnosaGizi;
use App\Models\Kunjungan;
use App\Models\LaporanStatistik;
use App\Models\Monitoring;
use App\Models\Pasien;
use App\Models\PreskripsiDiet;
use App\Models\SkriningGizi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dari = $request->date('periode_dari') ?? today()->startOfMonth();
        $sampai = $request->date('periode_sampai') ?? today();

        $ringkasan = [
            'kunjungan' => Kunjungan::whereBetween('tanggal_kunjungan', [$dari, $sampai])->count(),
            'pasien_baru' => Pasien::whereBetween('created_at', [$dari->copy()->startOfDay(), $sampai->copy()->endOfDay()])->count(),
            'risiko_tinggi' => SkriningGizi::where('kategori_risiko', 'risiko_tinggi')
                ->whereHas('kunjungan', fn ($q) => $q->whereBetween('tanggal_kunjungan', [$dari, $sampai]))
                ->count(),
            'diagnosis_aktif' => DiagnosaGizi::where('status', 'aktif')->count(),
            'preskripsi_aktif' => PreskripsiDiet::where('status', 'aktif')->count(),
            'monitoring' => Monitoring::whereBetween('created_at', [$dari->copy()->startOfDay(), $sampai->copy()->endOfDay()])->count(),
        ];

        $laporan = LaporanStatistik::create([
            'tipe_laporan' => $request->input('tipe_laporan', 'kinerja_harian'),
            'periode_dari' => $dari,
            'periode_sampai' => $sampai,
            'parameter' => $request->only('tipe_laporan'),
            'data_laporan' => $ringkasan,
            'dibuat_oleh' => Auth::id(),
        ]);

        return view('laporan.index', compact('ringkasan', 'laporan', 'dari', 'sampai'));
    }
}
