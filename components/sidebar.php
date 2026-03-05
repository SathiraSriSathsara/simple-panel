<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-header">
        <h2>
            <img src="<?php echo ASSETS_URL; ?>images/simple-panel-logo-no-bg-white.png" alt="simple-panel-logo" style="width:200px;">
        </h2>
    </div>

    <!-- main navigation -->
    <ul class="nav">
        <!-- optional label -->
        <div class="nav-section-label">applications</div>

        <!-- 1. WEBSITES -->
        <li class="nav-item <?php echo ($current_page === 'websites') ? 'active' : ''; ?>">
            <a href="?page=websites">
                <i class="fas fa-folder-open"></i>
                <span>Websites</span>
                <span class="badge-float">12</span>
            </a>
        </li>

        <!-- 2. NODE APPS -->
        <li class="nav-item <?php echo ($current_page === 'nodeapps') ? 'active' : ''; ?>">
            <a href="?page=nodeapps">
                <i class="fab fa-node"></i>
                <span>Node apps</span>
                <span class="badge-float">4</span>
            </a>
        </li>

        <!-- 3. REVERSE PROXY -->
        <li class="nav-item <?php echo ($current_page === 'revproxy') ? 'active' : ''; ?>">
            <a href="?page=revproxy">
                <i class="fas fa-arrows-spin"></i>
                <span>Reverse proxy</span>
                <span class="badge-float">7</span>
            </a>
        </li>

        <!-- extra separation line + optional items -->
        <div class="nav-section-label" style="margin-top:12px;">system</div>
        
        <li class="nav-item <?php echo ($current_page === 'databases') ? 'active' : ''; ?>">
            <a href="?page=databases">
                <i class="fas fa-database"></i>
                <span>Databases</span>
                <span class="badge-float">3</span>
            </a>
        </li>
        
        <li class="nav-item <?php echo ($current_page === 'domains') ? 'active' : ''; ?>">
            <a href="?page=domains">
                <i class="fas fa-tag"></i>
                <span>Domains</span>
                <span class="badge-float">9</span>
            </a>
        </li>
        
        <li class="nav-item <?php echo ($current_page === 'ssl') ? 'active' : ''; ?>">
            <a href="?page=ssl">
                <i class="fas fa-lock"></i>
                <span>SSL/TLS</span>
                <span class="badge-float">6</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <i class="fas fa-cloud"></i>
        <span>node active • 2.31 GB / 8 GB</span>
    </div>
</aside>
