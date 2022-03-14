<section class="section plugins-page" data-csrf="<?php echo $this->security->get_csrf_token_name(); ?>" data-csrf-value="<?php echo $this->security->get_csrf_hash(); ?>">
    <div class="container-fluid">
        <div class="left-side">
            <?php get_the_file(MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'views/menu.php'); ?>
        </div>
        <div class="right-side">
            <?php get_the_admin_plugins_page_content(the_global_variable('component_display')); ?>
        </div>
    </div>
</section>