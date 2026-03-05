<?php
/**
 * Database Management - phpMyAdmin Integration
 * Themed to match panel colors: #1f3a68, #2d538f, #5f9cf8
 */

require_once COMPONENTS_PATH . '/stat-card.php';

function db_page_url($params = []) {
    $base = ['page' => 'databases'];
    $query = array_merge($base, $params);
    if (function_exists('url_with_lang')) {
        return url_with_lang($query);
    }
    return '?' . http_build_query($query);
}

function db_load_connections($file) {
    if (!file_exists($file)) {
        return [];
    }

    $json = @file_get_contents($file);
    if ($json === false || trim($json) === '') {
        return [];
    }

    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function db_save_connections($file, array $connections) {
    $dir = dirname($file);
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    return @file_put_contents($file, json_encode(array_values($connections), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) !== false;
}

function db_sanitize_name($value) {
    $value = trim((string)$value);
    return preg_replace('/[^a-zA-Z0-9_\-. ]/', '_', $value);
}

function db_sanitize_identifier($value) {
    $value = trim((string)$value);
    if ($value === '') {
        return '';
    }
    return preg_match('/^[a-zA-Z0-9_]+$/', $value) ? $value : '';
}

function db_resolve_sqlite_path(array $conn) {
    $path = trim((string)($conn['path'] ?? ''));
    if ($path === '') {
        $path = 'data/sqlite/main.db';
    }

    if (preg_match('/^[A-Za-z]:\\\\/', $path) || strpos($path, '/') === 0) {
        $full = $path;
    } else {
        $full = BASE_PATH . '/' . ltrim($path, '/\\');
    }

    $dir = dirname($full);
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }

    return $full;
}

function db_resolve_sqlite_database_path(array $conn, $databaseName = '') {
    $base = db_resolve_sqlite_path($conn);
    if (trim((string)$databaseName) === '') {
        return $base;
    }

    $dir = dirname($base);
    $safe = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', (string)$databaseName);
    if (substr($safe, -3) !== '.db') {
        $safe .= '.db';
    }
    return $dir . '/' . $safe;
}

function db_get_conn_by_id(array $connections, $id) {
    foreach ($connections as $conn) {
        if (($conn['id'] ?? '') === $id) {
            return $conn;
        }
    }
    return null;
}

function db_call($object, $method, ...$args) {
    if (!is_object($object) || !method_exists($object, $method)) {
        throw new Exception('Method not available: ' . $method);
    }
    return $object->$method(...$args);
}

function db_connect_for_server_ops(array $conn, &$error = null) {
    $error = null;
    $type = $conn['type'];

    try {
        if ($type === 'mysql') {
            $dsn = 'mysql:host=' . ($conn['host'] ?: '127.0.0.1') . ';port=' . ((int)$conn['port'] ?: 3306) . ';charset=utf8mb4';
            $pdo = new PDO($dsn, $conn['username'] ?? '', $conn['password'] ?? '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $pdo;
        }

        if ($type === 'pgsql') {
            $db = trim((string)($conn['database'] ?? ''));
            if ($db === '') {
                $db = 'postgres';
            }
            $dsn = 'pgsql:host=' . ($conn['host'] ?: '127.0.0.1') . ';port=' . ((int)$conn['port'] ?: 5432) . ';dbname=' . $db;
            $pdo = new PDO($dsn, $conn['username'] ?? '', $conn['password'] ?? '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $pdo;
        }

        if ($type === 'sqlite') {
            $path = db_resolve_sqlite_path($conn);
            $pdo = new PDO('sqlite:' . $path, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $pdo;
        }

        if ($type === 'mongodb') {
            if (!class_exists('MongoDB\\Client')) {
                $error = 'MongoDB extension/library is not installed.';
                return null;
            }
            $host = trim((string)($conn['host'] ?? '127.0.0.1'));
            $port = (int)($conn['port'] ?? 27017);
            $user = trim((string)($conn['username'] ?? ''));
            $pass = (string)($conn['password'] ?? '');

            if ($user !== '') {
                $uri = 'mongodb://' . rawurlencode($user) . ':' . rawurlencode($pass) . '@' . $host . ':' . $port;
            } else {
                $uri = 'mongodb://' . $host . ':' . $port;
            }

            $mongoClass = 'MongoDB\\Client';
            return new $mongoClass($uri);
        }

        if ($type === 'redis') {
            if (!class_exists('Redis')) {
                $error = 'Redis extension is not installed.';
                return null;
            }

            $redisClass = 'Redis';
            $redis = new $redisClass();
            $ok = @db_call($redis, 'connect', $conn['host'] ?: '127.0.0.1', (int)($conn['port'] ?: 6379), 5.0);
            if (!$ok) {
                $error = 'Unable to connect to Redis server.';
                return null;
            }

            $pass = (string)($conn['password'] ?? '');
            if ($pass !== '') {
                if (!@db_call($redis, 'auth', $pass)) {
                    $error = 'Redis authentication failed.';
                    return null;
                }
            }

            return $redis;
        }

        $error = 'Unsupported database type.';
        return null;
    } catch (Throwable $e) {
        $error = $e->getMessage();
        return null;
    }
}

function db_connect_with_database(array $conn, $databaseName, &$error = null) {
    $error = null;
    $type = $conn['type'];

    try {
        if ($type === 'mysql') {
            $dsn = 'mysql:host=' . ($conn['host'] ?: '127.0.0.1') . ';port=' . ((int)$conn['port'] ?: 3306) . ';dbname=' . $databaseName . ';charset=utf8mb4';
            return new PDO($dsn, $conn['username'] ?? '', $conn['password'] ?? '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        if ($type === 'pgsql') {
            $dsn = 'pgsql:host=' . ($conn['host'] ?: '127.0.0.1') . ';port=' . ((int)$conn['port'] ?: 5432) . ';dbname=' . $databaseName;
            return new PDO($dsn, $conn['username'] ?? '', $conn['password'] ?? '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        if ($type === 'sqlite') {
            $path = db_resolve_sqlite_database_path($conn, $databaseName);
            return new PDO('sqlite:' . $path, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        return db_connect_for_server_ops($conn, $error);
    } catch (Throwable $e) {
        $error = $e->getMessage();
        return null;
    }
}

function db_list_databases(array $conn, &$error = null) {
    $error = null;
    $type = $conn['type'];
    $client = db_connect_for_server_ops($conn, $error);
    if ($client === null) {
        return [];
    }

    try {
        if ($type === 'mysql') {
            $rows = $client->query('SHOW DATABASES')->fetchAll(PDO::FETCH_NUM);
            return array_map(function ($r) { return $r[0]; }, $rows);
        }

        if ($type === 'pgsql') {
            $rows = $client->query("SELECT datname FROM pg_database WHERE datistemplate = false ORDER BY datname")->fetchAll(PDO::FETCH_ASSOC);
            return array_map(function ($r) { return $r['datname']; }, $rows);
        }

        if ($type === 'sqlite') {
            $path = db_resolve_sqlite_path($conn);
            $dir = dirname($path);
            $items = @scandir($dir);
            if (!is_array($items)) {
                return [basename($path)];
            }
            $dbs = [];
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') {
                    continue;
                }
                if (substr(strtolower($item), -3) === '.db') {
                    $dbs[] = $item;
                }
            }
            sort($dbs);
            return !empty($dbs) ? $dbs : [basename($path)];
        }

        if ($type === 'mongodb') {
            $names = [];
            $dbList = db_call($client, 'listDatabases');
            foreach ($dbList as $dbInfo) {
                $names[] = db_call($dbInfo, 'getName');
            }
            sort($names);
            return $names;
        }

        if ($type === 'redis') {
            $count = 16;
            $cfg = @db_call($client, 'config', 'GET', 'databases');
            if (is_array($cfg) && isset($cfg['databases'])) {
                $count = (int)$cfg['databases'];
            }
            $dbs = [];
            for ($i = 0; $i < $count; $i++) {
                $dbs[] = (string)$i;
            }
            return $dbs;
        }

        return [];
    } catch (Throwable $e) {
        $error = $e->getMessage();
        return [];
    }
}

function db_list_tables(array $conn, $databaseName, &$error = null) {
    $error = null;
    $type = $conn['type'];
    $databaseName = (string)$databaseName;

    try {
        if ($type === 'mysql') {
            $pdo = db_connect_with_database($conn, $databaseName, $error);
            if ($pdo === null) {
                return [];
            }
            $rows = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_NUM);
            return array_map(function ($r) { return $r[0]; }, $rows);
        }

        if ($type === 'pgsql') {
            $pdo = db_connect_with_database($conn, $databaseName, $error);
            if ($pdo === null) {
                return [];
            }
            $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema='public' ORDER BY table_name");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map(function ($r) { return $r['table_name']; }, $rows);
        }

        if ($type === 'sqlite') {
            $pdo = db_connect_with_database($conn, $databaseName, $error);
            if ($pdo === null) {
                return [];
            }
            $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map(function ($r) { return $r['name']; }, $rows);
        }

        if ($type === 'mongodb') {
            $client = db_connect_for_server_ops($conn, $error);
            if ($client === null) {
                return [];
            }
            $collections = [];
            $dbObj = db_call($client, 'selectDatabase', $databaseName);
            $colList = db_call($dbObj, 'listCollections');
            foreach ($colList as $collection) {
                $collections[] = db_call($collection, 'getName');
            }
            sort($collections);
            return $collections;
        }

        if ($type === 'redis') {
            $client = db_connect_for_server_ops($conn, $error);
            if ($client === null) {
                return [];
            }
            $idx = ctype_digit($databaseName) ? (int)$databaseName : 0;
            db_call($client, 'select', $idx);
            $keys = db_call($client, 'keys', '*');
            if (!is_array($keys)) {
                return [];
            }
            sort($keys);
            return array_slice($keys, 0, 200);
        }

        return [];
    } catch (Throwable $e) {
        $error = $e->getMessage();
        return [];
    }
}

$connections = db_load_connections($connection_store_file);
$message = '';
$is_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_connection') {
        $type = $_POST['type'] ?? '';
        $mode = $_POST['mode'] ?? 'local';
        $name = db_sanitize_name($_POST['name'] ?? '');
        $host = trim((string)($_POST['host'] ?? ''));
        $port = trim((string)($_POST['port'] ?? ''));
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $database = db_sanitize_name($_POST['database'] ?? '');
        $path = trim((string)($_POST['path'] ?? ''));

        if (!in_array($type, ['sqlite', 'mysql', 'pgsql', 'mongodb', 'redis'], true)) {
            $message = 'Unsupported database type.';
        } elseif ($name === '') {
            $message = 'Connection name is required.';
        } else {
            $new = [
                'id' => function_exists('random_bytes') ? bin2hex(random_bytes(6)) : uniqid('dbc_', true),
                'name' => $name,
                'type' => $type,
                'mode' => in_array($mode, ['local', 'remote'], true) ? $mode : 'local',
                'host' => $host,
                'port' => $port,
                'username' => $username,
                'password' => $password,
                'database' => $database,
                'path' => $path,
            ];

            if ($type === 'sqlite' && $new['path'] === '') {
                $new['path'] = 'data/sqlite/' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', strtolower($name)) . '.db';
            }

            $connections[] = $new;
            if (db_save_connections($connection_store_file, $connections)) {
                $is_success = true;
                $message = 'Connection added successfully.';
            } else {
                $message = 'Failed to save connection.';
            }
        }
    }

    if ($action === 'delete_connection') {
        $id = $_POST['id'] ?? '';
        $connections = array_values(array_filter($connections, function ($c) use ($id) {
            return ($c['id'] ?? '') !== $id;
        }));
        if (db_save_connections($connection_store_file, $connections)) {
            $is_success = true;
            $message = 'Connection removed.';
        } else {
            $message = 'Failed to remove connection.';
        }
    }
}

$active_conn_id = $_GET['conn'] ?? '';
$active_connection = db_get_conn_by_id($connections, $active_conn_id);
$databases = [];
$tables = [];
$db_error = '';
$selected_database = $_GET['db'] ?? '';

if ($active_connection) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        if (in_array($action, ['create_database', 'drop_database', 'create_table', 'drop_table'], true)) {
            $db_name = db_sanitize_identifier($_POST['database_name'] ?? $selected_database);
            $table_name = db_sanitize_identifier($_POST['table_name'] ?? '');

            $conn_type = $active_connection['type'];
            $client = db_connect_for_server_ops($active_connection, $op_error);

            if ($client === null) {
                $message = 'Connection failed: ' . $op_error;
                $is_success = false;
            } else {
                try {
                    if ($action === 'create_database') {
                        if ($db_name === '') {
                            throw new Exception('Enter a valid database name. Use letters, numbers, and underscore only.');
                        }

                        if ($conn_type === 'mysql') {
                            $client->exec('CREATE DATABASE IF NOT EXISTS `' . $db_name . '`');
                        } elseif ($conn_type === 'pgsql') {
                            $client->exec('CREATE DATABASE "' . $db_name . '"');
                        } elseif ($conn_type === 'sqlite') {
                            $new_file = db_resolve_sqlite_database_path($active_connection, $db_name);
                            new PDO('sqlite:' . $new_file);
                        } elseif ($conn_type === 'mongodb') {
                            $active_connection['database'] = $db_name;
                            $mdb = db_connect_for_server_ops($active_connection, $op_error);
                            if ($mdb === null) {
                                throw new Exception($op_error ?: 'MongoDB connection failed.');
                            }
                            $dbObj = db_call($mdb, 'selectDatabase', $db_name);
                            db_call($dbObj, 'createCollection', '__init_collection');
                        } elseif ($conn_type === 'redis') {
                            throw new Exception('Redis logical databases are configured server-side. Cannot create new DB index here.');
                        }

                        $is_success = true;
                        $message = 'Database created successfully.';
                    }

                    if ($action === 'drop_database') {
                        if ($db_name === '') {
                            throw new Exception('Select a valid database to drop.');
                        }

                        if ($conn_type === 'mysql') {
                            $client->exec('DROP DATABASE `' . $db_name . '`');
                        } elseif ($conn_type === 'pgsql') {
                            $client->exec('DROP DATABASE "' . $db_name . '"');
                        } elseif ($conn_type === 'sqlite') {
                            $file = db_resolve_sqlite_database_path($active_connection, $db_name);
                            if (file_exists($file)) {
                                @unlink($file);
                            }
                        } elseif ($conn_type === 'mongodb') {
                            $active_connection['database'] = $db_name;
                            $mdb = db_connect_for_server_ops($active_connection, $op_error);
                            if ($mdb === null) {
                                throw new Exception($op_error ?: 'MongoDB connection failed.');
                            }
                            $dbObj = db_call($mdb, 'selectDatabase', $db_name);
                            db_call($dbObj, 'drop');
                        } elseif ($conn_type === 'redis') {
                            if (!ctype_digit($db_name)) {
                                throw new Exception('Redis database must be a numeric index.');
                            }
                            db_call($client, 'select', (int)$db_name);
                            db_call($client, 'flushDB');
                        }

                        $is_success = true;
                        $message = 'Database dropped successfully.';
                    }

                    if ($action === 'create_table') {
                        if ($db_name === '') {
                            throw new Exception('Select a database first.');
                        }
                        if ($table_name === '') {
                            throw new Exception('Enter a valid table/collection name.');
                        }

                        if ($conn_type === 'mysql') {
                            $pdo = db_connect_with_database($active_connection, $db_name, $op_error2);
                            if ($pdo === null) {
                                throw new Exception($op_error2 ?: 'MySQL connection failed.');
                            }
                            $pdo->exec('CREATE TABLE IF NOT EXISTS `' . $table_name . '` (id INT AUTO_INCREMENT PRIMARY KEY, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)');
                        } elseif ($conn_type === 'pgsql') {
                            $pdo = db_connect_with_database($active_connection, $db_name, $op_error2);
                            if ($pdo === null) {
                                throw new Exception($op_error2 ?: 'PostgreSQL connection failed.');
                            }
                            $pdo->exec('CREATE TABLE IF NOT EXISTS "' . $table_name . '" (id SERIAL PRIMARY KEY, created_at TIMESTAMP DEFAULT NOW())');
                        } elseif ($conn_type === 'sqlite') {
                            $pdo = db_connect_with_database($active_connection, $db_name, $op_error2);
                            if ($pdo === null) {
                                throw new Exception($op_error2 ?: 'SQLite connection failed.');
                            }
                            $pdo->exec('CREATE TABLE IF NOT EXISTS "' . $table_name . '" (id INTEGER PRIMARY KEY AUTOINCREMENT, created_at TEXT DEFAULT CURRENT_TIMESTAMP)');
                        } elseif ($conn_type === 'mongodb') {
                            $active_connection['database'] = $db_name;
                            $mdb = db_connect_for_server_ops($active_connection, $op_error2);
                            if ($mdb === null) {
                                throw new Exception($op_error2 ?: 'MongoDB connection failed.');
                            }
                            $dbObj = db_call($mdb, 'selectDatabase', $db_name);
                            db_call($dbObj, 'createCollection', $table_name);
                        } elseif ($conn_type === 'redis') {
                            if (!ctype_digit($db_name)) {
                                throw new Exception('Redis database must be numeric.');
                            }
                            db_call($client, 'select', (int)$db_name);
                            db_call($client, 'set', $table_name, 'created:' . date('c'));
                        }

                        $is_success = true;
                        $message = 'Table/collection created successfully.';
                    }

                    if ($action === 'drop_table') {
                        if ($db_name === '') {
                            throw new Exception('Select a database first.');
                        }
                        if ($table_name === '') {
                            throw new Exception('Select a valid table/collection/key name.');
                        }

                        if ($conn_type === 'mysql') {
                            $pdo = db_connect_with_database($active_connection, $db_name, $op_error2);
                            if ($pdo === null) {
                                throw new Exception($op_error2);
                            }
                            $pdo->exec('DROP TABLE IF EXISTS `' . $table_name . '`');
                        } elseif ($conn_type === 'pgsql') {
                            $pdo = db_connect_with_database($active_connection, $db_name, $op_error2);
                            if ($pdo === null) {
                                throw new Exception($op_error2);
                            }
                            $pdo->exec('DROP TABLE IF EXISTS "' . $table_name . '"');
                        } elseif ($conn_type === 'sqlite') {
                            $pdo = db_connect_with_database($active_connection, $db_name, $op_error2);
                            if ($pdo === null) {
                                throw new Exception($op_error2);
                            }
                            $pdo->exec('DROP TABLE IF EXISTS "' . $table_name . '"');
                        } elseif ($conn_type === 'mongodb') {
                            $active_connection['database'] = $db_name;
                            $mdb = db_connect_for_server_ops($active_connection, $op_error2);
                            if ($mdb === null) {
                                throw new Exception($op_error2);
                            }
                            $dbObj = db_call($mdb, 'selectDatabase', $db_name);
                            db_call($dbObj, 'dropCollection', $table_name);
                        } elseif ($conn_type === 'redis') {
                            if (!ctype_digit($db_name)) {
                                throw new Exception('Redis database must be numeric.');
                            }
                            db_call($client, 'select', (int)$db_name);
                            db_call($client, 'del', $table_name);
                        }

                        $is_success = true;
                        $message = 'Table/collection dropped successfully.';
                    }
                } catch (Throwable $e) {
                    $is_success = false;
                    $message = $e->getMessage();
                }
            }
        }
    }

    $databases = db_list_databases($active_connection, $db_error);
    if ($selected_database === '') {
        $selected_database = $active_connection['database'] ?? '';
        if ($selected_database === '' && !empty($databases)) {
            $selected_database = $databases[0];
        }
    }

    if ($selected_database !== '') {
        $tables = db_list_tables($active_connection, $selected_database, $db_error2);
        if ($db_error2) {
            $db_error = $db_error2;
        }
    }
}

$total_connections = count($connections);
$total_databases = count($databases);
$total_tables = count($tables);
$active_engine = $active_connection['type'] ?? '-';
?>

<style>
.db-wrap {
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    border: 1px solid #dfe9f7;
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 12px 26px rgba(18, 46, 84, 0.06);
    margin-bottom: 20px;
}

.db-title {
    margin: 0 0 16px 0;
    color: #142c4e;
    font-size: 1.2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}

.db-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 12px;
}

