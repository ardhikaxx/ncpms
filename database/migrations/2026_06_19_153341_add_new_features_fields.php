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
        Schema::table('skrining_gizis', function (Blueprint $table) {
            $table->json('detail_skrining')->nullable();
        });
        
        DB::statement("ALTER TABLE skrining_gizis MODIFY COLUMN metode_skrining ENUM('MNA', 'NRS2002', 'MST', 'MUST', 'STAMP', 'STRONGkids') NOT NULL");

        Schema::table('kunjungans', function (Blueprint $table) {
            $table->text('obat_sedang_dikonsumsi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skrining_gizis', function (Blueprint $table) {
            $table->dropColumn('detail_skrining');
        });
        Schema::table('kunjungans', function (Blueprint $table) {
            $table->dropColumn('obat_sedang_dikonsumsi');
        });
    }
};
