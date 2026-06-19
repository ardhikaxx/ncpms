<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengguna extends Model implements \OwenIt\Auditing\Contracts\Auditable, \Illuminate\Contracts\Auth\Authenticatable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable, \Illuminate\Auth\Authenticatable;

    protected $table = 'penggunas';
    protected $fillable = ['nama_lengkap', 'email', 'password', 'peran', 'nomor_sip', 'nomor_str', 'unit_kerja', 'status_aktif', 'last_login_at', 'last_login_ip'];
    protected $casts = ['password' => 'hashed'];
    protected $hidden = ['password'];

    public function getNamaPeranAttribute()
    {
        return [
            'spgk' => 'Dokter Spesialis Gizi Klinik',
            'dietisien' => 'Dietisien Teregistrasi',
            'nutrisionis' => 'Tenaga Nutrisionis',
            'perawat' => 'Perawat Poliklinik',
            'admin_ti' => 'Administrator TI',
        ][$this->peran] ?? $this->peran;
    }

    public function getAuthIdentifierName() { return 'id'; }
    public function getAuthIdentifier() { return $this->id; }
    public function getAuthPasswordName() { return 'password'; }
    public function getAuthPassword() { return $this->password; }
    public function getRememberToken() { return ''; }
    public function setRememberToken($value) { }
    public function getRememberTokenName() { return ''; }
}
