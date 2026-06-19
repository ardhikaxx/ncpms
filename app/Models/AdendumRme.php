<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdendumRme extends Model
{
    protected $guarded = [];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }

    public function pembuat()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }
}
