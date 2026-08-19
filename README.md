# 📰 Backend Website Berita (REST API & CMS Dashboard)

Backend portal berita modern berbasis **Laravel 12**, **Backpack CMS v6 (Tabler Theme)**, dan **Podman/Docker**, dirancang dengan arsitektur RESTful API yang lengkap, aman, dan siap pakai untuk aplikasi frontend (Web/Mobile) serta tim redaksi media berita.

---

## 🌟 Fitur Utama

- 🔐 **Autentikasi Fleksibel**: Mendukung login menggunakan **Email** maupun **Username** (Laravel Sanctum Bearer Token).
- 👥 **Role & Permission Granular**: Manajemen hak akses berbasis `spatie/laravel-permission` dengan 4 peran utama:
  - **Super Administrator**: Akses penuh sistem, kelola pengguna, permission, dan activity logs.
  - **Editor**: Moderasi komentar, peninjauan artikel, penerbitan (*publish*), dan penjadwalan berita (*schedule*).
  - **Penulis (Jurnalis)**: Membuat draf artikel, mengunggah media, dan mengajukan draf untuk ditinjau.
  - **Pembaca**: Membaca artikel, memberi *like*, menyimpan *bookmark*, dan mengirim komentar.
- 📝 **Alur Kerja Redaksi (Workflow)**: Status artikel bertahap (*Draft* ➔ *In Review* ➔ *Published* / *Scheduled* ➔ *Archived*).
- 🏷️ **Hierarki Kategori & Tags**: Kategori bersarang (*parent-child*) dan sistem penandaan (*tagging*) dinamis.
- 💬 **Interaksi & Moderasi Komentar**: Komentar bertingkat (*nested replies*) dengan sistem moderasi (*Pending*, *Approved*, *Spam*).
- 📊 **Dashboard Analitik Interaktif**: Dilengkapi kalkulasi metrik *real-time* dan grafik tren (*ApexCharts*) aktivitas pembaca mingguan & distribusi kategori.
- ⚡ **Dokumentasi REST API & Postman**: Halaman katalog API terintegrasi di dalam panel admin dilengkapi fitur unduh **Postman Collection (.json)** sekali klik.
- 📡 **Feeds & SEO Ready**: Dukungan penuh RSS Feed 2.0 (`/rss`) dan XML Sitemap (`/sitemap.xml`).

---

## 🛠️ Stack Teknologi

| Komponen | Teknologi | Keterangan |
|---|---|---|
| **Framework** | PHP 8.3 / Laravel 12 | RESTful API & Backend Engine |
| **Admin CMS Panel** | Backpack for Laravel 6.8 | Tema Tabler (*Vertical Sidebar Layout*) |
| **Database** | MySQL 8.0 | Container `global-mysql:3306` (`be_website_berita`) |
| **Cache & Queue** | Redis | Container `global-redis:6379` via ekstensi `phpredis` |
| **Container Engine** | Podman / Podman Compose | Port mapping `8086:80` pada jaringan `global-db-net` |
| **Visualisasi Data** | ApexCharts | Grafik tren pembaca dan distribusi kategori |

---

## 🚀 Panduan Instalasi & Menjalankan Proyek

