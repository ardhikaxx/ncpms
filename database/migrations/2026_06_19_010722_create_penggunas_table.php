<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penggunas', function (Blueprint $table) {
            
            $table->id();
            $table->string('nama_lengkap', 150);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->enum('peran', ['spgk', 'dietisien', 'nutrisionis', 'perawat', 'admin_ti']);
            $table->string('nomor_sip', 50)->nullable();
            $table->string('nomor_str', 50)->nullable();
            $table->string('unit_kerja', 100)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->timestamps();
            $table->softDeletes();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('penggunas');
    }
};
