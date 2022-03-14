<div class="settings-list theme-box">
    <div class="tab-content">
        <div class="tab-pane container fade active show" id="posts-settings">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="icon-layers"></i>
                    <?php echo $this->lang->line('posts'); ?>
                </div>
                <div class="panel-body">
                    <ul class="settings-list-options">
                        <?php

                        // Display the characters counter option
                        get_option_template(array(
                            'type' => 'checkbox_input',
                            'slug' => 'settings_character_count',
                            'name' => $this->lang->line('character_count'),
                            'description' => $this->lang->line('character_count_description')
                        ));

                        // Display the email errors option
                        get_option_template(array(
                            'type' => 'checkbox_input',
                            'slug' => 'error_notifications',
                            'name' => $this->lang->line('email_errors'),
                            'description' => $this->lang->line('email_errors_if_enabled')
                        ));

                        // Display the groups with accounts option
                        get_option_template(array(
                            'type' => 'checkbox_input',
                            'slug' => 'settings_display_groups',
                            'name' => $this->lang->line('display_groups'),
                            'description' => $this->lang->line('display_groups_description')
                        ));

                        // Display the url input option
                        get_option_template(array(
                            'type' => 'checkbox_input',
                            'slug' => 'settings_posts_url_import',
                            'name' => $this->lang->line('posts_enable_url_field'),
                            'description' => $this->lang->line('posts_enable_url_field_description')
                        ));

                        // Display the hashtags option
                        get_option_template(array(
                            'type' => 'checkbox_input',
                            'slug' => 'settings_hashtags_suggestion',
                            'name' => $this->lang->line('posts_hashtags_option'),
                            'description' => $this->lang->line('posts_hashtags_option_description')
                        ));

                        // Display the parse images from rss option
                        get_option_template(array(
                            'type' => 'checkbox_input',
                            'slug' => 'settings_posts_parse_rss_images',
                            'name' => $this->lang->line('posts_parse_rss_images'),
                            'description' => $this->lang->line('posts_parse_rss_images_description')
                        ));

                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>