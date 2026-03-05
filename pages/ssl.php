<?php
// SSL/TLS page
require_once COMPONENTS_PATH . '/stat-card.php';

$page_title = 'SSL/TLS';
$page_icon = 'fas fa-lock';
?>

<!-- Stats Grid -->
<div class="stats-grid">
    <?php
    render_stat_card('fas fa-certificate', 'SSL certificates', '6');
    render_stat_card('fas fa-shield-halved', 'Let\'s Encrypt', '4');
    render_stat_card('fas fa-calendar-check', 'Valid', '5');
    render_stat_card('fas fa-triangle-exclamation', 'Expiring (30d)', '1');
    ?>
</div>

<!-- Content Card -->
<div class="content-placeholder">
    <h3><i class="fas fa-lock" style="margin-right:10px;"></i>SSL/TLS certificates</h3>
    <p>Manage SSL certificates, automatic renewals with Let's Encrypt, and custom certificate uploads.</p>
    <div class="tag-list">
        <span class="tag">*.example.com (wildcard)</span>
        <span class="tag">api.domain.com (LE auto)</span>
        <span class="tag">secure.site (custom)</span>
        <span class="tag">app.io (cloudflare)</span>
    </div>
</div>
