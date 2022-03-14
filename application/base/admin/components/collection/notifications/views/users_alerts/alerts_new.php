<div class="notifications-new-users-alert">
    <?php echo form_open('admin/notifications', array('class' => 'notifications-create-users-alert', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
        <div class="row">
            <div class="col-lg-7 col-lg-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="material-icons-outlined">
                            notification_add
                        </i>
                        <?php echo $this->lang->line('notifications_new_alert'); ?>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="notifications-alert-name">
                                        <?php echo $this->lang->line('notifications_alert_name'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="text" class="form-control notifications-text-input" id="notifications-alert-name" placeholder="<?php echo $this->lang->line('notifications_enter_name'); ?>" />
                                    <small class="form-text text-muted">
                                        <?php echo $this->lang->line('notifications_alert_name_description'); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="notifications-alert-type">
                                        <?php echo $this->lang->line('notifications_alert_type'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="btn-group notifications-buttons-group notifications-select-alert-type" role="group" aria-label="Alerts Type">
                                        <button type="button" class="btn btn-secondary notifications-button-selected" data-tab="#notifications-users-alert-type-news" data-type="0">
                                            <?php echo $this->lang->line('notifications_news'); ?>
                                        </button>
                                        <button type="button" class="btn btn-secondary" data-tab="#notifications-users-alert-type-promo" data-type="1">
                                            <?php echo $this->lang->line('notifications_promo'); ?>
                                        </button>
                                        <button type="button" class="btn btn-secondary" data-tab="#notifications-users-alert-type-fixed" data-type="2">
                                            <?php echo $this->lang->line('notifications_fixed'); ?>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">
                                        <a href="#notifications-users-alert-type" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="notifications-users-alert-type">
                                            <?php echo $this->lang->line('notifications_more_information'); ?>
                                        </a>
                                    </small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="collapse notifications-show-more-info" id="notifications-users-alert-type">
                                        <p>
                                            <?php echo $this->lang->line('notifications_alert_type_instructions'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <hr>
                        <div class="tab-content" id="notifications-users-alert-types-content">
                            <div class="tab-pane fade active in" id="notifications-users-alert-type-news" role="tabpanel" aria-labelledby="notifications-users-alert-type-news-tab">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="notifications-alert-banner">
                                                <?php echo $this->lang->line('notifications_alert_banner'); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <ul class="nav nav-tabs nav-justified">
                                                <?php

                                                // Get all languages
                                                $languages = glob(APPPATH . 'language' . '/*', GLOB_ONLYDIR);

                                                // List all languages
                                                foreach ($languages as $language) {

                                                    // Get language dir name
                                                    $only_dir = str_replace(APPPATH . 'language' . '/', '', $language);

                                                    // Active variable
                                                    $active = '';

                                                    // Verify if is the configured language
                                                    if ($this->config->item('language') === $only_dir) {
                                                        $active = ' class="active"';
                                                    }

                                                    echo '<li' . $active . '>'
                                                        . '<a data-toggle="tab" href="#notifications-type-news-alert-banner-' . $only_dir . '">'
                                                            . ucfirst($only_dir)
                                                        . '</a>'
                                                    . '</li>';
                                                }

                                                ?>
                                            </ul>
                                            <div class="tab-content tab-all-banner-editors">
                                                <?php

                                                // List all languages
                                                foreach ($languages as $language) {

                                                    // Get language dir name
                                                    $only_dir = str_replace(APPPATH . 'language' . '/', '', $language);

                                                    // Active variable
                                                    $active = '';

                                                    // Verify if is the configured language
                                                    if ($this->config->item('language') === $only_dir) {
                                                        $active = ' in active';
                                                    }

                                                    ?>
                                                    <div id="notifications-type-news-alert-banner-<?php echo $only_dir; ?>" class="tab-pane fade<?php echo $active; ?>">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="summernote-editor notifications-type-news-alert-banner-<?php echo $only_dir; ?>" data-dir="<?php echo $only_dir; ?>"></div>
                                                                <textarea class="summernote-editor-textarea notifications-alert-banner-textarea hidden"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                }

                                                ?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <small class="form-text text-muted">
                                                <?php echo $this->lang->line('notifications_alert_banner_description'); ?>
                                            </small>
                                        </div>
                                    </div>                          
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-10 col-xs-6">
                                            <label for="menu-item-text-input">
                                                <?php echo $this->lang->line('notifications_enable_alert_banner'); ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-2 col-xs-6">
                                            <div class="checkbox-option pull-right">
                                                <input id="notifications-alert-enable-banner" name="notifications-alert-enable-banner" class="notifications-option-checkbox" type="checkbox" />
                                                <label for="notifications-alert-enable-banner"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="notifications-alert-page">
                                                <?php echo $this->lang->line('notifications_alert_page'); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <ul class="nav nav-tabs nav-justified">
                                                <?php

                                                // Get all languages
                                                $languages = glob(APPPATH . 'language' . '/*', GLOB_ONLYDIR);

                                                // List all languages
                                                foreach ($languages as $language) {

                                                    // Get language dir name
                                                    $only_dir = str_replace(APPPATH . 'language' . '/', '', $language);

                                                    // Active variable
                                                    $active = '';

                                                    // Verify if is the configured language
                                                    if ($this->config->item('language') === $only_dir) {
                                                        $active = ' class="active"';
                                                    }

                                                    echo '<li' . $active . '>'
                                                        . '<a data-toggle="tab" href="#notifications-news-alert-page-' . $only_dir . '">'
                                                            . ucfirst($only_dir)
                                                        . '</a>'
                                                    . '</li>';
                                                }

                                                ?>
                                            </ul>
                                            <div class="tab-content tab-all-page-editors">
                                                <?php

                                                // List all languages
                                                foreach ($languages as $language) {

                                                    // Get language dir name
                                                    $only_dir = str_replace(APPPATH . 'language' . '/', '', $language);

                                                    // Active variable
                                                    $active = '';

                                                    // Verify if is the configured language
                                                    if ($this->config->item('language') === $only_dir) {
                                                        $active = ' in active';
                                                    }

                                                    ?>
                                                    <div id="notifications-news-alert-page-<?php echo $only_dir; ?>" class="tab-pane fade<?php echo $active; ?>">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <input type="text" class="form-control notifications-text-input notifications-alert-page-title" id="notifications-news-alert-page-title-<?php echo $only_dir; ?>" placeholder="<?php echo $this->lang->line('notifications_enter_page_title'); ?>" />
                                                                <small class="form-text text-muted">
                                                                    <?php echo $this->lang->line('notifications_alert_page_title_description'); ?>
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <br>                                             
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="summernote-editor notifications-type-news-alert-page-<?php echo $only_dir; ?>" data-dir="<?php echo $only_dir; ?>"></div>
                                                                <textarea class="summernote-editor-textarea notifications-alert-page-textarea hidden"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                }

                                                ?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <small class="form-text text-muted">
                                                <?php echo $this->lang->line('notifications_alert_page_description'); ?>
                                            </small>
                                        </div>
                                    </div>                          
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-10 col-xs-6">
                                            <label for="menu-item-text-input">
                                                <?php echo $this->lang->line('notifications_enable_alert_page'); ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-2 col-xs-6">
                                            <div class="checkbox-option pull-right">
                                                <input id="notifications-news-alert-enable-page" name="notifications-news-alert-enable-page" class="notifications-option-checkbox notifications-alert-page-enabled" type="checkbox" />
                                                <label for="notifications-news-alert-enable-page"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="notifications-users-alert-type-promo" role="tabpanel" aria-labelledby="notifications-users-alert-type-promo-tab">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="notifications-alert-banner">
                                                <?php echo $this->lang->line('notifications_alert_banner'); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <ul class="nav nav-tabs nav-justified">
                                                <?php

                                                // Get all languages
                                                $languages = glob(APPPATH . 'language' . '/*', GLOB_ONLYDIR);

                                                // List all languages
                                                foreach ($languages as $language) {

                                                    // Get language dir name
                                                    $only_dir = str_replace(APPPATH . 'language' . '/', '', $language);

                                                    // Active variable
                                                    $active = '';

                                                    // Verify if is the configured language
                                                    if ($this->config->item('language') === $only_dir) {
                                                        $active = ' class="active"';
                                                    }

                                                    echo '<li' . $active . '>'
                                                        . '<a data-toggle="tab" href="#notifications-type-promo-alert-banner' . $only_dir . '">'
                                                            . ucfirst($only_dir)
                                                        . '</a>'
                                                    . '</li>';
                                                }

                                                ?>
                                            </ul>
                                            <div class="tab-content tab-all-banner-editors">
                                                <?php

                                                // List all languages
                                                foreach ($languages as $language) {

                                                    // Get language dir name
                                                    $only_dir = str_replace(APPPATH . 'language' . '/', '', $language);

                                                    // Active variable
                                                    $active = '';

                                                    // Verify if is the configured language
                                                    if ($this->config->item('language') === $only_dir) {
                                                        $active = ' in active';
                                                    }

                                                    ?>
                                                    <div id="notifications-type-promo-alert-banner<?php echo $only_dir; ?>" class="tab-pane fade<?php echo $active; ?>">   
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="summernote-editor notifications-type-promo-alert-banner-<?php echo $only_dir; ?>" data-dir="<?php echo $only_dir; ?>"></div>
                                                                <textarea class="summernote-editor-textarea notifications-alert-banner-textarea hidden"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                }

                                                ?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <small class="form-text text-muted">
                                                <?php echo $this->lang->line('notifications_alert_banner_description'); ?>
                                            </small>
                                        </div>
                                    </div>                          
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="notifications-alert-page">
                                                <?php echo $this->lang->line('notifications_alert_page'); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <ul class="nav nav-tabs nav-justified">
                                                <?php

                                                // Get all languages
                                                $languages = glob(APPPATH . 'language' . '/*', GLOB_ONLYDIR);

                                                // List all languages
                                                foreach ($languages as $language) {

                                                    // Get language dir name
                                                    $only_dir = str_replace(APPPATH . 'language' . '/', '', $language);

                                                    // Active variable
                                                    $active = '';

                                                    // Verify if is the configured language
                                                    if ($this->config->item('language') === $only_dir) {
                                                        $active = ' class="active"';
                                                    }

                                                    echo '<li' . $active . '>'
                                                        . '<a data-toggle="tab" href="#notifications-type-promo-alert-page-' . $only_dir . '">'
                                                            . ucfirst($only_dir)
                                                        . '</a>'
                                                    . '</li>';
                                                }

                                                ?>
                                            </ul>
                                            <div class="tab-content tab-all-page-editors">
                                                <?php

                                                // List all languages
                                                foreach ($languages as $language) {

                                                    // Get language dir name
                                                    $only_dir = str_replace(APPPATH . 'language' . '/', '', $language);

                                                    // Active variable
                                                    $active = '';

                                                    // Verify if is the configured language
                                                    if ($this->config->item('language') === $only_dir) {
                                                        $active = ' in active';
                                                    }

                                                    ?>
                                                    <div id="notifications-type-promo-alert-page-<?php echo $only_dir; ?>" class="tab-pane fade<?php echo $active; ?>">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <input type="text" class="form-control notifications-text-input notifications-alert-page-title" id="notifications-promo-alert-page-title-<?php echo $only_dir; ?>" placeholder="<?php echo $this->lang->line('notifications_enter_page_title'); ?>" />
                                                                <small class="form-text text-muted">
                                                                    <?php echo $this->lang->line('notifications_alert_page_title_description'); ?>
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <br>   
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="summernote-editor notifications-type-promo-alert-page-<?php echo $only_dir; ?>" data-dir="<?php echo $only_dir; ?>"></div>
                                                                <textarea class="summernote-editor-textarea hidden"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                }

                                                ?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <small class="form-text text-muted">
                                                <?php echo $this->lang->line('notifications_alert_page_description'); ?>
                                            </small>
                                        </div>
                                    </div>                          
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-10 col-xs-6">
                                            <label for="menu-item-text-input">
                                                <?php echo $this->lang->line('notifications_enable_alert_page'); ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-2 col-xs-6">
                                            <div class="checkbox-option pull-right">
                                                <input id="notifications-promo-alert-enable-page" name="notifications-promo-alert-enable-page" class="notifications-option-checkbox notifications-alert-page-enabled" type="checkbox" />
                                                <label for="notifications-promo-alert-enable-page"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="notifications-users-alert-type-fixed" role="tabpanel" aria-labelledby="notifications-users-alert-type-fixed-tab">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="notifications-alert-banner">
                                                <?php echo $this->lang->line('notifications_alert_banner'); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <ul class="nav nav-tabs nav-justified">
                                                <?php

                                                // Get all languages
                                                $languages = glob(APPPATH . 'language' . '/*', GLOB_ONLYDIR);

                                                // List all languages
                                                foreach ($languages as $language) {

                                                    // Get language dir name
                                                    $only_dir = str_replace(APPPATH . 'language' . '/', '', $language);

                                                    // Active variable
                                                    $active = '';

                                                    // Verify if is the configured language
                                                    if ($this->config->item('language') === $only_dir) {
                                                        $active = ' class="active"';
                                                    }

                                                    echo '<li' . $active . '>'
                                                        . '<a data-toggle="tab" href="#notifications-type-fixed-alert-banner-' . $only_dir . '">'
                                                            . ucfirst($only_dir)
                                                        . '</a>'
                                                    . '</li>';
                                                }

                                                ?>
                                            </ul>
                                            <div class="tab-content tab-all-banner-editors">
                                                <?php

                                                // List all languages
                                                foreach ($languages as $language) {

                                                    // Get language dir name
                                                    $only_dir = str_replace(APPPATH . 'language' . '/', '', $language);

                                                    // Active variable
                                                    $active = '';

                                                    // Verify if is the configured language
                                                    if ($this->config->item('language') === $only_dir) {
                                                        $active = ' in active';
                                                    }

                                                    ?>
                                                    <div id="notifications-type-fixed-alert-banner-<?php echo $only_dir; ?>" class="tab-pane fade<?php echo $active; ?>"> 
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="summernote-editor notifications-type-fixed-alert-banner-<?php echo $only_dir; ?>" data-dir="<?php echo $only_dir; ?>"></div>
                                                                <textarea class="summernote-editor-textarea notifications-alert-banner-textarea hidden"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                }

                                                ?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <small class="form-text text-muted">
                                                <?php echo $this->lang->line('notifications_alert_banner_description'); ?>
                                            </small>
                                        </div>
                                    </div>                          
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="notifications-alert-page">
                                                <?php echo $this->lang->line('notifications_alert_page'); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <ul class="nav nav-tabs nav-justified">
                                                <?php

                                                // Get all languages
                                                $languages = glob(APPPATH . 'language' . '/*', GLOB_ONLYDIR);

                                                // List all languages
                                                foreach ($languages as $language) {

                                                    // Get language dir name
                                                    $only_dir = str_replace(APPPATH . 'language' . '/', '', $language);

                                                    // Active variable
                                                    $active = '';

                                                    // Verify if is the configured language
                                                    if ($this->config->item('language') === $only_dir) {
                                                        $active = ' class="active"';
                                                    }

                                                    echo '<li' . $active . '>'
                                                        . '<a data-toggle="tab" href="#notifications-type-fixed-alert-page-' . $only_dir . '">'
                                                            . ucfirst($only_dir)
                                                        . '</a>'
                                                    . '</li>';
                                                }

                                                ?>
                                            </ul>
                                            <div class="tab-content tab-all-page-editors">
                                                <?php

                                                // List all languages
                                                foreach ($languages as $language) {

                                                    // Get language dir name
                                                    $only_dir = str_replace(APPPATH . 'language' . '/', '', $language);

                                                    // Active variable
                                                    $active = '';

                                                    // Verify if is the configured language
                                                    if ($this->config->item('language') === $only_dir) {
                                                        $active = ' in active';
                                                    }

                                                    ?>
                                                    <div id="notifications-type-fixed-alert-page-<?php echo $only_dir; ?>" class="tab-pane fade<?php echo $active; ?>">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <input type="text" class="form-control notifications-text-input notifications-alert-page-title" id="notifications-fixed-alert-page-title-<?php echo $only_dir; ?>" placeholder="<?php echo $this->lang->line('notifications_enter_page_title'); ?>" />
                                                                <small class="form-text text-muted">
                                                                    <?php echo $this->lang->line('notifications_alert_page_title_description'); ?>
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <br>   
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="summernote-editor notifications-type-fixed-alert-page-<?php echo $only_dir; ?>" data-dir="<?php echo $only_dir; ?>"></div>
                                                                <textarea class="summernote-editor-textarea hidden"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                }

                                                ?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <small class="form-text text-muted">
                                                <?php echo $this->lang->line('notifications_alert_page_description'); ?>
                                            </small>
                                        </div>
                                    </div>                          
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-10 col-xs-6">
                                            <label for="menu-item-text-input">
                                                <?php echo $this->lang->line('notifications_enable_alert_page'); ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-2 col-xs-6">
                                            <div class="checkbox-option pull-right">
                                                <input id="notifications-fixed-alert-enable-page" name="notifications-fixed-alert-enable-page" class="notifications-option-checkbox notifications-alert-page-enabled" type="checkbox" />
                                                <label for="notifications-fixed-alert-enable-page"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-success notifications-save-users-alert">
                                    <?php echo $this->lang->line('notifications_save'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default notifications-users-alert-filters">
                            <div class="panel-heading">
                                <?php echo $this->lang->line('notifications_filters'); ?>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="panel panel-default notifications-users-alert-selected-plans">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-10">
                                                    <?php echo $this->lang->line('notifications_selected_plans'); ?>
                                                </div>
                                                <div class="col-xs-2 text-right">
                                                    <button type="button" class="btn btn-light" data-toggle="modal" data-target="#notifications-users-alert-filters-plans-filter">
                                                        <i class="icon-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="notifications-selected-plans-list">
                                                <li class="notifications-no-results-found">
                                                    <?php echo $this->lang->line('notifications_no_plans_were_selected'); ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="panel panel-default notifications-users-alert-selected-languages">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-10">
                                                    <?php echo $this->lang->line('notifications_selected_languages'); ?>
                                                </div>
                                                <div class="col-xs-2 text-right">
                                                    <button type="button" class="btn btn-light" data-toggle="modal" data-target="#notifications-users-alert-filters-languages-filter">
                                                        <i class="icon-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="notifications-selected-languages-list">
                                                <li class="notifications-no-results-found">
                                                    <?php echo $this->lang->line('notifications_no_languages_were_selected'); ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php echo form_close() ?>
</div>