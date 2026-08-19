@php
    $user = backpack_user();
@endphp
<div class="nav-item dropdown">
    <a href="#" class="nav-link d-flex lh-1 text-reset p-0 align-items-center" data-bs-toggle="dropdown" aria-label="Open user menu">
        <span class="avatar avatar-sm rounded-circle bg-primary text-white fw-bold shadow-xs">
            @if($user && $user->avatar)
                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="avatar avatar-sm rounded-circle" onerror="this.style.display='none'">
            @endif
            <span>{{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}</span>
        </span>
        <div class="d-none d-md-block ps-2 text-start">
            <div class="fw-semibold text-reset small">{{ $user->name ?? 'Admin' }}</div>
            <div class="text-secondary" style="font-size: 0.72rem;">{{ $user->roles->first()?->name ?? 'Administrator' }}</div>
        </div>
        <i class="la la-angle-down ms-2 text-secondary small d-none d-md-inline"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow-sm">
        <div class="dropdown-header">
            <div class="fw-bold">{{ $user->name ?? 'Admin' }}</div>
            <div class="text-secondary small">{{ $user->email ?? '' }}</div>
        </div>
        <div class="dropdown-divider"></div>
        <a href="{{ backpack_url('article') }}" class="dropdown-item">
            <i class="la la-newspaper me-2 text-primary"></i> Kelola Artikel
        </a>
        <a href="{{ route('admin.api_docs') }}" class="dropdown-item">
            <i class="la la-code me-2 text-info"></i> Dokumentasi API
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{ backpack_url('logout') }}" class="dropdown-item text-danger">
            <i class="la la-sign-out me-2"></i> {{ trans('backpack::base.logout') }}
        </a>
    </div>
</div>
