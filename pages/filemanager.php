<?php
/**
 * Simple File Manager - No Authentication
 * Based on Tiny File Manager concept
 */

// Runtime limits for long uploads (size limits come from server php.ini/.htaccess)
ini_set('max_execution_time', '600');
ini_set('max_input_time', '600');
ini_set('memory_limit', '512M');

function parseIniSizeToBytes($value) {
    $value = trim((string)$value);
    if ($value === '') {
        return 0;
    }

    $number = (float)$value;
    $unit = strtolower(substr($value, -1));

    switch ($unit) {
        case 'g':
            return (int)($number * 1024 * 1024 * 1024);
        case 'm':
            return (int)($number * 1024 * 1024);
        case 'k':
            return (int)($number * 1024);
        default:
            return (int)$number;
    }
}

$upload_max_bytes = parseIniSizeToBytes(ini_get('upload_max_filesize'));
$post_max_bytes = parseIniSizeToBytes(ini_get('post_max_size'));
$effective_upload_limit_bytes = $upload_max_bytes > 0 && $post_max_bytes > 0
    ? min($upload_max_bytes, $post_max_bytes)
    : max($upload_max_bytes, $post_max_bytes);

function sanitizeItemName($name) {
    $name = trim((string)$name);
    $name = str_replace(['/', '\\'], '', $name);
    $name = preg_replace('/[^a-zA-Z0-9._ -]/', '_', $name);
    return trim($name);
}

// Root directory for file manager
$fm_root = BASE_PATH . '/uploads';

// Get current path
$fm_path = isset($_GET['fm_path']) ? $_GET['fm_path'] : '';
$fm_path = str_replace(['..', '\\'], '', $fm_path);
$fm_path = trim($fm_path, '/');

// Full path
$full_path = $fm_root . ($fm_path ? '/' . $fm_path : '');

// Create uploads directory if not exists
if (!is_dir($fm_root)) {
    mkdir($fm_root, 0755, true);
}

// Ensure path exists
if (!is_dir($full_path)) {
    $fm_path = '';
    $full_path = $fm_root;
}

$full_path_real = realpath($full_path);
$fm_root_real = realpath($fm_root);

if ($full_path_real === false || $fm_root_real === false || strpos($full_path_real, $fm_root_real) !== 0) {
    $fm_path = '';
    $full_path = $fm_root;
}

// Handle actions (create folder, create file, upload)
$action_message = '';
$action_success = false;

if (isset($_POST['create_folder'])) {
    $folder_name = sanitizeItemName($_POST['folder_name'] ?? '');

    if ($folder_name === '' || $folder_name === '.' || $folder_name === '..') {
        $action_message = 'Please enter a valid folder name';
    } else {
        $target_folder = $full_path . '/' . $folder_name;
        if (file_exists($target_folder)) {
            $action_message = 'Folder already exists: ' . htmlspecialchars($folder_name);
        } elseif (mkdir($target_folder, 0755, true)) {
            $action_success = true;
            $action_message = 'Folder created successfully: ' . htmlspecialchars($folder_name);
        } else {
            $action_message = 'Failed to create folder';
        }
    }
} elseif (isset($_POST['create_file'])) {
    $new_file_name = sanitizeItemName($_POST['new_file_name'] ?? '');

    if ($new_file_name === '' || $new_file_name === '.' || $new_file_name === '..') {
        $action_message = 'Please enter a valid file name';
    } else {
        $target_new_file = $full_path . '/' . $new_file_name;
        if (file_exists($target_new_file)) {
            $action_message = 'File already exists: ' . htmlspecialchars($new_file_name);
        } elseif (file_put_contents($target_new_file, '') !== false) {
            $action_success = true;
            $action_message = 'File created successfully: ' . htmlspecialchars($new_file_name);
        } else {
            $action_message = 'Failed to create file';
        }
    }
} elseif (isset($_POST['save_file']) && isset($_GET['edit'])) {
    $edit_name = sanitizeItemName($_GET['edit']);
    $target_edit_file = $full_path . '/' . $edit_name;
    $target_edit_real = realpath($target_edit_file);

    if ($edit_name === '' || $target_edit_real === false || strpos($target_edit_real, $fm_root_real) !== 0 || !is_file($target_edit_real)) {
        $action_message = 'Invalid file path for editing';
    } else {
        $new_content = isset($_POST['file_content']) ? (string)$_POST['file_content'] : '';
        if (file_put_contents($target_edit_real, $new_content) !== false) {
            $action_success = true;
            $action_message = 'File saved successfully: ' . htmlspecialchars($edit_name);
        } else {
            $action_message = 'Failed to save file';
        }
    }
} elseif (isset($_POST['upload_file']) && isset($_FILES['file_upload'])) {
    $upload_error = $_FILES['file_upload']['error'];
    
    if ($upload_error === UPLOAD_ERR_OK) {
        $file_name = basename($_FILES['file_upload']['name']);
        $file_name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file_name); // Sanitize
        $target_file = $full_path . '/' . $file_name;
        
        // Check if file already exists
        if (file_exists($target_file)) {
            $action_message = 'File already exists: ' . htmlspecialchars($file_name);
        } else {
            if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $target_file)) {
                $action_success = true;
                $action_message = 'File uploaded successfully: ' . htmlspecialchars($file_name);
            } else {
                $action_message = 'Failed to upload file';
            }
        }
    } else {
        $human_limit = $effective_upload_limit_bytes > 0 ? formatBytes($effective_upload_limit_bytes) : 'server limit';
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE => 'File too large (server limit: ' . $human_limit . ')',
            UPLOAD_ERR_FORM_SIZE => 'File too large (form/server limit: ' . $human_limit . ')',
            UPLOAD_ERR_PARTIAL => 'File upload incomplete',
            UPLOAD_ERR_NO_FILE => 'No file selected',
        ];
        $action_message = isset($upload_errors[$upload_error]) ? $upload_errors[$upload_error] : 'Upload failed';
    }
}

