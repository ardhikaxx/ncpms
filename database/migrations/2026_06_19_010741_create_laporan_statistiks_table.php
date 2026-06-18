<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_statistiks', function (Blueprint $table) {
            
            $table->id();
            $table->enum('tipe_laporan', ['kinerja_harian', 'demografi_patologi', 'rasio_intervensi', 'spm_gizi', 'audit_mutu']);
            $table->date('periode_dari');
            $table->date('periode_sampai');
            $table->json('parameter')->nullable();
            $table->json('data_laporan');
            $table->foreignId('dibuat_oleh')->constrained('penggunas');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_statistiks');
    }
};
