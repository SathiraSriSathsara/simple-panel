<!-- TOP BAR -->
<div class="top-bar">
    <div class="page-title">
        <i class="<?php echo isset($page_icon) ? htmlspecialchars($page_icon) : 'fas fa-cog'; ?>"></i>
        <span><?php echo isset($page_title) ? htmlspecialchars($page_title) : htmlspecialchars(t('common.dashboard')); ?></span>
    </div>

    <div class="topbar-actions">
        <form class="language-switcher" method="get">
            <input type="hidden" name="page" value="<?php echo htmlspecialchars($current_page); ?>">
            <label for="lang"><?php echo htmlspecialchars(t('common.language')); ?></label>
            <select id="lang" name="lang" onchange="this.form.submit()">
                <?php foreach ($available_languages as $lang_code => $lang_name): ?>
                    <option value="<?php echo htmlspecialchars($lang_code); ?>" <?php echo ($lang_code === $current_language) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($lang_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <div class="status-chip">
            <i class="fas fa-circle" style="color: #32b86d; font-size: 0.6rem;"></i> <?php echo htmlspecialchars(t('common.all_systems_operational')); ?>
        </div>
    </div>
</div>
