<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('monitorings', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungans');
            $table->foreignId('kunjungan_sebelumnya_id')->nullable()->constrained('kunjungans');
            $table->json('parameter_dipantau');
            $table->text('evaluasi_anthropometri')->nullable();
            $table->text('evaluasi_biokimia')->nullable();
            $table->text('evaluasi_asupan')->nullable();
            $table->enum('evaluasi_kepatuhan_diet', ['patuh', 'cukup_patuh', 'tidak_patuh'])->nullable();
            $table->decimal('persen_sisa_makanan', 5, 2)->nullable();
            $table->text('kesimpulan')->nullable();
            $table->text('rekomendasi_lanjutan')->nullable();
            $table->boolean('perlu_rujukan')->default(false);
            $table->text('tujuan_rujukan')->nullable();
            $table->date('rencana_kunjungan_berikutnya')->nullable();
            $table->foreignId('dilakukan_oleh')->constrained('penggunas');
            $table->timestamps();
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('monitorings');
    }
};
