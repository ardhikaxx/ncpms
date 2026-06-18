# design-NCPMS.md
# NCPMS — Nutrition Care and Patient Management System
# Panduan Desain UI/UX — Sistem Manajemen Gizi Klinis

---

## 1. IDENTITAS VISUAL

### Nama & Tagline
- **Nama Sistem**: NCPMS
- **Nama Panjang**: Nutrition Care and Patient Management System
- **Tagline**: *Presisi Gizi, Akurasi Asuhan*
- **Tone Visual**: Klinis-profesional, bersih, dapat dipercaya, hangat secara ilmiah

### Konsep Desain
Sistem ini melayani tenaga kesehatan profesional yang bekerja dalam lingkungan klinis bertekanan tinggi. Desain harus:
- **Mengurangi beban kognitif** — informasi tersaji terstruktur, hierarkis, dan dapat dipindai cepat
- **Memberikan kepercayaan klinis** — tampilan serius, presisi, tidak diragukan akurasinya
- **Mengutamakan efisiensi** — alur kerja tanpa friksi, interaksi minimal untuk tugas rutin
- **Mencerminkan kehangatan medis** — tidak dingin dan mesin, tetapi humanis dan peduli

---

## 2. PALET WARNA

### Warna Utama (Primary)
```css
--color-primary:          #1A7A64;   /* Clinical Teal Utama — warna dominan sistem */
--color-primary-dark:     #145C4B;   /* Teal gelap — hover, active, sidebar */
--color-primary-darker:   #0E3F35;   /* Teal sangat gelap — sidebar active, badge */
--color-primary-light:    #2BA882;   /* Teal terang — elemen sekunder */
--color-primary-subtle:   #E8F5F1;   /* Teal sangat muda — background panel, row hover */
--color-primary-border:   #B2DDD4;   /* Teal muda — garis batas elemen teal */
```

### Warna Aksen (Accent)
```css
--color-accent:           #F4A830;   /* Warm Amber — tindakan penting, highlight */
--color-accent-dark:      #D4891A;   /* Amber gelap — hover tombol aksen */
--color-accent-light:     #FDE9C0;   /* Amber muda — badge, tag */
```

### Warna Semantik Klinis
```css
/* Status Risiko Gizi */
--color-risiko-tinggi:    #D93025;   /* Merah klinis — risiko tinggi */
--color-risiko-sedang:    #E67E22;   /* Oranye — risiko sedang */
--color-risiko-rendah:    #27AE60;   /* Hijau — risiko rendah */
--color-risiko-bg-tinggi: #FDF2F2;
--color-risiko-bg-sedang: #FEF9EC;
--color-risiko-bg-rendah: #F0FAF4;

/* Status Dokumen */
--color-status-draft:     #6C757D;
--color-status-divalidasi:#1A7A64;
--color-status-terkunci:  #145C4B;
--color-status-batal:     #D93025;

/* Status Kepatuhan */
--color-patuh:            #27AE60;
--color-cukup-patuh:      #E67E22;
--color-tidak-patuh:      #D93025;

/* Nilai Laboratorium */
--color-lab-normal:       #27AE60;
--color-lab-tinggi:       #D93025;
--color-lab-rendah:       #2980B9;
--color-lab-kritis:       #8E44AD;
```

### Warna Netral
```css
--color-bg-page:          #F0F4F3;   /* Background halaman — abu-abu teal */
--color-bg-card:          #FFFFFF;   /* Background kartu */
--color-bg-sidebar:       #0E3F35;   /* Sidebar gelap */
--color-bg-topbar:        #FFFFFF;   /* Top navigation */
--color-bg-input:         #FFFFFF;
--color-bg-input-disabled:#F8F9FA;
--color-bg-table-header:  #E8F5F1;   /* Header tabel */
--color-bg-row-even:      #FAFFFE;
--color-bg-row-hover:     #E8F5F1;

--color-text-primary:     #1C2A27;   /* Teks utama — hitam teal */
--color-text-secondary:   #4A6560;   /* Teks sekunder */
--color-text-muted:       #8AA09A;   /* Teks tersamar */
--color-text-inverse:     #FFFFFF;   /* Teks di atas background gelap */
--color-text-link:        #1A7A64;

--color-border:           #D4E6E1;   /* Garis batas umum */
--color-border-input:     #C0D8D2;
--color-border-focus:     #1A7A64;
--color-divider:          #EAF2EF;
```

### Penggunaan Warna — Aturan Ketat
- **Primary teal** hanya untuk: sidebar, tombol aksi utama, header aktif, badge status aktif, grafik utama
- **Accent amber** hanya untuk: tombol aksi kritis sekunder (simpan & lanjut), alert informasi penting, badge highlight
- **Merah/oranye/hijau semantik** hanya untuk: indikator status klinis (risiko, kepatuhan, nilai lab)
- **DILARANG** menggunakan warna primer untuk dekorasi non-fungsional

---

## 3. TIPOGRAFI