### 1. Prasyarat Sistem
Pastikan Anda telah memasang:
- [Podman](https://podman.io/) atau [Docker](https://www.docker.com/)
- Podman Compose / Docker Compose

### 2. Clone Repository
```bash
git clone https://github.com/yafiazka/be-website-berita.git
cd be-website-berita
```

### 3. Konfigurasi Environment (`.env`)
Salin berkas contoh environment dan sesuaikan kredensial database/Redis Anda:
```bash
cp .env.example .env
```
Pastikan pengaturan koneksi database mengarah ke service database Anda:
```env
APP_NAME="Portal Berita"
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

### 4. Build & Jalankan Container
Jalankan container menggunakan Podman Compose:
```bash
podman compose up -d --build
```
Aplikasi akan aktif dan dapat diakses melalui port host **`http://localhost:8086`**.

### 5. Jalankan Migrasi & Database Seeder
Eksekusi migrasi tabel dan data awal di dalam container:
```bash
podman exec -i berita-app php artisan migrate:fresh --seed
```

---

## 🔑 Kredensial Login Default

Gunakan kredensial berikut untuk login ke **Dashboard Admin** (`http://localhost:8086/admin/login`) atau melalui endpoint API:

| Peran (Role) | Username | Email | Password |
|---|---|---|---|
| **Super Admin** | `admin` | `admin@berita.local` | `password` |
| **Senior Editor** | `editor` | `editor@berita.local` | `password` |
| **Penulis** | `penulis` | `penulis@berita.local` | `password` |
| **Pembaca** | `pembaca` | `pembaca@berita.local` | `password` |

> 💡 *Catatan: Form login menerima input berupa **Email** ataupun **Username**.*

---

## 🧭 Navigasi Panel Admin & REST API

- **Dashboard CMS Redaksi**: `http://localhost:8086/admin`
- **Form Login Admin**: `http://localhost:8086/admin/login`
- **Katalog & Dokumentasi API**: `http://localhost:8086/admin/api-docs`
- **Unduh Postman Collection (.json)**: `http://localhost:8086/admin/api-docs/download-postman`
- **System Health Check**: `http://localhost:8086/api/v1/health`
- **RSS Feed Berita**: `http://localhost:8086/rss`
- **XML Sitemap**: `http://localhost:8086/sitemap.xml`

---

## 📚 Ringkasan Endpoint REST API v1

Seluruh respons API menggunakan format JSON standar:
```json
{
  "success": true,
  "message": "Pesan status",
  "data": { ... }
}
```

### 1. Autentikasi (`/api/v1/auth/*`)
- `POST /api/v1/auth/login` — Login via email atau username, mengembalikan Bearer Token.
- `POST /api/v1/auth/register` — Pendaftaran akun pembaca baru.
- `GET /api/v1/auth/me` — Profil user saat ini beserta roles & permissions (Auth).
- `POST /api/v1/auth/logout` — Mencabut token login aktif (Auth).

### 2. Artikel Publik (`/api/v1/articles/*`)
- `GET /api/v1/articles` — Daftar artikel terbit (filter kategori, tag, sortir, paginasi).
- `GET /api/v1/articles/trending` — Top 5 berita dengan pembaca terbanyak.
- `GET /api/v1/articles/breaking` — Berita utama mendesak (*breaking news*).
- `GET /api/v1/articles/search?q={keyword}` — Pencarian artikel (*full-text*).
- `GET /api/v1/articles/{slug}` — Detail lengkap artikel (otomatis menghitung views).
- `POST /api/v1/articles/{slug}/like` — Beri / batalkan Like (Auth).
- `POST /api/v1/articles/{slug}/bookmark` — Simpan ke Bookmark pribadi (Auth).

### 3. Redaksi & Editorial (`/api/v1/editorial/*`)
- `GET /api/v1/editorial/articles` — Manajemen seluruh status artikel redaksi.
- `POST /api/v1/editorial/articles` — Buat artikel baru (Draft/Review).
- `POST /api/v1/editorial/articles/{id}/publish` — Publikasikan artikel (Editor/Admin).
- `POST /api/v1/editorial/articles/{id}/schedule` — Jadwalkan publikasi artikel.
- `GET /api/v1/editorial/stats` — Agregasi statistik performa redaksi.

---

## 🌳 Struktur Percabangan Git

Repository ini menerapkan dua branch utama:
- **`production`**: Branch rilis stabil yang siap untuk *deployment* server *production*.
- **`development`**: Branch pengembangan aktif untuk integrasi fitur baru dan perbaikan *bug*.

---

## 📄 Lisensi
Proyek ini dilisensikan di bawah [MIT License](LICENSE).
