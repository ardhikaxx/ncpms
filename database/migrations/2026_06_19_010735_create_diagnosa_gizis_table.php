<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('diagnosa_gizis', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungans');
            $table->foreignId('terminologi_id')->constrained('terminologi_diagnosis_gizis');
            $table->enum('domain', ['asupan', 'klinis', 'perilaku_lingkungan']);
            $table->string('problem_masalah', 255);
            $table->text('etiologi_penyebab');
            $table->text('signs_symptoms');
            $table->text('narasi_pes');
            $table->tinyInteger('urutan_prioritas')->default(1);
            $table->enum('status', ['aktif', 'teratasi', 'tidak_aktif'])->default('aktif');
            $table->foreignId('divalidasi_oleh')->nullable()->constrained('penggunas');
            $table->timestamp('divalidasi_pada')->nullable();
            $table->string('satusehat_condition_id', 100)->nullable();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('penggunas');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('diagnosa_gizis');
    }
};
