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
        $intlCat = Category::where('slug', 'internasional')->first();
        $sportCat = Category::where('slug', 'olahraga')->first();
        $lifeCat = Category::where('slug', 'gaya-hidup')->first();
        $tags = Tag::all();

        $articles = [
            [
                'category_id' => $techCat?->id ?? 1,
                'author_id' => $author->id,
                'title' => 'Revolusi AI Generatif Mengubah Lanskap Industri Media dan Jurnalisme',
                'slug' => 'revolusi-ai-generatif-mengubah-lanskap-industri-media',
                'excerpt' => 'Teknologi kecerdasan buatan membantu jurnalis menganalisis data dalam hitungan detik tanpa menghilangkan integritas fakta.',
                'content' => '<p>Perkembangan teknologi <strong>Artificial Intelligence (AI)</strong> terus bertransformasi dengan cepat. Di ranah jurnalisme, pemanfaatan model bahasa skala besar memudahkan wartawan dalam merangkum transkrip wawancara, mendeteksi pola anomali dalam investigasi data, hingga menyajikan rekomendasi berita berbasis preferensi pembaca secara real-time.</p><p>Kendati demikian, para pakar menekankan bahwa etika jurnalistik dan proses verifikasi fakta oleh manusia tetap menjadi pilar utama yang tak tergantikan oleh otomasi mesin.</p>',
                'thumbnail' => null,
                'status' => 'published',
                'is_breaking' => true,
                'published_at' => now()->subHours(3),
                'created_at' => now()->subHours(5),
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
                'content' => '<p>Laporan ekonomi terbaru menunjukkan akselerasi transaksi digital di berbagai daerah. Kolaborasi antar lembaga perbankan dan ekosistem startup terbukti memperluas inklusi keuangan masyarakat hingga ke pelosok negeri.</p>',
                'thumbnail' => null,
                'status' => 'published',
                'is_breaking' => false,
                'published_at' => now()->subDays(1)->subHours(2),
                'created_at' => now()->subDays(1),
                'meta_title' => 'Pertumbuhan Ekonomi Digital - Berita Bisnis',
                'meta_description' => 'Analisis proyeksi lonjakan ekonomi digital dan fintech.',
                'views_count' => 980,
            ],
            [
                'category_id' => $natCat?->id ?? 3,
                'author_id' => $author->id,
                'title' => 'Pemerintah Resmikan Proyek Infrastruktur Transportasi Berkelanjutan',
                'slug' => 'pemerintah-resmikan-proyek-infrastruktur-transportasi-berkelanjutan',
                'excerpt' => 'Transportasi ramah lingkungan berbasis energi terbarukan mulai dioperasikan secara komersial.',
                'content' => '<p>Inisiatif mobilitas hijau semakin nyata dengan diresmikannya armada transportasi publik nir-emisi.</p>',
                'thumbnail' => null,
                'status' => 'published',
                'is_breaking' => false,
                'published_at' => now()->subDays(2),
                'created_at' => now()->subDays(2),
                'meta_title' => 'Transportasi Berkelanjutan Diresmikan - Berita Nasional',
                'meta_description' => 'Proyek transportasi ramah lingkungan resmi beroperasi.',
                'views_count' => 840,
            ],
            [
                'category_id' => $intlCat?->id ?? 4,
                'author_id' => $author->id,
                'title' => 'KTT Perubahan Iklim Global Sepakati Komitmen Transisi Energi Hijau',
                'slug' => 'ktt-perubahan-iklim-global-sepakati-komitmen-transisi-energi',
                'excerpt' => 'Para pemimpin dunia menyetujui pendanaan strategis untuk percepatan adopsi energi baru terbarukan.',
                'content' => '<p>Pertemuan tingkat tinggi ini menandai langkah konkret perlindungan ekosistem bumi.</p>',
                'thumbnail' => null,
                'status' => 'published',
                'is_breaking' => false,
                'published_at' => now()->subDays(3),
                'created_at' => now()->subDays(3),
                'meta_title' => 'KTT Perubahan Iklim Global - Internasional',
                'meta_description' => 'Komitmen global percepatan energi terbarukan.',
                'views_count' => 710,
            ],
            [
                'category_id' => $sportCat?->id ?? 5,
                'author_id' => $author->id,
                'title' => 'Tim Nasional Sukses Amankan Tiket Lolos ke Putaran Final Kejuaraan Asia',
                'slug' => 'tim-nasional-sukses-amankan-tiket-kejuaraan-asia',
                'excerpt' => 'Kemenangan dramatis di menit akhir memastikan langkah tim melaju ke fase berikutnya.',
                'content' => '<p>Performa solid lini pertahanan dan serangan balik cepat menjadi kunci kemenangan berharga.</p>',
                'thumbnail' => null,
                'status' => 'published',
                'is_breaking' => false,
                'published_at' => now()->subDays(4),
                'created_at' => now()->subDays(4),
                'meta_title' => 'Timnas Lolos Kejuaraan Asia - Olahraga',
                'meta_description' => 'Hasil pertandingan gemilang tim nasional.',
                'views_count' => 1150,
            ],
            [
                'category_id' => $lifeCat?->id ?? 6,
                'author_id' => $author->id,
                'title' => 'Tren Gaya Hidup Minimalis dan Mindfulness di Kalangan Profesional Urban',
                'slug' => 'tren-gaya-hidup-minimalis-mindfulness-profesional-urban',
                'excerpt' => 'Menjaga keseimbangan kerja dan kesehatan mental kini menjadi prioritas utama generasi produktif.',
                'content' => '<p>Praktik mindfulness dan decluttering terbukti menurunkan tingkat stres harian secara efektif.</p>',
                'thumbnail' => null,
                'status' => 'published',
                'is_breaking' => false,
                'published_at' => now()->subDays(5),
                'created_at' => now()->subDays(5),
                'meta_title' => 'Gaya Hidup Minimalis - Gaya Hidup',
                'meta_description' => 'Panduan mindfulness dan keseimbangan hidup.',
                'views_count' => 620,
            ],
            [
                'category_id' => $techCat?->id ?? 1,
                'author_id' => $author->id,
                'title' => 'Inovasi Baterai Solid-State Menjanjikan Jarak Tempuh Kendaraan Listrik Lebih Jauh',
                'slug' => 'inovasi-baterai-solid-state-kendaraan-listrik',
                'excerpt' => 'Kerapatan energi yang lebih tinggi membuat pengisian daya lebih singkat dan aman.',
                'content' => '<p>Riset terbaru laboratorium teknologi menunjukkan efisiensi daya hingga 50% lebih optimal.</p>',
                'thumbnail' => null,
                'status' => 'published',
                'is_breaking' => false,
                'published_at' => now()->subDays(6),
                'created_at' => now()->subDays(6),
                'meta_title' => 'Baterai Solid State - Teknologi',
                'meta_description' => 'Terobosan baru efisiensi kendaraan listrik.',
                'views_count' => 890,
            ],
            [
                'category_id' => $techCat?->id ?? 1,
                'author_id' => $author->id,
                'title' => 'Draft Desain Keamanan Siber Terintegrasi Berbasis Zero Trust',
                'slug' => 'draft-desain-keamanan-siber-zero-trust',
                'excerpt' => 'Pendekatan arsitektur keamanan modern untuk menangkal ancaman ransomware.',
                'content' => '<p>Naskah ini masih dalam tahap review oleh tim redaksi teknologi.</p>',
                'thumbnail' => null,
                'status' => 'draft',
                'is_breaking' => false,
                'published_at' => null,
                'created_at' => now()->subHours(1),
                'views_count' => 0,
            ],
            [
                'category_id' => $bizCat?->id ?? 2,
                'author_id' => $author->id,
                'title' => 'Analisis Kebijakan Suku Bunga dan Implikasinya pada Pasar Modal',
                'slug' => 'analisis-kebijakan-suku-bunga-dan-pasar-modal',
                'excerpt' => 'Review mendalam oleh editor sebelum dipublikasikan pada edisi akhir pekan.',
                'content' => '<p>Draf naskah analisis ekonomi dalam proses penyuntingan data grafik.</p>',
                'thumbnail' => null,
                'status' => 'review',
                'is_breaking' => false,
                'published_at' => null,
                'created_at' => now()->subDays(1),
                'views_count' => 0,
            ],
        ];

        foreach ($articles as $artData) {
            $article = Article::updateOrCreate(['slug' => $artData['slug']], $artData);
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
                        'content' => 'Ulasan yang sangat jelas dan berimbang. Sangat bermanfaat untuk referensi terkini!',
                        'status' => 'approved',
                    ]
                );
            }
        }
    }
}
