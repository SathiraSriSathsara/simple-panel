<?php
require_once COMPONENTS_PATH . '/stat-card.php';
?>

<div class="stats-grid">
    <?php
    render_stat_card('fas fa-certificate', t('SSL certificates'), '6');
    render_stat_card('fas fa-shield-halved', t("Let's Encrypt"), '4');
    render_stat_card('fas fa-calendar-check', t('Valid'), '5');
    render_stat_card('fas fa-triangle-exclamation', t('Expiring (30d)'), '1');
    ?>
</div>

<div class="content-placeholder">
    <h3><i class="fas fa-lock" style="margin-right:10px;"></i><?php echo htmlspecialchars(t('SSL/TLS certificates')); ?></h3>
    <p><?php echo htmlspecialchars(t("Manage SSL certificates, automatic renewals with Let's Encrypt, and custom certificate uploads.")); ?></p>
    <div class="tag-list">
        <span class="tag">*.example.com (wildcard)</span>
        <span class="tag">api.domain.com (LE auto)</span>
        <span class="tag">secure.site (custom)</span>
        <span class="tag">app.io (Cloudflare)</span>
    </div>
</div>
