<div class="notifications-new-email-template">
    <?php echo form_open('admin/notifications', array('class' => 'notifications-create-email-template', 'data-csrf' => $this->security->get_csrf_token_name())) ?>
    <div class="row">
        <div class="col-lg-7 col-lg-offset-1">
            <?php

            // Get languages
            $languages = glob(APPPATH . 'language' . '/*', GLOB_ONLYDIR);

            // Verify if there are more than a language
            if (count($languages) > 1) {

                // Display the list start
                echo '<ul class="nav nav-tabs nav-justified">';

                // List all languages
                foreach ($languages as $lang) {

                    // Set directory
                    $only_dir = str_replace(APPPATH . 'language' . '/', '', $lang);                    

                    // Active variable
                    $active = '';

                    // Verify if is the configured language
                    if ($this->config->item('language') === $only_dir) {
                        $active = ' class="active"';
                    }

                    // Display the link
                    echo '<li' . $active . '>'
                            . '<a data-toggle="tab" href="#' . $only_dir . '">'
                                . ucfirst($only_dir)
                            . '</a>'
                        . '</li>';

                }

                // Display the list end
                echo '</ul>';

            }

            ?>
            <div class="tab-content tab-all-editors">
                <?php

                // List languages
                foreach ($languages as $lang) {

                    // Get dir
                    $only_dir = str_replace(APPPATH . 'language' . '/', '', $lang);

                    // Active variable
                    $active = '';

                    // Verify if is the configured language
                    if ($this->config->item('language') === $only_dir) {
                        $active = ' in active';
                    }                    

                    // Display the tab
                    echo '<div id="' . $only_dir . '" class="tab-pane fade' . $active . '">'
                        . '<div class="row">'
                            . '<div class="col-lg-12">'
                                . '<input type="text" class="form-control input-form article-title" placeholder="' . $this->lang->line('enter_article_title') . '">'
                            . '</div>'
                        . '</div>'
                        . '<div class="row">'
                            . '<div class="col-lg-12">'
                                . '<div class="summernote-body body-' . $only_dir . '" data-dir="body-' . $only_dir . '"></div>'
                                . '<textarea class="article-body content-body-' . $only_dir . ' hidden"></textarea>'
                            . '</div>'
                        . '</div>'
                        . '<div class="row">'
                            . '<div class="col-lg-12">'
                                . '<div class="panel panel-default notifications-emails-template-placeholders-area">'
                                    . '<div class="panel-heading">'
                                        . $this->lang->line('notifications_placeholders')
                                    . '</div>'
                                    . '<div class="panel-body">'
                                        . '<ul class="notifications-emails-template-placeholders">'
                                            . '<li>'
                                                . '<p>'
                                                    . $this->lang->line('notifications_no_placeholders_found')
                                                . '</p>'
                                            . '</li>'
                                        . '</ul>'
                                    . '</div>'
                                . '</div>'
                            . '</div>'
                        . '</div>'
                    . '</div>';

                }
                ?>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-success notifications-save-email-template">
                                <?php echo $this->lang->line('notifications_save'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default notifications-email-templates">
                        <div class="panel-heading">
                            <?php echo $this->lang->line('notifications_email_template'); ?>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <select class="form-control notifications-email-template-select">
                                    <option disabled selected>
                                        <?php echo $this->lang->line('notifications_select_template'); ?>
                                    </option>
                                    <?php
                                    
                                    // Get email's templates
                                    $email_templates = the_admin_notifications_email_templates();

                                    // Get all templates
                                    $get_templates = $this->base_model->get_data_where('notifications_templates', '*');

                                    // Set all templates
                                    $all_templates = !empty($get_templates)?array_column($get_templates, 'template_slug'):array();

                                    // Verify if email's templates exists
                                    if ( $email_templates ) {

                                        // List email's templates
                                        foreach ( $email_templates as $email_template ) {

                                            // Get key
                                            $key = key($email_template);

                                            // Verify if array already exists
                                            if ( in_array($key, $all_templates ) ) {
                                                continue;
                                            }

                                            // Display option
                                            echo '<option value="' . $key . '">'
                                                    . $email_template[$key]['template_name']
                                                . '</option>';

                                        }

                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close() ?>
</div>

<script language="javascript">
    
    // Words list
    var words = {
        "no_placeholders_found": "<?php echo $this->lang->line('notifications_no_placeholders_found'); ?>"
    };
</script>