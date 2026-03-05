<?php
require_once COMPONENTS_PATH . '/stat-card.php';
?>

<div class="stats-grid">
    <?php
    render_stat_card('fas fa-globe-americas', t('pages.domains.stat_total'), '9');
    render_stat_card('fas fa-check-circle', t('pages.domains.stat_active'), '8');
    render_stat_card('fas fa-clock', t('pages.domains.stat_expiring'), '1');
    render_stat_card('fas fa-at', t('pages.domains.stat_dns'), '34');
    ?>
</div>

<div class="content-placeholder">
    <h3><i class="fas fa-tag" style="margin-right:10px;"></i><?php echo htmlspecialchars(t('pages.domains.overview_title')); ?></h3>
    <p><?php echo htmlspecialchars(t('pages.domains.overview_text')); ?></p>
    <div class="tag-list">
        <span class="tag">example.com (expires 2027)</span>
        <span class="tag">myapp.io (active)</span>
        <span class="tag">website.dev (Cloudflare)</span>
        <span class="tag">api.net (custom NS)</span>
    </div>
</div>
