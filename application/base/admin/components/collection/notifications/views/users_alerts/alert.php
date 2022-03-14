<div class="notifications-users-alert" data-alert="<?php echo $this->input->get('alert', true); ?>">
    <div class="row">
        <div class="col-lg-7 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="material-icons-outlined">
                        notifications_none
                    </i>
                    <?php echo $this->lang->line('notifications_alert_information'); ?>
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
                                <input type="text" value="<?php echo the_users_alert_name(); ?>" placeholder="<?php echo $this->lang->line('notifications_enter_name'); ?>" class="form-control notifications-text-input" id="notifications-alert-name" />
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
                                    <?php if (the_users_alert_type() === '0') { ?>
                                    <button type="button" class="btn btn-secondary notifications-button-selected" data-tab="#notifications-users-alert-type-news" data-type="0">
                                        <?php echo $this->lang->line('notifications_news'); ?>
                                    </button>
                                    <?php } ?>
                                    <?php if (the_users_alert_type() === '1') { ?>
                                    <button type="button" class="btn btn-secondary notifications-button-selected" data-tab="#notifications-users-alert-type-promo" data-type="1">
                                        <?php echo $this->lang->line('notifications_promo'); ?>
                                    </button>
                                    <?php } ?>
                                    <?php if (the_users_alert_type() === '2') { ?>
                                    <button type="button" class="btn btn-secondary notifications-button-selected" data-tab="#notifications-users-alert-type-fixed" data-type="2">
                                        <?php echo $this->lang->line('notifications_fixed'); ?>
                                    </button>
                                    <?php } ?>
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
                        <div class="tab-pane fade<?php echo (the_users_alert_type() === '0')?' active in':''; ?>" id="notifications-users-alert-type-news" role="tabpanel" aria-labelledby="notifications-users-alert-type-news-tab">
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
                                                            <textarea class="summernote-editor-textarea notifications-alert-banner-textarea hidden"><?php echo (the_users_alert_type() === '0')?the_users_alerts_field('banner_content', '', $only_dir):''; ?></textarea>
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
                                            <input type="checkbox" name="notifications-alert-enable-banner" id="notifications-alert-enable-banner" class="notifications-option-checkbox"<?php echo the_users_alerts_field('banner_enabled')?' checked':''; ?> />
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
                                                            <input type="text" value="<?php echo (the_users_alert_type() === '0')?the_users_alerts_field('page_title', '', $only_dir):''; ?>" placeholder="<?php echo $this->lang->line('notifications_enter_page_title'); ?>" class="form-control notifications-text-input notifications-alert-page-title" id="notifications-news-alert-page-title-<?php echo $only_dir; ?>" />
                                                            <small class="form-text text-muted">
                                                                <?php echo $this->lang->line('notifications_alert_page_title_description'); ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <br>                                             
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="summernote-editor notifications-type-news-alert-page-<?php echo $only_dir; ?>" data-dir="<?php echo $only_dir; ?>"></div>
                                                            <textarea class="summernote-editor-textarea notifications-alert-page-textarea hidden"><?php echo (the_users_alert_type() === '0')?the_users_alerts_field('page_content', '', $only_dir):''; ?></textarea>
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
                                            <input type="checkbox" name="notifications-news-alert-enable-page" id="notifications-news-alert-enable-page" class="notifications-option-checkbox notifications-alert-page-enabled"<?php echo the_users_alerts_field('page_enabled')?' checked':''; ?> />
                                            <label for="notifications-news-alert-enable-page"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade<?php echo (the_users_alert_type() === '1')?' active in':''; ?>" id="notifications-users-alert-type-promo" role="tabpanel" aria-labelledby="notifications-users-alert-type-promo-tab">
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
                                                            <textarea class="summernote-editor-textarea notifications-alert-banner-textarea hidden"><?php echo (the_users_alert_type() === '1')?the_users_alerts_field('banner_content', '', $only_dir):''; ?></textarea>
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
                                                            <input type="text" value="<?php echo (the_users_alert_type() === '1')?the_users_alerts_field('page_title', '', $only_dir):''; ?>" placeholder="<?php echo $this->lang->line('notifications_enter_page_title'); ?>" class="form-control notifications-text-input notifications-alert-page-title" id="notifications-promo-alert-page-title-<?php echo $only_dir; ?>" />
                                                            <small class="form-text text-muted">
                                                                <?php echo $this->lang->line('notifications_alert_page_title_description'); ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <br>   
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="summernote-editor notifications-type-promo-alert-page-<?php echo $only_dir; ?>" data-dir="<?php echo $only_dir; ?>"></div>
                                                            <textarea class="summernote-editor-textarea hidden"><?php echo (the_users_alert_type() === '1')?the_users_alerts_field('page_content', '', $only_dir):''; ?></textarea>
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
                                            <input type="checkbox" name="notifications-promo-alert-enable-page" id="notifications-promo-alert-enable-page" class="notifications-option-checkbox"<?php echo the_users_alerts_field('page_enabled')?' checked':''; ?> />
                                            <label for="notifications-promo-alert-enable-page"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade<?php echo (the_users_alert_type() === '2')?' active in':''; ?>" id="notifications-users-alert-type-fixed" role="tabpanel" aria-labelledby="notifications-users-alert-type-fixed-tab">
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
                                                            <textarea class="summernote-editor-textarea notifications-alert-banner-textarea hidden"><?php echo (the_users_alert_type() === '2')?the_users_alerts_field('banner_content', '', $only_dir):''; ?></textarea>
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
                                                            <input type="text" value="<?php echo (the_users_alert_type() === '2')?the_users_alerts_field('page_title', '', $only_dir):''; ?>" placeholder="<?php echo $this->lang->line('notifications_enter_page_title'); ?>" class="form-control notifications-text-input notifications-alert-page-title" id="notifications-fixed-alert-page-title-<?php echo $only_dir; ?>" />
                                                            <small class="form-text text-muted">
                                                                <?php echo $this->lang->line('notifications_alert_page_title_description'); ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <br>   
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="summernote-editor notifications-type-fixed-alert-page-<?php echo $only_dir; ?>" data-dir="<?php echo $only_dir; ?>"></div>
                                                            <textarea class="summernote-editor-textarea hidden"><?php echo (the_users_alert_type() === '2')?the_users_alerts_field('page_content', '', $only_dir):''; ?></textarea>
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
                                            <input type="checkbox" name="notifications-fixed-alert-enable-page" id="notifications-fixed-alert-enable-page" class="notifications-option-checkbox"<?php echo the_users_alerts_field('page_enabled')?' checked':''; ?> />
                                            <label for="notifications-fixed-alert-enable-page"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                            
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="material-icons-outlined">
                        people
                    </i>
                    <?php echo $this->lang->line('notifications_users'); ?>
                </div>
                <div class="panel-body">
                    <ul class="notifications-list-users"></ul>
                </div>
                <div class="panel-footer">
                    <div class="pagination-area">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-6">
                                <ul class="pagination" data-type="alert-users">
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
        <div class="col-lg-3">
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
                                            <div class="col-xs-12">
                                                <?php echo $this->lang->line('notifications_selected_plans'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <ul class="notifications-selected-plans-list">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="panel panel-default notifications-users-alert-selected-languages">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <?php echo $this->lang->line('notifications_selected_languages'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <ul class="notifications-selected-languages-list">
                                        <?php

                                        // Get languages
                                        $languages = the_users_alerts_filters('languages');

                                        // Verify if languages exists
                                        if ( $languages ) {

                                            // List all languages
                                            foreach (unserialize($languages) as $language) {

                                                // Display the language
                                                echo '<li data-language="' . $language . '">'
                                                    . ucfirst($language)
                                                . '</li>';

                                            }

                                        } else {

                                            // Display no languages message
                                            echo '<li class="notifications-no-results-found">'
                                                . $this->lang->line('notifications_no_languages_were_selected')
                                            . '</li>';

                                        }

                                        ?>
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
</div>