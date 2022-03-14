<div class="notifications-email-template">
    <?php echo form_open('admin/notifications', array('class' => 'notifications-update-email-template', 'data-template' => $this->input->get('template', true), 'data-csrf' => $this->security->get_csrf_token_name())) ?>
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

                    // Set template's title
                    $template_title = isset(the_global_variable('template')[$only_dir]['template_title'])?the_global_variable('template')[$only_dir]['template_title']:'';

                    // Set template's body
                    $template_body = isset(the_global_variable('template')[$only_dir]['template_body'])?the_global_variable('template')[$only_dir]['template_body']:'';

                    // Display the tab
                    echo '<div id="' . $only_dir . '" class="tab-pane fade' . $active . '">'
                        . '<div class="row">'
                            . '<div class="col-lg-12">'
                                . '<input type="text" class="form-control input-form article-title" placeholder="' . $this->lang->line('enter_article_title') . '" value="' . $template_title . '">'
                            . '</div>'
                        . '</div>'
                        . '<div class="row">'
                            . '<div class="col-lg-12">'
                                . '<div class="summernote-body body-' . $only_dir . '" data-dir="body-' . $only_dir . '"></div>'
                                . '<textarea class="article-body content-body-' . $only_dir . ' hidden">' . $template_body . '</textarea>'
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
                                            . the_admin_notifications_selected_template_placeholders(the_global_variable('template')['template_slug'])
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
                                <?php echo $this->lang->line('save'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo get_admin_notifications_selected_template(the_global_variable('template')['template_slug']); ?>
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