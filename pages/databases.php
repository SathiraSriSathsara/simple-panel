<?php
require_once COMPONENTS_PATH . '/stat-card.php';
?>

<div class="stats-grid">
    <?php
    render_stat_card('fas fa-database', t('pages.databases.stat_total'), '3');
    render_stat_card('fas fa-table', t('pages.databases.stat_tables'), '47');
    render_stat_card('fas fa-hdd', t('pages.databases.stat_storage'), '2.4 GB');
    render_stat_card('fas fa-users', t('pages.databases.stat_users'), '5');
    ?>
</div>

<div class="content-placeholder">
    <h3><i class="fas fa-database" style="margin-right:10px;"></i><?php echo htmlspecialchars(t('pages.databases.overview_title')); ?></h3>
    <p><?php echo htmlspecialchars(t('pages.databases.overview_text')); ?></p>
    <div class="tag-list">
        <span class="tag">production_db (MySQL 8.0)</span>
        <span class="tag">analytics (PostgreSQL 14)</span>
        <span class="tag">cache_redis (Redis 7.0)</span>
    </div>
</div>
