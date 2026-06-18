<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('data_biokimias', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungans');
            $table->date('tanggal_pemeriksaan');
            $table->enum('sumber_data', ['lab_internal', 'input_manual', 'satusehat'])->nullable();
            $table->decimal('gula_darah_sewaktu', 8, 2)->nullable();
            $table->decimal('gula_darah_puasa', 8, 2)->nullable();
            $table->decimal('gula_darah_2jpp', 8, 2)->nullable();
            $table->decimal('hba1c_persen', 5, 2)->nullable();
            $table->decimal('kolesterol_total', 8, 2)->nullable();
            $table->decimal('hdl', 8, 2)->nullable();
            $table->decimal('ldl', 8, 2)->nullable();
            $table->decimal('trigliserida', 8, 2)->nullable();
            $table->decimal('albumin', 5, 2)->nullable();
            $table->decimal('ureum', 8, 2)->nullable();
            $table->decimal('kreatinin', 8, 2)->nullable();
            $table->decimal('laju_filtrasi_gfr', 8, 2)->nullable();
            $table->decimal('hemoglobin', 5, 2)->nullable();
            $table->decimal('hematokrit', 5, 2)->nullable();
            $table->decimal('neutrofil', 5, 2)->nullable();
            $table->decimal('natrium', 8, 2)->nullable();
            $table->decimal('kalium', 8, 2)->nullable();
            $table->decimal('kalsium', 8, 2)->nullable();
            $table->decimal('fosfor', 8, 2)->nullable();
            $table->text('catatan_tambahan')->nullable();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('penggunas');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_biokimias');
    }
};
