# 🏥 NCPMS (Nutrition Care Process Management System)
**Sistem Manajemen Asuhan Gizi Rumah Sakit Terstandar**

NCPMS adalah Sistem Informasi Manajemen Rumah Sakit (SIMRS) terspesialisasi pada bidang Pelayanan Gizi. Sistem ini dirancang khusus untuk memfasilitasi Dietisien, Nutrisionis, Dokter Spesialis Gizi Klinis (SpGK), dan Perawat dalam melaksanakan Proses Asuhan Gizi Terstandar (PAGT) dengan kepatuhan penuh terhadap regulasi pemerintah.

Aplikasi ini mengadopsi prinsip antarmuka modern yang bersih, efisien, dan berpusat pada pengguna (user-centric), dikembangkan dengan kerangka kerja Laravel 12.

---

## 📜 Kepatuhan Regulasi (Compliance)

NCPMS dibangun berlandaskan 3 pilar regulasi dan pedoman nasional:
1. **Permenkes RI No. 24 Tahun 2022** tentang Rekam Medis Elektronik (RME)
2. **Pedoman Pelayanan Gizi Rumah Sakit (PGRS)** Kemenkes RI Tahun 2013
3. **Pedoman Proses Asuhan Gizi Terstandar (PAGT)** / *Nutrition Care Process* (NCP)

---

## ✨ Fitur Unggulan

### 🔐 1. Kepatuhan Rekam Medis Elektronik (Permenkes 24/2022)
*   **Tanda Tangan Elektronik (TTE):** Fitur penguncian dokumen (*lock*) dengan stempel waktu dan identifikasi nakes. Setelah dikunci, data tidak dapat dimodifikasi secara langsung.
*   **Sistem Audit Trail RME:** Setiap aktivitas (melihat, menambah, mengubah, mencetak, atau mengunci) pada data pasien terekam dalam log permanen yang tidak bisa dihapus, dilengkapi pelacakan *IP Address* dan *User Agent*.
*   **Modul Adendum & Koreksi RME (Pasal 20):** Dokumen rekam medis yang telah dikunci TTE hanya dapat diperbaiki melalui mekanisme Adendum (koreksi terlampir yang menampilkan jejak perubahan tanpa menghapus data asli).
*   **Enkripsi Data Pasien (AES-256):** Data rekam medis (Nomor RM, NIK, Nama, Alamat, Antropometri, Diagnosis, dan Catatan Klinis) dienkripsi otomatis di tingkat basis data.

### 🥗 2. Modul Pelayanan Asuhan Gizi Terstandar (PAGT)
*   **Skrining Gizi Demografi Spesifik:** Mendukung metode skrining terstandar sesuai demografi pasien:
    *   **MST** (Dewasa Umum)
    *   **NRS-2002** (Dewasa Risiko Tinggi / Rawat Inap / ICU)
    *   **STRONGkids** (Pediatri / Anak 1 bln - 18 tahun)
    *   **MNA** (Geriatri / Lansia > 65 tahun)
*   **Asesmen Komprehensif:** Modul pencatatan Antropometri, Biokimia, Fisik/Klinis, dan Riwayat Asupan Makanan (Recall 24h, SQ-FFQ).
*   **Diagnosis Gizi (PES):** Pencatatan *Problem*, *Etiology*, *Sign/Symptom* dengan terminologi baku (NI, NC, NB).
*   **Kalkulator Kebutuhan Energi Dinamis:** Terintegrasi langsung dengan formulir Preskripsi Diet, mendukung penghitungan Harris-Benedict & Mifflin-St Jeor.
*   **Peringatan Interaksi Obat-Makanan (*Food-Drug Interaction*):** Peringatan otomatis cerdas saat Dietisien meresepkan diet pada pasien yang sedang mengonsumsi obat-obatan khusus (misal: Warfarin, Captopril, Metformin).

