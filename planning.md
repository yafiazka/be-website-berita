# Planning Backend — Website Berita (Laravel 13 API)

> Dokumen ini fokus khusus pada backend (API dan CRUD). Frontend Next.js akan dibuatkan planning terpisah.

---

## 1. Tujuan & Prinsip

- Backend dibangun **API** dan Dashboard CMS untuk admin menggunakan **Backpack\NewsCRUD**.
- Semua response format **JSON standar** (konsisten via API Resource).
- Versioning API sejak awal: `/api/v1/...`.
- Autentikasi: **Laravel Sanctum** (cookie-based untuk SPA Next.js).
- Otorisasi: **Policy + Role/Permission** (Spatie).
- Setiap endpoint tervalidasi (Form Request), terdokumentasi, dan rate-limited.

---

## 2. Tech Stack Backend

| Kebutuhan | Tools/Package |
|---|---|
| Framework | Laravel 13 |
| Auth | `laravel/sanctum` |
| Role & Permission | `spatie/laravel-permission` |
| Audit Log | `spatie/laravel-activitylog` |
| Media/Upload | `spatie/laravel-medialibrary` + `intervention/image` |
| Search | `laravel/scout` + Meilisearch |
| Queue | Laravel Queue + Horizon (untuk job berat: resize image, kirim email) |
| Cache | Redis |
| Testing | PHPUnit / Pest |
| Dokumentasi API | `dedoc/scramble` atau `knuckleswtf/scribe` (auto-generate dari code) |
| Database | MySQL/PostgreSQL |
| Monitoring | Laravel Telescope (dev), Sentry (prod) |
|Penambahan template dari backpack/newscrud|
|In your terminal, run:
composer require backpack/newscrud
Publish the migration:
php artisan vendor:publish --provider="Backpack\NewsCRUD\NewsCRUDServiceProvider"
Run the migration to have the database table we need:
php artisan migrate|

---

## 3. Roadmap Backend (Bertahap)

