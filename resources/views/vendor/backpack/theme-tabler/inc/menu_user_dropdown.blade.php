@php
    $user = backpack_user();
@endphp
<div class="nav-item dropdown">
    <a href="#" class="nav-link d-flex lh-1 text-reset p-0 align-items-center" data-bs-toggle="dropdown" aria-label="Open user menu">
        <span class="avatar avatar-sm rounded-circle bg-secondary-subtle text-secondary border">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user m-0" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
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
