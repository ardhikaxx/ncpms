<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SesiAktif extends Model 
{
    use HasFactory;

    protected $table = 'sesi_aktifs';
    protected $fillable = ['pengguna_id', 'token', 'ip_address', 'user_agent', 'last_activity', 'expired_at'];
    protected $casts = ['last_activity' => 'datetime', 'expired_at' => 'datetime'];
    

}
