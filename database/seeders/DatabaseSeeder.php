<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ([
            'login_histories', 'laporan_statistiks', 'monitorings', 'catatan_konselings', 'dokumen_edukasiis',
            'detail_menu_harians', 'preskripsi_diets', 'diagnosa_gizis', 'riwayat_asupan_gizis',
            'pemeriksaan_fisik_gizis', 'data_biokimias', 'data_antropometris', 'skrining_gizis',
            'kunjungans', 'terminologi_diagnosis_gizis', 'bahan_makanans', 'diagnosis_medis_utamas',
            'riwayat_alergi_pasiens', 'sesi_aktifs', 'pasiens', 'penggunas',
        ] as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $pengguna = $this->seedPengguna($now);
        $diagnosisMedis = $this->seedDiagnosisMedis($now);
        $bahan = $this->seedBahanMakanan($now);
        $terminologi = $this->seedTerminologi($now);
        
        $faker = Faker::create('id_ID');
        $pasienIds = $this->seedPasienBulk($now, $pengguna, $faker, 200);

        $this->seedKlinisBulk($now, $pengguna, $pasienIds, $diagnosisMedis, $terminologi, $bahan, $faker);
        $this->seedLaporan($now, $pengguna);
    }

    private function seedPengguna(Carbon $now): array
    {
        $rows = [
            'spgk' => ['dr. Andika Surya, Sp.GK', 'andika.spgk@ncpms.local', 'spgk', 'SIP.3171.2026.0001', 'STR-DOK-3171-0001', 'Klinik Gizi Medik'],
            'dietisien' => ['Sinta Maharani, S.Gz, RD', 'sinta.dietisien@ncpms.local', 'dietisien', null, 'STR-GZ-3171-0002', 'Instalasi Gizi Rawat Jalan'],
            'nutrisionis' => ['Budi Santoso, S.Gz', 'budi.nutrisionis@ncpms.local', 'nutrisionis', null, 'STR-GZ-3171-0003', 'Instalasi Gizi Rawat Jalan'],
            'perawat' => ['Rina Wijaya, S.Kep, Ners', 'rina.perawat@ncpms.local', 'perawat', null, 'STR-PRW-3171-0004', 'Poliklinik Penyakit Dalam'],
            'admin' => ['Admin TI NCPMS', 'admin@ncpms.local', 'admin_ti', null, null, 'Unit Teknologi Informasi'],
        ];

        $ids = [];
        foreach ($rows as $key => [$nama, $email, $peran, $sip, $str, $unit]) {
            $ids[$key] = DB::table('penggunas')->insertGetId([
                'nama_lengkap' => $nama,
                'email' => $email,
                'password' => Hash::make('password123'),
                'peran' => $peran,
                'nomor_sip' => $sip,
                'nomor_str' => $str,
                'unit_kerja' => $unit,
                'status_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        return $ids;
    }

    private function seedPasienBulk(Carbon $now, array $pengguna, \Faker\Generator $faker, int $count): array
    {
        $ids = [];
        $gols = ['A', 'B', 'AB', 'O', 'tidak_diketahui'];
        
        for ($i = 1; $i <= $count; $i++) {
            $gender = $faker->randomElement(['L', 'P']);
            $nama = $gender === 'L' ? $faker->firstNameMale . ' ' . $faker->lastName : $faker->firstNameFemale . ' ' . $faker->lastName;
            $nrm = 'RM-' . date('Y') . '-' . str_pad($i, 5, '0', STR_PAD_LEFT);
            $nik = $faker->numerify('################');
            
            $pasienId = DB::table('pasiens')->insertGetId([
                'nomor_rekam_medis' => Crypt::encryptString($nrm),
                'nik' => Crypt::encryptString($nik),
                'nama_lengkap' => Crypt::encryptString($nama),
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->dateTimeBetween('-70 years', '-5 years')->format('Y-m-d'),
                'jenis_kelamin' => $gender,
                'golongan_darah' => $faker->randomElement($gols),
                'nomor_telepon' => Crypt::encryptString($faker->phoneNumber),
                'alamat' => Crypt::encryptString($faker->address),
                'nomor_bpjs' => Crypt::encryptString($faker->numerify('#############')),
                'status_aktif' => true,
                'satusehat_patient_id' => 'P'.Str::upper(Str::random(10)),
                'created_at' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s'),
                'updated_at' => $now,
            ]);
            $ids[] = $pasienId;

            if ($faker->boolean(15)) {
                DB::table('riwayat_alergi_pasiens')->insert([
                    'pasien_id' => $pasienId,
                    'jenis_alergi' => $faker->randomElement(['makanan', 'obat', 'lingkungan', 'lainnya']),
                    'nama_alergen' => Crypt::encryptString($faker->word),
                    'reaksi' => $faker->sentence,
                    'tingkat_keparahan' => $faker->randomElement(['ringan', 'sedang', 'berat']),
                    'dicatat_oleh' => $pengguna['perawat'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        return $ids;
    }

    private function seedDiagnosisMedis(Carbon $now): array
    {
        $rows = [
            ['E11.9', 'Diabetes melitus tipe 2 tanpa komplikasi', 'Endokrin'],
            ['I10', 'Hipertensi esensial', 'Kardiovaskular'],
            ['N18.3', 'Penyakit ginjal kronik stadium 3', 'Ginjal'],
            ['E43', 'Malnutrisi energi-protein berat', 'Malnutrisi'],
            ['K76.0', 'Fatty liver, tidak diklasifikasikan di tempat lain', 'Hepatobilier'],
            ['E66.9', 'Obesitas, tidak spesifik', 'Metabolik'],
            ['I20', 'Angina pectoris', 'Kardiovaskular'],
            ['J44.9', 'Penyakit paru obstruktif kronik', 'Respirasi'],
        ];

        $ids = [];
        foreach ($rows as [$kode, $nama, $kategori]) {
            $ids[$kode] = DB::table('diagnosis_medis_utamas')->insertGetId([
                'kode_icd10' => $kode,
                'nama_diagnosis' => $nama,
                'kategori' => $kategori,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return $ids;
    }

    private function seedBahanMakanan(Carbon $now): array
    {
        $rows = [
            ['Nasi putih', 'karbohidrat', 100, 130, 2.7, 0.3, 28.6, 0.4, 1, 35],
            ['Kentang rebus', 'karbohidrat', 100, 87, 1.9, 0.1, 20.1, 1.8, 6, 379],
            ['Ayam dada tanpa kulit', 'protein_hewani', 50, 82.5, 15.5, 1.8, 0, 0, 37, 128],
            ['Ikan kembung', 'protein_hewani', 50, 83, 10.5, 4.2, 0, 0, 34, 122],
            ['Telur ayam', 'protein_hewani', 55, 85, 6.8, 6.2, 0.6, 0, 71, 69],
            ['Tempe kedelai', 'protein_nabati', 50, 96.5, 9.5, 5.4, 4.7, 0.7, 5, 206],
            ['Tahu putih', 'protein_nabati', 100, 80, 10.9, 4.7, 0.8, 0.1, 7, 87],
            ['Bayam rebus', 'sayuran', 100, 23, 3, 0.3, 3.8, 2.4, 70, 466],
            ['Wortel rebus', 'sayuran', 100, 35, 0.8, 0.2, 8.2, 3, 58, 235],
            ['Pepaya', 'buah', 100, 46, 0.5, 0.1, 12.2, 1.6, 4, 257],
            ['Apel merah', 'buah', 100, 52, 0.3, 0.2, 13.8, 2.4, 1, 107],
            ['Minyak kanola', 'lemak', 5, 45, 0, 5, 0, 0, 0, 0],
        ];

        $ids = [];
        foreach ($rows as [$nama, $kategori, $porsi, $energi, $protein, $lemak, $karbo, $serat, $natrium, $kalium]) {
            $ids[$nama] = DB::table('bahan_makanans')->insertGetId([
                'nama_bahan' => $nama,
                'kategori' => $kategori,
                'porsi_standar_gram' => $porsi,
                'energi_kkal' => $energi,
                'protein_gram' => $protein,
                'lemak_gram' => $lemak,
                'karbohidrat_gram' => $karbo,
                'serat_gram' => $serat,
                'natrium_mg' => $natrium,
                'kalium_mg' => $kalium,
                'sumber_data' => 'TKPI Indonesia 2019',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        return $ids;
    }

    private function seedTerminologi(Carbon $now): array
    {
        $rows = [
            ['NI-2.1', 'asupan', 'Asupan oral tidak adekuat', 'Asupan makanan/minuman oral kurang dari kebutuhan.'],
            ['NI-5.8.4', 'asupan', 'Asupan karbohidrat inkonsisten', 'Distribusi karbohidrat tidak sesuai rekomendasi.'],
            ['NC-2.2', 'klinis', 'Perubahan nilai laboratorium terkait gizi', 'Nilai biokimia terkait gizi di luar target klinis.'],
            ['NC-3.3', 'klinis', 'Kelebihan berat badan/obesitas', 'Akumulasi lemak tubuh berlebih.'],
            ['NB-1.1', 'perilaku_lingkungan', 'Kurang pengetahuan terkait makanan dan gizi', 'Kurang informasi akurat terkait terapi diet.'],
            ['NB-2.1', 'perilaku_lingkungan', 'Aktivitas fisik kurang', 'Aktivitas fisik di bawah rekomendasi.'],
        ];

        $ids = [];
        foreach ($rows as [$kode, $domain, $nama, $deskripsi]) {
            $ids[$kode] = DB::table('terminologi_diagnosis_gizis')->insertGetId([
                'kode_diagnosis' => $kode,
                'domain' => $domain,
                'nama_masalah' => $nama,
                'deskripsi' => $deskripsi,
                'is_aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        return $ids;
    }

    private function seedKlinisBulk(Carbon $now, array $pengguna, array $pasienIds, array $diagnosisMedis, array $terminologi, array $bahan, \Faker\Generator $faker): void
    {
        $dxKeys = array_keys($diagnosisMedis);
        $termKeys = array_keys($terminologi);

        for ($i = 0; $i < count($pasienIds); $i++) {
            $pasienId = $pasienIds[$i];
            
            $numVisits = $faker->numberBetween(1, 4);
            
            for ($v = 0; $v < $numVisits; $v++) {
                $tanggal = Carbon::parse($faker->dateTimeBetween('-1 year', 'now'));
                $kodeDx = $faker->randomElement($dxKeys);
                $risiko = $faker->randomElement(['risiko_rendah', 'risiko_sedang', 'risiko_tinggi']);
                $bb = $faker->randomFloat(1, 40, 120);
                $tb = $faker->randomFloat(1, 140, 185);
                
                $kunjunganId = DB::table('kunjungans')->insertGetId([
                    'pasien_id' => $pasienId,
                    'nomor_kunjungan' => 'KGZ-'.$tanggal->format('Ymd').'-'.str_pad(mt_rand(1,9999), 4, '0', STR_PAD_LEFT),
                    'tipe_kunjungan' => $faker->randomElement(['mandiri', 'rujukan_internal']),
                    'status' => $v === 0 && $i < 20 ? 'dalam_pelayanan' : 'selesai',
                    'tanggal_kunjungan' => $tanggal->toDateString(),
                    'waktu_registrasi' => $tanggal->copy()->setTime(8, 0),
                    'perawat_id' => $pengguna['perawat'],
                    'dietisien_id' => $pengguna['dietisien'],
                    'spgk_id' => $pengguna['spgk'],
                    'diagnosis_medis_utama_id' => $diagnosisMedis[$kodeDx],
                    'diagnosis_medis_penyerta' => json_encode([]),
                    'dokumen_terkunci' => $v !== 0,
                    'dikunci_oleh' => $v !== 0 ? $pengguna['spgk'] : null,
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);

                DB::table('skrining_gizis')->insert([
                    'kunjungan_id' => $kunjunganId,
                    'metode_skrining' => 'MST',
                    'skor_penurunan_bb' => $faker->numberBetween(0,2),
                    'skor_penurunan_asupan' => $faker->numberBetween(0,1),
                    'skor_keparahan_penyakit' => $faker->numberBetween(0,2),
                    'total_skor' => $faker->numberBetween(0,5),
                    'kategori_risiko' => $risiko,
                    'dilakukan_oleh' => $pengguna['perawat'],
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);

                $imt = round($bb / (($tb / 100) ** 2), 2);
                DB::table('data_antropometris')->insert([
                    'kunjungan_id' => $kunjunganId,
                    'tanggal_pengukuran' => $tanggal->toDateString(),
                    'berat_badan_kg' => Crypt::encryptString((string) $bb),
                    'tinggi_badan_cm' => Crypt::encryptString((string) $tb),
                    'imt' => Crypt::encryptString((string) $imt),
                    'status_gizi_imt' => $imt < 18.5 ? 'kurus' : ($imt <= 25 ? 'normal' : 'gemuk'),
                    'dicatat_oleh' => $pengguna['nutrisionis'],
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);

                DB::table('pemeriksaan_fisik_gizis')->insert([
                    'kunjungan_id' => $kunjunganId,
                    'tekanan_darah_sistolik' => $faker->numberBetween(100, 160),
                    'tekanan_darah_diastolik' => $faker->numberBetween(70, 100),
                    'nadi_per_menit' => $faker->numberBetween(60, 100),
                    'suhu_celsius' => 36.5,
                    'kondisi_mulut' => 'Normal',
                    'dicatat_oleh' => $pengguna['perawat'],
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);
                
                $term = $faker->randomElement($termKeys);
                $problem = DB::table('terminologi_diagnosis_gizis')->where('id', $terminologi[$term])->value('nama_masalah');
                
                DB::table('diagnosa_gizis')->insert([
                    'kunjungan_id' => $kunjunganId,
                    'terminologi_id' => $terminologi[$term],
                    'domain' => 'klinis',
                    'problem_masalah' => $problem,
                    'etiologi_penyebab' => 'kondisi klinis terkait',
                    'signs_symptoms' => 'berdasarkan asesmen awal',
                    'narasi_pes' => Crypt::encryptString($problem . ' berkaitan dengan kondisi klinis terkait.'),
                    'dicatat_oleh' => $pengguna['dietisien'],
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);
            }
        }
    }

    private function seedLaporan(Carbon $now, array $pengguna): void
    {
        DB::table('laporan_statistiks')->insert([
            'tipe_laporan' => 'kinerja_harian',
            'periode_dari' => $now->copy()->startOfMonth()->toDateString(),
            'periode_sampai' => $now->toDateString(),
            'data_laporan' => json_encode(['kunjungan' => DB::table('kunjungans')->count()]),
            'dibuat_oleh' => $pengguna['spgk'],
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
