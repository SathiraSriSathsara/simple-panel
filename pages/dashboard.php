<?php
require_once COMPONENTS_PATH . '/stat-card.php';
?>

<div class="stats-grid">
    <div class="stat-card" id="cpu-card">
        <div class="stat-icon">
            <i class="fas fa-microchip"></i>
        </div>
        <div class="stat-content">
            <h4><?php echo htmlspecialchars(t('CPU Usage')); ?></h4>
            <div class="value" id="cpu-value">--</div>
        </div>
        <div class="stat-progress">
            <div class="progress-bar" id="cpu-bar" style="width: 0%"></div>
        </div>
    </div>

    <div class="stat-card" id="ram-card">
        <div class="stat-icon">
            <i class="fas fa-memory"></i>
        </div>
        <div class="stat-content">
            <h4><?php echo htmlspecialchars(t('RAM Usage')); ?></h4>
            <div class="value" id="ram-value">--</div>
        </div>
        <div class="stat-progress">
            <div class="progress-bar" id="ram-bar" style="width: 0%"></div>
        </div>
    </div>

    <div class="stat-card" id="disk-card">
        <div class="stat-icon">
            <i class="fas fa-hard-drive"></i>
        </div>
        <div class="stat-content">
            <h4><?php echo htmlspecialchars(t('Disk Usage')); ?></h4>
            <div class="value" id="disk-value">--</div>
        </div>
        <div class="stat-progress">
            <div class="progress-bar" id="disk-bar" style="width: 0%"></div>
        </div>
    </div>

    <div class="stat-card" id="network-card">
        <div class="stat-icon">
            <i class="fas fa-network-wired"></i>
        </div>
        <div class="stat-content">
            <h4><?php echo htmlspecialchars(t('Network Usage')); ?></h4>
            <div class="value" id="network-value">--</div>
        </div>
    </div>
</div>

<!-- Usage Graphs -->
<div class="graphs-section">
    <div class="graph-card">
        <h3><i class="fas fa-chart-line" style="margin-right:10px;"></i><?php echo htmlspecialchars(t('CPU Usage')); ?> - Real-time</h3>
        <canvas id="cpu-chart"></canvas>
    </div>

    <div class="graph-card">
        <h3><i class="fas fa-chart-area" style="margin-right:10px;"></i><?php echo htmlspecialchars(t('RAM Usage')); ?> - Real-time</h3>
        <canvas id="ram-chart"></canvas>
    </div>

    <div class="graph-card">
        <h3><i class="fas fa-chart-bar" style="margin-right:10px;"></i><?php echo htmlspecialchars(t('Disk Usage')); ?></h3>
        <canvas id="disk-chart"></canvas>
    </div>
</div>

<div class="content-placeholder">
    <h3><i class="fas fa-server" style="margin-right:10px;"></i><?php echo htmlspecialchars(t('Server Information')); ?></h3>
    <div class="server-info-grid">
        <div class="info-item">
            <strong><?php echo htmlspecialchars(t('Hostname')); ?>:</strong>
            <span id="hostname">--</span>
        </div>
        <div class="info-item">
            <strong><?php echo htmlspecialchars(t('Operating System')); ?>:</strong>
            <span id="os">--</span>
        </div>
        <div class="info-item">
            <strong><?php echo htmlspecialchars(t('Uptime')); ?>:</strong>
            <span id="uptime">--</span>
        </div>
        <div class="info-item">
            <strong><?php echo htmlspecialchars(t('Load Average')); ?>:</strong>
            <span id="load-average">--</span>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Chart data storage
const maxDataPoints = 20;
const cpuData = [];
const ramData = [];
const timeLabels = [];

// Initialize charts
const cpuChart = new Chart(document.getElementById('cpu-chart'), {
    type: 'line',
    data: {
        labels: timeLabels,
        datasets: [{
            label: 'CPU Usage (%)',
            data: cpuData,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    },
                    color: '#9ca3af'
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                }
            },
            x: {
                ticks: {
                    color: '#9ca3af'
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                }
            }
        },
        plugins: {
            legend: {
                labels: {
                    color: '#fff'
                }
            }
        },
        animation: {
            duration: 300
        }
    }
});

