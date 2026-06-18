<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dokumen_edukasiis', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasiens');
            $table->foreignId('kunjungan_id')->constrained('kunjungans');
            $table->string('judul_dokumen', 255);
            $table->enum('tipe', ['leaflet_diet', 'panduan_makan', 'ringkasan_kalori', 'pantangan_alergi', 'rencana_makan']);
            $table->json('konten_json');
            $table->string('path_pdf', 500)->nullable();
            $table->string('token_akses', 100)->nullable();
            $table->timestamp('token_expired_at')->nullable();
            $table->foreignId('dibuat_oleh')->constrained('penggunas');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('dokumen_edukasiis');
    }
};
