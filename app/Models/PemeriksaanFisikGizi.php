<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PemeriksaanFisikGizi extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'pemeriksaan_fisik_gizis';
    protected $fillable = ['kunjungan_id', 'tekanan_darah_sistolik', 'tekanan_darah_diastolik', 'nadi_per_menit', 'respirasi_per_menit', 'suhu_celsius', 'saturasi_oksigen_persen', 'edema', 'lokasi_edema', 'tanda_defisiensi', 'gangguan_gastrointestinal', 'kondisi_mulut', 'kekuatan_genggam_kg', 'catatan_klinis', 'dicatat_oleh'];
    protected $casts = ['tanda_defisiensi' => 'array', 'gangguan_gastrointestinal' => 'array'];
    public function kunjungan() { return $this->belongsTo(Kunjungan::class); }
    public function pencatat() { return $this->belongsTo(Pengguna::class, 'dicatat_oleh'); }
}
