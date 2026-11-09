# Aplikasi HR Internal (Practical Test IT Support)

Aplikasi web untuk mengelola data karyawan beberapa brand F&B.
Dibuat dengan **Laravel 13**, data disimpan di **MySQL**. Semua data adalah **dummy** (nama & brand fiktif).

## Fitur (sesuai brief)

| Brief | Lokasi di aplikasi |
|---|---|
| Dashboard interaktif: total, aktif/nonaktif, per brand/divisi/lokasi (kartu & grafik bisa diklik) | menu **Dashboard** |
| Tambah, lihat daftar/detail, ubah, ganti status aktif/nonaktif | menu **Karyawan** |
| Cari nama/ID, filter brand/divisi/lokasi/status | form di atas tabel **Karyawan** |
| Rekap per brand, daftar aktif/nonaktif, ekspor CSV & PDF | menu **Laporan** |
| Validasi (ID unik, wajib diisi, format, pesan error) | form tambah/ubah karyawan |
| **Kelola data master** brand / divisi / lokasi / jabatan (tambah, ubah, hapus) | menu **Brand**, **Divisi**, **Lokasi**, **Jabatan** |

Karyawan **tidak dihapus permanen**; yang dipakai adalah status **Nonaktif** supaya riwayat tetap ada.
Data master yang **masih dipakai karyawan tidak bisa dihapus** (dilindungi dengan pesan), untuk mencegah data yatim.

## Login

Aplikasi ini **dilindungi login** (semua halaman wajib masuk). Akun demo dibuat oleh seeder:

- **Email:** `admin@jojo.test`
- **Password:** `password`

Setelah login, **nama user** dan tombol **Logout** tampil di bilah atas. Logout mengakhiri sesi dan kembali ke halaman login.

## Teknologi

- PHP 8.3 dan **Laravel 13 (MVC)**
- Eloquent ORM + migration + seeder
- Blade (server-side rendering, CSS inline — tanpa npm/Vite)
- **barryvdh/laravel-dompdf** untuk ekspor laporan ke PDF
- **MySQL** (database)

## Struktur aplikasi

```
app/Models/Employee.php                       model karyawan + relasi brand/divisi/lokasi/jabatan + scope filter
app/Models/{Brand,Division,Location,Position}.php   data master (hasMany Employee)
app/Http/Requests/EmployeeRequest.php         validasi karyawan (Bahasa Indonesia)
app/Http/Requests/{Brand,Division,Location,Position}Request.php   validasi data master
app/Http/Controllers/DashboardController.php  angka dashboard
app/Http/Controllers/EmployeeController.php   tambah, daftar, detail, ubah, ganti status
app/Http/Controllers/{Brand,Division,Location,Position}Controller.php   CRUD data master
app/Http/Controllers/ReportController.php     laporan + ekspor CSV & PDF
app/Providers/AppServiceProvider.php          paksa HTTPS production + pagination kustom
database/migrations/2026_01_01_00000{0,1,2,3}_create_{brands,divisions,locations,positions}_table.php
database/migrations/2026_01_01_000004_create_employees_table.php  tabel karyawan + FK
database/seeders/DatabaseSeeder.php           data master + 33 karyawan dummy
routes/web.php                                daftar URL
resources/views/                              layout, dashboard, karyawan, laporan, master
```

## Cara kerja penyimpanan data

1. Data master (brand, divisi, lokasi, jabatan) disimpan di tabel tersendiri. Karyawan menyimpan **ID** (`brand_id`, `division_id`, `location_id`, `position_id`) dengan **foreign key**, bukan teks biasa.
2. Karena memakai relasi, **mengubah nama brand/divisi/lokasi langsung mengubah tampilan** di semua karyawan & laporan.
3. Saat form karyawan disimpan, `EmployeeRequest` memvalidasi dulu, lalu Eloquent menjalankan `INSERT`/`UPDATE`.
4. Data ada di MySQL sehingga tetap ada setelah reload/restart. Dashboard & laporan menghitung ulang dengan `COUNT` + relasi tiap dibuka.
5. ID karyawan unik dijaga dua lapis: aturan `unique` di validasi + `UNIQUE INDEX` di database.
6. Menghapus brand/divisi/lokasi yang **masih dipakai** karyawan ditolak aplikasi (dicek jumlah karyawan) plus FK `restrict`.

