<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sesi_aktifs', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('pengguna_id')->constrained('penggunas');
            $table->string('token', 500);
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->timestamp('last_activity')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('sesi_aktifs');
    }
};
