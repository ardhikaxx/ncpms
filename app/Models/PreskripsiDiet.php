<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\AuditsActivity;

class PreskripsiDiet extends Model
{
    use HasFactory, AuditsActivity;

    protected $table = 'preskripsi_diets';
    protected $fillable = ['kunjungan_id', 'jenis_diet', 'bentuk_makanan', 'jenis_preskripsi', 'rute_pemberian', 'laju_makanan_cair', 'nama_formula_komersial', 'frekuensi_makan', 'total_kalori_target', 'total_protein_target', 'total_lemak_target', 'total_karbohidrat_target', 'instruksi_tambahan', 'dietisien_id', 'waktu_preskripsi', 'formula_basal', 'berat_badan_acuan', 'kebutuhan_energi_basal_kkal', 'faktor_aktivitas', 'faktor_stres', 'faktor_koreksi_usia', 'total_kebutuhan_energi_kkal', 'persen_karbohidrat', 'gram_karbohidrat', 'persen_protein', 'gram_protein', 'persen_lemak', 'gram_lemak', 'gram_serat', 'batas_natrium_mg', 'batas_kalium_mg', 'batas_fosfor_mg', 'batas_cairan_ml', 'frekuensi_makan_utama', 'frekuensi_selingan', 'pantangan_spesifik', 'catatan_klinis', 'tujuan_terapi', 'target_luaran_klinis', 'tanggal_mulai', 'tanggal_evaluasi', 'durasi_hari', 'status', 'dibuat_oleh', 'disetujui_oleh', 'disetujui_pada', 'satusehat_careplan_id'];
    protected $casts = ['catatan_klinis' => 'encrypted', 'pantangan_spesifik' => 'array', 'target_luaran_klinis' => 'array', 'tanggal_mulai' => 'date', 'tanggal_evaluasi' => 'date', 'disetujui_pada' => 'datetime'];

    public function kunjungan() { return $this->belongsTo(Kunjungan::class); }
    public function pembuat() { return $this->belongsTo(Pengguna::class, 'dibuat_oleh'); }
    public function penyetuju() { return $this->belongsTo(Pengguna::class, 'disetujui_oleh'); }
    public function detailMenuHarians() { return $this->hasMany(DetailMenuHarian::class); }
}
