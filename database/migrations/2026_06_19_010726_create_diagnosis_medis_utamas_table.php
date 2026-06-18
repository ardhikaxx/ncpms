<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('diagnosis_medis_utamas', function (Blueprint $table) {
            
            $table->id();
            $table->string('kode_icd10', 10);
            $table->string('nama_diagnosis', 255);
            $table->string('kategori', 100)->nullable();
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('diagnosis_medis_utamas');
    }
};