.db-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.db-field label {
    font-size: 0.84rem;
    color: #4f6688;
    font-weight: 600;
}

.db-input,
.db-select {
    border: 1px solid #c8daf5;
    border-radius: 10px;
    padding: 10px 12px;
    background: #fff;
    color: #163256;
}

.db-btn {
    border: none;
    border-radius: 10px;
    padding: 10px 14px;
    background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%);
    color: #fff;
    font-weight: 600;
    cursor: pointer;
}

.db-btn.secondary {
    background: #edf3ff;
    color: #1f3a68;
    border: 1px solid #c7daf8;
}

.db-btn.danger {
    background: #d94b4b;
}

.db-btn-row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

.db-message {
    border-radius: 12px;
    padding: 10px 14px;
    margin: 14px 0;
    border: 1px solid;
}

.db-message.success {
    background: #e9f9ef;
    border-color: #b6e8ca;
    color: #20784a;
}

.db-message.error {
    background: #ffecec;
    border-color: #f8baba;
    color: #a63a3a;
}

.conn-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 12px;
    margin-top: 14px;
}

.conn-card {
    border: 1px solid #d9e6fb;
    border-radius: 12px;
    background: #fff;
    padding: 12px;
}

.conn-card h4 {
    margin: 0 0 6px;
    color: #142c4e;
    font-size: 1rem;
}

