{{-- Backpack v6 Tabler Menu Items --}}
<x-backpack::menu-item title="Dashboard" icon="la la-home" :link="backpack_url('dashboard')" />

<x-backpack::menu-separator title="MANAJEMEN KONTEN" />
<x-backpack::menu-item title="Artikel Berita" icon="la la-newspaper" :link="backpack_url('article')" />
<x-backpack::menu-item title="Kategori" icon="la la-folder-open" :link="backpack_url('category')" />
<x-backpack::menu-item title="Tags" icon="la la-tags" :link="backpack_url('tag')" />
<x-backpack::menu-item title="Komentar" icon="la la-comments" :link="backpack_url('comment')" />

<x-backpack::menu-separator title="PENGATURAN & USER" />
<x-backpack::menu-item title="Pengguna (Users)" icon="la la-user-cog" :link="backpack_url('user')" />
