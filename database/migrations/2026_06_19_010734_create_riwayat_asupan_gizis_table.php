<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('riwayat_asupan_gizis', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungans');
            $table->enum('metode', ['food_recall_24h', 'food_recall_48h', 'food_recall_72h', 'ffq_semi_kuantitatif']);
            $table->date('tanggal_recall');
            $table->json('detail_asupan');
            $table->decimal('total_energi_kkal', 10, 2)->nullable();
            $table->decimal('total_protein_gram', 10, 2)->nullable();
            $table->decimal('total_lemak_gram', 10, 2)->nullable();
            $table->decimal('total_karbohidrat_gram', 10, 2)->nullable();
            $table->decimal('total_serat_gram', 10, 2)->nullable();
            $table->decimal('total_natrium_mg', 10, 2)->nullable();
            $table->decimal('persen_pemenuhan_energi', 5, 2)->nullable();
            $table->decimal('persen_pemenuhan_protein', 5, 2)->nullable();
            $table->decimal('persen_pemenuhan_lemak', 5, 2)->nullable();
            $table->decimal('persen_pemenuhan_karbohidrat', 5, 2)->nullable();
            $table->text('kesimpulan_asupan')->nullable();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('penggunas');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_asupan_gizis');
    }
};
