<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiagnosisMedisUtama extends Model 
{
    use HasFactory;

    protected $table = 'diagnosis_medis_utamas';
    protected $fillable = ['kode_icd10', 'nama_diagnosis', 'kategori'];
    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class, 'diagnosis_medis_utama_id');
    }
}
