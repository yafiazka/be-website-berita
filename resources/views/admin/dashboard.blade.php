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
                        <i class="la la-chart-line text-success"></i> Akumulasi seluruh pembaca aktif
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
                        <h3 class="card-title fw-bold m-0">Tren Pembaca & Aktivitas Harian</h3>
                        <div class="text-secondary small">Statistik penayangan 7 hari terakhir</div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary">Aktivitas Mingguan</span>
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
                    <div id="chart-categories-donut" style="min-height: 210px; width: 100%;"></div>
                    <div class="row g-2 w-100 mt-2">
                        @foreach($categoryDistribution as $cat)
                            <div class="col-6">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded bg-dark-subtle small">
                                    <span class="text-truncate me-1">{{ $cat->name }}</span>
                                    <span class="fw-bold">{{ $cat->articles_count }}</span>
                                </div>
                            </div>
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
                    <h3 class="card-title fw-bold m-0">Status Workflow Redaksi</h3>
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
                            <span class="text-secondary fw-semibold"><i class="la la-calendar-check"></i> Scheduled (Terjadwal)</span>
                            <span class="fw-bold">{{ $scheduledArticles }} ({{ $schPct }}%)</span>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-secondary" style="width: {{ $schPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Trending Articles -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3 d-flex align-items-center justify-content-between">
                    <h3 class="card-title fw-bold m-0"><i class="la la-fire text-danger me-1"></i> Artikel Terpopuler</h3>
                    <a href="{{ backpack_url('article') }}" class="btn btn-sm btn-ghost-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-hover">
                        <thead>
                            <tr>
                                <th>Judul Berita</th>
                                <th>Kategori</th>
                                <th>Penulis</th>
                                <th class="text-end">Tayangan</th>
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
        const last7DaysData = @json($last7Days);
        const categoryData = @json($categoryDistribution);

        const dates = last7DaysData.map(item => item.date);
        const views = last7DaysData.map(item => item.views);
        const articles = last7DaysData.map(item => item.articles);

        // 1. Views Trend Smooth Area Chart
        const viewsOptions = {
            chart: {
                type: 'area',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'inherit',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            series: [{
                name: 'Tayangan Pembaca',
                data: views
            }, {
                name: 'Artikel Terbit',
                data: articles
            }],
            xaxis: {
                categories: dates,
                labels: {
                    style: { colors: '#6c757d', fontSize: '12px' }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: [{
                title: { text: 'Views', style: { color: '#6c757d', fontSize: '11px' } },
                labels: {
                    style: { colors: '#6c757d' },
                    formatter: val => val >= 1000 ? (val/1000).toFixed(1) + 'k' : val
                }
            }],
            colors: ['#206bc4', '#4299e1'],
            stroke: {
                curve: 'smooth',
                width: [2.5, 2]
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.35,
                    opacityTo: 0.05,
                    stops: [0, 95]
                }
            },
            grid: {
                strokeDashArray: 4,
                borderColor: 'rgba(255, 255, 255, 0.08)'
            },
            dataLabels: { enabled: false },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: val => val.toLocaleString() + ' kali'
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                labels: { colors: '#6c757d' }
            }
        };
        new ApexCharts(document.querySelector("#chart-views-trend"), viewsOptions).render();

        // 2. Category Donut Chart
        const catLabels = categoryData.map(c => c.name);
        const catCounts = categoryData.map(c => c.articles_count);

        const donutOptions = {
            chart: {
                type: 'donut',
                height: 210,
                fontFamily: 'inherit'
            },
            series: catCounts.length > 0 ? catCounts : [1],
            labels: catLabels.length > 0 ? catLabels : ['Belum Ada Data'],
            colors: ['#206bc4', '#2fb344', '#f76707', '#d63939', '#4299e1', '#ae3ec9'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '72%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Berita',
                                formatter: () => catCounts.reduce((a, b) => a + b, 0)
                            }
                        }
                    }
                }
            },
            legend: { show: false },
            dataLabels: { enabled: false },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: val => val + ' artikel'
                }
            }
        };
        new ApexCharts(document.querySelector("#chart-categories-donut"), donutOptions).render();
    });
</script>
@endpush
@endsection