### Font Utama
```css
/* Import di <head> — Google Fonts CDN */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap');

--font-primary:   'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
--font-secondary: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
--font-mono:      'JetBrains Mono', 'Courier New', monospace;
```

### Hierarki Tipografi
```css
/* Judul Halaman / Page Title */
--text-page-title:    font-size: 1.375rem (22px); font-weight: 700; color: --color-text-primary;

/* Judul Kartu / Section */
--text-card-title:    font-size: 1.0625rem (17px); font-weight: 700; color: --color-text-primary;

/* Sub-judul */
--text-subtitle:      font-size: 0.9375rem (15px); font-weight: 600; color: --color-text-secondary;

/* Body / Konten Utama */
--text-body:          font-size: 0.875rem (14px); font-weight: 400; color: --color-text-primary;

/* Label Form */
--text-label:         font-size: 0.8125rem (13px); font-weight: 600; color: --color-text-secondary;

/* Nilai / Data Klinis */
--text-clinical-value: font-size: 1.25rem (20px); font-weight: 700; font-family: Inter; color: --color-primary;

/* Data Numerik Besar (stat card) */
--text-stat-value:    font-size: 1.875rem (30px); font-weight: 800; font-family: Inter;

/* Kecil / Keterangan */
--text-small:         font-size: 0.75rem (12px); font-weight: 400; color: --color-text-muted;

/* Monospace — kode, ID, nomor rekam medis */
--text-mono:          font-size: 0.8125rem (13px); font-family: JetBrains Mono;
```

---

## 4. TATA LETAK (LAYOUT)

### Struktur Utama
```
┌─────────────────────────────────────────────────────┐
│  TOPBAR (fixed, h=60px, bg-white, shadow-sm)        │
├──────────────┬──────────────────────────────────────┤
│              │                                       │
│  SIDEBAR     │   MAIN CONTENT AREA                  │
│  (fixed)     │   (scrollable, bg: --color-bg-page)  │
│  w=240px     │                                       │
│  bg: dark    │   ┌─────────────────────────────┐    │
│  teal        │   │  Page Header (breadcrumb,    │    │
│              │   │  judul, tombol aksi)         │    │
│              │   └─────────────────────────────┘    │
│              │                                       │
│              │   ┌─────────────────────────────┐    │
│              │   │  Content Cards               │    │
│              │   └─────────────────────────────┘    │
│              │                                       │
└──────────────┴──────────────────────────────────────┘
```

### Dimensi Grid
```css
--sidebar-width:        240px;
--topbar-height:        60px;
--content-padding:      24px;          /* padding halaman konten */
--card-padding:         24px;          /* padding dalam kartu */
--card-padding-sm:      16px;
--card-gap:             16px;          /* jarak antar kartu */
--border-radius-card:   12px;
--border-radius-input:  8px;
--border-radius-badge:  6px;
--border-radius-btn:    8px;
```

### Breakpoint Responsif
```css
/* NCPMS adalah sistem desktop-first (klinisi pakai komputer) */
/* Mobile hanya untuk monitoring sederhana */

/* Desktop (default, target utama) */
@media (min-width: 1200px) { /* Layout penuh dua kolom */ }

/* Tablet */
@media (max-width: 1199px) {
    --sidebar-width: 64px; /* collapse ke icon-only */
}

/* Mobile (terbatas) */
@media (max-width: 767px) {
    /* Sidebar tersembunyi, trigger via hamburger */
    /* Konten full-width */
}
```

---

## 5. KOMPONEN UI

### A. SIDEBAR

```css
.sidebar {
    width: var(--sidebar-width);          /* 240px */
    background: var(--color-bg-sidebar);  /* #0E3F35 */
    position: fixed;
    top: 0; left: 0;
    height: 100vh;
    overflow-y: auto;
    z-index: 1030;
    display: flex;
    flex-direction: column;
}

/* Logo area */
.sidebar-brand {
    padding: 20px 20px 16px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}
.sidebar-brand .brand-name {
    font-family: var(--font-primary);
    font-weight: 800;
    font-size: 1.125rem;
    color: #FFFFFF;
    letter-spacing: -0.01em;
}
.sidebar-brand .brand-tagline {
    font-size: 0.6875rem;
    color: rgba(255,255,255,0.5);
    margin-top: 2px;
}

/* Menu Group Label */
.sidebar-menu-label {
    font-size: 0.625rem;
    font-weight: 700;
    color: rgba(255,255,255,0.35);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    padding: 20px 20px 8px;
}

/* Menu Item */
.sidebar-menu-item a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 20px;
    color: rgba(255,255,255,0.7);
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 0;
    transition: all 0.15s ease;
    text-decoration: none;
}
.sidebar-menu-item a:hover {
    background: rgba(255,255,255,0.08);
    color: #FFFFFF;
}
.sidebar-menu-item a.active {
    background: var(--color-primary);    /* #1A7A64 */
    color: #FFFFFF;
    font-weight: 600;
}
.sidebar-menu-item a .menu-icon {
    width: 18px;
    text-align: center;
    font-size: 0.875rem;
    opacity: 0.8;
}

/* Peran badge di bawah sidebar */
.sidebar-user-info {
    margin-top: auto;
    padding: 16px 20px;
    border-top: 1px solid rgba(255,255,255,0.1);
}
.sidebar-user-info .user-name {
    font-size: 0.8125rem;
    font-weight: 600;
    color: #FFFFFF;
}
.sidebar-user-info .user-role {
    font-size: 0.6875rem;
    color: rgba(255,255,255,0.5);
    margin-top: 2px;
}
```