### **Sprint 0 — Setup Project (2-3 hari)**
- [ ] Install Laravel 13, setup `.env`, koneksi database.
- [ ] Install & konfigurasi Sanctum (`stateful` domain untuk SPA).
- [ ] Install Spatie Permission, Activity Log, Media Library.
- [ ] Setup struktur folder API (Controllers/Api/V1, Requests, Resources, Services, Policies).
- [ ] Setup `config/cors.php` — whitelist domain frontend saja.
- [ ] Setup base response helper (format sukses/error konsisten).
- [ ] Setup Telescope (local only) & error handler global.
- [ ] Setup Git, branching strategy (main, develop, feature/*).
- [ ] Setup Docker/Sail (opsional, untuk konsistensi environment tim).

### **Sprint 1 — Database & Model Inti (3-5 hari)**
- [ ] Rancang ERD (lihat bagian 5).
- [ ] Migration: `users`, `roles`, `permissions`, `categories`, `tags`, `articles`, `article_tag`, `comments`, `media`.
- [ ] Model + relasi Eloquent lengkap.
- [ ] Seeder & Factory untuk data dummy (testing).
- [ ] Setup Policy dasar per model.

### **Sprint 2 — Autentikasi & Otorisasi (3-4 hari)**
- [ ] Endpoint register/login/logout (admin & staff redaksi).
- [ ] Endpoint `me` (get current user info + role/permission).
- [ ] Middleware role-based access per endpoint.
- [ ] Rate limiting khusus endpoint auth (anti brute-force).
- [ ] Forgot password / reset password flow.
- [ ] (Opsional) Login pembaca publik (untuk komentar/bookmark).

### **Sprint 3 — Modul Artikel & Taksonomi (5-7 hari)**
- [ ] CRUD Kategori (dengan hierarki parent-child opsional).
- [ ] CRUD Tag.
- [ ] CRUD Artikel: create/update draft, submit review, publish, archive, schedule publish.
- [ ] Slug generator otomatis + validasi unique.
- [ ] SEO meta fields (meta title, meta description, OG image).
- [ ] Upload & manajemen media (thumbnail, gambar konten).
- [ ] Endpoint publik: list artikel, detail artikel, artikel by kategori/tag.
- [ ] Endpoint search artikel (Scout + Meilisearch).
- [ ] View counter artikel (dengan anti-spam — misal debounce per IP/session).

### **Sprint 4 — Interaksi Publik (4-5 hari)**
- [ ] CRUD Komentar (nested/reply opsional).
- [ ] Moderasi komentar (approve/reject/spam).
- [ ] Like/bookmark artikel.
- [ ] Newsletter subscription (endpoint subscribe + validasi email).
- [ ] Rate limiting untuk endpoint publik interaktif (cegah spam comment/like).

### **Sprint 5 — Dashboard & Manajemen (4-5 hari)**
- [ ] Endpoint manajemen user (CRUD, assign role).
- [ ] Endpoint statistik dashboard (jumlah artikel, views, komentar terbaru, dsb).
- [ ] Endpoint manajemen iklan/banner (opsional).
- [ ] Endpoint audit log (siapa mengubah apa, kapan).
- [ ] Export data (opsional: export artikel ke CSV/Excel).

### **Sprint 6 — Keamanan & Hardening (3-5 hari)**
- [ ] Review seluruh endpoint: pastikan Form Request + Policy diterapkan.
- [ ] Setup security headers (CSP, HSTS, X-Frame-Options, dst).
- [ ] Sanitasi HTML dari rich text editor (cegah stored XSS).
- [ ] Validasi MIME type asli untuk file upload.
- [ ] `composer audit` + update dependency rawan.
- [ ] Setup logging percobaan login gagal + alert.
- [ ] Load testing endpoint publik (artikel, search).

### **Sprint 7 — Dokumentasi & Testing (3-4 hari)**
- [ ] Generate dokumentasi API otomatis (Scramble/Scribe → Swagger/OpenAPI).
- [ ] Unit test untuk Service layer penting.
- [ ] Feature test untuk endpoint kritikal (auth, CRUD artikel, permission).
- [ ] Postman collection untuk tim FE.

### **Sprint 8 — Deployment (2-3 hari)**
- [ ] Setup server (Nginx + PHP-FPM / Docker).
- [ ] Setup queue worker & scheduler (cron untuk Laravel Task Scheduling).
- [ ] Setup Redis production.
- [ ] Setup backup database otomatis + rotasi.
- [ ] Setup Sentry & uptime monitoring.
- [ ] Setup CI/CD (GitHub Actions: run test → deploy).

**Estimasi total backend: ± 6-8 minggu** (1-2 backend developer, MVP scope).

---

## 4. Struktur Folder Backend (Detail)

```
berita-backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       └── V1/
│   │   │           ├── Auth/
│   │   │           │   ├── LoginController.php
│   │   │           │   ├── LogoutController.php
│   │   │           │   ├── RegisterController.php
│   │   │           │   ├── ForgotPasswordController.php
│   │   │           │   └── MeController.php
│   │   │           ├── ArticleController.php
│   │   │           ├── CategoryController.php
│   │   │           ├── TagController.php
│   │   │           ├── CommentController.php
│   │   │           ├── MediaController.php
│   │   │           ├── UserController.php
│   │   │           ├── RoleController.php
│   │   │           ├── NewsletterController.php
│   │   │           ├── BookmarkController.php
│   │   │           ├── SearchController.php
│   │   │           ├── DashboardStatController.php
│   │   │           └── ActivityLogController.php
│   │   ├── Middleware/
│   │   │   ├── EnsureRole.php
│   │   │   ├── ForceJsonResponse.php
│   │   │   └── SetSecurityHeaders.php
│   │   ├── Requests/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginRequest.php
│   │   │   │   └── RegisterRequest.php
│   │   │   ├── Article/
│   │   │   │   ├── StoreArticleRequest.php
│   │   │   │   └── UpdateArticleRequest.php
│   │   │   ├── Category/
│   │   │   ├── Comment/
│   │   │   └── User/
│   │   └── Resources/
│   │       ├── ArticleResource.php
│   │       ├── ArticleListResource.php   # versi ringkas untuk list
│   │       ├── CategoryResource.php
│   │       ├── TagResource.php
│   │       ├── CommentResource.php
│   │       └── UserResource.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Article.php
│   │   ├── Category.php
│   │   ├── Tag.php
│   │   ├── Comment.php
│   │   ├── Bookmark.php
│   │   └── NewsletterSubscriber.php
│   ├── Policies/
│   │   ├── ArticlePolicy.php
│   │   ├── CommentPolicy.php
│   │   └── UserPolicy.php
│   ├── Services/
│   │   ├── ArticleService.php
│   │   ├── MediaUploadService.php
│   │   ├── SlugService.php
│   │   ├── SearchService.php
│   │   └── StatService.php
│   ├── Jobs/
│   │   ├── ResizeArticleImage.php
│   │   └── SendNewsletterJob.php
│   ├── Observers/
│   │   └── ArticleObserver.php          # auto slug, auto log activity
│   └── Providers/
├── routes/
│   └── api.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── config/
│   ├── sanctum.php
│   ├── cors.php
│   └── permission.php
├── tests/
│   ├── Feature/
│   │   ├── AuthTest.php
│   │   ├── ArticleTest.php
│   │   └── CommentTest.php
│   └── Unit/
└── .env
```

---

## 5. ERD Ringkas (Tabel Utama)

```
users
├─ id, name, email, password, avatar, is_active, timestamps

roles / permissions / model_has_roles      (dari Spatie)

categories
├─ id, parent_id (nullable), name, slug, description, timestamps

tags
├─ id, name, slug, timestamps

articles
├─ id, category_id, author_id, title, slug, excerpt, content (longtext),
│  thumbnail, status (draft/review/published/archived), is_breaking,
│  published_at, meta_title, meta_description, og_image, views_count,
│  timestamps

article_tag (pivot)
├─ article_id, tag_id

comments
├─ id, article_id, user_id (nullable jika guest), parent_id (nullable, nested),
│  name, email, content, status (pending/approved/spam), timestamps

bookmarks
├─ id, user_id, article_id, timestamps

newsletter_subscribers
├─ id, email, is_verified, unsubscribed_at, timestamps

activity_log                              (dari Spatie)
├─ id, log_name, description, subject_type, subject_id, causer_id, properties, timestamps

media                                      (dari Spatie Media Library)
```

---

## 6. Daftar Lengkap API Endpoint

> Format: `METHOD /api/v1/...` — **Auth** = butuh login, **Role** = role minimal, **Public** = tanpa login.

### 6.1 Autentikasi
| Method | Endpoint | Akses | Keterangan |
|---|---|---|---|
| GET | `/sanctum/csrf-cookie` | Public | Ambil CSRF cookie sebelum login (Sanctum SPA) |
| POST | `/api/v1/auth/register` | Public (dibatasi/disable di prod jika hanya admin yg buat user) | Register user internal |
| POST | `/api/v1/auth/login` | Public | Login, rate-limited ketat (misal 5x/menit) |
| POST | `/api/v1/auth/logout` | Auth | Logout & invalidate session/token |
| GET | `/api/v1/auth/me` | Auth | Info user login + role & permission |
| POST | `/api/v1/auth/forgot-password` | Public | Kirim link reset password |
| POST | `/api/v1/auth/reset-password` | Public | Reset password via token |
| PUT | `/api/v1/auth/change-password` | Auth | Ganti password saat login |

### 6.2 Artikel (Publik)
| Method | Endpoint | Akses | Keterangan |
|---|---|---|---|
| GET | `/api/v1/articles` | Public | List artikel published (pagination, filter kategori/tag) |
| GET | `/api/v1/articles/{slug}` | Public | Detail artikel + increment views |
| GET | `/api/v1/articles/trending` | Public | Artikel populer (by views/likes) |
| GET | `/api/v1/articles/breaking` | Public | Artikel breaking news aktif |
| GET | `/api/v1/categories/{slug}/articles` | Public | Artikel by kategori |
| GET | `/api/v1/tags/{slug}/articles` | Public | Artikel by tag |
| GET | `/api/v1/search` | Public | Full-text search artikel (rate-limited) |
| GET | `/api/v1/articles/{slug}/related` | Public | Artikel terkait |

### 6.3 Artikel (Dashboard/Redaksi)
| Method | Endpoint | Akses | Keterangan |
|---|---|---|---|
| GET | `/api/v1/dashboard/articles` | Auth (Editor/Penulis) | List semua artikel (termasuk draft) milik sendiri/semua tergantung role |
| POST | `/api/v1/dashboard/articles` | Auth (Penulis+) | Buat artikel baru (draft) |
| GET | `/api/v1/dashboard/articles/{id}` | Auth | Detail artikel utk edit |
| PUT | `/api/v1/dashboard/articles/{id}` | Auth (Policy: owner/editor) | Update artikel |
| DELETE | `/api/v1/dashboard/articles/{id}` | Auth (Editor+) | Hapus artikel |
| POST | `/api/v1/dashboard/articles/{id}/submit-review` | Auth (Penulis) | Ajukan review ke editor |
| POST | `/api/v1/dashboard/articles/{id}/publish` | Auth (Editor+) | Publish artikel |
| POST | `/api/v1/dashboard/articles/{id}/archive` | Auth (Editor+) | Arsipkan artikel |
| POST | `/api/v1/dashboard/articles/{id}/schedule` | Auth (Editor+) | Jadwalkan publish |

### 6.4 Kategori & Tag (Dashboard)
| Method | Endpoint | Akses | Keterangan |
|---|---|---|---|
| GET | `/api/v1/dashboard/categories` | Auth | List kategori |
| POST | `/api/v1/dashboard/categories` | Auth (Editor+) | Buat kategori |
| PUT | `/api/v1/dashboard/categories/{id}` | Auth (Editor+) | Update kategori |
| DELETE | `/api/v1/dashboard/categories/{id}` | Auth (Admin) | Hapus kategori |
| GET | `/api/v1/dashboard/tags` | Auth | List tag |
| POST | `/api/v1/dashboard/tags` | Auth (Penulis+) | Buat tag baru |
| DELETE | `/api/v1/dashboard/tags/{id}` | Auth (Editor+) | Hapus tag |

### 6.5 Media/Upload
| Method | Endpoint | Akses | Keterangan |
|---|---|---|---|
| POST | `/api/v1/dashboard/media/upload` | Auth (Penulis+) | Upload gambar (validasi MIME, max size) |
| DELETE | `/api/v1/dashboard/media/{id}` | Auth (Editor+) | Hapus media |

### 6.6 Komentar
| Method | Endpoint | Akses | Keterangan |
|---|---|---|---|
| GET | `/api/v1/articles/{slug}/comments` | Public | List komentar approved di artikel |
| POST | `/api/v1/articles/{slug}/comments` | Public/Auth | Kirim komentar (rate-limited, anti-spam/captcha) |
| GET | `/api/v1/dashboard/comments` | Auth (Editor+) | List semua komentar (moderasi) |
| POST | `/api/v1/dashboard/comments/{id}/approve` | Auth (Editor+) | Approve komentar |
| POST | `/api/v1/dashboard/comments/{id}/spam` | Auth (Editor+) | Tandai spam |
| DELETE | `/api/v1/dashboard/comments/{id}` | Auth (Editor+) | Hapus komentar |

### 6.7 Interaksi Pembaca
| Method | Endpoint | Akses | Keterangan |
|---|---|---|---|
| POST | `/api/v1/articles/{slug}/like` | Auth (pembaca) | Like artikel |
| DELETE | `/api/v1/articles/{slug}/like` | Auth | Unlike |
| POST | `/api/v1/articles/{slug}/bookmark` | Auth | Bookmark artikel |
| GET | `/api/v1/me/bookmarks` | Auth | List bookmark milik user |
| POST | `/api/v1/newsletter/subscribe` | Public | Subscribe newsletter (rate-limited) |
| POST | `/api/v1/newsletter/unsubscribe` | Public | Unsubscribe via token |

### 6.8 User & Role Management
| Method | Endpoint | Akses | Keterangan |
|---|---|---|---|
| GET | `/api/v1/dashboard/users` | Auth (Admin) | List user internal |
| POST | `/api/v1/dashboard/users` | Auth (Admin) | Buat user + assign role |
| PUT | `/api/v1/dashboard/users/{id}` | Auth (Admin) | Update user/role |
| DELETE | `/api/v1/dashboard/users/{id}` | Auth (Admin) | Nonaktifkan/hapus user |
| GET | `/api/v1/dashboard/roles` | Auth (Admin) | List role & permission |

### 6.9 Statistik & Audit
| Method | Endpoint | Akses | Keterangan |
|---|---|---|---|
| GET | `/api/v1/dashboard/stats/overview` | Auth (Editor+) | Total artikel, views, komentar pending, dsb |
| GET | `/api/v1/dashboard/stats/top-articles` | Auth (Editor+) | Artikel terpopuler periode tertentu |
| GET | `/api/v1/dashboard/activity-logs` | Auth (Admin) | Audit trail seluruh aksi |

### 6.10 SEO & Utilitas
| Method | Endpoint | Akses | Keterangan |
|---|---|---|---|
| GET | `/sitemap.xml` | Public | Sitemap dinamis (bisa digenerate scheduled job) |
| GET | `/rss` | Public | RSS feed artikel terbaru |
| GET | `/api/v1/health` | Public | Health check (untuk monitoring/load balancer) |

---

## 7. Konvensi & Standar Response

**Response sukses:**
```json
{
  "success": true,
  "message": "Artikel berhasil dibuat",
  "data": { ... }
}
```

**Response list (dengan pagination):**
```json
{
  "success": true,
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 68
  }
}
```

**Response error (validasi):**
```json
{
  "success": false,
  "message": "Data tidak valid",
  "errors": {
    "title": ["Judul wajib diisi"]
  }
}
```

**Response error (umum):**
```json
{
  "success": false,
  "message": "Anda tidak memiliki akses untuk aksi ini"
}
```

**Konvensi tambahan:**
- Gunakan HTTP status code yang tepat: `200`, `201`, `204`, `401`, `403`, `404`, `422`, `429`, `500`.
- Semua endpoint dashboard wajib prefix `/dashboard/` untuk membedakan dari endpoint publik secara jelas di route & middleware group.
- Rate limit berbeda per grup: publik (lebih longgar), auth/login (ketat), komentar/like (sedang).

---

## 8. Checklist Keamanan per Endpoint (Wajib Dicek Sebelum Merge)

- [ ] Route dibungkus middleware `auth:sanctum` jika bukan publik.
- [ ] Ada Policy check (bukan hanya role check) untuk resource milik user tertentu.
- [ ] Ada Form Request untuk validasi input.
- [ ] Rate limiter diterapkan (`throttle:api`, atau custom per endpoint sensitif).
- [ ] Tidak ada mass assignment tanpa `$fillable` eksplisit di model.
- [ ] Response tidak membocorkan data sensitif (password hash, token, dsb) — cek API Resource.
- [ ] File upload divalidasi MIME asli + ukuran maksimal.
- [ ] Query menggunakan Eloquent/Query Builder (bukan raw query rawan injeksi).

---

## 9. Langkah Selanjutnya

Setelah planning backend ini disepakati, tahap berikutnya:
1. Setup project Laravel 13 sesuai Sprint 0.
2. Buat migration & model sesuai ERD di atas.
3. Implementasi modul auth terlebih dahulu (fondasi semua modul lain).
4. Baru lanjut ke planning frontend (Next.js) secara terpisah.