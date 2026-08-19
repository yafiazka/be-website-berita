<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Theme Configuration Values
    |--------------------------------------------------------------------------
    */

    /**
     * Layout type: 'vertical' sets the navigation to a left sidebar.
     */
    'layout' => 'vertical',

    /**
     * Login page layout.
     */
    'auth_layout' => 'default',

    'project_name' => 'Portal Berita CMS',
    'project_logo' => '<i class="la la-newspaper text-primary me-1"></i><b>Portal</b> Berita',

    'show_powered_by' => false,
    'developer_name' => false,
    'developer_link' => false,

    'styles' => [
        base_path('vendor/backpack/theme-tabler/resources/assets/css/color-adjustments.css'),
        base_path('vendor/backpack/theme-tabler/resources/assets/css/colors.css'),
    ],

    'options' => [
        'colorModes' => [
            'system' => 'la-desktop',
            'light' => 'la-sun',
            'dark' => 'la-moon',
        ],
        'defaultColorMode' => 'system',
        'showColorModeSwitcher' => true,
        'useStickyHeader' => false,
        'useFluidContainers' => false,
        'sidebarFixed' => true,
        'doubleTopBarInHorizontalLayouts' => false,
        'showPasswordVisibilityToggler' => true,
    ],

    'classes' => [
        'body' => null,
        'topHeader' => null,
        'sidebar' => 'navbar-vertical navbar-expand-lg',
        'menuHorizontalContainer' => null,
        'menuHorizontalContent' => null,
        'footer' => 'd-none',
        'table' => null,
        'tableWrapper' => null,
    ],
];
