# rule-NCPMS.md
# NCPMS — Nutrition Care and Patient Management System
# Aturan Arsitektur Sistem, Database, dan Alur Pengembangan

---

## 1. IDENTITAS SISTEM

- **Nama Sistem**: NCPMS — Nutrition Care and Patient Management System
- **Nama Lengkap**: Sistem Manajemen Gizi Klinis dan Perencanaan Kalori Pasien Rawat Jalan
- **Framework**: Laravel 12
- **Tipe Aplikasi**: Web-based Enterprise Clinical Information System
- **Target Pengguna**: Tenaga kesehatan klinis (tidak ada portal publik/pasien)
- **Regulasi Acuan**:
  - Permenkes RI No. 24 Tahun 2022 tentang Rekam Medis Elektronik
  - Pedoman PGRS (Pelayanan Gizi Rumah Sakit) Kemenkes 2013
  - Pedoman Proses Asuhan Gizi Terstandar (PAGT)
  - Standar Profesi Dietisien Kemenkes

---

## 2. STACK TEKNOLOGI

### Backend
- **Framework**: Laravel 12 (PHP 8.3+)
- **Auth**: Laravel Sanctum (token-based, multi-guard)
- **Audit Trail**: OwenIt Laravel Auditing package
- **Enkripsi Model**: `Model::encryptUsing()` + `Encrypted` cast bawaan Laravel
- **Enkripsi Kolom Sensitif**: Custom cast via Laravel Attribute Casting
- **Job & Schedule**: Laravel Scheduler (sesi expiry, laporan otomatis)
- **Queue**: Laravel Queue (export PDF, notifikasi)

### Frontend
- **CSS Framework**: Bootstrap 5 via CDN
- **Icon Library**: Font Awesome 6 via CDN
- **Alert & Konfirmasi**: SweetAlert2 via CDN (WAJIB untuk semua alert, confirm, toast)
- **Chart & Visualisasi**: Chart.js via CDN
- **Tidak ada NPM/Vite** — semua dependensi frontend via CDN

### Database
- **DBMS**: MySQL 8.0+ / MariaDB 10.6+
- **ORM**: Eloquent Laravel
- **Migrasi**: Laravel Migration (semua perubahan skema via migration)
- **Soft Delete**: Digunakan pada tabel rekam medis dan pasien

### Bahasa
- **Validasi & Pesan Error**: Bahasa Indonesia
- **Komentar Kode**: Bahasa Indonesia
- **Antarmuka Sistem**: Bahasa Indonesia

---

## 3. ARSITEKTUR APLIKASI

