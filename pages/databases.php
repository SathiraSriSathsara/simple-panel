<?php
require_once COMPONENTS_PATH . '/stat-card.php';
?>

<div class="stats-grid">
    <?php
    render_stat_card('fas fa-database', t('Total databases'), '3');
    render_stat_card('fas fa-table', t('Tables'), '47');
    render_stat_card('fas fa-hdd', t('Storage used'), '2.4 GB');
    render_stat_card('fas fa-users', t('DB users'), '5');
    ?>
</div>

<div class="content-placeholder">
    <h3><i class="fas fa-database" style="margin-right:10px;"></i><?php echo htmlspecialchars(t('Database management')); ?></h3>
    <p><?php echo htmlspecialchars(t('MySQL, PostgreSQL, and MongoDB instances. Manage users, backups, and access control.')); ?></p>
    <div class="tag-list">
        <span class="tag">production_db (MySQL 8.0)</span>
        <span class="tag">analytics (PostgreSQL 14)</span>
        <span class="tag">cache_redis (Redis 7.0)</span>
    </div>
</div>
