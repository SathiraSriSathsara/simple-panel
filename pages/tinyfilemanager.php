<?php
//Default Configuration
$CONFIG = '{"lang":"en","error_reporting":false,"show_hidden":false,"hide_Cols":false,"theme":"dark"}';

/**
 * H3K ~ Tiny File Manager V2.6
 * Integrated into Simple Panel
 */

//TFM version
define('VERSION', '2.6');

//Application Title
define('APP_TITLE', 'File Manager');

// --- CONFIGURATION ---

// Disable authentication
$use_auth = false;

// Root path for file manager - set to panel uploads directory
$root_path = __DIR__ . '/../uploads';

// Root url for links
$root_url = '';

// Server hostname
$http_host = $_SERVER['HTTP_HOST'];

// input encoding for iconv
$iconv_input_encoding = 'UTF-8';

// date() format for file modification date
$datetime_format = 'm/d/Y g:i A';

// Path display mode
$path_display_mode = 'full';

// Allowed file extensions for create and rename files
$allowed_file_extensions = '';

// Allowed file extensions for upload files
$allowed_upload_extensions = '';

// Favicon path
$favicon_path = '';

// Files and folders to excluded from listing
$exclude_items = array();

// Online office Docs Viewer
$online_viewer = 'google';

// Sticky Nav bar
$sticky_navbar = true;

// Maximum file upload size
$max_upload_size_bytes = 5000000000; // 5GB

// chunk size used for upload
$upload_chunk_size_bytes = 2000000; // 2MB

// IP restrictions
$ip_ruleset = 'OFF';
$ip_silent = true;
$ip_whitelist = array('127.0.0.1', '::1');
$ip_blacklist = array('0.0.0.0', '::');

// Configuration
$cfg = new FM_Config();

// Default language
$lang = isset($cfg->data['lang']) ? $cfg->data['lang'] : 'en';

// Show or hide files and folders that starts with a dot
$show_hidden_files = isset($cfg->data['show_hidden']) ? $cfg->data['show_hidden'] : true;

// PHP error reporting
$report_errors = isset($cfg->data['error_reporting']) ? $cfg->data['error_reporting'] : false;

// Hide Permissions and Owner cols in file-listing
$hide_Cols = isset($cfg->data['hide_Cols']) ? $cfg->data['hide_Cols'] : true;

// Theme
$theme = isset($cfg->data['theme']) ? $cfg->data['theme'] : 'dark';

define('FM_THEME', $theme);

// Available languages
$lang_list = array('en' => 'English');

if ($report_errors == true) {
    @ini_set('error_reporting', E_ALL);
    @ini_set('display_errors', 1);
} else {
    @ini_set('error_reporting', E_ALL);
    @ini_set('display_errors', 0);
}

@set_time_limit(600);
date_default_timezone_set('UTC');
ini_set('default_charset', 'UTF-8');

session_cache_limiter('nocache');
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Generating CSRF Token
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// Clean and check $root_path
$root_path = rtrim($root_path, '\\/');
$root_path = str_replace('\\', '/', $root_path);
if (!@is_dir($root_path)) {
    echo "<h1>Root path \"{$root_path}\" not found!</h1>";
    exit;
}

define('FM_SHOW_HIDDEN', $show_hidden_files);
define('FM_ROOT_PATH', $root_path);
define('FM_LANG', $lang);
define('FM_FILE_EXTENSION', $allowed_file_extensions);
define('FM_UPLOAD_EXTENSION', $allowed_upload_extensions);
define('FM_EXCLUDE_ITEMS', $exclude_items);
define('FM_DOC_VIEWER', $online_viewer);
define('FM_READONLY', false);
define('FM_IS_WIN', DIRECTORY_SEPARATOR == '\\');
define('FM_DATETIME_FORMAT', $datetime_format);
define('MAX_UPLOAD_SIZE', $max_upload_size_bytes);
define('UPLOAD_CHUNK_SIZE', $upload_chunk_size_bytes);
define('FM_USE_HIGHLIGHTJS', true);
define('FM_HIGHLIGHTJS_STYLE', 'vs');
define('FM_EDIT_FILE', true);
define('FM_ICONV_INPUT_ENC', $iconv_input_encoding);

$is_https = isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)
    || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https';