### Struktur Direktori (sesuai konvensi Laravel 12)
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── Dashboard/
│   │   ├── Pasien/
│   │   ├── Asesmen/
│   │   ├── Diagnosis/
│   │   ├── Intervensi/
│   │   ├── Monitoring/
│   │   ├── Laporan/
│   │   └── Admin/
│   ├── Middleware/
│   │   ├── RoleMiddleware.php
│   │   ├── SessionTimeoutMiddleware.php
│   │   └── AuditMiddleware.php
│   └── Requests/ (FormRequest per modul)
├── Models/
├── Services/
│   ├── KalkulasiKaloriService.php
│   ├── AntropometriService.php
│   ├── SkrinGiziService.php
│   ├── SatuSehatService.php
│   └── AuditService.php
├── Enums/
│   ├── RolePengguna.php
│   ├── StatusRisikoGizi.php
│   ├── DomainDiagnosis.php
│   └── FormulaKalori.php
└── Policies/ (per resource)
```

### Pola Arsitektur
- **Controller → Service → Model** (service layer wajib untuk logika bisnis)
- **FormRequest** untuk semua validasi input
- **Resource Controller** (RESTful) untuk semua CRUD
- **Policy** untuk otorisasi akses per resource
- **Observer** untuk trigger audit log otomatis

---

## 4. SISTEM AUTENTIKASI DAN MANAJEMEN PERAN

### Guard & Provider
```php
// config/auth.php
'guards' => [
    'web' => ['driver' => 'session', 'provider' => 'pengguna'],
],
'providers' => [
    'pengguna' => ['driver' => 'eloquent', 'model' => App\Models\Pengguna::class],
],
```

### Definisi Peran (RBAC)
Sistem menggunakan **5 peran** dengan hierarki wewenang klinis:

| Kode Peran | Nama Peran | Tingkat Akses |
|---|---|---|
| `spgk` | Dokter Spesialis Gizi Klinik | Paripurna (semua modul) |
| `dietisien` | Dietisien Teregistrasi | Komprehensif (kecuali farmakoterapi) |
| `nutrisionis` | Tenaga Nutrisionis | Terbatas (input data, bukan diagnosis) |
| `perawat` | Perawat Poliklinik | Minimal (skrining & vital sign) |
| `admin_ti` | Administrator TI | Teknis (tanpa akses data klinis pasien) |

### Aturan Akses Per Modul

#### Spesialis Gizi Klinik (SpGK)
- Akses penuh semua modul klinis
- Dapat override/anulir preskripsi dietisien
- Otorisasi final dokumen asuhan (digital sign + lock)
- Akses modul nutrisi parenteral (intravena)
- Akses modul rekomendasi perioperatif

#### Dietisien
- Pelaksanaan PAGT penuh (asesmen → diagnosis → intervensi → monitoring)
- Kalkulasi kalori dan perumusan preskripsi diet
- Input diagnosis gizi dengan format PES
- Dokumentasi konseling
- **DILARANG**: modul resep farmakologi, diagnosis patologi medis utama

#### Nutrisionis
- Input data antropometri pasien
- Pencatatan riwayat asupan (food recall, food frequency)
- Melihat rancangan preskripsi (read-only)
- Edukasi gizi mendasar
- **DILARANG**: manipulasi variabel kritis diagnosis klinis, kalkulasi kalori komorbid berat

#### Perawat Poliklinik
- Registrasi kedatangan pasien
- Pencatatan vital sign (TD, nadi, RR, suhu, SpO2)
- Pelaksanaan skrining risiko malnutrisi awal
- **DILARANG**: seluruh modul manajemen preskripsi diet, kalkulasi kalori, diagnosis gizi

#### Administrator TI
- Manajemen akun dan alokasi peran
- Pemantauan log server dan stabilitas sistem
- Backup database
- **DILARANG KERAS**: akses data diagnostik pasien, riwayat alergi, catatan kalori personal (blinded interface)

### Middleware Sesi
```php
// Timeout sesi inaktif: 15 menit
// Konfigurasi di SessionTimeoutMiddleware
// Token Sanctum hangus otomatis → redirect ke halaman login
SESSION_LIFETIME=15 // dalam menit
```

---

## 5. SKEMA DATABASE

### Konvensi Penamaan
- Tabel: `snake_case`, bentuk jamak, bahasa Indonesia (contoh: `pasiens`, `data_antropometris`)
- Kolom: `snake_case`, bahasa Indonesia
- Primary Key: `id` (auto-increment bigint)
- Foreign Key: `{nama_tabel_singular}_id`
- Timestamps: `created_at`, `updated_at` (semua tabel)
- Soft Delete: `deleted_at` (tabel rekam medis dan pasien)

### Kolom Terenkripsi (Wajib Encrypt)
Seluruh kolom berikut WAJIB menggunakan `Encrypted` cast:
- `pasiens.nomor_rekam_medis`
- `pasiens.nik`
- `pasiens.nama_lengkap`
- `pasiens.nomor_telepon`
- `pasiens.alamat`
- `data_antropometris.*` (semua nilai pengukuran)
- `diagnosa_gizis.narasi_pes`
- `preskripsi_diets.catatan_klinis`
- `catatan_konseling.isi_konseling`

### Tabel-Tabel Utama

#### A. Tabel Manajemen Pengguna & Akses

**Table: `penggunas`**
```sql
id                  BIGINT PK AUTO_INCREMENT
nama_lengkap        VARCHAR(150) NOT NULL
email               VARCHAR(100) UNIQUE NOT NULL
password            VARCHAR(255) NOT NULL       -- bcrypt hash
peran               ENUM('spgk','dietisien','nutrisionis','perawat','admin_ti') NOT NULL
nomor_sip           VARCHAR(50) NULL            -- Surat Izin Praktik
nomor_str           VARCHAR(50) NULL            -- Surat Tanda Registrasi
unit_kerja          VARCHAR(100) NULL
status_aktif        BOOLEAN DEFAULT TRUE
last_login_at       TIMESTAMP NULL
last_login_ip       VARCHAR(45) NULL
created_at          TIMESTAMP
updated_at          TIMESTAMP
deleted_at          TIMESTAMP NULL
```

**Table: `sesi_aktifs`**
```sql
id                  BIGINT PK
pengguna_id         BIGINT FK → penggunas.id
token               VARCHAR(500)
ip_address          VARCHAR(45)
user_agent          TEXT
last_activity       TIMESTAMP
expired_at          TIMESTAMP
```

#### B. Tabel Master Data Klinis

**Table: `pasiens`** *(MPI — Master Patient Index)*
```sql
id                          BIGINT PK AUTO_INCREMENT
nomor_rekam_medis           TEXT                        -- ENCRYPTED
nik                         TEXT                        -- ENCRYPTED
nama_lengkap                TEXT NOT NULL               -- ENCRYPTED
tempat_lahir                VARCHAR(100) NULL
tanggal_lahir               DATE NOT NULL
jenis_kelamin               ENUM('L','P') NOT NULL
golongan_darah              ENUM('A','B','AB','O','tidak_diketahui') NULL
nomor_telepon               TEXT NULL                   -- ENCRYPTED
alamat                      TEXT NULL                   -- ENCRYPTED
nomor_bpjs                  TEXT NULL                   -- ENCRYPTED
status_aktif                BOOLEAN DEFAULT TRUE
satusehat_patient_id        VARCHAR(100) NULL           -- IHS Number SATUSEHAT
created_at                  TIMESTAMP
updated_at                  TIMESTAMP
deleted_at                  TIMESTAMP NULL
```

**Table: `riwayat_alergi_pasiens`**
```sql
id                  BIGINT PK
pasien_id           BIGINT FK → pasiens.id
jenis_alergi        ENUM('makanan','obat','lingkungan','lainnya')
nama_alergen        TEXT NOT NULL                   -- ENCRYPTED
reaksi              TEXT NULL
tingkat_keparahan   ENUM('ringan','sedang','berat')
dicatat_oleh        BIGINT FK → penggunas.id
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**Table: `diagnosis_medis_utamas`** *(ICD-10)*
```sql
id              BIGINT PK
kode_icd10      VARCHAR(10) NOT NULL
nama_diagnosis  VARCHAR(255) NOT NULL
kategori        VARCHAR(100)
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

**Table: `bahan_makanans`** *(Food Database)*
```sql
id                      BIGINT PK
nama_bahan              VARCHAR(200) NOT NULL
nama_daerah             VARCHAR(200) NULL
kategori                ENUM('karbohidrat','protein_hewani','protein_nabati','sayuran','buah','lemak','minuman','bumbu','lainnya')
porsi_standar_gram      DECIMAL(8,2) NOT NULL
energi_kkal             DECIMAL(8,2) NOT NULL
protein_gram            DECIMAL(8,2) NOT NULL
lemak_gram              DECIMAL(8,2) NOT NULL
karbohidrat_gram        DECIMAL(8,2) NOT NULL
serat_gram              DECIMAL(8,2) NULL
natrium_mg              DECIMAL(8,2) NULL
kalium_mg               DECIMAL(8,2) NULL
fosfor_mg               DECIMAL(8,2) NULL
kalsium_mg              DECIMAL(8,2) NULL
sumber_data             VARCHAR(100) NULL           -- misal: TKPI 2019
created_at              TIMESTAMP
updated_at              TIMESTAMP
```

**Table: `terminologi_diagnosis_gizis`** *(Referensi PES)*
```sql
id              BIGINT PK
kode_diagnosis  VARCHAR(20) NOT NULL               -- misal: NI-2.1
domain          ENUM('asupan','klinis','perilaku_lingkungan') NOT NULL
nama_masalah    VARCHAR(255) NOT NULL
deskripsi       TEXT NULL
is_aktif        BOOLEAN DEFAULT TRUE
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

