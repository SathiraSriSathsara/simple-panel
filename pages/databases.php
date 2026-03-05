<?php
// Databases page
require_once COMPONENTS_PATH . '/stat-card.php';

$page_title = 'Databases';
$page_icon = 'fas fa-database';
?>

<!-- Stats Grid -->
<div class="stats-grid">
    <?php
    render_stat_card('fas fa-database', 'Total databases', '3');
    render_stat_card('fas fa-table', 'Tables', '47');
    render_stat_card('fas fa-hdd', 'Storage used', '2.4 GB');
    render_stat_card('fas fa-users', 'DB users', '5');
    ?>
</div>

<!-- Content Card -->
<div class="content-placeholder">
    <h3><i class="fas fa-database" style="margin-right:10px;"></i>database management</h3>
    <p>MySQL, PostgreSQL, and MongoDB instances. Manage users, backups, and access control.</p>
    <div class="tag-list">
        <span class="tag">production_db (MySQL 8.0)</span>
        <span class="tag">analytics (PostgreSQL 14)</span>
        <span class="tag">cache_redis (Redis 7.0)</span>
    </div>
</div>
