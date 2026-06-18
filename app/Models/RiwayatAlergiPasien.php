<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiwayatAlergiPasien extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'riwayat_alergi_pasiens';
    protected $fillable = ['pasien_id', 'jenis_alergi', 'nama_alergen', 'reaksi', 'tingkat_keparahan', 'dicatat_oleh'];
    protected $casts = ['nama_alergen' => 'encrypted'];
    

}