#### C. Tabel Alur Klinis PAGT

**Table: `kunjungans`** *(FHIR: Encounter)*
```sql
id                          BIGINT PK AUTO_INCREMENT
pasien_id                   BIGINT FK → pasiens.id NOT NULL
nomor_kunjungan             VARCHAR(50) UNIQUE NOT NULL
tipe_kunjungan              ENUM('mandiri','rujukan_internal','rujukan_eksternal')
asal_rujukan                VARCHAR(200) NULL
status                      ENUM('terdaftar','dalam_pelayanan','selesai','batal') DEFAULT 'terdaftar'
tanggal_kunjungan           DATE NOT NULL
waktu_registrasi            TIMESTAMP NOT NULL
waktu_selesai               TIMESTAMP NULL
perawat_id                  BIGINT FK → penggunas.id    -- perawat penerima
dietisien_id                BIGINT FK → penggunas.id NULL
spgk_id                     BIGINT FK → penggunas.id NULL
diagnosis_medis_utama_id    BIGINT FK → diagnosis_medis_utamas.id NULL
diagnosis_medis_penyerta    JSON NULL                   -- array ICD-10
satusehat_encounter_id      VARCHAR(100) NULL
dokumen_terkunci            BOOLEAN DEFAULT FALSE
dikunci_oleh                BIGINT FK → penggunas.id NULL
dikunci_pada                TIMESTAMP NULL
created_at                  TIMESTAMP
updated_at                  TIMESTAMP
```

**Table: `skrining_gizis`** *(Nutritional Screening)*
```sql
id                          BIGINT PK AUTO_INCREMENT
kunjungan_id                BIGINT FK → kunjungans.id NOT NULL
metode_skrining             ENUM('MNA','NRS2002','MST','MUST','STAMP') NOT NULL
skor_penurunan_bb           TINYINT NOT NULL            -- 0-3
skor_penurunan_asupan       TINYINT NOT NULL            -- 0-3
skor_keparahan_penyakit     TINYINT NOT NULL            -- 0-3
skor_usia                   TINYINT DEFAULT 0
total_skor                  TINYINT NOT NULL
kategori_risiko             ENUM('risiko_rendah','risiko_sedang','risiko_tinggi') NOT NULL
rekomendasi_tindak_lanjut   TEXT NULL
dilakukan_oleh              BIGINT FK → penggunas.id
created_at                  TIMESTAMP
updated_at                  TIMESTAMP
```

