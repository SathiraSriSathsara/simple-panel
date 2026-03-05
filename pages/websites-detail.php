<?php
/**
 * Website Detail View
 * Shows website information, files, config, and database tabs
 */

// Check if this is being called with the required data
if (!isset($current_website)) {
    return;
}

$website = $current_website;

?>

<style>
    .detail-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        padding: 20px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dfe9f7;
        border-radius: 14px;
    }

    .detail-header-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: #ffffff;
    }

    .detail-header-info h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #142c4e;
        margin: 0 0 8px 0;
    }

    .detail-header-info p {
        color: #4f6888;
        margin: 0;
        font-size: 0.9rem;
    }

    .detail-tabs {
        display: flex;
        gap: 0;
        border-bottom: 2px solid #dfe9f7;
        margin-bottom: 20px;
        flex-wrap: wrap;
        background: #ffffff;
        border-radius: 12px 12px 0 0;
        border: 1px solid #dfe9f7;
        border-bottom: 2px solid #dfe9f7;
    }

    .detail-tab {
        padding: 14px 20px;
        background: transparent;
        border: none;
        color: #4f6888;
        font-weight: 600;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
    }

    .detail-tab:hover {
        color: #1f3a68;
    }

    .detail-tab.active {
        color: #1f3a68;
        border-bottom-color: #2d538f;
    }

    .detail-content {
        display: none;
    }

    .detail-content.active {
        display: block;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .info-card {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dfe9f7;
        border-radius: 12px;
        padding: 16px;
    }

    .info-card-title {
        font-size: 0.85rem;
        color: #4f6888;
        font-weight: 600;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-card-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: #142c4e;
        word-break: break-word;
    }

    .chart-container {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dfe9f7;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .chart-title {
        font-size: 1rem;
        font-weight: 600;
        color: #142c4e;
        margin-bottom: 16px;
    }

    .chart-placeholder {
        background: linear-gradient(135deg, #edf3fd 0%, #f0f6ff 100%);
        border: 2px dashed #c8daf5;
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        color: #637b9b;
        font-size: 0.9rem;
    }

    /* File Manager Styles */
    .file-browser {
        background: #ffffff;
        border: 1px solid #dfe9f7;
        border-radius: 12px;
        overflow: hidden;
    }

    .file-toolbar {
        display: flex;
        gap: 8px;
        padding: 12px;
        background: linear-gradient(135deg, #edf3fd 0%, #f0f6ff 100%);
        border-bottom: 1px solid #dfe9f7;
        flex-wrap: wrap;
    }

    .file-btn {
        padding: 8px 12px;
        background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .file-btn:hover {
        box-shadow: 0 2px 8px rgba(31, 58, 104, 0.2);
    }

    .file-btn.secondary {
        background: #edf3ff;
        color: #1f3a68;
        border: 1px solid #c7daf8;
    }

    .file-btn.secondary:hover {
        background: #dae6ff;
        border-color: #5f9cf8;
    }

    .file-breadcrumb {
        padding: 12px;
        background: #f8fbff;
        border-bottom: 1px solid #eef3fb;
        font-size: 0.85rem;
        color: #4f6888;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .file-breadcrumb a {
        color: #2d538f;
        cursor: pointer;
        text-decoration: none;
    }

    .file-breadcrumb a:hover {
        text-decoration: underline;
    }

    .file-list {
        max-height: 500px;
        overflow-y: auto;
    }

    .file-item {
        display: flex;
        align-items: center;
        padding: 12px;
        border-bottom: 1px solid #eef3fb;
        transition: all 0.2s ease;
    }

    .file-item:hover {
        background: #f8fbff;
    }

    .file-item-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #e8f0ff 0%, #f0f6ff 100%);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2d538f;
        font-size: 1rem;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .file-item-name {
        flex: 1;
        color: #142c4e;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .file-item-size {
        color: #4f6888;
        font-size: 0.85rem;
        margin: 0 12px;
        min-width: 60px;
        text-align: right;
    }

    .file-item-date {
        color: #637b9b;
        font-size: 0.8rem;
        min-width: 120px;
        text-align: right;
    }

    .file-item-actions {
        display: flex;
        gap: 6px;
        margin-left: 12px;
    }

    .file-action-btn {
        width: 28px;
        height: 28px;
        background: transparent;
        border: 1px solid #c8daf5;
        border-radius: 6px;
        color: #1f3a68;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }

    .file-action-btn:hover {
        background: #e8f0ff;
        border-color: #5f9cf8;
    }

    .file-empty {
        text-align: center;
        padding: 40px 20px;
        color: #637b9b;
    }

    /* Code Editor Styles */
    .code-editor {
        background: #1e1e1e;
        color: #d4d4d4;
        border: 1px solid #3e3e42;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .code-editor-header {
        background: #252526;
        border-bottom: 1px solid #3e3e42;
        padding: 12px;
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        color: #859fb4;
    }

    .code-editor-content {
        padding: 16px;
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.9rem;
        line-height: 1.6;
        overflow-x: auto;
        max-height: 400px;
        overflow-y: auto;
    }

    .code-editor-content code {
        color: #d4d4d4;
    }

    /* Database Status */
    .status-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .status-indicator.connected {
        background: #20784a;
    }

    .status-indicator.disconnected {
        background: #d94b4b;
    }

    .db-info {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dfe9f7;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .db-info-item {
        display: grid;
        grid-template-columns: 150px 1fr;
        gap: 16px;
        padding: 12px 0;
        border-bottom: 1px solid #eef3fb;
    }

    .db-info-item:last-child {
        border-bottom: none;
    }

    .db-info-label {
        font-weight: 600;
        color: #4f6888;
        font-size: 0.9rem;
    }

    .db-info-value {
        color: #142c4e;
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.95rem;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #1f3a68;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 16px;
        cursor: pointer;
        border: 1px solid #c8daf5;
        background: #edf3ff;
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .back-button:hover {
        background: #dae6ff;
        border-color: #5f9cf8;
    }

    @media (max-width: 768px) {
        .detail-header {
            flex-direction: column;
            text-align: center;
        }

        .detail-header-info h2 {
            font-size: 1.2rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .file-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .file-item-size,
        .file-item-date {
            width: 100%;
            text-align: left;
            margin: 4px 0;
        }

        .db-info-item {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Back Button -->
<button class="back-button" onclick="history.back()">
    <i class="fas fa-arrow-left"></i> Back to Websites
</button>

<!-- Header -->
<div class="detail-header">
    <div class="detail-header-icon">
        <i class="<?php echo ($website['type'] === 'php' ? 'fab fa-php' : ($website['type'] === 'wordpress' ? 'fab fa-wordpress' : 'fas fa-code')); ?>"></i>
    </div>
    <div class="detail-header-info">
        <h2><?php echo htmlspecialchars($website['name']); ?></h2>
        <p>
            <span class="status-indicator <?php echo ($website['status'] === 'active' ? 'connected' : 'disconnected'); ?>"></span>
            <?php echo ucfirst($website['status']); ?> • <?php echo strtoupper($website['type']); ?> 
            <?php if ($website['ssl']): ?>
                • <i class="fas fa-lock" style="color: #20784a;"></i> SSL Enabled
            <?php endif; ?>
        </p>
    </div>
</div>

<!-- Tabs Navigation -->
<div class="detail-tabs">
    <button class="detail-tab active" data-tab="info" onclick="switchTab('info', event)">
        <i class="fas fa-info-circle"></i> Info
    </button>
    <button class="detail-tab" data-tab="files" onclick="switchTab('files', event)">
        <i class="fas fa-folder"></i> Files
    </button>
    <button class="detail-tab" data-tab="config" onclick="switchTab('config', event)">
        <i class="fas fa-cog"></i> Config
    </button>
    <button class="detail-tab" data-tab="database" onclick="switchTab('database', event)">
        <i class="fas fa-database"></i> Database
    </button>
</div>

<!-- TAB 1: Info -->
<div id="tab-info" class="detail-content active">
    <div class="info-grid">
        <div class="info-card">
            <div class="info-card-title"><i class="fas fa-link"></i> Website URL</div>
            <div class="info-card-value"><a href="<?php echo htmlspecialchars($website['url']); ?>" target="_blank" style="color: #2d538f; text-decoration: none;"><?php echo htmlspecialchars($website['url']); ?></a></div>
        </div>

        <div class="info-card">
            <div class="info-card-title"><i class="fas fa-code"></i> Type</div>
            <div class="info-card-value"><?php echo strtoupper($website['type']); ?></div>
        </div>

        <div class="info-card">
            <div class="info-card-title"><i class="fas fa-calendar"></i> Created</div>
            <div class="info-card-value"><?php echo htmlspecialchars($website['created']); ?></div>
        </div>

        <div class="info-card">
            <div class="info-card-title"><i class="fas fa-shield"></i> SSL</div>
            <div class="info-card-value"><?php echo ($website['ssl'] ? 'Enabled' : 'Disabled'); ?></div>
        </div>

        <div class="info-card">
            <div class="info-card-title"><i class="fas fa-hdd"></i> Storage Used</div>
            <div class="info-card-value"><?php echo htmlspecialchars($website['storage_used']); ?></div>
        </div>

        <div class="info-card">
            <div class="info-card-title"><i class="fas fa-chart-pie"></i> Storage Limit</div>
            <div class="info-card-value"><?php echo htmlspecialchars($website['storage_limit']); ?></div>
        </div>
    </div>

    <!-- Storage Usage Chart -->
    <div class="chart-container">
        <div class="chart-title"><i class="fas fa-chart-bar"></i> Storage Usage</div>
        <div class="chart-placeholder">
            <p>Storage Usage Chart (Chart.js implementation)</p>
            <p style="margin: 0;">Used: <?php echo htmlspecialchars($website['storage_used']); ?> / Limit: <?php echo htmlspecialchars($website['storage_limit']); ?></p>
        </div>
    </div>

    <!-- Traffic Chart -->
    <div class="chart-container">
        <div class="chart-title"><i class="fas fa-chart-line"></i> Traffic (Last 24 Hours)</div>
        <div class="chart-placeholder">
            <p>Traffic Chart (Chart.js implementation)</p>
            <p style="margin: 0;">Total: <?php echo htmlspecialchars($website['traffic_24h']); ?></p>
        </div>
    </div>
</div>

<!-- TAB 2: Files -->
<div id="tab-files" class="detail-content">
    <div class="file-browser">
        <div class="file-toolbar">
            <button class="file-btn"><i class="fas fa-upload"></i> Upload Files</button>
            <button class="file-btn secondary"><i class="fas fa-folder-plus"></i> New Folder</button>
            <button class="file-btn secondary"><i class="fas fa-file-plus"></i> New File</button>
            <button class="file-btn secondary"><i class="fas fa-sync"></i> Refresh</button>
        </div>

        <div class="file-breadcrumb">
            <i class="fas fa-home"></i>
            <a onclick="alert('Navigate to home - API implementation needed')">/</a>
            <span>/</span>
            <span>public_html</span>
        </div>

        <div class="file-list">
            <!-- Sample Files -->
            <div class="file-item">
                <div class="file-item-icon"><i class="fas fa-folder"></i></div>
                <div class="file-item-name">uploads</div>
                <div class="file-item-size">—</div>
                <div class="file-item-date">Mar 5, 2024</div>
                <div class="file-item-actions">
                    <button class="file-action-btn"><i class="fas fa-edit"></i></button>
                    <button class="file-action-btn"><i class="fas fa-trash"></i></button>
                </div>
            </div>

            <div class="file-item">
                <div class="file-item-icon"><i class="fas fa-file"></i></div>
                <div class="file-item-name">index.php</div>
                <div class="file-item-size">12 KB</div>
                <div class="file-item-date">Mar 4, 2024</div>
                <div class="file-item-actions">
                    <button class="file-action-btn"><i class="fas fa-edit"></i></button>
                    <button class="file-action-btn"><i class="fas fa-download"></i></button>
                    <button class="file-action-btn"><i class="fas fa-trash"></i></button>
                </div>
            </div>

            <div class="file-item">
                <div class="file-item-icon"><i class="fas fa-file"></i></div>
                <div class="file-item-name">config.php</div>
                <div class="file-item-size">2.5 KB</div>
                <div class="file-item-date">Mar 1, 2024</div>
                <div class="file-item-actions">
                    <button class="file-action-btn"><i class="fas fa-edit"></i></button>
                    <button class="file-action-btn"><i class="fas fa-download"></i></button>
                    <button class="file-action-btn"><i class="fas fa-trash"></i></button>
                </div>
            </div>

            <div class="file-item">
                <div class="file-item-icon"><i class="fas fa-file"></i></div>
                <div class="file-item-name">style.css</div>
                <div class="file-item-size">34 KB</div>
                <div class="file-item-date">Feb 28, 2024</div>
                <div class="file-item-actions">
                    <button class="file-action-btn"><i class="fas fa-edit"></i></button>
                    <button class="file-action-btn"><i class="fas fa-download"></i></button>
                    <button class="file-action-btn"><i class="fas fa-trash"></i></button>
                </div>
            </div>

            <div class="file-item">
                <div class="file-item-icon"><i class="fas fa-file"></i></div>
                <div class="file-item-name">README.md</div>
                <div class="file-item-size">1.8 KB</div>
                <div class="file-item-date">Feb 20, 2024</div>
                <div class="file-item-actions">
                    <button class="file-action-btn"><i class="fas fa-edit"></i></button>
                    <button class="file-action-btn"><i class="fas fa-download"></i></button>
                    <button class="file-action-btn"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TAB 3: Config -->
<div id="tab-config" class="detail-content">
    <h3 style="color: #142c4e; margin-bottom: 16px;">Nginx Server Configuration</h3>
    
    <div class="code-editor">
        <div class="code-editor-header">
            <span>nginx.conf</span>
            <button style="background: none; border: none; color: #859fb4; cursor: pointer; padding: 4px 8px;">
                <i class="fas fa-copy"></i> Copy
            </button>
        </div>
        <div class="code-editor-content"><code>server {
    listen 80;
    listen [::]:80;
    server_name <?php echo htmlspecialchars($website['name']); ?>;

    root /var/www/<?php echo htmlspecialchars($website['name']); ?>/public_html;
    index index.html index.php;

    # SSL Configuration
    <?php if ($website['ssl']): ?>
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    ssl_certificate /etc/letsencrypt/live/<?php echo htmlspecialchars($website['name']); ?>/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/<?php echo htmlspecialchars($website['name']); ?>/privkey.pem;
    <?php endif; ?>

    # PHP Handler
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Static Files Caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
}
</code></div>
    </div>

    <div style="display: flex; gap: 12px;">
        <button style="padding: 10px 16px; background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%); color: #ffffff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
            <i class="fas fa-edit"></i> Edit Config
        </button>
        <button style="padding: 10px 16px; background: #edf3ff; color: #1f3a68; border: 1px solid #c7daf8; border-radius: 8px; font-weight: 600; cursor: pointer;">
            <i class="fas fa-redo"></i> Reload Nginx
        </button>
    </div>
</div>

<!-- TAB 4: Database -->
<div id="tab-database" class="detail-content">
    <?php if ($website['database']): ?>
        <div class="db-info">
            <div class="db-info-item">
                <div class="db-info-label"><i class="fas fa-link"></i> Status</div>
                <div class="db-info-value">
                    <span class="status-indicator connected"></span> Connected
                </div>
            </div>
            <div class="db-info-item">
                <div class="db-info-label"><i class="fas fa-database"></i> Database</div>
                <div class="db-info-value"><?php echo htmlspecialchars($website['database']); ?></div>
            </div>
            <div class="db-info-item">
                <div class="db-info-label"><i class="fas fa-server"></i> Host</div>
                <div class="db-info-value">localhost</div>
            </div>
            <div class="db-info-item">
                <div class="db-info-label"><i class="fas fa-user"></i> Username</div>
                <div class="db-info-value">wp_user</div>
            </div>
            <div class="db-info-item">
                <div class="db-info-label"><i class="fas fa-key"></i> Port</div>
                <div class="db-info-value">3306 (MySQL)</div>
            </div>
            <div class="db-info-item">
                <div class="db-info-label"><i class="fas fa-table"></i> Tables</div>
                <div class="db-info-value">42 tables</div>
            </div>
            <div class="db-info-item">
                <div class="db-info-label"><i class="fas fa-database"></i> Size</div>
                <div class="db-info-value">125 MB</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; margin-top: 20px;">
            <button style="padding: 10px 12px; background: #e8f0ff; color: #1f3a68; border: 1px solid #c7daf8; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-backup"></i> Backup
            </button>
            <button style="padding: 10px 12px; background: #e8f0ff; color: #1f3a68; border: 1px solid #c7daf8; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-upload"></i> Import
            </button>
            <button style="padding: 10px 12px; background: #e8f0ff; color: #1f3a68; border: 1px solid #c7daf8; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-wrench"></i> Manage
            </button>
            <button style="padding: 10px 12px; background: #ffecec; color: #a63a3a; border: 1px solid #f8baba; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-times"></i> Disconnect
            </button>
        </div>
    <?php else: ?>
        <div style="background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%); border: 1px solid #dfe9f7; border-radius: 12px; padding: 40px 20px; text-align: center; color: #4f6888;">
            <i class="fas fa-database" style="font-size: 3rem; color: #c8daf5; margin-bottom: 12px; display: block;"></i>
            <p style="margin: 0; font-size: 0.95rem;">No database connected</p>
            <p style="color: #637b9b; font-size: 0.85rem; margin-top: 8px;">This <?php echo strtoupper($website['type']); ?> website doesn't require a database connection.</p>
            <button style="margin-top: 16px; padding: 10px 16px; background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%); color: #ffffff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-plus"></i> Add Database Connection
            </button>
        </div>
    <?php endif; ?>
</div>

<script>
    function switchTab(tabName, event) {
        event.preventDefault();
        
        // Hide all content
        document.querySelectorAll('.detail-content').forEach(content => {
            content.classList.remove('active');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.detail-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Show selected content
        document.getElementById('tab-' + tabName).classList.add('active');
        
        // Add active class to clicked tab
        event.target.closest('.detail-tab').classList.add('active');
    }
</script>
