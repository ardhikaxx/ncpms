<?php
namespace App\Services;

class KalkulasiKaloriService
{
    public function hitungKebutuhanBasal($formula, $bb, $tb, $usia, $gender)
    {
        $bb = (float) $bb;
        $tb = (float) $tb;
        $usia = (int) $usia;

        return match ($formula) {
            'harris_benedict' => $gender === 'L'
                ? 66.5 + (13.75 * $bb) + (5.003 * $tb) - (6.75 * $usia)
                : 655.1 + (9.563 * $bb) + (1.850 * $tb) - (4.676 * $usia),
            'mifflin_st_jeor' => $gender === 'L'
                ? (10 * $bb) + (6.25 * $tb) - (5 * $usia) + 5
                : (10 * $bb) + (6.25 * $tb) - (5 * $usia) - 161,
            'who' => $this->hitungWho($bb, $usia, $gender),
            'konsensus_dm', 'konsensus_ckd' => 25 * $bb,
            default => 0,
        };
    }

    public function hitungTotalKebutuhan($keb, $faktorAktivitas, $faktorStres)
    {
        return round($keb * $faktorAktivitas * $faktorStres, 2);
    }

    public function distribusiMakro($totalEnergi, $persenKarbohidrat, $persenProtein, $persenLemak)
    {
        return [
            'gram_karbohidrat' => round(($totalEnergi * $persenKarbohidrat / 100) / 4, 2),
            'gram_protein' => round(($totalEnergi * $persenProtein / 100) / 4, 2),
            'gram_lemak' => round(($totalEnergi * $persenLemak / 100) / 9, 2),
        ];
    }

    private function hitungWho($bb, $usia, $gender)
    {
        if ($gender === 'L') {
            return $usia < 30 ? (15.3 * $bb) + 679 : (($usia < 60) ? (11.6 * $bb) + 879 : (13.5 * $bb) + 487);
        }

        return $usia < 30 ? (14.7 * $bb) + 496 : (($usia < 60) ? (8.7 * $bb) + 829 : (10.5 * $bb) + 596);
    }
}
