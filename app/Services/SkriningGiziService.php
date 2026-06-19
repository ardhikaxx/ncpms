<?php
namespace App\Services;

class SkriningGiziService
{
    public function hitungTotal(array $data): int
    {
        return (int) ($data['skor_penurunan_bb'] ?? 0)
            + (int) ($data['skor_penurunan_asupan'] ?? 0)
            + (int) ($data['skor_keparahan_penyakit'] ?? 0)
            + (int) ($data['skor_usia'] ?? 0);
    }

    public function kategoriRisiko(int $totalSkor): string
    {
        if ($totalSkor >= 4) {
            return 'risiko_tinggi';
        }

        if ($totalSkor >= 2) {
            return 'risiko_sedang';
        }

        return 'risiko_rendah';
    }

    public function rekomendasi(string $kategori): string
    {
        return match ($kategori) {
            'risiko_tinggi' => 'Asesmen gizi komprehensif pada hari yang sama dan prioritas antrean.',
            'risiko_sedang' => 'Lanjutkan asesmen gizi dan konseling sesuai antrean.',
            default => 'Konseling rutin dan pemantauan berkala.',
        };
    }
}
