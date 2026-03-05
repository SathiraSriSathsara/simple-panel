<?php
// Domains page
require_once COMPONENTS_PATH . '/stat-card.php';

$page_title = 'Domains';
$page_icon = 'fas fa-tag';
?>

<!-- Stats Grid -->
<div class="stats-grid">
    <?php
    render_stat_card('fas fa-globe-americas', 'Total domains', '9');
    render_stat_card('fas fa-check-circle', 'Active', '8');
    render_stat_card('fas fa-clock', 'Expiring soon', '1');
    render_stat_card('fas fa-at', 'DNS records', '34');
    ?>
</div>

<!-- Content Card -->
<div class="content-placeholder">
    <h3><i class="fas fa-tag" style="margin-right:10px;"></i>domain management</h3>
    <p>Register, transfer, and manage domains. Configure DNS records, nameservers, and domain forwarding.</p>
    <div class="tag-list">
        <span class="tag">example.com (expires 2027)</span>
        <span class="tag">myapp.io (active)</span>
        <span class="tag">website.dev (cloudflare)</span>
        <span class="tag">api.net (custom NS)</span>
    </div>
</div>
