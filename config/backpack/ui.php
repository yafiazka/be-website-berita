<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Theme (User Interface)
    |--------------------------------------------------------------------------
    */
    'view_namespace' => 'backpack.theme-tabler::',
    'view_namespace_fallback' => 'backpack.theme-tabler::',

    /*
    |--------------------------------------------------------------------------
    | Look & feel customizations
    |--------------------------------------------------------------------------
    */
    'default_date_format' => 'D MMM YYYY',
    'default_datetime_format' => 'D MMM YYYY, HH:mm',
    'html_direction' => 'ltr',

    // ----
    // HEAD
    // ----
    'project_name' => 'Portal Berita CMS',
    'meta_robots_content' => 'noindex, nofollow',

    // ------
    // HEADER
    // ------
    'home_link' => 'admin/dashboard',
    'project_logo' => '<b>Portal</b> Berita',
    'breadcrumbs' => true,

    // ------
    // FOOTER (Credits Removed)
    // ------
    'developer_name' => false,
    'developer_link' => false,
    'show_powered_by' => false,

    // ---------
    // DASHBOARD
    // ---------
    'show_getting_started' => false,

    // -------------
    // GLOBAL STYLES & SCRIPTS
    // -------------
    'styles' => [],
    'mix_styles' => [],
    'vite_styles' => [],
    'scripts' => [],
    'mix_scripts' => [],
    'vite_scripts' => [],

    'classes' => [
        'table' => null,
        'tableWrapper' => null,
    ],

];