**Table: `data_antropometris`** *(FHIR: Observation)*
```sql
id                      BIGINT PK AUTO_INCREMENT
kunjungan_id            BIGINT FK → kunjungans.id NOT NULL
tanggal_pengukuran      DATE NOT NULL
-- Pengukuran Dasar
berat_badan_kg          TEXT NOT NULL               -- ENCRYPTED
tinggi_badan_cm         TEXT NOT NULL               -- ENCRYPTED
imt                     TEXT NULL                   -- ENCRYPTED (auto-kalkulasi)
status_gizi_imt         ENUM('sangat_kurus','kurus','normal','gemuk','obesitas_1','obesitas_2') NULL
-- Pengukuran Tambahan
lingkar_lengan_atas_cm  TEXT NULL                   -- ENCRYPTED
lingkar_perut_cm        TEXT NULL                   -- ENCRYPTED
panjang_lutut_cm        TEXT NULL                   -- ENCRYPTED (geriatri)
tebal_lipatan_kulit_mm  TEXT NULL                   -- ENCRYPTED
-- Berat Ideal & Derivat
berat_badan_ideal_kg    TEXT NULL                   -- ENCRYPTED
persentase_bbl          TEXT NULL                   -- ENCRYPTED (% penurunan)
-- Pediatri (Z-score)
is_pediatri             BOOLEAN DEFAULT FALSE
usia_tahun              TINYINT NULL
usia_bulan              TINYINT NULL
zscore_bb_u             DECIMAL(5,2) NULL
zscore_tb_u             DECIMAL(5,2) NULL
zscore_imt_u            DECIMAL(5,2) NULL
status_gizi_anak        ENUM('gizi_buruk','gizi_kurang','gizi_baik','gizi_lebih','obesitas') NULL
dicatat_oleh            BIGINT FK → penggunas.id
satusehat_obs_id        VARCHAR(100) NULL
created_at              TIMESTAMP
updated_at              TIMESTAMP
```

**Table: `data_biokimias`** *(FHIR: Observation)*
```sql
id                      BIGINT PK AUTO_INCREMENT
kunjungan_id            BIGINT FK → kunjungans.id NOT NULL
tanggal_pemeriksaan     DATE NOT NULL
sumber_data             ENUM('lab_internal','input_manual','satusehat')
-- Metabolisme Glukosa
gula_darah_sewaktu      DECIMAL(8,2) NULL           -- mg/dL
gula_darah_puasa        DECIMAL(8,2) NULL
gula_darah_2jpp         DECIMAL(8,2) NULL
hba1c_persen            DECIMAL(5,2) NULL
-- Profil Lipid
kolesterol_total        DECIMAL(8,2) NULL
hdl                     DECIMAL(8,2) NULL
ldl                     DECIMAL(8,2) NULL
trigliserida            DECIMAL(8,2) NULL
-- Protein & Ginjal
albumin                 DECIMAL(5,2) NULL           -- g/dL
ureum                   DECIMAL(8,2) NULL
kreatinin               DECIMAL(8,2) NULL
laju_filtrasi_gfr       DECIMAL(8,2) NULL
-- Darah Lengkap
hemoglobin              DECIMAL(5,2) NULL
hematokrit              DECIMAL(5,2) NULL
neutrofil               DECIMAL(5,2) NULL
-- Elektrolit
natrium                 DECIMAL(8,2) NULL
kalium                  DECIMAL(8,2) NULL
kalsium                 DECIMAL(8,2) NULL
fosfor                  DECIMAL(8,2) NULL
catatan_tambahan        TEXT NULL
dicatat_oleh            BIGINT FK → penggunas.id
created_at              TIMESTAMP
updated_at              TIMESTAMP
```

**Table: `pemeriksaan_fisik_gizis`**
```sql
id                          BIGINT PK AUTO_INCREMENT
kunjungan_id                BIGINT FK → kunjungans.id NOT NULL
tekanan_darah_sistolik      SMALLINT NULL
tekanan_darah_diastolik     SMALLINT NULL
nadi_per_menit              SMALLINT NULL
respirasi_per_menit         SMALLINT NULL
suhu_celsius                DECIMAL(4,1) NULL
saturasi_oksigen_persen     TINYINT NULL
edema                       BOOLEAN DEFAULT FALSE
lokasi_edema                VARCHAR(100) NULL
tanda_defisiensi            JSON NULL               -- array defisiensi mikronutrien
gangguan_gastrointestinal   JSON NULL               -- mual, muntah, disfagia, dll
kondisi_mulut               TEXT NULL
kekuatan_genggam_kg         DECIMAL(5,2) NULL
catatan_klinis              TEXT NULL
dicatat_oleh                BIGINT FK → penggunas.id
created_at                  TIMESTAMP
updated_at                  TIMESTAMP
```

**Table: `riwayat_asupan_gizis`** *(Food Recall / FFQ)*
```sql
id                          BIGINT PK AUTO_INCREMENT
kunjungan_id                BIGINT FK → kunjungans.id NOT NULL
metode                      ENUM('food_recall_24h','food_recall_48h','food_recall_72h','ffq_semi_kuantitatif')
tanggal_recall              DATE NOT NULL
detail_asupan               JSON NOT NULL           -- array makanan + porsi
-- Hasil Kalkulasi Asupan
total_energi_kkal           DECIMAL(10,2) NULL
total_protein_gram          DECIMAL(10,2) NULL
total_lemak_gram            DECIMAL(10,2) NULL
total_karbohidrat_gram      DECIMAL(10,2) NULL
total_serat_gram            DECIMAL(10,2) NULL
total_natrium_mg            DECIMAL(10,2) NULL
persen_pemenuhan_energi     DECIMAL(5,2) NULL
persen_pemenuhan_protein    DECIMAL(5,2) NULL
persen_pemenuhan_lemak      DECIMAL(5,2) NULL
persen_pemenuhan_karbohidrat DECIMAL(5,2) NULL
kesimpulan_asupan           TEXT NULL
dicatat_oleh                BIGINT FK → penggunas.id
created_at                  TIMESTAMP
updated_at                  TIMESTAMP
```

