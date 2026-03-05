<?php
require_once COMPONENTS_PATH . '/stat-card.php';
?>

<div class="stats-grid">
    <?php
    render_stat_card('fas fa-globe', t('Total sites'), '12');
    render_stat_card('fas fa-chart-line', t('Traffic (last 24h)'), '148k');
    render_stat_card('fas fa-wordpress', t('CMS installs'), '8');
    render_stat_card('fas fa-shield', t('SSL enabled'), '10');
    ?>
</div>

<div class="content-placeholder">
    <h3><i class="fas fa-file-code" style="margin-right:10px;"></i><?php echo htmlspecialchars(t('Websites overview')); ?></h3>
    <p><?php echo htmlspecialchars(t('example.com, myblog.net, shop.local ... 12 entries. Manage your vhosts, PHP versions, and root directories.')); ?></p>
    <div class="tag-list">
        <span class="tag">example.com (PHP 8.2)</span>
        <span class="tag">laravel.app (node build)</span>
        <span class="tag">static.site (HTML)</span>
        <span class="tag">wordpress (MySQL)</span>
    </div>
</div>
