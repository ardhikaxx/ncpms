<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('login_histories', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('pengguna_id')->constrained('penggunas');
            $table->enum('tipe_event', ['login', 'logout', 'login_gagal', 'timeout']);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('login_histories');
    }
};
