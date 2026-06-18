<?php

$modelsDir = __DIR__ . '/app/Models/';

$models = [
    'Pengguna' => [
        'table' => 'penggunas',
        'fillable' => "['nama_lengkap', 'email', 'password', 'peran', 'nomor_sip', 'nomor_str', 'unit_kerja', 'status_aktif', 'last_login_at', 'last_login_ip']",
        'traits' => "use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable, \Illuminate\Auth\Authenticatable;",
        'interfaces' => "implements \OwenIt\Auditing\Contracts\Auditable, \Illuminate\Contracts\Auth\Authenticatable",
        'casts' => "['password' => 'hashed']",
        'hidden' => "['password']"
    ],
    'SesiAktif' => [
        'table' => 'sesi_aktifs',
        'fillable' => "['pengguna_id', 'token', 'ip_address', 'user_agent', 'last_activity', 'expired_at']",
        'traits' => "use HasFactory;",
        'interfaces' => "",
        'casts' => "['last_activity' => 'datetime', 'expired_at' => 'datetime']"
    ],
    'Pasien' => [
        'table' => 'pasiens',
        'fillable' => "['nomor_rekam_medis', 'nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'golongan_darah', 'nomor_telepon', 'alamat', 'nomor_bpjs', 'status_aktif', 'satusehat_patient_id']",
        'traits' => "use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;",
        'interfaces' => "implements \OwenIt\Auditing\Contracts\Auditable",
        'casts' => "['nomor_rekam_medis' => 'encrypted', 'nik' => 'encrypted', 'nama_lengkap' => 'encrypted', 'nomor_telepon' => 'encrypted', 'alamat' => 'encrypted', 'nomor_bpjs' => 'encrypted', 'tanggal_lahir' => 'date']"
    ],
    'RiwayatAlergiPasien' => [
        'table' => 'riwayat_alergi_pasiens',
        'fillable' => "['pasien_id', 'jenis_alergi', 'nama_alergen', 'reaksi', 'tingkat_keparahan', 'dicatat_oleh']",
        'traits' => "use HasFactory, \OwenIt\Auditing\Auditable;",
        'interfaces' => "implements \OwenIt\Auditing\Contracts\Auditable",
        'casts' => "['nama_alergen' => 'encrypted']"
    ],
    'DiagnosisMedisUtama' => [
        'table' => 'diagnosis_medis_utamas',
        'fillable' => "['kode_icd10', 'nama_diagnosis', 'kategori']",
        'traits' => "use HasFactory;",
        'interfaces' => ""
    ],
    'BahanMakanan' => [
        'table' => 'bahan_makanans',
        'fillable' => "['nama_bahan', 'nama_daerah', 'kategori', 'porsi_standar_gram', 'energi_kkal', 'protein_gram', 'lemak_gram', 'karbohidrat_gram', 'serat_gram', 'natrium_mg', 'kalium_mg', 'fosfor_mg', 'kalsium_mg', 'sumber_data']",
        'traits' => "use HasFactory;",
        'interfaces' => ""
    ],
    'TerminologiDiagnosisGizi' => [
        'table' => 'terminologi_diagnosis_gizis',
        'fillable' => "['kode_diagnosis', 'domain', 'nama_masalah', 'deskripsi', 'is_aktif']",
        'traits' => "use HasFactory;",
        'interfaces' => ""
    ],
    'Kunjungan' => [
        'table' => 'kunjungans',
        'fillable' => "['pasien_id', 'nomor_kunjungan', 'tipe_kunjungan', 'asal_rujukan', 'status', 'tanggal_kunjungan', 'waktu_registrasi', 'waktu_selesai', 'perawat_id', 'dietisien_id', 'spgk_id', 'diagnosis_medis_utama_id', 'diagnosis_medis_penyerta', 'satusehat_encounter_id', 'dokumen_terkunci', 'dikunci_oleh', 'dikunci_pada']",
        'traits' => "use HasFactory, \OwenIt\Auditing\Auditable;",
        'interfaces' => "implements \OwenIt\Auditing\Contracts\Auditable",
        'casts' => "['diagnosis_medis_penyerta' => 'array', 'tanggal_kunjungan' => 'date', 'waktu_registrasi' => 'datetime', 'waktu_selesai' => 'datetime', 'dikunci_pada' => 'datetime']"
    ],
    'SkriningGizi' => [
        'table' => 'skrining_gizis',
        'fillable' => "['kunjungan_id', 'metode_skrining', 'skor_penurunan_bb', 'skor_penurunan_asupan', 'skor_keparahan_penyakit', 'skor_usia', 'total_skor', 'kategori_risiko', 'rekomendasi_tindak_lanjut', 'dilakukan_oleh']",
        'traits' => "use HasFactory, \OwenIt\Auditing\Auditable;",
        'interfaces' => "implements \OwenIt\Auditing\Contracts\Auditable"
    ],
    'DataAntropometri' => [
        'table' => 'data_antropometris',
        'fillable' => "['kunjungan_id', 'tanggal_pengukuran', 'berat_badan_kg', 'tinggi_badan_cm', 'imt', 'status_gizi_imt', 'lingkar_lengan_atas_cm', 'lingkar_perut_cm', 'panjang_lutut_cm', 'tebal_lipatan_kulit_mm', 'berat_badan_ideal_kg', 'persentase_bbl', 'is_pediatri', 'usia_tahun', 'usia_bulan', 'zscore_bb_u', 'zscore_tb_u', 'zscore_imt_u', 'status_gizi_anak', 'dicatat_oleh', 'satusehat_obs_id']",
        'traits' => "use HasFactory, \OwenIt\Auditing\Auditable;",
        'interfaces' => "implements \OwenIt\Auditing\Contracts\Auditable",
        'casts' => "['tanggal_pengukuran' => 'date', 'berat_badan_kg' => 'encrypted', 'tinggi_badan_cm' => 'encrypted', 'imt' => 'encrypted', 'lingkar_lengan_atas_cm' => 'encrypted', 'lingkar_perut_cm' => 'encrypted', 'panjang_lutut_cm' => 'encrypted', 'tebal_lipatan_kulit_mm' => 'encrypted', 'berat_badan_ideal_kg' => 'encrypted', 'persentase_bbl' => 'encrypted']"
    ],
    'DataBiokimia' => [
        'table' => 'data_biokimias',
        'fillable' => "['kunjungan_id', 'tanggal_pemeriksaan', 'sumber_data', 'gula_darah_sewaktu', 'gula_darah_puasa', 'gula_darah_2jpp', 'hba1c_persen', 'kolesterol_total', 'hdl', 'ldl', 'trigliserida', 'albumin', 'ureum', 'kreatinin', 'laju_filtrasi_gfr', 'hemoglobin', 'hematokrit', 'neutrofil', 'natrium', 'kalium', 'kalsium', 'fosfor', 'catatan_tambahan', 'dicatat_oleh']",
        'traits' => "use HasFactory, \OwenIt\Auditing\Auditable;",
        'interfaces' => "implements \OwenIt\Auditing\Contracts\Auditable",
        'casts' => "['tanggal_pemeriksaan' => 'date']"
    ],
    'PemeriksaanFisikGizi' => [
        'table' => 'pemeriksaan_fisik_gizis',
        'fillable' => "['kunjungan_id', 'tekanan_darah_sistolik', 'tekanan_darah_diastolik', 'nadi_per_menit', 'respirasi_per_menit', 'suhu_celsius', 'saturasi_oksigen_persen', 'edema', 'lokasi_edema', 'tanda_defisiensi', 'gangguan_gastrointestinal', 'kondisi_mulut', 'kekuatan_genggam_kg', 'catatan_klinis', 'dicatat_oleh']",
        'traits' => "use HasFactory, \OwenIt\Auditing\Auditable;",
        'interfaces' => "implements \OwenIt\Auditing\Contracts\Auditable",
        'casts' => "['tanda_defisiensi' => 'array', 'gangguan_gastrointestinal' => 'array']"
    ],
    'RiwayatAsupanGizi' => [
        'table' => 'riwayat_asupan_gizis',
        'fillable' => "['kunjungan_id', 'metode', 'tanggal_recall', 'detail_asupan', 'total_energi_kkal', 'total_protein_gram', 'total_lemak_gram', 'total_karbohidrat_gram', 'total_serat_gram', 'total_natrium_mg', 'persen_pemenuhan_energi', 'persen_pemenuhan_protein', 'persen_pemenuhan_lemak', 'persen_pemenuhan_karbohidrat', 'kesimpulan_asupan', 'dicatat_oleh']",
        'traits' => "use HasFactory, \OwenIt\Auditing\Auditable;",
        'interfaces' => "implements \OwenIt\Auditing\Contracts\Auditable",
        'casts' => "['tanggal_recall' => 'date', 'detail_asupan' => 'array']"
    ],
    'DiagnosaGizi' => [
        'table' => 'diagnosa_gizis',
        'fillable' => "['kunjungan_id', 'terminologi_id', 'domain', 'problem_masalah', 'etiologi_penyebab', 'signs_symptoms', 'narasi_pes', 'urutan_prioritas', 'status', 'divalidasi_oleh', 'divalidasi_pada', 'satusehat_condition_id', 'dicatat_oleh']",
        'traits' => "use HasFactory, \OwenIt\Auditing\Auditable;",
        'interfaces' => "implements \OwenIt\Auditing\Contracts\Auditable",
        'casts' => "['narasi_pes' => 'encrypted', 'divalidasi_pada' => 'datetime']"
    ],
    'PreskripsiDiet' => [
        'table' => 'preskripsi_diets',
        'fillable' => "['kunjungan_id', 'formula_basal', 'berat_badan_acuan', 'kebutuhan_energi_basal_kkal', 'faktor_aktivitas', 'faktor_stres', 'faktor_koreksi_usia', 'total_kebutuhan_energi_kkal', 'persen_karbohidrat', 'gram_karbohidrat', 'persen_protein', 'gram_protein', 'persen_lemak', 'gram_lemak', 'gram_serat', 'batas_natrium_mg', 'batas_kalium_mg', 'batas_fosfor_mg', 'batas_cairan_ml', 'bentuk_makanan', 'frekuensi_makan_utama', 'frekuensi_selingan', 'pantangan_spesifik', 'catatan_klinis', 'tujuan_terapi', 'target_luaran_klinis', 'tanggal_mulai', 'tanggal_evaluasi', 'durasi_hari', 'status', 'dibuat_oleh', 'disetujui_oleh', 'disetujui_pada', 'satusehat_careplan_id']",
        'traits' => "use HasFactory, \OwenIt\Auditing\Auditable;",
        'interfaces' => "implements \OwenIt\Auditing\Contracts\Auditable",
        'casts' => "['catatan_klinis' => 'encrypted', 'pantangan_spesifik' => 'array', 'target_luaran_klinis' => 'array', 'tanggal_mulai' => 'date', 'tanggal_evaluasi' => 'date', 'disetujui_pada' => 'datetime']"
    ],
    'DetailMenuHarian' => [
        'table' => 'detail_menu_harians',
        'fillable' => "['preskripsi_diet_id', 'waktu_makan', 'bahan_makanan_id', 'porsi_gram', 'energi_kkal', 'protein_gram', 'lemak_gram', 'karbohidrat_gram', 'keterangan_penukar']",
        'traits' => "use HasFactory;",
        'interfaces' => ""
    ],
    'DokumenEdukasi' => [
        'table' => 'dokumen_edukasiis',
        'fillable' => "['pasien_id', 'kunjungan_id', 'judul_dokumen', 'tipe', 'konten_json', 'path_pdf', 'token_akses', 'token_expired_at', 'dibuat_oleh']",
        'traits' => "use HasFactory, \OwenIt\Auditing\Auditable;",
        'interfaces' => "implements \OwenIt\Auditing\Contracts\Auditable",
        'casts' => "['konten_json' => 'array', 'token_expired_at' => 'datetime']"
    ],
    'CatatanKonseling' => [
        'table' => 'catatan_konselings',
        'fillable' => "['kunjungan_id', 'tanggal_konseling', 'durasi_menit', 'metode', 'topik_konseling', 'isi_konseling', 'hambatan_pasien', 'kesepakatan_tindak_lanjut', 'tingkat_pemahaman_pasien', 'dokumen_edukasi_id', 'dilakukan_oleh']",
        'traits' => "use HasFactory, \OwenIt\Auditing\Auditable;",
        'interfaces' => "implements \OwenIt\Auditing\Contracts\Auditable",
        'casts' => "['isi_konseling' => 'encrypted', 'topik_konseling' => 'array', 'tanggal_konseling' => 'date']"
    ],
    'Monitoring' => [
        'table' => 'monitorings',
        'fillable' => "['kunjungan_id', 'kunjungan_sebelumnya_id', 'parameter_dipantau', 'evaluasi_anthropometri', 'evaluasi_biokimia', 'evaluasi_asupan', 'evaluasi_kepatuhan_diet', 'persen_sisa_makanan', 'kesimpulan', 'rekomendasi_lanjutan', 'perlu_rujukan', 'tujuan_rujukan', 'rencana_kunjungan_berikutnya', 'dilakukan_oleh']",
        'traits' => "use HasFactory, \OwenIt\Auditing\Auditable;",
        'interfaces' => "implements \OwenIt\Auditing\Contracts\Auditable",
        'casts' => "['parameter_dipantau' => 'array', 'rencana_kunjungan_berikutnya' => 'date']"
    ],
    'LaporanStatistik' => [
        'table' => 'laporan_statistiks',
        'fillable' => "['tipe_laporan', 'periode_dari', 'periode_sampai', 'parameter', 'data_laporan', 'dibuat_oleh']",
        'traits' => "use HasFactory;",
        'interfaces' => "",
        'casts' => "['parameter' => 'array', 'data_laporan' => 'array', 'periode_dari' => 'date', 'periode_sampai' => 'date']"
    ],
    'LoginHistory' => [
        'table' => 'login_histories',
        'fillable' => "['pengguna_id', 'tipe_event', 'ip_address', 'user_agent']",
        'traits' => "use HasFactory;",
        'interfaces' => ""
    ]
];

