<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataBiokimia extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'data_biokimias';
    protected $fillable = ['kunjungan_id', 'tanggal_pemeriksaan', 'sumber_data', 'gula_darah_sewaktu', 'gula_darah_puasa', 'gula_darah_2jpp', 'hba1c_persen', 'kolesterol_total', 'hdl', 'ldl', 'trigliserida', 'albumin', 'ureum', 'kreatinin', 'laju_filtrasi_gfr', 'hemoglobin', 'hematokrit', 'neutrofil', 'natrium', 'kalium', 'kalsium', 'fosfor', 'catatan_tambahan', 'dicatat_oleh'];
    protected $casts = ['tanggal_pemeriksaan' => 'date'];
    

}
