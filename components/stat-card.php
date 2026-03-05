<?php
/**
 * Stat Card Component
 * 
 * @param string $icon - Font Awesome icon class
 * @param string $label - Card label
 * @param string $value - Card value
 */
function render_stat_card($icon, $label, $value) {
    ?>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="<?php echo htmlspecialchars($icon); ?>"></i>
        </div>
        <div class="stat-content">
            <h4><?php echo htmlspecialchars($label); ?></h4>
            <div class="value"><?php echo htmlspecialchars($value); ?></div>
        </div>
    </div>
    <?php
}
