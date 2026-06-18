<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('skrining_gizis', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungans');
            $table->enum('metode_skrining', ['MNA', 'NRS2002', 'MST', 'MUST', 'STAMP']);
            $table->tinyInteger('skor_penurunan_bb');
            $table->tinyInteger('skor_penurunan_asupan');
            $table->tinyInteger('skor_keparahan_penyakit');
            $table->tinyInteger('skor_usia')->default(0);
            $table->tinyInteger('total_skor');
            $table->enum('kategori_risiko', ['risiko_rendah', 'risiko_sedang', 'risiko_tinggi']);
            $table->text('rekomendasi_tindak_lanjut')->nullable();
            $table->foreignId('dilakukan_oleh')->nullable()->constrained('penggunas');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('skrining_gizis');
    }
};
