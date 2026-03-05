<?php
/**
 * Status & Monitoring Dashboard
 * Monitor services, servers, and nodes status (Uptime Kuma style)
 */

require_once COMPONENTS_PATH . '/stat-card.php';

// Sample monitoring data
$monitors = [
    [
        'id' => 1,
        'name' => 'Web Server 1',
        'type' => 'http',
        'url' => 'https://example.com',
        'status' => 'up',
        'uptime' => '99.8%',
        'response_time' => '182ms',
        'checks' => 245,
        'failures' => 1,
        'last_check' => '2 seconds ago',
        'created' => '2024-01-15'
    ],
    [
        'id' => 2,
        'name' => 'MySQL Database',
        'type' => 'tcp',
        'url' => 'localhost:3306',
        'status' => 'up',
        'uptime' => '100%',
        'response_time' => '5ms',
        'checks' => 245,
        'failures' => 0,
        'last_check' => '1 second ago',
        'created' => '2024-01-10'
    ],
    [
        'id' => 3,
        'name' => 'Redis Cache',
        'type' => 'tcp',
        'url' => 'localhost:6379',
        'status' => 'up',
        'uptime' => '99.95%',
        'response_time' => '3ms',
        'checks' => 245,
        'failures' => 0,
        'last_check' => 'now',
        'created' => '2024-02-01'
    ],
    [
        'id' => 4,
        'name' => 'Email Service',
        'type' => 'http',
        'url' => 'https://mail.example.com',
        'status' => 'down',
        'uptime' => '95.2%',
        'response_time' => '5000ms+',
        'checks' => 245,
        'failures' => 21,
        'last_check' => '30 seconds ago',
        'created' => '2024-02-15'
    ],
    [
        'id' => 5,
        'name' => 'Node App Server',
        'type' => 'http',
        'url' => 'https://app.example.com:3000',
        'status' => 'up',
        'uptime' => '98.5%',
        'response_time' => '245ms',
        'checks' => 245,
        'failures' => 4,
        'last_check' => '5 seconds ago',
        'created' => '2024-02-20'
    ],
    [
        'id' => 6,
        'name' => 'SSL Certificate',
        'type' => 'ssl',
        'url' => 'example.com',
        'status' => 'up',
        'uptime' => '100%',
        'response_time' => '0ms',
        'checks' => 30,
        'failures' => 0,
        'last_check' => 'now',
        'created' => '2024-01-01',
        'expires' => 'Apr 15, 2025'
    ]
];

// Calculate overall stats
$total = count($monitors);
$up_count = count(array_filter($monitors, fn($m) => $m['status'] === 'up'));
$down_count = count(array_filter($monitors, fn($m) => $m['status'] === 'down'));
$avg_uptime = number_format(array_sum(array_map(fn($m) => floatval($m['uptime']), $monitors)) / $total, 2);

?>