.conn-meta {
    color: #5c7394;
    font-size: 0.85rem;
    margin-bottom: 10px;
}

.db-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border: 1px solid #dbe6f5;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
}

.db-table th,
.db-table td {
    padding: 10px 12px;
    border-bottom: 1px solid #eef3fb;
    text-align: left;
}

.db-table thead {
    background: #edf3fd;
}

.db-table th {
    color: #1f3a68;
}

.db-table tr:last-child td {
    border-bottom: none;
}

.db-note {
    color: #637b9b;
    font-size: 0.85rem;
    margin-top: 10px;
}

@media (max-width: 900px) {
    .db-wrap {
        padding: 16px;
    }
}
</style>

<div class="stats-grid">
    <?php
    render_stat_card('fas fa-link', t('Connections'), (string)$total_connections);
    render_stat_card('fas fa-database', t('Databases'), (string)$total_databases);
    render_stat_card('fas fa-table', t('Tables/Collections'), (string)$total_tables);
    render_stat_card('fas fa-microchip', t('Active Engine'), strtoupper((string)$active_engine));
    ?>
</div>

<?php if ($message !== ''): ?>
<div class="db-message <?php echo $is_success ? 'success' : 'error'; ?>">
    <?php echo htmlspecialchars($message); ?>
</div>
<?php endif; ?>

