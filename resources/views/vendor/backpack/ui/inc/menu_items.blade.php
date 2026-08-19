{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>

<li class="nav-separator">MANAJEMEN BERITA</li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('article') }}"><i class="la la-newspaper nav-icon"></i> <span>Artikel Berita</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('category') }}"><i class="la la-list nav-icon"></i> <span>Kategori</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('tag') }}"><i class="la la-tag nav-icon"></i> <span>Tags</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('comment') }}"><i class="la la-comments nav-icon"></i> <span>Komentar</span></a></li>

<li class="nav-separator">PENGATURAN & USER</li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="la la-users nav-icon"></i> <span>Pengguna (Users)</span></a></li>
