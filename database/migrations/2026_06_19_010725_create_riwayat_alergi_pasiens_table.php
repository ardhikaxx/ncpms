<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('riwayat_alergi_pasiens', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasiens');
            $table->enum('jenis_alergi', ['makanan', 'obat', 'lingkungan', 'lainnya']);
            $table->text('nama_alergen');
            $table->text('reaksi')->nullable();
            $table->enum('tingkat_keparahan', ['ringan', 'sedang', 'berat']);
            $table->foreignId('dicatat_oleh')->constrained('penggunas');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_alergi_pasiens');
    }
};