const ramChart = new Chart(document.getElementById('ram-chart'), {
    type: 'line',
    data: {
        labels: timeLabels,
        datasets: [{
            label: 'RAM Usage (%)',
            data: ramData,
            borderColor: 'rgb(255, 159, 64)',
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    },
                    color: '#9ca3af'
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                }
            },
            x: {
                ticks: {
                    color: '#9ca3af'
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                }
            }
        },
        plugins: {
            legend: {
                labels: {
                    color: '#fff'
                }
            }
        },
        animation: {
            duration: 300
        }
    }
});

const diskChart = new Chart(document.getElementById('disk-chart'), {
    type: 'doughnut',
    data: {
        labels: ['Used', 'Free'],
        datasets: [{
            data: [0, 100],
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgba(255, 255, 255, 0.1)'
            ],
            borderColor: [
                'rgb(255, 99, 132)',
                'rgba(255, 255, 255, 0.3)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#fff'
                }
            }
        }
    }
});

// Fetch server stats and update dashboard
function updateServerStats() {
    fetch('<?php echo BASE_URL; ?>api/server-stats.php')
        .then(response => response.json())
        .then(data => {
            // Update CPU
            document.getElementById('cpu-value').textContent = data.cpu.usage + '%';
            document.getElementById('cpu-bar').style.width = data.cpu.usage + '%';
            updateProgressColor('cpu-bar', data.cpu.usage);

            // Update RAM
            document.getElementById('ram-value').textContent = data.ram.used + ' / ' + data.ram.total;
            document.getElementById('ram-bar').style.width = data.ram.percentage + '%';
            updateProgressColor('ram-bar', data.ram.percentage);

            // Update Disk
            document.getElementById('disk-value').textContent = data.disk.used + ' / ' + data.disk.total;
            document.getElementById('disk-bar').style.width = data.disk.percentage + '%';
            updateProgressColor('disk-bar', data.disk.percentage);

            // Update Network
            document.getElementById('network-value').textContent = '↓ ' + data.network.received + ' ↑ ' + data.network.sent;

            // Update Server Info
            document.getElementById('hostname').textContent = data.server.hostname;
            document.getElementById('os').textContent = data.server.os;
            document.getElementById('uptime').textContent = data.server.uptime;
            document.getElementById('load-average').textContent = data.server.load_average;

            // Update charts
            updateCharts(data.cpu.usage, data.ram.percentage, data.disk.percentage);
        })
        .catch(error => {
            console.error('Error fetching server stats:', error);
        });
}

// Update charts with new data
function updateCharts(cpuUsage, ramUsage, diskUsage) {
    // Get current time
    const now = new Date();
    const timeStr = now.toLocaleTimeString();

    // Add new data point
    cpuData.push(cpuUsage);
    ramData.push(ramUsage);
    timeLabels.push(timeStr);

    // Keep only last maxDataPoints
    if (cpuData.length > maxDataPoints) {
        cpuData.shift();
        ramData.shift();
        timeLabels.shift();
    }

    // Update line charts
    cpuChart.update('none');
    ramChart.update('none');

    // Update disk chart
    diskChart.data.datasets[0].data = [diskUsage, 100 - diskUsage];
    diskChart.update();
}

// Update progress bar color based on usage percentage
function updateProgressColor(elementId, percentage) {
    const bar = document.getElementById(elementId);
    bar.classList.remove('low', 'medium', 'high');
    
    if (percentage < 50) {
        bar.classList.add('low');
    } else if (percentage < 80) {
        bar.classList.add('medium');
    } else {
        bar.classList.add('high');
    }
}

// Update stats immediately and then every 3 seconds
updateServerStats();
setInterval(updateServerStats, 3000);
</script>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-progress {
    margin-top: 10px;
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: var(--accent-color);
    transition: width 0.3s ease, background-color 0.3s ease;
    border-radius: 3px;
}

.progress-bar.low {
    background: #4CAF50;
}

.progress-bar.medium {
    background: #FF9800;
}

.progress-bar.high {
    background: #f44336;
}

.server-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.info-item {
    padding: 10px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 6px;
}

.info-item strong {
    display: block;
    margin-bottom: 5px;
    color: var(--text-secondary);
}

.info-item span {
    font-size: 1.1em;
    color: var(--text-primary);
}

.graphs-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.graph-card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    padding: 20px;
    backdrop-filter: blur(10px);
}

.graph-card h3 {
    margin: 0 0 20px 0;
    color: var(--text-primary);
    font-size: 1.1em;
    display: flex;
    align-items: center;
}

.graph-card canvas {
    height: 250px !important;
    max-height: 250px;
}
</style>