// Scan directory
$items = is_readable($full_path) ? scandir($full_path) : [];
$folders = [];
$files = [];

foreach ($items as $item) {
    if ($item == '.' || $item == '..' || $item == '.gitkeep') continue;
    $item_path = $full_path . '/' . $item;
    if (is_dir($item_path)) {
        $folders[] = $item;
    } else {
        $files[] = $item;
    }
}

sort($folders);
sort($files);

function formatBytes($bytes) {
    if ($bytes == 0) return '0 B';
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes, 1024));
    return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
}

function getFileIcon($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $icons = [
        'jpg' => 'fa-file-image-o', 'jpeg' => 'fa-file-image-o', 'png' => 'fa-file-image-o', 'gif' => 'fa-file-image-o',
        'pdf' => 'fa-file-pdf-o',
        'zip' => 'fa-file-archive-o', 'rar' => 'fa-file-archive-o', '7z' => 'fa-file-archive-o',
        'doc' => 'fa-file-word-o', 'docx' => 'fa-file-word-o',
        'xls' => 'fa-file-excel-o', 'xlsx' => 'fa-file-excel-o',
        'txt' => 'fa-file-text-o', 'log' => 'fa-file-text-o',
        'php' => 'fa-file-code-o', 'html' => 'fa-file-code-o', 'css' => 'fa-file-code-o', 'js' => 'fa-file-code-o',
    ];
    return isset($icons[$ext]) ? $icons[$ext] : 'fa-file-o';
}

function isEditableTextFile($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $editable_ext = [
        'txt', 'log', 'md', 'json', 'xml', 'csv', 'ini', 'conf', 'env',
        'php', 'html', 'css', 'js', 'ts', 'jsx', 'tsx', 'sql', 'py', 'java', 'c', 'cpp', 'h', 'sh', 'bat'
    ];
    return in_array($ext, $editable_ext, true);
}

$edit_mode = false;
$edit_file_name = '';
$edit_file_content = '';
$edit_readonly_reason = '';

if (isset($_GET['edit'])) {
    $edit_file_name = sanitizeItemName($_GET['edit']);
    $candidate_file = $full_path . '/' . $edit_file_name;
    $candidate_real = realpath($candidate_file);

    if ($edit_file_name !== '' && $candidate_real !== false && strpos($candidate_real, $fm_root_real) === 0 && is_file($candidate_real)) {
        $edit_mode = true;
        if (!isEditableTextFile($edit_file_name)) {
            $edit_readonly_reason = 'This file type is not supported in the text editor.';
        } elseif (filesize($candidate_real) > (2 * 1024 * 1024)) {
            $edit_readonly_reason = 'File is too large to edit in browser (limit: 2 MB).';
        } else {
            $content = file_get_contents($candidate_real);
            if ($content === false) {
                $edit_readonly_reason = 'Unable to read file content.';
            } else {
                $edit_file_content = $content;
            }
        }
    }
}
?>