<?php if ($db_error): ?>
<div class="db-message error"><?php echo htmlspecialchars($db_error); ?></div>
<?php endif; ?>

<div class="db-wrap">
    <h3 class="db-title"><i class="fas fa-plug"></i> Add Database Connection</h3>
    <form method="post" action="<?php echo htmlspecialchars(db_page_url()); ?>">
        <input type="hidden" name="action" value="add_connection">
        <div class="db-grid">
            <div class="db-field">
                <label>Connection Name</label>
                <input class="db-input" type="text" name="name" placeholder="Production MySQL" required>
            </div>
            <div class="db-field">
                <label>Database Type</label>
                <select class="db-select" name="type" required>
                    <option value="mysql">MySQL</option>
                    <option value="pgsql">PostgreSQL</option>
                    <option value="sqlite">SQLite</option>
                    <option value="mongodb">MongoDB</option>
                    <option value="redis">Redis</option>
                </select>
            </div>
            <div class="db-field">
                <label>Location</label>
                <select class="db-select" name="mode">
                    <option value="local">Local</option>
                    <option value="remote">Remote</option>
                </select>
            </div>
            <div class="db-field">
                <label>Host</label>
                <input class="db-input" type="text" name="host" placeholder="127.0.0.1">
            </div>
            <div class="db-field">
                <label>Port</label>
                <input class="db-input" type="text" name="port" placeholder="3306 / 5432 / 27017 / 6379">
            </div>
            <div class="db-field">
                <label>Username</label>
                <input class="db-input" type="text" name="username" placeholder="root">
            </div>
            <div class="db-field">
                <label>Password</label>
                <input class="db-input" type="password" name="password" placeholder="••••••">
            </div>
            <div class="db-field">
                <label>Default Database</label>
                <input class="db-input" type="text" name="database" placeholder="app_db (for SQL/Mongo)">
            </div>
            <div class="db-field" style="grid-column: 1 / -1;">
                <label>SQLite Path (only for SQLite)</label>
                <input class="db-input" type="text" name="path" placeholder="data/sqlite/main.db">
            </div>
        </div>
        <div class="db-btn-row" style="margin-top: 12px;">
            <button class="db-btn" type="submit"><i class="fas fa-plus"></i> Add Connection</button>
        </div>
        <div class="db-note">Connections can be local or remote. SQLite uses file path; other engines use host/port credentials.</div>
    </form>
