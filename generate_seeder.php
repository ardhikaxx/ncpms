<?php

$seederFile = __DIR__ . '/database/seeders/DatabaseSeeder.php';

$content = "<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        \$now = Carbon::now();

        // 1. Penggunas
        DB::table('penggunas')->insert([
            [
                'nama_lengkap' => 'dr. Andika Surya, Sp.GK',
                'email' => 'andika.spgk@ncpms.local',
                'password' => Hash::make('password123'),
                'peran' => 'spgk',
                'nomor_sip' => 'SIP.123.456.789',
                'status_aktif' => true,
                'created_at' => \$now, 'updated_at' => \$now
            ],
            [
                'nama_lengkap' => 'Sinta Maharani, S.Gz, Dietisien',
                'email' => 'sinta.dietisien@ncpms.local',
                'password' => Hash::make('password123'),
                'peran' => 'dietisien',
                'nomor_str' => 'STR.987.654.321',
                'status_aktif' => true,
                'created_at' => \$now, 'updated_at' => \$now
            ],
            [
                'nama_lengkap' => 'Budi Santoso, S.Gz',
                'email' => 'budi.nutrisionis@ncpms.local',
                'password' => Hash::make('password123'),
                'peran' => 'nutrisionis',
                'nomor_str' => 'STR.111.222.333',
                'status_aktif' => true,
                'created_at' => \$now, 'updated_at' => \$now
            ],
            [
                'nama_lengkap' => 'Rina Wijaya, S.Kep, Ners',
                'email' => 'rina.perawat@ncpms.local',
                'password' => Hash::make('password123'),
                'peran' => 'perawat',
                'nomor_str' => 'STR.555.666.777',
                'status_aktif' => true,
                'created_at' => \$now, 'updated_at' => \$now
            ],
            [
                'nama_lengkap' => 'Admin TI Sistem',
                'email' => 'admin@ncpms.local',
                'password' => Hash::make('password123'),
                'peran' => 'admin_ti',
                'nomor_str' => null,
                'status_aktif' => true,
                'created_at' => \$now, 'updated_at' => \$now
            ],
        ]);

        // 2. Pasiens
        \$pasienIds = [];
        \$pasiens = [
            ['RM000001', '3201010101900001', 'Ahmad Rizal', 'Bandung', '1990-01-01', 'L', 'O', '081234567890', 'Jl. Merdeka No. 1, Bandung', '0001112223334'],
            ['RM000002', '3201020202850002', 'Siti Aminah', 'Jakarta', '1985-02-02', 'P', 'A', '081987654321', 'Jl. Sudirman No. 2, Jakarta', '0002223334445'],
            ['RM000003', '3201030303750003', 'Bambang Supriyanto', 'Surabaya', '1975-03-03', 'L', 'B', '081112223334', 'Jl. Pahlawan No. 3, Surabaya', '0003334445556'],
        ];

        foreach (\$pasiens as \$idx => \$p) {
            \$pasienIds[] = DB::table('pasiens')->insertGetId([
                'nomor_rekam_medis' => encrypt(\$p[0]),
                'nik' => encrypt(\$p[1]),
                'nama_lengkap' => encrypt(\$p[2]),
                'tempat_lahir' => \$p[3],
                'tanggal_lahir' => \$p[4],
                'jenis_kelamin' => \$p[5],
                'golongan_darah' => \$p[6],
                'nomor_telepon' => encrypt(\$p[7]),
                'alamat' => encrypt(\$p[8]),
                'nomor_bpjs' => encrypt(\$p[9]),
                'status_aktif' => true,
                'created_at' => \$now, 'updated_at' => \$now
            ]);
        }

        // 3. Riwayat Alergi
        DB::table('riwayat_alergi_pasiens')->insert([
            [
                'pasien_id' => \$pasienIds[0],
                'jenis_alergi' => 'makanan',
                'nama_alergen' => encrypt('Seafood (Udang)'),
                'reaksi' => 'Gatal-gatal kemerahan pada kulit',
                'tingkat_keparahan' => 'sedang',
                'dicatat_oleh' => 4,
                'created_at' => \$now, 'updated_at' => \$now
            ]
        ]);

        // 4. Diagnosis Medis Utama
        DB::table('diagnosis_medis_utamas')->insert([
            ['kode_icd10' => 'E11.9', 'nama_diagnosis' => 'Type 2 diabetes mellitus without complications', 'kategori' => 'Endokrin', 'created_at' => \$now, 'updated_at' => \$now],
            ['kode_icd10' => 'I10', 'nama_diagnosis' => 'Essential (primary) hypertension', 'kategori' => 'Kardiovaskular', 'created_at' => \$now, 'updated_at' => \$now],
            ['kode_icd10' => 'E43', 'nama_diagnosis' => 'Unspecified severe protein-calorie malnutrition', 'kategori' => 'Malnutrisi', 'created_at' => \$now, 'updated_at' => \$now],
        ]);

        // 5. Bahan Makanan
        DB::table('bahan_makanans')->insert([
            ['nama_bahan' => 'Nasi Putih', 'kategori' => 'karbohidrat', 'porsi_standar_gram' => 100, 'energi_kkal' => 130, 'protein_gram' => 2.7, 'lemak_gram' => 0.3, 'karbohidrat_gram' => 28.6, 'created_at' => \$now, 'updated_at' => \$now],
            ['nama_bahan' => 'Daging Ayam Tanpa Kulit', 'kategori' => 'protein_hewani', 'porsi_standar_gram' => 50, 'energi_kkal' => 82.5, 'protein_gram' => 14, 'lemak_gram' => 2.5, 'karbohidrat_gram' => 0, 'created_at' => \$now, 'updated_at' => \$now],
            ['nama_bahan' => 'Tempe Kedelai', 'kategori' => 'protein_nabati', 'porsi_standar_gram' => 50, 'energi_kkal' => 96.5, 'protein_gram' => 9.5, 'lemak_gram' => 5.4, 'karbohidrat_gram' => 4.7, 'created_at' => \$now, 'updated_at' => \$now],
            ['nama_bahan' => 'Bayam Rebus', 'kategori' => 'sayuran', 'porsi_standar_gram' => 100, 'energi_kkal' => 23, 'protein_gram' => 3, 'lemak_gram' => 0.3, 'karbohidrat_gram' => 3.8, 'created_at' => \$now, 'updated_at' => \$now],
            ['nama_bahan' => 'Apel Merah', 'kategori' => 'buah', 'porsi_standar_gram' => 100, 'energi_kkal' => 52, 'protein_gram' => 0.3, 'lemak_gram' => 0.2, 'karbohidrat_gram' => 13.8, 'created_at' => \$now, 'updated_at' => \$now],
        ]);

        // 6. Terminologi Diagnosis Gizi
        DB::table('terminologi_diagnosis_gizis')->insert([
            ['kode_diagnosis' => 'NI-2.1', 'domain' => 'asupan', 'nama_masalah' => 'Asupan oral tidak adekuat', 'deskripsi' => 'Asupan makanan dan minuman melalui oral kurang dari kebutuhan', 'created_at' => \$now, 'updated_at' => \$now],
            ['kode_diagnosis' => 'NC-3.1', 'domain' => 'klinis', 'nama_masalah' => 'Berat badan kurang', 'deskripsi' => 'Berat badan di bawah standar yang direkomendasikan', 'created_at' => \$now, 'updated_at' => \$now],
            ['kode_diagnosis' => 'NB-1.1', 'domain' => 'perilaku_lingkungan', 'nama_masalah' => 'Kurang pengetahuan terkait makanan dan zat gizi', 'deskripsi' => 'Kurangnya informasi yang akurat terkait pola makan', 'created_at' => \$now, 'updated_at' => \$now],
        ]);

        // 7. Kunjungan
        \$kunjunganId = DB::table('kunjungans')->insertGetId([
            'pasien_id' => \$pasienIds[0],
            'nomor_kunjungan' => 'KGZ-20231015-0001',
            'tipe_kunjungan' => 'mandiri',
            'status' => 'dalam_pelayanan',
            'tanggal_kunjungan' => \$now->toDateString(),
            'waktu_registrasi' => \$now,
            'perawat_id' => 4,
            'dietisien_id' => 2,
            'diagnosis_medis_utama_id' => 1,
            'created_at' => \$now, 'updated_at' => \$now
        ]);

        // 8. Skrining Gizi
        DB::table('skrining_gizis')->insert([
            'kunjungan_id' => \$kunjunganId,
            'metode_skrining' => 'MST',
            'skor_penurunan_bb' => 1,
            'skor_penurunan_asupan' => 1,
            'skor_keparahan_penyakit' => 0,
            'total_skor' => 2,
            'kategori_risiko' => 'risiko_sedang',
            'dilakukan_oleh' => 4,
            'created_at' => \$now, 'updated_at' => \$now
        ]);

        // 9. Data Antropometri
        DB::table('data_antropometris')->insert([
            'kunjungan_id' => \$kunjunganId,
            'tanggal_pengukuran' => \$now->toDateString(),
            'berat_badan_kg' => encrypt('55.5'),
            'tinggi_badan_cm' => encrypt('165'),
            'imt' => encrypt('20.38'),
            'status_gizi_imt' => 'normal',
            'dicatat_oleh' => 4,
            'created_at' => \$now, 'updated_at' => \$now
        ]);

        // 10. Data Biokimia
        DB::table('data_biokimias')->insert([
            'kunjungan_id' => \$kunjunganId,
            'tanggal_pemeriksaan' => \$now->toDateString(),
            'sumber_data' => 'lab_internal',
            'gula_darah_puasa' => 145.5,
            'hba1c_persen' => 7.2,
            'kolesterol_total' => 210,
            'dicatat_oleh' => 2,
            'created_at' => \$now, 'updated_at' => \$now
        ]);
        
        // 11. Preskripsi Diet (CarePlan)
        DB::table('preskripsi_diets')->insert([
            'kunjungan_id' => \$kunjunganId,
            'formula_basal' => 'harris_benedict',
            'berat_badan_acuan' => 'aktual',
            'kebutuhan_energi_basal_kkal' => 1400,
            'faktor_aktivitas' => 1.3,
            'faktor_stres' => 1.1,
            'total_kebutuhan_energi_kkal' => 2002,
            'persen_karbohidrat' => 50,
            'gram_karbohidrat' => 250,
            'persen_protein' => 20,
            'gram_protein' => 100,
            'persen_lemak' => 30,
            'gram_lemak' => 66,
            'bentuk_makanan' => 'biasa',
            'frekuensi_makan_utama' => 3,
            'frekuensi_selingan' => 2,
            'tujuan_terapi' => 'Mengontrol kadar glukosa darah',
            'tanggal_mulai' => \$now->toDateString(),
            'status' => 'aktif',
            'dibuat_oleh' => 2,
            'disetujui_oleh' => 1,
            'disetujui_pada' => \$now,
            'created_at' => \$now, 'updated_at' => \$now
        ]);
    }
}
";

file_put_contents($seederFile, $content);

echo "DatabaseSeeder generated.\n";
