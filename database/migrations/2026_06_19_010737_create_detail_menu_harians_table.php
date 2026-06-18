<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_menu_harians', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('preskripsi_diet_id')->constrained('preskripsi_diets');
            $table->enum('waktu_makan', ['makan_pagi', 'selingan_pagi', 'makan_siang', 'selingan_sore', 'makan_malam', 'selingan_malam']);
            $table->foreignId('bahan_makanan_id')->constrained('bahan_makanans');
            $table->decimal('porsi_gram', 8, 2);
            $table->decimal('energi_kkal', 8, 2);
            $table->decimal('protein_gram', 8, 2);
            $table->decimal('lemak_gram', 8, 2);
            $table->decimal('karbohidrat_gram', 8, 2);
            $table->text('keterangan_penukar')->nullable();
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_menu_harians');
    }
};
