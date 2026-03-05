<?php
// Node Apps page
require_once COMPONENTS_PATH . '/stat-card.php';

$page_title = 'Node apps';
$page_icon = 'fab fa-node';
?>

<!-- Stats Grid -->
<div class="stats-grid">
    <?php
    render_stat_card('fas fa-cube', 'Node apps', '4');
    render_stat_card('fas fa-rocket', 'PM2 instances', '7');
    render_stat_card('fas fa-memory', 'Heap usage', '1.2 GB');
    render_stat_card('fas fa-rotate', 'restarts (24h)', '2');
    ?>
</div>

<!-- Content Card -->
<div class="content-placeholder">
    <h3><i class="fab fa-node" style="margin-right:10px;"></i>node.js applications</h3>
    <p>Express, Next.js, or fastify apps – each with environment variables, process monitoring, and logs.</p>
    <div class="tag-list">
        <span class="tag">api-server (port 3001)</span>
        <span class="tag">dashboard (next, port 3000)</span>
        <span class="tag">websocket (port 8080)</span>
        <span class="tag">cron-worker</span>
    </div>
</div>
