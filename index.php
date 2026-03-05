
<?php
// Main entry point for Simple Panel
require_once __DIR__ . '/config/config.php';

// Get the requested page from URL parameter
$current_page = isset($_GET['page']) ? $_GET['page'] : DEFAULT_PAGE;

// Validate the requested page
if (!in_array($current_page, $available_pages)) {
    $current_page = DEFAULT_PAGE;
}

// Set page-specific variables from config
$page_title = $page_config[$current_page]['title'] ?? 'Dashboard';
$page_icon = $page_config[$current_page]['icon'] ?? 'fas fa-cog';

// Include header
require_once INCLUDES_PATH . '/header.php';
?>

<!-- Include Sidebar -->
<?php require_once COMPONENTS_PATH . '/sidebar.php'; ?>

<!-- MAIN PANEL -->
<main class="main-panel">
    
    <!-- Include Top Bar -->
    <?php require_once COMPONENTS_PATH . '/topbar.php'; ?>
    
    <!-- Include the requested page content -->
    <?php
    $page_file = PAGES_PATH . '/' . $current_page . '.php';
    if (file_exists($page_file)) {
        require_once $page_file;
    } else {
        echo '<div class="content-placeholder">';
        echo '<h3>Page not found</h3>';
        echo '<p>The requested page does not exist.</p>';
        echo '</div>';
    }
    ?>
    
    <hr>
    <div class="preview-note">
        <i class="fas-regular fa-compass"></i> sidebar includes: websites, node apps, reverse proxy (icons & counters)
    </div>
</main>

<?php
// Include footer
require_once INCLUDES_PATH . '/footer.php';
?>
