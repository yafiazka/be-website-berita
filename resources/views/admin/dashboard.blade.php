@extends(backpack_view('blank'))

@section('header')
    <div class="container-xl">
        <div class="row g-2 align-items-center justify-content-between">
            <div class="col">
                <div class="page-pretitle text-uppercase text-secondary fw-semibold">
                    Ringkasan & Analitik
                </div>
                <h2 class="page-title d-flex align-items-center gap-2">
                    <i class="la la-chart-pie text-primary"></i> Dashboard Redaksi & Berita
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ backpack_url('article/create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
                        <i class="la la-plus fs-4"></i>
                        <span>Tulis Artikel Baru</span>
                    </a>
                    <a href="{{ route('admin.api_docs') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                        <i class="la la-code fs-4"></i>
                        <span>Dokumentasi API</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-xl mt-3">
    <!-- Row 1: Key Metric Cards -->
    <div class="row row-deck row-cards mb-4">
        <!-- Total Articles -->
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="subheader text-uppercase text-secondary fw-semibold">Total Artikel</div>
                        <span class="avatar avatar-sm bg-primary-subtle text-primary rounded">
                            <i class="la la-newspaper fs-3"></i>
                        </span>
                    </div>
                    <div class="h1 mb-2 mt-2 fw-bold">{{ number_format($totalArticles) }}</div>
                    <div class="d-flex align-items-center gap-2 text-secondary small">
                        <span class="badge bg-success-subtle text-success">{{ $publishedArticles }} Terbit</span>
                        <span class="badge bg-warning-subtle text-warning">{{ $draftArticles }} Draft</span>
                        <span class="badge bg-info-subtle text-info">{{ $reviewArticles }} Review</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Views -->
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="subheader text-uppercase text-secondary fw-semibold">Total Pembaca (Views)</div>
                        <span class="avatar avatar-sm bg-success-subtle text-success rounded">
                            <i class="la la-eye fs-3"></i>
                        </span>
                    </div>
                    <div class="h1 mb-2 mt-2 fw-bold text-success">{{ number_format($totalViews) }}</div>
                    <div class="text-secondary small">
                        <i class="la la-arrow-up text-success"></i> Akumulasi seluruh pembaca berita
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Interaksi (Likes, Bookmarks, Comments) -->
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="subheader text-uppercase text-secondary fw-semibold">Interaksi Pembaca</div>
                        <span class="avatar avatar-sm bg-danger-subtle text-danger rounded">
                            <i class="la la-heart fs-3"></i>
                        </span>
                    </div>
                    <div class="h1 mb-2 mt-2 fw-bold">{{ number_format($totalLikes + $totalBookmarks) }}</div>
                    <div class="d-flex align-items-center gap-2 text-secondary small">
                        <span><i class="la la-heart text-danger"></i> {{ $totalLikes }} Likes</span>
                        <span>•</span>
                        <span><i class="la la-bookmark text-primary"></i> {{ $totalBookmarks }} Simpan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Komentar & Moderasi -->
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="subheader text-uppercase text-secondary fw-semibold">Total Komentar</div>
                        <span class="avatar avatar-sm bg-warning-subtle text-warning rounded">
                            <i class="la la-comments fs-3"></i>
                        </span>
                    </div>
                    <div class="h1 mb-2 mt-2 fw-bold">{{ number_format($totalComments) }}</div>
                    <div class="text-secondary small">
                        @if($pendingComments > 0)
                            <span class="badge bg-danger text-white"><i class="la la-exclamation-circle"></i> {{ $pendingComments }} Perlu Moderasi</span>
                        @else
                            <span class="badge bg-success-subtle text-success"><i class="la la-check-circle"></i> Semua Termoderasi</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Charts & Kalkulasi Interaktif -->
    <div class="row row-deck row-cards mb-4">
        <!-- Main Line/Area Trend Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="card-title fw-bold m-0">Tren Pembaca & Publikasi Berita (7 Hari Terakhir)</h3>
                        <div class="text-secondary small">Aktivitas tayangan pembaca harian</div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary">Real-time</span>
                </div>
                <div class="card-body">
                    <div id="chart-views-trend" style="min-height: 280px;"></div>
                </div>
            </div>
        </div>

        <!-- Category Distribution Donut Chart -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h3 class="card-title fw-bold m-0">Distribusi Kategori Berita</h3>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div id="chart-categories-donut" style="min-height: 230px; width: 100%;"></div>
                    <div class="d-flex flex-wrap justify-content-center gap-2 mt-3 small">
                        @foreach($categoryDistribution as $cat)
                            <span class="badge bg-secondary-subtle text-secondary">
                                {{ $cat->name }}: {{ $cat->articles_count }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Status Breakdown & Extra Stats -->
    <div class="row row-deck row-cards mb-4">
        <!-- Status Articles Progress -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h3 class="card-title fw-bold m-0">Status Workflow Artikel</h3>
                </div>
                <div class="card-body">
                    @php
                        $pubPct = $totalArticles > 0 ? round(($publishedArticles / $totalArticles) * 100) : 0;
                        $draftPct = $totalArticles > 0 ? round(($draftArticles / $totalArticles) * 100) : 0;
                        $revPct = $totalArticles > 0 ? round(($reviewArticles / $totalArticles) * 100) : 0;
                        $schPct = $totalArticles > 0 ? round(($scheduledArticles / $totalArticles) * 100) : 0;
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1 small">
                            <span class="text-success fw-semibold"><i class="la la-check-circle"></i> Published (Terbit)</span>
                            <span class="fw-bold">{{ $publishedArticles }} ({{ $pubPct }}%)</span>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-success" style="width: {{ $pubPct }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1 small">
                            <span class="text-warning fw-semibold"><i class="la la-edit"></i> Draft (Konsep)</span>
                            <span class="fw-bold">{{ $draftArticles }} ({{ $draftPct }}%)</span>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-warning" style="width: {{ $draftPct }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1 small">
                            <span class="text-info fw-semibold"><i class="la la-clock"></i> In Review (Peninjauan)</span>
                            <span class="fw-bold">{{ $reviewArticles }} ({{ $revPct }}%)</span>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-info" style="width: {{ $revPct }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between mb-1 small">
                            <span class="text-purple fw-semibold"><i class="la la-calendar-check"></i> Scheduled (Terjadwal)</span>
                            <span class="fw-bold">{{ $scheduledArticles }} ({{ $schPct }}%)</span>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-purple" style="width: {{ $schPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Trending Articles -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3 d-flex align-items-center justify-content-between">
                    <h3 class="card-title fw-bold m-0"><i class="la la-fire text-danger me-1"></i> Artikel Paling Populer (Trending)</h3>
                    <a href="{{ backpack_url('article') }}" class="btn btn-sm btn-ghost-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-hover">
                        <thead>
                            <tr>
                                <th>Judul Artikel</th>
                                <th>Kategori</th>
                                <th>Penulis</th>
                                <th class="text-end">Tayangan (Views)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topArticles as $art)
                                <tr>
                                    <td>
                                        <a href="{{ backpack_url('article/' . $art->id . '/edit') }}" class="text-reset fw-semibold text-truncate d-inline-block" style="max-width: 320px;">
                                            {{ $art->title }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary">{{ $art->category->name ?? '-' }}</span>
                                    </td>
                                    <td class="text-secondary small">{{ $art->author->name ?? 'Redaksi' }}</td>
                                    <td class="text-end fw-bold text-success">{{ number_format($art->views_count) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-secondary py-3">Belum ada artikel yang diterbitkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after_scripts')
<!-- ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Data from Controller
        const last7DaysData = @json($last7Days);
        const categoryData = @json($categoryDistribution);

        const dates = last7DaysData.map(item => item.date);
        const views = last7DaysData.map(item => item.views);
        const articles = last7DaysData.map(item => item.articles);

        // 1. Views Trend Area Chart
        const viewsOptions = {
            chart: {
                type: 'area',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'inherit',
                animations: { enabled: true }
            },
            series: [{
                name: 'Tayangan Pembaca',
                data: views
            }, {
                name: 'Artikel Dibuat',
                data: articles
            }],
            xaxis: {
                categories: dates,
                labels: { style: { colors: '#6c757d' } }
            },
            yaxis: {
                labels: { style: { colors: '#6c757d' } }
            },
            colors: ['#206bc4', '#4299e1'],
            stroke: {
                curve: 'smooth',
                width: [3, 2]
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            tooltip: {
                theme: 'dark'
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right'
            }
        };
        new ApexCharts(document.querySelector("#chart-views-trend"), viewsOptions).render();

        // 2. Category Donut Chart
        const catLabels = categoryData.map(c => c.name);
        const catCounts = categoryData.map(c => c.articles_count);

        const donutOptions = {
            chart: {
                type: 'donut',
                height: 230,
                fontFamily: 'inherit'
            },
            series: catCounts.length > 0 ? catCounts : [1],
            labels: catLabels.length > 0 ? catLabels : ['Belum Ada Kategori'],
            colors: ['#206bc4', '#2fb344', '#f76707', '#d63939', '#4299e1', '#ae3ec9'],
            legend: { show: false },
            dataLabels: { enabled: true },
            tooltip: { theme: 'dark' }
        };
        new ApexCharts(document.querySelector("#chart-categories-donut"), donutOptions).render();
    });
</script>
@endpush
@endsection
