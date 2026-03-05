<?php
require_once COMPONENTS_PATH . '/stat-card.php';
?>

<div class="stats-grid">
    <?php
    render_stat_card('fas fa-certificate', t('pages.ssl.stat_certificates'), '6');
    render_stat_card('fas fa-shield-halved', t('pages.ssl.stat_lets_encrypt'), '4');
    render_stat_card('fas fa-calendar-check', t('pages.ssl.stat_valid'), '5');
    render_stat_card('fas fa-triangle-exclamation', t('pages.ssl.stat_expiring'), '1');
    ?>
</div>

<div class="content-placeholder">
    <h3><i class="fas fa-lock" style="margin-right:10px;"></i><?php echo htmlspecialchars(t('pages.ssl.overview_title')); ?></h3>
    <p><?php echo htmlspecialchars(t('pages.ssl.overview_text')); ?></p>
    <div class="tag-list">
        <span class="tag">*.example.com (wildcard)</span>
        <span class="tag">api.domain.com (LE auto)</span>
        <span class="tag">secure.site (custom)</span>
        <span class="tag">app.io (Cloudflare)</span>
    </div>
</div>
