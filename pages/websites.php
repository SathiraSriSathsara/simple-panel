<?php
require_once COMPONENTS_PATH . '/stat-card.php';
?>

<div class="stats-grid">
    <?php
    render_stat_card('fas fa-globe', t('pages.websites.stat_total_sites'), '12');
    render_stat_card('fas fa-chart-line', t('pages.websites.stat_traffic'), '148k');
    render_stat_card('fas fa-wordpress', t('pages.websites.stat_cms'), '8');
    render_stat_card('fas fa-shield', t('pages.websites.stat_ssl'), '10');
    ?>
</div>

<div class="content-placeholder">
    <h3><i class="fas fa-file-code" style="margin-right:10px;"></i><?php echo htmlspecialchars(t('pages.websites.overview_title')); ?></h3>
    <p><?php echo htmlspecialchars(t('pages.websites.overview_text')); ?></p>
    <div class="tag-list">
        <span class="tag">example.com (PHP 8.2)</span>
        <span class="tag">laravel.app (node build)</span>
        <span class="tag">static.site (HTML)</span>
        <span class="tag">wordpress (MySQL)</span>
    </div>
</div>
