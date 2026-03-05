<?php
require_once COMPONENTS_PATH . '/stat-card.php';
?>

<div class="stats-grid">
    <?php
    render_stat_card('fas fa-globe-americas', t('Total domains'), '9');
    render_stat_card('fas fa-check-circle', t('Active'), '8');
    render_stat_card('fas fa-clock', t('Expiring soon'), '1');
    render_stat_card('fas fa-at', t('DNS records'), '34');
    ?>
</div>

<div class="content-placeholder">
    <h3><i class="fas fa-tag" style="margin-right:10px;"></i><?php echo htmlspecialchars(t('Domain management')); ?></h3>
    <p><?php echo htmlspecialchars(t('Register, transfer, and manage domains. Configure DNS records, nameservers, and forwarding.')); ?></p>
    <div class="tag-list">
        <span class="tag">example.com (expires 2027)</span>
        <span class="tag">myapp.io (active)</span>
        <span class="tag">website.dev (Cloudflare)</span>
        <span class="tag">api.net (custom NS)</span>
    </div>
</div>
