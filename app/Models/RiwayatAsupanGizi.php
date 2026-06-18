<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiwayatAsupanGizi extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'riwayat_asupan_gizis';
    protected $fillable = ['kunjungan_id', 'metode', 'tanggal_recall', 'detail_asupan', 'total_energi_kkal', 'total_protein_gram', 'total_lemak_gram', 'total_karbohidrat_gram', 'total_serat_gram', 'total_natrium_mg', 'persen_pemenuhan_energi', 'persen_pemenuhan_protein', 'persen_pemenuhan_lemak', 'persen_pemenuhan_karbohidrat', 'kesimpulan_asupan', 'dicatat_oleh'];
    protected $casts = ['tanggal_recall' => 'date', 'detail_asupan' => 'array'];
    

}
