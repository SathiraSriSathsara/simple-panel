<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-header">
        <h2>
            <img src="<?php echo ASSETS_URL; ?>images/simple-panel-logo-no-bg-white.png" alt="simple-panel-logo" style="width:200px;">
        </h2>
    </div>

    <ul class="nav">
        <div class="nav-section-label"><?php echo htmlspecialchars(t('nav.applications')); ?></div>

        <li class="nav-item <?php echo ($current_page === 'websites') ? 'active' : ''; ?>">
            <a href="<?php echo htmlspecialchars(url_with_lang(['page' => 'websites'])); ?>">
                <i class="fas fa-folder-open"></i>
                <span><?php echo htmlspecialchars(t('nav.websites')); ?></span>
                <span class="badge-float">12</span>
            </a>
        </li>

        <li class="nav-item <?php echo ($current_page === 'nodeapps') ? 'active' : ''; ?>">
            <a href="<?php echo htmlspecialchars(url_with_lang(['page' => 'nodeapps'])); ?>">
                <i class="fab fa-node"></i>
                <span><?php echo htmlspecialchars(t('nav.nodeapps')); ?></span>
                <span class="badge-float">4</span>
            </a>
        </li>

        <li class="nav-item <?php echo ($current_page === 'revproxy') ? 'active' : ''; ?>">
            <a href="<?php echo htmlspecialchars(url_with_lang(['page' => 'revproxy'])); ?>">
                <i class="fas fa-arrows-spin"></i>
                <span><?php echo htmlspecialchars(t('nav.revproxy')); ?></span>
                <span class="badge-float">7</span>
            </a>
        </li>

        <div class="nav-section-label" style="margin-top:12px;"><?php echo htmlspecialchars(t('nav.system')); ?></div>

        <li class="nav-item <?php echo ($current_page === 'databases') ? 'active' : ''; ?>">
            <a href="<?php echo htmlspecialchars(url_with_lang(['page' => 'databases'])); ?>">
                <i class="fas fa-database"></i>
                <span><?php echo htmlspecialchars(t('nav.databases')); ?></span>
                <span class="badge-float">3</span>
            </a>
        </li>

        <li class="nav-item <?php echo ($current_page === 'domains') ? 'active' : ''; ?>">
            <a href="<?php echo htmlspecialchars(url_with_lang(['page' => 'domains'])); ?>">
                <i class="fas fa-tag"></i>
                <span><?php echo htmlspecialchars(t('nav.domains')); ?></span>
                <span class="badge-float">9</span>
            </a>
        </li>

        <li class="nav-item <?php echo ($current_page === 'ssl') ? 'active' : ''; ?>">
            <a href="<?php echo htmlspecialchars(url_with_lang(['page' => 'ssl'])); ?>">
                <i class="fas fa-lock"></i>
                <span><?php echo htmlspecialchars(t('nav.ssl')); ?></span>
                <span class="badge-float">6</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <i class="fas fa-cloud"></i>
        <span>Storage • 2.31 GB / 8 GB</span>
    </div>
</aside>
