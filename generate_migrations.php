<?php

$migrationsDir = __DIR__ . '/database/migrations/';

// delete existing migrations except users, cache, jobs, audits
$files = glob($migrationsDir . '*');
foreach($files as $file) {
    if (strpos($file, 'users') === false && strpos($file, 'cache') === false && strpos($file, 'jobs') === false && strpos($file, 'audits') === false) {
        unlink($file);
    }
}

$tables = [
    [
        'name' => 'penggunas',
        'fields' => "
            \$table->id();
            \$table->string('nama_lengkap', 150);
            \$table->string('email', 100)->unique();
            \$table->string('password', 255);
            \$table->enum('peran', ['spgk', 'dietisien', 'nutrisionis', 'perawat', 'admin_ti']);
            \$table->string('nomor_sip', 50)->nullable();
            \$table->string('nomor_str', 50)->nullable();
            \$table->string('unit_kerja', 100)->nullable();
            \$table->boolean('status_aktif')->default(true);
            \$table->timestamp('last_login_at')->nullable();
            \$table->string('last_login_ip', 45)->nullable();
            \$table->timestamps();
            \$table->softDeletes();
        "
    ],
    [
        'name' => 'sesi_aktifs',
        'fields' => "
            \$table->id();
            \$table->foreignId('pengguna_id')->constrained('penggunas');
            \$table->string('token', 500);
            \$table->string('ip_address', 45);
            \$table->text('user_agent');
            \$table->timestamp('last_activity')->nullable();
            \$table->timestamp('expired_at')->nullable();
            \$table->timestamps();
        "
    ],
    [
        'name' => 'pasiens',
        'fields' => "
            \$table->id();
            \$table->text('nomor_rekam_medis');
            \$table->text('nik');
            \$table->text('nama_lengkap');
            \$table->string('tempat_lahir', 100)->nullable();
            \$table->date('tanggal_lahir');
            \$table->enum('jenis_kelamin', ['L', 'P']);
            \$table->enum('golongan_darah', ['A', 'B', 'AB', 'O', 'tidak_diketahui'])->nullable();
            \$table->text('nomor_telepon')->nullable();
            \$table->text('alamat')->nullable();
            \$table->text('nomor_bpjs')->nullable();
            \$table->boolean('status_aktif')->default(true);
            \$table->string('satusehat_patient_id', 100)->nullable();
            \$table->timestamps();
            \$table->softDeletes();
        "
    ],
    [
        'name' => 'riwayat_alergi_pasiens',
        'fields' => "
            \$table->id();
            \$table->foreignId('pasien_id')->constrained('pasiens');
            \$table->enum('jenis_alergi', ['makanan', 'obat', 'lingkungan', 'lainnya']);
            \$table->text('nama_alergen');
            \$table->text('reaksi')->nullable();
            \$table->enum('tingkat_keparahan', ['ringan', 'sedang', 'berat']);
            \$table->foreignId('dicatat_oleh')->constrained('penggunas');
            \$table->timestamps();
        "
    ],
    [
        'name' => 'diagnosis_medis_utamas',
        'fields' => "
            \$table->id();
            \$table->string('kode_icd10', 10);
            \$table->string('nama_diagnosis', 255);
            \$table->string('kategori', 100)->nullable();
            \$table->timestamps();
        "
    ],
    [
        'name' => 'bahan_makanans',
        'fields' => "
            \$table->id();
            \$table->string('nama_bahan', 200);
            \$table->string('nama_daerah', 200)->nullable();
            \$table->enum('kategori', ['karbohidrat', 'protein_hewani', 'protein_nabati', 'sayuran', 'buah', 'lemak', 'minuman', 'bumbu', 'lainnya']);
            \$table->decimal('porsi_standar_gram', 8, 2);
            \$table->decimal('energi_kkal', 8, 2);
            \$table->decimal('protein_gram', 8, 2);
            \$table->decimal('lemak_gram', 8, 2);
            \$table->decimal('karbohidrat_gram', 8, 2);
            \$table->decimal('serat_gram', 8, 2)->nullable();
            \$table->decimal('natrium_mg', 8, 2)->nullable();
            \$table->decimal('kalium_mg', 8, 2)->nullable();
            \$table->decimal('fosfor_mg', 8, 2)->nullable();
            \$table->decimal('kalsium_mg', 8, 2)->nullable();
            \$table->string('sumber_data', 100)->nullable();
            \$table->timestamps();
        "
    ],
    [
        'name' => 'terminologi_diagnosis_gizis',
        'fields' => "
            \$table->id();
            \$table->string('kode_diagnosis', 20);
            \$table->enum('domain', ['asupan', 'klinis', 'perilaku_lingkungan']);
            \$table->string('nama_masalah', 255);
            \$table->text('deskripsi')->nullable();
            \$table->boolean('is_aktif')->default(true);
            \$table->timestamps();
        "
    ],
    [
        'name' => 'kunjungans',
        'fields' => "
            \$table->id();
            \$table->foreignId('pasien_id')->constrained('pasiens');
            \$table->string('nomor_kunjungan', 50)->unique();
            \$table->enum('tipe_kunjungan', ['mandiri', 'rujukan_internal', 'rujukan_eksternal'])->nullable();
            \$table->string('asal_rujukan', 200)->nullable();
            \$table->enum('status', ['terdaftar', 'dalam_pelayanan', 'selesai', 'batal'])->default('terdaftar');
            \$table->date('tanggal_kunjungan');
            \$table->timestamp('waktu_registrasi')->useCurrent();
            \$table->timestamp('waktu_selesai')->nullable();
            \$table->foreignId('perawat_id')->nullable()->constrained('penggunas');
            \$table->foreignId('dietisien_id')->nullable()->constrained('penggunas');
            \$table->foreignId('spgk_id')->nullable()->constrained('penggunas');
            \$table->foreignId('diagnosis_medis_utama_id')->nullable()->constrained('diagnosis_medis_utamas');
            \$table->json('diagnosis_medis_penyerta')->nullable();
            \$table->string('satusehat_encounter_id', 100)->nullable();
            \$table->boolean('dokumen_terkunci')->default(false);
            \$table->foreignId('dikunci_oleh')->nullable()->constrained('penggunas');
            \$table->timestamp('dikunci_pada')->nullable();
            \$table->timestamps();
        "
    ],
    [
        'name' => 'skrining_gizis',
        'fields' => "
            \$table->id();
            \$table->foreignId('kunjungan_id')->constrained('kunjungans');
            \$table->enum('metode_skrining', ['MNA', 'NRS2002', 'MST', 'MUST', 'STAMP']);
            \$table->tinyInteger('skor_penurunan_bb');
            \$table->tinyInteger('skor_penurunan_asupan');
            \$table->tinyInteger('skor_keparahan_penyakit');
            \$table->tinyInteger('skor_usia')->default(0);
            \$table->tinyInteger('total_skor');
            \$table->enum('kategori_risiko', ['risiko_rendah', 'risiko_sedang', 'risiko_tinggi']);
            \$table->text('rekomendasi_tindak_lanjut')->nullable();
            \$table->foreignId('dilakukan_oleh')->nullable()->constrained('penggunas');
            \$table->timestamps();
        "
    ],
    [
        'name' => 'data_antropometris',
        'fields' => "
            \$table->id();
            \$table->foreignId('kunjungan_id')->constrained('kunjungans');
            \$table->date('tanggal_pengukuran');
            \$table->text('berat_badan_kg');
            \$table->text('tinggi_badan_cm');
            \$table->text('imt')->nullable();
            \$table->enum('status_gizi_imt', ['sangat_kurus', 'kurus', 'normal', 'gemuk', 'obesitas_1', 'obesitas_2'])->nullable();
            \$table->text('lingkar_lengan_atas_cm')->nullable();
            \$table->text('lingkar_perut_cm')->nullable();
            \$table->text('panjang_lutut_cm')->nullable();
            \$table->text('tebal_lipatan_kulit_mm')->nullable();
            \$table->text('berat_badan_ideal_kg')->nullable();
            \$table->text('persentase_bbl')->nullable();
            \$table->boolean('is_pediatri')->default(false);
            \$table->tinyInteger('usia_tahun')->nullable();
            \$table->tinyInteger('usia_bulan')->nullable();
            \$table->decimal('zscore_bb_u', 5, 2)->nullable();
            \$table->decimal('zscore_tb_u', 5, 2)->nullable();
            \$table->decimal('zscore_imt_u', 5, 2)->nullable();
            \$table->enum('status_gizi_anak', ['gizi_buruk', 'gizi_kurang', 'gizi_baik', 'gizi_lebih', 'obesitas'])->nullable();
            \$table->foreignId('dicatat_oleh')->nullable()->constrained('penggunas');
            \$table->string('satusehat_obs_id', 100)->nullable();
            \$table->timestamps();
        "
    ],
    [
        'name' => 'data_biokimias',
        'fields' => "
            \$table->id();
            \$table->foreignId('kunjungan_id')->constrained('kunjungans');
            \$table->date('tanggal_pemeriksaan');
            \$table->enum('sumber_data', ['lab_internal', 'input_manual', 'satusehat'])->nullable();
            \$table->decimal('gula_darah_sewaktu', 8, 2)->nullable();
            \$table->decimal('gula_darah_puasa', 8, 2)->nullable();
            \$table->decimal('gula_darah_2jpp', 8, 2)->nullable();
            \$table->decimal('hba1c_persen', 5, 2)->nullable();
            \$table->decimal('kolesterol_total', 8, 2)->nullable();
            \$table->decimal('hdl', 8, 2)->nullable();
            \$table->decimal('ldl', 8, 2)->nullable();
            \$table->decimal('trigliserida', 8, 2)->nullable();
            \$table->decimal('albumin', 5, 2)->nullable();
            \$table->decimal('ureum', 8, 2)->nullable();
            \$table->decimal('kreatinin', 8, 2)->nullable();
            \$table->decimal('laju_filtrasi_gfr', 8, 2)->nullable();
            \$table->decimal('hemoglobin', 5, 2)->nullable();
            \$table->decimal('hematokrit', 5, 2)->nullable();
            \$table->decimal('neutrofil', 5, 2)->nullable();
            \$table->decimal('natrium', 8, 2)->nullable();
            \$table->decimal('kalium', 8, 2)->nullable();
            \$table->decimal('kalsium', 8, 2)->nullable();
            \$table->decimal('fosfor', 8, 2)->nullable();
            \$table->text('catatan_tambahan')->nullable();
            \$table->foreignId('dicatat_oleh')->nullable()->constrained('penggunas');
            \$table->timestamps();
        "
    ],
    [
        'name' => 'pemeriksaan_fisik_gizis',
        'fields' => "
            \$table->id();
            \$table->foreignId('kunjungan_id')->constrained('kunjungans');
            \$table->smallInteger('tekanan_darah_sistolik')->nullable();
            \$table->smallInteger('tekanan_darah_diastolik')->nullable();
            \$table->smallInteger('nadi_per_menit')->nullable();
            \$table->smallInteger('respirasi_per_menit')->nullable();
            \$table->decimal('suhu_celsius', 4, 1)->nullable();
            \$table->tinyInteger('saturasi_oksigen_persen')->nullable();
            \$table->boolean('edema')->default(false);
            \$table->string('lokasi_edema', 100)->nullable();
            \$table->json('tanda_defisiensi')->nullable();
            \$table->json('gangguan_gastrointestinal')->nullable();
            \$table->text('kondisi_mulut')->nullable();
            \$table->decimal('kekuatan_genggam_kg', 5, 2)->nullable();
            \$table->text('catatan_klinis')->nullable();
            \$table->foreignId('dicatat_oleh')->nullable()->constrained('penggunas');
            \$table->timestamps();
        "
    ],
    [
        'name' => 'riwayat_asupan_gizis',
        'fields' => "
            \$table->id();
            \$table->foreignId('kunjungan_id')->constrained('kunjungans');
            \$table->enum('metode', ['food_recall_24h', 'food_recall_48h', 'food_recall_72h', 'ffq_semi_kuantitatif']);
            \$table->date('tanggal_recall');
            \$table->json('detail_asupan');
            \$table->decimal('total_energi_kkal', 10, 2)->nullable();
            \$table->decimal('total_protein_gram', 10, 2)->nullable();
            \$table->decimal('total_lemak_gram', 10, 2)->nullable();
            \$table->decimal('total_karbohidrat_gram', 10, 2)->nullable();
            \$table->decimal('total_serat_gram', 10, 2)->nullable();
            \$table->decimal('total_natrium_mg', 10, 2)->nullable();
            \$table->decimal('persen_pemenuhan_energi', 5, 2)->nullable();
            \$table->decimal('persen_pemenuhan_protein', 5, 2)->nullable();
            \$table->decimal('persen_pemenuhan_lemak', 5, 2)->nullable();
            \$table->decimal('persen_pemenuhan_karbohidrat', 5, 2)->nullable();
            \$table->text('kesimpulan_asupan')->nullable();
            \$table->foreignId('dicatat_oleh')->nullable()->constrained('penggunas');
            \$table->timestamps();
        "
    ],
    [
        'name' => 'diagnosa_gizis',
        'fields' => "
            \$table->id();
            \$table->foreignId('kunjungan_id')->constrained('kunjungans');
            \$table->foreignId('terminologi_id')->constrained('terminologi_diagnosis_gizis');
            \$table->enum('domain', ['asupan', 'klinis', 'perilaku_lingkungan']);
            \$table->string('problem_masalah', 255);
            \$table->text('etiologi_penyebab');
            \$table->text('signs_symptoms');
            \$table->text('narasi_pes');
            \$table->tinyInteger('urutan_prioritas')->default(1);
            \$table->enum('status', ['aktif', 'teratasi', 'tidak_aktif'])->default('aktif');
            \$table->foreignId('divalidasi_oleh')->nullable()->constrained('penggunas');
            \$table->timestamp('divalidasi_pada')->nullable();
            \$table->string('satusehat_condition_id', 100)->nullable();
            \$table->foreignId('dicatat_oleh')->nullable()->constrained('penggunas');
            \$table->timestamps();
        "
    ],
    [
        'name' => 'preskripsi_diets',
        'fields' => "
            \$table->id();
            \$table->foreignId('kunjungan_id')->constrained('kunjungans');
            \$table->enum('formula_basal', ['harris_benedict', 'mifflin_st_jeor', 'who', 'konsensus_dm', 'konsensus_ckd']);
            \$table->enum('berat_badan_acuan', ['aktual', 'ideal', 'adjusted']);
            \$table->decimal('kebutuhan_energi_basal_kkal', 10, 2);
            \$table->decimal('faktor_aktivitas', 4, 2);
            \$table->decimal('faktor_stres', 4, 2);
            \$table->decimal('faktor_koreksi_usia', 4, 2)->default(1.0);
            \$table->decimal('total_kebutuhan_energi_kkal', 10, 2);
            \$table->decimal('persen_karbohidrat', 5, 2);
            \$table->decimal('gram_karbohidrat', 10, 2);
            \$table->decimal('persen_protein', 5, 2);
            \$table->decimal('gram_protein', 10, 2);
            \$table->decimal('persen_lemak', 5, 2);
            \$table->decimal('gram_lemak', 10, 2);
            \$table->decimal('gram_serat', 8, 2)->nullable();
            \$table->decimal('batas_natrium_mg', 10, 2)->nullable();
            \$table->decimal('batas_kalium_mg', 10, 2)->nullable();
            \$table->decimal('batas_fosfor_mg', 10, 2)->nullable();
            \$table->decimal('batas_cairan_ml', 10, 2)->nullable();
            \$table->enum('bentuk_makanan', ['biasa', 'lunak', 'saring', 'cair_penuh', 'cair_jernih', 'formula_medis']);
            \$table->tinyInteger('frekuensi_makan_utama')->default(3);
            \$table->tinyInteger('frekuensi_selingan')->default(2);
            \$table->json('pantangan_spesifik')->nullable();
            \$table->text('catatan_klinis')->nullable();
            \$table->text('tujuan_terapi');
            \$table->json('target_luaran_klinis')->nullable();
            \$table->date('tanggal_mulai');
            \$table->date('tanggal_evaluasi')->nullable();
            \$table->smallInteger('durasi_hari')->nullable();
            \$table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
            \$table->foreignId('dibuat_oleh')->constrained('penggunas');
            \$table->foreignId('disetujui_oleh')->nullable()->constrained('penggunas');
            \$table->timestamp('disetujui_pada')->nullable();
            \$table->string('satusehat_careplan_id', 100)->nullable();
            \$table->timestamps();
        "
    ],
    [
        'name' => 'detail_menu_harians',
        'fields' => "
            \$table->id();
            \$table->foreignId('preskripsi_diet_id')->constrained('preskripsi_diets');
            \$table->enum('waktu_makan', ['makan_pagi', 'selingan_pagi', 'makan_siang', 'selingan_sore', 'makan_malam', 'selingan_malam']);
            \$table->foreignId('bahan_makanan_id')->constrained('bahan_makanans');
            \$table->decimal('porsi_gram', 8, 2);
            \$table->decimal('energi_kkal', 8, 2);
            \$table->decimal('protein_gram', 8, 2);
            \$table->decimal('lemak_gram', 8, 2);
            \$table->decimal('karbohidrat_gram', 8, 2);
            \$table->text('keterangan_penukar')->nullable();
            \$table->timestamps();
        "
    ],
    [
        'name' => 'dokumen_edukasiis',
        'fields' => "
            \$table->id();
            \$table->foreignId('pasien_id')->constrained('pasiens');
            \$table->foreignId('kunjungan_id')->constrained('kunjungans');
            \$table->string('judul_dokumen', 255);
            \$table->enum('tipe', ['leaflet_diet', 'panduan_makan', 'ringkasan_kalori', 'pantangan_alergi', 'rencana_makan']);
            \$table->json('konten_json');
            \$table->string('path_pdf', 500)->nullable();
            \$table->string('token_akses', 100)->nullable();
            \$table->timestamp('token_expired_at')->nullable();
            \$table->foreignId('dibuat_oleh')->constrained('penggunas');
            \$table->timestamps();
        "
    ],
    [
        'name' => 'catatan_konselings',
        'fields' => "
            \$table->id();
            \$table->foreignId('kunjungan_id')->constrained('kunjungans');
            \$table->date('tanggal_konseling');
            \$table->smallInteger('durasi_menit')->nullable();
            \$table->enum('metode', ['tatap_muka', 'telepon', 'video_call']);
            \$table->json('topik_konseling');
            \$table->text('isi_konseling');
            \$table->text('hambatan_pasien')->nullable();
            \$table->text('kesepakatan_tindak_lanjut')->nullable();
            \$table->enum('tingkat_pemahaman_pasien', ['baik', 'cukup', 'kurang'])->nullable();
            \$table->foreignId('dokumen_edukasi_id')->nullable()->constrained('dokumen_edukasiis');
            \$table->foreignId('dilakukan_oleh')->constrained('penggunas');
            \$table->timestamps();
        "
    ],
    [
        'name' => 'monitorings',
        'fields' => "
            \$table->id();
            \$table->foreignId('kunjungan_id')->constrained('kunjungans');
            \$table->foreignId('kunjungan_sebelumnya_id')->nullable()->constrained('kunjungans');
            \$table->json('parameter_dipantau');
            \$table->text('evaluasi_anthropometri')->nullable();
            \$table->text('evaluasi_biokimia')->nullable();
            \$table->text('evaluasi_asupan')->nullable();
            \$table->enum('evaluasi_kepatuhan_diet', ['patuh', 'cukup_patuh', 'tidak_patuh'])->nullable();
            \$table->decimal('persen_sisa_makanan', 5, 2)->nullable();
            \$table->text('kesimpulan')->nullable();
            \$table->text('rekomendasi_lanjutan')->nullable();
            \$table->boolean('perlu_rujukan')->default(false);
            \$table->text('tujuan_rujukan')->nullable();
            \$table->date('rencana_kunjungan_berikutnya')->nullable();
            \$table->foreignId('dilakukan_oleh')->constrained('penggunas');
            \$table->timestamps();
        "
    ],
    [
        'name' => 'laporan_statistiks',
        'fields' => "
            \$table->id();
            \$table->enum('tipe_laporan', ['kinerja_harian', 'demografi_patologi', 'rasio_intervensi', 'spm_gizi', 'audit_mutu']);
            \$table->date('periode_dari');
            \$table->date('periode_sampai');
            \$table->json('parameter')->nullable();
            \$table->json('data_laporan');
            \$table->foreignId('dibuat_oleh')->constrained('penggunas');
            \$table->timestamps();
        "
    ],
    [
        'name' => 'login_histories',
        'fields' => "
            \$table->id();
            \$table->foreignId('pengguna_id')->constrained('penggunas');
            \$table->enum('tipe_event', ['login', 'logout', 'login_gagal', 'timeout']);
            \$table->string('ip_address', 45)->nullable();
            \$table->text('user_agent')->nullable();
            \$table->timestamps();
        "
    ]
];

$i = 1;
foreach($tables as $table) {
    $timestamp = date('Y_m_d_His', time() + $i);
    
    $content = "<?php\n\nuse Illuminate\Database\Migrations\Migration;\nuse Illuminate\Database\Schema\Blueprint;\nuse Illuminate\Support\Facades\Schema;\n\nreturn new class extends Migration\n{\n    public function up()\n    {\n        Schema::create('{$table['name']}', function (Blueprint \$table) {\n            {$table['fields']}\n        });\n    }\n\n    public function down()\n    {\n        Schema::dropIfExists('{$table['name']}');\n    }\n};\n";
    
    file_put_contents($migrationsDir . $timestamp . '_create_' . $table['name'] . '_table.php', $content);
    $i++;
}

echo "Migrations generated.\n";
