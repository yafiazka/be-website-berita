# Backend Website Berita (REST API & CMS Dashboard)

Repositori ini berisi implementasi backend untuk portal berita modern yang dibangun menggunakan framework Laravel 12 dan Backpack for Laravel v6 (Tabler Theme). Sistem ini menyediakan antarmuka administrasi konten redaksi serta RESTful API lengkap untuk dikonsumsi oleh aplikasi frontend (Web, Mobile, atau agregator berita).

---

## Gambaran Sistem

Aplikasi ini memisahkan fungsionalitas menjadi dua bagian utama:

1. **Panel Administrasi CMS**: Antarmuka berbasis web untuk tim redaksi yang mencakup penulisan berita, manajemen kategori dan tag, moderasi komentar pembaca, manajemen pengguna, serta visualisasi metrik performa artikel secara real-time.
2. **RESTful API v1**: Endpoint JSON terstruktur untuk melayani publikasi berita, alur kerja editorial, autentikasi berbasis Sanctum, interaksi pembaca (komentar, bookmark, like), serta RSS dan sitemap generator.

---

## Fitur Utama

### 1. Autentikasi dan Otorisasi
- Autentikasi fleksibel mendukung input berupa **Email** ataupun **Username**.
- Integrasi token berbasis Laravel Sanctum untuk akses endpoint API.
- Manajemen hak akses terinci menggunakan `spatie/laravel-permission` yang terbagi ke dalam 4 peran pengguna:
  - **Super Administrator**: Akses menyeluruh ke seluruh modul sistem, hak akses, dan log aktivitas.
  - **Editor**: Meninjau draf, menyunting, mempublikasikan, menjadwalkan artikel, dan memoderasi komentar.
  - **Penulis**: Membuat dan mengelola artikel milik sendiri serta mengajukannya ke meja redaksi.
  - **Pembaca**: Mengakses konten publik, memberi suka (like), menyimpan bacaan (bookmark), dan mengirim komentar.

### 2. Manajemen Konten dan Alur Redaksi
- Workflow penerbitan bertahap: Draft, Peninjauan (In Review), Terbit (Published), Terjadwal (Scheduled), dan Arsip (Archived).
- Hierarki kategori multi-level (kategori induk dan subkategori).
- Pengelompokan berita menggunakan sistem tags dinamis.
- Manajemen meta tag SEO per artikel (judul meta, deskripsi meta, slug kustom, gambar pratinjau).

### 3. Interaksi dan Komentar
- Komentar bertingkat (nested replies) dengan alur moderasi status (Pending, Approved, Spam).
- Fitur like dan bookmark per artikel khusus pengguna terdaftar.
- Fitur langganan buletin berita (newsletter) berbasis verifikasi email.

### 4. Dashboard Analitik Redaksi
- Ringkasan metrik total artikel, akumulasi tayangan pembaca, total interaksi, dan status moderasi komentar.
- Visualisasi grafik area interaktif untuk tren pembaca dan penerbitan 7 hari terakhir.
- Donut chart distribusi artikel per kategori berita.
- Daftar artikel terpopuler (trending) dengan jumlah tayangan tertinggi.

### 5. Dokumentasi API dan Ekspor Postman
- Halaman katalog endpoint API terintegrasi di dalam panel admin.
- Fitur unduh berkas Postman Collection v2.1 (.json) untuk pengujian endpoint secara langsung di aplikasi Postman.
- Dukungan umpan RSS 2.0 (`/rss`) dan XML Sitemap (`/sitemap.xml`).

---

## Stack Teknologi

| Komponen | Spesifikasi | Keterangan |
|---|---|---|
| Backend Framework | PHP 8.3 / Laravel 12 | Engine inti aplikasi dan API |
| Panel CMS | Backpack CRUD 6.8 (Theme Tabler) | Tata letak sidebar vertikal & top header |
| Database | MySQL 8.0 | Container `global-mysql:3306` |
| Cache & Session | Redis | Driver `phpredis` via `global-redis:6379` |
| Container Platform | Podman / Docker | Port host `8086:80` |
| Visualisasi Data | ApexCharts | Grafik tren dan distribusi kategori |

---

## Panduan Instalasi dan Menjalankan Proyek

### 1. Prasyarat
Pastikan environment lokal telah terpasang:
- Podman / Docker
- Podman Compose / Docker Compose

### 2. Kloning Repositori
```bash
git clone https://github.com/yafiazka/be-website-berita.git
cd be-website-berita
```

