<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BahanMakanan extends Model 
{
    use HasFactory;

    protected $table = 'bahan_makanans';
    protected $fillable = ['nama_bahan', 'nama_daerah', 'kategori', 'porsi_standar_gram', 'energi_kkal', 'protein_gram', 'lemak_gram', 'karbohidrat_gram', 'serat_gram', 'natrium_mg', 'kalium_mg', 'fosfor_mg', 'kalsium_mg', 'sumber_data'];
    
    

}
