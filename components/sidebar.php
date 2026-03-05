<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-header">
        <h2>
            <img src="<?php echo ASSETS_URL; ?>images/simple-panel-logo-no-bg-white.png" alt="simple-panel-logo" style="width:200px;">
        </h2>
    </div>

    <ul class="nav">
        <li class="nav-item <?php echo ($current_page === 'dashboard') ? 'active' : ''; ?>">
            <a href="<?php echo htmlspecialchars(url_with_lang(['page' => 'dashboard'])); ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span><?php echo htmlspecialchars(t('Dashboard')); ?></span>
            </a>
        </li>

        <div class="nav-section-label" style="margin-top:12px;"><?php echo htmlspecialchars(t('Applications')); ?></div>

        <li class="nav-item <?php echo ($current_page === 'websites') ? 'active' : ''; ?>">
            <a href="<?php echo htmlspecialchars(url_with_lang(['page' => 'websites'])); ?>">
                <i class="fas fa-folder-open"></i>
                <span><?php echo htmlspecialchars(t('Websites')); ?></span>
                <span class="badge-float">12</span>
            </a>
        </li>

        <li class="nav-item <?php echo ($current_page === 'nodeapps') ? 'active' : ''; ?>">
            <a href="<?php echo htmlspecialchars(url_with_lang(['page' => 'nodeapps'])); ?>">
                <i class="fab fa-node"></i>
                <span><?php echo htmlspecialchars(t('Node Apps')); ?></span>
                <span class="badge-float">4</span>
            </a>
        </li>

        <li class="nav-item <?php echo ($current_page === 'revproxy') ? 'active' : ''; ?>">
            <a href="<?php echo htmlspecialchars(url_with_lang(['page' => 'revproxy'])); ?>">
                <i class="fas fa-random"></i>
                <span><?php echo htmlspecialchars(t('Reverse Proxy')); ?></span>
                <span class="badge-float">7</span>
            </a>
        </li>

        <div class="nav-section-label" style="margin-top:12px;"><?php echo htmlspecialchars(t('System')); ?></div>

        <li class="nav-item <?php echo ($current_page === 'databases') ? 'active' : ''; ?>">
            <a href="<?php echo htmlspecialchars(url_with_lang(['page' => 'databases'])); ?>">
                <i class="fas fa-database"></i>
                <span><?php echo htmlspecialchars(t('Databases')); ?></span>
                <span class="badge-float">3</span>
            </a>
        </li>

        <li class="nav-item <?php echo ($current_page === 'domains') ? 'active' : ''; ?>">
            <a href="<?php echo htmlspecialchars(url_with_lang(['page' => 'domains'])); ?>">
                <i class="fas fa-tag"></i>
                <span><?php echo htmlspecialchars(t('Domains')); ?></span>
                <span class="badge-float">9</span>
            </a>
        </li>

        <li class="nav-item <?php echo ($current_page === 'ssl') ? 'active' : ''; ?>">
            <a href="<?php echo htmlspecialchars(url_with_lang(['page' => 'ssl'])); ?>">
                <i class="fas fa-lock"></i>
                <span><?php echo htmlspecialchars(t('SSL/TLS')); ?></span>
                <span class="badge-float">6</span>
            </a>
        </li>

        <li class="nav-item <?php echo ($current_page === 'filemanager') ? 'active' : ''; ?>">
            <a href="<?php echo htmlspecialchars(url_with_lang(['page' => 'filemanager'])); ?>">
                <i class="fas fa-folder-tree"></i>
                <span><?php echo htmlspecialchars(t('File Manager')); ?></span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <div class="storage-info">
            <div class="storage-header">
                <i class="fas fa-hard-drive"></i>
                <span id="sidebar-storage-text">Loading...</span>
            </div>
            <div class="storage-bar">
                <div class="storage-bar-fill" id="sidebar-storage-bar" style="width: 0%"></div>
            </div>
        </div>
    </div>
</aside>

<script>
// Fetch and update sidebar storage info
function updateSidebarStorage() {
    fetch('<?php echo BASE_URL; ?>api/server-stats.php')
        .then(response => response.json())
        .then(data => {
            const storageText = data.disk.used + ' / ' + data.disk.total;
            const percentage = data.disk.percentage;
            
            document.getElementById('sidebar-storage-text').textContent = 'Storage • ' + storageText;
            document.getElementById('sidebar-storage-bar').style.width = percentage + '%';
            
            // Color code the bar based on usage
            const bar = document.getElementById('sidebar-storage-bar');
            bar.classList.remove('low', 'medium', 'high');
            if (percentage < 60) {
                bar.classList.add('low');
            } else if (percentage < 85) {
                bar.classList.add('medium');
            } else {
                bar.classList.add('high');
            }
        })
        .catch(error => {
            console.error('Error fetching storage info:', error);
        });
}

// Update immediately and then every 10 seconds
updateSidebarStorage();
setInterval(updateSidebarStorage, 10000);
</script>

<style>
.sidebar-footer {
    padding: 15px 20px;
    margin-top: auto;
}

.storage-info {
    background: rgba(255, 255, 255, 0.05);
    padding: 12px;
    border-radius: 8px;
}

.storage-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-size: 0.85em;
    color: var(--text-secondary);
}

.storage-header i {
    font-size: 1em;
}

.storage-bar {
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    overflow: hidden;
}

.storage-bar-fill {
    height: 100%;
    background: var(--accent-color);
    transition: width 0.5s ease, background-color 0.3s ease;
    border-radius: 3px;
}

.storage-bar-fill.low {
    background: #4CAF50;
}

.storage-bar-fill.medium {
    background: #FF9800;
}

.storage-bar-fill.high {
    background: #f44336;
}
</style>
