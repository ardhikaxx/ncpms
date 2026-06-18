<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bahan_makanans', function (Blueprint $table) {
            
            $table->id();
            $table->string('nama_bahan', 200);
            $table->string('nama_daerah', 200)->nullable();
            $table->enum('kategori', ['karbohidrat', 'protein_hewani', 'protein_nabati', 'sayuran', 'buah', 'lemak', 'minuman', 'bumbu', 'lainnya']);
            $table->decimal('porsi_standar_gram', 8, 2);
            $table->decimal('energi_kkal', 8, 2);
            $table->decimal('protein_gram', 8, 2);
            $table->decimal('lemak_gram', 8, 2);
            $table->decimal('karbohidrat_gram', 8, 2);
            $table->decimal('serat_gram', 8, 2)->nullable();
            $table->decimal('natrium_mg', 8, 2)->nullable();
            $table->decimal('kalium_mg', 8, 2)->nullable();
            $table->decimal('fosfor_mg', 8, 2)->nullable();
            $table->decimal('kalsium_mg', 8, 2)->nullable();
            $table->string('sumber_data', 100)->nullable();
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('bahan_makanans');
    }
};
