<?php

$available_languages = [
    'en' => 'English',
    'es' => 'Español',
    'ru' => 'Русский',
    'si' => 'සිංහල',
];

$default_language = 'en';

$translations = [
    'en' => [
        'site_name' => 'Web Hosting Panel',
        'common' => [
            'dashboard' => 'Dashboard',
            'page_not_found' => 'Page not found',
            'page_not_found_text' => 'The requested page does not exist.',
            'all_systems_operational' => 'all systems operational',
            'language' => 'Language',
            'preview_note' => 'Sidebar includes: websites, node apps, reverse proxy (icons and counters)',
            'node_status' => 'node active - 2.31 GB / 8 GB',
        ],
        'nav' => [
            'applications' => 'Applications',
            'system' => 'System',
            'websites' => 'Websites',
            'nodeapps' => 'Node apps',
            'revproxy' => 'Reverse proxy',
            'databases' => 'Databases',
            'domains' => 'Domains',
            'ssl' => 'SSL/TLS',
        ],
        'pages' => [
            'websites' => [
                'title' => 'Websites',
                'stat_total_sites' => 'Total sites',
                'stat_traffic' => 'Traffic (last 24h)',
                'stat_cms' => 'CMS installs',
                'stat_ssl' => 'SSL enabled',
                'overview_title' => 'Websites overview',
                'overview_text' => 'example.com, myblog.net, shop.local ... 12 entries. Manage your vhosts, PHP versions, and root directories.',
            ],
            'nodeapps' => [
                'title' => 'Node apps',
                'stat_apps' => 'Node apps',
                'stat_pm2' => 'PM2 instances',
                'stat_heap' => 'Heap usage',
                'stat_restarts' => 'Restarts (24h)',
                'overview_title' => 'Node.js applications',
                'overview_text' => 'Express, Next.js, or Fastify apps - each with environment variables, process monitoring, and logs.',
            ],
            'revproxy' => [
                'title' => 'Reverse proxy',
                'stat_hosts' => 'Proxy hosts',
                'stat_upstreams' => 'Upstreams',
                'stat_ssl_term' => 'SSL termination',
                'stat_avg_response' => 'Avg response',
                'overview_title' => 'Reverse proxy rules',
                'overview_text' => 'NGINX / HAProxy style: route subdomains, path-based rules, load balancing, and WebSocket support.',
            ],
            'databases' => [
                'title' => 'Databases',
                'stat_total' => 'Total databases',
                'stat_tables' => 'Tables',
                'stat_storage' => 'Storage used',
                'stat_users' => 'DB users',
                'overview_title' => 'Database management',
                'overview_text' => 'MySQL, PostgreSQL, and MongoDB instances. Manage users, backups, and access control.',
            ],
            'domains' => [
                'title' => 'Domains',
                'stat_total' => 'Total domains',
                'stat_active' => 'Active',
                'stat_expiring' => 'Expiring soon',
                'stat_dns' => 'DNS records',
                'overview_title' => 'Domain management',
                'overview_text' => 'Register, transfer, and manage domains. Configure DNS records, nameservers, and forwarding.',
            ],
            'ssl' => [
                'title' => 'SSL/TLS',
                'stat_certificates' => 'SSL certificates',
                'stat_lets_encrypt' => "Let's Encrypt",
                'stat_valid' => 'Valid',
                'stat_expiring' => 'Expiring (30d)',
                'overview_title' => 'SSL/TLS certificates',
                'overview_text' => "Manage SSL certificates, automatic renewals with Let's Encrypt, and custom certificate uploads.",
            ],
        ],
    ],
    'es' => [
        'site_name' => 'Panel de Hosting Web',
        'common' => [
            'dashboard' => 'Panel',
            'page_not_found' => 'Página no encontrada',
            'page_not_found_text' => 'La página solicitada no existe.',
            'all_systems_operational' => 'todos los sistemas operativos',
            'language' => 'Idioma',
            'preview_note' => 'La barra lateral incluye: sitios web, apps Node y proxy inverso (iconos y contadores)',
            'node_status' => 'nodo activo - 2.31 GB / 8 GB',
        ],
        'nav' => [
            'applications' => 'Aplicaciones',
            'system' => 'Sistema',
            'websites' => 'Sitios web',
            'nodeapps' => 'Apps Node',
            'revproxy' => 'Proxy inverso',
            'databases' => 'Bases de datos',
            'domains' => 'Dominios',
            'ssl' => 'SSL/TLS',
        ],
        'pages' => [
            'websites' => [
                'title' => 'Sitios web',
                'stat_total_sites' => 'Sitios totales',
                'stat_traffic' => 'Tráfico (últimas 24h)',
                'stat_cms' => 'Instalaciones CMS',
                'stat_ssl' => 'SSL habilitado',
                'overview_title' => 'Resumen de sitios web',
                'overview_text' => 'example.com, myblog.net, shop.local ... 12 entradas. Gestiona tus vhosts, versiones de PHP y directorios raíz.',
            ],
            'nodeapps' => [
                'title' => 'Apps Node',
                'stat_apps' => 'Apps Node',
                'stat_pm2' => 'Instancias PM2',
                'stat_heap' => 'Uso de heap',
                'stat_restarts' => 'Reinicios (24h)',
                'overview_title' => 'Aplicaciones Node.js',
                'overview_text' => 'Apps de Express, Next.js o Fastify, cada una con variables de entorno, monitoreo de procesos y registros.',
            ],
            'revproxy' => [
                'title' => 'Proxy inverso',
                'stat_hosts' => 'Hosts proxy',
                'stat_upstreams' => 'Upstreams',
                'stat_ssl_term' => 'Terminación SSL',
                'stat_avg_response' => 'Respuesta prom.',
                'overview_title' => 'Reglas de proxy inverso',
                'overview_text' => 'Estilo NGINX/HAProxy: enrutamiento por subdominios, reglas por ruta, balanceo y soporte WebSocket.',
            ],
            'databases' => [
                'title' => 'Bases de datos',
                'stat_total' => 'Bases totales',
                'stat_tables' => 'Tablas',
                'stat_storage' => 'Almacenamiento usado',
                'stat_users' => 'Usuarios BD',
                'overview_title' => 'Gestión de bases de datos',
                'overview_text' => 'Instancias MySQL, PostgreSQL y MongoDB. Gestiona usuarios, copias de seguridad y control de acceso.',
            ],
            'domains' => [
                'title' => 'Dominios',
                'stat_total' => 'Dominios totales',
                'stat_active' => 'Activos',
                'stat_expiring' => 'Vencen pronto',
                'stat_dns' => 'Registros DNS',
                'overview_title' => 'Gestión de dominios',
                'overview_text' => 'Registra, transfiere y gestiona dominios. Configura DNS, nameservers y redirecciones.',
            ],
            'ssl' => [
                'title' => 'SSL/TLS',
                'stat_certificates' => 'Certificados SSL',
                'stat_lets_encrypt' => "Let's Encrypt",
                'stat_valid' => 'Válidos',
                'stat_expiring' => 'Vencen (30d)',
                'overview_title' => 'Certificados SSL/TLS',
                'overview_text' => "Gestiona certificados SSL, renovaciones automáticas con Let's Encrypt y cargas de certificados personalizados.",
            ],
        ],
    ],
    'ru' => [
        'site_name' => 'Панель веб-хостинга',
        'common' => [
            'dashboard' => 'Панель',
            'page_not_found' => 'Страница не найдена',
            'page_not_found_text' => 'Запрошенная страница не существует.',
            'all_systems_operational' => 'все системы в норме',
            'language' => 'Язык',
            'preview_note' => 'Боковая панель включает: сайты, Node-приложения, reverse proxy (иконки и счетчики)',
            'node_status' => 'узел активен - 2.31 ГБ / 8 ГБ',
        ],
        'nav' => [
            'applications' => 'Приложения',
            'system' => 'Система',
            'websites' => 'Сайты',
            'nodeapps' => 'Node-приложения',
            'revproxy' => 'Reverse proxy',
            'databases' => 'Базы данных',
            'domains' => 'Домены',
            'ssl' => 'SSL/TLS',
        ],
        'pages' => [
            'websites' => [
                'title' => 'Сайты',
                'stat_total_sites' => 'Всего сайтов',
                'stat_traffic' => 'Трафик (24ч)',
                'stat_cms' => 'Установки CMS',
                'stat_ssl' => 'SSL включен',
                'overview_title' => 'Обзор сайтов',
                'overview_text' => 'example.com, myblog.net, shop.local ... 12 записей. Управляйте vhost, версиями PHP и корневыми каталогами.',
            ],
            'nodeapps' => [
                'title' => 'Node-приложения',
                'stat_apps' => 'Node-приложения',
                'stat_pm2' => 'Инстансы PM2',
                'stat_heap' => 'Использование heap',
                'stat_restarts' => 'Перезапуски (24ч)',
                'overview_title' => 'Приложения Node.js',
                'overview_text' => 'Приложения на Express, Next.js или Fastify с переменными окружения, мониторингом процессов и логами.',
            ],
            'revproxy' => [
                'title' => 'Reverse proxy',
                'stat_hosts' => 'Прокси-хосты',
                'stat_upstreams' => 'Upstream-сервисы',
                'stat_ssl_term' => 'SSL-терминация',
                'stat_avg_response' => 'Средний отклик',
                'overview_title' => 'Правила reverse proxy',
                'overview_text' => 'Стиль NGINX / HAProxy: маршрутизация по поддоменам, правилам путей, балансировка и поддержка WebSocket.',
            ],
            'databases' => [
                'title' => 'Базы данных',
                'stat_total' => 'Всего баз',
                'stat_tables' => 'Таблицы',
                'stat_storage' => 'Использовано места',
                'stat_users' => 'Пользователи БД',
                'overview_title' => 'Управление базами данных',
                'overview_text' => 'Инстансы MySQL, PostgreSQL и MongoDB. Управляйте пользователями, бэкапами и контролем доступа.',
            ],
            'domains' => [
                'title' => 'Домены',
                'stat_total' => 'Всего доменов',
                'stat_active' => 'Активные',
                'stat_expiring' => 'Скоро истекают',
                'stat_dns' => 'DNS-записи',
                'overview_title' => 'Управление доменами',
                'overview_text' => 'Регистрируйте, переносите и управляйте доменами. Настраивайте DNS, nameserver и переадресацию.',
            ],
            'ssl' => [
                'title' => 'SSL/TLS',
                'stat_certificates' => 'SSL-сертификаты',
                'stat_lets_encrypt' => "Let's Encrypt",
                'stat_valid' => 'Действительные',
                'stat_expiring' => 'Истекают (30д)',
                'overview_title' => 'SSL/TLS сертификаты',
                'overview_text' => "Управляйте SSL-сертификатами, автообновлением через Let's Encrypt и загрузкой пользовательских сертификатов.",
            ],
        ],
    ],
    'si' => [
        'site_name' => 'වෙබ් හෝස්ටින් පැනලය',
        'common' => [
            'dashboard' => 'ඩෑෂ්බෝඩ්',
            'page_not_found' => 'පිටුව සොයාගත නොහැක',
            'page_not_found_text' => 'ඔබ ඉල්ලූ පිටුව නොපවතී.',
            'all_systems_operational' => 'සියලු පද්ධති සාමාන්‍ය ලෙස ක්‍රියාත්මකයි',
            'language' => 'භාෂාව',
            'preview_note' => 'පැති තීරුවේ: වෙබ් අඩවි, Node යෙදුම්, reverse proxy (අයිකන සහ ගණන්)',
            'node_status' => 'node සක්‍රියයි - 2.31 GB / 8 GB',
        ],
        'nav' => [
            'applications' => 'යෙදුම්',
            'system' => 'පද්ධතිය',
            'websites' => 'වෙබ් අඩවි',
            'nodeapps' => 'Node යෙදුම්',
            'revproxy' => 'Reverse proxy',
            'databases' => 'දත්ත ගබඩා',
            'domains' => 'ඩොමේන්',
            'ssl' => 'SSL/TLS',
        ],
        'pages' => [
            'websites' => [
                'title' => 'වෙබ් අඩවි',
                'stat_total_sites' => 'මුළු අඩවි',
                'stat_traffic' => 'ට්‍රැෆික් (පසුගිය 24h)',
                'stat_cms' => 'CMS ස්ථාපන',
                'stat_ssl' => 'SSL සක්‍රීය',
                'overview_title' => 'වෙබ් අඩවි සාරාංශය',
                'overview_text' => 'example.com, myblog.net, shop.local ... ඇතුළත් 12ක්. vhost, PHP සංස්කරණ සහ root directory කළමනාකරණය කරන්න.',
            ],
            'nodeapps' => [
                'title' => 'Node යෙදුම්',
                'stat_apps' => 'Node යෙදුම්',
                'stat_pm2' => 'PM2 instances',
                'stat_heap' => 'Heap භාවිතය',
                'stat_restarts' => 'නැවත ආරම්භ (24h)',
                'overview_title' => 'Node.js යෙදුම්',
                'overview_text' => 'Express, Next.js හෝ Fastify යෙදුම් - environment variables, process monitoring සහ logs සමඟ.',
            ],
            'revproxy' => [
                'title' => 'Reverse proxy',
                'stat_hosts' => 'Proxy hosts',
                'stat_upstreams' => 'Upstreams',
                'stat_ssl_term' => 'SSL termination',
                'stat_avg_response' => 'සාමාන්‍ය ප්‍රතිචාරය',
                'overview_title' => 'Reverse proxy නීති',
                'overview_text' => 'NGINX / HAProxy රටාව: subdomain routing, path rules, load balancing සහ WebSocket support.',
            ],
            'databases' => [
                'title' => 'දත්ත ගබඩා',
                'stat_total' => 'මුළු දත්ත ගබඩා',
                'stat_tables' => 'වගු',
                'stat_storage' => 'භාවිත කළ ගබඩා ඉඩ',
                'stat_users' => 'DB පරිශීලකයින්',
                'overview_title' => 'දත්ත ගබඩා කළමනාකරණය',
                'overview_text' => 'MySQL, PostgreSQL සහ MongoDB instances. පරිශීලකයින්, backup සහ access control කළමනාකරණය කරන්න.',
            ],
            'domains' => [
                'title' => 'ඩොමේන්',
                'stat_total' => 'මුළු ඩොමේන්',
                'stat_active' => 'සක්‍රිය',
                'stat_expiring' => 'ඉක්මනින් කල් ඉකුත් වන',
                'stat_dns' => 'DNS වාර්තා',
                'overview_title' => 'ඩොමේන් කළමනාකරණය',
                'overview_text' => 'ඩොමේන් ලියාපදිංචි කරන්න, මාරු කරන්න, කළමනාකරණය කරන්න. DNS, nameserver සහ forwarding සකසන්න.',
            ],
            'ssl' => [
                'title' => 'SSL/TLS',
                'stat_certificates' => 'SSL සහතික',
                'stat_lets_encrypt' => "Let's Encrypt",
                'stat_valid' => 'වලංගු',
                'stat_expiring' => 'කල් ඉකුත් (30d)',
                'overview_title' => 'SSL/TLS සහතික',
                'overview_text' => "SSL සහතික, Let's Encrypt මඟින් ස්වයංක්‍රීය නවීකරණ, සහ custom certificate upload කළමනාකරණය කරන්න.",
            ],
        ],
    ],
];

