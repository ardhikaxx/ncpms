<?php
namespace App\Services;

use App\Models\LoginHistory;
use Illuminate\Support\Facades\DB;

class AuditService
{
    public function ringkasanAktivitas($dari, $sampai): array
    {
        return [
            'login' => LoginHistory::where('tipe_event', 'login')
                ->whereBetween('created_at', [$dari->copy()->startOfDay(), $sampai->copy()->endOfDay()])
                ->count(),
            'logout' => LoginHistory::where('tipe_event', 'logout')
                ->whereBetween('created_at', [$dari->copy()->startOfDay(), $sampai->copy()->endOfDay()])
                ->count(),
            'login_gagal' => LoginHistory::where('tipe_event', 'login_gagal')
                ->whereBetween('created_at', [$dari->copy()->startOfDay(), $sampai->copy()->endOfDay()])
                ->count(),
            'timeout' => LoginHistory::where('tipe_event', 'timeout')
                ->whereBetween('created_at', [$dari->copy()->startOfDay(), $sampai->copy()->endOfDay()])
                ->count(),
            'perubahan_rekam_medis' => DB::table('audits')
                ->whereBetween('created_at', [$dari->copy()->startOfDay(), $sampai->copy()->endOfDay()])
                ->count(),
        ];
    }
}
