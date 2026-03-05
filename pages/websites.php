<?php
// Websites page
require_once COMPONENTS_PATH . '/stat-card.php';

$page_title = 'Websites';
$page_icon = 'fas fa-folder-open';
?>

<!-- Stats Grid -->
<div class="stats-grid">
    <?php
    render_stat_card('fas fa-globe', 'Total sites', '12');
    render_stat_card('fas fa-chart-line', 'Traffic (last 24h)', '148k');
    render_stat_card('fas fa-wordpress', 'CMS installs', '8');
    render_stat_card('fas fa-shield', 'SSL enabled', '10');
    ?>
</div>

<!-- Content Card -->
<div class="content-placeholder">
    <h3><i class="fas fa-file-code" style="margin-right:10px;"></i>websites overview</h3>
    <p>example.com, myblog.net, shop.local … 12 entries. Manage your vhosts, php versions, and root directories.</p>
    <div class="tag-list">
        <span class="tag">example.com (php 8.2)</span>
        <span class="tag">laravel.app (node build)</span>
        <span class="tag">static.site (html)</span>
        <span class="tag">wordpress (mysql)</span>
    </div>
</div>
