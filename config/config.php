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
define('DEFAULT_PAGE', 'dashboard');

// Available pages
$available_pages = [
    'dashboard',
    'websites',
    'nodeapps',
    'revproxy',
    'docker',
    'databases',
    'domains',
    'ssl',
    'filemanager'
];

// Page configurations
$page_config = [
    'dashboard' => [
        'title' => 'Dashboard',
        'icon' => 'fas fa-tachometer-alt',
        'badge' => ''
    ],
    'websites' => [
        'title' => 'Websites',
        'icon' => 'fas fa-folder-open',
        'badge' => '12'
    ],
    'nodeapps' => [
        'title' => 'Node Apps',
        'icon' => 'fab fa-node',
        'badge' => '4'
    ],
    'revproxy' => [
        'title' => 'Reverse Proxy',
        'icon' => 'fas fa-random',
        'badge' => '7'
    ],
    'docker' => [
        'title' => 'Docker',
        'icon' => 'fab fa-docker',
        'badge' => ''
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
    ],
    'filemanager' => [
        'title' => 'File Manager',
        'icon' => 'fas fa-folder-tree',
        'badge' => ''
    ]
];
