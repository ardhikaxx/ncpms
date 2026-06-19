<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkriningGizi extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'skrining_gizis';
    protected $fillable = ['kunjungan_id', 'metode_skrining', 'skor_penurunan_bb', 'skor_penurunan_asupan', 'skor_keparahan_penyakit', 'skor_usia', 'total_skor', 'kategori_risiko', 'rekomendasi_tindak_lanjut', 'dilakukan_oleh', 'detail_skrining'];
    protected $casts = ['detail_skrining' => 'array'];

    public function kunjungan() { return $this->belongsTo(Kunjungan::class); }
    public function pelaksana() { return $this->belongsTo(Pengguna::class, 'dilakukan_oleh'); }
}