</div>

<div class="db-wrap">
    <h3 class="db-title"><i class="fas fa-server"></i> Saved Connections</h3>
    <?php if (empty($connections)): ?>
        <p class="db-note">No connections added yet.</p>
    <?php else: ?>
        <div class="conn-list">
            <?php foreach ($connections as $conn): ?>
                <?php
                $is_active = (($conn['id'] ?? '') === $active_conn_id);
                $type = strtoupper($conn['type'] ?? '');
                $host = ($conn['type'] ?? '') === 'sqlite' ? ($conn['path'] ?? '-') : (($conn['host'] ?: '127.0.0.1') . ':' . ($conn['port'] ?: '-'));
                ?>
                <div class="conn-card" style="<?php echo $is_active ? 'border-color:#5f9cf8; box-shadow:0 0 0 2px rgba(95,156,248,0.15);' : ''; ?>">
                    <h4><?php echo htmlspecialchars($conn['name'] ?? 'Connection'); ?></h4>
                    <div class="conn-meta"><?php echo htmlspecialchars($type); ?> | <?php echo htmlspecialchars($host); ?> | <?php echo htmlspecialchars(($conn['mode'] ?? 'local')); ?></div>
                    <div class="db-btn-row">
                        <a class="db-btn secondary" href="<?php echo htmlspecialchars(db_page_url(['conn' => $conn['id']])); ?>">Connect</a>
                        <form method="post" action="<?php echo htmlspecialchars(db_page_url()); ?>" onsubmit="return confirm('Delete this connection?');">
                            <input type="hidden" name="action" value="delete_connection">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($conn['id']); ?>">
                            <button class="db-btn danger" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($active_connection): ?>