// Define URLs
$root_url = fm_clean_path($root_url);
defined('FM_ROOT_URL') || define('FM_ROOT_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . (!empty($root_url) ? '/' . $root_url : ''));
defined('FM_SELF_URL') || define('FM_SELF_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . $_SERVER['PHP_SELF']);

// Always use ?p=
if (!isset($_GET['p']) && empty($_FILES)) {
    $_GET['p'] = '';
}

// Get path
$p = isset($_GET['p']) ? $_GET['p'] : (isset($_POST['p']) ? $_POST['p'] : '');

// Clean path
$p = fm_clean_path($p);

// For ajax request
$input = file_get_contents('php://input');
$_POST = (strpos($input, 'ajax') != FALSE && strpos($input, 'save') != FALSE) ? json_decode($input, true) : $_POST;

define('FM_PATH', $p);

/*************************** ACTIONS ***************************/

// Handle all AJAX Requests
if (isset($_POST['ajax'], $_POST['token'])) {
    if (!verifyToken($_POST['token'])) {
        header('HTTP/1.0 401 Unauthorized');
        die("Invalid Token.");
    }

    // Search
    if (isset($_POST['type']) && $_POST['type'] == "search") {
        $dir = $_POST['path'] == "." ? '' : $_POST['path'];
        $response = scan(fm_clean_path($dir), $_POST['content']);
        echo json_encode($response);
        exit();
    }

    // Save editor file
    if (isset($_POST['type']) && $_POST['type'] == "save") {
        $path = FM_ROOT_PATH;
        if (FM_PATH != '') {
            $path .= '/' . FM_PATH;
        }
        if (!is_dir($path)) {
            http_response_code(400);
            die("Invalid path");
        }
        $file = $_GET['edit'];
        $file = fm_clean_path($file);
        $file = str_replace('/', '', $file);
        if ($file == '' || !is_file($path . '/' . $file)) {
            http_response_code(404);
            die("File not found");
        }
        header('X-XSS-Protection:0');
        $file_path = $path . '/' . $file;
        $writedata = $_POST['content'];
        $fd = fopen($file_path, "w");
        $write_results = @fwrite($fd, $writedata);
        fclose($fd);
        if ($write_results === false) {
            header("HTTP/1.1 500 Internal Server Error");
            die("Could Not Write File!");
        }
        die(true);
    }

    // Backup files
    if (isset($_POST['type']) && $_POST['type'] == "backup" && !empty($_POST['file'])) {
        $fileName = fm_clean_path($_POST['file']);
        $fullPath = FM_ROOT_PATH . '/';
        if (!empty($_POST['path'])) {
            $relativeDirPath = fm_clean_path($_POST['path']);
            $fullPath .= "{$relativeDirPath}/";
        }
        $date = date("dMy-His");
        $newFileName = "{$fileName}-{$date}.bak";
        $fullyQualifiedFileName = $fullPath . $fileName;
        try {
            if (!file_exists($fullyQualifiedFileName)) {
                throw new Exception("File {$fileName} not found");
            }
            if (copy($fullyQualifiedFileName, $fullPath . $newFileName)) {
                echo "Backup {$newFileName} created";
            } else {
                throw new Exception("Could not copy file {$fileName}");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        exit();
    }
}

// Include necessary functions
require_once 'tfm-functions.php';

// Continue with the file manager interface...
// Get current path
$path = FM_ROOT_PATH;
if (FM_PATH != '') {
    $path .= '/' . FM_PATH;
}

// Check path
if (!is_dir($path)) {
    $_GET['p'] = '';
    $p = '';
    $path = FM_ROOT_PATH;
}

// Get parent folder
$parent = fm_get_parent_path(FM_PATH);

$objects = is_readable($path) ? scandir($path) : array();
$folders = array();
$files = array();
$current_path = array_slice(explode("/", $path), -1)[0];

if (is_array($objects) && fm_is_exclude_items($current_path, $path)) {
    foreach ($objects as $file) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        if (!FM_SHOW_HIDDEN && substr($file, 0, 1) === '.') {
            continue;
        }
        $new_path = $path . '/' . $file;
        if (@is_file($new_path) && fm_is_exclude_items($file, $new_path)) {
            $files[] = $file;
        } elseif (@is_dir($new_path) && $file != '.' && $file != '..' && fm_is_exclude_items($file, $new_path)) {
            $folders[] = $file;
        }
    }
}

if (!empty($files)) {
    natcasesort($files);
}
if (!empty($folders)) {
    natcasesort($folders);
}

?>
<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo APP_TITLE ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { background: #1c2429; color: #CFD8DC; padding: 20px; }
        .table { color: #CFD8DC; }
        .table-dark { --bs-table-bg: #2d3238; }
        .btn-outline-primary { color: #b8e59c; border-color: #b8e59c; }
        .btn-outline-primary:hover { background-color: #2d4121; color: #b8e59c; }
        a { color: #b8e59c; text-decoration: none; }
        a:hover { color: #d4f5bd; }
        .breadcrumb { background: transparent; }
        .breadcrumb-item { color: #CFD8DC; }
        .breadcrumb-item.active { color: #b8e59c; }
        i.fa.fa-folder-o { color: #ffd700; }
        i.fa.fa-file-o { color: #b8e59c; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h2 class="mb-4"><i class="fa fa-folder-open"></i> <?php echo APP_TITLE ?></h2>
        
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="?page=filemanager&p="><i class="fa fa-home"></i> Home</a></li>
                <?php
                if ($p != '') {
                    $exploded = explode('/', $p);
                    $parent = '';
                    foreach ($exploded as $i => $part) {
                        if (empty($part)) continue;
                        $parent = trim($parent . '/' . $part, '/');
                        $parent_enc = urlencode($parent);
                        $isLast = ($i == count($exploded) - 1);
                        if ($isLast) {
                            echo '<li class="breadcrumb-item active">' . htmlspecialchars($part) . '</li>';
                        } else {
                            echo '<li class="breadcrumb-item"><a href="?page=filemanager&p=' . $parent_enc . '">' . htmlspecialchars($part) . '</a></li>';
                        }
                    }
                }
                ?>
            </ol>
        </nav>

        <!-- File List -->
        <div class="table-responsive">
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Size</th>
                        <th>Modified</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Parent directory link
                    if ($parent !== false) {
                        echo '<tr>';
                        echo '<td colspan="4"><a href="?page=filemanager&p=' . urlencode($parent) . '"><i class="fa fa-level-up"></i> ..</a></td>';
                        echo '</tr>';
                    }

                    // Folders
                    foreach ($folders as $f) {
                        $modif = date('Y-m-d H:i:s', filemtime($path . '/' . $f));
                        $folder_path = trim(FM_PATH . '/' . $f, '/');
                        echo '<tr>';
                        echo '<td><a href="?page=filemanager&p=' . urlencode($folder_path) . '"><i class="fa fa-folder-o"></i> ' . htmlspecialchars($f) . '</a></td>';
                        echo '<td>-</td>';
                        echo '<td>' . $modif . '</td>';
                        echo '<td>-</td>';
                        echo '</tr>';
                    }

                    // Files
                    foreach ($files as $f) {
                        $file_path = $path . '/' . $f;
                        $modif = date('Y-m-d H:i:s', filemtime($file_path));
                        $filesize = filesize($file_path);
                        $filesize_str = $filesize < 1024 ? $filesize . ' B' : ($filesize < 1048576 ? round($filesize/1024, 2) . ' KB' : round($filesize/1048576, 2) . ' MB');
                        
                        echo '<tr>';
                        echo '<td><i class="fa fa-file-o"></i> ' . htmlspecialchars($f) . '</td>';
                        echo '<td>' . $filesize_str . '</td>';
                        echo '<td>' . $modif . '</td>';
                        echo '<td>';
                        echo '<a href="?page=filemanager&p=' . urlencode(FM_PATH) . '&view=' . urlencode($f) . '" class="btn btn-sm btn-outline-primary me-1" title="View"><i class="fa fa-eye"></i></a>';
                        echo '<a href="?page=filemanager&p=' . urlencode(FM_PATH) . '&dl=' . urlencode($f) . '" class="btn btn-sm btn-outline-primary" title="Download"><i class="fa fa-download"></i></a>';
                        echo '</td>';
                        echo '</tr>';
                    }

                    if (empty($folders) && empty($files)) {
                        echo '<tr><td colspan="4" class="text-center"><em>Folder is empty</em></td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            <a href="?page=dashboard" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php

// Helper functions

function fm_clean_path($path, $trim = true) {
    $path = $trim ? trim($path) : $path;
    $path = trim($path, '\\/');
    $path = str_replace(array('../', '..\\'), '', $path);
    $path = str_replace('\\', '/', $path);
    if ($path == '..') {
        $path = '';
    }
    return $path;
}

function fm_get_parent_path($path) {
    $path = fm_clean_path($path);
    if ($path != '') {
        $array = explode('/', $path);
        if (count($array) > 1) {
            $array = array_slice($array, 0, -1);
            return implode('/', $array);
        }
        return '';
    }
    return false;
}

function fm_is_exclude_items($name, $path) {
    $exclude_items = FM_EXCLUDE_ITEMS;
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if (!in_array($name, $exclude_items) && !in_array("*.$ext", $exclude_items) && !in_array($path, $exclude_items)) {
        return true;
    }
    return false;
}

function verifyToken($token) {
    return hash_equals($_SESSION['token'], $token);
}

class FM_Config {
    var $data;
    function __construct() {
        global $CONFIG;
        $this->data = array(
            'lang' => 'en',
            'error_reporting' => false,
            'show_hidden' => false,
            'hide_Cols' => true,
            'theme' => 'dark'
        );
        if (strlen($CONFIG)) {
            $data = json_decode($CONFIG, true);
            if (is_array($data) && count($data)) {
                $this->data = $data;
            }
        }
    }
}
?>