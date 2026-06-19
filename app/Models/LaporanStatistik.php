<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanStatistik extends Model 
{
    use HasFactory;

    protected $table = 'laporan_statistiks';
    protected $fillable = ['tipe_laporan', 'periode_dari', 'periode_sampai', 'parameter', 'data_laporan', 'dibuat_oleh'];
    protected $casts = ['parameter' => 'array', 'data_laporan' => 'array', 'periode_dari' => 'date', 'periode_sampai' => 'date'];
    public function pembuat() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }
}
