<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kunjungans', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasiens');
            $table->string('nomor_kunjungan', 50)->unique();
            $table->enum('tipe_kunjungan', ['mandiri', 'rujukan_internal', 'rujukan_eksternal'])->nullable();
            $table->string('asal_rujukan', 200)->nullable();
            $table->enum('status', ['terdaftar', 'dalam_pelayanan', 'selesai', 'batal'])->default('terdaftar');
            $table->date('tanggal_kunjungan');
            $table->timestamp('waktu_registrasi')->useCurrent();
            $table->timestamp('waktu_selesai')->nullable();
            $table->foreignId('perawat_id')->nullable()->constrained('penggunas');
            $table->foreignId('dietisien_id')->nullable()->constrained('penggunas');
            $table->foreignId('spgk_id')->nullable()->constrained('penggunas');
            $table->foreignId('diagnosis_medis_utama_id')->nullable()->constrained('diagnosis_medis_utamas');
            $table->json('diagnosis_medis_penyerta')->nullable();
            $table->string('satusehat_encounter_id', 100)->nullable();
            $table->boolean('dokumen_terkunci')->default(false);
            $table->foreignId('dikunci_oleh')->nullable()->constrained('penggunas');
            $table->timestamp('dikunci_pada')->nullable();
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('kunjungans');
    }
};
