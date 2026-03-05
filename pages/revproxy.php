<?php
// Reverse Proxy page
require_once COMPONENTS_PATH . '/stat-card.php';

$page_title = 'Reverse proxy';
$page_icon = 'fas fa-arrows-spin';
?>

<!-- Stats Grid -->
<div class="stats-grid">
    <?php
    render_stat_card('fas fa-arrow-right-arrow-left', 'proxy hosts', '7');
    render_stat_card('fas fa-network-wired', 'upstreams', '12');
    render_stat_card('fas fa-lock-open', 'SSL termination', '5');
    render_stat_card('fas fa-clock', 'avg response', '143 ms');
    ?>
</div>

<!-- Content Card -->
<div class="content-placeholder">
    <h3><i class="fas fa-arrows-spin" style="margin-right:10px;"></i>reverse proxy rules</h3>
    <p>NGINX / HAProxy style: route subdomains, path-based, load balancing, websocket support.</p>
    <div class="tag-list">
        <span class="tag">app.domain.com → local:3000</span>
        <span class="tag">api.domain.com → 10.0.0.2:9000</span>
        <span class="tag">static → /var/www</span>
        <span class="tag">socket → node:8080</span>
    </div>
</div>
