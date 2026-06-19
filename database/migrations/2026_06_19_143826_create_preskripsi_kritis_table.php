<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('preskripsi_kritis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungans');
            $table->enum('jenis_nutrisi', ['enteral', 'parenteral', 'kombinasi']);
            $table->string('rute_pemberian', 100);
            $table->string('nama_formula', 200);
            $table->decimal('volume_ml', 8, 2);
            $table->integer('frekuensi_sehari');
            $table->decimal('kecepatan_pemberian', 8, 2)->nullable(); // ml/jam atau tetes/menit
            $table->decimal('total_kalori_kkal', 8, 2);
            $table->decimal('total_protein_gram', 8, 2);
            $table->decimal('total_lemak_gram', 8, 2);
            $table->decimal('total_karbohidrat_gram', 8, 2);
            $table->text('instruksi_khusus')->nullable();
            $table->foreignId('dicatat_oleh')->constrained('penggunas');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('preskripsi_kritis');
    }
};
