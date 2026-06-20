<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Enteral & Parenteral
        Schema::table('preskripsi_diets', function (Blueprint $table) {
            $table->enum('jenis_preskripsi', ['oral', 'enteral', 'parenteral'])->default('oral')->after('bentuk_makanan');
            $table->string('rute_pemberian')->nullable()->after('jenis_preskripsi'); // NGT, OGT, IV
            $table->string('laju_makanan_cair')->nullable()->after('rute_pemberian'); // misal: 50 cc/jam
            $table->string('nama_formula_komersial')->nullable()->after('laju_makanan_cair');
        });

        // 2. Sisa Makanan
        Schema::create('sisa_makanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungans')->cascadeOnDelete();
            $table->date('tanggal_pencatatan');
            $table->enum('waktu_makan', ['pagi', 'siang', 'malam', 'selingan_pagi', 'selingan_sore']);
            $table->enum('persentase_sisa', ['0', '25', '50', '75', '100']);
            $table->string('alasan_sisa')->nullable();
            $table->foreignId('dicatat_oleh')->constrained('penggunas')->cascadeOnDelete();
            $table->timestamps();
        });

        // 3 & 5. Status Puasa & Rawat Jalan
        Schema::table('kunjungans', function (Blueprint $table) {
            $table->boolean('status_puasa')->default(false)->after('status');
            $table->string('alasan_puasa')->nullable()->after('status_puasa');
            $table->dateTime('waktu_perjanjian')->nullable()->after('waktu_selesai'); // untuk rawat jalan
        });
    }

    public function down(): void
    {
        Schema::table('kunjungans', function (Blueprint $table) {
            $table->dropColumn(['status_puasa', 'alasan_puasa', 'waktu_perjanjian']);
        });
        Schema::dropIfExists('sisa_makanans');
        Schema::table('preskripsi_diets', function (Blueprint $table) {
            $table->dropColumn(['jenis_preskripsi', 'rute_pemberian', 'laju_makanan_cair', 'nama_formula_komersial']);
        });
    }
};
