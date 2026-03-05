<?php
/**
 * Websites Management
 * Create, edit, and manage websites (HTML, PHP, WordPress)
 */

require_once COMPONENTS_PATH . '/stat-card.php';

// Get website ID from URL if viewing details
$view_website_id = isset($_GET['website_id']) ? $_GET['website_id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Sample websites data (will be replaced with API calls)
$websites = [
    [
        'id' => 1,
        'name' => 'example.com',
        'type' => 'php',
        'url' => 'https://example.com',
        'status' => 'active',
        'storage_limit' => '5GB',
        'storage_used' => '1.2GB',
        'traffic_24h' => '45.2GB',
        'created' => '2024-01-15',
        'ssl' => true,
        'database' => 'mysql'
    ],
    [
        'id' => 2,
        'name' => 'myblog.net',
        'type' => 'wordpress',
        'url' => 'https://myblog.net',
        'status' => 'active',
        'storage_limit' => '10GB',
        'storage_used' => '3.5GB',
        'traffic_24h' => '23.1GB',
        'created' => '2023-06-20',
        'ssl' => true,
        'database' => 'wordpress_db'
    ],
    [
        'id' => 3,
        'name' => 'portfolio.local',
        'type' => 'html',
        'url' => 'https://portfolio.local',
        'status' => 'active',
        'storage_limit' => '2GB',
        'storage_used' => '0.3GB',
        'traffic_24h' => '5.2GB',
        'created' => '2024-03-01',
        'ssl' => false,
        'database' => null
    ]
];

// Check if viewing website details
if ($action === 'view' && isset($_GET['id'])) {
    $website_id = intval($_GET['id']);
    $current_website = null;
    
    foreach ($websites as $w) {
        if ($w['id'] === $website_id) {
            $current_website = $w;
            break;
        }
    }

    if ($current_website) {
        include 'websites-detail.php';
        return;
    }
}

?>

<style>
    .website-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .website-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #142c4e;
        margin: 0;
    }

    .website-controls {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }

    .view-toggle {
        display: flex;
        gap: 8px;
        border: 1px solid #dfe9f7;
        border-radius: 10px;
        padding: 4px;
        background: #ffffff;
    }

    .view-toggle button {
        border: 1px solid transparent;
        background: transparent;
        padding: 8px 12px;
        cursor: pointer;
        border-radius: 8px;
        color: #4f6888;
        transition: all 0.2s ease;
    }

    .view-toggle button.active {
        background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%);
        color: #ffffff;
        border-color: #2d538f;
    }

    .btn-create {
        background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%);
        color: #ffffff;
        border: none;
        padding: 10px 16px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-create:hover {
        box-shadow: 0 4px 12px rgba(31, 58, 104, 0.3);
        transform: translateY(-1px);
    }

    .website-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 16px;
        margin-top: 24px;
    }

    .website-card {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dfe9f7;
        border-radius: 14px;
        padding: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .website-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #1f3a68, #2d538f);
    }

    .website-card:hover {
        border-color: #5f9cf8;
        box-shadow: 0 8px 20px rgba(95, 156, 248, 0.15);
        transform: translateY(-2px);
    }

    .website-type-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .website-type-badge.php {
        background: #e3f0ff;
        color: #1f3a68;
    }

    .website-type-badge.html {
        background: #fff3e0;
        color: #8b6914;
    }

    .website-type-badge.wordpress {
        background: #fce4ec;
        color: #8b0a50;
    }

    .website-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #142c4e;
        margin: 0 0 8px 0;
        word-break: break-word;
    }

    .website-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: #4f6888;
        margin-bottom: 12px;
    }

    .status-badge {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #20784a;
        margin-right: 4px;
    }

    .status-badge.inactive {
        background: #d94b4b;
    }

    .website-stats {
        margin: 12px 0;
        padding: 12px 0;
        border-top: 1px solid #eef3fb;
        border-bottom: 1px solid #eef3fb;
    }

    .stat-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        color: #4f6888;
        margin: 6px 0;
    }

    .stat-label {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .stat-value {
        font-weight: 600;
        color: #1f3a68;
    }

    .progress-bar {
        width: 100%;
        height: 6px;
        background: #eef3fb;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 4px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #1f3a68, #2d538f);
        border-radius: 3px;
    }

    .website-actions {
        display: flex;
        gap: 8px;
        margin-top: 16px;
    }

    .action-btn {
        flex: 1;
        padding: 10px 12px;
        border: 1px solid #c8daf5;
        background: #edf3ff;
        color: #1f3a68;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .action-btn:hover {
        background: #dae6ff;
        border-color: #5f9cf8;
    }

    .action-btn.danger {
        background: #ffecec;
        color: #a63a3a;
        border-color: #f8baba;
    }

    .action-btn.danger:hover {
        background: #ffd9d9;
        border-color: #ef9999;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: #ffffff;
        border-radius: 18px;
        padding: 0;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(18, 46, 84, 0.2);
    }

    .modal-header {
        background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%);
        color: #ffffff;
        padding: 20px;
        border-radius: 18px 18px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        color: #ffffff;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 6px;
    }

    .modal-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        color: #142c4e;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        border: 1px solid #c8daf5;
        border-radius: 10px;
        padding: 10px 12px;
        background: #ffffff;
        color: #163256;
        font-size: 0.9rem;
        font-family: inherit;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #5f9cf8;
        outline: none;
        box-shadow: 0 0 0 3px rgba(95, 156, 248, 0.1);
    }

    .website-type-selector {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 16px;
    }

    .type-option {
        border: 2px solid #dfe9f7;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #ffffff;
    }

    .type-option:hover {
        border-color: #5f9cf8;
        background: #f8fbff;
    }

    .type-option.selected {
        border-color: #2d538f;
        background: #e8f0ff;
    }

    .type-option-icon {
        font-size: 2rem;
        margin-bottom: 8px;
    }

    .type-option-label {
        font-weight: 600;
        color: #142c4e;
        font-size: 0.9rem;
    }

    .section-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1f3a68;
        margin: 16px 0 12px 0;
        border-bottom: 2px solid #edf3fd;
        padding-bottom: 8px;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        color: #4f6888;
    }

    .checkbox-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .checkbox-group label {
        margin: 0;
        cursor: pointer;
    }

    .modal-footer {
        padding: 16px 20px;
        border-top: 1px solid #eef3fb;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .btn-primary {
        background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%);
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-primary:hover {
        box-shadow: 0 4px 12px rgba(31, 58, 104, 0.3);
    }

    .btn-secondary {
        background: #edf3ff;
        color: #1f3a68;
        border: 1px solid #c7daf8;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-secondary:hover {
        background: #dae6ff;
        border-color: #5f9cf8;
    }

    .wordpress-options {
        display: none;
    }

    .wordpress-options.show {
        display: block;
    }

    .db-selector {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .db-option {
        border: 2px solid #dfe9f7;
        border-radius: 10px;
        padding: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .db-option:hover {
        border-color: #5f9cf8;
    }

    .db-option.selected {
        border-color: #2d538f;
        background: #e8f0ff;
        color: #1f3a68;
    }

    .storage-input-group {
        display: flex;
        gap: 8px;
    }

    .storage-input-group input {
        flex: 1;
    }

    .storage-input-group select {
        width: 120px;
    }

    @media (max-width: 768px) {
        .website-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .website-grid {
            grid-template-columns: 1fr;
        }

        .website-type-selector {
            grid-template-columns: 1fr;
        }

        .modal-content {
            width: 95%;
            max-height: 95vh;
        }

        .db-selector {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="stats-grid">
    <?php
    render_stat_card('fas fa-globe', t('Total sites'), (string)count($websites));
    render_stat_card('fas fa-chart-line', t('Traffic (last 24h)'), '148k');
    render_stat_card('fas fa-wordpress', t('WordPress installs'), '1');
    render_stat_card('fas fa-shield', t('SSL enabled'), '2');
    ?>
</div>

<div class="website-header">
    <h2 class="website-title"><i class="fas fa-globe"></i> Websites</h2>
    <div class="website-controls">
        <div class="view-toggle">
            <button class="active" onclick="setView('grid')"><i class="fas fa-th"></i></button>
            <button onclick="setView('table')"><i class="fas fa-list"></i></button>
        </div>
        <button class="btn-create" onclick="openCreateModal()">
            <i class="fas fa-plus"></i> Create Website
        </button>
    </div>
</div>

<!-- Websites Grid View -->
<div id="gridView" class="website-grid">
    <?php foreach ($websites as $website): ?>
        <div class="website-card">
            <span class="website-type-badge <?php echo $website['type']; ?>"><?php echo strtoupper($website['type']); ?></span>
            
            <h3 class="website-name"><?php echo htmlspecialchars($website['name']); ?></h3>
            
            <div class="website-meta">
                <span class="status-badge <?php echo $website['status'] !== 'active' ? 'inactive' : ''; ?>"></span>
                <span><?php echo ucfirst($website['status']); ?></span>
                <?php if ($website['ssl']): ?>
                    <span style="margin-left: auto;"><i class="fas fa-lock" style="color: #20784a;"></i> SSL</span>
                <?php endif; ?>
            </div>

            <div class="website-stats">
                <div class="stat-row">
                    <span class="stat-label"><i class="fas fa-link"></i> URL</span>
                    <span class="stat-value" style="font-size: 0.8rem; overflow: hidden; text-overflow: ellipsis;"><?php echo htmlspecialchars($website['url']); ?></span>
                </div>
                <div class="stat-row">
                    <span class="stat-label"><i class="fas fa-calendar"></i> Created</span>
                    <span class="stat-value"><?php echo htmlspecialchars($website['created']); ?></span>
                </div>
                <div class="stat-row">
                    <span class="stat-label"><i class="fas fa-database"></i> Storage</span>
                    <span class="stat-value"><?php echo htmlspecialchars($website['storage_used'] . ' / ' . $website['storage_limit']); ?></span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php 
                        $used = intval($website['storage_used']);
                        $limit = intval($website['storage_limit']);
                        echo ($used / $limit * 100);
                    ?>%"></div>
                </div>
                <div class="stat-row">
                    <span class="stat-label"><i class="fas fa-chart-line"></i> Traffic (24h)</span>
                    <span class="stat-value"><?php echo htmlspecialchars($website['traffic_24h']); ?></span>
                </div>
            </div>

            <div class="website-actions">
                <button class="action-btn" onclick="viewWebsite(<?php echo $website['id']; ?>)">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="action-btn danger">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Websites Table View -->
<div id="tableView" style="display: none; overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; background: #ffffff; border-radius: 14px; overflow: hidden; border: 1px solid #dfe9f7;">
        <thead>
            <tr style="background: linear-gradient(135deg, #edf3fd 0%, #f0f6ff 100%); border-bottom: 2px solid #dfe9f7;">
                <th style="padding: 14px; text-align: left; color: #1f3a68; font-weight: 700;">Website</th>
                <th style="padding: 14px; text-align: left; color: #1f3a68; font-weight: 700;">Type</th>
                <th style="padding: 14px; text-align: left; color: #1f3a68; font-weight: 700;">Status</th>
                <th style="padding: 14px; text-align: left; color: #1f3a68; font-weight: 700;">Storage</th>
                <th style="padding: 14px; text-align: left; color: #1f3a68; font-weight: 700;">Traffic</th>
                <th style="padding: 14px; text-align: center; color: #1f3a68; font-weight: 700;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($websites as $website): ?>
                <tr style="border-bottom: 1px solid #eef3fb;">
                    <td style="padding: 14px; color: #142c4e; font-weight: 600;">
                        <?php echo htmlspecialchars($website['name']); ?>
                    </td>
                    <td style="padding: 14px;">
                        <span class="website-type-badge <?php echo $website['type']; ?>" style="margin: 0;">
                            <?php echo strtoupper($website['type']); ?>
                        </span>
                    </td>
                    <td style="padding: 14px; color: #4f6888;">
                        <span class="status-badge <?php echo $website['status'] !== 'active' ? 'inactive' : ''; ?>" style="display: inline-block;"></span>
                        <?php echo ucfirst($website['status']); ?>
                    </td>
                    <td style="padding: 14px; color: #4f6888;">
                        <?php echo htmlspecialchars($website['storage_used'] . ' / ' . $website['storage_limit']); ?>
                    </td>
                    <td style="padding: 14px; color: #4f6888;">
                        <?php echo htmlspecialchars($website['traffic_24h']); ?>
                    </td>
                    <td style="padding: 14px; text-align: center;">
                        <button class="action-btn" style="width: auto;" onclick="viewWebsite(<?php echo $website['id']; ?>)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Create Website Modal -->
<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title"><i class="fas fa-plus-circle"></i> Create New Website</h2>
            <button class="modal-close" onclick="closeCreateModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <!-- Step 1: Website Type Selection -->
            <div id="step1">
                <h3 style="color: #142c4e; margin: 0 0 16px 0;">Select Website Type</h3>
                <div class="website-type-selector">
                    <div class="type-option" onclick="selectWebsiteType('html')">
                        <div class="type-option-icon"><i class="fas fa-code"></i></div>
                        <div class="type-option-label">HTML</div>
                    </div>
                    <div class="type-option" onclick="selectWebsiteType('php')">
                        <div class="type-option-icon"><i class="fab fa-php"></i></div>
                        <div class="type-option-label">PHP</div>
                    </div>
                    <div class="type-option" onclick="selectWebsiteType('wordpress')">
                        <div class="type-option-icon"><i class="fab fa-wordpress"></i></div>
                        <div class="type-option-label">WordPress</div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Website Domain</label>
                    <input type="text" id="websiteDomain" placeholder="example.com">
                </div>

                <div class="section-title">Storage Limit</div>
                <div class="form-group">
                    <label>Limit Website Storage</label>
                    <div class="storage-input-group">
                        <input type="number" id="storageLimit" placeholder="10" min="1">
                        <select id="storageUnit">
                            <option value="GB">GB</option>
                            <option value="TB">TB</option>
                            <option value="MB">MB</option>
                        </select>
                    </div>
                </div>

                <!-- WordPress Options -->
                <div id="wordpressOptions" class="wordpress-options">
                    <div class="section-title"><i class="fab fa-wordpress"></i> WordPress Configuration</div>
                    
                    <div class="form-group">
                        <label>WordPress Download URL</label>
                        <input type="text" id="wpUrl" placeholder="https://wordpress.org/latest.zip" value="https://wordpress.org/latest.zip">
                        <small style="color: #637b9b; margin-top: 4px; display: block;">Auto-filled with latest version</small>
                    </div>

                    <div class="form-group">
                        <label>Database Configuration</label>
                        <div class="db-selector">
                            <div class="db-option" onclick="selectDbOption('create')">
                                <i class="fas fa-plus-circle"></i> Create New
                            </div>
                            <div class="db-option" onclick="selectDbOption('existing')">
                                <i class="fas fa-database"></i> Use Existing
                            </div>
                        </div>
                    </div>

                    <!-- Create New Database -->
                    <div id="dbCreateOptions" style="display: none;">
                        <div class="form-group">
                            <label>Database Name</label>
                            <input type="text" id="dbName" placeholder="wordpress_db">
                        </div>
                        <div class="form-group">
                            <label>Database User</label>
                            <input type="text" id="dbUser" placeholder="wp_user">
                        </div>
                        <div class="form-group">
                            <label>Database Password</label>
                            <input type="password" id="dbPassword" placeholder="••••••••">
                            <div class="checkbox-group" style="margin-top: 8px;">
                                <input type="checkbox" id="autoGeneratePass" checked onchange="togglePasswordInput()">
                                <label style="margin: 0; cursor: pointer;">Auto-generate password</label>
                            </div>
                        </div>
                    </div>

                    <!-- Use Existing Database -->
                    <div id="dbExistingOptions" style="display: none;">
                        <div class="form-group">
                            <label>Select Database</label>
                            <select id="existingDb">
                                <option value="">Choose database...</option>
                                <option value="mysql_1">Database 1 (MySQL)</option>
                                <option value="mysql_2">Database 2 (MySQL)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Database User</label>
                            <input type="text" id="existingDbUser" placeholder="username">
                        </div>
                        <div class="form-group">
                            <label>Database Password</label>
                            <input type="password" id="existingDbPassword" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="section-title" style="margin-top: 24px;">Web Server Configuration</div>
                <div class="form-group">
                    <label>PHP Version (for PHP websites)</label>
                    <select id="phpVersion">
                        <option value="8.2">PHP 8.2 (Latest)</option>
                        <option value="8.1">PHP 8.1</option>
                        <option value="8.0">PHP 8.0</option>
                        <option value="7.4">PHP 7.4</option>
                    </select>
                </div>

                <div class="checkbox-group" style="margin: 16px 0;">
                    <input type="checkbox" id="enableSSL" checked>
                    <label style="margin: 0; cursor: pointer;">Enable SSL Certificate (Let's Encrypt)</label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeCreateModal()">Cancel</button>
            <button class="btn-primary" onclick="createWebsite()">
                <i class="fas fa-check"></i> Create Website
            </button>
        </div>
    </div>
</div>

<script>
    let selectedType = null;
    let selectedDbOption = 'create';

    function openCreateModal() {
        document.getElementById('createModal').classList.add('active');
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.remove('active');
        selectedType = null;
        document.querySelectorAll('.type-option').forEach(el => el.classList.remove('selected'));
    }

    function selectWebsiteType(type) {
        selectedType = type;
        document.querySelectorAll('.type-option').forEach(el => el.classList.remove('selected'));
        event.target.closest('.type-option').classList.add('selected');

        const wpOptions = document.getElementById('wordpressOptions');
        if (type === 'wordpress') {
            wpOptions.classList.add('show');
        } else {
            wpOptions.classList.remove('show');
        }
    }

    function selectDbOption(option) {
        selectedDbOption = option;
        document.querySelectorAll('.db-option').forEach(el => el.classList.remove('selected'));
        event.target.closest('.db-option').classList.add('selected');

        const createOpts = document.getElementById('dbCreateOptions');
        const existingOpts = document.getElementById('dbExistingOptions');

        if (option === 'create') {
            createOpts.style.display = 'block';
            existingOpts.style.display = 'none';
        } else {
            createOpts.style.display = 'none';
            existingOpts.style.display = 'block';
        }
    }

    function togglePasswordInput() {
        const input = document.getElementById('dbPassword');
        const checkbox = document.getElementById('autoGeneratePass');
        input.disabled = checkbox.checked;
    }

    function setView(view) {
        const gridView = document.getElementById('gridView');
        const tableView = document.getElementById('tableView');
        const buttons = document.querySelectorAll('.view-toggle button');

        buttons.forEach(btn => btn.classList.remove('active'));
        event.target.closest('button').classList.add('active');

        if (view === 'grid') {
            gridView.style.display = 'grid';
            tableView.style.display = 'none';
        } else {
            gridView.style.display = 'none';
            tableView.style.display = 'block';
        }
    }

    function viewWebsite(websiteId) {
        // Navigate to website details page
        window.location.href = '?page=websites&action=view&id=' + websiteId;
    }

    function createWebsite() {
        if (!selectedType) {
            alert('Please select a website type');
            return;
        }
        if (!document.getElementById('websiteDomain').value) {
            alert('Please enter a domain');
            return;
        }

        // TODO: Implement API call to create website
        console.log({
            type: selectedType,
            domain: document.getElementById('websiteDomain').value,
            storageLimit: document.getElementById('storageLimit').value + document.getElementById('storageUnit').value,
            phpVersion: document.getElementById('phpVersion').value,
            ssl: document.getElementById('enableSSL').checked
        });

        alert('Website creation submitted (preview mode - API integration needed)');
        closeCreateModal();
    }

    // Close modal when clicking outside
    document.getElementById('createModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateModal();
        }
    });
</script>
