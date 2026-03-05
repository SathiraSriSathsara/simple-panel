
<?php
require_once __DIR__ . '/config/config.php';

$current_page = isset($_GET['page']) ? $_GET['page'] : DEFAULT_PAGE;
if (!in_array($current_page, $available_pages, true)) {
    $current_page = DEFAULT_PAGE;
}

$page_title_key = $page_config[$current_page]['title_key'] ?? '';
$page_title = $page_title_key !== '' ? t($page_title_key) : t('common.dashboard');
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
        echo '<h3>' . htmlspecialchars(t('common.page_not_found')) . '</h3>';
        echo '<p>' . htmlspecialchars(t('common.page_not_found_text')) . '</p>';
        echo '</div>';
    }
    ?>

    <hr>
    <div class="preview-note">
        <i class="fas fa-compass"></i> <?php echo htmlspecialchars(t('common.preview_note')); ?>
    </div>
</main>

<?php require_once INCLUDES_PATH . '/footer.php'; ?>
