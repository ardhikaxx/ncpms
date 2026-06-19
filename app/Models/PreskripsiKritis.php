<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreskripsiKritis extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'preskripsi_kritis';
    protected $fillable = [
        'kunjungan_id', 'jenis_nutrisi', 'rute_pemberian', 'nama_formula',
        'volume_ml', 'frekuensi_sehari', 'kecepatan_pemberian',
        'total_kalori_kkal', 'total_protein_gram', 'total_lemak_gram', 'total_karbohidrat_gram',
        'instruksi_khusus', 'dicatat_oleh'
    ];

    public function kunjungan() {
        return $this->belongsTo(Kunjungan::class);
    }

    public function pencatat() {
        return $this->belongsTo(Pengguna::class, 'dicatat_oleh');
    }
}
