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
        $admin = User::where('email', 'admin@berita.local')->first() ?? $author;

        $natCat = Category::where('slug', 'nasional')->first();
        $intlCat = Category::where('slug', 'internasional')->first();
        $techCat = Category::where('slug', 'teknologi')->first();
        $bizCat = Category::where('slug', 'bisnis')->first();
        $sportCat = Category::where('slug', 'olahraga')->first();
        $lifeCat = Category::where('slug', 'gaya-hidup')->first();

        $tags = Tag::all();

        $articles = [
            [
                'category_id' => $techCat?->id ?? 1,
                'author_id' => $author->id,
                'author_source' => 'Rian Hidayat (Editor Teknologi)',
                'source' => 'Reuters Media Tech',
                'title' => 'Revolusi AI Generatif Mengubah Lanskap Industri Media dan Jurnalisme',
                'slug' => 'revolusi-ai-generatif-mengubah-lanskap-industri-media',
                'excerpt' => 'Teknologi kecerdasan buatan membantu jurnalis menganalisis data dalam hitungan detik tanpa menghilangkan integritas fakta.',
                'content' => '<h2>Transformasi Digital di Ruang Redaksi</h2><p>Perkembangan teknologi <strong>Artificial Intelligence (AI)</strong> terus bertransformasi dengan cepat. Di ranah jurnalisme modern, pemanfaatan model bahasa skala besar memudahkan wartawan dalam merangkum transkrip wawancara, mendeteksi anomali data investigasi, hingga menyajikan rekomendasi berita berbasis preferensi pembaca secara real-time.</p><figure><img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=1200&auto=format&fit=crop&q=80" alt="Artificial Intelligence Visual" /><figcaption>Ilustrasi: Perkembangan kecerdasan buatan generatif dalam ekosistem informasi global. (Foto: Unsplash/Guerillabuzz)</figcaption></figure><blockquote>"AI bukan untuk menggantikan peran jurnalis, melainkan memberdayakan wartawan untuk menggali kebenaran lebih dalam dan cepat." — Dewan Pers Internasional</blockquote><p>Kendati demikian, para pakar menekankan bahwa etika jurnalistik, verifikasi silang, dan empati manusia tetap menjadi pilar utama yang tak tergantikan oleh otomasi mesin algoritma.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=1200&auto=format&fit=crop&q=80',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'status' => 'published',
                'is_featured' => true,
                'is_breaking' => true,
                'published_at' => now()->subHours(2),
                'created_at' => now()->subHours(4),
                'meta_title' => 'Revolusi AI Generatif di Media - Berita Teknologi',
                'meta_description' => 'Bagaimana AI merevolusi proses peliputan dan distribusi berita secara global.',
                'views_count' => 1840,
            ],
            [
                'category_id' => $bizCat?->id ?? 2,
                'author_id' => $author->id,
                'author_source' => 'Dewi Anggraini (Jurnalis Ekonomi)',
                'source' => 'LKBN Antara / BI',
                'title' => 'Pertumbuhan Ekonomi Digital Diproyeksikan Melonjak Signifikan Tahun Ini',
                'slug' => 'pertumbuhan-ekonomi-digital-diproyeksikan-melonjak',
                'excerpt' => 'Sektor e-commerce dan fintech menjadi pendorong utama ekspansi ekosistem digital nasional yang inklusif.',
                'content' => '<h2>Akselerasi Transaksi Digital Antardaerah</h2><p>Laporan ekonomi triwulan terbaru menunjukkan akselerasi transaksi digital di berbagai daerah meningkat hingga 28% secara tahunan. Kolaborasi antar lembaga perbankan dan ekosistem startup terbukti memperluas inklusi keuangan masyarakat hingga ke pelosok negeri.</p><figure><img src="https://images.unsplash.com/photo-1559526324-4b87b5e36e44?w=1200&auto=format&fit=crop&q=80" alt="Ekonomi Digital" /><figcaption>Foto: Pertumbuhan transaksi pembayaran digital di kawasan perkotaan. (Unsplash/Austin Distel)</figcaption></figure><p>Regulator optimis momentum positif ini akan memperkuat fundamental ekonomi nasional dalam menghadapi volatilitas pasar global.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?w=1200&auto=format&fit=crop&q=80',
                'video_url' => null,
                'status' => 'published',
                'is_featured' => true,
                'is_breaking' => false,
                'published_at' => now()->subDays(1)->subHours(2),
                'created_at' => now()->subDays(1),
                'meta_title' => 'Pertumbuhan Ekonomi Digital - Berita Bisnis',
                'meta_description' => 'Analisis proyeksi lonjakan ekonomi digital dan fintech nasional.',
                'views_count' => 1250,
            ],
            [
                'category_id' => $natCat?->id ?? 3,
                'author_id' => $admin->id,
                'author_source' => 'Budi Santoso (Koresponden IKN)',
                'source' => 'Biro Pers, Media, dan Informasi Istana',
                'title' => 'Pemerintah Resmikan Proyek Infrastruktur Transportasi Berkelanjutan di Kawasan Inti IKN',
                'slug' => 'pemerintah-resmikan-proyek-infrastruktur-transportasi-berkelanjutan',
                'excerpt' => 'Transportasi ramah lingkungan berbasis armada energi terbarukan mulai dioperasikan secara komersial.',
                'content' => '<h2>Mobilitas Hijau Nir-Emisi</h2><p>Inisiatif mobilitas hijau semakin nyata dengan diresmikannya koridor transportasi publik nir-emisi di Ibu Kota Nusantara. Seluruh kendaraan massal bertenaga listrik cerdas telah terintegrasi dengan sensor lalu lintas otomatis berbasis satelit.</p><figure><img src="https://images.unsplash.com/photo-1519003722824-194d4455a60c?w=1200&auto=format&fit=crop&q=80" alt="Transportasi Masa Depan" /><figcaption>Armada bus listrik cerdas beroperasi di jalur khusus. (Foto: Humas Kementerian Perhubungan)</figcaption></figure><p>Pemerintah menargetkan pengurangan emisi karbon transportasi hingga 75% di kawasan metropolitan dan pusat pemerintahan baru.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1519003722824-194d4455a60c?w=1200&auto=format&fit=crop&q=80',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'status' => 'published',
                'is_featured' => true,
                'is_breaking' => false,
                'published_at' => now()->subDays(2),
                'created_at' => now()->subDays(2),
                'meta_title' => 'Transportasi Berkelanjutan Diresmikan - Berita Nasional',
                'meta_description' => 'Proyek transportasi ramah lingkungan resmi beroperasi di IKN.',
                'views_count' => 960,
            ],
            [
                'category_id' => $intlCat?->id ?? 4,
                'author_id' => $author->id,
                'author_source' => 'Siti Nurhaliza (Kanal Internasional)',
                'source' => 'AFP News Agency',
                'title' => 'KTT Perubahan Iklim Global Sepakati Komitmen Pendanaan Transisi Energi Hijau',
                'slug' => 'ktt-perubahan-iklim-global-sepakati-komitmen-transisi-energi',
                'excerpt' => 'Para pemimpin dunia menyetujui paket stimulus strategis untuk percepatan adopsi panel surya dan turbin angin.',
                'content' => '<h2>Komitmen Global Transisi Rendah Karbon</h2><p>Pertemuan tingkat tinggi para pemimpin dunia di Jenewa berhasil mencapai konsensus penting mengenai pendanaan mitigasi iklim. Negara-negara berkembang akan menerima hibah teknologi pembangkit energi terbarukan.</p><figure><img src="https://images.unsplash.com/photo-1497435334941-8c899ee9e8e9?w=1200&auto=format&fit=crop&q=80" alt="Energi Terbarukan" /><figcaption>Pembangkit listrik tenaga angin dan panel surya. (Unsplash/Karsten Würth)</figcaption></figure><p>Langkah konkret ini diharapkan dapat menjaga kenaikan temperatur global di bawah ambang batas kritis 1,5 derajat Celsius.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1497435334941-8c899ee9e8e9?w=1200&auto=format&fit=crop&q=80',
                'video_url' => null,
                'status' => 'published',
                'is_featured' => false,
                'is_breaking' => false,
                'published_at' => now()->subDays(3),
                'created_at' => now()->subDays(3),
                'meta_title' => 'KTT Perubahan Iklim Global - Internasional',
                'meta_description' => 'Komitmen global percepatan energi terbarukan disepakati.',
                'views_count' => 820,
            ],
            [
                'category_id' => $sportCat?->id ?? 5,
                'author_id' => $author->id,
                'author_source' => 'Andi Prasetyo (Liputan Khusus Olahraga)',
                'source' => 'KONI & PSSI Official',
                'title' => 'Tim Nasional Sukses Amankan Tiket Lolos ke Putaran Final Kejuaraan Asia',
                'slug' => 'tim-nasional-sukses-amankan-tiket-kejuaraan-asia',
                'excerpt' => 'Kemenangan dramatis di menit akhir babak perpanjangan waktu memastikan langkah tim melaju ke putaran bergengsi.',
                'content' => '<h2>Kemenangan Dramatis di Menit Akhir</h2><p>Stadion Utama bergemuruh saat gol penentu kemenangan tercipta melalui sundulan akurat di masa injury time. Kemenangan 2-1 atas tim unggulan memastikan posisi runner-up grup terbaik.</p><figure><img src="https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=1200&auto=format&fit=crop&q=80" alt="Pertandingan Sepak Bola" /><figcaption>Momen selebrasi skuad tim nasional usai peluit panjang berbunyi. (Unsplash/Connor Coyne)</figcaption></figure><p>Pelatih kepala mengapresiasi disiplin taktik dan semangat juang tinggi para pemain sepanjang 90 menit pertandingan.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=1200&auto=format&fit=crop&q=80',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'status' => 'published',
                'is_featured' => true,
                'is_breaking' => true,
                'published_at' => now()->subDays(4),
                'created_at' => now()->subDays(4),
                'meta_title' => 'Timnas Lolos Kejuaraan Asia - Olahraga',
                'meta_description' => 'Hasil pertandingan gemilang tim nasional menuju kejuaraan Asia.',
                'views_count' => 2100,
            ],
            [
                'category_id' => $lifeCat?->id ?? 6,
                'author_id' => $author->id,
                'author_source' => 'Clara Renata (Gaya Hidup & Wellness)',
                'source' => 'Journal of Urban Health',
                'title' => 'Tren Gaya Hidup Minimalis dan Mindfulness di Kalangan Profesional Urban',
                'slug' => 'tren-gaya-hidup-minimalis-mindfulness-profesional-urban',
                'excerpt' => 'Menjaga keseimbangan ritme kerja dan kesehatan mental kini menjadi prioritas utama generasi produktif.',
                'content' => '<h2>Menemukan Ketenangan di Tengah Hiruk-Pikuk Kota</h2><p>Gaya hidup minimalis dan meditasi singkat (mindfulness) makin digemari di kota-kota besar. Riset kesehatan membuktikan pengurangan distorsi digital 30 menit sehari mampu menurunkan kadar hormon kortisol secara signifikan.</p><figure><img src="https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=1200&auto=format&fit=crop&q=80" alt="Mindfulness dan Ketenangan" /><figcaption>Menciptakan ruang tenang untuk relaksasi mental harian. (Unsplash/Moritz Knöringer)</figcaption></figure><p>Komunitas mindfulness di berbagai sentra perkantoran pun rutin menggelar sesi pernapasan sadar setiap menjelang jam kerja.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=1200&auto=format&fit=crop&q=80',
                'video_url' => null,
                'status' => 'published',
                'is_featured' => false,
                'is_breaking' => false,
                'published_at' => now()->subDays(5),
                'created_at' => now()->subDays(5),
                'meta_title' => 'Gaya Hidup Minimalis - Gaya Hidup',
                'meta_description' => 'Panduan mindfulness dan keseimbangan ritme hidup profesional muda.',
                'views_count' => 740,
            ],
            [
                'category_id' => $techCat?->id ?? 1,
                'author_id' => $author->id,
                'author_source' => 'Rian Hidayat (Editor Teknologi)',
                'source' => 'IEEE Spectrum Tech',
                'title' => 'Inovasi Baterai Solid-State Menjanjikan Jarak Tempuh Kendaraan Listrik Lebih Jauh dan Cepat Diisi',
                'slug' => 'inovasi-baterai-solid-state-kendaraan-listrik',
                'excerpt' => 'Kerapatan energi yang lebih tinggi membuat pengisian daya kendaraan listrik berlangsung dalam hitungan menit.',
                'content' => '<h2>Lompatan Teknologi Otomotif Masa Depan</h2><p>Peneliti teknik material berhasil menguji prototipe sel baterai solid-state generasi baru. Dibandingkan baterai lithium-ion konvensional, elektrolit padat mampu menahan suhu ekstrem dan mencegah risiko degradasi dini.</p><figure><img src="https://images.unsplash.com/photo-1558441719-2347b73b8c5d?w=1200&auto=format&fit=crop&q=80" alt="Kendaraan Listrik" /><figcaption>Pengisian daya cepat generasi baru untuk mobil listrik. (Unsplash/CHUTTERSNAP)</figcaption></figure><p>Uji coba jarak tempuh menunjukkan peningkatan hingga 60% dengan durasi pengisian daya penuh hanya 12 menit.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1558441719-2347b73b8c5d?w=1200&auto=format&fit=crop&q=80',
                'video_url' => null,
                'status' => 'published',
                'is_featured' => true,
                'is_breaking' => false,
                'published_at' => now()->subDays(6),
                'created_at' => now()->subDays(6),
                'meta_title' => 'Baterai Solid State - Teknologi',
                'meta_description' => 'Terobosan baru efisiensi pengisian dan jarak tempuh kendaraan listrik.',
                'views_count' => 1100,
            ],
            [
                'category_id' => $techCat?->id ?? 1,
                'author_id' => $author->id,
                'author_source' => 'Jurnalis Berita',
                'source' => 'Internal Redaksi',
                'title' => 'Draft Desain Keamanan Siber Terintegrasi Berbasis Zero Trust',
                'slug' => 'draft-desain-keamanan-siber-zero-trust',
                'excerpt' => 'Pendekatan arsitektur keamanan modern untuk menangkal ancaman ransomware di level enterprise.',
                'content' => '<p>Naskah artikel ini masih dalam tahap peninjauan teknis oleh tim redaksi sebelum rilis.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=1200&auto=format&fit=crop&q=80',
                'video_url' => null,
                'status' => 'draft',
                'is_featured' => false,
                'is_breaking' => false,
                'published_at' => null,
                'created_at' => now()->subHours(1),
                'views_count' => 0,
            ],
            [
                'category_id' => $bizCat?->id ?? 2,
                'author_id' => $author->id,
                'author_source' => 'Dewi Anggraini (Jurnalis Ekonomi)',
                'source' => 'OJK & BEI',
                'title' => 'Analisis Kebijakan Suku Bunga dan Implikasinya pada Pergerakan Indeks Saham',
                'slug' => 'analisis-kebijakan-suku-bunga-dan-pasar-modal',
                'excerpt' => 'Review mendalam oleh editor sebelum dipublikasikan pada edisi khusus akhir pekan.',
                'content' => '<p>Draf naskah analisis ekonomi dalam proses penyuntingan tabel dan infografik pendukung.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=1200&auto=format&fit=crop&q=80',
                'video_url' => null,
                'status' => 'review',
                'is_featured' => false,
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
                        'content' => 'Ulasan yang sangat jelas, tajam, dan berimbang. Sangat bermanfaat untuk referensi terkini!',
                        'status' => 'approved',
                    ]
                );
            }
        }
    }
}
