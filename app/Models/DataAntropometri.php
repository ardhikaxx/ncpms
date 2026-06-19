<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataAntropometri extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'data_antropometris';
    protected $fillable = ['kunjungan_id', 'tanggal_pengukuran', 'berat_badan_kg', 'tinggi_badan_cm', 'imt', 'status_gizi_imt', 'lingkar_lengan_atas_cm', 'lingkar_perut_cm', 'panjang_lutut_cm', 'tebal_lipatan_kulit_mm', 'berat_badan_ideal_kg', 'persentase_bbl', 'is_pediatri', 'usia_tahun', 'usia_bulan', 'zscore_bb_u', 'zscore_tb_u', 'zscore_imt_u', 'status_gizi_anak', 'dicatat_oleh', 'satusehat_obs_id'];
    protected $casts = ['tanggal_pengukuran' => 'date', 'berat_badan_kg' => 'encrypted', 'tinggi_badan_cm' => 'encrypted', 'imt' => 'encrypted', 'lingkar_lengan_atas_cm' => 'encrypted', 'lingkar_perut_cm' => 'encrypted', 'panjang_lutut_cm' => 'encrypted', 'tebal_lipatan_kulit_mm' => 'encrypted', 'berat_badan_ideal_kg' => 'encrypted', 'persentase_bbl' => 'encrypted'];

    public function kunjungan() { return $this->belongsTo(Kunjungan::class); }
    public function pencatat() { return $this->belongsTo(Pengguna::class, 'dicatat_oleh'); }
}
