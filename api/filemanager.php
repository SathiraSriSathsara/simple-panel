<?php
/**
 * File Manager API
 * Handles file operations: list, upload, download, delete, rename, mkdir, info
 */

header('Content-Type: application/json');

// Security: Define base directory (adjust as needed)
define('FM_BASE_DIR', __DIR__ . '/../uploads');

// Create uploads directory if it doesn't exist
if (!file_exists(FM_BASE_DIR)) {
    mkdir(FM_BASE_DIR, 0755, true);
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        listFiles();
        break;
    case 'upload':
        uploadFiles();
        break;
    case 'download':
        downloadFile();
        break;
    case 'delete':
        deleteFiles();
        break;
    case 'rename':
        renameFile();
        break;
    case 'mkdir':
        createDirectory();
        break;
    case 'info':
        getFileInfo();
        break;
    default:
        jsonResponse(false, 'Invalid action');
}

/**
 * List files and directories
 */
function listFiles()
{
    $path = $_GET['path'] ?? '';
    $fullPath = getFullPath($path);
    
    if (!is_dir($fullPath)) {
        // Try to create the directory if it doesn't exist
        if (!file_exists($fullPath) && $path === '') {
            mkdir($fullPath, 0755, true);
        } else {
            jsonResponse(false, 'Directory not found: ' . $fullPath);
            return;
        }
    }
    
    $files = [];
    $items = scandir($fullPath);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        $itemPath = $fullPath . '/' . $item;
        $relativePath = $path ? $path . '/' . $item : $item;
        $isDir = is_dir($itemPath);
        
        $files[] = [
            'name' => $item,
            'path' => $relativePath,
            'type' => $isDir ? 'directory' : 'file',
            'size' => $isDir ? '-' : formatBytes(filesize($itemPath)),
            'modified' => date('Y-m-d H:i:s', filemtime($itemPath)),
            'permissions' => substr(sprintf('%o', fileperms($itemPath)), -4)
        ];
    }
    
    // Sort: directories first, then by name
    usort($files, function($a, $b) {
        if ($a['type'] !== $b['type']) {
            return $a['type'] === 'directory' ? -1 : 1;
        }
        return strcasecmp($a['name'], $b['name']);
    });
    
    jsonResponse(true, 'Files retrieved', ['files' => $files]);
}

/**
 * Upload files
 */
function uploadFiles()
{
    if (!isset($_FILES['files'])) {
        jsonResponse(false, 'No files uploaded');
        return;
    }
    
    $path = $_POST['path'] ?? '';
    $uploadDir = getFullPath($path);
    
    if (!is_dir($uploadDir)) {
        jsonResponse(false, 'Upload directory not found');
        return;
    }
    
    $uploaded = 0;
    $files = $_FILES['files'];
    
    // Handle multiple files
    $fileCount = is_array($files['name']) ? count($files['name']) : 1;
    
    for ($i = 0; $i < $fileCount; $i++) {
        if (is_array($files['name'])) {
            $name = $files['name'][$i];
            $tmpName = $files['tmp_name'][$i];
            $error = $files['error'][$i];
        } else {
            $name = $files['name'];
            $tmpName = $files['tmp_name'];
            $error = $files['error'];
        }
        
        if ($error === UPLOAD_ERR_OK) {
            $destination = $uploadDir . '/' . basename($name);
            
            // Prevent overwriting existing files
            if (file_exists($destination)) {
                $pathInfo = pathinfo($name);
                $counter = 1;
                do {
                    $newName = $pathInfo['filename'] . '_' . $counter . '.' . $pathInfo['extension'];
                    $destination = $uploadDir . '/' . $newName;
                    $counter++;
                } while (file_exists($destination));
            }
            
            if (move_uploaded_file($tmpName, $destination)) {
                $uploaded++;
            }
        }
    }
    
    if ($uploaded > 0) {
        jsonResponse(true, "$uploaded file(s) uploaded successfully");
    } else {
        jsonResponse(false, 'Failed to upload files');
    }
}

/**
 * Download file
 */
function downloadFile()
{
    $path = $_GET['path'] ?? '';
    $fullPath = getFullPath($path);
    
    if (!file_exists($fullPath) || !is_file($fullPath)) {
        header('HTTP/1.0 404 Not Found');
        echo 'File not found';
        return;
    }
    
    $filename = basename($fullPath);
    $mimeType = mime_content_type($fullPath);
    
    header('Content-Type: ' . $mimeType);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($fullPath));
    header('Cache-Control: no-cache');
    
    readfile($fullPath);
    exit;
}

