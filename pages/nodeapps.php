<?php
require_once COMPONENTS_PATH . '/stat-card.php';
?>

<div class="stats-grid">
    <?php
    render_stat_card('fas fa-cube', t('pages.nodeapps.stat_apps'), '4');
    render_stat_card('fas fa-rocket', t('pages.nodeapps.stat_pm2'), '7');
    render_stat_card('fas fa-memory', t('pages.nodeapps.stat_heap'), '1.2 GB');
    render_stat_card('fas fa-rotate', t('pages.nodeapps.stat_restarts'), '2');
    ?>
</div>

<div class="content-placeholder">
    <h3><i class="fab fa-node" style="margin-right:10px;"></i><?php echo htmlspecialchars(t('pages.nodeapps.overview_title')); ?></h3>
    <p><?php echo htmlspecialchars(t('pages.nodeapps.overview_text')); ?></p>
    <div class="tag-list">
        <span class="tag">api-server (port 3001)</span>
        <span class="tag">dashboard (Next.js, port 3000)</span>
        <span class="tag">websocket (port 8080)</span>
        <span class="tag">cron-worker</span>
    </div>
</div>
