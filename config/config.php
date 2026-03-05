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

require_once __DIR__ . '/i18n.php';
$current_language = get_current_language();

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
        'title_key' => 'pages.websites.title',
        'icon' => 'fas fa-folder-open',
        'badge' => '12'
    ],
    'nodeapps' => [
        'title_key' => 'pages.nodeapps.title',
        'icon' => 'fab fa-node',
        'badge' => '4'
    ],
    'revproxy' => [
        'title_key' => 'pages.revproxy.title',
        'icon' => 'fas fa-arrows-spin',
        'badge' => '7'
    ],
    'databases' => [
        'title_key' => 'pages.databases.title',
        'icon' => 'fas fa-database',
        'badge' => '3'
    ],
    'domains' => [
        'title_key' => 'pages.domains.title',
        'icon' => 'fas fa-tag',
        'badge' => '9'
    ],
    'ssl' => [
        'title_key' => 'pages.ssl.title',
        'icon' => 'fas fa-lock',
        'badge' => '6'
    ]
];
