<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LaporanStatistik;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateLaporanBulananJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        Log::info('Memulai generasi laporan bulanan NCPMS...');
        
        $start = Carbon::now()->subMonth()->startOfMonth();
        $end = Carbon::now()->subMonth()->endOfMonth();

        LaporanStatistik::create([
            'tipe_laporan' => 'kinerja_harian',
            'periode_dari' => $start->toDateString(),
            'periode_sampai' => $end->toDateString(),
            'parameter' => json_encode(['otomatis' => true]),
            'data_laporan' => json_encode([
                'kunjungan_total' => \App\Models\Kunjungan::whereBetween('tanggal_kunjungan', [$start, $end])->count(),
            ]),
            'dibuat_oleh' => null, // Sistem
        ]);

        Log::info('Laporan bulanan berhasil digenerate.');
    }
}