<div class="db-wrap">
    <h3 class="db-title"><i class="fas fa-database"></i> Manage: <?php echo htmlspecialchars($active_connection['name']); ?></h3>

    <form method="get" action="<?php echo htmlspecialchars(db_page_url()); ?>" style="margin-bottom: 14px;">
        <input type="hidden" name="page" value="databases">
        <input type="hidden" name="conn" value="<?php echo htmlspecialchars($active_conn_id); ?>">
        <div class="db-grid">
            <div class="db-field">
                <label>Select Database</label>
                <select class="db-select" name="db" onchange="this.form.submit()">
                    <option value="">-- select --</option>
                    <?php foreach ($databases as $db): ?>
                        <option value="<?php echo htmlspecialchars($db); ?>" <?php echo ($selected_database === $db) ? 'selected' : ''; ?>><?php echo htmlspecialchars($db); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>

    <div class="db-grid" style="margin-bottom: 14px;">
        <div class="db-field">
            <label>Create Database</label>
            <form method="post" action="<?php echo htmlspecialchars(db_page_url(['conn' => $active_conn_id, 'db' => $selected_database])); ?>" class="db-btn-row">
                <input type="hidden" name="action" value="create_database">
                <input class="db-input" type="text" name="database_name" placeholder="new_database" required>
                <button class="db-btn" type="submit">Create</button>
            </form>
        </div>
        <div class="db-field">
            <label>Drop Database</label>
            <form method="post" action="<?php echo htmlspecialchars(db_page_url(['conn' => $active_conn_id, 'db' => $selected_database])); ?>" class="db-btn-row" onsubmit="return confirm('Drop selected database? This cannot be undone.');">
                <input type="hidden" name="action" value="drop_database">
                <input class="db-input" type="text" name="database_name" value="<?php echo htmlspecialchars($selected_database); ?>" placeholder="database_name" required>
                <button class="db-btn danger" type="submit">Drop</button>
            </form>
        </div>
    </div>

    <div class="db-grid" style="margin-bottom: 14px;">
        <div class="db-field">
            <label>Create Table / Collection</label>
            <form method="post" action="<?php echo htmlspecialchars(db_page_url(['conn' => $active_conn_id, 'db' => $selected_database])); ?>" class="db-btn-row">
                <input type="hidden" name="action" value="create_table">
                <input type="hidden" name="database_name" value="<?php echo htmlspecialchars($selected_database); ?>">
                <input class="db-input" type="text" name="table_name" placeholder="table_name" required>
                <button class="db-btn" type="submit">Create</button>
            </form>
        </div>
        <div class="db-field">
            <label>Drop Table / Collection</label>
            <form method="post" action="<?php echo htmlspecialchars(db_page_url(['conn' => $active_conn_id, 'db' => $selected_database])); ?>" class="db-btn-row" onsubmit="return confirm('Drop table/collection/key?');">
                <input type="hidden" name="action" value="drop_table">
                <input type="hidden" name="database_name" value="<?php echo htmlspecialchars($selected_database); ?>">
                <input class="db-input" type="text" name="table_name" placeholder="table_name" required>
                <button class="db-btn danger" type="submit">Drop</button>
            </form>
        </div>
    </div>

    <table class="db-table">
        <thead>
            <tr>
                <th style="width: 40%;">Databases</th>
                <th style="width: 60%;">Tables / Collections / Keys</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <?php if (empty($databases)): ?>
                        <span class="db-note">No databases available.</span>
                    <?php else: ?>
                        <?php foreach ($databases as $db): ?>
                            <div><a href="<?php echo htmlspecialchars(db_page_url(['conn' => $active_conn_id, 'db' => $db])); ?>"><?php echo htmlspecialchars($db); ?></a></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($selected_database === ''): ?>
                        <span class="db-note">Select a database to list tables/collections.</span>
                    <?php elseif (empty($tables)): ?>
                        <span class="db-note">No tables/collections/keys found in <?php echo htmlspecialchars($selected_database); ?>.</span>
                    <?php else: ?>
                        <?php foreach ($tables as $tbl): ?>
                            <div><?php echo htmlspecialchars($tbl); ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="db-note">
        Redis note: Redis uses logical DB indexes and keys (not SQL tables). MongoDB uses collections. SQL engines use tables.
    </div>
</div>
<?php endif; ?>
