<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TerminologiDiagnosisGizi extends Model 
{
    use HasFactory;

    protected $table = 'terminologi_diagnosis_gizis';
    protected $fillable = ['kode_diagnosis', 'domain', 'nama_masalah', 'deskripsi', 'is_aktif'];
    
    

}