function get_current_language()
{
    global $available_languages, $default_language;

    if (isset($_GET['lang']) && array_key_exists($_GET['lang'], $available_languages)) {
        $lang = $_GET['lang'];
        setcookie('panel_lang', $lang, time() + (365 * 24 * 60 * 60), '/');
        return $lang;
    }

    if (isset($_COOKIE['panel_lang']) && array_key_exists($_COOKIE['panel_lang'], $available_languages)) {
        return $_COOKIE['panel_lang'];
    }

    return $default_language;
}

function translate_get_value(array $source, $parts)
{
    $cursor = $source;
    foreach ($parts as $part) {
        if (!is_array($cursor) || !array_key_exists($part, $cursor)) {
            return null;
        }
        $cursor = $cursor[$part];
    }
    return is_string($cursor) ? $cursor : null;
}

function t($key)
{
    global $translations, $current_language, $default_language;

    $parts = explode('.', $key);
    $value = translate_get_value($translations[$current_language] ?? [], $parts);
    if ($value !== null) {
        return $value;
    }

    $fallback = translate_get_value($translations[$default_language] ?? [], $parts);
    return $fallback !== null ? $fallback : $key;
}

function url_with_lang(array $extra_params = [])
{
    global $current_language;
    $query = array_merge($_GET, $extra_params, ['lang' => $current_language]);
    return '?' . http_build_query($query);
}

