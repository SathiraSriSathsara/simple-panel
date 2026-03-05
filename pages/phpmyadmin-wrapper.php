<?php
/**
 * phpMyAdmin Integration Wrapper for Simple Panel
 * Themed to match the panel's color scheme
 */

// Set base path for phpMyAdmin
define('PMA_MINIMUM_COMMON_PATH', './vendor/phpmyadmin/');
define('ROOT_PATH', './vendor/phpmyadmin/');

// Check if phpMyAdmin is being accessed directly
$is_phpmyadmin_request = isset($_REQUEST['route']) || isset($_REQUEST['db']) || isset($_REQUEST['table']) || isset($_POST['token']);

if ($is_phpmyadmin_request) {
    // Load phpMyAdmin
    chdir('./vendor/phpmyadmin/');
    
    // Override display to embed in panel
    ob_start();
    require './index.php';
    $phpmyadmin_content = ob_get_clean();
    chdir('../..');
    
    // Extract and modify phpMyAdmin output to match theme
    ?>
    <style>
        /* phpMyAdmin Theme Override - Match Panel Colors */
        
        /* Primary Colors Override */
        :root {
            --primary-color: #1f3a68;
            --primary-light: #2d538f;
            --primary-lighter: #5f9cf8;
            --bg-light: #f8fbff;
            --bg-lighter: #ffffff;
            --border-color: #dfe9f7;
            --text-primary: #142c4e;
            --text-secondary: #4f6688;
            --success-color: #20784a;
            --danger-color: #d94b4b;
        }
        
        /* phpMyAdmin Navigation */
        #pma_navigation {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%) !important;
            border-right: 1px solid #dfe9f7 !important;
        }
        
        #pma_navigation_tree .navItemContainer {
            border-bottom: 1px solid #eef3fb !important;
        }
        
        #pma_navigation_tree a:hover {
            background-color: #e8f0ff !important;
            color: #1f3a68 !important;
        }
        
        /* phpMyAdmin Header */
        #pma_header {
            background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%) !important;
            border-bottom: 1px solid #1a2f55 !important;
            color: #fff !important;
            box-shadow: 0 2px 8px rgba(18, 46, 84, 0.1) !important;
        }
        
        #pma_header a,
        #pma_header .navbar-brand {
            color: #ffffff !important;
        }
        
        #pma_header a:hover {
            color: #e8f0ff !important;
        }
        
        /* Buttons - Primary */
        .btn-primary,
        button[type="submit"],
        input[type="submit"] {
            background-color: #1f3a68 !important;
            background-image: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%) !important;
            border-color: #1a2f55 !important;
            color: #ffffff !important;
        }
        
        .btn-primary:hover,
        button[type="submit"]:hover,
        input[type="submit"]:hover {
            background-image: linear-gradient(135deg, #2d538f 0%, #1f3a68 100%) !important;
            box-shadow: 0 4px 12px rgba(31, 58, 104, 0.3) !important;
        }
        
        /* Buttons - Secondary */
        .btn-default,
        .btn-secondary {
            background-color: #edf3ff !important;
            border-color: #c7daf8 !important;
            color: #1f3a68 !important;
        }
        
        .btn-default:hover,
        .btn-secondary:hover {
            background-color: #dae6ff !important;
            border-color: #5f9cf8 !important;
        }
        
        /* Buttons - Danger */
        .btn-danger {
            background-color: #d94b4b !important;
            border-color: #c63838 !important;
            color: #ffffff !important;
        }
        
        .btn-danger:hover {
            background-color: #c63838 !important;
            border-color: #b02f2f !important;
        }
        
        /* Main Content Area */
        #main_panel,
        #pma_main {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%) !important;
            border-radius: 0 !important;
        }
        
        /* Table Styling */
        table,
        .table {
            background-color: #ffffff !important;
            border: 1px solid #dbe6f5 !important;
            border-radius: 12px !important;
            overflow: hidden !important;
            box-shadow: 0 1px 3px rgba(18, 46, 84, 0.05) !important;
        }
        
        thead,
        .table thead {
            background-color: #edf3fd !important;
            color: #1f3a68 !important;
        }
        
        th {
            background-color: #edf3fd !important;
            color: #1f3a68 !important;
            border-bottom: 2px solid #dbe6f5 !important;
            font-weight: 600 !important;
        }
        
        td {
            border-bottom: 1px solid #eef3fb !important;
            color: #163256 !important;
        }
        
        /* Form Inputs */
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="number"],
        input[type="date"],
        input[type="time"],
        select,
        textarea {
            border: 1px solid #c8daf5 !important;
            border-radius: 10px !important;
            background-color: #ffffff !important;
            color: #163256 !important;
            padding: 10px 12px !important;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus,
        input[type="time"]:focus,
        select:focus,
        textarea:focus {
            border-color: #5f9cf8 !important;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(95, 156, 248, 0.1) !important;
        }
        
        /* Alert Messages */
        .alert-success,
        .success {
            background-color: #e9f9ef !important;
            border: 1px solid #b6e8ca !important;
            color: #20784a !important;
            border-radius: 12px !important;
        }
        
        .alert-danger,
        .alert-error,
        .error {
            background-color: #ffecec !important;
            border: 1px solid #f8baba !important;
            color: #a63a3a !important;
            border-radius: 12px !important;
        }
        
        .alert-warning,
        .warning {
            background-color: #fff9e6 !important;
            border: 1px solid #ffe6a0 !important;
            color: #8b6914 !important;
            border-radius: 12px !important;
        }
        
        .alert-info,
        .info {
            background-color: #e6f3ff !important;
            border: 1px solid #a0d5ff !important;
            color: #004999 !important;
            border-radius: 12px !important;
        }
        
        /* Links */
        a, .link {
            color: #2d538f !important;
        }
        
        a:hover, .link:hover {
            color: #1f3a68 !important;
            text-decoration: underline !important;
        }
        
        /* Menu Items */
        .menuItem,
        .navItemContainer a {
            color: #4f6688 !important;
        }
        
        .menuItem.selected,
        .navItemContainer a.selected {
            background-color: #e8f0ff !important;
            color: #1f3a68 !important;
            border-left: 4px solid #2d538f !important;
        }
        
        /* Tabs */
        .tab,
        .tabActive,
        .nav-tabs .nav-link {
            border-bottom: 2px solid transparent !important;
            color: #4f6688 !important;
        }
        
        .tabActive,
        .nav-tabs .nav-link.active {
            border-bottom-color: #2d538f !important;
            color: #1f3a68 !important;
            background-color: transparent !important;
            font-weight: 600 !important;
        }
        
        /* Breadcrumb */
        .breadcrumb {
            background-color: #f8fbff !important;
            border-bottom: 1px solid #dfe9f7 !important;
        }
        
        .breadcrumb a {
            color: #2d538f !important;
        }
        
        .breadcrumb .active {
            color: #4f6688 !important;
        }
        
        /* Database/Table List Items */
        .item,
        .database,
        .table_list tbody tr {
            border-bottom: 1px solid #eef3fb !important;
        }
        
        .item:hover,
        .database:hover,
        .table_list tbody tr:hover {
            background-color: #f0f6ff !important;
        }
        
        /* SQL Query Editor */
        #sqlquery,
        .editor,
        textarea.sql {
            background-color: #ffffff !important;
            border: 1px solid #c8daf5 !important;
            color: #163256 !important;
            font-family: 'Monaco', 'Courier New', monospace !important;
        }
        
        /* Code/Syntax Highlighting */
        .syntax {
            color: #1f3a68 !important;
        }
        
        .syntax-keyword {
            color: #2d538f !important;
            font-weight: 600 !important;
        }
        
        .syntax-string {
            color: #20784a !important;
        }
        
        .syntax-number {
            color: #d94b4b !important;
        }
        
        /* Modals/Dialogs */
        .modal,
        .ui-dialog {
            border-radius: 18px !important;
            box-shadow: 0 12px 26px rgba(18, 46, 84, 0.15) !important;
        }
        
        .modal-header,
        .ui-dialog-titlebar {
            background: linear-gradient(135deg, #1f3a68 0%, #2d538f 100%) !important;
            color: #ffffff !important;
            border-radius: 18px 18px 0 0 !important;
        }
        
        .modal-body {
            background-color: #ffffff !important;
        }
        
        /* Utilities */
        .fieldset {
            border: 1px solid #dfe9f7 !important;
            border-radius: 12px !important;
            padding: 15px !important;
        }
        
        .fieldset legend {
            color: #1f3a68 !important;
            font-weight: 600 !important;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            #pma_navigation {
                display: none;
            }
            
            #main_panel {
                width: 100% !important;
            }
        }
    </style>
    <?php
    echo $phpmyadmin_content;
} else {
    // Display phpMyAdmin embedded in an iframe
    ?>
    <div class="db-wrap" style="padding: 0; border: none; background: transparent; box-shadow: none;">
        <style>
            .phpmyadmin-container {
                width: 100%;
                height: 90vh;
                border: 1px solid #dfe9f7;
                border-radius: 18px;
                overflow: hidden;
                background: #ffffff;
                box-shadow: 0 12px 26px rgba(18, 46, 84, 0.06);
            }
            
            .phpmyadmin-frame {
                width: 100%;
                height: 100%;
                border: none;
            }
        </style>
        <div class="phpmyadmin-container">
            <iframe class="phpmyadmin-frame" src="./vendor/phpmyadmin/index.php" allowfullscreen></iframe>
        </div>
    </div>
    <?php
}
