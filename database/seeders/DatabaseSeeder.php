<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $pasien = $this->seedPasien($now, $pengguna);

        $this->seedKlinis($now, $pengguna, $pasien, $diagnosisMedis, $terminologi, $bahan);
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

    private function seedPasien(Carbon $now, array $pengguna): array
    {
        $rows = [
            ['RM-2026-0001', '3171010101760001', 'Ahmad Rizal Pratama', 'Jakarta', '1976-01-01', 'L', 'O', '081234567801', 'Jl. Cempaka Putih Tengah No. 14, Jakarta Pusat', '0001882211001'],
            ['RM-2026-0002', '3171021202840002', 'Siti Aminah Lestari', 'Jakarta', '1984-02-12', 'P', 'A', '081234567802', 'Jl. Anggrek Raya No. 8, Jakarta Barat', '0001882211002'],
            ['RM-2026-0003', '3273012303690003', 'Bambang Supriyanto', 'Bandung', '1969-03-23', 'L', 'B', '081234567803', 'Jl. Sukajadi No. 22, Bandung', '0001882211003'],
            ['RM-2026-0004', '3674011705920004', 'Nur Aisyah Putri', 'Tangerang', '1992-05-17', 'P', 'AB', '081234567804', 'Perum Griya Sehat Blok B2, Tangerang', '0001882211004'],
            ['RM-2026-0005', '3173050910580005', 'Hendra Gunawan', 'Jakarta', '1958-10-09', 'L', 'O', '081234567805', 'Jl. Melati Selatan No. 7, Jakarta Selatan', '0001882211005'],
            ['RM-2026-0006', '3172042806010006', 'Maya Kartika Dewi', 'Depok', '2001-06-28', 'P', 'A', '081234567806', 'Jl. Margonda Raya Gang Kenanga, Depok', '0001882211006'],
            ['RM-2026-0007', '3175011507150007', 'Rafi Alfarizi', 'Bekasi', '2015-07-15', 'L', 'tidak_diketahui', '081234567807', 'Jl. Patriot No. 10, Bekasi', '0001882211007'],
            ['RM-2026-0008', '3173031111880008', 'Dewi Larasati', 'Jakarta', '1988-11-11', 'P', 'B', '081234567808', 'Jl. Tebet Timur Dalam No. 3, Jakarta Selatan', '0001882211008'],
        ];

        $ids = [];
        foreach ($rows as $row) {
            $ids[] = DB::table('pasiens')->insertGetId([
                'nomor_rekam_medis' => encrypt($row[0]),
                'nik' => encrypt($row[1]),
                'nama_lengkap' => encrypt($row[2]),
                'tempat_lahir' => $row[3],
                'tanggal_lahir' => $row[4],
                'jenis_kelamin' => $row[5],
                'golongan_darah' => $row[6],
                'nomor_telepon' => encrypt($row[7]),
                'alamat' => encrypt($row[8]),
                'nomor_bpjs' => encrypt($row[9]),
                'status_aktif' => true,
                'satusehat_patient_id' => 'P'.Str::upper(Str::random(10)),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $alergi = [
            [$ids[0], 'makanan', 'Udang dan kepiting', 'Urtikaria dan gatal seluruh tubuh', 'sedang'],
            [$ids[2], 'obat', 'Metformin immediate release', 'Mual berat dan diare', 'ringan'],
            [$ids[6], 'makanan', 'Susu sapi', 'Diare dan kembung', 'sedang'],
        ];

        foreach ($alergi as [$pasienId, $jenis, $nama, $reaksi, $tingkat]) {
            DB::table('riwayat_alergi_pasiens')->insert([
                'pasien_id' => $pasienId,
                'jenis_alergi' => $jenis,
                'nama_alergen' => encrypt($nama),
                'reaksi' => $reaksi,
                'tingkat_keparahan' => $tingkat,
                'dicatat_oleh' => $pengguna['perawat'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
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
                'sumber_data' => 'TKPI Indonesia 2019 - contoh operasional',
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

    private function seedKlinis(Carbon $now, array $pengguna, array $pasien, array $diagnosisMedis, array $terminologi, array $bahan): void
    {
        $kasus = [
            [$pasien[0], 'E11.9', 'risiko_tinggi', 82.4, 165, 8.1, 178, 7.8, 'Asupan karbohidrat tidak teratur dan sering minum teh manis.'],
            [$pasien[1], 'I10', 'risiko_sedang', 68.2, 158, 4.2, 101, 5.6, 'Asupan natrium tinggi dari makanan olahan dan gorengan.'],
            [$pasien[2], 'N18.3', 'risiko_tinggi', 59.0, 170, 3.7, 112, 5.9, 'Nafsu makan menurun, perlu pembatasan natrium dan kalium.'],
            [$pasien[3], 'E66.9', 'risiko_sedang', 86.0, 160, 4.4, 95, 5.4, 'Pola makan tinggi energi dan aktivitas fisik rendah.'],
            [$pasien[4], 'E43', 'risiko_tinggi', 44.8, 166, 2.9, 87, 5.1, 'Penurunan berat badan 8 kg dalam 3 bulan.'],
            [$pasien[5], 'K76.0', 'risiko_rendah', 61.5, 162, 4.5, 92, 5.3, 'Konseling pola makan rendah lemak jenuh.'],
        ];

        foreach ($kasus as $i => [$pasienId, $kodeDx, $risiko, $bb, $tb, $albumin, $gdp, $hba1c, $asupan]) {
            $tanggal = $now->copy()->subDays(5 - $i);
            $kunjunganId = DB::table('kunjungans')->insertGetId([
                'pasien_id' => $pasienId,
                'nomor_kunjungan' => 'KGZ-'.$tanggal->format('Ymd').'-'.str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'tipe_kunjungan' => $i % 2 === 0 ? 'rujukan_internal' : 'mandiri',
                'asal_rujukan' => $i % 2 === 0 ? 'Poliklinik Penyakit Dalam' : null,
                'status' => $i < 4 ? 'dalam_pelayanan' : 'selesai',
                'tanggal_kunjungan' => $tanggal->toDateString(),
                'waktu_registrasi' => $tanggal->copy()->setTime(8 + $i, 15),
                'waktu_selesai' => $i >= 4 ? $tanggal->copy()->setTime(11, 30) : null,
                'perawat_id' => $pengguna['perawat'],
                'dietisien_id' => $pengguna['dietisien'],
                'spgk_id' => $pengguna['spgk'],
                'diagnosis_medis_utama_id' => $diagnosisMedis[$kodeDx],
                'diagnosis_medis_penyerta' => json_encode($kodeDx === 'E11.9' ? ['I10'] : []),
                'dokumen_terkunci' => $i >= 4,
                'dikunci_oleh' => $i >= 4 ? $pengguna['spgk'] : null,
                'dikunci_pada' => $i >= 4 ? $tanggal->copy()->setTime(11, 20) : null,
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            $skor = $risiko === 'risiko_tinggi' ? 5 : ($risiko === 'risiko_sedang' ? 2 : 1);
            DB::table('skrining_gizis')->insert([
                'kunjungan_id' => $kunjunganId,
                'metode_skrining' => 'MST',
                'skor_penurunan_bb' => $risiko === 'risiko_tinggi' ? 2 : 1,
                'skor_penurunan_asupan' => $risiko === 'risiko_rendah' ? 0 : 1,
                'skor_keparahan_penyakit' => $risiko === 'risiko_tinggi' ? 2 : 0,
                'skor_usia' => $risiko === 'risiko_tinggi' ? 0 : 0,
                'total_skor' => $skor,
                'kategori_risiko' => $risiko,
                'rekomendasi_tindak_lanjut' => $risiko === 'risiko_tinggi' ? 'Asesmen gizi komprehensif pada hari yang sama.' : 'Konseling dan asesmen sesuai antrean.',
                'dilakukan_oleh' => $pengguna['perawat'],
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            $imt = round($bb / (($tb / 100) ** 2), 2);
            DB::table('data_antropometris')->insert([
                'kunjungan_id' => $kunjunganId,
                'tanggal_pengukuran' => $tanggal->toDateString(),
                'berat_badan_kg' => encrypt((string) $bb),
                'tinggi_badan_cm' => encrypt((string) $tb),
                'imt' => encrypt((string) $imt),
                'status_gizi_imt' => $imt < 18.5 ? 'kurus' : ($imt <= 25 ? 'normal' : ($imt <= 27 ? 'gemuk' : 'obesitas_1')),
                'lingkar_lengan_atas_cm' => encrypt((string) round($bb / 2.8, 1)),
                'lingkar_perut_cm' => encrypt((string) round($bb + 20, 1)),
                'berat_badan_ideal_kg' => encrypt((string) round(($tb - 100) * .9, 1)),
                'dicatat_oleh' => $pengguna['nutrisionis'],
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            DB::table('pemeriksaan_fisik_gizis')->insert([
                'kunjungan_id' => $kunjunganId,
                'tekanan_darah_sistolik' => $kodeDx === 'I10' ? 152 : 128,
                'tekanan_darah_diastolik' => $kodeDx === 'I10' ? 92 : 78,
                'nadi_per_menit' => 82 + $i,
                'respirasi_per_menit' => 18,
                'suhu_celsius' => 36.6,
                'saturasi_oksigen_persen' => 98,
                'edema' => $kodeDx === 'N18.3',
                'lokasi_edema' => $kodeDx === 'N18.3' ? 'pretibial ringan' : null,
                'tanda_defisiensi' => json_encode($kodeDx === 'E43' ? ['wasting otot temporal', 'kulit kering'] : []),
                'gangguan_gastrointestinal' => json_encode($kodeDx === 'E43' ? ['mual', 'cepat kenyang'] : []),
                'kondisi_mulut' => 'Mukosa mulut lembab',
                'catatan_klinis' => 'Pemeriksaan fisik gizi dicatat saat asesmen awal.',
                'dicatat_oleh' => $pengguna['perawat'],
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            DB::table('data_biokimias')->insert([
                'kunjungan_id' => $kunjunganId,
                'tanggal_pemeriksaan' => $tanggal->toDateString(),
                'sumber_data' => 'lab_internal',
                'gula_darah_puasa' => $gdp,
                'hba1c_persen' => $hba1c,
                'kolesterol_total' => 185 + ($i * 8),
                'albumin' => $albumin,
                'kreatinin' => $kodeDx === 'N18.3' ? 2.1 : 0.9,
                'hemoglobin' => $kodeDx === 'E43' ? 10.2 : 13.1,
                'catatan_tambahan' => 'Hasil laboratorium rujukan internal.',
                'dicatat_oleh' => $pengguna['dietisien'],
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            DB::table('riwayat_asupan_gizis')->insert([
                'kunjungan_id' => $kunjunganId,
                'metode' => 'food_recall_24h',
                'tanggal_recall' => $tanggal->toDateString(),
                'detail_asupan' => json_encode([['waktu' => 'sehari', 'catatan' => $asupan]]),
                'total_energi_kkal' => 1450 + ($i * 120),
                'total_protein_gram' => 45 + ($i * 4),
                'total_lemak_gram' => 48 + ($i * 3),
                'total_karbohidrat_gram' => 210 + ($i * 10),
                'total_serat_gram' => 12 + $i,
                'total_natrium_mg' => 1800 + ($i * 150),
                'persen_pemenuhan_energi' => 72 + ($i * 3),
                'persen_pemenuhan_protein' => 68 + ($i * 4),
                'kesimpulan_asupan' => 'Asupan belum sepenuhnya sesuai kebutuhan dan target terapi.',
                'dicatat_oleh' => $pengguna['nutrisionis'],
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            $term = $kodeDx === 'E66.9' ? $terminologi['NC-3.3'] : ($kodeDx === 'E11.9' ? $terminologi['NI-5.8.4'] : $terminologi['NI-2.1']);
            $problem = DB::table('terminologi_diagnosis_gizis')->where('id', $term)->value('nama_masalah');
            DB::table('diagnosa_gizis')->insert([
                'kunjungan_id' => $kunjunganId,
                'terminologi_id' => $term,
                'domain' => $kodeDx === 'E66.9' ? 'klinis' : 'asupan',
                'problem_masalah' => $problem,
                'etiologi_penyebab' => 'pola makan belum sesuai kondisi klinis dan kebutuhan terapi',
                'signs_symptoms' => 'hasil skrining '.$risiko.', recall asupan tidak sesuai target, dan parameter klinis terkait',
                'narasi_pes' => encrypt($problem.' berkaitan dengan pola makan belum sesuai kondisi klinis ditandai dengan hasil skrining '.$risiko.' dan parameter klinis terkait.'),
                'urutan_prioritas' => 1,
                'status' => 'aktif',
                'divalidasi_oleh' => $i >= 4 ? $pengguna['spgk'] : null,
                'divalidasi_pada' => $i >= 4 ? $tanggal->copy()->setTime(10, 40) : null,
                'dicatat_oleh' => $pengguna['dietisien'],
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            $energiBasal = 1350 + ($i * 65);
            $totalEnergi = round($energiBasal * 1.3 * 1.1, 2);
            $preskripsiId = DB::table('preskripsi_diets')->insertGetId([
                'kunjungan_id' => $kunjunganId,
                'formula_basal' => $kodeDx === 'E11.9' ? 'konsensus_dm' : 'harris_benedict',
                'berat_badan_acuan' => 'aktual',
                'kebutuhan_energi_basal_kkal' => $energiBasal,
                'faktor_aktivitas' => 1.3,
                'faktor_stres' => 1.1,
                'faktor_koreksi_usia' => 1,
                'total_kebutuhan_energi_kkal' => $totalEnergi,
                'persen_karbohidrat' => 50,
                'gram_karbohidrat' => round($totalEnergi * .5 / 4, 2),
                'persen_protein' => 20,
                'gram_protein' => round($totalEnergi * .2 / 4, 2),
                'persen_lemak' => 30,
                'gram_lemak' => round($totalEnergi * .3 / 9, 2),
                'gram_serat' => 25,
                'batas_natrium_mg' => in_array($kodeDx, ['I10', 'N18.3']) ? 1500 : 2000,
                'batas_kalium_mg' => $kodeDx === 'N18.3' ? 2000 : null,
                'bentuk_makanan' => $kodeDx === 'E43' ? 'lunak' : 'biasa',
                'frekuensi_makan_utama' => 3,
                'frekuensi_selingan' => 2,
                'pantangan_spesifik' => json_encode($kodeDx === 'N18.3' ? ['makanan tinggi kalium', 'makanan tinggi natrium'] : ['minuman tinggi gula']),
                'catatan_klinis' => encrypt('Preskripsi disesuaikan dengan diagnosis medis utama dan hasil asesmen gizi.'),
                'tujuan_terapi' => 'Mencapai asupan adekuat, memperbaiki parameter klinis, dan meningkatkan kepatuhan diet.',
                'target_luaran_klinis' => json_encode(['asupan energi 90-110% kebutuhan', 'kepatuhan diet meningkat', 'parameter lab membaik']),
                'tanggal_mulai' => $tanggal->toDateString(),
                'tanggal_evaluasi' => $tanggal->copy()->addWeeks(2)->toDateString(),
                'durasi_hari' => 14,
                'status' => 'aktif',
                'dibuat_oleh' => $pengguna['dietisien'],
                'disetujui_oleh' => $pengguna['spgk'],
                'disetujui_pada' => $tanggal->copy()->setTime(10, 55),
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            foreach (['makan_pagi' => 'Nasi putih', 'makan_siang' => 'Ayam dada tanpa kulit', 'makan_malam' => 'Tempe kedelai', 'selingan_pagi' => 'Pepaya'] as $waktu => $namaBahan) {
                DB::table('detail_menu_harians')->insert([
                    'preskripsi_diet_id' => $preskripsiId,
                    'waktu_makan' => $waktu,
                    'bahan_makanan_id' => $bahan[$namaBahan],
                    'porsi_gram' => 100,
                    'energi_kkal' => 130,
                    'protein_gram' => 5,
                    'lemak_gram' => 2,
                    'karbohidrat_gram' => 22,
                    'keterangan_penukar' => 'Dapat ditukar sesuai daftar bahan sejenis dan pantangan pasien.',
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);
            }

            $dokumenId = DB::table('dokumen_edukasiis')->insertGetId([
                'pasien_id' => $pasienId,
                'kunjungan_id' => $kunjunganId,
                'judul_dokumen' => 'Rencana Makan dan Edukasi Gizi',
                'tipe' => 'rencana_makan',
                'konten_json' => json_encode(['energi' => $totalEnergi, 'pesan' => 'Ikuti pembagian makan utama dan selingan.']),
                'token_akses' => Str::random(40),
                'token_expired_at' => $tanggal->copy()->addDays(7),
                'dibuat_oleh' => $pengguna['dietisien'],
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            DB::table('catatan_konselings')->insert([
                'kunjungan_id' => $kunjunganId,
                'tanggal_konseling' => $tanggal->toDateString(),
                'durasi_menit' => 30,
                'metode' => 'tatap_muka',
                'topik_konseling' => json_encode(['pembagian porsi', 'pemilihan bahan makanan', 'target kontrol']),
                'isi_konseling' => encrypt('Pasien diedukasi tentang pembagian porsi, jadwal makan, pantangan, dan pemantauan mandiri.'),
                'hambatan_pasien' => 'Jadwal kerja dan kebiasaan makan keluarga.',
                'kesepakatan_tindak_lanjut' => 'Kontrol ulang dua minggu dengan catatan makan harian.',
                'tingkat_pemahaman_pasien' => $i % 3 === 0 ? 'cukup' : 'baik',
                'dokumen_edukasi_id' => $dokumenId,
                'dilakukan_oleh' => $pengguna['dietisien'],
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            DB::table('monitorings')->insert([
                'kunjungan_id' => $kunjunganId,
                'kunjungan_sebelumnya_id' => null,
                'parameter_dipantau' => json_encode(['berat badan', 'asupan energi', 'kepatuhan diet', 'parameter lab']),
                'evaluasi_anthropometri' => 'Berat badan dan IMT menjadi parameter utama evaluasi.',
                'evaluasi_biokimia' => 'Parameter lab dipantau sesuai diagnosis medis utama.',
                'evaluasi_asupan' => 'Asupan belum optimal, perlu catatan makan harian.',
                'evaluasi_kepatuhan_diet' => $i % 2 === 0 ? 'cukup_patuh' : 'patuh',
                'persen_sisa_makanan' => 10 + ($i * 3),
                'kesimpulan' => 'Intervensi dilanjutkan dengan modifikasi kecil sesuai toleransi pasien.',
                'rekomendasi_lanjutan' => 'Kontrol ulang dan evaluasi recall 24 jam.',
                'perlu_rujukan' => false,
                'rencana_kunjungan_berikutnya' => $tanggal->copy()->addWeeks(2)->toDateString(),
                'dilakukan_oleh' => $pengguna['dietisien'],
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);
        }
    }

    private function seedLaporan(Carbon $now, array $pengguna): void
    {
        DB::table('laporan_statistiks')->insert([
            'tipe_laporan' => 'kinerja_harian',
            'periode_dari' => $now->copy()->startOfMonth()->toDateString(),
            'periode_sampai' => $now->toDateString(),
            'parameter' => json_encode(['unit' => 'Poliklinik Gizi']),
            'data_laporan' => json_encode([
                'kunjungan' => DB::table('kunjungans')->count(),
                'risiko_tinggi' => DB::table('skrining_gizis')->where('kategori_risiko', 'risiko_tinggi')->count(),
                'preskripsi_aktif' => DB::table('preskripsi_diets')->where('status', 'aktif')->count(),
            ]),
            'dibuat_oleh' => $pengguna['spgk'],
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
