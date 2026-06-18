<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kunjungan extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'kunjungans';
    protected $fillable = ['pasien_id', 'nomor_kunjungan', 'tipe_kunjungan', 'asal_rujukan', 'status', 'tanggal_kunjungan', 'waktu_registrasi', 'waktu_selesai', 'perawat_id', 'dietisien_id', 'spgk_id', 'diagnosis_medis_utama_id', 'diagnosis_medis_penyerta', 'satusehat_encounter_id', 'dokumen_terkunci', 'dikunci_oleh', 'dikunci_pada'];
    protected $casts = ['diagnosis_medis_penyerta' => 'array', 'tanggal_kunjungan' => 'date', 'waktu_registrasi' => 'datetime', 'waktu_selesai' => 'datetime', 'dikunci_pada' => 'datetime'];
    

}
