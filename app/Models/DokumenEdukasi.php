<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DokumenEdukasi extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'dokumen_edukasiis';
    protected $fillable = ['pasien_id', 'kunjungan_id', 'judul_dokumen', 'tipe', 'konten_json', 'path_pdf', 'token_akses', 'token_expired_at', 'dibuat_oleh'];
    protected $casts = ['konten_json' => 'array', 'token_expired_at' => 'datetime'];
    

}
