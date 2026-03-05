<?php
/**
 * Server Stats API
 * Returns real-time server statistics
 */

header('Content-Type: application/json');

function getServerStats() {
    $stats = [];

    // Get CPU usage
    $stats['cpu'] = getCpuUsage();

    // Get RAM usage
    $stats['ram'] = getRamUsage();

    // Get Disk usage
    $stats['disk'] = getDiskUsage();

    // Get Network usage
    $stats['network'] = getNetworkUsage();

    // Get Server info
    $stats['server'] = getServerInfo();

    return $stats;
}

function getCpuUsage() {
    $cpu = 0;
    
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // Windows
        $output = shell_exec('wmic cpu get loadpercentage');
        if ($output) {
            $lines = explode("\n", trim($output));
            if (isset($lines[1])) {
                $cpu = (int)trim($lines[1]);
            }
        }
    } else {
        // Linux/Unix
        $load = sys_getloadavg();
        $cpu = round($load[0] * 100 / 4, 2); // Assuming 4 cores, adjust as needed
    }

    return [
        'usage' => $cpu
    ];
}

function getRamUsage() {
    $total = 0;
    $free = 0;
    $used = 0;
    
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // Windows
        $output = shell_exec('wmic OS get FreePhysicalMemory,TotalVisibleMemorySize /Value');
        if ($output) {
            preg_match('/FreePhysicalMemory=(\d+)/', $output, $free_match);
            preg_match('/TotalVisibleMemorySize=(\d+)/', $output, $total_match);
            
            if ($free_match && $total_match) {
                $free = $free_match[1] * 1024; // Convert KB to bytes
                $total = $total_match[1] * 1024;
                $used = $total - $free;
            }
        }
    } else {
        // Linux/Unix
        $meminfo = @file_get_contents('/proc/meminfo');
        if ($meminfo) {
            preg_match('/MemTotal:\s+(\d+)/', $meminfo, $total_match);
            preg_match('/MemAvailable:\s+(\d+)/', $meminfo, $avail_match);
            
            if ($total_match && $avail_match) {
                $total = $total_match[1] * 1024;
                $free = $avail_match[1] * 1024;
                $used = $total - $free;
            }
        }
    }

    $percentage = $total > 0 ? round(($used / $total) * 100, 2) : 0;

    return [
        'total' => formatBytes($total),
        'used' => formatBytes($used),
        'free' => formatBytes($free),
        'percentage' => $percentage
    ];
}

function getDiskUsage() {
    $total = disk_total_space('/');
    $free = disk_free_space('/');
    $used = $total - $free;
    $percentage = $total > 0 ? round(($used / $total) * 100, 2) : 0;

    return [
        'total' => formatBytes($total),
        'used' => formatBytes($used),
        'free' => formatBytes($free),
        'percentage' => $percentage
    ];
}

function getNetworkUsage() {
    // This is a simplified version
    // For real network monitoring, you'd need to track deltas over time
    
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // Windows - simplified
        return [
            'received' => '0 B/s',
            'sent' => '0 B/s'
        ];
    } else {
        // Linux/Unix
        $netstat = @file_get_contents('/proc/net/dev');
        if ($netstat) {
            $lines = explode("\n", $netstat);
            $rx_bytes = 0;
            $tx_bytes = 0;
            
            foreach ($lines as $line) {
                if (preg_match('/^\s*(\w+):\s*(\d+)\s+\d+\s+\d+\s+\d+\s+\d+\s+\d+\s+\d+\s+\d+\s+(\d+)/', $line, $matches)) {
                    $interface = $matches[1];
                    if ($interface !== 'lo') { // Skip loopback
                        $rx_bytes += $matches[2];
                        $tx_bytes += $matches[3];
                    }
                }
            }
            
            return [
                'received' => formatBytes($rx_bytes),
                'sent' => formatBytes($tx_bytes)
            ];
        }
    }

    return [
        'received' => 'N/A',
        'sent' => 'N/A'
    ];
}

function getServerInfo() {
    $uptime = 'N/A';
    
    if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
        // Linux/Unix
        $uptime_data = @file_get_contents('/proc/uptime');
        if ($uptime_data) {
            $uptime_seconds = (int)explode(' ', $uptime_data)[0];
            $uptime = formatUptime($uptime_seconds);
        }
    } else {
        // Windows
        $output = shell_exec('net statistics workstation');
        if ($output && preg_match('/Statistics since (.+)/', $output, $matches)) {
            $boot_time = strtotime($matches[1]);
            $uptime_seconds = time() - $boot_time;
            $uptime = formatUptime($uptime_seconds);
        }
    }

    $load_avg = 'N/A';
    if (function_exists('sys_getloadavg')) {
        $load = sys_getloadavg();
        $load_avg = implode(', ', array_map(function($val) {
            return number_format($val, 2);
        }, $load));
    }

    return [
        'hostname' => gethostname(),
        'os' => PHP_OS . ' (' . php_uname('r') . ')',
        'uptime' => $uptime,
        'load_average' => $load_avg
    ];
}

function formatBytes($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, 2) . ' ' . $units[$pow];
}

function formatUptime($seconds) {
    $days = floor($seconds / 86400);
    $hours = floor(($seconds % 86400) / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    
    $parts = [];
    if ($days > 0) $parts[] = $days . 'd';
    if ($hours > 0) $parts[] = $hours . 'h';
    if ($minutes > 0) $parts[] = $minutes . 'm';
    
    return implode(' ', $parts) ?: '0m';
}

// Output the stats
echo json_encode(getServerStats());