## Instalasi lokal (Laragon / MySQL)

Prasyarat: PHP 8.3+, Composer, ekstensi `pdo_mysql`, `mbstring`, `openssl`, `zip`; MySQL berjalan.

```bash
cd c:\laragon\www\hr-app
composer install
copy .env.example .env          # lalu isi koneksi MySQL
```

`.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hr_app
DB_USERNAME=root
DB_PASSWORD=
```

Buat database lalu jalankan:
```bash
mysql -uroot -e "CREATE DATABASE IF NOT EXISTS hr_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Buka **http://127.0.0.1:8000**.

> Reset data: `php artisan migrate:fresh --seed` → 3 brand (jojo juice, jly, guulak), 8 divisi, 5 lokasi (alamat di Pontianak & Singkawang, Kalbar), 13 jabatan, 33 karyawan dummy.

## Petunjuk penggunaan

> Semua halaman wajib login. Masuk dengan **admin@jojo.test** / **password** (lihat bagian **Login**).

1. **Dashboard interaktif**: kartu Total/Aktif/Nonaktif/Brand dapat diklik untuk membuka daftar terfilter; grafik donut status + batang per brand/divisi/lokasi juga bisa diklik. Menu samping responsif (tombol ☰ di tablet & HP).
2. **Tambah karyawan**: Karyawan → Tambah Karyawan → pilih brand/divisi/lokasi/jabatan → Simpan.
3. **Ubah / ganti status**: tombol **Ubah** / **Nonaktifkan / Aktifkan**. Saat dinonaktifkan, waktu nonaktif otomatis tercatat dan tampil di halaman **Lihat** (baris **Dinonaktifkan Pada**).
4. **Cari & filter**: ketik nama/ID, pilih brand/divisi/lokasi/status, klik **Terapkan**.
5. **Data master**: buka menu **Master Data** (dropdown) → **Brand / Divisi / Lokasi / Jabatan** untuk menambah, mengubah, menghapus. Item yang dipakai karyawan tidak bisa dihapus.
6. **Laporan**: rekap per brand + daftar. Tombol **Ekspor CSV / Excel** (BOM UTF-8, siap Excel) dan **Ekspor PDF** (header **JOJO GROUP MANAGEMENT**, rincian **detail per karyawan** seperti halaman Lihat) mengikuti filter aktif.

## Aturan validasi

- **ID karyawan**: wajib, unik, format `KS-001` (2-5 huruf besar + hubung + 3-6 angka). Huruf kecil otomatis jadi besar.
- **Nama lengkap**: wajib, 3-100 karakter, hanya huruf (dengan aksen), spasi, titik, apostrof, hubung.
- **Brand / divisi / lokasi / jabatan / status**: wajib, harus mengacu ke data master yang tersedia.
- **Jabatan**: wajib dipilih dari data master jabatan (dikelola di menu Jabatan).
- **Tanggal bergabung**: wajib, format `YYYY-MM-DD`, tidak boleh di masa depan.
- **Nama brand / divisi / lokasi / jabatan**: wajib, maks 100 karakter, tidak boleh duplikat.

## Demo & Checklist Screenshot (untuk pengumpulan)

Aplikasi siap didemokan di `http://127.0.0.1:8000` (login: **admin@jojo.test** / **password**). Berikut daftar screenshot fitur utama dan alur demo ±5 menit. Sertakan juga screenshot **halaman Login**.

