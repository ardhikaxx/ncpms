<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    private $firstNamesMale = ['Budi', 'Andi', 'Hendra', 'Eko', 'Agus', 'Dwi', 'Tri', 'Iwan', 'Rizal', 'Joko', 'Wahyu', 'Arif', 'Ahmad', 'Muhamad', 'Bayu', 'Rudi', 'Fajar', 'Deni', 'Yusuf', 'Ilham', 'Dimas', 'Reza', 'Fadli', 'Surya', 'Rama', 'Gilang', 'Rangga', 'Teguh', 'Bambang', 'Sigit', 'Hasan', 'Ali', 'Umar', 'Farhan', 'Rizki', 'Taufik', 'Iqbal', 'Lukman', 'Rahmat', 'Iman', 'Darmawan', 'Adi', 'Bagas', 'Satria', 'Wira', 'Cipto', 'Bowo', 'Gede', 'Made', 'Ketut'];
    private $firstNamesFemale = ['Siti', 'Ayu', 'Sri', 'Putri', 'Dewi', 'Nur', 'Rini', 'Dina', 'Sari', 'Indah', 'Lestari', 'Fitri', 'Rina', 'Wulan', 'Tari', 'Maya', 'Nita', 'Ratna', 'Lia', 'Rika', 'Ani', 'Yuni', 'Desi', 'Ika', 'Mega', 'Santi', 'Vina', 'Eka', 'Puspa', 'Dita', 'Intan', 'Tiara', 'Mutiara', 'Amira', 'Salma', 'Dinda', 'Nanda', 'Salsabila', 'Nadia', 'Risma', 'Aulia', 'Zahra', 'Kirana', 'Melati', 'Mawar', 'Anggun', 'Sekar', 'Kadek', 'Niuh', 'Gusti'];
    private $lastNames = ['Pratama', 'Santoso', 'Wijaya', 'Kusuma', 'Saputra', 'Setiawan', 'Nugroho', 'Hidayat', 'Kurniawan', 'Ramadhan', 'Lestari', 'Wahyuni', 'Susanti', 'Purnama', 'Wibowo', 'Siregar', 'Hutagalung', 'Gunawan', 'Hartono', 'Halim', 'Putra', 'Firmansyah', 'Ardiansyah', 'Baskoro', 'Sutanto', 'Subagyo', 'Suryono', 'Sudarsono', 'Wahyono', 'Nasution', 'Simanjuntak', 'Sitompul', 'Lubis', 'Harahap', 'Ginting', 'Sembiring', 'Tarigan', 'Mahendra', 'Widyanto', 'Susanto', 'Irawan', 'Budiarto', 'Rahman', 'Hakim', 'Fauzi', 'Setiadi', 'Sanjaya', 'Hermansyah', 'Sulaeman', 'Mulyadi'];
    private $cities = ['Kaliwates, Jember', 'Sumbersari, Jember', 'Patrang, Jember', 'Arjasa, Jember', 'Ajung, Jember', 'Ambulu, Jember', 'Balung, Jember', 'Bangsalsari, Jember', 'Kencong, Jember', 'Puger, Jember', 'Rambipuji, Jember', 'Sukowono, Jember', 'Tanggul, Jember', 'Wuluhan, Jember', 'Pakusari, Jember', 'Tempurejo, Jember', 'Umbulsari, Jember', 'Jombang, Jember', 'Mayang, Jember', 'Mumbulsari, Jember'];
    private $streets = ['Jl. PB Sudirman', 'Jl. Gajah Mada', 'Jl. Hayam Wuruk', 'Jl. Letjen Suprapto', 'Jl. Trunojoyo', 'Jl. Letjen S. Parman', 'Jl. Letjen Panjaitan', 'Jl. Kalimantan', 'Jl. Jawa', 'Jl. Mastrip', 'Jl. Riau', 'Jl. Sumatra', 'Jl. Letjen Haryono', 'Jl. Tidar', 'Jl. Ahmad Yani', 'Jl. Kartini', 'Jl. Basuki Rahmat', 'Jl. Brawijaya', 'Jl. KH. Shiddiq', 'Jl. Sultan Agung'];

    public function run(): void
    {
        $now = Carbon::now();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ([
            'login_histories', 'laporan_statistiks', 'monitorings', 'catatan_konselings', 'dokumen_edukasiis',
            'detail_menu_harians', 'preskripsi_kritis', 'preskripsi_diets', 'diagnosa_gizis', 'riwayat_asupan_gizis',
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
        
        $pasienIds = $this->seedPasienBulkCustom($now, $pengguna, 1000);

        $this->seedKlinisBulkCustom($now, $pengguna, $pasienIds, $diagnosisMedis, $terminologi, $bahan);
        $this->seedLaporan($now, $pengguna);
    }

    private function generateName($gender) {
        $first = $gender === 'L' ? $this->firstNamesMale[array_rand($this->firstNamesMale)] : $this->firstNamesFemale[array_rand($this->firstNamesFemale)];
        $last = $this->lastNames[array_rand($this->lastNames)];
        return "$first $last";
    }

    private function generateDateOfBirth() {
        $year = rand(1950, 2018);
        $month = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
        $day = str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
        return "$year-$month-$day";
    }

    private function generateNik() {
        $nik = '';
        for ($i=0; $i<16; $i++) $nik .= rand(0,9);
        return $nik;
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

    private function seedPasienBulkCustom(Carbon $now, array $pengguna, int $count): array
    {
        $ids = [];
        $gols = ['A', 'B', 'AB', 'O', 'tidak_diketahui'];
        
        for ($i = 1; $i <= $count; $i++) {
            $gender = rand(0, 1) === 0 ? 'L' : 'P';
            $nama = $this->generateName($gender);
            $nrm = 'RM-' . date('Y') . '-' . str_pad($i, 5, '0', STR_PAD_LEFT);
            $nik = $this->generateNik();
            $kota = $this->cities[array_rand($this->cities)];
            $jalan = $this->streets[array_rand($this->streets)];
            $alamat = "$jalan No. " . rand(1, 150) . ", $kota";
            $telepon = '08' . rand(1000000000, 9999999999);
            
            $pasienId = DB::table('pasiens')->insertGetId([
                'nomor_rekam_medis' => Crypt::encryptString($nrm),
                'nik' => Crypt::encryptString($nik),
                'nama_lengkap' => Crypt::encryptString($nama),
                'tempat_lahir' => $kota,
                'tanggal_lahir' => $this->generateDateOfBirth(),
                'jenis_kelamin' => $gender,
                'golongan_darah' => $gols[array_rand($gols)],
                'nomor_telepon' => Crypt::encryptString($telepon),
                'alamat' => Crypt::encryptString($alamat),
                'nomor_bpjs' => Crypt::encryptString(rand(1000000000000, 9999999999999)),
                'status_aktif' => true,
                'satusehat_patient_id' => 'P'.Str::upper(Str::random(10)),
                'created_at' => $now->copy()->subDays(rand(1, 700))->format('Y-m-d H:i:s'),
                'updated_at' => $now,
            ]);
            $ids[] = $pasienId;

            if (rand(1, 10) > 8) {
                $alergi = ['makanan', 'obat', 'lingkungan', 'lainnya'][rand(0, 3)];
                $keparahan = ['ringan', 'sedang', 'berat'][rand(0, 2)];
                DB::table('riwayat_alergi_pasiens')->insert([
                    'pasien_id' => $pasienId,
                    'jenis_alergi' => $alergi,
                    'nama_alergen' => Crypt::encryptString('Alergen Random ' . rand(1, 50)),
                    'reaksi' => 'Reaksi alergi ' . $keparahan,
                    'tingkat_keparahan' => $keparahan,
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
            // Karbohidrat
            ['Nasi Putih', 'karbohidrat', 100, 130, 2.4, 0.2, 28.6, 0.4, 1, 35],
            ['Beras Giling', 'karbohidrat', 100, 357, 8.4, 1.7, 77.1, 0.4, 1, 35],
            ['Singkong (Ubi Kayu)', 'karbohidrat', 100, 160, 1.2, 0.3, 38.1, 0.4, 1, 35],
            ['Kentang', 'karbohidrat', 100, 83, 2.0, 0.1, 19.1, 1.8, 6, 379],
            ['Roti Putih', 'karbohidrat', 100, 266, 8.9, 3.3, 49.0, 0.4, 1, 35],
            
            // Protein Hewani
            ['Daging Sapi (Tanpa Lemak)', 'protein_hewani', 100, 250, 26.0, 15.0, 0.0, 0.0, 37, 128],
            ['Telur Ayam Ras (Mentah)', 'protein_hewani', 100, 154, 12.4, 10.8, 0.7, 0.0, 71, 69],
            ['Daging Ayam (Dada)', 'protein_hewani', 100, 165, 31.0, 3.6, 0.0, 0.0, 37, 128],
            ['Ikan Lele Segar', 'protein_hewani', 100, 105, 18.0, 2.9, 0.0, 0.0, 34, 122],
            ['Susu Sapi Segar', 'minuman', 100, 61, 3.2, 3.5, 4.3, 0.0, 37, 128],
            
            // Protein Nabati
            ['Tempe Kedelai', 'protein_nabati', 100, 193, 18.5, 10.8, 9.4, 0.7, 5, 206],
            ['Tahu Putih', 'protein_nabati', 100, 76, 8.1, 4.8, 1.9, 0.1, 7, 87],
            ['Kacang Hijau', 'protein_nabati', 100, 347, 23.8, 1.2, 62.9, 0.7, 5, 206],
            
            // Sayuran
            ['Bayam Segar', 'sayuran', 100, 23, 2.9, 0.4, 3.6, 2.4, 70, 466],
            ['Kangkung Segar', 'sayuran', 100, 19, 3.4, 0.2, 3.1, 2.4, 70, 466],
            ['Wortel Segar', 'sayuran', 100, 41, 0.9, 0.2, 9.6, 3.0, 58, 235],
            
            // Buah
            ['Pisang Ambon', 'buah', 100, 89, 1.1, 0.3, 22.8, 1.6, 4, 257],
            ['Apel Segar', 'buah', 100, 52, 0.3, 0.2, 13.8, 2.4, 1, 107],
            ['Jeruk Manis', 'buah', 100, 47, 0.9, 0.1, 11.8, 1.6, 4, 257],
            ['Pepaya Segar', 'buah', 100, 43, 0.5, 0.1, 10.8, 1.6, 4, 257],

            // Lemak & Minyak
            ['Minyak Kelapa Sawit', 'lemak', 100, 884, 0.0, 100.0, 0.0, 0.0, 0, 0],
            ['Mentega (Butter)', 'lemak', 100, 717, 0.9, 81.1, 0.1, 0.0, 0, 0],

            // Formula Medis Kritis (Komersial)
            ['Formula Enteral Standar', 'minuman', 100, 100, 4.0, 3.3, 13.8, 0.0, 0, 0],
            ['Formula High Protein ICU', 'minuman', 100, 150, 10.0, 5.0, 16.2, 0.0, 0, 0],
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
                'sumber_data' => 'DKPI Kemenkes',
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

    private function seedKlinisBulkCustom(Carbon $now, array $pengguna, array $pasienIds, array $diagnosisMedis, array $terminologi, array $bahan): void
    {
        $dxKeys = array_keys($diagnosisMedis);
        $termKeys = array_keys($terminologi);
        $risikos = ['risiko_rendah', 'risiko_sedang', 'risiko_tinggi'];

        for ($i = 0; $i < count($pasienIds); $i++) {
            $pasienId = $pasienIds[$i];
            $numVisits = rand(1, 4);
            
            for ($v = 0; $v < $numVisits; $v++) {
                // Ensure the last 50 patients have a visit exactly today that is waiting for assessment
                $isTodayVisit = ($i >= count($pasienIds) - 50 && $v === $numVisits - 1);
                
                if ($isTodayVisit) {
                    $tanggal = Carbon::today(); // Force today without time components shifting it
                    $status = 'dalam_pelayanan';
                } else {
                    $tanggal = $now->copy()->subDays(rand(1, 300));
                    $status = 'selesai';
                }
                
                $kodeDx = $dxKeys[array_rand($dxKeys)];
                $risiko = $risikos[array_rand($risikos)];
                $bb = rand(400, 1200) / 10;
                $tb = rand(1400, 1850) / 10;
                
                $utamaId = $diagnosisMedis[$kodeDx];
                $penyertas = [];
                if (rand(1, 100) <= 60) {
                    $availablePenyerta = array_values(array_filter($diagnosisMedis, fn($id) => $id !== $utamaId));
                    $numPenyerta = rand(1, 2);
                    $penyertas = array_map(fn($k) => $availablePenyerta[$k], (array) array_rand($availablePenyerta, $numPenyerta));
                }

                $kunjunganId = DB::table('kunjungans')->insertGetId([
                    'pasien_id' => $pasienId,
                    'nomor_kunjungan' => 'KGZ-'.$tanggal->format('Ymd').'-'.str_pad($i, 3, '0', STR_PAD_LEFT).str_pad($v, 2, '0', STR_PAD_LEFT),
                    'tipe_kunjungan' => rand(0, 1) === 0 ? 'mandiri' : 'rujukan_internal',
                    'status' => $status,
                    'tanggal_kunjungan' => $tanggal->toDateString(),
                    'waktu_registrasi' => $tanggal->copy()->setTime(8, 0),
                    'perawat_id' => $pengguna['perawat'],
                    'dietisien_id' => $pengguna['dietisien'],
                    'spgk_id' => $pengguna['spgk'],
                    'diagnosis_medis_utama_id' => $utamaId,
                    'diagnosis_medis_penyerta' => json_encode($penyertas),
                    'dokumen_terkunci' => $v !== 0,
                    'dikunci_oleh' => $v !== 0 ? $pengguna['spgk'] : null,
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);

                DB::table('skrining_gizis')->insert([
                    'kunjungan_id' => $kunjunganId,
                    'metode_skrining' => 'MST',
                    'skor_penurunan_bb' => rand(0,2),
                    'skor_penurunan_asupan' => rand(0,1),
                    'skor_keparahan_penyakit' => rand(0,2),
                    'total_skor' => rand(0,5),
                    'kategori_risiko' => $risiko,
                    'dilakukan_oleh' => $pengguna['perawat'],
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);

                if ($status === 'selesai') {
                    $imt = round($bb / (($tb / 100) ** 2), 2);
                    $isPediatri = rand(1, 100) <= 20; // 20% anak-anak
                    $zbb = $isPediatri ? (rand(-30, 30) / 10) : null;
                    DB::table('data_antropometris')->insert([
                        'kunjungan_id' => $kunjunganId,
                        'tanggal_pengukuran' => $tanggal->toDateString(),
                        'berat_badan_kg' => Crypt::encryptString((string) $bb),
                        'tinggi_badan_cm' => Crypt::encryptString((string) $tb),
                        'imt' => Crypt::encryptString((string) $imt),
                        'status_gizi_imt' => $imt < 18.5 ? 'kurus' : ($imt <= 25 ? 'normal' : 'gemuk'),
                        'is_pediatri' => $isPediatri,
                        'usia_tahun' => $isPediatri ? rand(1, 17) : null,
                        'usia_bulan' => $isPediatri ? rand(0, 11) : null,
                        'zscore_bb_u' => $zbb,
                        'zscore_tb_u' => $isPediatri ? (rand(-30, 30) / 10) : null,
                        'zscore_imt_u' => $isPediatri ? (rand(-30, 30) / 10) : null,
                        'status_gizi_anak' => $isPediatri ? ($zbb < -2 ? 'gizi_kurang' : ($zbb > 2 ? 'gizi_lebih' : 'gizi_baik')) : null,
                        'dicatat_oleh' => $pengguna['nutrisionis'],
                        'created_at' => $tanggal,
                        'updated_at' => $tanggal,
                    ]);

                    DB::table('pemeriksaan_fisik_gizis')->insert([
                        'kunjungan_id' => $kunjunganId,
                        'tekanan_darah_sistolik' => rand(100, 160),
                        'tekanan_darah_diastolik' => rand(70, 100),
                        'nadi_per_menit' => rand(60, 100),
                        'suhu_celsius' => 36.5,
                        'kondisi_mulut' => 'Normal',
                        'dicatat_oleh' => $pengguna['perawat'],
                        'created_at' => $tanggal,
                        'updated_at' => $tanggal,
                    ]);
                    
                    $term = $termKeys[array_rand($termKeys)];
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

                    $formulas = ['harris_benedict', 'mifflin_st_jeor', 'who', 'konsensus_dm', 'konsensus_ckd'];
                    $acuans = ['aktual', 'ideal', 'adjusted'];
                    $bentukMakans = ['biasa', 'lunak', 'saring', 'cair_penuh', 'cair_jernih', 'formula_medis'];
                    $tujuanTerapis = [
                        'Meningkatkan asupan oral secara bertahap',
                        'Mencapai berat badan ideal',
                        'Mengendalikan kadar glukosa darah',
                        'Mengurangi retensi cairan',
                        'Mendukung proses penyembuhan luka',
                        'Mencegah malnutrisi lebih lanjut',
                        'Menurunkan tekanan darah'
                    ];

                    $preskripsiId = DB::table('preskripsi_diets')->insertGetId([
                        'kunjungan_id' => $kunjunganId,
                        'formula_basal' => $formulas[array_rand($formulas)],
                        'berat_badan_acuan' => $acuans[array_rand($acuans)],
                        'kebutuhan_energi_basal_kkal' => rand(1200, 1500),
                        'faktor_aktivitas' => rand(11, 14) / 10,
                        'faktor_stres' => rand(10, 15) / 10,
                        'total_kebutuhan_energi_kkal' => rand(1500, 2200),
                        'persen_karbohidrat' => 50,
                        'gram_karbohidrat' => rand(200, 300),
                        'persen_protein' => 20,
                        'gram_protein' => rand(50, 80),
                        'persen_lemak' => 30,
                        'gram_lemak' => rand(40, 70),
                        'bentuk_makanan' => $bentukMakans[array_rand($bentukMakans)],
                        'frekuensi_makan_utama' => 3,
                        'frekuensi_selingan' => rand(1, 3),
                        'tujuan_terapi' => $tujuanTerapis[array_rand($tujuanTerapis)],
                        'tanggal_mulai' => $tanggal->toDateString(),
                        'status' => 'selesai',
                        'dibuat_oleh' => $pengguna['dietisien'],
                        'created_at' => $tanggal,
                        'updated_at' => $tanggal,
                    ]);

                    if (rand(1, 100) <= 15) { // 15% peluang preskripsi nutrisi kritis
                        $jenis = ['enteral', 'parenteral', 'kombinasi'];
                        $rute = ['NGT', 'NDT', 'Central IV', 'Peripheral IV'];
                        $formulas = ['Peptamen', 'Nutrican', 'Aminofusin', 'Clinimix', 'Ensure', 'Pan-Enteral'];
                        DB::table('preskripsi_kritis')->insert([
                            'kunjungan_id' => $kunjunganId,
                            'jenis_nutrisi' => $jenis[array_rand($jenis)],
                            'rute_pemberian' => $rute[array_rand($rute)],
                            'nama_formula' => $formulas[array_rand($formulas)],
                            'volume_ml' => rand(500, 2000),
                            'frekuensi_sehari' => rand(4, 6),
                            'kecepatan_pemberian' => rand(20, 60),
                            'total_kalori_kkal' => rand(1000, 2000),
                            'total_protein_gram' => rand(40, 80),
                            'total_lemak_gram' => rand(30, 60),
                            'total_karbohidrat_gram' => rand(150, 250),
                            'instruksi_khusus' => 'Bilas selang dengan air hangat 30ml setiap selesai pemberian. Observasi residu lambung.',
                            'dicatat_oleh' => $pengguna['dietisien'],
                            'created_at' => $tanggal,
                            'updated_at' => $tanggal,
                        ]);
                    }

                    $waktuMakan = ['makan_pagi', 'selingan_pagi', 'makan_siang', 'selingan_sore', 'makan_malam'];
                    $pengolahans = ['Rebus', 'Kukus', 'Panggang', 'Tumis Sedikit Minyak', 'Bakar', 'Saring'];
                    foreach ($waktuMakan as $waktu) {
                        DB::table('detail_menu_harians')->insert([
                            'preskripsi_diet_id' => $preskripsiId,
                            'waktu_makan' => $waktu,
                            'bahan_makanan_id' => $bahan[array_rand($bahan)],
                            'porsi_gram' => rand(50, 150),
                            'energi_kkal' => rand(100, 300),
                            'protein_gram' => rand(5, 20),
                            'lemak_gram' => rand(5, 15),
                            'karbohidrat_gram' => rand(20, 50),
                            'keterangan_penukar' => $pengolahans[array_rand($pengolahans)],
                            'created_at' => $tanggal,
                            'updated_at' => $tanggal,
                        ]);
                    }

                    if (rand(1, 100) <= 50) {
                        $topikEdukasis = ['Panduan Diet DM', 'Diet ETPT', 'Diet Rendah Garam', 'Edukasi Penurunan BB', 'Diet Penyakit Ginjal'];
                        $tipes = ['leaflet_diet', 'panduan_makan', 'ringkasan_kalori', 'pantangan_alergi', 'rencana_makan'];
                        $edukasiId = DB::table('dokumen_edukasiis')->insertGetId([
                            'pasien_id' => $pasienId,
                            'kunjungan_id' => $kunjunganId,
                            'judul_dokumen' => $topikEdukasis[array_rand($topikEdukasis)],
                            'tipe' => $tipes[array_rand($tipes)],
                            'konten_json' => json_encode([
                                'poin_utama' => 'Tingkatkan konsumsi serat dan kurangi gula sederhana',
                                'target' => 'Gula darah stabil dan BB ideal',
                                'pantangan' => 'Gorengan, makanan cepat saji'
                            ]),
                            'dibuat_oleh' => $pengguna['dietisien'],
                            'created_at' => $tanggal,
                            'updated_at' => $tanggal,
                        ]);

                        $metodes = ['tatap_muka', 'telepon', 'video_call'];
                        $tingkats = ['baik', 'cukup', 'kurang'];
                        DB::table('catatan_konselings')->insert([
                            'kunjungan_id' => $kunjunganId,
                            'tanggal_konseling' => $tanggal->toDateString(),
                            'durasi_menit' => rand(15, 60),
                            'metode' => $metodes[array_rand($metodes)],
                            'topik_konseling' => json_encode(['Pemahaman Diet', 'Cara Pengolahan Makanan']),
                            'isi_konseling' => 'Menjelaskan prinsip diet dan bahan makanan penukar. Pasien bertanya mengenai batasan buah-buahan.',
                            'hambatan_pasien' => rand(0, 1) === 1 ? 'Kurang motivasi untuk mengubah kebiasaan' : null,
                            'kesepakatan_tindak_lanjut' => 'Keluarga akan membantu mengawasi asupan di rumah',
                            'tingkat_pemahaman_pasien' => $tingkats[array_rand($tingkats)],
                            'dokumen_edukasi_id' => $edukasiId,
                            'dilakukan_oleh' => $pengguna['dietisien'],
                            'created_at' => $tanggal,
                            'updated_at' => $tanggal,
                        ]);
                    }

                    if (rand(1, 100) <= 70) {
                        $kepatuhans = ['patuh', 'cukup_patuh', 'tidak_patuh'];
                        $evaluasiBb = ['BB stabil', 'Terjadi penurunan BB 0.5kg', 'BB naik 1kg', 'Masih underweight'];
                        $kesimpulans = ['Kondisi klinis membaik', 'Asupan masih perlu ditingkatkan', 'Gula darah mulai terkontrol', 'Pasien kurang kooperatif', 'Tercapai target kalori >80%'];
                        DB::table('monitorings')->insert([
                            'kunjungan_id' => $kunjunganId,
                            'parameter_dipantau' => json_encode(['Berat Badan', 'Asupan Energi', 'Kadar Glukosa', 'Tekanan Darah']),
                            'evaluasi_kepatuhan_diet' => $kepatuhans[array_rand($kepatuhans)],
                            'evaluasi_anthropometri' => $evaluasiBb[array_rand($evaluasiBb)],
                            'evaluasi_asupan' => 'Asupan ' . rand(50, 100) . '% dari kebutuhan',
                            'kesimpulan' => $kesimpulans[array_rand($kesimpulans)],
                            'dilakukan_oleh' => $pengguna['dietisien'],
                            'created_at' => $tanggal,
                            'updated_at' => $tanggal,
                        ]);
                    }
                }
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

        // Seed Audit Logs dummy
        $models = ['App\Models\Kunjungan', 'App\Models\PreskripsiDiet', 'App\Models\Pasien'];
        $actions = ['view', 'create', 'update', 'print', 'lock'];
        for ($i=0; $i<20; $i++) {
            $action = $actions[array_rand($actions)];
            $model = $models[array_rand($models)];
            
            $desc = '';
            if ($action === 'view') $desc = "Melihat detail data pasien/kunjungan";
            elseif ($action === 'create') $desc = "Membuat data baru";
            elseif ($action === 'update') $desc = "Memperbarui catatan medis";
            elseif ($action === 'print') $desc = "Mencetak dokumen PAGT / Etiket";
            elseif ($action === 'lock') $desc = "Mengunci dokumen menggunakan TTE";

            DB::table('audit_logs')->insert([
                'user_id' => array_rand(array_flip([1, 2, 3, 4, 5])),
                'action' => $action,
                'model_type' => $model,
                'model_id' => rand(1, 50),
                'deskripsi' => $desc,
                'ip_address' => '192.168.1.' . rand(10, 100),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/119.0.0.0 Safari/537.36',
                'created_at' => $now->copy()->subHours(rand(1, 72)),
                'updated_at' => $now->copy()->subHours(rand(1, 72)),
            ]);
        }
    }
}
