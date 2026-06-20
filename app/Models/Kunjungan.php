<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\AuditsActivity;

class Kunjungan extends Model
{
    use HasFactory, AuditsActivity;

    protected $table = 'kunjungans';
    protected $fillable = ['pasien_id', 'nomor_kunjungan', 'tipe_kunjungan', 'asal_rujukan', 'status', 'tanggal_kunjungan', 'waktu_registrasi', 'waktu_selesai', 'perawat_id', 'dietisien_id', 'spgk_id', 'diagnosis_medis_utama_id', 'diagnosis_medis_penyerta', 'satusehat_encounter_id', 'dokumen_terkunci', 'dikunci_oleh', 'dikunci_pada', 'obat_sedang_dikonsumsi', 'status_puasa', 'alasan_puasa', 'waktu_perjanjian'];
    protected $casts = ['diagnosis_medis_penyerta' => 'array', 'tanggal_kunjungan' => 'date', 'waktu_registrasi' => 'datetime', 'waktu_selesai' => 'datetime', 'dikunci_pada' => 'datetime'];

    public function pasien() { return $this->belongsTo(Pasien::class); }
    public function perawat() { return $this->belongsTo(Pengguna::class, 'perawat_id'); }
    public function dietisien() { return $this->belongsTo(Pengguna::class, 'dietisien_id'); }
    public function spgk() { return $this->belongsTo(Pengguna::class, 'spgk_id'); }
    public function diagnosisMedisUtama() { return $this->belongsTo(DiagnosisMedisUtama::class, 'diagnosis_medis_utama_id'); }
    public function skriningGizi() { return $this->hasOne(SkriningGizi::class); }
    public function antropometri() { return $this->hasOne(DataAntropometri::class)->latestOfMany(); }
    public function biokimia() { return $this->hasOne(DataBiokimia::class)->latestOfMany(); }
    public function fisik() { return $this->hasOne(PemeriksaanFisikGizi::class)->latestOfMany(); }
    public function asupan() { return $this->hasOne(RiwayatAsupanGizi::class)->latestOfMany(); }
    public function diagnosaGizis() { return $this->hasMany(DiagnosaGizi::class)->orderBy('urutan_prioritas'); }
    public function preskripsiDiets() { return $this->hasMany(PreskripsiDiet::class); }
    public function preskripsiKritis() { return $this->hasOne(PreskripsiKritis::class); }
    public function adendums()
    {
        return $this->hasMany(AdendumRme::class)->orderBy('created_at', 'desc');
    }

    public function sisaMakanan()
    {
        return $this->hasMany(SisaMakanan::class)->orderBy('tanggal_pencatatan', 'desc')->orderBy('waktu_makan', 'desc');
    }
    public function detailMenuHarians() { return $this->hasManyThrough(DetailMenuHarian::class, PreskripsiDiet::class); }
    public function monitoring() { return $this->hasOne(Monitoring::class); }
    public function catatanKonselings() { return $this->hasMany(CatatanKonseling::class)->latest('tanggal_konseling'); }
    public function dokumenEdukasiis() { return $this->hasMany(DokumenEdukasi::class)->latest(); }
}
