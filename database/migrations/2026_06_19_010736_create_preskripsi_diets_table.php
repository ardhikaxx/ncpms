<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('preskripsi_diets', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungans');
            $table->enum('formula_basal', ['harris_benedict', 'mifflin_st_jeor', 'who', 'konsensus_dm', 'konsensus_ckd']);
            $table->enum('berat_badan_acuan', ['aktual', 'ideal', 'adjusted']);
            $table->decimal('kebutuhan_energi_basal_kkal', 10, 2);
            $table->decimal('faktor_aktivitas', 4, 2);
            $table->decimal('faktor_stres', 4, 2);
            $table->decimal('faktor_koreksi_usia', 4, 2)->default(1.0);
            $table->decimal('total_kebutuhan_energi_kkal', 10, 2);
            $table->decimal('persen_karbohidrat', 5, 2);
            $table->decimal('gram_karbohidrat', 10, 2);
            $table->decimal('persen_protein', 5, 2);
            $table->decimal('gram_protein', 10, 2);
            $table->decimal('persen_lemak', 5, 2);
            $table->decimal('gram_lemak', 10, 2);
            $table->decimal('gram_serat', 8, 2)->nullable();
            $table->decimal('batas_natrium_mg', 10, 2)->nullable();
            $table->decimal('batas_kalium_mg', 10, 2)->nullable();
            $table->decimal('batas_fosfor_mg', 10, 2)->nullable();
            $table->decimal('batas_cairan_ml', 10, 2)->nullable();
            $table->enum('bentuk_makanan', ['biasa', 'lunak', 'saring', 'cair_penuh', 'cair_jernih', 'formula_medis']);
            $table->tinyInteger('frekuensi_makan_utama')->default(3);
            $table->tinyInteger('frekuensi_selingan')->default(2);
            $table->json('pantangan_spesifik')->nullable();
            $table->text('catatan_klinis')->nullable();
            $table->text('tujuan_terapi');
            $table->json('target_luaran_klinis')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_evaluasi')->nullable();
            $table->smallInteger('durasi_hari')->nullable();
            $table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
            $table->foreignId('dibuat_oleh')->constrained('penggunas');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('penggunas');
            $table->timestamp('disetujui_pada')->nullable();
            $table->string('satusehat_careplan_id', 100)->nullable();
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('preskripsi_diets');
    }
};