**Checklist screenshot fitur utama:**
1. **Dashboard HR** — kartu total/aktif/nonaktif/brand, donut status, grafik batang per brand/divisi/lokasi.
2. **Daftar Karyawan** — tabel + form pencarian & filter.
3. **Hasil pencarian** (mis. ketik `KS-001`) dan **hasil filter** (brand/divisi/status).
4. **Form Tambah Karyawan** (dropdown brand/divisi/lokasi/jabatan).
5. **Error validasi** — coba ID duplikat (mis. `KS-001`) dan kolom kosong; tampilkan pesan error.
6. **Detail Karyawan**.
7. **Ubah data** & **ganti status** (Aktif/Nonaktif).
8. **Laporan** — rekap per brand, ringkasan aktif/nonaktif, daftar karyawan.
9. **File ekspor** — CSV (dibuka di Excel) dan PDF (header **JOJO GROUP MANAGEMENT**).
10. **Data master** — layar Brand / Divisi / Lokasi / Jabatan (tambah/ubah/hapus).

**Alur demo (±5 menit):**
1. Login (admin@jojo.test / password), lalu buka Dashboard → tunjukkan angka awal (total, aktif, nonaktif, per brand/divisi/lokasi).
2. Tambah karyawan baru (pilih jabatan dari dropdown) → lihat dashboard & laporan ikut bertambah.
3. Coba tambah dengan ID yang sama + kolom kosong → tunjukkan pesan error validasi.
4. Nonaktifkan seorang karyawan → angka aktif/nonaktif berubah di dashboard.
5. Cari (nama/ID) dan filter (brand/divisi/status).
6. Buka Laporan → ekspor **CSV** dan **PDF** → buka filenya.
7. Refresh browser / restart `php artisan serve` → tunjukkan data tetap tersimpan.
8. (Opsional) Tunjukkan pengelolaan data master Brand/Divisi/Lokasi/Jabatan dan tampilan responsif (tablet/HP, tombol ☰).

> Catatan: file screenshot dan rekaman demo penggunaan disiapkan dari aplikasi yang berjalan di atas sesuai checklist ini.

## Deploy ke Railway (online)

Konfigurasi deploy ada di `railway.json` (pre-deploy `migrate --force --seed`, healthcheck `/up`, restart policy `ON_FAILURE`).

**Langkah:**

1. Railway → **New Project → Deploy from GitHub repo** → pilih repo `hr-app`.
2. **+ New → Database → MySQL** (satu project dengan service aplikasi).
3. Buka service aplikasi → **Variables → Raw Editor**, tempel:

```
APP_NAME="HR App"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...        # hasil: php artisan key:generate --show
APP_URL=https://domain-railway-kamu
APP_LOCALE=id

LOG_CHANNEL=stderr
LOG_STDERR_FORMATTER=\Monolog\Formatter\JsonFormatter

DB_CONNECTION=mysql
DB_URL=${{MySQL.MYSQL_URL}}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

RAILPACK_PHP_EXTENSIONS=pdo_mysql,mbstring,dom,gd,zip
RAILPACK_SKIP_MIGRATIONS=true
```

4. **Settings → Networking → Generate Domain**, lalu samakan `APP_URL` dengan domain tersebut dan redeploy.
5. Setelah deploy sukses, login dengan akun seeder: **admin@jojo.test** / **password**.

**Catatan Railway:**

- `railway.json` menjalankan `php artisan migrate --force --seed` sebelum container start, jadi tabel + data dummy dibuat otomatis.
- `DatabaseSeeder` hanya mengisi tabel yang masih kosong, jadi **redeploy tidak menggandakan data**.
- `RAILPACK_SKIP_MIGRATIONS=true` mencegah migrasi jalan dua kali (Railpack/FrankenPHP menjalankan migrasi otomatis saat start).
- Filesystem Railway bersifat sementara → log dikirim ke stdout lewat `LOG_CHANNEL=stderr`.
- Alternatif tanpa GitHub: `railway login` → `railway init` → `railway up` (butuh Railway CLI terpasang).
