<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CatatanKonseling extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'catatan_konselings';
    protected $fillable = ['kunjungan_id', 'tanggal_konseling', 'durasi_menit', 'metode', 'topik_konseling', 'isi_konseling', 'hambatan_pasien', 'kesepakatan_tindak_lanjut', 'tingkat_pemahaman_pasien', 'dokumen_edukasi_id', 'dilakukan_oleh'];
    protected $casts = ['isi_konseling' => 'encrypted', 'topik_konseling' => 'array', 'tanggal_konseling' => 'date'];

    public function kunjungan() { return $this->belongsTo(Kunjungan::class); }
    public function dokumenEdukasi() { return $this->belongsTo(DokumenEdukasi::class); }
    public function pelaksana() { return $this->belongsTo(Pengguna::class, 'dilakukan_oleh'); }

}