### B. TOPBAR

```css
.topbar {
    position: fixed;
    top: 0;
    left: var(--sidebar-width);
    right: 0;
    height: var(--topbar-height);         /* 60px */
    background: var(--color-bg-topbar);
    border-bottom: 1px solid var(--color-border);
    display: flex;
    align-items: center;
    padding: 0 24px;
    z-index: 1020;
    gap: 16px;
}

/* Breadcrumb */
.topbar-breadcrumb .breadcrumb {
    margin: 0;
    font-size: 0.8125rem;
}
.topbar-breadcrumb .breadcrumb-item.active {
    color: var(--color-primary);
    font-weight: 600;
}

/* Topbar kanan — notifikasi, profil */
.topbar-right {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 12px;
}
.topbar-icon-btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: var(--color-primary-subtle);
    color: var(--color-primary-dark);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.15s;
    position: relative;
}
.topbar-icon-btn:hover {
    background: var(--color-primary-border);
}
```

### C. PAGE HEADER

```css
/* Header setiap halaman — di bawah topbar, di atas konten */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}
.page-header .page-title {
    font-size: 1.375rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0;
}
.page-header .page-subtitle {
    font-size: 0.8125rem;
    color: var(--color-text-muted);
    margin-top: 2px;
}
.page-header-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}
```

### D. KARTU (CARD)

```css
/* Kartu standar */
.ncpms-card {
    background: var(--color-bg-card);
    border-radius: var(--border-radius-card);      /* 12px */
    border: 1px solid var(--color-border);
    box-shadow: 0 1px 3px rgba(26,122,100,0.06), 0 1px 2px rgba(0,0,0,0.04);
    padding: var(--card-padding);                   /* 24px */
    margin-bottom: var(--card-gap);                 /* 16px */
}

/* Card Header */
.ncpms-card .card-header-custom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 16px;
    margin-bottom: 20px;
    border-bottom: 1px solid var(--color-divider);
}
.ncpms-card .card-title-custom {
    font-size: 1.0625rem;
    font-weight: 700;
    color: var(--color-text-primary);
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}
.ncpms-card .card-title-custom .card-title-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--color-primary-subtle);
    color: var(--color-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
}

/* Stat Card — dasbor ringkasan */
.stat-card {
    background: var(--color-bg-card);
    border-radius: 12px;
    border: 1px solid var(--color-border);
    padding: 20px 24px;
    position: relative;
    overflow: hidden;
}
.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--color-primary);    /* atau warna semantik sesuai konteks */
}
.stat-card .stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--color-text-muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.stat-card .stat-value {
    font-size: 1.875rem;
    font-weight: 800;
    color: var(--color-primary-dark);
    font-family: var(--font-secondary);
    line-height: 1.1;
    margin: 4px 0 2px;
}
.stat-card .stat-sub {
    font-size: 0.75rem;
    color: var(--color-text-muted);
}
.stat-card .stat-icon {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 2rem;
    opacity: 0.08;
    color: var(--color-primary);
}

/* Kartu Klinis — untuk data pasien spesifik */
.clinical-card {
    border-left: 4px solid var(--color-primary);
    border-radius: 0 12px 12px 0;
}
.clinical-card.risiko-tinggi { border-left-color: var(--color-risiko-tinggi); }
.clinical-card.risiko-sedang { border-left-color: var(--color-risiko-sedang); }
.clinical-card.risiko-rendah { border-left-color: var(--color-risiko-rendah); }
```

### E. TOMBOL (BUTTON)

```css
/* Tombol Utama */
.btn-primary-ncpms {
    background: var(--color-primary);
    color: #FFFFFF;
    border: none;
    padding: 9px 20px;
    border-radius: var(--border-radius-btn);
    font-weight: 600;
    font-size: 0.875rem;
    font-family: var(--font-primary);
    cursor: pointer;
    transition: background 0.15s, box-shadow 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-primary-ncpms:hover {
    background: var(--color-primary-dark);
    box-shadow: 0 4px 12px rgba(26,122,100,0.25);
}

/* Tombol Aksen */
.btn-accent-ncpms {
    background: var(--color-accent);
    color: #FFFFFF;
    /* style sama dengan primary */
}
.btn-accent-ncpms:hover {
    background: var(--color-accent-dark);
    box-shadow: 0 4px 12px rgba(244,168,48,0.25);
}

/* Tombol Outline */
.btn-outline-ncpms {
    background: transparent;
    color: var(--color-primary);
    border: 1.5px solid var(--color-primary);
    padding: 8px 20px;
    border-radius: var(--border-radius-btn);
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.15s;
}
.btn-outline-ncpms:hover {
    background: var(--color-primary-subtle);
}

/* Tombol Bahaya */
.btn-danger-ncpms {
    background: var(--color-risiko-tinggi);
    color: #FFFFFF;
    /* style sama */
}

/* Tombol Kecil */
.btn-sm-ncpms { padding: 5px 12px; font-size: 0.8125rem; }

/* Tombol Icon */
.btn-icon-ncpms {
    width: 34px;
    height: 34px;
    padding: 0;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
}

/* Kelompok Tombol Aksi Tabel */
.table-action-group {
    display: flex;
    gap: 6px;
    justify-content: center;
}
```