<style>
.fm-container {
    --fm-primary: #1f3a68;
    --fm-primary-strong: #173158;
    --fm-accent: #5f9cf8;
    --fm-accent-soft: #e8f0ff;
    --fm-border: #dbe6f5;
    --fm-text: #10253f;
    --fm-text-muted: #5b6f8c;
    --fm-success-bg: #e9f9ef;
    --fm-success-text: #20784a;
    --fm-error-bg: #ffecec;
    --fm-error-text: #a63a3a;
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    border: 1px solid #e2ebf8;
    border-radius: 20px;
    padding: 24px;
    min-height: 600px;
    box-shadow: 0 14px 28px rgba(18, 46, 84, 0.06);
}

.fm-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--fm-border);
}

.fm-title {
    margin: 0;
    font-size: 1.45rem;
    font-weight: 700;
    color: var(--fm-text);
    display: flex;
    align-items: center;
    gap: 10px;
}

.fm-title i {
    width: 34px;
    height: 34px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--fm-text);
    font-size: 0.95rem;
}

.fm-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--fm-text-muted);
    margin-bottom: 20px;
    padding: 11px 14px;
    background: #f4f8ff;
    border: 1px solid var(--fm-border);
    border-radius: 12px;
}

.fm-breadcrumb a {
    color: var(--fm-primary);
    font-weight: 600;
    text-decoration: none;
}

.fm-breadcrumb a:hover {
    color: var(--fm-accent);
}

.fm-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    overflow: hidden;
    border: 1px solid var(--fm-border);
    border-radius: 14px;
    background: #fff;
}

.fm-table thead {
    background: #edf3fd;
}

.fm-table th,
.fm-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #edf2fa;
}

.fm-table th {
    font-weight: 700;
    color: var(--fm-primary);
    letter-spacing: 0.2px;
}

.fm-table tbody tr:hover {
    background: #f8fbff;
}