foreach ($models as $name => $meta) {
    $authMethod = ($name === 'Pengguna') ? "
    public function getAuthIdentifierName() { return 'id'; }
    public function getAuthIdentifier() { return \$this->id; }
    public function getAuthPasswordName() { return 'password'; }
    public function getAuthPassword() { return \$this->password; }
    public function getRememberToken() { return ''; }
    public function setRememberToken(\$value) { }
    public function getRememberTokenName() { return ''; }" : "";

    $castsStr = isset($meta['casts']) ? "protected \$casts = {$meta['casts']};" : "";
    $hiddenStr = isset($meta['hidden']) ? "protected \$hidden = {$meta['hidden']};" : "";

    $content = "<?php\n\nnamespace App\Models;\n\nuse Illuminate\Database\Eloquent\Model;\nuse Illuminate\Database\Eloquent\SoftDeletes;\nuse Illuminate\Database\Eloquent\Factories\HasFactory;\n\nclass {$name} extends Model {$meta['interfaces']}\n{\n    {$meta['traits']}\n\n    protected \$table = '{$meta['table']}';\n    protected \$fillable = {$meta['fillable']};\n    {$castsStr}\n    {$hiddenStr}\n{$authMethod}\n}\n";
    
    file_put_contents($modelsDir . $name . '.php', $content);
}

echo "Models generated.\n";
