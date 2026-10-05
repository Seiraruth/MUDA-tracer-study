 # 🎓 Tracer Study Alumni SMK

Sistem Informasi **Tracer Study Alumni SMK** adalah platform web modern berbasis **Laravel 12** dan **Filament v3 Admin Panel** yang dirancang khusus untuk sekolah menengah kejuruan (SMK) dalam melacak indikator keberhasilan lulusan berdasarkan standar Kemendikbudristek: **BMW (Bekerja, Melanjutkan Kuliah, Wirausaha)** serta pengelolaan **Bursa Kerja Khusus (BKK)**.

---

## 🌟 Fitur Utama

- 🔐 **Autentikasi Identity Check Alumni (Tanpa Password):** Validasi aman menggunakan kombinasi **NISN** dan **Tanggal Lahir**.
- 📋 **Kuesioner Dinamis (Conditional Form):** Form kuesioner interaktif yang tampilannya berubah secara otomatis sesuai status utama yang dipilih alumni (*Bekerja*, *Melanjutkan Kuliah*, *Berwirausaha*, atau *Mencari Kerja*).
- 💼 **Bursa Kerja Khusus (BKK / Lowongan Kerja):** Portal publik bagi alumni untuk melihat dan melamar lowongan kerja mitra sekolah.
- 📊 **Filament Admin Panel v3:** Portal khusus pengelola BKK & sekolah dilengkapi dengan:
  - Widget Statistik Realtime (*Total Alumni*, *% Bekerja*, *% Kuliah*, *% Wirausaha*).
  - Grafik Distribusi BMW Interaktif (*Doughnut Chart*).
  - Manajemen Data Alumni, Kuesioner Tracer, dan Lowongan Kerja BKK.
- 🎨 **Modern Dark Glassmorphism UI:** Tampilan frontend memukau dengan Tailwind CSS, tipografi *Outfit* & *Plus Jakarta Sans*, serta mikro-animasi.

---

## 💻 Persyaratan Sistem (Prerequisites)

Pastikan perangkat Anda sudah terinstall:
- **PHP** >= 8.2 (Pastikan ekstensi `pdo_sqlite`, `sqlite3`, dan `zip` aktif di `php.ini`).
- **Composer** >= 2.x
- **Node.js** >= 18.x & NPM (Opsional untuk build assets)
- **Web Browser** modern (Chrome, Edge, Firefox, Safari).

---

## 🛠️ Langkah-Langkah Menjalankan Website (Step-by-Step)

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di lingkungan lokal:

### 1. Buka Terminal & Masuk ke Direktori Proyek
```bash
cd /path/to/TRACER-elrahma
```

### 2. Salin File Konfigurasi Environment (`.env`)
Jika file `.env` belum ada, buat salinan dari `.env.example`:
```bash
cp .env.example .env
```

### 3. Install Dependensi Composer
Jalankan perintah berikut untuk menginstall dependensi PHP:
```bash
composer install --ignore-platform-reqs
```

### 3.5 (Opsional) Install Dependensi NPM & Build Assets
*Catatan: Aplikasi ini dapat langsung berjalan tanpa NPM karena assets Admin Panel (Filament) sudah ter-compile di folder `public/`, dan frontend menggunakan Tailwind CDN. Namun jika Anda ingin mengembangkan atau meng-compile aset Vite tersendiri:*
```bash
npm install
npm run dev   # Untuk mode development
# ATAU
npm run build # Untuk mode produksi
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Opsi Konfigurasi Database (SQLite atau MySQL)

Aplikasi ini mendukung 2 opsi driver database:

#### 🟢 Opsi A: SQLite (Default & Paling Praktis - Tanpa Server Database Tambahan)
Pastikan file `database/database.sqlite` sudah tersedia:

- **Windows (PowerShell):**
  ```powershell
  if (-not (Test-Path database/database.sqlite)) { New-Item -ItemType File database/database.sqlite }
  ```
- **Linux / macOS / Git Bash:**
  ```bash
  touch database/database.sqlite
  ```

#### 🟡 Opsi B: MySQL / MariaDB (Laragon / phpMyAdmin)
Jika Anda ingin menggunakan server MySQL (seperti di Laragon):
1. Buat database baru bernama `tracer_smk` di MySQL / phpMyAdmin / HeidiSQL.
2. Buka file `.env` dan ganti konfigurasi database menjadi:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tracer_smk
   DB_USERNAME=root
   DB_PASSWORD=
   ```