### 🍳 3. Modul Dapur & Penyelenggaraan Makanan (PGRS 2013)
*   **Dashboard Instalasi Gizi (Dapur):** Melihat *real-time* permintaan preskripsi diet dari ruang rawat.
*   **Cetak Etiket Label Makanan:** Pembuatan dan pencetakan etiket label pasien yang siap ditempel pada *food tray*, lengkap dengan detail bentuk makanan, kalori, dan alergi/pantangan.
*   **Pemindai Keselamatan Makanan (QR Code):** Fitur khusus perawat/pramusaji untuk memindai label makanan dan memastikan diet disajikan pada pasien yang tepat tanpa risiko silang-alergen (Standar JCI).
*   **Pemantauan Sisa Makanan (Comstock Scale):** Fasilitas pencatatan persentase sisa piring (0%, 25%, 50%, 75%, 100%) untuk mengevaluasi daya terima dan keefektifan diet.
*   **Alarm Status Puasa Darurat (NPO):** Sirine visual *real-time* di Dashboard Dapur yang menginstruksikan penyetopan penyajian makanan ketika pasien mendadak diinstruksikan puasa operasi medis.
*   **Master Data Bahan Makanan:** Basis data nilai tukar/kandungan zat gizi bahan makanan untuk formulasi diet.

### 🤝 4. Pelayanan Lintas Pengaturan (ICU & Rawat Jalan)
*   **Modul Enteral & Parenteral (ICU):** Kalkulasi laju makanan cair (drip/jam), osmolaritas, dan formulasi diet enteral spesifik untuk pasien kritis.
*   **Modul Rawat Jalan (Poliklinik):** Penjadwalan perjanjian konsultasi (Appointment) dan edukasi diet preskriptif rawat jalan (Mandiri/Rujukan).
*   **Monitoring & Evaluasi:** Pemantauan perkembangan luaran klinis pasien (CPPT).
*   **Catatan Edukasi & Konseling:** Pencatatan durasi, metode, topik, dan tingkat pemahaman pasien atas edukasi gizi.
*   **Cetak Dokumen Medis PDF:** Ekspor rekaman asuhan gizi ke dalam format PDF yang tersertifikasi.

---

## 🛠️ Stack Teknologi

*   **Backend:** PHP 8.2, Laravel 12.x
*   **Frontend:** Bootstrap 5.3.3, Vanilla JavaScript, Chart.js
*   **Database:** MySQL / MariaDB
*   **Security:** Laravel Crypt (AES-256-CBC), Middleware Role-based
*   **PDF Generator:** Barryvdh / dompdf

---

## 🚀 Panduan Instalasi (Development)

1. **Kloning Repositori**
   ```bash
   git clone https://github.com/ardhikaxx/ncpms.git
   cd ncpms
   ```

2. **Instalasi Dependensi**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Atur konfigurasi database MySQL Anda di dalam file `.env`.*

4. **Migrasi & Seeding Database**
   ```bash
   php artisan migrate:fresh --seed
   ```
   *Perintah ini akan menyuntikkan data dummy yang lengkap (Pasien, Kunjungan, Skrining, Interaksi Obat, Adendum, Etiket Makanan, dll).*

5. **Jalankan Server Lokal**
   ```bash
   php artisan serve
   ```
   *Aplikasi dapat diakses di http://127.0.0.1:8000*

---

## 👥 Hak Akses (Role-Based)

Gunakan kredensial berikut untuk menguji masing-masing *role* (Password default: `password`):
*   **Dokter Spesialis Gizi Klinis (SpGK):** `andika.spgk@ncpms.local`
*   **Dietisien:** `budi.dietisien@ncpms.local`
*   **Nutrisionis:** `citra.nutrisionis@ncpms.local`
*   **Perawat:** `diana.perawat@ncpms.local`
*   **Admin/Dapur:** `eko.admin@ncpms.local`

---

*Dikembangkan untuk dedikasi tinggi terhadap pelayanan kesehatan dan asuhan gizi yang presisi.* 🍏
