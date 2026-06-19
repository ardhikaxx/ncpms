<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SatuSehatService
{
    public function syncPatient($pasien) {
        // Mock API integration with SATUSEHAT FHIR
        Log::info('Syncing patient to SATUSEHAT', ['nik' => decrypt($pasien->nik)]);
        return 'P' . strtoupper(substr(md5(time()), 0, 8));
    }
    public function syncEncounter($kunjungan) {
        Log::info('Syncing encounter to SATUSEHAT', ['kunjungan_id' => $kunjungan->id]);
        return 'E' . strtoupper(substr(md5(time()), 0, 8));
    }
}
