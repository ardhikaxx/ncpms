<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Monitoring extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'monitorings';
    protected $fillable = ['kunjungan_id', 'kunjungan_sebelumnya_id', 'parameter_dipantau', 'evaluasi_anthropometri', 'evaluasi_biokimia', 'evaluasi_asupan', 'evaluasi_kepatuhan_diet', 'persen_sisa_makanan', 'kesimpulan', 'rekomendasi_lanjutan', 'perlu_rujukan', 'tujuan_rujukan', 'rencana_kunjungan_berikutnya', 'dilakukan_oleh'];
    protected $casts = ['parameter_dipantau' => 'array', 'rencana_kunjungan_berikutnya' => 'date'];
    

}
