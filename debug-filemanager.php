<?php
// Debug script to check file manager paths
require_once __DIR__ . '/../config/config.php';

echo "<h2>File Manager Debug Info</h2>";
echo "<pre>";
echo "BASE_PATH: " . BASE_PATH . "\n";
echo "Uploads dir: " . BASE_PATH . '/uploads' . "\n";
echo "Uploads dir exists: " . (file_exists(BASE_PATH . '/uploads') ? 'YES' : 'NO') . "\n";
echo "Uploads dir is_dir: " . (is_dir(BASE_PATH . '/uploads') ? 'YES' : 'NO') . "\n";
echo "Uploads dir is readable: " . (is_readable(BASE_PATH . '/uploads') ? 'YES' : 'NO') . "\n\n";

echo "API __DIR__: Would be " . __DIR__ . "/../api\n";
echo "FM_BASE_DIR would be: " . __DIR__ . "/uploads\n";
echo "FM_BASE_DIR exists: " . (file_exists(__DIR__ . '/uploads') ? 'YES' : 'NO') . "\n\n";

if (is_dir(BASE_PATH . '/uploads')) {
    echo "Files in uploads directory:\n";
    $files = scandir(BASE_PATH . '/uploads');
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "  - $file\n";
        }
    }
}
echo "</pre>";

echo "<h3>Test API Call</h3>";
echo "<button onclick='testAPI()'>Test API</button>";
echo "<div id='result'></div>";
?>

<script>
function testAPI() {
    fetch('/api/filemanager.php?action=list&path=')
        .then(response => response.text())
        .then(text => {
            document.getElementById('result').innerHTML = '<pre>' + text + '</pre>';
        })
        .catch(error => {
            document.getElementById('result').innerHTML = '<pre style="color:red;">Error: ' + error + '</pre>';
        });
}
</script>