/**
 * Delete files or directories
 */
function deleteFiles()
{
    $data = json_decode(file_get_contents('php://input'), true);
    $paths = $data['paths'] ?? [];
    
    if (empty($paths)) {
        jsonResponse(false, 'No files specified');
        return;
    }
    
    $deleted = 0;
    foreach ($paths as $path) {
        $fullPath = getFullPath($path);
        
        if (file_exists($fullPath)) {
            if (is_dir($fullPath)) {
                if (deleteDirectory($fullPath)) {
                    $deleted++;
                }
            } else {
                if (unlink($fullPath)) {
                    $deleted++;
                }
            }
        }
    }
    
    if ($deleted > 0) {
        jsonResponse(true, "$deleted item(s) deleted successfully");
    } else {
        jsonResponse(false, 'Failed to delete items');
    }
}

/**
 * Rename file or directory
 */
function renameFile()
{
    $data = json_decode(file_get_contents('php://input'), true);
    $path = $data['path'] ?? '';
    $newName = $data['newName'] ?? '';
    
    if (empty($path) || empty($newName)) {
        jsonResponse(false, 'Invalid parameters');
        return;
    }
    
    $fullPath = getFullPath($path);
    $directory = dirname($fullPath);
    $newPath = $directory . '/' . basename($newName);
    
    if (!file_exists($fullPath)) {
        jsonResponse(false, 'File not found');
        return;
    }
    
    if (file_exists($newPath)) {
        jsonResponse(false, 'A file with that name already exists');
        return;
    }
    
    if (rename($fullPath, $newPath)) {
        jsonResponse(true, 'Renamed successfully');
    } else {
        jsonResponse(false, 'Failed to rename');
    }
}

/**
 * Create directory
 */
function createDirectory()
{
    $data = json_decode(file_get_contents('php://input'), true);
    $path = $data['path'] ?? '';
    $name = $data['name'] ?? '';
    
    if (empty($name)) {
        jsonResponse(false, 'Folder name is required');
        return;
    }
    
    $parentDir = getFullPath($path);
    $newDir = $parentDir . '/' . basename($name);
    
    if (file_exists($newDir)) {
        jsonResponse(false, 'A folder with that name already exists');
        return;
    }
    
    if (mkdir($newDir, 0755)) {
        jsonResponse(true, 'Folder created successfully');
    } else {
        jsonResponse(false, 'Failed to create folder');
    }
}

/**
 * Get file information
 */
function getFileInfo()
{
    $path = $_GET['path'] ?? '';
    $fullPath = getFullPath($path);
    
    if (!file_exists($fullPath)) {
        jsonResponse(false, 'File not found');
        return;
    }
    
    $info = [
        'name' => basename($fullPath),
        'path' => $path,
        'type' => is_dir($fullPath) ? 'Directory' : 'File',
        'size' => is_dir($fullPath) ? '-' : formatBytes(filesize($fullPath)),
        'modified' => date('Y-m-d H:i:s', filemtime($fullPath)),
        'permissions' => substr(sprintf('%o', fileperms($fullPath)), -4)
    ];
    
    if (is_file($fullPath)) {
        $info['mime_type'] = mime_content_type($fullPath);
    }
    
    jsonResponse(true, 'File info retrieved', ['info' => $info]);
}

/**
 * Helper: Get full path and validate
 */
function getFullPath($relativePath)
{
    // Security: Remove any directory traversal attempts
    $relativePath = str_replace(['..', '\\'], ['', '/'], $relativePath);
    $relativePath = trim($relativePath, '/');
    
    if (empty($relativePath)) {
        return FM_BASE_DIR;
    }
    
    return FM_BASE_DIR . '/' . $relativePath;
}

/**
 * Helper: Delete directory recursively
 */
function deleteDirectory($dir)
{
    if (!is_dir($dir)) {
        return false;
    }
    
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
            unlink($path);
        }
    }
    
    return rmdir($dir);
}

/**
 * Helper: Format bytes to human readable
 */
function formatBytes($bytes, $precision = 2)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    
    return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * Helper: JSON response
 */
function jsonResponse($success, $message, $data = [])
{
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message
    ], $data));
    exit;
}
