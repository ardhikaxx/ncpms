<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SisaMakanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kunjungan_id',
        'tanggal_pencatatan',
        'waktu_makan',
        'persentase_sisa',
        'alasan_sisa',
        'dicatat_oleh'
    ];

    protected $casts = [
        'tanggal_pencatatan' => 'date',
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }

    public function pencatat()
    {
        return $this->belongsTo(Pengguna::class, 'dicatat_oleh');
    }
}
