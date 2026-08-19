@extends(backpack_view('blank'))

@section('header')
    <div class="container-xl">
        <div class="row g-2 align-items-center justify-content-between">
            <div class="col">
                <div class="page-pretitle text-uppercase text-secondary fw-semibold">
                    Developer & Integrasi
                </div>
                <h2 class="page-title d-flex align-items-center gap-2">
                    <i class="la la-code text-primary"></i> Dokumentasi REST API v1
                    <span class="badge bg-primary-subtle text-primary fs-6">{{ $totalEndpoints }} Endpoints</span>
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('admin.api_docs.postman') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                        <span>Unduh Postman Collection (.json)</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-xl mt-3">
    <!-- Search and Filter Bar -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="la la-search fs-4"></i>
                        </span>
                        <input type="text" id="apiSearchInput" class="form-control" placeholder="Cari endpoint berdasarkan path, judul, atau kata kunci...">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex flex-wrap gap-1" id="categoryFilterButtons">
                        <button type="button" class="btn btn-sm btn-outline-primary active" data-category="all">Semua ({{ $totalEndpoints }})</button>
                        @foreach($groupedEndpoints as $category => $items)
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-category="{{ Str::slug($category) }}">
                                {{ $category }} ({{ count($items) }})
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Endpoints List Grouped by Category -->
    <div class="row g-4" id="apiEndpointsContainer">
        @foreach($groupedEndpoints as $category => $items)
            <div class="col-12 category-section" data-category="{{ Str::slug($category) }}">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <h3 class="h4 mb-0 text-primary fw-bold">{{ $category }}</h3>
                    <span class="badge bg-secondary-subtle text-secondary">{{ count($items) }} Endpoint</span>
                </div>

                <div class="row g-3">
                    @foreach($items as $endpoint)
                        @php
                            $method = strtoupper($endpoint['method']);
                            $methodBadgeClass = match($method) {
                                'GET' => 'bg-success-subtle text-success border border-success-subtle',
                                'POST' => 'bg-primary-subtle text-primary border border-primary-subtle',
                                'PUT' => 'bg-warning-subtle text-warning border border-warning-subtle',
                                'PATCH' => 'bg-info-subtle text-info border border-info-subtle',
                                'DELETE' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                default => 'bg-secondary-subtle text-secondary',
                            };
                        @endphp
                        <div class="col-12 endpoint-card-wrapper" data-search="{{ strtolower($endpoint['name'] . ' ' . $endpoint['path'] . ' ' . $endpoint['method'] . ' ' . $category) }}">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header py-3 d-flex flex-wrap align-items-center justify-content-between gap-2 bg-transparent">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="badge fw-bold px-2 py-1 {{ $methodBadgeClass }}" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                            {{ $method }}
                                        </span>
                                        <code class="fs-5 fw-bold text-reset">{{ $endpoint['path'] }}</code>
                                        <span class="text-secondary small ms-2">— {{ $endpoint['name'] }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge {{ $endpoint['auth'] === 'Public' ? 'bg-teal-subtle text-teal' : 'bg-purple-subtle text-purple' }}">
                                            <i class="la {{ $endpoint['auth'] === 'Public' ? 'la-globe' : 'la-lock' }} me-1"></i>
                                            {{ $endpoint['auth'] }}
                                        </span>
                                        <button type="button" class="btn btn-sm btn-ghost-secondary btn-icon" onclick="copyToClipboard('{{ url($endpoint['path']) }}')" title="Salin Full URL" data-bs-toggle="tooltip">
                                            <i class="la la-copy fs-4"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body py-3">
                                    <p class="text-secondary mb-3">{{ $endpoint['description'] ?? '' }}</p>

                                    <div class="row g-3">
                                        @if(!empty($endpoint['params']))
                                            <div class="col-md-6">
                                                <div class="fw-semibold small text-uppercase text-secondary mb-1">Query Parameters:</div>
                                                <div class="bg-dark-subtle p-2 rounded small font-monospace">
                                                    @foreach($endpoint['params'] as $pk => $pv)
                                                        <div><span class="text-primary">{{ $pk }}</span>: <span class="text-muted">{{ is_array($pv) ? json_encode($pv) : $pv }}</span></div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if(!empty($endpoint['body']))
                                            <div class="col-md-6">
                                                <div class="fw-semibold small text-uppercase text-secondary mb-1">Request Body (JSON):</div>
                                                <pre class="bg-dark p-2 rounded text-light small mb-0" style="max-height: 180px; overflow-y: auto;"><code>{{ json_encode($endpoint['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                            </div>
                                        @endif

                                        @if(!empty($endpoint['response']))
                                            <div class="{{ empty($endpoint['body']) && empty($endpoint['params']) ? 'col-12' : 'col-md-6' }}">
                                                <div class="fw-semibold small text-uppercase text-secondary mb-1">Contoh Response (200 OK):</div>
                                                <pre class="bg-dark p-2 rounded text-light small mb-0" style="max-height: 180px; overflow-y: auto;"><code>{{ is_array($endpoint['response']) ? json_encode($endpoint['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $endpoint['response'] }}</code></pre>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('after_scripts')
<script>
    // Copy to clipboard helper
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            if (typeof Noty !== 'undefined') {
                new Noty({
                    text: 'URL Endpoint berhasil disalin ke clipboard!',
                    type: 'success',
                    timeout: 2000,
                    layout: 'topRight',
                }).show();
            } else {
                alert('Tersalin: ' + text);
            }
        });
    }

    // Real-time Search & Filter
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('apiSearchInput');
        const filterButtons = document.querySelectorAll('#categoryFilterButtons button');
        const cards = document.querySelectorAll('.endpoint-card-wrapper');
        const sections = document.querySelectorAll('.category-section');

        let activeCategory = 'all';

        function filterEndpoints() {
            const query = searchInput.value.toLowerCase().trim();

            sections.forEach(section => {
                const sectionCategory = section.getAttribute('data-category');
                const isCatMatch = (activeCategory === 'all' || activeCategory === sectionCategory);

                let visibleCardsInSection = 0;

                const sectionCards = section.querySelectorAll('.endpoint-card-wrapper');
                sectionCards.forEach(card => {
                    const searchData = card.getAttribute('data-search');
                    const isQueryMatch = !query || searchData.includes(query);

                    if (isCatMatch && isQueryMatch) {
                        card.style.display = 'block';
                        visibleCardsInSection++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (visibleCardsInSection > 0) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('input', filterEndpoints);

        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                filterButtons.forEach(b => {
                    b.classList.remove('active', 'btn-outline-primary');
                    b.classList.add('btn-outline-secondary');
                });
                this.classList.add('active', 'btn-outline-primary');
                this.classList.remove('btn-outline-secondary');
                activeCategory = this.getAttribute('data-category');
                filterEndpoints();
            });
        });
    });
</script>
@endpush
@endsection