### 3. Konfigurasi Environment
Salin berkas template environment:
```bash
cp .env.example .env
```

Pastikan variabel koneksi database dan Redis disesuaikan dengan container jaringan Anda:
```env
APP_NAME="Portal Berita"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8086

DB_CONNECTION=mysql
DB_HOST=global-mysql
DB_PORT=3306
DB_DATABASE=be_website_berita
DB_USERNAME=root
DB_PASSWORD=root

REDIS_CLIENT=phpredis
REDIS_HOST=global-redis
REDIS_PORT=6379
```

### 4. Menjalankan Container
Jalankan container menggunakan compose:
```bash
podman compose up -d --build
```
Aplikasi akan dapat diakses pada alamat `http://localhost:8086`.

### 5. Menjalankan Migrasi dan Seeder
Eksekusi migrasi database dan pengisian data dummy awal:
```bash
podman exec -i berita-app php artisan migrate:fresh --seed
```

---

## Akun Pengujian Default

| Peran (Role) | Username | Email | Password |
|---|---|---|---|
| Super Administrator | admin | admin@berita.local | password |
| Senior Editor | editor | editor@berita.local | password |
| Penulis | penulis | penulis@berita.local | password |
| Pembaca | pembaca | pembaca@berita.local | password |

---

## Struktur Routing Utama

- **`/`**: Halaman utama langsung mengarah ke Dashboard CMS Admin.
- **`/api`**: Informasi indeks REST API v1.
- **`/admin/login`**: Formulir login admin panel (mendukung email atau username).
- **`/admin/dashboard`**: Dashboard analitik dan statistik redaksi.
- **`/admin/api-docs`**: Dokumentasi interaktif seluruh endpoint REST API.
- **`/admin/api-docs/download-postman`**: Pengunduhan berkas Postman Collection JSON.
- **`/sitemap.xml`**: Peta situs artikel berita format XML standar search engine.
- **`/rss`**: Umpan berita RSS Feed 2.0.

---

## Ringkasan Endpoint REST API v1

Format respons API standar:
```json
{
  "success": true,
  "message": "Pesan deskripsi proses",
  "data": { ... }
}
```

### Autentikasi (`/api/v1/auth/*`)
- `POST /api/v1/auth/login`: Login pengguna dengan email atau username, menghasilkan Bearer Token.
- `POST /api/v1/auth/register`: Pendaftaran akun pembaca baru.
- `GET /api/v1/auth/me`: Mengambil data profil pengguna yang sedang terotentikasi.
- `POST /api/v1/auth/logout`: Mencabut token autentikasi aktif.

### Artikel Publik (`/api/v1/articles/*`)
- `GET /api/v1/articles`: Mengambil daftar artikel terbit (filter kategori, tag, sorting, paginasi).
- `GET /api/v1/articles/trending`: Mengambil 5 berita dengan pembaca terbanyak.
- `GET /api/v1/articles/breaking`: Mengambil berita berstatus breaking news.
- `GET /api/v1/articles/search?q={kata_kunci}`: Pencarian berita berdasarkan judul atau isi.
- `GET /api/v1/articles/{slug}`: Mengambil detail satu artikel serta menaikkan counter views.
- `POST /api/v1/articles/{slug}/like`: Memberikan atau membatalkan suka pada artikel (Auth).
- `POST /api/v1/articles/{slug}/bookmark`: Menyimpan atau membatalkan bookmark artikel (Auth).

### Redaksi dan Editorial (`/api/v1/editorial/*`)
- `GET /api/v1/editorial/articles`: Mengambil seluruh artikel redaksi (draft, review, published).
- `POST /api/v1/editorial/articles`: Membuat draf artikel baru.
- `POST /api/v1/editorial/articles/{id}/publish`: Mengubah status artikel menjadi terbit.
- `POST /api/v1/editorial/articles/{id}/schedule`: Menjadwalkan tanggal rilis artikel.
- `GET /api/v1/editorial/stats`: Mengambil statistik performa tim redaksi.

---

## Percabangan Git

Pengembangan proyek ini dibagi menjadi dua cabang utama:
- **`production`**: Cabang utama yang berisi kode teruji dan stabil untuk rilis production.
- **`development`**: Cabang kerja aktif untuk penambahan fitur dan perbaikan berkala.

---

## Lisensi

Proyek ini menggunakan lisensi [MIT](LICENSE).