**Table: `diagnosa_gizis`** *(Format PES — FHIR: Condition)*
```sql
id                              BIGINT PK AUTO_INCREMENT
kunjungan_id                    BIGINT FK → kunjungans.id NOT NULL
terminologi_id                  BIGINT FK → terminologi_diagnosis_gizis.id NOT NULL
domain                          ENUM('asupan','klinis','perilaku_lingkungan') NOT NULL
-- Komponen PES
problem_masalah                 VARCHAR(255) NOT NULL
etiologi_penyebab               TEXT NOT NULL
signs_symptoms                  TEXT NOT NULL
narasi_pes                      TEXT NOT NULL               -- ENCRYPTED
-- Prioritas
urutan_prioritas                TINYINT DEFAULT 1
status                          ENUM('aktif','teratasi','tidak_aktif') DEFAULT 'aktif'
-- Validasi & Tanda Tangan
divalidasi_oleh                 BIGINT FK → penggunas.id NULL
divalidasi_pada                 TIMESTAMP NULL
satusehat_condition_id          VARCHAR(100) NULL
dicatat_oleh                    BIGINT FK → penggunas.id
created_at                      TIMESTAMP
updated_at                      TIMESTAMP
```

**Table: `preskripsi_diets`** *(FHIR: CarePlan)*
```sql
id                              BIGINT PK AUTO_INCREMENT
kunjungan_id                    BIGINT FK → kunjungans.id NOT NULL
-- Kalkulasi Kebutuhan Energi
formula_basal                   ENUM('harris_benedict','mifflin_st_jeor','who','konsensus_dm','konsensus_ckd') NOT NULL
berat_badan_acuan               ENUM('aktual','ideal','adjusted') NOT NULL
-- Input Kalkulasi
kebutuhan_energi_basal_kkal     DECIMAL(10,2) NOT NULL
faktor_aktivitas                DECIMAL(4,2) NOT NULL        -- 1.2 - 1.9
faktor_stres                    DECIMAL(4,2) NOT NULL        -- 1.0 - 2.0
faktor_koreksi_usia             DECIMAL(4,2) DEFAULT 1.0
total_kebutuhan_energi_kkal     DECIMAL(10,2) NOT NULL
-- Distribusi Makronutrien
persen_karbohidrat              DECIMAL(5,2) NOT NULL
gram_karbohidrat                DECIMAL(10,2) NOT NULL
persen_protein                  DECIMAL(5,2) NOT NULL
gram_protein                    DECIMAL(10,2) NOT NULL
persen_lemak                    DECIMAL(5,2) NOT NULL
gram_lemak                      DECIMAL(10,2) NOT NULL
gram_serat                      DECIMAL(8,2) NULL
-- Restriksi Mikronutrien
batas_natrium_mg                DECIMAL(10,2) NULL
batas_kalium_mg                 DECIMAL(10,2) NULL
batas_fosfor_mg                 DECIMAL(10,2) NULL
batas_cairan_ml                 DECIMAL(10,2) NULL
-- Bentuk & Frekuensi
bentuk_makanan                  ENUM('biasa','lunak','saring','cair_penuh','cair_jernih','formula_medis') NOT NULL
frekuensi_makan_utama           TINYINT DEFAULT 3
frekuensi_selingan              TINYINT DEFAULT 2
-- Catatan & Pantangan
pantangan_spesifik              JSON NULL
catatan_klinis                  TEXT NULL                   -- ENCRYPTED
tujuan_terapi                   TEXT NOT NULL
target_luaran_klinis            JSON NULL
-- Jangka Waktu & Status
tanggal_mulai                   DATE NOT NULL
tanggal_evaluasi                DATE NULL
durasi_hari                     SMALLINT NULL
status                          ENUM('aktif','selesai','dibatalkan') DEFAULT 'aktif'
-- Otorisasi
dibuat_oleh                     BIGINT FK → penggunas.id NOT NULL
disetujui_oleh                  BIGINT FK → penggunas.id NULL  -- SpGK
disetujui_pada                  TIMESTAMP NULL
satusehat_careplan_id           VARCHAR(100) NULL
created_at                      TIMESTAMP
updated_at                      TIMESTAMP
```

**Table: `detail_menu_harians`**
```sql
id                      BIGINT PK AUTO_INCREMENT
preskripsi_diet_id      BIGINT FK → preskripsi_diets.id NOT NULL
waktu_makan             ENUM('makan_pagi','selingan_pagi','makan_siang','selingan_sore','makan_malam','selingan_malam')
bahan_makanan_id        BIGINT FK → bahan_makanans.id NOT NULL
porsi_gram              DECIMAL(8,2) NOT NULL
energi_kkal             DECIMAL(8,2) NOT NULL
protein_gram            DECIMAL(8,2) NOT NULL
lemak_gram              DECIMAL(8,2) NOT NULL
karbohidrat_gram        DECIMAL(8,2) NOT NULL
keterangan_penukar      TEXT NULL
created_at              TIMESTAMP
updated_at              TIMESTAMP
```

