<?php
/**
 * Database Management - phpMyAdmin Integration
 * Styled to match panel theme colors: #1f3a68, #2d538f, #5f9cf8
 */

require_once COMPONENTS_PATH . '/stat-card.php';

// phpMyAdmin path
$pma_path = BASE_PATH . '/vendor/phpmyadmin/index.php';
$pma_installed = file_exists($pma_path);

?>

<style>
    .db-wrap {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dfe9f7;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 12px 26px rgba(18, 46, 84, 0.06);
        margin-bottom: 20px;
    }

    .db-title {
        margin: 0 0 16px 0;
        color: #142c4e;
        font-size: 1.2rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pma-container {
        width: 100%;
        height: calc(100vh - 250px);
        min-height: 600px;
        border: 1px solid #dfe9f7;
        border-radius: 18px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 12px 26px rgba(18, 46, 84, 0.06);
    }

    .pma-iframe {
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 18px;
    }

    .pma-info {
        background: linear-gradient(135deg, #e8f0ff 0%, #f0f6ff 100%);
        border: 1px solid #c7daf8;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
        color: #1f3a68;
    }

    .pma-info strong {
        color: #142c4e;
    }

    .pma-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 400px;
        color: #4f6888;
        font-size: 1.1rem;
    }

    .pma-loading::after {
        content: "";
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #c8daf5;
        border-top-color: #2d538f;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 10px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    @media (max-width: 900px) {
        .pma-container {
            height: calc(100vh - 300px);
            min-height: 400px;
        }
    }

    /* phpMyAdmin Theming Override */
    <style>
        /* Inject custom theme styles for phpMyAdmin inside iframe */
        :root {
            --primary-color: #1f3a68;
            --primary-light: #2d538f;
            --primary-lighter: #5f9cf8;
            --bg-light: #f8fbff;
            --bg-lighter: #ffffff;
            --border-color: #dfe9f7;
            --text-primary: #142c4e;
            --text-secondary: #4f6888;
            --success-color: #20784a;
            --danger-color: #d94b4b;
        }

        /* phpMyAdmin Navigation */
        body #pma_navigation,
        iframe #pma_navigation {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%) !important;
            border-right: 1px solid #dfe9f7 !important;
        }

        /* phpMyAdmin Header */
        body #pma_header,
        iframe #pma_header {
            background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%) !important;
            border-bottom: 1px solid #1a2f55 !important;
            color: #fff !important;
            box-shadow: 0 2px 8px rgba(18, 46, 84, 0.1) !important;
        }

        /* Buttons */
        button, .btn, input[type="submit"], input[type="button"] {
            background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%) !important;
            color: #ffffff !important;
            border-color: #1a2f55 !important;
            border-radius: 8px !important;
        }

        button:hover, .btn:hover, input[type="submit"]:hover {
            box-shadow: 0 4px 12px rgba(31, 58, 104, 0.3) !important;
        }

        /* Tables */
        table, .table {
            background-color: #ffffff !important;
            border: 1px solid #dbe6f5 !important;
            border-radius: 8px !important;
            overflow: hidden !important;
        }

        thead, .table thead {
            background-color: #edf3fd !important;
            color: #1f3a68 !important;
        }

        /* Forms */
        input, select, textarea {
            border: 1px solid #c8daf5 !important;
            border-radius: 6px !important;
            background-color: #ffffff !important;
            color: #163256 !important;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #5f9cf8 !important;
            box-shadow: 0 0 0 3px rgba(95, 156, 248, 0.1) !important;
            outline: none !important;
        }

        /* Alerts */
        .alert-success, .success {
            background-color: #e9f9ef !important;
            border-color: #b6e8ca !important;
            color: #20784a !important;
        }

        .alert-danger, .alert-error, .error {
            background-color: #ffecec !important;
            border-color: #f8baba !important;
            color: #a63a3a !important;
        }

        /* Links */
        a {
            color: #2d538f !important;
        }

        a:hover {
            color: #1f3a68 !important;
        }
    </style>
</style>

<div class="stats-grid">
    <?php
    render_stat_card('fas fa-database', t('Database Manager'), 'phpMyAdmin');
    render_stat_card('fas fa-plug', t('Connection'), 'Active');
    render_stat_card('fas fa-shield-alt', t('Security'), 'Enabled');
    render_stat_card('fas fa-tachometer-alt', t('Performance'), 'Optimized');
    ?>
</div>

<?php if ($pma_installed): ?>
    
    <div class="pma-info">
        <strong><i class="fas fa-info-circle"></i> PHP MyAdmin Database Manager</strong><br>
        Manage databases, tables, users, and execute queries with phpMyAdmin interface styled to match your panel theme.
    </div>

    <div class="db-wrap" style="padding: 0; border: none; background: transparent; box-shadow: none;">
        <div class="pma-container">
            <div class="pma-loading">Loading phpMyAdmin...</div>
            <iframe 
                class="pma-iframe" 
                src="<?php echo htmlspecialchars(BASE_PATH . '/vendor/phpmyadmin/index.php'); ?>"
                allow="same-origin"
                onload="document.querySelector('.pma-loading').style.display='none'; this.style.display='block';"
            ></iframe>
        </div>
    </div>

<?php else: ?>
    
    <div class="db-wrap">
        <h3 class="db-title" style="color: #d94b4b;">
            <i class="fas fa-exclamation-circle"></i> phpMyAdmin Not Installed
        </h3>
        <p style="color: #4f6888; margin: 0;">
            phpMyAdmin has not been installed yet. To set it up, run the following command in your project directory:
        </p>
        <div style="
            background: #ffffff;
            border: 1px solid #c8daf5;
            border-radius: 10px;
            padding: 12px;
            margin-top: 12px;
            font-family: 'Monaco', 'Courier New', monospace;
            color: #163256;
            overflow-x: auto;
        ">
            npm install phpmyadmin
        </div>
        <p style="color: #4f6888; margin-top: 12px; margin-bottom: 0;">
            Or download phpMyAdmin and extract it to the <code>vendor/phpmyadmin</code> directory manually.
        </p>
    </div>

<?php endif; ?>