<style>
    .status-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .status-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #142c4e;
        margin: 0;
    }

    .status-controls {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }

    .btn-add-monitor {
        background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%);
        color: #ffffff;
        border: none;
        padding: 10px 16px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add-monitor:hover {
        box-shadow: 0 4px 12px rgba(31, 58, 104, 0.3);
        transform: translateY(-1px);
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-box {
        background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dfe9f7;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
    }

    .stat-box-value {
        font-size: 2rem;
        font-weight: 700;
        color: #142c4e;
        margin-bottom: 8px;
    }

    .stat-box.up .stat-box-value {
        color: #20784a;
    }

    .stat-box.down .stat-box-value {
        color: #d94b4b;
    }

    .stat-box-label {
        font-size: 0.9rem;
        color: #4f6888;
        font-weight: 600;
    }

    .monitor-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .monitor-card {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dfe9f7;
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .monitor-card:hover {
        box-shadow: 0 8px 24px rgba(31, 58, 104, 0.12);
        border-color: #5f9cf8;
        transform: translateY(-2px);
    }

    .monitor-header {
        padding: 16px;
        background: linear-gradient(135deg, #edf3fd 0%, #f0f6ff 100%);
        border-bottom: 1px solid #dfe9f7;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }

    .monitor-title-section {
        flex: 1;
    }

    .monitor-name {
        font-size: 1rem;
        font-weight: 700;
        color: #142c4e;
        margin: 0 0 4px 0;
    }

    .monitor-url {
        font-size: 0.8rem;
        color: #637b9b;
        font-family: 'Monaco', 'Courier New', monospace;
        word-break: break-all;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge.up {
        background: #d4f5e8;
        color: #055d4a;
    }

    .status-badge.down {
        background: #f5d4d4;
        color: #8a2a2a;
    }

    .status-badge.checking {
        background: #fff3d4;
        color: #8a6b2a;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        animation: pulse 2s infinite;
    }

    .status-dot.up {
        background: #20784a;
    }

    .status-dot.down {
        background: #d94b4b;
    }

    .status-dot.checking {
        background: #f5a623;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .monitor-body {
        padding: 16px;
    }

    .monitor-stat {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #eef3fb;
        font-size: 0.9rem;
    }

    .monitor-stat:last-child {
        border-bottom: none;
    }

    .monitor-stat-label {
        color: #4f6888;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .monitor-stat-value {
        color: #142c4e;
        font-weight: 700;
        font-family: 'Monaco', 'Courier New', monospace;
    }

    .monitor-footer {
        padding: 12px 16px;
        background: #f8fbff;
        border-top: 1px solid #eef3fb;
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .monitor-action-btn {
        padding: 6px 10px;
        background: transparent;
        border: 1px solid #c8daf5;
        border-radius: 6px;
        color: #1f3a68;
        cursor: pointer;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .monitor-action-btn:hover {
        background: #e8f0ff;
        border-color: #5f9cf8;
    }

    .monitor-action-btn.danger {
        color: #d94b4b;
        border-color: #f5a8a8;
    }

    .monitor-action-btn.danger:hover {
        background: #ffe8e8;
        border-color: #d94b4b;
    }

    /* Timeline Chart */
    .chart-container {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dfe9f7;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .chart-title {
        font-size: 1rem;
        font-weight: 600;
        color: #142c4e;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .timeline {
        display: flex;
        gap: 2px;
        margin-bottom: 12px;
    }

    .timeline-day {
        flex: 1;
        min-height: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 1px;
        background: #eef3fb;
        border-radius: 6px;
        padding: 2px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .timeline-day:hover {
        background: #dae6ff;
    }

    .timeline-dot {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: #20784a;
    }

    .timeline-dot.down {
        background: #d94b4b;
    }

    .timeline-dot.checking {
        background: #f5a623;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: #ffffff;
        border-radius: 14px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        padding: 20px;
        border-bottom: 1px solid #dfe9f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 1.25rem;
        color: #142c4e;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #4f6888;
        cursor: pointer;
    }

    .modal-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #142c4e;
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #dfe9f7;
        border-radius: 8px;
        font-size: 0.95rem;
        font-family: inherit;
        transition: all 0.2s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #5f9cf8;
        box-shadow: 0 0 0 3px rgba(95, 156, 248, 0.1);
    }

    .modal-footer {
        padding: 16px 20px;
        border-top: 1px solid #dfe9f7;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .btn-primary {
        background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%);
        color: #ffffff;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        box-shadow: 0 4px 12px rgba(31, 58, 104, 0.3);
    }

    .btn-secondary {
        background: #edf3ff;
        color: #1f3a68;
        border: 1px solid #c7daf8;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-secondary:hover {
        background: #dae6ff;
    }

    @media (max-width: 768px) {
        .monitor-grid {
            grid-template-columns: 1fr;
        }

        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .monitor-header {
            flex-direction: column;
        }
    }
</style>

<!-- Header -->
<div class="status-header">
    <h1 class="status-title"><i class="fas fa-heartbeat"></i> System Status</h1>
    <div class="status-controls">
        <button class="btn-add-monitor" onclick="openAddMonitorModal()">
            <i class="fas fa-plus"></i> Add Monitor
        </button>
    </div>
</div>

<!-- Overall Statistics -->
<div class="stats-row">
    <div class="stat-box up">
        <div class="stat-box-value"><?php echo $up_count; ?>/<?php echo $total; ?></div>
        <div class="stat-box-label">Services Up</div>
    </div>
    <div class="stat-box down">
        <div class="stat-box-value"><?php echo $down_count; ?></div>
        <div class="stat-box-label">Services Down</div>
    </div>
    <div class="stat-box">
        <div class="stat-box-value"><?php echo $avg_uptime; ?>%</div>
        <div class="stat-box-label">Average Uptime</div>
    </div>
    <div class="stat-box">
        <div class="stat-box-value"><?php echo $total; ?></div>
        <div class="stat-box-label">Total Monitors</div>
    </div>
</div>

<!-- Monitors Grid -->
<div class="monitor-grid">
    <?php foreach ($monitors as $monitor): ?>
        <div class="monitor-card">
            <div class="monitor-header">
                <div class="monitor-title-section">
                    <h3 class="monitor-name"><?php echo htmlspecialchars($monitor['name']); ?></h3>
                    <div class="monitor-url"><?php echo htmlspecialchars($monitor['url']); ?></div>
                </div>
                <span class="status-badge <?php echo $monitor['status']; ?>">
                    <span class="status-dot <?php echo $monitor['status']; ?>"></span>
                    <?php echo ucfirst($monitor['status']); ?>
                </span>
            </div>

            <div class="monitor-body">
                <div class="monitor-stat">
                    <span class="monitor-stat-label"><i class="fas fa-percentage"></i> Uptime</span>
                    <span class="monitor-stat-value"><?php echo htmlspecialchars($monitor['uptime']); ?></span>
                </div>
                <div class="monitor-stat">
                    <span class="monitor-stat-label"><i class="fas fa-tachometer-alt"></i> Response Time</span>
                    <span class="monitor-stat-value"><?php echo htmlspecialchars($monitor['response_time']); ?></span>
                </div>
                <div class="monitor-stat">
                    <span class="monitor-stat-label"><i class="fas fa-check"></i> Total Checks</span>
                    <span class="monitor-stat-value"><?php echo $monitor['checks']; ?></span>
                </div>
                <div class="monitor-stat">
                    <span class="monitor-stat-label"><i class="fas fa-times"></i> Failures</span>
                    <span class="monitor-stat-value"><?php echo $monitor['failures']; ?></span>
                </div>
                <div class="monitor-stat">
                    <span class="monitor-stat-label"><i class="fas fa-clock"></i> Last Check</span>
                    <span class="monitor-stat-value"><?php echo htmlspecialchars($monitor['last_check']); ?></span>
                </div>
                <?php if (isset($monitor['expires'])): ?>
                    <div class="monitor-stat">
                        <span class="monitor-stat-label"><i class="fas fa-calendar"></i> Expires</span>
                        <span class="monitor-stat-value"><?php echo htmlspecialchars($monitor['expires']); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="monitor-footer">
                <button class="monitor-action-btn" onclick="viewMonitorDetails(<?php echo $monitor['id']; ?>)">
                    <i class="fas fa-chart-line"></i> Details
                </button>
                <button class="monitor-action-btn" onclick="editMonitor(<?php echo $monitor['id']; ?>)">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="monitor-action-btn danger" onclick="deleteMonitor(<?php echo $monitor['id']; ?>)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Uptime Chart -->
<div class="chart-container">
    <div class="chart-title">
        <i class="fas fa-history"></i> Uptime History (Last 30 Days)
    </div>
    <div class="timeline">
        <?php for ($i = 0; $i < 30; $i++): ?>
            <div class="timeline-day" title="Mar <?php echo (date('d') - $i); ?>, 2026">
                <div class="timeline-dot <?php echo rand(0, 10) > 1 ? 'up' : 'down'; ?>"></div>
                <div class="timeline-dot <?php echo rand(0, 10) > 0 ? 'up' : 'checking'; ?>"></div>
                <div class="timeline-dot <?php echo rand(0, 10) > 1 ? 'up' : 'down'; ?>"></div>
            </div>
        <?php endfor; ?>
    </div>
    <div style="font-size: 0.85rem; color: #637b9b; text-align: right;">
        <i class="fas fa-square" style="color: #20784a;"></i> Up 
        <i class="fas fa-square" style="color: #d94b4b; margin-left: 12px;"></i> Down
        <i class="fas fa-square" style="color: #f5a623; margin-left: 12px;"></i> Checking
    </div>
</div>

<!-- Add Monitor Modal -->
<div id="addMonitorModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Monitor</h2>
            <button class="modal-close" onclick="closeAddMonitorModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Monitor Name</label>
                <input type="text" id="monitorName" placeholder="e.g., My Website">
            </div>
            <div class="form-group">
                <label>Monitor Type</label>
                <select id="monitorType" onchange="updateMonitorTypeFields()">
                    <option value="http">HTTP/HTTPS</option>
                    <option value="tcp">TCP Port</option>
                    <option value="ping">Ping</option>
                    <option value="dns">DNS</option>
                    <option value="ssl">SSL Certificate</option>
                </select>
            </div>
            <div class="form-group">
                <label>URL/Hostname</label>
                <input type="text" id="monitorUrl" placeholder="https://example.com">
            </div>
            <div class="form-group">
                <label>Check Interval (seconds)</label>
                <input type="number" id="monitorInterval" value="60" min="30" max="3600">
            </div>
            <div class="form-group">
                <label>Timeout (seconds)</label>
                <input type="number" id="monitorTimeout" value="30" min="5" max="120">
            </div>
            <div class="form-group">
                <label>Notification</label>
                <select id="monitorNotification">
                    <option value="none">None</option>
                    <option value="email">Email</option>
                    <option value="webhook">Webhook</option>
                    <option value="all">All Methods</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeAddMonitorModal()">Cancel</button>
            <button class="btn-primary" onclick="createMonitor()">Add Monitor</button>
        </div>
    </div>
</div>

<script>
    function openAddMonitorModal() {
        document.getElementById('addMonitorModal').classList.add('active');
    }

    function closeAddMonitorModal() {
        document.getElementById('addMonitorModal').classList.remove('active');
    }

    function updateMonitorTypeFields() {
        const type = document.getElementById('monitorType').value;
        const urlInput = document.getElementById('monitorUrl');
        
        switch(type) {
            case 'http':
                urlInput.placeholder = 'https://example.com';
                break;
            case 'tcp':
                urlInput.placeholder = 'localhost:3306';
                break;
            case 'ping':
                urlInput.placeholder = 'example.com';
                break;
            case 'dns':
                urlInput.placeholder = 'example.com';
                break;
            case 'ssl':
                urlInput.placeholder = 'example.com';
                break;
        }
    }

    function createMonitor() {
        const name = document.getElementById('monitorName').value;
        const type = document.getElementById('monitorType').value;
        const url = document.getElementById('monitorUrl').value;
        const interval = document.getElementById('monitorInterval').value;
        const timeout = document.getElementById('monitorTimeout').value;
        const notification = document.getElementById('monitorNotification').value;

        if (!name || !url) {
            alert('Please fill in all required fields');
            return;
        }

        console.log('Monitor creation submitted (preview mode - API integration needed):', {
            name,
            type,
            url,
            interval,
            timeout,
            notification
        });

        alert('Monitor added! (Preview mode - API integration needed)');
        closeAddMonitorModal();
    }

    function viewMonitorDetails(id) {
        console.log('View monitor details:', id);
        alert('Monitor details page implementation needed (API integration)');
    }

    function editMonitor(id) {
        console.log('Edit monitor:', id);
        alert('Edit monitor form (API integration needed)');
    }

    function deleteMonitor(id) {
        if (confirm('Are you sure you want to delete this monitor?')) {
            console.log('Delete monitor:', id);
            alert('Monitor deleted! (Preview mode - API integration needed)');
        }
    }

    // Close modal when clicking outside
    document.getElementById('addMonitorModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddMonitorModal();
        }
    });
</script>