#### 🚀 Jalankan Migration & Seeder
Setelah memilih salah satu driver di atas, jalankan perintah berikut untuk membuat tabel & mengisi data demo:
```bash
php artisan migrate:fresh --seed
```

### 6. Jalankan Server lokal Laravel
```bash
php artisan serve
```
Server akan berjalan di `http://127.0.0.1:8000`.

---

## 🔑 Akun & Data Contoh untuk Pengujian (Demo Data)

### A. Portal Admin BKK (Filament Admin Panel)
- **URL Admin:** [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)
- **Email:** `admin@tracer.smk.sch.id`
- **Password:** `password`

---

### B. Portal Alumni (Identity Check Login)
- **URL Alumni:** [http://127.0.0.1:8000/alumni/login](http://127.0.0.1:8000/alumni/login)
- Gunakan salah satu sampel data NISN + Tanggal Lahir di bawah ini untuk melakukan uji coba verifikasi login alumni:

| Nama Alumni | NISN | Tanggal Lahir | Jurusan | Status Demo |
| :--- | :--- | :--- | :--- | :--- |
| **Budi Santoso** | `0051234501` | `2005-04-12` | Teknik Komputer & Jaringan (TKJ) | Bekerja (PT Telkom) |
| **Siti Nurhaliza** | `0051234502` | `2005-08-20` | Rekayasa Perangkat Lunak (RPL) | Bekerja (Web Dev) |
| **Rian Hidayat** | `0051234503` | `2005-11-03` | Rekayasa Perangkat Lunak (RPL) | Kuliah (UGM TI) |
| **Dewi Anggraini** | `0051234504` | `2005-02-15` | Akuntansi & Keuangan (AKL) | Wirausaha (Catering) |
| **Agus Setiawan** | `0051234505` | `2005-06-25` | Teknik Kendaraan Ringan (TKR) | Bekerja (Mekanik AHM) |
| **Lestari Putri** | `0051234506` | `2005-09-10` | Multimedia (MM) | Mencari Kerja |

---

## 🧪 Menjalankan Automated Unit & Feature Test

Untuk memastikan seluruh fungsi rute, autentikasi NISN, penyimpanan kuesioner, dan halaman loker berjalan tanpa masalah, jalankan pengujian berikut:

```bash
php artisan test
```

Hasil test suite:
```
  PASS  Tests\Unit\ExampleTest
  ✓ that true is true

  PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response

  PASS  Tests\Feature\TracerStudyTest
  ✓ homepage loads successfully
  ✓ alumni login validation
  ✓ alumni can submit kuesioner
  ✓ loker page loads

  Tests: 6 passed (14 assertions)
```

---

## 📂 Struktur Direktori Proyek

```
TRACER-elrahma/
├── app/
│   ├── Filament/               # Admin Panel Filament (Resources & Widgets)
│   │   ├── Resources/          # AlumniResource, TracerResponseResource, JobPostingResource
│   │   └── Widgets/            # StatsOverview, BmwChartWidget
│   ├── Http/Controllers/       # TracerController (Frontend Alumni & Loker)
│   └── Models/                 # Eloquent Models (Alumni, TracerResponse, JobPosting)
├── database/
│   ├── migrations/             # Tabel Alumni, Tracer Responses, Job Postings
│   └── seeders/                # DatabaseSeeder (Admin, Alumni Demo, Loker)
├── resources/views/
│   ├── layouts/app.blade.php   # Layout Utama Frontend (Tailwind + Icons)
│   ├── welcome.blade.php       # Homepage Hero & Statistik Realtime
│   ├── alumni/                 # Views Login, Kuesioner Dinamis, Sukses, Loker
│   └── ...
└── routes/web.php              # Rute aplikasi web
```