### F. FORM & INPUT

```css
/* Label */
.form-label-ncpms {
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    margin-bottom: 6px;
    display: block;
}
.form-label-ncpms .required-mark {
    color: var(--color-risiko-tinggi);
    margin-left: 3px;
}

/* Input Field */
.form-control-ncpms {
    border: 1.5px solid var(--color-border-input);
    border-radius: var(--border-radius-input);
    padding: 9px 12px;
    font-size: 0.875rem;
    font-family: var(--font-primary);
    color: var(--color-text-primary);
    background: var(--color-bg-input);
    width: 100%;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.form-control-ncpms:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(26,122,100,0.12);
}
.form-control-ncpms:disabled {
    background: var(--color-bg-input-disabled);
    color: var(--color-text-muted);
    cursor: not-allowed;
}
.form-control-ncpms.is-invalid {
    border-color: var(--color-risiko-tinggi);
    box-shadow: 0 0 0 3px rgba(217,48,37,0.1);
}

/* Input Numerik Klinis */
.form-control-clinical {
    font-family: var(--font-secondary);
    font-weight: 600;
    font-size: 1rem;
}

/* Field dengan unit (misal: "kg", "cm") */
.input-with-unit {
    position: relative;
}
.input-with-unit .unit-label {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--color-text-muted);
    pointer-events: none;
}
.input-with-unit input { padding-right: 40px; }

/* Select */
.form-select-ncpms {
    /* sama dengan form-control-ncpms + */
    background-image: url("data:image/svg+xml,..."); /* panah kustom teal */
    appearance: none;
    padding-right: 32px;
}

/* Hint / Help Text */
.form-hint {
    font-size: 0.75rem;
    color: var(--color-text-muted);
    margin-top: 4px;
}
.form-error {
    font-size: 0.75rem;
    color: var(--color-risiko-tinggi);
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}
```

### G. TABEL DATA

```css
/* Tabel Klinis */
.ncpms-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.875rem;
}
.ncpms-table thead th {
    background: var(--color-bg-table-header);      /* #E8F5F1 */
    color: var(--color-primary-dark);
    font-weight: 700;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 12px 16px;
    border-bottom: 2px solid var(--color-primary-border);
    white-space: nowrap;
    position: sticky;
    top: 0;
}
.ncpms-table tbody tr {
    background: var(--color-bg-card);
    transition: background 0.1s;
}
.ncpms-table tbody tr:nth-child(even) {
    background: var(--color-bg-row-even);
}
.ncpms-table tbody tr:hover {
    background: var(--color-bg-row-hover);
}
.ncpms-table tbody td {
    padding: 12px 16px;
    border-bottom: 1px solid var(--color-divider);
    color: var(--color-text-primary);
    vertical-align: middle;
}
.ncpms-table tbody tr:last-child td {
    border-bottom: none;
}

/* Kolom nomor rekam medis / ID */
.ncpms-table td.col-id {
    font-family: var(--font-mono);
    font-size: 0.8125rem;
    color: var(--color-text-secondary);
}

/* Kolom nilai klinis */
.ncpms-table td.col-value {
    font-family: var(--font-secondary);
    font-weight: 600;
}

/* Kosong state */
.table-empty-state {
    text-align: center;
    padding: 48px 24px;
    color: var(--color-text-muted);
}
.table-empty-state .empty-icon {
    font-size: 2rem;
    margin-bottom: 12px;
    color: var(--color-primary-border);
}
```

### H. BADGE & TAG STATUS

