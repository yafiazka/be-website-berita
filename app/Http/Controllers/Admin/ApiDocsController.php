<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ApiDocsController extends Controller
{
    public function index()
    {
        $endpoints = $this->getEndpoints();
        $totalEndpoints = count($endpoints);
        $groupedEndpoints = collect($endpoints)->groupBy('category');

        return view('admin.api_docs', compact('endpoints', 'totalEndpoints', 'groupedEndpoints'));
    }

    public function downloadPostman()
    {
        $collection = $this->generatePostmanCollection();
        $fileName = 'Website_Berita_API_v1.postman_collection.json';

        return response()->json($collection, 200, [
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Content-Type' => 'application/json',
        ]);
    }

    private function getEndpoints(): array
    {
        return [
            // AUTHENTICATION
            [
                'category' => 'Authentication',
                'name' => 'Login (Email atau Username)',
                'method' => 'POST',
                'path' => '/api/v1/auth/login',
                'auth' => 'Public',
                'description' => 'Autentikasi pengguna menggunakan email atau username serta kata sandi. Mengembalikan Bearer Token.',
                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                'body' => [
                    'login' => 'admin (atau admin@berita.local)',
                    'password' => 'password',
                ],
                'response' => [
                    'success' => true,
                    'message' => 'Login berhasil.',
                    'data' => [
                        'token' => '1|token_string_here',
                        'token_type' => 'Bearer',
                        'user' => ['id' => 1, 'name' => 'Super Administrator', 'username' => 'admin', 'email' => 'admin@berita.local', 'roles' => ['Admin']],
                    ],
                ],
            ],
            [
                'category' => 'Authentication',
                'name' => 'Register Pembaca',
                'method' => 'POST',
                'path' => '/api/v1/auth/register',
                'auth' => 'Public',
                'description' => 'Pendaftaran akun pembaca baru.',
                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                'body' => [
                    'name' => 'Budi Santoso',
                    'username' => 'budisantoso',
                    'email' => 'budi@example.com',
                    'password' => 'password123',
                    'password_confirmation' => 'password123',
                ],
                'response' => [
                    'success' => true,
                    'message' => 'Pendaftaran akun berhasil.',
                    'data' => [
                        'token' => '2|token_string',
                        'user' => ['id' => 5, 'name' => 'Budi Santoso', 'username' => 'budisantoso', 'email' => 'budi@example.com'],
                    ],
                ],
            ],
            [
                'category' => 'Authentication',
                'name' => 'User Profile (Me)',
                'method' => 'GET',
                'path' => '/api/v1/auth/me',
                'auth' => 'Bearer Token',
                'description' => 'Mengambil profil pengguna yang sedang login beserta role & permission.',
                'headers' => ['Authorization' => 'Bearer {{token}}', 'Accept' => 'application/json'],
                'body' => null,
                'response' => [
                    'success' => true,
                    'data' => ['id' => 1, 'name' => 'Super Administrator', 'username' => 'admin', 'roles' => ['Admin']],
                ],
            ],
            [
                'category' => 'Authentication',
                'name' => 'Logout',
                'method' => 'POST',
                'path' => '/api/v1/auth/logout',
                'auth' => 'Bearer Token',
                'description' => 'Mencabut token autentikasi saat ini.',
                'headers' => ['Authorization' => 'Bearer {{token}}', 'Accept' => 'application/json'],
                'body' => null,
                'response' => ['success' => true, 'message' => 'Logout berhasil.'],
            ],

            // PUBLIC ARTICLES
            [
                'category' => 'Public Articles',
                'name' => 'Daftar Artikel (Feed Utama)',
                'method' => 'GET',
                'path' => '/api/v1/articles',
                'auth' => 'Public',
                'description' => 'Mendapatkan daftar artikel terbit dengan filter kategori, tag, sorting, dan paginasi.',
                'params' => ['category' => 'teknologi', 'tag' => 'ai', 'sort' => 'latest|popular', 'page' => 1, 'per_page' => 10],
                'response' => [
                    'success' => true,
                    'data' => [
                        ['id' => 1, 'title' => 'Peluncuran AI Generatif Generasi Baru', 'slug' => 'peluncuran-ai-generatif-generasi-baru', 'views_count' => 1250],
                    ],
                    'meta' => ['current_page' => 1, 'total' => 50],
                ],
            ],
            [
                'category' => 'Public Articles',
                'name' => 'Artikel Trending',
                'method' => 'GET',
                'path' => '/api/v1/articles/trending',
                'auth' => 'Public',
                'description' => 'Mendapatkan 5-10 artikel dengan tayangan (views) terbanyak dalam 7 hari terakhir.',
                'params' => ['limit' => 5],
                'response' => ['success' => true, 'data' => []],
            ],
            [
                'category' => 'Public Articles',
                'name' => 'Breaking News',
                'method' => 'GET',
                'path' => '/api/v1/articles/breaking',
                'auth' => 'Public',
                'description' => 'Mendapatkan artikel yang ditandai sebagai berita utama mendesak (is_breaking).',
                'params' => [],
                'response' => ['success' => true, 'data' => []],
            ],
            [
                'category' => 'Public Articles',
                'name' => 'Detail Artikel',
                'method' => 'GET',
                'path' => '/api/v1/articles/{slug}',
                'auth' => 'Public',
                'description' => 'Mendapatkan detail lengkap satu artikel dan otomatis menghitung increment views.',
                'params' => ['slug' => 'peluncuran-ai-generatif-generasi-baru'],
                'response' => ['success' => true, 'data' => ['id' => 1, 'title' => 'Peluncuran AI', 'content' => '<p>Konten...</p>']],
            ],
            [
                'category' => 'Public Articles',
                'name' => 'Pencarian Artikel (Full-Text Search)',
                'method' => 'GET',
                'path' => '/api/v1/articles/search',
                'auth' => 'Public',
                'description' => 'Mencari artikel berdasarkan kata kunci pada judul atau konten.',
                'params' => ['q' => 'teknologi ai', 'page' => 1],
                'response' => ['success' => true, 'data' => []],
            ],
            [
                'category' => 'Public Articles',
                'name' => 'Artikel Terkait (Related)',
                'method' => 'GET',
                'path' => '/api/v1/articles/{slug}/related',
                'auth' => 'Public',
                'description' => 'Mendapatkan rekomendasi artikel relevan berdasarkan kesamaan kategori dan tags.',
                'params' => ['limit' => 4],
                'response' => ['success' => true, 'data' => []],
            ],
            [
                'category' => 'Public Articles',
                'name' => 'Like / Unlike Artikel',
                'method' => 'POST',
                'path' => '/api/v1/articles/{slug}/like',
                'auth' => 'Bearer Token',
                'description' => 'Memberi atau membatalkan like pada artikel.',
                'headers' => ['Authorization' => 'Bearer {{token}}'],
                'response' => ['success' => true, 'message' => 'Artikel disukai.', 'data' => ['is_liked' => true, 'likes_count' => 45]],
            ],
            [
                'category' => 'Public Articles',
                'name' => 'Bookmark / Unbookmark Artikel',
                'method' => 'POST',
                'path' => '/api/v1/articles/{slug}/bookmark',
                'auth' => 'Bearer Token',
                'description' => 'Menyimpan artikel ke daftar bacaan pribadi (bookmark).',
                'headers' => ['Authorization' => 'Bearer {{token}}'],
                'response' => ['success' => true, 'message' => 'Artikel berhasil disimpan.', 'data' => ['is_bookmarked' => true]],
            ],

            // REDAKSI & EDITORIAL
            [
                'category' => 'Redaksi & Editorial',
                'name' => 'Daftar Artikel Redaksi',
                'method' => 'GET',
                'path' => '/api/v1/editorial/articles',
                'auth' => 'Bearer Token (Editor/Admin/Penulis)',
                'description' => 'Mendapatkan seluruh artikel untuk redaksi termasuk draft, in review, dan scheduled.',
                'params' => ['status' => 'draft|review|published', 'author_id' => 1],
                'response' => ['success' => true, 'data' => []],
            ],
            [
                'category' => 'Redaksi & Editorial',
                'name' => 'Buat Artikel Baru',
                'method' => 'POST',
                'path' => '/api/v1/editorial/articles',
                'auth' => 'Bearer Token (Penulis/Editor/Admin)',
                'description' => 'Membuat draft atau mengajukan artikel berita baru.',
                'body' => [
                    'title' => 'Judul Berita Terbaru Hari Ini',
                    'category_id' => 1,
                    'excerpt' => 'Ringkasan singkat berita...',
                    'content' => '<p>Isi lengkap berita...</p>',
                    'status' => 'draft',
                    'tags' => [1, 2],
                    'is_featured' => false,
                    'is_breaking' => false,
                ],
                'response' => ['success' => true, 'message' => 'Artikel berhasil dibuat.', 'data' => ['id' => 10]],
            ],
            [
                'category' => 'Redaksi & Editorial',
                'name' => 'Publikasikan Artikel',
                'method' => 'POST',
                'path' => '/api/v1/editorial/articles/{id}/publish',
                'auth' => 'Bearer Token (Editor/Admin)',
                'description' => 'Mengubah status artikel menjadi published dan mengeset published_at saat ini.',
                'response' => ['success' => true, 'message' => 'Artikel berhasil dipublikasikan.'],
            ],
            [
                'category' => 'Redaksi & Editorial',
                'name' => 'Jadwalkan Publikasi Artikel',
                'method' => 'POST',
                'path' => '/api/v1/editorial/articles/{id}/schedule',
                'auth' => 'Bearer Token (Editor/Admin)',
                'body' => ['published_at' => '2026-08-20 08:00:00'],
                'response' => ['success' => true, 'message' => 'Artikel berhasil dijadwalkan.'],
            ],
            [
                'category' => 'Redaksi & Editorial',
                'name' => 'Statistik Dashboard Redaksi',
                'method' => 'GET',
                'path' => '/api/v1/editorial/stats',
                'auth' => 'Bearer Token (Editor/Admin)',
                'description' => 'Mendapatkan agregasi statistik total artikel, views, pembaca, dan interaksi.',
                'response' => ['success' => true, 'data' => ['total_articles' => 24, 'published_articles' => 18, 'total_views' => 15200]],
            ],

            // KOMENTAR
            [
                'category' => 'Komentar & Moderasi',
                'name' => 'Komentar pada Artikel',
                'method' => 'GET',
                'path' => '/api/v1/articles/{slug}/comments',
                'auth' => 'Public',
                'description' => 'Mendapatkan komentar bertingkat (nested replies) yang telah disetujui.',
                'params' => ['page' => 1],
                'response' => ['success' => true, 'data' => []],
            ],
            [
                'category' => 'Komentar & Moderasi',
                'name' => 'Kirim Komentar',
                'method' => 'POST',
                'path' => '/api/v1/articles/{slug}/comments',
                'auth' => 'Bearer Token',
                'body' => ['content' => 'Opini yang sangat menarik!', 'parent_id' => null],
                'response' => ['success' => true, 'message' => 'Komentar berhasil dikirim dan menunggu moderasi.'],
            ],
            [
                'category' => 'Komentar & Moderasi',
                'name' => 'Moderasi Komentar (Admin/Editor)',
                'method' => 'PATCH',
                'path' => '/api/v1/admin/comments/{id}/status',
                'auth' => 'Bearer Token (Admin/Editor)',
                'body' => ['status' => 'approved (atau rejected / spam)'],
                'response' => ['success' => true, 'message' => 'Status komentar berhasil diperbarui.'],
            ],

            // KATEGORI & TAGS
            [
                'category' => 'Kategori & Tags',
                'name' => 'Daftar Kategori',
                'method' => 'GET',
                'path' => '/api/v1/categories',
                'auth' => 'Public',
                'description' => 'Mendapatkan daftar seluruh kategori beserta hierarki parent/child.',
                'response' => ['success' => true, 'data' => []],
            ],
            [
                'category' => 'Kategori & Tags',
                'name' => 'Daftar Tags',
                'method' => 'GET',
                'path' => '/api/v1/tags',
                'auth' => 'Public',
                'description' => 'Mendapatkan daftar tag populer berita.',
                'response' => ['success' => true, 'data' => []],
            ],

            // PENGGUNA & ROLES
            [
                'category' => 'Manajemen Pengguna (Admin)',
                'name' => 'Daftar Pengguna',
                'method' => 'GET',
                'path' => '/api/v1/admin/users',
                'auth' => 'Bearer Token (Admin)',
                'description' => 'Mendapatkan daftar user terdaftar dengan filter role dan status aktif.',
                'response' => ['success' => true, 'data' => []],
            ],
            [
                'category' => 'Manajemen Pengguna (Admin)',
                'name' => 'Update Role & Status Pengguna',
                'method' => 'PUT',
                'path' => '/api/v1/admin/users/{id}',
                'auth' => 'Bearer Token (Admin)',
                'body' => ['roles' => ['Editor'], 'is_active' => true],
                'response' => ['success' => true, 'message' => 'Pengguna berhasil diperbarui.'],
            ],

            // FEEDS & SYSTEM
            [
                'category' => 'Feeds & System',
                'name' => 'Health Check System',
                'method' => 'GET',
                'path' => '/api/v1/health',
                'auth' => 'Public',
                'description' => 'Memeriksa status kesehatan database, Redis, container PHP, dan Laravel.',
                'response' => ['success' => true, 'data' => ['status' => 'healthy', 'database' => 'OK', 'redis' => 'OK']],
            ],
            [
                'category' => 'Feeds & System',
                'name' => 'Sitemap XML',
                'method' => 'GET',
                'path' => '/sitemap.xml',
                'auth' => 'Public',
                'description' => 'Sitemap XML standar search engine untuk pengindeksan artikel berita.',
                'response' => 'XML Sitemap response',
            ],
            [
                'category' => 'Feeds & System',
                'name' => 'RSS Feed Berita',
                'method' => 'GET',
                'path' => '/rss',
                'auth' => 'Public',
                'description' => 'RSS Feed 2.0 untuk pembaca berita dan agregator.',
                'response' => 'XML RSS Feed 2.0',
            ],
        ];
    }

    private function generatePostmanCollection(): array
    {
        $endpoints = $this->getEndpoints();
        $grouped = collect($endpoints)->groupBy('category');

        $itemFolders = [];

        foreach ($grouped as $category => $items) {
            $folderItems = [];
            foreach ($items as $endpoint) {
                $urlParts = explode('/', ltrim($endpoint['path'], '/'));
                $pathArray = array_map(function ($part) {
                    return preg_replace('/^{(.+)}$/', ':$1', $part);
                }, $urlParts);

                $headers = [];
                if (!empty($endpoint['headers'])) {
                    foreach ($endpoint['headers'] as $k => $v) {
                        $headers[] = ['key' => $k, 'value' => $v, 'type' => 'text'];
                    }
                } else {
                    $headers[] = ['key' => 'Accept', 'value' => 'application/json', 'type' => 'text'];
                }

                $requestData = [
                    'method' => $endpoint['method'],
                    'header' => $headers,
                    'url' => [
                        'raw' => '{{base_url}}' . $endpoint['path'],
                        'host' => ['{{base_url}}'],
                        'path' => $pathArray,
                    ],
                    'description' => $endpoint['description'] ?? '',
                ];

                if (!empty($endpoint['body'])) {
                    $requestData['body'] = [
                        'mode' => 'raw',
                        'raw' => json_encode($endpoint['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                        'options' => [
                            'raw' => ['language' => 'json'],
                        ],
                    ];
                }

                if (!empty($endpoint['params'])) {
                    $query = [];
                    foreach ($endpoint['params'] as $pk => $pv) {
                        $query[] = ['key' => $pk, 'value' => (string) $pv];
                    }
                    $requestData['url']['query'] = $query;
                }

                $folderItems[] = [
                    'name' => $endpoint['name'] . ' [' . $endpoint['method'] . ']',
                    'request' => $requestData,
                    'response' => [
                        [
                            'name' => 'Contoh Response Sukses (200 OK)',
                            'status' => 'OK',
                            'code' => 200,
                            'header' => [['key' => 'Content-Type', 'value' => 'application/json']],
                            'body' => json_encode($endpoint['response'] ?? ['success' => true], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                        ],
                    ],
                ];
            }

            $itemFolders[] = [
                'name' => $category,
                'item' => $folderItems,
            ];
        }

        return [
            'info' => [
                'name' => 'Website Berita REST API v1',
                '_postman_id' => 'be-website-berita-v1-collection',
                'description' => 'Koleksi lengkap REST API Backend Website Berita (Laravel 12 + Sanctum + Spatie Roles).',
                'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
            ],
            'variable' => [
                [
                    'key' => 'base_url',
                    'value' => url('/'),
                    'type' => 'string',
                ],
                [
                    'key' => 'token',
                    'value' => 'YOUR_BEARER_TOKEN_HERE',
                    'type' => 'string',
                ],
            ],
            'item' => $itemFolders,
        ];
    }
}