**Table: `catatan_konselings`**
```sql
id                          BIGINT PK AUTO_INCREMENT
kunjungan_id                BIGINT FK → kunjungans.id NOT NULL
tanggal_konseling           DATE NOT NULL
durasi_menit                SMALLINT NULL
metode                      ENUM('tatap_muka','telepon','video_call')
topik_konseling             JSON NOT NULL               -- array topik
isi_konseling               TEXT NOT NULL               -- ENCRYPTED
hambatan_pasien             TEXT NULL
kesepakatan_tindak_lanjut   TEXT NULL
tingkat_pemahaman_pasien    ENUM('baik','cukup','kurang') NULL
dokumen_edukasi_id          BIGINT FK → dokumen_edukasiis.id NULL
dilakukan_oleh              BIGINT FK → penggunas.id NOT NULL
created_at                  TIMESTAMP
updated_at                  TIMESTAMP
```

**Table: `monitorings`**
```sql
id                              BIGINT PK AUTO_INCREMENT
kunjungan_id                    BIGINT FK → kunjungans.id NOT NULL     -- kunjungan saat ini
kunjungan_sebelumnya_id         BIGINT FK → kunjungans.id NULL          -- referensi kunjungan lalu
-- Indikator yang Dipantau
parameter_dipantau              JSON NOT NULL               -- array indikator
-- Evaluasi
evaluasi_anthropometri          TEXT NULL
evaluasi_biokimia               TEXT NULL
evaluasi_asupan                 TEXT NULL
evaluasi_kepatuhan_diet         ENUM('patuh','cukup_patuh','tidak_patuh') NULL
persen_sisa_makanan             DECIMAL(5,2) NULL
-- Kesimpulan & Rencana
kesimpulan                      TEXT NULL
rekomendasi_lanjutan            TEXT NULL
perlu_rujukan                   BOOLEAN DEFAULT FALSE
tujuan_rujukan                  TEXT NULL
rencana_kunjungan_berikutnya    DATE NULL
dilakukan_oleh                  BIGINT FK → penggunas.id NOT NULL
created_at                      TIMESTAMP
updated_at                      TIMESTAMP
```

#### D. Tabel Dokumen & Laporan

**Table: `dokumen_edukasiis`**
```sql
id                  BIGINT PK AUTO_INCREMENT
pasien_id           BIGINT FK → pasiens.id NOT NULL
kunjungan_id        BIGINT FK → kunjungans.id NOT NULL
judul_dokumen       VARCHAR(255) NOT NULL
tipe                ENUM('leaflet_diet','panduan_makan','ringkasan_kalori','pantangan_alergi','rencana_makan')
konten_json         JSON NOT NULL               -- template data untuk render
path_pdf            VARCHAR(500) NULL           -- path hasil export PDF
token_akses         VARCHAR(100) NULL           -- token link aman untuk pasien
token_expired_at    TIMESTAMP NULL
dibuat_oleh         BIGINT FK → penggunas.id NOT NULL
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

**Table: `laporan_statistiks`**
```sql
id                  BIGINT PK AUTO_INCREMENT
tipe_laporan        ENUM('kinerja_harian','demografi_patologi','rasio_intervensi','spm_gizi','audit_mutu')
periode_dari        DATE NOT NULL
periode_sampai      DATE NOT NULL
parameter           JSON NULL
data_laporan        JSON NOT NULL
dibuat_oleh         BIGINT FK → penggunas.id NOT NULL
created_at          TIMESTAMP
```

#### E. Tabel Audit & Keamanan

**Table: `audit_logs`** *(via OwenIt Laravel Auditing — otomatis)*
```sql
id                  BIGINT PK
user_type           VARCHAR(100)
user_id             BIGINT
event               VARCHAR(50)             -- created, updated, deleted
auditable_type      VARCHAR(100)
auditable_id        BIGINT
old_values          JSON NULL
new_values          JSON NULL
url                 TEXT NULL
ip_address          VARCHAR(45)
user_agent          TEXT NULL
tags                TEXT NULL
created_at          TIMESTAMP
```

**Table: `login_histories`**
```sql
id                  BIGINT PK AUTO_INCREMENT
pengguna_id         BIGINT FK → penggunas.id NOT NULL
tipe_event          ENUM('login','logout','login_gagal','timeout')
ip_address          VARCHAR(45)
user_agent          TEXT
created_at          TIMESTAMP
```

---

## 6. ALUR SISTEM (WORKFLOW KLINIS)

### Alur 1: Registrasi & Skrining
```
[Pasien Datang]
     ↓
[Perawat: Daftarkan Kunjungan]
  - Buat nomor kunjungan otomatis (format: KGZ-YYYYMMDD-XXXX)
  - Input vital sign
  - Tentukan tipe kunjungan (mandiri/rujukan)
     ↓