```css
/* Badge dasar */
.ncpms-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 6px;
    font-size: 0.6875rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    white-space: nowrap;
}

/* Badge Risiko Gizi */
.badge-risiko-tinggi { background: var(--color-risiko-bg-tinggi); color: var(--color-risiko-tinggi); }
.badge-risiko-sedang { background: var(--color-risiko-bg-sedang); color: var(--color-risiko-sedang); }
.badge-risiko-rendah { background: var(--color-risiko-bg-rendah); color: var(--color-risiko-rendah); }

/* Badge Peran Pengguna */
.badge-spgk       { background: #EDE7F6; color: #6A1B9A; }
.badge-dietisien  { background: #E3F2FD; color: #1565C0; }
.badge-nutrisionis{ background: #E8F5E9; color: #2E7D32; }
.badge-perawat    { background: #FFF3E0; color: #E65100; }
.badge-admin      { background: #F5F5F5; color: #424242; }

/* Badge Status Dokumen */
.badge-draft      { background: #F5F5F5; color: #757575; }
.badge-divalidasi { background: var(--color-primary-subtle); color: var(--color-primary-dark); }
.badge-terkunci   { background: var(--color-primary-darker); color: #FFFFFF; }
.badge-batal      { background: var(--color-risiko-bg-tinggi); color: var(--color-risiko-tinggi); }

/* Tag info kecil */
.ncpms-tag {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.6875rem;
    font-weight: 600;
    background: var(--color-primary-subtle);
    color: var(--color-primary-dark);
}
```

### I. INDIKATOR NILAI KLINIS

```css
/* Indikator nilai lab / antropometri */
.clinical-value-card {
    background: var(--color-bg-card);
    border: 1px solid var(--color-border);
    border-radius: 10px;
    padding: 14px 16px;
    text-align: center;
}
.clinical-value-card .cv-label {
    font-size: 0.6875rem;
    font-weight: 700;
    color: var(--color-text-muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 4px;
}
.clinical-value-card .cv-value {
    font-size: 1.5rem;
    font-weight: 800;
    font-family: var(--font-secondary);
    line-height: 1;
    margin-bottom: 4px;
}
.clinical-value-card .cv-unit {
    font-size: 0.75rem;
    color: var(--color-text-muted);
    font-weight: 500;
}
.clinical-value-card .cv-status {
    margin-top: 8px;
}

/* Warna berdasarkan status */
.cv-normal   .cv-value { color: var(--color-lab-normal); }
.cv-tinggi   .cv-value { color: var(--color-lab-tinggi); }
.cv-rendah   .cv-value { color: var(--color-lab-rendah); }
.cv-kritis   .cv-value { color: var(--color-lab-kritis); }

/* Progress Bar Pemenuhan Asupan */
.asupan-progress {
    margin-bottom: 12px;
}
.asupan-progress .progress-label {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    margin-bottom: 4px;
}
.asupan-progress .progress-bar-wrap {
    height: 8px;
    background: var(--color-divider);
    border-radius: 99px;
    overflow: hidden;
}
.asupan-progress .progress-bar-fill {
    height: 100%;
    border-radius: 99px;
    background: var(--color-primary);
    transition: width 0.4s ease;
}
/* Warna progress berdasarkan % */
.progress-bar-fill.persen-baik    { background: var(--color-risiko-rendah); }
.progress-bar-fill.persen-sedang  { background: var(--color-risiko-sedang); }
.progress-bar-fill.persen-kurang  { background: var(--color-risiko-tinggi); }
```

### J. STEPPER (Wizard Alur PAGT)

```css
/* Digunakan di halaman Asesmen, Intervensi (multi-step) */
.pagt-stepper {
    display: flex;
    align-items: center;
    margin-bottom: 32px;
    gap: 0;
}
.pagt-step {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}
/* Garis penghubung */
.pagt-step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 16px;
    left: 50%;
    right: -50%;
    height: 2px;
    background: var(--color-border);
    z-index: 0;
}
.pagt-step.completed::after { background: var(--color-primary); }

.pagt-step-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 2px solid var(--color-border);
    background: var(--color-bg-card);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--color-text-muted);
    z-index: 1;
    position: relative;
    transition: all 0.2s;
}
.pagt-step.active   .pagt-step-circle { border-color: var(--color-primary); background: var(--color-primary); color: #FFFFFF; }
.pagt-step.completed .pagt-step-circle { border-color: var(--color-primary); background: var(--color-primary-subtle); color: var(--color-primary); }

.pagt-step-label {
    font-size: 0.6875rem;
    font-weight: 600;
    color: var(--color-text-muted);
    margin-top: 6px;
    text-align: center;
    max-width: 80px;
}
.pagt-step.active .pagt-step-label   { color: var(--color-primary-dark); }
.pagt-step.completed .pagt-step-label { color: var(--color-primary); }
```

---

## 6. KONFIGURASI CHART.JS

