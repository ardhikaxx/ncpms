<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('terminologi_diagnosis_gizis', function (Blueprint $table) {
            
            $table->id();
            $table->string('kode_diagnosis', 20);
            $table->enum('domain', ['asupan', 'klinis', 'perilaku_lingkungan']);
            $table->string('nama_masalah', 255);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('terminologi_diagnosis_gizis');
    }
};
