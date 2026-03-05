<?php
require_once COMPONENTS_PATH . '/stat-card.php';
?>

<div class="stats-grid">
    <?php
    render_stat_card('fas fa-arrow-right-arrow-left', t('pages.revproxy.stat_hosts'), '7');
    render_stat_card('fas fa-network-wired', t('pages.revproxy.stat_upstreams'), '12');
    render_stat_card('fas fa-lock-open', t('pages.revproxy.stat_ssl_term'), '5');
    render_stat_card('fas fa-clock', t('pages.revproxy.stat_avg_response'), '143 ms');
    ?>
</div>

<div class="content-placeholder">
    <h3><i class="fas fa-arrows-spin" style="margin-right:10px;"></i><?php echo htmlspecialchars(t('pages.revproxy.overview_title')); ?></h3>
    <p><?php echo htmlspecialchars(t('pages.revproxy.overview_text')); ?></p>
    <div class="tag-list">
        <span class="tag">app.domain.com -> local:3000</span>
        <span class="tag">api.domain.com -> 10.0.0.2:9000</span>
        <span class="tag">static -> /var/www</span>
        <span class="tag">socket -> node:8080</span>
    </div>
</div>
