<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" dir="{{ backpack_theme_config('html_direction') }}">

<head>
    @include(backpack_view('inc.head'))
</head>

<body class="{{ backpack_theme_config('classes.body') }}" bp-layout="vertical">

@include(backpack_view('layouts.partials.light_dark_mode_logic'))

<div class="page">

    {{-- 1. Left Vertical Sidebar (Main Feature Navigation) --}}
    @include(backpack_view('layouts._vertical.menu_container'))

    <div class="page-wrapper">

        {{-- 2. Top Navbar Header (User profile, theme mode switcher, quick shortcuts) --}}
        @if (backpack_auth()->check())
            <header class="navbar navbar-expand-md d-print-none border-bottom px-3 py-2 bg-body">
                <div class="container-fluid d-flex align-items-center justify-content-between">
                    <!-- Left: Mobile toggle & Breadcrumb / Quick Link -->
                    <div class="d-flex align-items-center gap-2">
                        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-secondary d-none d-sm-inline-flex align-items-center gap-1 rounded-pill px-3">
                            <i class="la la-external-link fs-4"></i>
                            <span>Kunjungi Website API</span>
                        </a>
                    </div>

                    <!-- Right: Theme Switcher & User Profile Dropdown -->
                    <div class="navbar-nav flex-row align-items-center gap-3">
                        <!-- Dark / Light Theme Mode Switcher -->
                        <div class="nav-item">
                            @includeWhen(backpack_theme_config('options.showColorModeSwitcher'), backpack_view('layouts.partials.switch_theme'))
                        </div>

                        <!-- User Profile Dropdown -->
                        @include(backpack_view('inc.menu_user_dropdown'))
                    </div>
                </div>
            </header>
        @endif

        {{-- 3. Main Page Content --}}
        <div class="page-body">
            <main class="{{ backpack_theme_config('options.useFluidContainers') ? 'container-fluid' : 'container-xl' }}">

                @yield('before_breadcrumbs_widgets')
                @includeWhen(isset($breadcrumbs), backpack_view('inc.breadcrumbs'))
                @yield('after_breadcrumbs_widgets')
                @yield('header')

                <div class="container-fluid animated fadeIn">
                    @yield('before_content_widgets')
                    @yield('content')
                    @yield('after_content_widgets')
                </div>
            </main>
        </div>

        @include(backpack_view('inc.footer'))
    </div>
</div>

@yield('before_scripts')
@stack('before_scripts')

@include(backpack_view('inc.scripts'))
@include(backpack_view('inc.theme_scripts'))

@yield('after_scripts')
@stack('after_scripts')
</body>
</html>
