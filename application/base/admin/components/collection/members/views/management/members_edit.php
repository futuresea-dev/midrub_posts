<div class="row">
    <div class="col-lg-12">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="row">
                        <div class="col-xs-12">
                            <h3>
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                                </svg>
                                <?php echo $this->lang->line('members_member_page'); ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <?php echo form_open('admin/members', array('class' => 'members-save-form', 'data-member' => $this->input->get('member', true), 'data-csrf' => $this->security->get_csrf_token_name(), 'autocomplete' => 'off')) ?>
            <?php get_admin_members_fields(); ?>
        <?php echo form_close() ?>
    </div>
    <div class="col-lg-6">
        <?php

        // Get member's tabs
        $member_tabs = the_admin_members_member_tabs();

        // Verify if at least a tab exists
        if ( $member_tabs ) {
        ?>
        <div class="panel panel-member-information panel-default">
            <div class="panel-heading">
                <ul class="nav nav-tabs">
                    <?php 
                    
                    // List tabs
                    foreach ( $member_tabs AS $tab_slug => $args ) {

                        // Default active
                        $active = '';

                        // Default expanded
                        $expanded = 'false';

                        // Verify if is the first tab
                        if ( $tab_slug === array_key_first($member_tabs) ) {

                            // Set active
                            $active = ' class="active"';

                            // Set expanded
                            $expanded = 'true';

                        }

                        ?>
                        <li <?php echo $active; ?>>
                            <a data-toggle="tab" href="#members-member-tab-<?php echo $tab_slug; ?>" aria-expanded="<?php echo $expanded; ?>">
                                <?php echo $args['tab_icon']; ?>
                                <?php echo $args['tab_name']; ?>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <div class="panel-body affiliates-reports">
                <div class="tab-content">
                    <?php 
                    
                    // List tabs
                    foreach ( $member_tabs AS $tab_slug => $args ) {

                        // Default active
                        $active = '';

                        // Verify if is the first tab
                        if ( $tab_slug === array_key_first($member_tabs) ) {

                            // Set active
                            $active = ' in';

                        }

                        ?>
                            <div id="members-member-tab-<?php echo $tab_slug; ?>" class="tab-pane fade active<?php echo $active; ?>">
                                <?php echo $args['tab_content']; ?>
                            </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<div class="settings-save-changes">
    <div class="col-xs-6">
        <p>
            <?php echo $this->lang->line('frontend_settings_you_have_unsaved_changes'); ?>
        </p>
    </div>
     <div class="col-xs-6 text-right">
        <button type="button" class="btn btn-default">
            <i class="far fa-save"></i>
            <?php echo $this->lang->line('frontend_settings_save_changes'); ?>
        </button>
    </div>   
</div>