[Perawat: Skrining Gizi]
  - Pilih metode (MNA/NRS-2002/MST/MUST)
  - Input skor 3 domain
  - SISTEM: hitung total skor → tetapkan kategori risiko
     ↓
[SISTEM: Triase Otomatis]
  - Risiko Tinggi → flag merah + prioritas antrean + notifikasi dietisien
  - Risiko Sedang → flag kuning + asuhan gizi lanjutan
  - Risiko Rendah → flag hijau + konseling rutin
```

### Alur 2: Asesmen Gizi (PAGT Tahap 1)
```
[Dietisien/Nutrisionis: Asesmen]
     ↓
[Tab 1: Antropometri]
  - Input BB, TB → SISTEM kalkulasi IMT otomatis
  - Jika pediatri: SISTEM plotting Z-score ke kurva WHO
     ↓
[Tab 2: Biokimia]
  - Tarik dari lab internal (jika terintegrasi) ATAU
  - Input manual hasil lab
     ↓
[Tab 3: Pemeriksaan Fisik Klinis]
  - Input tanda defisiensi, gangguan GI, observasi klinis
     ↓
[Tab 4: Riwayat Asupan]
  - Input food recall 24h per waktu makan
  - SISTEM: kalkulasi otomatis total asupan vs kebutuhan (%)
     ↓
[SISTEM: Agregasi Asesmen → Generate Ringkasan Klinis]
```

### Alur 3: Diagnosis Gizi (PAGT Tahap 2)
```
[Dietisien: Penegakan Diagnosis]
     ↓
[Pilih Domain: Asupan / Klinis / Perilaku Lingkungan]
  - Pilih kode dari terminologi standar
  - SISTEM tampilkan template PES
     ↓
[Input PES]
  - Problem: dipilih dari dropdown terminologi
  - Etiologi: diisi naratif
  - Signs/Symptoms: diisi berdasarkan data asesmen
  - SISTEM: susun kalimat narasi PES otomatis
     ↓
[Urutkan prioritas diagnosis (1, 2, 3...)]
     ↓
[SpGK: Validasi & TTD Digital]
  - Status dokumen: DRAFT → DIVALIDASI
```

### Alur 4: Intervensi & Preskripsi (PAGT Tahap 3)
```
[Dietisien: Buka Modul Kalkulator Kalori]
     ↓
[Step 1: Pilih Formula Basal]
  - Harris-Benedict / Mifflin-St Jeor / WHO / Konsensus DM / Konsensus CKD
  - Input data: BB acuan, jenis kelamin, usia
  - SISTEM: hitung Kebutuhan Energi Basal (KEB)
     ↓
[Step 2: Koreksi Faktor]
  - Faktor Aktivitas (1.2 - 1.9) → dropdown deskriptif
  - Faktor Stres Metabolik (1.0 - 2.0) → dropdown kondisi klinis
  - Faktor Usia (jika perlu)
  - SISTEM: Total Kebutuhan = KEB × FA × FS
     ↓
[Step 3: Distribusi Makronutrien]
  - Input % karbohidrat, protein, lemak
  - SISTEM: hitung gramasi masing-masing
  - SISTEM: cek terhadap restriksi kondisi (DM, hipertensi, CKD)
     ↓
[Step 4: Susun Menu Harian]
  - Pilih bahan makanan dari database
  - SISTEM: cek silang alergi → WARNING jika ada konflik
  - SISTEM: cek interaksi nutrisi-farmakologi
  - SISTEM: hitung total nilai gizi menu vs target
     ↓
[Step 5: Tentukan Bentuk & Frekuensi]
  - Bentuk makanan (biasa/lunak/cair)
  - Jadwal makan (3x utama + selingan)
     ↓
[Simpan Preskripsi → Generate Dokumen Edukasi]
     ↓
[SpGK: Persetujuan Final]
  - Status: DRAFT → DISETUJUI → DOKUMEN TERKUNCI
```

### Alur 5: Monitoring & Evaluasi (PAGT Tahap 4)
```
[Kunjungan Berikutnya]
     ↓
[Dietisien: Buka Modul Monitoring]
  - SISTEM tampilkan grafik tren: BB, HbA1c, albumin, dll
  - SISTEM tampilkan perbandingan target vs capaian
     ↓
[Input Evaluasi]
  - Evaluasi setiap indikator SMART
  - Input % sisa makanan / kepatuhan diet
  - Catat hambatan & kemajuan
     ↓
[Keputusan Klinis]
  - Lanjutkan preskripsi (tanpa perubahan)
  - Modifikasi preskripsi → buat preskripsi baru (versi)
  - Rujuk (aktivasi modul rujukan)
     ↓
