<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pasien extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'pasiens';
    protected $fillable = ['nomor_rekam_medis', 'nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'golongan_darah', 'nomor_telepon', 'alamat', 'nomor_bpjs', 'status_aktif', 'satusehat_patient_id'];
    protected $casts = ['nomor_rekam_medis' => 'encrypted', 'nik' => 'encrypted', 'nama_lengkap' => 'encrypted', 'nomor_telepon' => 'encrypted', 'alamat' => 'encrypted', 'nomor_bpjs' => 'encrypted', 'tanggal_lahir' => 'date'];
    

}
