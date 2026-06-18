<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('data_antropometris', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungans');
            $table->date('tanggal_pengukuran');
            $table->text('berat_badan_kg');
            $table->text('tinggi_badan_cm');
            $table->text('imt')->nullable();
            $table->enum('status_gizi_imt', ['sangat_kurus', 'kurus', 'normal', 'gemuk', 'obesitas_1', 'obesitas_2'])->nullable();
            $table->text('lingkar_lengan_atas_cm')->nullable();
            $table->text('lingkar_perut_cm')->nullable();
            $table->text('panjang_lutut_cm')->nullable();
            $table->text('tebal_lipatan_kulit_mm')->nullable();
            $table->text('berat_badan_ideal_kg')->nullable();
            $table->text('persentase_bbl')->nullable();
            $table->boolean('is_pediatri')->default(false);
            $table->tinyInteger('usia_tahun')->nullable();
            $table->tinyInteger('usia_bulan')->nullable();
            $table->decimal('zscore_bb_u', 5, 2)->nullable();
            $table->decimal('zscore_tb_u', 5, 2)->nullable();
            $table->decimal('zscore_imt_u', 5, 2)->nullable();
            $table->enum('status_gizi_anak', ['gizi_buruk', 'gizi_kurang', 'gizi_baik', 'gizi_lebih', 'obesitas'])->nullable();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('penggunas');
            $table->string('satusehat_obs_id', 100)->nullable();
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_antropometris');
    }
};
