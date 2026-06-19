<?php
namespace App\Services;

class AntropometriService
{
    public function hitungIMT($bb_kg, $tb_cm)
    {
        if ($tb_cm <= 0) return 0;
        $tb_m = $tb_cm / 100;
        return round($bb_kg / ($tb_m * $tb_m), 2);
    }

    public function kategoriIMT($imt)
    {
        if ($imt < 17.0) return 'sangat_kurus';
        if ($imt < 18.5) return 'kurus';
        if ($imt <= 25.0) return 'normal';
        if ($imt <= 27.0) return 'gemuk';
        if ($imt <= 30.0) return 'obesitas_1';
        return 'obesitas_2';
    }

    public function beratBadanIdeal($tb_cm, $jenisKelamin)
    {
        $dasar = $tb_cm - 100;
        $koreksi = $jenisKelamin === 'L' ? 0.9 : 0.85;
        return round($dasar * $koreksi, 2);
    }

    public function labelStatus($status)
    {
        return [
            'sangat_kurus' => 'Sangat kurus',
            'kurus' => 'Kurus',
            'normal' => 'Normal',
            'gemuk' => 'Gemuk',
            'obesitas_1' => 'Obesitas I',
            'obesitas_2' => 'Obesitas II',
        ][$status] ?? $status;
    }
}