```javascript
// Konfigurasi global — wajib diaplikasikan sebelum render chart
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.font.size   = 12;
Chart.defaults.color       = '#4A6560';

// Palet warna Chart.js NCPMS
const NCPMS_CHART_COLORS = {
    primary:    '#1A7A64',
    secondary:  '#2BA882',
    accent:     '#F4A830',
    danger:     '#D93025',
    warning:    '#E67E22',
    success:    '#27AE60',
    info:       '#2980B9',
    muted:      '#8AA09A',
};

// Opsi default Line Chart (tren klinis)
const defaultLineOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
            labels: { usePointStyle: true, padding: 20, font: { weight: '600', size: 11 } }
        },
        tooltip: {
            mode: 'index',
            intersect: false,
            backgroundColor: '#1C2A27',
            titleFont: { size: 12, weight: '700' },
            bodyFont:  { size: 12 },
            padding: 12,
            cornerRadius: 8,
        }
    },
    scales: {
        x: {
            grid: { color: '#EAF2EF', drawBorder: false },
            ticks: { font: { size: 11 }, color: '#8AA09A' }
        },
        y: {
            grid: { color: '#EAF2EF', drawBorder: false },
            ticks: { font: { size: 11 }, color: '#8AA09A' }
        }
    },
    elements: {
        line:  { tension: 0.4, borderWidth: 2 },
        point: { radius: 4, hoverRadius: 6, borderWidth: 2, backgroundColor: '#FFFFFF' }
    }
};

// Opsi default Bar Chart (perbandingan distribusi)
const defaultBarOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: { /* sama dengan atas */ }
    },
    scales: {
        x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#8AA09A' } },
        y: { grid: { color: '#EAF2EF', drawBorder: false }, ticks: { font: { size: 11 }, color: '#8AA09A' } }
    },
    borderRadius: 6,
};

// Opsi default Doughnut/Pie (distribusi makronutrien)
const defaultDoughnutOptions = {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '65%',
    plugins: {
        legend: {
            position: 'right',
            labels: { usePointStyle: true, padding: 16, font: { size: 12 } }
        }
    }
};
```

---

## 7. KONFIGURASI SWEETALERT2

```javascript
// Wrapper global NCPMS untuk SweetAlert2
const NCPMS_SWAL = {
    // Konfirmasi hapus / tindakan destruktif
    konfirmasiHapus: (judul = 'Hapus Data?', teks = 'Data tidak dapat dipulihkan.') =>
        Swal.fire({
            title: judul,
            text:  teks,
            icon:  'warning',
            showCancelButton:     true,
            confirmButtonText:    '<i class="fas fa-trash me-1"></i>Ya, Hapus',
            cancelButtonText:     'Batal',
            confirmButtonColor:   '#D93025',
            cancelButtonColor:    '#6C757D',
            reverseButtons:       true,
            focusCancel:          true,
        }),

    // Konfirmasi kunci dokumen (tidak bisa diubah)
    konfirmasiKunci: () =>
        Swal.fire({
            title: 'Kunci Dokumen?',
            html:  '<p>Dokumen akan dikunci dan <strong>tidak dapat diubah</strong> setelah proses ini.</p>',
            icon:  'warning',
            showCancelButton:     true,
            confirmButtonText:    '<i class="fas fa-lock me-1"></i>Ya, Kunci Dokumen',
            cancelButtonText:     'Batal',
            confirmButtonColor:   '#145C4B',
            reverseButtons:       true,
        }),

    // Notifikasi sukses (ringan, auto-close)
    sukses: (pesan = 'Data berhasil disimpan.') =>
        Swal.fire({
            icon:             'success',
            title:            'Berhasil!',
            text:             pesan,
            timer:            2500,
            timerProgressBar: true,
            showConfirmButton: false,
            toast:            true,
            position:         'top-end',
        }),

    // Notifikasi error
    error: (pesan = 'Terjadi kesalahan. Silakan coba lagi.') =>
        Swal.fire({
            icon:  'error',
            title: 'Terjadi Kesalahan',
            text:  pesan,
        }),

    // Notifikasi peringatan klinis (kontraindikasi, alergi)
    peringatanKlinis: (judul, pesan) =>
        Swal.fire({
            icon:              'warning',
            title:             `⚕️ ${judul}`,
            html:              `<p class="text-start">${pesan}</p>`,
            confirmButtonText: 'Saya Mengerti',
            confirmButtonColor: '#E67E22',
            allowOutsideClick: false,
        }),

    // Loading state
    loading: (pesan = 'Memproses data...') =>
        Swal.fire({
            title:             pesan,
            allowOutsideClick: false,
            allowEscapeKey:    false,
            didOpen: () => Swal.showLoading(),
        }),
};
```

---

## 8. HALAMAN-HALAMAN UTAMA

### Dasbor Utama
- **Layout**: 4 stat card atas, 2 kolom (grafik tren + daftar pasien hari ini)
- **Stat Cards**: Kunjungan Hari Ini, Risiko Tinggi, Menunggu Asesmen, Selesai
- **Grafik**: Line chart kunjungan 7 hari terakhir (Chart.js)
- **Tabel**: Manifes pasien hari ini dengan kolom: No. Kunjungan, Nama (tersamar), Risiko, Penanganan, Status, Aksi

### Halaman Daftar Pasien (MPI)
- **Pencarian**: realtime search by nama / nomor RM
- **Filter**: jenis kelamin, usia, kunjungan terakhir, status aktif
- **Tabel**: NRM, Nama, Usia, JK, Kunjungan Terakhir, Diagnosis Utama, Aksi
- **Aksi baris**: Lihat Detail, Buat Kunjungan Baru

