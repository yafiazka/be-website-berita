<?php

/**
 * Tujuan: Controller Admin Backpack untuk mengelola Pengaturan & Template Frontend (Setting Fe Berita)
 * Caller: Route admin.setting_fe (GET & POST)
 * Dependensi: App\Http\Controllers\Controller, Illuminate\Support\Facades\File
 * Main Functions: index(), update(), resetToDefault()
 * Side Effects: File Read & Write setting-api.json
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Prologue\Alerts\Facades\Alert;

class SettingFeController extends Controller
{
    protected string $filePath;

    public function __construct()
    {
        $this->filePath = base_path('setting-api.json');
    }

    /**
     * Tampilkan Halaman Setting Fe Berita
     */
    public function index()
    {
        $settings = $this->loadSettings();

        return view('admin.setting_fe', [
            'title' => 'Setting Fe Berita',
            'settings' => $settings,
            'jsonRaw' => json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ]);
    }

    /**
     * Simpan Perubahan Pengaturan Template Frontend
     */
    public function update(Request $request)
    {
        $currentSettings = $this->loadSettings();

        // 1. Site Identitas
        $currentSettings['site']['name'] = $request->input('site.name', $currentSettings['site']['name'] ?? 'JERITAN');
        $currentSettings['site']['tagline'] = $request->input('site.tagline', $currentSettings['site']['tagline'] ?? 'Jurnal Edukasi, Reformasi, Investigasi, Transparansi dan Aspirasi Nusantara');
        $currentSettings['site']['description'] = $request->input('site.description', $currentSettings['site']['description'] ?? '');
        $currentSettings['site']['keywords'] = $request->input('site.keywords', $currentSettings['site']['keywords'] ?? '');
        $currentSettings['site']['logo_url'] = $request->input('site.logo_url', $currentSettings['site']['logo_url'] ?? '/template/images/logo.svg');
        $currentSettings['site']['logo_footer_url'] = $request->input('site.logo_footer_url', $currentSettings['site']['logo_footer_url'] ?? '/template/images/white_logo.svg');
        $currentSettings['site']['favicon_url'] = $request->input('site.favicon_url', $currentSettings['site']['favicon_url'] ?? '/favicon.ico');
        $currentSettings['site']['author'] = $request->input('site.author', $currentSettings['site']['author'] ?? 'Redaksi JERITAN');
        $currentSettings['site']['language'] = $request->input('site.language', 'id');
        $currentSettings['site']['timezone'] = $request->input('site.timezone', 'Asia/Jakarta');

        // 2. Theme (General, Light Mode & Dark Mode)
        $currentSettings['theme']['default_mode'] = $request->input('theme.default_mode', 'light');
        $currentSettings['theme']['enable_theme_toggle'] = $request->boolean('theme.enable_theme_toggle');
        $currentSettings['theme']['primary_color'] = $request->input('theme.light.primary_color', $request->input('theme.primary_color', '#d62828'));
        $currentSettings['theme']['primary_hover'] = $request->input('theme.light.primary_hover', $request->input('theme.primary_hover', '#b22222'));
        $currentSettings['theme']['secondary_color'] = $request->input('theme.light.secondary_color', $request->input('theme.secondary_color', '#003049'));
        $currentSettings['theme']['accent_color'] = $request->input('theme.light.accent_color', $request->input('theme.accent_color', '#f77f00'));
        $currentSettings['theme']['dark_bg'] = $request->input('theme.dark.background', $request->input('theme.dark_bg', '#0f172a'));

        // Light Mode Palette
        $currentSettings['theme']['light'] = [
            'background' => $request->input('theme.light.background', '#ffffff'),
            'surface_bg' => $request->input('theme.light.surface_bg', '#ffffff'),
            'topbar_bg' => $request->input('theme.light.topbar_bg', '#f8fafc'),
            'header_bg' => $request->input('theme.light.header_bg', '#ffffff'),
            'footer_bg' => $request->input('theme.light.footer_bg', '#1e293b'),
            'footer_text' => $request->input('theme.light.footer_text', '#cbd5e1'),
            'primary_color' => $request->input('theme.light.primary_color', '#d62828'),
            'primary_hover' => $request->input('theme.light.primary_hover', '#b22222'),
            'secondary_color' => $request->input('theme.light.secondary_color', '#003049'),
            'accent_color' => $request->input('theme.light.accent_color', '#f77f00'),
            'text_primary' => $request->input('theme.light.text_primary', '#0f172a'),
            'text_secondary' => $request->input('theme.light.text_secondary', '#475569'),
            'text_muted' => $request->input('theme.light.text_muted', '#94a3b8'),
            'border_color' => $request->input('theme.light.border_color', '#e2e8f0'),
        ];

        // Dark Mode Palette
        $currentSettings['theme']['dark'] = [
            'background' => $request->input('theme.dark.background', '#0f172a'),
            'surface_bg' => $request->input('theme.dark.surface_bg', '#1e293b'),
            'topbar_bg' => $request->input('theme.dark.topbar_bg', '#0b0f19'),
            'header_bg' => $request->input('theme.dark.header_bg', '#0f172a'),
            'footer_bg' => $request->input('theme.dark.footer_bg', '#080c14'),
            'footer_text' => $request->input('theme.dark.footer_text', '#94a3b8'),
            'primary_color' => $request->input('theme.dark.primary_color', '#ef4444'),
            'primary_hover' => $request->input('theme.dark.primary_hover', '#f87171'),
            'secondary_color' => $request->input('theme.dark.secondary_color', '#38bdf8'),
            'accent_color' => $request->input('theme.dark.accent_color', '#fbbf24'),
            'text_primary' => $request->input('theme.dark.text_primary', '#f8fafc'),
            'text_secondary' => $request->input('theme.dark.text_secondary', '#cbd5e1'),
            'text_muted' => $request->input('theme.dark.text_muted', '#64748b'),
            'border_color' => $request->input('theme.dark.border_color', '#334155'),
        ];

        // 3. Features
        $currentSettings['features']['breaking_news_ticker']['enabled'] = $request->boolean('features.breaking_news_ticker.enabled');
        $currentSettings['features']['breaking_news_ticker']['title'] = $request->input('features.breaking_news_ticker.title', 'Breaking News');
        $currentSettings['features']['breaking_news_ticker']['autoplay_speed'] = (int) $request->input('features.breaking_news_ticker.autoplay_speed', 4000);

        $currentSettings['features']['trending_section']['enabled'] = $request->boolean('features.trending_section.enabled');
        $currentSettings['features']['trending_section']['title'] = $request->input('features.trending_section.title', 'Trending Topics');
        $currentSettings['features']['trending_section']['limit'] = (int) $request->input('features.trending_section.limit', 6);

        $currentSettings['features']['video_news_section']['enabled'] = $request->boolean('features.video_news_section.enabled');
        $currentSettings['features']['video_news_section']['title'] = $request->input('features.video_news_section.title', 'Video Berita Terpopuler');
        $currentSettings['features']['video_news_section']['limit'] = (int) $request->input('features.video_news_section.limit', 4);

        $currentSettings['features']['newsletter']['enabled'] = $request->boolean('features.newsletter.enabled');
        $currentSettings['features']['newsletter']['title'] = $request->input('features.newsletter.title', 'Berlangganan Buletin Berita');
        $currentSettings['features']['newsletter']['description'] = $request->input('features.newsletter.description', '');

        $currentSettings['features']['reading_time']['enabled'] = $request->boolean('features.reading_time.enabled');
        $currentSettings['features']['comments']['enabled'] = $request->boolean('features.comments.enabled');
        $currentSettings['features']['comments']['require_moderation'] = $request->boolean('features.comments.require_moderation');
        $currentSettings['features']['comments']['allow_guest'] = $request->boolean('features.comments.allow_guest');

        // Social Share Platforms
        if ($request->has('features.social_share.platforms')) {
            $platforms = $request->input('features.social_share.platforms');
            $currentSettings['features']['social_share']['platforms'] = is_array($platforms) ? $platforms : explode(',', $platforms);
        }
        $currentSettings['features']['social_share']['enabled'] = $request->boolean('features.social_share.enabled');

        // 4. Contact
        $currentSettings['contact']['email'] = $request->input('contact.email', '');
        $currentSettings['contact']['phone'] = $request->input('contact.phone', '');
        $currentSettings['contact']['whatsapp'] = $request->input('contact.whatsapp', '');
        $currentSettings['contact']['address'] = $request->input('contact.address', '');
        $currentSettings['contact']['google_maps_embed_url'] = $request->input('contact.google_maps_embed_url', '');

        // 5. Social Links
        $currentSettings['social_links']['facebook'] = $request->input('social_links.facebook', '');
        $currentSettings['social_links']['twitter'] = $request->input('social_links.twitter', '');
        $currentSettings['social_links']['instagram'] = $request->input('social_links.instagram', '');
        $currentSettings['social_links']['youtube'] = $request->input('social_links.youtube', '');
        $currentSettings['social_links']['tiktok'] = $request->input('social_links.tiktok', '');
        $currentSettings['social_links']['linkedin'] = $request->input('social_links.linkedin', '');

        // 6. Navigation
        if ($request->filled('navigation_topbar_json')) {
            $decoded = json_decode($request->input('navigation_topbar_json'), true);
            if (is_array($decoded)) {
                $currentSettings['navigation']['topbar_menu'] = $decoded;
            }
        }
        if ($request->filled('navigation_footer_quick_json')) {
            $decoded = json_decode($request->input('navigation_footer_quick_json'), true);
            if (is_array($decoded)) {
                $currentSettings['navigation']['footer_menu_quick_links'] = $decoded;
            }
        }
        if ($request->filled('navigation_footer_legal_json')) {
            $decoded = json_decode($request->input('navigation_footer_legal_json'), true);
            if (is_array($decoded)) {
                $currentSettings['navigation']['footer_menu_legal'] = $decoded;
            }
        }

        // 7. Footer
        $currentSettings['footer']['about_text'] = $request->input('footer.about_text', '');
        $currentSettings['footer']['copyright_text'] = $request->input('footer.copyright_text', '');

        // 8. Ads
        $currentSettings['ads']['header_banner']['enabled'] = $request->boolean('ads.header_banner.enabled');
        $currentSettings['ads']['header_banner']['image_url'] = $request->input('ads.header_banner.image_url', '');
        $currentSettings['ads']['header_banner']['target_url'] = $request->input('ads.header_banner.target_url', '');
        $currentSettings['ads']['header_banner']['alt_text'] = $request->input('ads.header_banner.alt_text', '');

        $currentSettings['ads']['sidebar_banner']['enabled'] = $request->boolean('ads.sidebar_banner.enabled');
        $currentSettings['ads']['sidebar_banner']['image_url'] = $request->input('ads.sidebar_banner.image_url', '');
        $currentSettings['ads']['sidebar_banner']['target_url'] = $request->input('ads.sidebar_banner.target_url', '');
        $currentSettings['ads']['sidebar_banner']['alt_text'] = $request->input('ads.sidebar_banner.alt_text', '');

        $currentSettings['ads']['article_bottom_banner']['enabled'] = $request->boolean('ads.article_bottom_banner.enabled');
        $currentSettings['ads']['article_bottom_banner']['image_url'] = $request->input('ads.article_bottom_banner.image_url', '');
        $currentSettings['ads']['article_bottom_banner']['target_url'] = $request->input('ads.article_bottom_banner.target_url', '');
        $currentSettings['ads']['article_bottom_banner']['alt_text'] = $request->input('ads.article_bottom_banner.alt_text', '');

        // 9. Analytics
        $currentSettings['analytics']['google_analytics_id'] = $request->input('analytics.google_analytics_id', '');
        $currentSettings['analytics']['google_tag_manager_id'] = $request->input('analytics.google_tag_manager_id', '');

        // Simpan ke file
        $payload = [
            'success' => true,
            'message' => 'Pengaturan website berhasil dimuat.',
            'data' => $currentSettings,
        ];

        File::put($this->filePath, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        if (class_exists(Alert::class)) {
            Alert::success('Pengaturan template Frontend "Berita Satu Nusa" berhasil diperbarui!')->flash();
        }

        return redirect()->back()->with('success', 'Pengaturan template Frontend berhasil disimpan.');
    }

    /**
     * Helper memuat array settings
     */
    protected function loadSettings(): array
    {
        if (!File::exists($this->filePath)) {
            return [];
        }

        $json = json_decode(File::get($this->filePath), true);

        return $json['data'] ?? $json ?? [];
    }
}