.fm-table a {
    color: var(--fm-text);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.fm-table a:hover {
    color: var(--fm-primary);
}

.fm-icon {
    width: 20px;
    text-align: center;
}

.fm-folder {
    color: #f5b700;
}

.fm-actions {
    display: flex;
    gap: 8px;
}

.fm-btn {
    padding: 6px 10px;
    background: #edf3ff;
    border: 1px solid #c7daf8;
    border-radius: 10px;
    color: var(--fm-primary);
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s ease;
}

.fm-btn:hover {
    background: var(--fm-primary);
    border-color: var(--fm-primary);
    color: white;
    transform: translateY(-1px);
}

.fm-empty {
    text-align: center;
    padding: 60px 20px;
    color: var(--fm-text-muted);
}

.fm-empty i {
    font-size: 64px;
    margin-bottom: 20px;
    display: block;
    color: #c5d4ea;
}

.fm-stats {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid var(--fm-border);
    color: var(--fm-text-muted);
    font-size: 14px;
}

.fm-upload-box {
    background: linear-gradient(180deg, #f8fbff 0%, #f2f7ff 100%);
    border: 1px solid #ccdcf7;
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: inset 0 0 0 1px rgba(95, 156, 248, 0.12);
    transition: all 0.25s;
}

.fm-upload-box:hover {
    border-color: var(--fm-accent);
    box-shadow: inset 0 0 0 1px rgba(95, 156, 248, 0.22), 0 8px 18px rgba(31, 58, 104, 0.08);
}

.fm-upload-form {
    display: flex;
    gap: 10px;
    align-items: center;
}

.fm-create-row {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.fm-create-form {
    display: flex;
    gap: 8px;
    align-items: center;
    flex: 1;
    min-width: 260px;
}

.fm-text-input {
    flex: 1;
    padding: 10px;
    background: #fff;
    border: 1px solid #c8daf5;
    border-radius: 10px;
    color: var(--fm-text);
}

.fm-text-input::placeholder {
    color: #91a4bf;
}

.fm-file-input {
    flex: 1;
    padding: 10px;
    background: #fff;
    border: 1px solid #c8daf5;
    border-radius: 10px;
    color: var(--fm-text);
}

.fm-file-input::-webkit-file-upload-button {
    background: var(--fm-primary);
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    margin-right: 10px;
}

.fm-file-input::-webkit-file-upload-button:hover {
    background: var(--fm-primary-strong);
}

.fm-upload-btn {
    padding: 10px 24px;
    background: linear-gradient(135deg, var(--fm-primary) 0%, #2d538f 100%);
    border: none;
    border-radius: 10px;
    color: white;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.fm-upload-btn:hover {
    background: linear-gradient(135deg, var(--fm-primary-strong) 0%, var(--fm-primary) 100%);
    box-shadow: 0 8px 16px rgba(31, 58, 104, 0.24);
    transform: translateY(-1px);
}

@media (max-width: 900px) {
    .fm-upload-form,
    .fm-create-form {
        flex-direction: column;
        align-items: stretch;
    }
}

.fm-message {
    padding: 12px 16px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.fm-message.success {
    background: var(--fm-success-bg);
    border: 1px solid #b6e8ca;
    color: var(--fm-success-text);
}

.fm-message.error {
    background: var(--fm-error-bg);
    border: 1px solid #f8baba;
    color: var(--fm-error-text);
}

.fm-editor-box {
    background: #f8fbff;
    border: 1px solid #d0def6;
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 20px;
}

.fm-editor-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    color: var(--fm-text);
}

.fm-editor-textarea {
    width: 100%;
    min-height: 360px;
    background: #fff;
    border: 1px solid #c8daf5;
    border-radius: 10px;
    color: #1a2f4d;
    padding: 12px;
    resize: vertical;
    font-family: Consolas, Monaco, 'Courier New', monospace;
    font-size: 13px;
    line-height: 1.4;
}

.fm-editor-actions {
    margin-top: 12px;
    display: flex;
    gap: 10px;
}

.fm-note {
    color: var(--fm-text-muted);
    font-size: 13px;
}

.fm-helper {
    color: #7389a9;
    margin-top: 8px;
    display: block;
}

.fm-helper i {
    color: var(--fm-accent);
}

@media (max-width: 900px) {
    .fm-container {
        padding: 18px;
        border-radius: 16px;
    }

    .fm-table {
        font-size: 13px;
    }

    .fm-btn {
        padding: 5px 8px;
    }
}
</style>

<div class="fm-container">
    <div class="fm-header">
        <div>
            <h3 class="fm-title"><i class="fas fa-folder-open"></i> File Management Interface</h3>
        </div>
    </div>

    <?php if ($action_message): ?>
    <div class="fm-message <?php echo $action_success ? 'success' : 'error'; ?>">
        <i class="fas fa-<?php echo $action_success ? 'check-circle' : 'exclamation-circle'; ?>"></i>
        <?php echo $action_message; ?>
    </div>
    <?php endif; ?>

    <?php if ($edit_mode): ?>
    <div class="fm-editor-box">
        <div class="fm-editor-header">
            <strong><i class="fas fa-edit"></i> Editing: <?php echo htmlspecialchars($edit_file_name); ?></strong>
            <a href="?page=filemanager&fm_path=<?php echo urlencode($fm_path); ?>" class="fm-btn">
                <i class="fas fa-times"></i> Close Editor
            </a>
        </div>

        <?php if ($edit_readonly_reason): ?>
        <div class="fm-message error" style="margin-bottom: 0;">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($edit_readonly_reason); ?>
        </div>
        <?php else: ?>
        <form method="post" action="?page=filemanager&fm_path=<?php echo urlencode($fm_path); ?>&edit=<?php echo urlencode($edit_file_name); ?>">
            <textarea name="file_content" class="fm-editor-textarea"><?php echo htmlspecialchars($edit_file_content); ?></textarea>
            <div class="fm-editor-actions">
                <button type="submit" name="save_file" class="fm-upload-btn">
                    <i class="fas fa-save"></i>
                    Save File
                </button>
            </div>
            <small class="fm-note">Only text/code files up to 2 MB are editable in browser.</small>
        </form>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="fm-upload-box">
        <div class="fm-create-row">
            <form method="post" class="fm-create-form">
                <input type="text" name="folder_name" class="fm-text-input" placeholder="New folder name" required>
                <button type="submit" name="create_folder" class="fm-upload-btn">
                    <i class="fas fa-folder-plus"></i>
                    Create Folder
                </button>
            </form>
            <form method="post" class="fm-create-form">
                <input type="text" name="new_file_name" class="fm-text-input" placeholder="New file name (e.g. notes.txt)" required>
                <button type="submit" name="create_file" class="fm-upload-btn">
                    <i class="fas fa-file"></i>
                    Create File
                </button>
            </form>
        </div>

        <form method="post" enctype="multipart/form-data" class="fm-upload-form">
            <input type="file" name="file_upload" class="fm-file-input" required>
            <button type="submit" name="upload_file" class="fm-upload-btn">
                <i class="fas fa-cloud-upload"></i>
                Upload File
            </button>
        </form>
            <small class="fm-helper">
                <i class="fas fa-info-circle"></i> Maximum file size: <?php echo $effective_upload_limit_bytes > 0 ? formatBytes($effective_upload_limit_bytes) : 'Unknown'; ?>
            </small>
    </div>

    <div class="fm-breadcrumb">
        <i class="fas fa-home"></i>
        <a href="?page=filemanager">Home</a>
        <?php
        if ($fm_path) {
            $parts = explode('/', $fm_path);
            $build_path = '';
            foreach ($parts as $part) {
                $build_path .= ($build_path ? '/' : '') . $part;
                echo ' / <a href="?page=filemanager&fm_path=' . urlencode($build_path) . '">' . htmlspecialchars($part) . '</a>';
            }
        }
        ?>
    </div>

    <table class="fm-table">
        <thead>
            <tr>
                <th style="width: 50%;">Name</th>
                <th style="width: 15%;">Size</th>
                <th style="width: 20%;">Modified</th>
                <th style="width: 15%;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($fm_path): ?>
            <tr>
                <td colspan="4">
                    <?php
                    $parent_parts = explode('/', $fm_path);
                    array_pop($parent_parts);
                    $parent_path = implode('/', $parent_parts);
                    ?>
                    <a href="?page=filemanager&fm_path=<?php echo urlencode($parent_path); ?>">
                        <i class="fas fa-level-up"></i> ..
                    </a>
                </td>
            </tr>
            <?php endif; ?>

            <?php foreach ($folders as $folder): ?>
            <tr>
                <td>
                    <a href="?page=filemanager&fm_path=<?php echo urlencode($fm_path . ($fm_path ? '/' : '') . $folder); ?>">
                        <i class="fas fa-folder fm-folder"></i>
                        <?php echo htmlspecialchars($folder); ?>
                    </a>
                </td>
                <td>-</td>
                <td><?php echo date('Y-m-d H:i', filemtime($full_path . '/' . $folder)); ?></td>
                <td>
                    <div class="fm-actions">
                        <a href="?page=filemanager&fm_path=<?php echo urlencode($fm_path . ($fm_path ? '/' : '') . $folder); ?>" class="fm-btn" title="Open">
                            <i class="fas fa-folder-open"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php foreach ($files as $file): ?>
            <?php $file_path = $full_path . '/' . $file; ?>
            <tr>
                <td>
                    <i class="fas <?php echo getFileIcon($file); ?>"></i>
                    <?php echo htmlspecialchars($file); ?>
                </td>
                <td><?php echo formatBytes(filesize($file_path)); ?></td>
                <td><?php echo date('Y-m-d H:i', filemtime($file_path)); ?></td>
                <td>
                    <div class="fm-actions">
                        <a href="<?php echo BASE_URL; ?>uploads/<?php echo $fm_path . ($fm_path ? '/' : '') . $file; ?>" target="_blank" class="fm-btn" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="<?php echo BASE_URL; ?>uploads/<?php echo $fm_path . ($fm_path ? '/' : '') . $file; ?>" download class="fm-btn" title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                        <?php if (isEditableTextFile($file)): ?>
                        <a href="?page=filemanager&fm_path=<?php echo urlencode($fm_path); ?>&edit=<?php echo urlencode($file); ?>" class="fm-btn" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php if (empty($folders) && empty($files)): ?>
            <tr>
                <td colspan="4">
                    <div class="fm-empty">
                        <i class="fas fa-folder-open"></i>
                        <p>This folder is empty</p>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="fm-stats">
        <i class="fas fa-info-circle"></i> 
        Total: <?php echo count($folders); ?> folders, <?php echo count($files); ?> files
    </div>
</div>
