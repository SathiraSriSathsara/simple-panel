
<?php
require_once __DIR__ . '/config/config.php';

$current_page = isset($_GET['page']) ? $_GET['page'] : DEFAULT_PAGE;
if (!in_array($current_page, $available_pages, true)) {
    $current_page = DEFAULT_PAGE;
}

$page_title = $page_config[$current_page]['title'] ?? 'Dashboard';
$page_icon = $page_config[$current_page]['icon'] ?? 'fas fa-cog';

require_once INCLUDES_PATH . '/header.php';
?>

<?php require_once COMPONENTS_PATH . '/sidebar.php'; ?>

<main class="main-panel">
    <?php require_once COMPONENTS_PATH . '/topbar.php'; ?>

    <?php
    $page_file = PAGES_PATH . '/' . $current_page . '.php';
    if (file_exists($page_file)) {
        require_once $page_file;
    } else {
        echo '<div class="content-placeholder">';
        echo '<h3>' . htmlspecialchars(t('Page not found')) . '</h3>';
        echo '<p>' . htmlspecialchars(t('The requested page does not exist.')) . '</p>';
        echo '</div>';
    }
    ?>

    <hr>
    <div class="preview-note">
        <i class="fas fa-compass"></i> <?php echo htmlspecialchars(t('Sidebar includes: websites, node apps, reverse proxy (icons and counters)')); ?>
    </div>
</main>

<?php require_once INCLUDES_PATH . '/footer.php'; ?>
