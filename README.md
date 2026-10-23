# Tracer Study Alumni SMK Muhammadiyah 2 Cikampek

Aplikasi tracer study untuk mendata perkembangan alumni, mengelola kuesioner, dan menampilkan ringkasan indikator BMW: Bekerja, Melanjutkan Kuliah, dan Wirausaha. Aplikasi menggunakan Laravel 12, Vue 3, Inertia.js, dan Vite.

## Fitur

- Login alumni menggunakan NISN dan tanggal lahir, tanpa kata sandi.
- Kuesioner tracer study dengan pertanyaan sesuai status utama alumni.
- Halaman publik: beranda, informasi lowongan BKK, dan statistik tracer study.
- Panel admin berbasis Vue dan Inertia untuk mengelola data alumni, jawaban tracer, lowongan BKK, dan mitra DUDI.
- Daftar saran alumni untuk ditinjau admin.
- Dashboard admin dengan ringkasan statistik dan daftar alumni yang belum mengisi tracer tahun berjalan.

## Persyaratan

- PHP 8.2 atau lebih baru, dengan ekstensi PHP yang diperlukan Laravel dan driver database pilihan Anda.
- Composer 2 atau lebih baru.
- Node.js dan npm.
- SQLite (bawaan paling sederhana) atau MySQL/MariaDB.

## Menjalankan aplikasi secara lokal

Perintah berikut menggunakan SQLite, yang merupakan konfigurasi database bawaan di `.env.example`.

### 1. Buka terminal di direktori proyek

Sesuaikan lokasi direktori jika proyek berada di tempat lain:

```bash
cd E:/laragon/www/MUDA-tracer-study
```

### 2. Pasang dependensi PHP dan JavaScript

```bash
composer install
npm install
```

### 3. Siapkan file environment

Buat `.env` dari template jika file tersebut belum tersedia.

PowerShell:

```powershell
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
```

Git Bash, Linux, atau macOS:

```bash
test -f .env || cp .env.example .env
```

### 4. Buat application key

```bash
php artisan key:generate
```

### 5. Siapkan database

Untuk SQLite, pastikan file database tersedia. Perintah ini aman dijalankan jika file sudah ada.

PowerShell:

```powershell
if (-not (Test-Path database/database.sqlite)) { New-Item -ItemType File database/database.sqlite }
```

Git Bash, Linux, atau macOS:

```bash
test -f database/database.sqlite || touch database/database.sqlite
```

Pastikan `.env` memakai konfigurasi SQLite berikut:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Jika menggunakan MySQL/MariaDB, buat database terlebih dahulu lalu atur kredensial koneksi di `.env`, misalnya:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tracer_smk
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan migrasi

```bash
php artisan migrate
```

Untuk membuat akun admin dan mengisi data contoh, jalankan seeder:

```bash
php artisan db:seed
```

Seeder memakai `updateOrCreate` untuk akun admin dan data alumni contoh. Seeder lowongan membuat entri baru saat dijalankan, jadi hindari menjalankannya berulang kali pada database yang sama jika tidak ingin lowongan contoh terduplikasi.

### 7. Jalankan Laravel dan Vite

```bash
npm start
```

Perintah tersebut menjalankan server Laravel dan Vite bersamaan. Buka aplikasi di [http://127.0.0.1:8000](http://127.0.0.1:8000). Biarkan terminal tetap berjalan selama aplikasi digunakan.

Jika hanya ingin membuat aset produksi, jalankan:

```bash
npm run build
```

## URL aplikasi

| Halaman | URL lokal |
| --- | --- |
| Beranda | `http://127.0.0.1:8000/` |
| Login alumni | `http://127.0.0.1:8000/alumni/login` |
| Lowongan BKK | `http://127.0.0.1:8000/loker` |
| Statistik publik | `http://127.0.0.1:8000/statistik` |
| Login admin | `http://127.0.0.1:8000/admin/login` |

Setelah login admin, halaman pengelolaan dapat dibuka melalui navigasi panel admin.

## Akun lokal untuk pengembangan

Seeder membuat akun admin berikut:

- Email: `admin@tracer.smk.sch.id`
- Kata sandi: `password`

Seeder juga memasukkan data alumni contoh. Gunakan NISN dan tanggal lahir pada `database/seeders/DatabaseSeeder.php` untuk mencoba login alumni.

**Peringatan:** kredensial tersebut hanya untuk pengembangan lokal. Ganti kata sandi dan kelola akun secara aman sebelum aplikasi digunakan di lingkungan lain.

## Pengujian

Jalankan test Laravel dengan:

```bash
php artisan test
```

## Struktur proyek

```text
app/Http/Controllers/      Controller halaman publik dan admin
app/Models/                Model Eloquent
database/migrations/       Struktur tabel
database/seeders/          Akun admin dan data contoh
resources/js/Pages/Admin/  Halaman admin Vue
resources/views/           Tampilan publik Blade
routes/web.php             Rute aplikasi
```
