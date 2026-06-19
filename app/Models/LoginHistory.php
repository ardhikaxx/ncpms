<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginHistory extends Model 
{
    use HasFactory;

    protected $table = 'login_histories';
    protected $fillable = ['pengguna_id', 'tipe_event', 'ip_address', 'user_agent'];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id')->withTrashed();
    }

}
