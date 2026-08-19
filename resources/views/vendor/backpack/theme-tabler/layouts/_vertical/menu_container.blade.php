@if (backpack_auth()->check())
    <aside class="navbar navbar-vertical navbar-expand-lg border-end bg-body" style="min-height: 100vh; z-index: 1000;">
        <div class="container-fluid d-flex flex-column h-100 p-0">
            <!-- Brand Logo Header -->
            <div class="navbar-brand-container d-flex align-items-center justify-content-between w-100 px-3 py-3 border-bottom">
                <a class="navbar-brand d-flex align-items-center text-decoration-none m-0" href="{{ backpack_url('dashboard') }}">
                    <span class="avatar avatar-sm bg-primary text-white me-2 rounded-2 shadow-sm d-flex align-items-center justify-content-center">
                        <i class="la la-newspaper" style="font-size: 1.3rem;"></i>
                    </span>
                    <div>
                        <div class="fs-3 fw-bold text-body line-height-1">Portal Berita</div>
                        <div class="text-secondary" style="font-size: 0.72rem; letter-spacing: 0.5px;">ADMIN PANEL</div>
                    </div>
                </a>
                <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse flex-grow-1 w-100 px-3 py-3" id="sidebar-menu" style="overflow-y: auto;">
                <ul class="navbar-nav w-100">
                    @include(backpack_view('inc.menu_items'))
                </ul>
            </div>

            <!-- Sidebar Footer Status -->
            <div class="navbar-footer w-100 p-3 border-top mt-auto bg-body-tertiary d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center small text-secondary">
                    <span class="status-dot status-dot-animated bg-success me-2"></span>
                    <span>API v1 • Online</span>
                </div>
                <a href="{{ route('admin.api_docs') }}" class="badge bg-primary-subtle text-primary text-decoration-none" title="Dokumentasi API">
                    REST API
                </a>
            </div>
        </div>
    </aside>
@endif
