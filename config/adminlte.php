<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    */
    'title' => '',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    */
    'logo' => '.',
    'logo_img' => 'images/logo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_alt' => '',
    

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    */
    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => true,
    'usermenu_desc' => true,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    */
    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    */
    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    */
    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    */
    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    */
    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    */
    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    */
    'menu' => [

        // Dashboard
        [
            'text' => 'dashboard',
            'url'  => 'dashboard',
            'icon' => 'fas fa-home',
        ],

        // Income
        [
            'text' => 'income',
            'icon' => 'fas fa-money-bill-wave',
            'can'  => 'income.view',
            'submenu' => [
                [
                    'text' => 'income_add',
                    'url'  => 'income/create',
                    'icon' => 'fas fa-plus-circle',
                ],
                [
                    'text' => 'income_all',
                    'url'  => 'income',
                    'icon' => 'fas fa-list',
                ],
            ],
        ],

        // Loan Management
        [
            'text' => 'loan_management',
            'icon' => 'fas fa-hand-holding-usd',
            'can'  => 'loan.management.view',
            'submenu' => [
                [
                    'text' => 'providers',
                    'url'  => 'loan-providers',
                    'icon' => 'fas fa-building',
                ],
                [
                    'text' => 'loans',
                    'url'  => 'loans',
                    'icon' => 'fas fa-hand-holding-usd',
                ],
            ],
        ],

        // Products/Services
        [
            'text' => 'products_services',
            'url'  => 'product',
            'icon' => 'fas fa-boxes',
            'can'  => 'product.service.view',
        ],

        // Expense Category
        [
            'text' => 'expense_category',
            'url'  => 'expense-category',
            'icon' => 'fas fa-tags',
            'can'  => 'admin-only',
        ],

        // Expense
        [
            'text' => 'expense',
            'icon' => 'fas fa-receipt',
            'submenu' => [
                [
                    'text' => 'expense_add',
                    'url'  => 'expenses/create',
                    'icon' => 'fas fa-plus-circle',
                ],
                [
                    'text' => 'expense_all',
                    'url'  => 'expenses',
                    'icon' => 'fas fa-list',
                ],
            ],
        ],

        // Reports
        [
            'text' => 'reports',
            'icon' => 'fas fa-chart-line',
            'submenu' => [
                ['text' => 'report_daily', 'url' => 'reports/daily', 'icon' => 'fas fa-calendar-day'],
                ['text' => 'report_monthly', 'url' => 'reports/monthly', 'icon' => 'fas fa-calendar-day'],
                ['text' => 'report_product', 'url' => 'reports/product', 'icon' => 'fas fa-box-open'],
                ['text' => 'report_income_expense', 'url' => 'reports/income-expense', 'icon' => 'fas fa-file-invoice-dollar'],
                ['text' => 'report_category', 'url' => 'reports/category', 'icon' => 'fas fa-box-open'],
                ['text' => 'report_datewise', 'url' => 'reports/datewise', 'icon' => 'fas fa-file-invoice-dollar'],
            ],
        ],

        // Roles
        [
            'text' => 'roles',
            'icon' => 'fas fa-user-shield',
            'can'  => 'admin-only',
            'submenu' => [
                ['text' => 'roles_all', 'url' => 'admin/roles', 'icon' => 'fas fa-list'],
                ['text' => 'roles_create', 'url' => 'admin/roles/create', 'icon' => 'fas fa-plus-circle'],
            ],
        ],

        // Permissions
        [
            'text' => 'permissions',
            'icon' => 'fas fa-key',
            'can'  => 'admin-only',
            'submenu' => [
                ['text' => 'permissions_all', 'url' => 'admin/permissions', 'icon' => 'fas fa-list'],
                ['text' => 'permissions_create', 'url' => 'admin/permissions/create', 'icon' => 'fas fa-plus-circle'],
            ],
        ],

        // Users Management
        [
            'text' => 'users_management',
            'icon' => 'fas fa-users-cog',
            'can'  => 'admin-only',
            'submenu' => [
                ['text' => 'users_all', 'url' => 'admin/users'],
                ['text' => 'users_create', 'url' => 'admin/users/create'],
            ],
        ],

        // Settings Header
        ['header' => 'settings'],

        [
            'text' => 'profile',
            'url'  => 'profile',
            'icon' => 'fas fa-user',
        ],
        [
            'text' => 'change_password',
            'url'  => 'password/change',
            'icon' => 'fas fa-lock',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins
    |--------------------------------------------------------------------------
    */
    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/datatables/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/chart.js/Chart.bundle.min.js',
                ],
            ],
        ],
    ],

];
