<?php
namespace App\Policies;
use App\Models\Pengguna;
use App\Models\Kunjungan;

class KunjunganPolicy
{
    public function update(Pengguna $user, Kunjungan $kunjungan) {
        if ($kunjungan->dokumen_terkunci) return false;
        return in_array($user->peran, ['perawat', 'dietisien', 'spgk', 'nutrisionis']);
    }
    public function lock(Pengguna $user, Kunjungan $kunjungan) {
        return $user->peran === 'spgk';
    }
}