### Halaman Detail Pasien
- **Header**: info identitas singkat + foto placeholder + badge risiko terakhir
- **Tabs**: Riwayat Kunjungan | Profil Alergi | Tren Antropometri | Tren Lab
- **Timeline kunjungan**: vertikal, tiap entry menampilkan tanggal, dietisien, status

### Halaman Kunjungan Aktif (PAGT Wizard)
- **Stepper**: Skrining → Asesmen → Diagnosis → Intervensi → Monitoring
- **Auto-save**: setiap perubahan form disimpan sebagai draft
- **Sidebar kecil**: ringkasan data pasien + status tiap tahap

### Kalkulator Kalori (Intervensi)
- **Layout**: 2 kolom — kiri (form input parameter), kanan (preview hasil real-time)
- **Preview kanan**: animasi perhitungan, breakdown KH/Protein/Lemak dalam donut chart
- **Tab menu**: Kebutuhan Kalori | Distribusi Makronutrien | Susun Menu | Preview Dokumen

### Halaman Monitoring
- **Grafik BB**: Line chart BB per kunjungan vs target
- **Grafik Lab**: Multi-line chart HbA1c, albumin, kolesterol
- **Tabel kepatuhan**: % sisa makanan per kunjungan
- **Comparative panel**: nilai saat ini vs target vs kunjungan sebelumnya

### Halaman Laporan (Admin/SpGK)
- **Filter**: periode, tipe laporan, unit
- **Preview inline** + tombol Export PDF dan Excel

---

## 9. ATURAN UX PENTING

### Efisiensi Klinis
- **Tab order** yang logis pada form — urutan sesuai alur isi data
- **Shortcut keyboard**: Enter untuk lanjut field, Ctrl+S untuk simpan draft
- **Auto-fill**: data pasien tidak perlu diisi ulang di setiap tab kunjungan
- **Persistent filter**: filter tabel tersimpan selama sesi aktif
- **Quick access**: dari dasbor bisa langsung klik nama pasien → kunjungan aktif

### Keamanan UX
- **Konfirmasi SweetAlert2** WAJIB sebelum: hapus, kunci dokumen, ubah diagnosis yang sudah divalidasi
- **Disabled state visual yang jelas** untuk field/tombol yang di luar wewenang peran
- **Tooltip penjelasan** pada tombol/field yang dinonaktifkan karena pembatasan peran
- **Badge peran** selalu terlihat di sidebar agar tenaga kesehatan tahu konteks aksesnya
- **Indikator dokumen terkunci**: kartu dengan ikon gembok + warna gelap teal + watermark "TERKUNCI"

### Umpan Balik Visual
- **Loading state** pada setiap operasi async (kalkulasi, simpan, sinkronisasi SATUSEHAT)
- **Real-time kalkulasi** (IMT, total kalori, % pemenuhan) tanpa perlu klik submit
- **Highlight perubahan**: nilai yang berubah dari kunjungan sebelumnya diberi highlight kuning ringan
- **Peringatan alergi**: muncul sebagai overlay merah mencolok (NCPMS_SWAL.peringatanKlinis) — TIDAK bisa diabaikan tanpa klik konfirmasi
- **Indikator simpan**: autosave indicator di pojok kanan atas saat form dalam proses penyimpanan

### Aksesibilitas
- Semua form punya atribut `aria-label` dan `aria-describedby`
- Kontras warna memenuhi WCAG AA (4.5:1 untuk teks kecil, 3:1 untuk teks besar)
- Focus ring terlihat jelas pada semua elemen interaktif (outline: 3px solid var(--color-primary))
- Error message terhubung ke field via `aria-describedby`

### Responsif
- Tabel dengan scroll horizontal di tablet/mobile (tidak dipotong)
- Sidebar collapse ke icon-only di layar kecil
- Form multi-kolom menjadi single kolom di mobile
- Stat card stack vertikal di mobile

---

## 10. TEMPLATE BLADE STANDAR

### Layout Utama (`layouts/app.blade.php`)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NCPMS') — Nutrition Care & Patient Management</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <!-- NCPMS Custom CSS -->
    <link href="{{ asset('css/ncpms.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Wrapper --}}
    <div class="main-wrapper">
        {{-- Topbar --}}
        @include('partials.topbar')

        {{-- Content Area --}}
        <main class="content-area">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- NCPMS Global JS -->
    <script src="{{ asset('js/ncpms.js') }}"></script>

    {{-- Flash SweetAlert2 --}}
    @if(session('swal_success'))
    <script>NCPMS_SWAL.sukses('{{ session("swal_success") }}');</script>
    @endif
    @if(session('swal_error'))
    <script>NCPMS_SWAL.error('{{ session("swal_error") }}');</script>
    @endif

    @stack('scripts')
</body>
</html>
```

### Struktur Page Content Standar
```blade
@extends('layouts.app')
@section('title', 'Nama Halaman')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Judul Halaman</h1>
        <p class="page-subtitle">Deskripsi singkat atau konteks halaman</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('...') }}" class="btn-primary-ncpms">
            <i class="fas fa-plus"></i> Tambah Data
        </a>
    </div>
