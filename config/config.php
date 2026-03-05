<?php
// Config file for Simple Panel

// Site configuration
define('SITE_NAME', 'Web Hosting Panel');
define('SITE_VERSION', 'v1.0');

// Path configuration
define('BASE_PATH', __DIR__ . '/..');
define('COMPONENTS_PATH', BASE_PATH . '/components');
define('PAGES_PATH', BASE_PATH . '/pages');
define('INCLUDES_PATH', BASE_PATH . '/includes');

// URL configuration
define('BASE_URL', '/');
define('ASSETS_URL', BASE_URL . 'assets/');

// Default page
define('DEFAULT_PAGE', 'websites');

// Available pages
$available_pages = [
    'websites',
    'nodeapps',
    'revproxy',
    'databases',
    'domains',
    'ssl'
];

// Page configurations
$page_config = [
    'websites' => [
        'title' => 'Websites',
        'icon' => 'fas fa-folder-open',
        'badge' => '12'
    ],
    'nodeapps' => [
        'title' => 'Node apps',
        'icon' => 'fab fa-node',
        'badge' => '4'
    ],
    'revproxy' => [
        'title' => 'Reverse proxy',
        'icon' => 'fas fa-arrows-spin',
        'badge' => '7'
    ],
    'databases' => [
        'title' => 'Databases',
        'icon' => 'fas fa-database',
        'badge' => '3'
    ],
    'domains' => [
        'title' => 'Domains',
        'icon' => 'fas fa-tag',
        'badge' => '9'
    ],
    'ssl' => [
        'title' => 'SSL/TLS',
        'icon' => 'fas fa-lock',
        'badge' => '6'
    ]
];
