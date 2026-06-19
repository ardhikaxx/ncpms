<?php
namespace App\Policies;
use App\Models\Pengguna;
use App\Models\Pasien;
use Illuminate\Auth\Access\HandlesAuthorization;

class PasienPolicy
{
    use HandlesAuthorization;

    public function viewAny(Pengguna $user) { return in_array($user->peran, ['perawat', 'nutrisionis', 'dietisien', 'spgk']); }
    public function view(Pengguna $user, Pasien $pasien) { return in_array($user->peran, ['perawat', 'nutrisionis', 'dietisien', 'spgk']); }
    public function create(Pengguna $user) { return in_array($user->peran, ['perawat', 'spgk']); }
    public function update(Pengguna $user, Pasien $pasien) { return in_array($user->peran, ['perawat', 'spgk']); }
    public function delete(Pengguna $user, Pasien $pasien) { return $user->peran === 'spgk'; }
}
