<section class="section notifications-page" data-csrf="<?php echo $this->security->get_csrf_token_name(); ?>" data-csrf-value="<?php echo $this->security->get_csrf_hash(); ?>">
    <div class="container-fluid">
        <div class="left-side">
            <?php md_include_component_file(MIDRUB_BASE_ADMIN_COMPONENTS_NOTIFICATIONS . 'views/menu.php'); ?>
        </div>
        <div class="right-side">
            <?php get_the_admin_notifications_page_content(md_the_component_variable('component_display')); ?>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade in" id="notifications-users-alert-filters-plans-filter" tabindex="-1" role="dialog" aria-labelledby="notifications-users-alert-filters-plans-filter-label">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <input type="text" class="form-control notifications-search-for-plans" placeholder="<?php echo $this->lang->line('notifications_search_for_plans'); ?>" />
            </div>
            <div class="modal-body">
                <ul class="notifications-users-alert-filters-plans-filter-list"></ul>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-6 text-left">
                        <ul class="pagination" data-type="plans">
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-6 text-right">
                        <p>
                            <span>
                                <span class="pagination-from"></span>
                                –
                                <span class="pagination-to"></span>
                            </span>
                            <?php echo $this->lang->line('notifications_of'); ?>
                            <span class="pagination-total"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade in" id="notifications-users-alert-filters-languages-filter" tabindex="-1" role="dialog" aria-labelledby="notifications-users-alert-filters-languages-filter-label">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3>
                    <?php echo $this->lang->line('notifications_all_languages'); ?>
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="notifications-users-alert-filters-languages-filter-list">
                    <?php

                    // Get all languages
                    $languages = glob(APPPATH . 'language' . '/*', GLOB_ONLYDIR);

                    // List all languages
                    foreach ( $languages as $language ) {

                        // Get language dir name
                        $only_dir = str_replace(APPPATH . 'language' . '/', '', $language);

                        // Display language
                        echo '<li>'
                            . '<div class="row">'
                                . '<div class="col-xs-12">'
                                    . '<div class="checkbox-option-select">'
                                        . '<input id="notifications-users-alert-filters-languages-filter-language-' . $only_dir . '" name="notifications-users-alert-filters-languages-filter-language-' . $only_dir . '" type="checkbox" data-language="' . $only_dir . '" />'
                                        . '<label for="notifications-users-alert-filters-languages-filter-language-' . $only_dir . '"></label>'
                                    . '</div>'
                                    . ucfirst($only_dir)
                            . '</div>'
                        . '</li>';
                    }

                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Loader -->
<div class="page-loading">
    <div class="loading-animation-area">
        <div class="loading-center-absolute">
            <div class="object object_four"></div>
            <div class="object object_three"></div>
            <div class="object object_two"></div>
            <div class="object object_one"></div>
        </div>
    </div>
</div>

<!-- Word list for JS !-->
<script language="javascript">
    var words = {
        no_languages_were_selected: '<?php echo $this->lang->line('notifications_no_languages_were_selected'); ?>',
        no_plans_were_selected: '<?php echo $this->lang->line('notifications_no_plans_were_selected'); ?>',
        all_users: '<?php echo $this->lang->line('notifications_all_users'); ?>'
    };
</script>