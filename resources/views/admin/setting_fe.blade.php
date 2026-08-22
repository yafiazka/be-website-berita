@extends(backpack_view('blank'))

@section('header')
    <div class="container-xl">
        <div class="row g-2 align-items-center justify-content-between">
            <div class="col">
                <div class="page-pretitle text-uppercase text-secondary fw-semibold">
                    Pengaturan Template & Tampilan
                </div>
                <h2 class="page-title d-flex align-items-center gap-2">
                    <i class="la la-sliders-h text-primary"></i> Setting Fe Berita
                    <span class="badge bg-success-subtle text-success fs-6">Live Synchronized</span>
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ url('/api/v1/settings') }}" target="_blank" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                        <i class="la la-external-link-alt"></i>
                        <span>Lihat Raw JSON API</span>
                    </a>
                    <button type="submit" form="settingFeForm" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
                        <i class="la la-save"></i>
                        <span>Simpan Pengaturan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-xl mt-3">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="la la-check-circle fs-3 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form id="settingFeForm" action="{{ route('admin.setting_fe.update') }}" method="POST">
        @csrf

        <div class="row g-4">
            <!-- Left Sidebar Navigation Tabs -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body p-2">
                        <div class="nav flex-column nav-pills" id="settings-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active text-start py-2 px-3 d-flex align-items-center gap-2 mb-1" id="tab-site-tab" data-bs-toggle="pill" data-bs-target="#tab-site" type="button" role="tab">
                                <i class="la la-globe fs-4 text-primary"></i>
                                <div>
                                    <div class="fw-semibold">Identitas & SEO</div>
                                    <small class="text-secondary" style="font-size: 0.75rem;">Nama, logo, favicon, SEO</small>
                                </div>
                            </button>

                            <button class="nav-link text-start py-2 px-3 d-flex align-items-center gap-2 mb-1" id="tab-theme-tab" data-bs-toggle="pill" data-bs-target="#tab-theme" type="button" role="tab">
                                <i class="la la-palette fs-4 text-info"></i>
                                <div>
                                    <div class="fw-semibold">Tema & Warna</div>
                                    <small class="text-secondary" style="font-size: 0.75rem;">Palet warna, dark mode</small>
                                </div>
                            </button>

                            <button class="nav-link text-start py-2 px-3 d-flex align-items-center gap-2 mb-1" id="tab-features-tab" data-bs-toggle="pill" data-bs-target="#tab-features" type="button" role="tab">
                                <i class="la la-cubes fs-4 text-success"></i>
                                <div>
                                    <div class="fw-semibold">Fitur & Modul Berita</div>
                                    <small class="text-secondary" style="font-size: 0.75rem;">Ticker, trending, komentar</small>
                                </div>
                            </button>

                            <button class="nav-link text-start py-2 px-3 d-flex align-items-center gap-2 mb-1" id="tab-contact-tab" data-bs-toggle="pill" data-bs-target="#tab-contact" type="button" role="tab">
                                <i class="la la-phone-volume fs-4 text-warning"></i>
                                <div>
                                    <div class="fw-semibold">Kontak & Sosmed</div>
                                    <small class="text-secondary" style="font-size: 0.75rem;">Alamat, WA, link medsos</small>
                                </div>
                            </button>

                            <button class="nav-link text-start py-2 px-3 d-flex align-items-center gap-2 mb-1" id="tab-nav-tab" data-bs-toggle="pill" data-bs-target="#tab-nav" type="button" role="tab">
                                <i class="la la-compass fs-4 text-purple"></i>
                                <div>
                                    <div class="fw-semibold">Navigasi & Footer</div>
                                    <small class="text-secondary" style="font-size: 0.75rem;">Menu link, about, copyright</small>
                                </div>
                            </button>

                            <button class="nav-link text-start py-2 px-3 d-flex align-items-center gap-2 mb-1" id="tab-ads-tab" data-bs-toggle="pill" data-bs-target="#tab-ads" type="button" role="tab">
                                <i class="la la-ad fs-4 text-danger"></i>
                                <div>
                                    <div class="fw-semibold">Iklan & Analytics</div>
                                    <small class="text-secondary" style="font-size: 0.75rem;">Banner ads, Google Analytics</small>
                                </div>
                            </button>

                            <button class="nav-link text-start py-2 px-3 d-flex align-items-center gap-2" id="tab-json-tab" data-bs-toggle="pill" data-bs-target="#tab-json" type="button" role="tab">
                                <i class="la la-code fs-4 text-secondary"></i>
                                <div>
                                    <div class="fw-semibold">Live JSON Output</div>
                                    <small class="text-secondary" style="font-size: 0.75rem;">Pratinjau data API frontend</small>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content Pane -->
            <div class="col-md-9">
                <div class="tab-content" id="settings-tabContent">

                    <!-- TAB 1: IDENTITAS & SEO -->
                    <div class="tab-pane fade show active" id="tab-site" role="tabpanel">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent border-bottom">
                                <h3 class="card-title fw-bold">
                                    <i class="la la-globe text-primary me-2"></i> Identitas Portal Berita
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label required fw-semibold">Nama Website / Media</label>
                                        <input type="text" name="site[name]" class="form-control" value="{{ $settings['site']['name'] ?? 'Berita Satu Nusa' }}" required>
                                        <small class="text-secondary">Nama utama portal berita yang tampil di header dan title browser.</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Tagline / Slogan</label>
                                        <input type="text" name="site[tagline]" class="form-control" value="{{ $settings['site']['tagline'] ?? '' }}">
                                        <small class="text-secondary">Slogan portal berita.</small>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Deskripsi Website (Meta SEO Description)</label>
                                        <textarea name="site[description]" rows="3" class="form-control">{{ $settings['site']['description'] ?? '' }}</textarea>
                                        <small class="text-secondary">Deskripsi default yang dibaca oleh mesin pencari Google.</small>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Keywords SEO</label>
                                        <input type="text" name="site[keywords]" class="form-control" value="{{ $settings['site']['keywords'] ?? '' }}">
                                        <small class="text-secondary">Pisahkan dengan tanda koma (,).</small>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">URL Logo Header</label>
                                        <input type="text" name="site[logo_url]" class="form-control" value="{{ $settings['site']['logo_url'] ?? '/template/images/logo.svg' }}">
                                        <small class="text-secondary">Path atau URL gambar logo utama (Light background).</small>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">URL Logo Footer (White/Light)</label>
                                        <input type="text" name="site[logo_footer_url]" class="form-control" value="{{ $settings['site']['logo_footer_url'] ?? '/template/images/white_logo.svg' }}">
                                        <small class="text-secondary">Path logo untuk area footer berlatar gelap.</small>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">URL Favicon</label>
                                        <input type="text" name="site[favicon_url]" class="form-control" value="{{ $settings['site']['favicon_url'] ?? '/favicon.ico' }}">
                                        <small class="text-secondary">Icon browser tab (.ico atau .png).</small>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Nama Redaksi / Author</label>
                                        <input type="text" name="site[author]" class="form-control" value="{{ $settings['site']['author'] ?? 'Redaksi Berita Satu Nusa' }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Bahasa (ISO Code)</label>
                                        <input type="text" name="site[language]" class="form-control" value="{{ $settings['site']['language'] ?? 'id' }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Zona Waktu (Timezone)</label>
                                        <input type="text" name="site[timezone]" class="form-control" value="{{ $settings['site']['timezone'] ?? 'Asia/Jakarta' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: TEMA & WARNA -->
                    <div class="tab-pane fade" id="tab-theme" role="tabpanel">
                        <!-- Pengaturan Global Mode -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent border-bottom">
                                <h3 class="card-title fw-bold">
                                    <i class="la la-adjust text-info me-2"></i> Konfigurasi Dasar Tema
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Default Mode Tampilan</label>
                                        <select name="theme[default_mode]" class="form-select">
                                            <option value="light" {{ ($settings['theme']['default_mode'] ?? 'light') === 'light' ? 'selected' : '' }}>☀️ Light Mode (Mode Terang)</option>
                                            <option value="dark" {{ ($settings['theme']['default_mode'] ?? '') === 'dark' ? 'selected' : '' }}>🌙 Dark Mode (Mode Gelap)</option>
                                        </select>
                                        <small class="text-secondary">Mode default saat pembaca pertama kali membuka website.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-check form-switch cursor-pointer mt-3">
                                            <input class="form-check-input" type="checkbox" name="theme[enable_theme_toggle]" value="1" {{ !empty($settings['theme']['enable_theme_toggle']) ? 'checked' : '' }}>
                                            <span class="form-check-label fw-semibold">Aktifkan Tombol Pengalih Mode (Theme Switcher) di Frontend</span>
                                        </label>
                                        <small class="text-secondary d-block">Memberikan kebebasan pembaca mengganti mode terang/gelap.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2 Kolom: Palet Light Mode & Palet Dark Mode -->
                        <div class="row g-4">
                            <!-- ☀️ LIGHT MODE PALETTE -->
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100" style="background: #ffffff; border-top: 4px solid #d62828 !important;">
                                    <div class="card-header bg-light border-bottom d-flex align-items-center justify-content-between">
                                        <h4 class="card-title fw-bold text-dark m-0">
                                            ☀️ Palet Warna Light Mode
                                        </h4>
                                        <span class="badge bg-danger-subtle text-danger">Terang & Bersih</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Primary Color (Merah)</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['light']['primary_color'] ?? '#d62828' }}" oninput="document.getElementById('lightPrimaryHex').value = this.value">
                                                    <input type="text" id="lightPrimaryHex" name="theme[light][primary_color]" class="form-control font-monospace" value="{{ $settings['theme']['light']['primary_color'] ?? '#d62828' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Primary Hover</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['light']['primary_hover'] ?? '#b22222' }}" oninput="document.getElementById('lightHoverHex').value = this.value">
                                                    <input type="text" id="lightHoverHex" name="theme[light][primary_hover]" class="form-control font-monospace" value="{{ $settings['theme']['light']['primary_hover'] ?? '#b22222' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Secondary (Navy)</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['light']['secondary_color'] ?? '#003049' }}" oninput="document.getElementById('lightSecHex').value = this.value">
                                                    <input type="text" id="lightSecHex" name="theme[light][secondary_color]" class="form-control font-monospace" value="{{ $settings['theme']['light']['secondary_color'] ?? '#003049' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Accent (Orange)</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['light']['accent_color'] ?? '#f77f00' }}" oninput="document.getElementById('lightAccHex').value = this.value">
                                                    <input type="text" id="lightAccHex" name="theme[light][accent_color]" class="form-control font-monospace" value="{{ $settings['theme']['light']['accent_color'] ?? '#f77f00' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Halaman Background</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['light']['background'] ?? '#ffffff' }}" oninput="document.getElementById('lightBgHex').value = this.value">
                                                    <input type="text" id="lightBgHex" name="theme[light][background]" class="form-control font-monospace" value="{{ $settings['theme']['light']['background'] ?? '#ffffff' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Card / Surface Bg</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['light']['surface_bg'] ?? '#ffffff' }}" oninput="document.getElementById('lightCardBgHex').value = this.value">
                                                    <input type="text" id="lightCardBgHex" name="theme[light][surface_bg]" class="form-control font-monospace" value="{{ $settings['theme']['light']['surface_bg'] ?? '#ffffff' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Text Utama (Dark)</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['light']['text_primary'] ?? '#0f172a' }}" oninput="document.getElementById('lightTextPriHex').value = this.value">
                                                    <input type="text" id="lightTextPriHex" name="theme[light][text_primary]" class="form-control font-monospace" value="{{ $settings['theme']['light']['text_primary'] ?? '#0f172a' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Border Garis</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['light']['border_color'] ?? '#e2e8f0' }}" oninput="document.getElementById('lightBorderHex').value = this.value">
                                                    <input type="text" id="lightBorderHex" name="theme[light][border_color]" class="form-control font-monospace" value="{{ $settings['theme']['light']['border_color'] ?? '#e2e8f0' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Footer Background</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['light']['footer_bg'] ?? '#1e293b' }}" oninput="document.getElementById('lightFooterBgHex').value = this.value">
                                                    <input type="text" id="lightFooterBgHex" name="theme[light][footer_bg]" class="form-control font-monospace" value="{{ $settings['theme']['light']['footer_bg'] ?? '#1e293b' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Footer Text Color</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['light']['footer_text'] ?? '#cbd5e1' }}" oninput="document.getElementById('lightFooterTextHex').value = this.value">
                                                    <input type="text" id="lightFooterTextHex" name="theme[light][footer_text]" class="form-control font-monospace" value="{{ $settings['theme']['light']['footer_text'] ?? '#cbd5e1' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 🌙 DARK MODE PALETTE -->
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100" style="background: #111827; color: #f8fafc; border-top: 4px solid #ef4444 !important;">
                                    <div class="card-header border-bottom d-flex align-items-center justify-content-between" style="background: #0f172a; border-color: #1f2937 !important;">
                                        <h4 class="card-title fw-bold text-white m-0">
                                            🌙 Palet Warna Dark Mode
                                        </h4>
                                        <span class="badge bg-warning-subtle text-warning">Kontras & Nyaman di Mata</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold text-light">Primary Color (Coral Red)</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['dark']['primary_color'] ?? '#ef4444' }}" oninput="document.getElementById('darkPrimaryHex').value = this.value">
                                                    <input type="text" id="darkPrimaryHex" name="theme[dark][primary_color]" class="form-control font-monospace bg-dark text-white border-secondary" value="{{ $settings['theme']['dark']['primary_color'] ?? '#ef4444' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold text-light">Primary Hover</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['dark']['primary_hover'] ?? '#f87171' }}" oninput="document.getElementById('darkHoverHex').value = this.value">
                                                    <input type="text" id="darkHoverHex" name="theme[dark][primary_hover]" class="form-control font-monospace bg-dark text-white border-secondary" value="{{ $settings['theme']['dark']['primary_hover'] ?? '#f87171' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold text-light">Secondary (Sky Blue)</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['dark']['secondary_color'] ?? '#38bdf8' }}" oninput="document.getElementById('darkSecHex').value = this.value">
                                                    <input type="text" id="darkSecHex" name="theme[dark][secondary_color]" class="form-control font-monospace bg-dark text-white border-secondary" value="{{ $settings['theme']['dark']['secondary_color'] ?? '#38bdf8' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold text-light">Accent (Amber Gold)</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['dark']['accent_color'] ?? '#fbbf24' }}" oninput="document.getElementById('darkAccHex').value = this.value">
                                                    <input type="text" id="darkAccHex" name="theme[dark][accent_color]" class="form-control font-monospace bg-dark text-white border-secondary" value="{{ $settings['theme']['dark']['accent_color'] ?? '#fbbf24' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold text-light">Halaman Background (Slate 900)</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['dark']['background'] ?? '#0f172a' }}" oninput="document.getElementById('darkBgHexVal').value = this.value">
                                                    <input type="text" id="darkBgHexVal" name="theme[dark][background]" class="form-control font-monospace bg-dark text-white border-secondary" value="{{ $settings['theme']['dark']['background'] ?? '#0f172a' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold text-light">Card / Surface Bg (Slate 800)</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['dark']['surface_bg'] ?? '#1e293b' }}" oninput="document.getElementById('darkCardBgHex').value = this.value">
                                                    <input type="text" id="darkCardBgHex" name="theme[dark][surface_bg]" class="form-control font-monospace bg-dark text-white border-secondary" value="{{ $settings['theme']['dark']['surface_bg'] ?? '#1e293b' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold text-light">Text Utama (Slate 50)</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['dark']['text_primary'] ?? '#f8fafc' }}" oninput="document.getElementById('darkTextPriHex').value = this.value">
                                                    <input type="text" id="darkTextPriHex" name="theme[dark][text_primary]" class="form-control font-monospace bg-dark text-white border-secondary" value="{{ $settings['theme']['dark']['text_primary'] ?? '#f8fafc' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold text-light">Border Garis (Slate 700)</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['dark']['border_color'] ?? '#334155' }}" oninput="document.getElementById('darkBorderHex').value = this.value">
                                                    <input type="text" id="darkBorderHex" name="theme[dark][border_color]" class="form-control font-monospace bg-dark text-white border-secondary" value="{{ $settings['theme']['dark']['border_color'] ?? '#334155' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold text-light">Footer Background</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['dark']['footer_bg'] ?? '#080c14' }}" oninput="document.getElementById('darkFooterBgHex').value = this.value">
                                                    <input type="text" id="darkFooterBgHex" name="theme[dark][footer_bg]" class="form-control font-monospace bg-dark text-white border-secondary" value="{{ $settings['theme']['dark']['footer_bg'] ?? '#080c14' }}">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label small fw-semibold text-light">Footer Text Color</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="color" class="form-control form-control-color" value="{{ $settings['theme']['dark']['footer_text'] ?? '#94a3b8' }}" oninput="document.getElementById('darkFooterTextHex').value = this.value">
                                                    <input type="text" id="darkFooterTextHex" name="theme[dark][footer_text]" class="form-control font-monospace bg-dark text-white border-secondary" value="{{ $settings['theme']['dark']['footer_text'] ?? '#94a3b8' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- TAB 3: FITUR & MODUL BERITA -->
                    <div class="tab-pane fade" id="tab-features" role="tabpanel">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent border-bottom">
                                <h3 class="card-title fw-bold">
                                    <i class="la la-cubes text-success me-2"></i> Fitur & Modul Berita Homepage
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <!-- Breaking News -->
                                    <div class="col-12 p-3 border rounded bg-light-subtle">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="fw-bold fs-5 text-body">
                                                <i class="la la-bolt text-warning me-1"></i> Breaking News Ticker (Berita Kilat)
                                            </div>
                                            <label class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" name="features[breaking_news_ticker][enabled]" value="1" {{ !empty($settings['features']['breaking_news_ticker']['enabled']) ? 'checked' : '' }}>
                                                <span class="form-check-label">Aktifkan Ticker</span>
                                            </label>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Judul Label Ticker</label>
                                                <input type="text" name="features[breaking_news_ticker][title]" class="form-control" value="{{ $settings['features']['breaking_news_ticker']['title'] ?? 'Breaking News' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Kecepatan Autoplay (milidetik)</label>
                                                <input type="number" name="features[breaking_news_ticker][autoplay_speed]" class="form-control" value="{{ $settings['features']['breaking_news_ticker']['autoplay_speed'] ?? 4000 }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Trending Topics -->
                                    <div class="col-12 p-3 border rounded bg-light-subtle">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="fw-bold fs-5 text-body">
                                                <i class="la la-fire text-danger me-1"></i> Bagian Trending Topics
                                            </div>
                                            <label class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" name="features[trending_section][enabled]" value="1" {{ !empty($settings['features']['trending_section']['enabled']) ? 'checked' : '' }}>
                                                <span class="form-check-label">Aktifkan Section</span>
                                            </label>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Judul Section</label>
                                                <input type="text" name="features[trending_section][title]" class="form-control" value="{{ $settings['features']['trending_section']['title'] ?? 'Trending Topics' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Batas Jumlah Artikel (Limit)</label>
                                                <input type="number" name="features[trending_section][limit]" class="form-control" value="{{ $settings['features']['trending_section']['limit'] ?? 6 }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Video Berita -->
                                    <div class="col-12 p-3 border rounded bg-light-subtle">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="fw-bold fs-5 text-body">
                                                <i class="la la-play-circle text-primary me-1"></i> Section Video Berita
                                            </div>
                                            <label class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" name="features[video_news_section][enabled]" value="1" {{ !empty($settings['features']['video_news_section']['enabled']) ? 'checked' : '' }}>
                                                <span class="form-check-label">Aktifkan Video</span>
                                            </label>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Judul Section</label>
                                                <input type="text" name="features[video_news_section][title]" class="form-control" value="{{ $settings['features']['video_news_section']['title'] ?? 'Video Berita Terpopuler' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Batas Jumlah Video (Limit)</label>
                                                <input type="number" name="features[video_news_section][limit]" class="form-control" value="{{ $settings['features']['video_news_section']['limit'] ?? 4 }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Newsletter & Komentar -->
                                    <div class="col-md-6 p-3 border rounded bg-light-subtle">
                                        <div class="fw-bold fs-5 mb-2"><i class="la la-envelope me-1"></i> Widget Newsletter</div>
                                        <div class="mb-3">
                                            <label class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="features[newsletter][enabled]" value="1" {{ !empty($settings['features']['newsletter']['enabled']) ? 'checked' : '' }}>
                                                <span class="form-check-label">Tampilkan Form Buletin Newsletter</span>
                                            </label>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Judul Buletin</label>
                                            <input type="text" name="features[newsletter][title]" class="form-control" value="{{ $settings['features']['newsletter']['title'] ?? 'Berlangganan Buletin Berita' }}">
                                        </div>
                                        <div>
                                            <label class="form-label">Deskripsi Buletin</label>
                                            <input type="text" name="features[newsletter][description]" class="form-control" value="{{ $settings['features']['newsletter']['description'] ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6 p-3 border rounded bg-light-subtle">
                                        <div class="fw-bold fs-5 mb-2"><i class="la la-comments me-1"></i> Pengaturan Komentar & Baca</div>
                                        <div class="mb-2">
                                            <label class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="features[comments][enabled]" value="1" {{ !empty($settings['features']['comments']['enabled']) ? 'checked' : '' }}>
                                                <span class="form-check-label">Aktifkan Kolom Komentar di Artikel</span>
                                            </label>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="features[comments][require_moderation]" value="1" {{ !empty($settings['features']['comments']['require_moderation']) ? 'checked' : '' }}>
                                                <span class="form-check-label">Wajibkan Moderasi Sebelum Tampil</span>
                                            </label>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="features[comments][allow_guest]" value="1" {{ !empty($settings['features']['comments']['allow_guest']) ? 'checked' : '' }}>
                                                <span class="form-check-label">Izinkan Komentar Tamu (Guest)</span>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="features[reading_time][enabled]" value="1" {{ !empty($settings['features']['reading_time']['enabled']) ? 'checked' : '' }}>
                                                <span class="form-check-label">Tampilkan Estimasi Waktu Baca (Reading Time)</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: KONTAK & SOSMED -->
                    <div class="tab-pane fade" id="tab-contact" role="tabpanel">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent border-bottom">
                                <h3 class="card-title fw-bold">
                                    <i class="la la-phone-volume text-warning me-2"></i> Kontak Redaksi & Tautan Sosial Media
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12"><h4 class="text-secondary fw-semibold border-bottom pb-2">Kontak Redaksi</h4></div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Email Redaksi</label>
                                        <input type="email" name="contact[email]" class="form-control" value="{{ $settings['contact']['email'] ?? 'redaksi@beritasatunusa.id' }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Telepon Kantor</label>
                                        <input type="text" name="contact[phone]" class="form-control" value="{{ $settings['contact']['phone'] ?? '+62 21 555 1234' }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">WhatsApp Redaksi</label>
                                        <input type="text" name="contact[whatsapp]" class="form-control" value="{{ $settings['contact']['whatsapp'] ?? '+62 812 3456 7890' }}">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Alamat Kantor Redaksi</label>
                                        <textarea name="contact[address]" rows="2" class="form-control">{{ $settings['contact']['address'] ?? '' }}</textarea>
                                    </div>

                                    <div class="col-12 mt-4"><h4 class="text-secondary fw-semibold border-bottom pb-2">Tautan Media Sosial</h4></div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold"><i class="la la-facebook text-primary me-1"></i> Facebook URL</label>
                                        <input type="url" name="social_links[facebook]" class="form-control" value="{{ $settings['social_links']['facebook'] ?? '' }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold"><i class="la la-twitter text-info me-1"></i> Twitter / X URL</label>
                                        <input type="url" name="social_links[twitter]" class="form-control" value="{{ $settings['social_links']['twitter'] ?? '' }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold"><i class="la la-instagram text-danger me-1"></i> Instagram URL</label>
                                        <input type="url" name="social_links[instagram]" class="form-control" value="{{ $settings['social_links']['instagram'] ?? '' }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold"><i class="la la-youtube text-danger me-1"></i> YouTube URL</label>
                                        <input type="url" name="social_links[youtube]" class="form-control" value="{{ $settings['social_links']['youtube'] ?? '' }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold"><i class="la la-tiktok text-dark me-1"></i> TikTok URL</label>
                                        <input type="url" name="social_links[tiktok]" class="form-control" value="{{ $settings['social_links']['tiktok'] ?? '' }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold"><i class="la la-linkedin text-primary me-1"></i> LinkedIn URL</label>
                                        <input type="url" name="social_links[linkedin]" class="form-control" value="{{ $settings['social_links']['linkedin'] ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 5: NAVIGASI & FOOTER -->
                    <div class="tab-pane fade" id="tab-nav" role="tabpanel">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent border-bottom">
                                <h3 class="card-title fw-bold">
                                    <i class="la la-compass text-purple me-2"></i> Navigasi Menu & Teks Footer
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Teks 'Tentang Kami' di Footer</label>
                                        <textarea name="footer[about_text]" rows="3" class="form-control">{{ $settings['footer']['about_text'] ?? '' }}</textarea>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Teks Copyright Footer</label>
                                        <input type="text" name="footer[copyright_text]" class="form-control" value="{{ $settings['footer']['copyright_text'] ?? '© 2026 Berita Satu Nusa. Seluruh hak cipta dilindungi undang-undang.' }}">
                                    </div>

                                    <div class="col-12 mt-3">
                                        <label class="form-label fw-semibold">Menu Navigasi Topbar (Format JSON: [{"label":"...", "url":"..."}])</label>
                                        <textarea name="navigation_topbar_json" rows="4" class="form-control font-monospace">{{ json_encode($settings['navigation']['topbar_menu'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Menu Footer Quick Links (JSON)</label>
                                        <textarea name="navigation_footer_quick_json" rows="4" class="form-control font-monospace">{{ json_encode($settings['navigation']['footer_menu_quick_links'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Menu Footer Legal & Kebijakan (JSON)</label>
                                        <textarea name="navigation_footer_legal_json" rows="4" class="form-control font-monospace">{{ json_encode($settings['navigation']['footer_menu_legal'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 6: IKLAN & ANALYTICS -->
                    <div class="tab-pane fade" id="tab-ads" role="tabpanel">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent border-bottom">
                                <h3 class="card-title fw-bold">
                                    <i class="la la-ad text-danger me-2"></i> Slot Banner Iklan & Pelacak Web
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <!-- Header Banner -->
                                    <div class="col-12 p-3 border rounded bg-light-subtle">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="fw-bold">1. Banner Iklan Header (Top Banner 728x90)</div>
                                            <label class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" name="ads[header_banner][enabled]" value="1" {{ !empty($settings['ads']['header_banner']['enabled']) ? 'checked' : '' }}>
                                                <span class="form-check-label">Aktif</span>
                                            </label>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-6"><input type="text" name="ads[header_banner][image_url]" class="form-control" placeholder="URL Gambar Banner" value="{{ $settings['ads']['header_banner']['image_url'] ?? '' }}"></div>
                                            <div class="col-md-6"><input type="text" name="ads[header_banner][target_url]" class="form-control" placeholder="Target URL saat diklik (https://...)" value="{{ $settings['ads']['header_banner']['target_url'] ?? '' }}"></div>
                                        </div>
                                    </div>

                                    <!-- Sidebar Banner -->
                                    <div class="col-12 p-3 border rounded bg-light-subtle">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="fw-bold">2. Banner Iklan Sidebar (300x250)</div>
                                            <label class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" name="ads[sidebar_banner][enabled]" value="1" {{ !empty($settings['ads']['sidebar_banner']['enabled']) ? 'checked' : '' }}>
                                                <span class="form-check-label">Aktif</span>
                                            </label>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-6"><input type="text" name="ads[sidebar_banner][image_url]" class="form-control" placeholder="URL Gambar Banner" value="{{ $settings['ads']['sidebar_banner']['image_url'] ?? '' }}"></div>
                                            <div class="col-md-6"><input type="text" name="ads[sidebar_banner][target_url]" class="form-control" placeholder="Target URL saat diklik (https://...)" value="{{ $settings['ads']['sidebar_banner']['target_url'] ?? '' }}"></div>
                                        </div>
                                    </div>

                                    <!-- Article Bottom Banner -->
                                    <div class="col-12 p-3 border rounded bg-light-subtle">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="fw-bold">3. Banner Iklan Bawah Artikel (In-Article 728x90)</div>
                                            <label class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" name="ads[article_bottom_banner][enabled]" value="1" {{ !empty($settings['ads']['article_bottom_banner']['enabled']) ? 'checked' : '' }}>
                                                <span class="form-check-label">Aktif</span>
                                            </label>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-6"><input type="text" name="ads[article_bottom_banner][image_url]" class="form-control" placeholder="URL Gambar Banner" value="{{ $settings['ads']['article_bottom_banner']['image_url'] ?? '' }}"></div>
                                            <div class="col-md-6"><input type="text" name="ads[article_bottom_banner][target_url]" class="form-control" placeholder="Target URL saat diklik (https://...)" value="{{ $settings['ads']['article_bottom_banner']['target_url'] ?? '' }}"></div>
                                        </div>
                                    </div>

                                    <!-- Tracking -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Google Analytics Tracking ID (G-XXXXX)</label>
                                        <input type="text" name="analytics[google_analytics_id]" class="form-control" value="{{ $settings['analytics']['google_analytics_id'] ?? '' }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Google Tag Manager Container ID (GTM-XXXXX)</label>
                                        <input type="text" name="analytics[google_tag_manager_id]" class="form-control" value="{{ $settings['analytics']['google_tag_manager_id'] ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 7: LIVE JSON -->
                    <div class="tab-pane fade" id="tab-json" role="tabpanel">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
                                <h3 class="card-title fw-bold">
                                    <i class="la la-code text-secondary me-2"></i> Live JSON Data (GET /api/v1/settings)
                                </h3>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText(document.getElementById('rawJsonArea').value); alert('JSON berhasil disalin ke clipboard!');">
                                    <i class="la la-copy"></i> Salin JSON
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <textarea id="rawJsonArea" class="form-control border-0 font-monospace bg-dark text-light p-3" rows="22" readonly style="font-size: 0.85rem; border-radius: 0 0 var(--tblr-border-radius) var(--tblr-border-radius);">{{ $jsonRaw }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Floating Save Bar at the bottom -->
                <div class="card border-0 shadow-sm bg-body-tertiary">
                    <div class="card-body py-3 d-flex align-items-center justify-content-between">
                        <div class="text-secondary small">
                            <i class="la la-info-circle me-1"></i> Perubahan akan langsung tersimpan di file <code>setting-api.json</code> dan diakses oleh Frontend.
                        </div>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm fw-semibold">
                            <i class="la la-save me-1"></i> Simpan Semua Pengaturan
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection
