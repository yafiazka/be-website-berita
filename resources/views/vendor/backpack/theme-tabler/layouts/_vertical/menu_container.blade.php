@if (backpack_auth()->check())
    @php
        $user = backpack_user();
    @endphp
    <aside class="navbar navbar-vertical navbar-expand-lg navbar-dark border-end" style="min-height: 100vh; z-index: 1000; box-shadow: 0 0 15px rgba(0,0,0,0.05);">
        <div class="container-fluid d-flex flex-column h-100 p-0">
            <!-- Brand Logo Header -->
            <div class="navbar-brand-container d-flex align-items-center justify-content-between w-100 px-3 py-3 border-bottom">
                <a class="navbar-brand d-flex align-items-center text-decoration-none m-0" href="{{ backpack_url('dashboard') }}">
                    <span class="avatar avatar-sm bg-primary text-white me-2 rounded-2 shadow-sm d-flex align-items-center justify-content-center">
                        <i class="la la-newspaper" style="font-size: 1.3rem;"></i>
                    </span>
                    <div>
                        <div class="fs-3 fw-bold text-reset line-height-1">Portal Berita</div>
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

            <!-- User Info & Quick Actions Footer in Sidebar -->
            <div class="navbar-footer w-100 p-3 border-top mt-auto bg-dark-subtle d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center text-truncate me-2">
                    <span class="avatar avatar-sm rounded-circle bg-primary text-white fw-bold me-2 flex-shrink-0">
                        {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                    </span>
                    <div class="text-truncate">
                        <div class="fw-semibold text-truncate small text-reset">{{ $user->name ?? 'Admin' }}</div>
                        <div class="text-secondary text-truncate" style="font-size: 0.72rem;">
                            {{ $user->roles->first()?->name ?? 'Administrator' }}
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-1">
                    @includeWhen(backpack_theme_config('options.showColorModeSwitcher'), backpack_view('layouts.partials.switch_theme'))
                    <a href="{{ backpack_url('logout') }}" class="btn btn-icon btn-ghost-danger btn-sm rounded" title="Keluar" data-bs-toggle="tooltip">
                        <i class="la la-sign-out" style="font-size: 1.2rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    </aside>
@endif
