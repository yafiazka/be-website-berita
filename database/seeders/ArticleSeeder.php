<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Models\Tag;
use App\Models\Comment;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'penulis@berita.local')->first() ?? User::first();
        $techCat = Category::where('slug', 'teknologi')->first();
        $bizCat = Category::where('slug', 'bisnis')->first();
        $natCat = Category::where('slug', 'nasional')->first();
        $tags = Tag::all();

        $articles = [
            [
                'category_id' => $techCat?->id ?? 1,
                'author_id' => $author->id,
                'title' => 'Revolusi AI Generatif Mengubah Lanskap Industri Media dan Jurnalisme',
                'slug' => 'revolusi-ai-generatif-mengubah-lanskap-industri-media',
                'excerpt' => 'Teknologi kecerdasan buatan membantu jurnalis menganalisis data dalam hitungan detik tanpa menghilangkan integritas fakta.',
                'content' => '<p>Perkembangan teknologi <strong>Artificial Intelligence (AI)</strong> terus bertransformasi dengan cepat. Di ranah jurnalisme, pemanfaatan model bahasa skala besar memudahkan wartawan dalam merangkum transkrip wawancara, mendeteksi pola anomali dalam investigasi data, hingga menyajikan rekomendasi berita berbasis preferensi pembaca secara real-time.</p><p>Kendati demikian, para pakar menekankan bahwa etika jurnalistik dan proses verifikasi fakta oleh manusia tetap menjadi pilar utama yang tak tergantikan oleh otomasi mesin.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=800',
                'status' => 'published',
                'is_breaking' => true,
                'published_at' => now()->subHours(2),
                'meta_title' => 'Revolusi AI Generatif di Media - Berita Teknologi',
                'meta_description' => 'Bagaimana AI merevolusi proses peliputan dan distribusi berita secara global.',
                'views_count' => 1420,
            ],
            [
                'category_id' => $bizCat?->id ?? 2,
                'author_id' => $author->id,
                'title' => 'Pertumbuhan Ekonomi Digital Diproyeksikan Melonjak Signifikan Tahun Ini',
                'slug' => 'pertumbuhan-ekonomi-digital-diproyeksikan-melonjak',
                'excerpt' => 'Sektor e-commerce dan fintech menjadi pendorong utama ekspansi ekosistem digital nasional.',
                'content' => '<p>Laporan ekonomi terbaru menunjukkan akselerasi transaksi digital di berbagai daerah. Kolaborasi antar lembaga perbankan dan ekosistem startup terbukti memperluas inklusi keuangan masyarakat hingga ke pelosok negeri.</p><p>Regulator optimis momentum positif ini akan memperkuat fundamental ekonomi menghadapi ketidakpastian global.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?w=800',
                'status' => 'published',
                'is_breaking' => false,
                'published_at' => now()->subHours(5),
                'meta_title' => 'Pertumbuhan Ekonomi Digital - Berita Bisnis',
                'meta_description' => 'Analisis proyeksi lonjakan ekonomi digital dan fintech.',
                'views_count' => 890,
            ],
            [
                'category_id' => $natCat?->id ?? 3,
                'author_id' => $author->id,
                'title' => 'Pemerintah Resmikan Proyek Infrastruktur Transportasi Berkelanjutan',
                'slug' => 'pemerintah-resmikan-proyek-infrastruktur-transportasi-berkelanjutan',
                'excerpt' => 'Transportasi ramah lingkungan berbasis energi terbarukan mulai dioperasikan secara komersial.',
                'content' => '<p>Inisiatif mobilitas hijau semakin nyata dengan diresmikannya armada transportasi publik nir-emisi. Pemerintah menargetkan pengurangan emisi karbon sebesar 30% di kawasan perkotaan metropolitan dalam lima tahun ke depan.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1519003722824-194d4455a60c?w=800',
                'status' => 'published',
                'is_breaking' => false,
                'published_at' => now()->subDay(),
                'meta_title' => 'Transportasi Berkelanjutan Diresmikan - Berita Nasional',
                'meta_description' => 'Proyek transportasi ramah lingkungan resmi beroperasi.',
                'views_count' => 610,
            ],
            [
                'category_id' => $techCat?->id ?? 1,
                'author_id' => $author->id,
                'title' => 'Draft Inovasi Chip Generasi Terbaru Siap Diuji Coba',
                'slug' => 'draft-inovasi-chip-generasi-terbaru',
                'excerpt' => 'Arsitektur silikon 2nm menjanjikan efisiensi daya hingga 40 persen lebih hemat.',
                'content' => '<p>Naskah ini masih dalam tahap peninjauan redaksi sebelum dirilis ke publik.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800',
                'status' => 'draft',
                'is_breaking' => false,
                'published_at' => null,
                'views_count' => 0,
            ],
        ];

        foreach ($articles as $artData) {
            $article = Article::firstOrCreate(['slug' => $artData['slug']], $artData);
            if ($tags->isNotEmpty()) {
                $article->tags()->sync($tags->random(min(3, $tags->count()))->pluck('id'));
            }

            // Add sample comment if published
            if ($article->status === 'published') {
                Comment::firstOrCreate(
                    [
                        'article_id' => $article->id,
                        'email' => 'pembaca@berita.local',
                    ],
                    [
                        'user_id' => User::where('email', 'pembaca@berita.local')->first()?->id,
                        'name' => 'Pembaca Setia',
                        'content' => 'Artikel yang sangat informatif dan membuka wawasan. Ditunggu ulasan mendalam berikutnya!',
                        'status' => 'approved',
                    ]
                );
            }
        }
    }
}
