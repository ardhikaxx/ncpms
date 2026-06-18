<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pasiens', function (Blueprint $table) {
            
            $table->id();
            $table->text('nomor_rekam_medis');
            $table->text('nik');
            $table->text('nama_lengkap');
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O', 'tidak_diketahui'])->nullable();
            $table->text('nomor_telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->text('nomor_bpjs')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->string('satusehat_patient_id', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('pasiens');
    }
};