</div>

<div class="ncpms-card">
    <div class="card-header-custom">
        <h2 class="card-title-custom">
            <span class="card-title-icon"><i class="fas fa-list"></i></span>
            Daftar Data
        </h2>
        {{-- filter/search --}}
    </div>
    {{-- konten --}}
</div>
@endsection
```

---

## 11. VARIABEL CSS GLOBAL (ncpms.css)

```css
:root {
    /* === WARNA === */
    --color-primary:          #1A7A64;
    --color-primary-dark:     #145C4B;
    --color-primary-darker:   #0E3F35;
    --color-primary-light:    #2BA882;
    --color-primary-subtle:   #E8F5F1;
    --color-primary-border:   #B2DDD4;
    --color-accent:           #F4A830;
    --color-accent-dark:      #D4891A;
    --color-accent-light:     #FDE9C0;
    --color-risiko-tinggi:    #D93025;
    --color-risiko-sedang:    #E67E22;
    --color-risiko-rendah:    #27AE60;
    --color-risiko-bg-tinggi: #FDF2F2;
    --color-risiko-bg-sedang: #FEF9EC;
    --color-risiko-bg-rendah: #F0FAF4;
    --color-bg-page:          #F0F4F3;
    --color-bg-card:          #FFFFFF;
    --color-bg-sidebar:       #0E3F35;
    --color-bg-topbar:        #FFFFFF;
    --color-bg-input:         #FFFFFF;
    --color-bg-input-disabled:#F8F9FA;
    --color-bg-table-header:  #E8F5F1;
    --color-bg-row-even:      #FAFFFE;
    --color-bg-row-hover:     #E8F5F1;
    --color-text-primary:     #1C2A27;
    --color-text-secondary:   #4A6560;
    --color-text-muted:       #8AA09A;
    --color-text-inverse:     #FFFFFF;
    --color-text-link:        #1A7A64;
    --color-border:           #D4E6E1;
    --color-border-input:     #C0D8D2;
    --color-border-focus:     #1A7A64;
    --color-divider:          #EAF2EF;
    --color-lab-normal:       #27AE60;
    --color-lab-tinggi:       #D93025;
    --color-lab-rendah:       #2980B9;
    --color-lab-kritis:       #8E44AD;

    /* === TIPOGRAFI === */
    --font-primary:    'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    --font-secondary:  'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    --font-mono:       'JetBrains Mono', 'Courier New', monospace;

    /* === LAYOUT === */
    --sidebar-width:        240px;
    --topbar-height:        60px;
    --content-padding:      24px;
    --card-padding:         24px;
    --card-padding-sm:      16px;
    --card-gap:             16px;
    --border-radius-card:   12px;
    --border-radius-input:  8px;
    --border-radius-badge:  6px;
    --border-radius-btn:    8px;

    /* === SHADOW === */
    --shadow-card:    0 1px 3px rgba(26,122,100,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow-topbar:  0 1px 0 var(--color-border);
    --shadow-btn:     0 4px 12px rgba(26,122,100,0.2);
}

/* Reset & Base */
* { box-sizing: border-box; }
body {
    font-family: var(--font-primary);
    background:  var(--color-bg-page);
    color:       var(--color-text-primary);
    font-size:   0.875rem;
    line-height: 1.5;
}
.main-wrapper {
    margin-left: var(--sidebar-width);
    padding-top: var(--topbar-height);
}
.content-area {
    padding: var(--content-padding);
    min-height: calc(100vh - var(--topbar-height));
}
```

---

## 12. ATURAN DESAIN YANG TIDAK BOLEH DILANGGAR

1. **TIDAK ADA** npm/Vite/Webpack — semua frontend dependency via CDN
2. **SEMUA** alert, konfirmasi, dan notifikasi menggunakan SweetAlert2 (tidak ada `alert()` atau Bootstrap modal untuk konfirmasi)
3. **SEMUA** grafik menggunakan Chart.js dengan konfigurasi warna NCPMS
4. **SEMUA** validasi pesan dalam Bahasa Indonesia
5. **SIDEBAR** selalu menggunakan warna `#0E3F35` (dark teal) — tidak boleh putih/abu
6. **BADGE RISIKO** selalu menggunakan tiga warna semantik (merah/oranye/hijau) — tidak boleh warna lain
7. **DOKUMEN TERKUNCI** harus memiliki indikator visual yang tidak bisa diabaikan (warna gelap + ikon gembok + disabled form)
8. **PERINGATAN ALERGI/KONTRAINDIKASI** harus menggunakan SweetAlert2 dengan `allowOutsideClick: false` — tidak boleh hanya tooltip atau toast
9. **TIDAK ADA** portal/tampilan yang bisa diakses pasien secara langsung
10. **TABEL DATA PASIEN** harus menyembunyikan informasi identitas sensitif di view daftar (tampilkan nomor RM tersamar + nama awal saja, kecuali di halaman detail pasien)
