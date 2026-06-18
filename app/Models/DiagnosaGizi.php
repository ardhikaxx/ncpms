<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiagnosaGizi extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'diagnosa_gizis';
    protected $fillable = ['kunjungan_id', 'terminologi_id', 'domain', 'problem_masalah', 'etiologi_penyebab', 'signs_symptoms', 'narasi_pes', 'urutan_prioritas', 'status', 'divalidasi_oleh', 'divalidasi_pada', 'satusehat_condition_id', 'dicatat_oleh'];
    protected $casts = ['narasi_pes' => 'encrypted', 'divalidasi_pada' => 'datetime'];
    

}