[Audit Log otomatis mencatat semua modifikasi]
```

---

## 7. ATURAN VALIDASI (FormRequest)

Semua pesan validasi **WAJIB dalam Bahasa Indonesia**:

```php
// Contoh pesan validasi
'required' => ':attribute wajib diisi.',
'numeric'  => ':attribute harus berupa angka.',
'min'      => ':attribute minimal :min.',
'max'      => ':attribute maksimal :max.',
'between'  => ':attribute harus antara :min dan :max.',
'in'       => ':attribute tidak sesuai pilihan yang tersedia.',
'date'     => ':attribute harus berupa tanggal yang valid.',
'unique'   => ':attribute sudah terdaftar dalam sistem.',
```

### Validasi Kritis Klinis
- IMT: hasil hitung, bukan input langsung
- Total % makronutrien (KH + Protein + Lemak): wajib = 100%
- Faktor aktivitas: range 1.2 - 1.9
- Faktor stres: range 1.0 - 2.0
- Tanggal kunjungan: tidak boleh lebih dari hari ini
- Skor skrining: range sesuai instrumen

---

## 8. KEAMANAN SISTEM

### Enkripsi Data at Rest
```php
// Pada Model yang memiliki kolom sensitif
use Illuminate\Database\Eloquent\Casts\Encrypted;

protected $casts = [
    'nik'                => Encrypted::class,
    'nama_lengkap'       => Encrypted::class,
    'nomor_rekam_medis'  => Encrypted::class,
    'narasi_pes'         => Encrypted::class,
];
```

### Audit Trail
```php
// Pada setiap Model rekam medis
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Pasien extends Model implements Auditable
{
    use AuditableTrait;

    protected $auditEvents = ['created', 'updated', 'deleted'];
    protected $auditExclude = ['updated_at']; // kecualikan field non-substantif
}
```

### Session & CSRF
- Session timeout: 15 menit inaktif → auto logout + redirect login
- CSRF token: aktif di semua form
- Rate limiting: login max 5 kali gagal → lockout 15 menit
- Semua transmisi: HTTPS only (HSTS header)

### Blinded Interface (Admin TI)
```php
// Middleware BlindedAdminMiddleware
// Admin TI DILARANG mengakses:
$blockedRoutes = [
    'pasien.show', 'pasien.edit',
    'asesmen.*', 'diagnosa.*',
    'intervensi.*', 'monitoring.*',
    'laporan.klinis.*'
];
```

---

## 9. KONVENSI KODE

### Penamaan
- **Controller**: `PasienController`, `AsesmenGiziController`
- **Model**: `Pasien`, `DataAntropometri`, `DiagnosaGizi`
- **Migration**: `create_pasiens_table`, `create_data_antropometris_table`
- **Service**: `KalkulasiKaloriService`, `SatuSehatService`
- **Policy**: `PasienPolicy`, `AsesmenPolicy`
- **Enum**: `RolePengguna::SPGK`, `StatusRisikoGizi::TINGGI`

### Response Controller
```php
// Sukses dengan SweetAlert2 (via session flash)
return redirect()->route('pasien.index')
    ->with('swal_success', 'Data pasien berhasil disimpan.');

// Gagal validasi → FormRequest otomatis
// Error → dengan pesan Indonesia
return back()->withErrors(['field' => 'Pesan kesalahan Indonesia.']);
```

### SweetAlert2 (Wajib untuk semua interaksi penting)
```javascript
// Konfirmasi hapus
Swal.fire({
    title: 'Hapus Data?',
    text: 'Data yang dihapus tidak dapat dikembalikan.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, Hapus',
    cancelButtonText: 'Batal',
    confirmButtonColor: '#dc3545',
});

// Notifikasi sukses (dari session flash)
@if(session('swal_success'))
Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("swal_success") }}', timer: 2000 });
@endif
```

---

### Resource yang Diintegrasikan
| Resource FHIR | Modul NCPMS | Trigger |
|---|---|---|
| `Patient` | Master Pasien | Saat pasien baru didaftarkan |
| `Encounter` | Kunjungan | Saat kunjungan dibuat & diselesaikan |
| `Observation` | Antropometri, Biokimia | Saat data divalidasi |
| `Condition` | Diagnosa Gizi | Saat diagnosis divalidasi SpGK |
| `CarePlan` | Preskripsi Diet | Saat preskripsi disetujui SpGK |

### Terminologi Wajib
- Kode pengukuran: **LOINC** (misal: 29463-7 untuk Body Weight)
- Kode kondisi: **ICD-10** dan **SNOMED-CT**
- Kode prosedur: **ICD-9-CM**

---

## 10. LAPORAN DAN EXPORT

### Tipe Laporan
1. **Laporan Kinerja Harian**: jumlah kunjungan, distribusi peran, waktu pelayanan
2. **Demografi Patologi**: distribusi diagnosis medis penyerta pasien gizi
3. **Rasio Keberhasilan Intervensi**: % normalisasi nilai lab post-intervensi
4. **SPM Gizi**: indikator standar pelayanan minimal (ketepatan preskripsi, % sisa makanan)
5. **Audit Mutu**: laporan aktivitas audit log per periode

### Format Export
- PDF (via Laravel DomPDF atau MPDF)
- Excel (via Laravel Excel/Maatwebsite)
- Cetak langsung (print CSS)

---

## 11. ATURAN MIGRASI DATABASE

- Semua perubahan skema WAJIB via `php artisan make:migration`
- DILARANG alter tabel secara langsung di production
- Setiap migrasi wajib memiliki method `down()` untuk rollback
- Urutan migrasi: tabel master → tabel transaksi → tabel relasi
- Foreign key constraints WAJIB diterapkan
- Index pada kolom yang sering di-query (tanggal, FK, status)
