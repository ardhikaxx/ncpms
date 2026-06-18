<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('catatan_konselings', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungans');
            $table->date('tanggal_konseling');
            $table->smallInteger('durasi_menit')->nullable();
            $table->enum('metode', ['tatap_muka', 'telepon', 'video_call']);
            $table->json('topik_konseling');
            $table->text('isi_konseling');
            $table->text('hambatan_pasien')->nullable();
            $table->text('kesepakatan_tindak_lanjut')->nullable();
            $table->enum('tingkat_pemahaman_pasien', ['baik', 'cukup', 'kurang'])->nullable();
            $table->foreignId('dokumen_edukasi_id')->nullable()->constrained('dokumen_edukasiis');
            $table->foreignId('dilakukan_oleh')->constrained('penggunas');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('catatan_konselings');
    }
};
