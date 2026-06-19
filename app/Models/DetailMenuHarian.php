<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailMenuHarian extends Model 
{
    use HasFactory;

    protected $table = 'detail_menu_harians';
    protected $fillable = ['preskripsi_diet_id', 'waktu_makan', 'bahan_makanan_id', 'porsi_gram', 'energi_kkal', 'protein_gram', 'lemak_gram', 'karbohidrat_gram', 'keterangan_penukar'];

    public function preskripsiDiet()
    {
        return $this->belongsTo(PreskripsiDiet::class);
    }

    public function bahanMakanan()
    {
        return $this->belongsTo(BahanMakanan::class);
    }

}
