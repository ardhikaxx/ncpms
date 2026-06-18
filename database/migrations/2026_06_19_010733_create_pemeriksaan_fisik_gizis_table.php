<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pemeriksaan_fisik_gizis', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungans');
            $table->smallInteger('tekanan_darah_sistolik')->nullable();
            $table->smallInteger('tekanan_darah_diastolik')->nullable();
            $table->smallInteger('nadi_per_menit')->nullable();
            $table->smallInteger('respirasi_per_menit')->nullable();
            $table->decimal('suhu_celsius', 4, 1)->nullable();
            $table->tinyInteger('saturasi_oksigen_persen')->nullable();
            $table->boolean('edema')->default(false);
            $table->string('lokasi_edema', 100)->nullable();
            $table->json('tanda_defisiensi')->nullable();
            $table->json('gangguan_gastrointestinal')->nullable();
            $table->text('kondisi_mulut')->nullable();
            $table->decimal('kekuatan_genggam_kg', 5, 2)->nullable();
            $table->text('catatan_klinis')->nullable();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('penggunas');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemeriksaan_fisik_gizis');
    }
};
