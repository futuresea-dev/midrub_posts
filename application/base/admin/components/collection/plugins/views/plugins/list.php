<div class="row">
    <div class="col-lg-12">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <a href="<?php echo site_url('admin/plugins?p=plugins&directory=1'); ?>" class="btn-option new-plugin">
                                <i class="material-icons">
                                    post_add
                                </i>
                                <?php echo $this->lang->line('plugins_new_plugin'); ?>
                            </a>
                        </div>
                        <div class="col-lg-6">
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <ul class="list-plugins">
            <?php
            
            // Verify if plugins exists
            if (the_plugins_list()) {

                foreach (the_plugins_list() as $plugin) {

                    // Set plugin's slug
                    $plugin_slug = !empty($plugin['plugin_slug'])?$plugin['plugin_slug']:'';

                    ?>
                    <li class="plugin-single<?php echo the_option('plugin_' . trim($plugin_slug) . '_enable')?' plugins-activated-plugin':''; ?>" data-slug="<?php echo $plugin_slug; ?>">
                        <div class="row">
                            <div class="col-lg-10 col-xs-8">
                                <div class="plugin-preview">
                                    <img src="<?php echo !empty($plugin['plugin_cover'])?$plugin['plugin_cover']:''; ?>" alt="<?php echo !empty($plugin['plugin_name'])?$plugin['plugin_name']:''; ?>" onerror="this.src='<?php echo base_url('assets/img/no-image.png'); ?>';">
                                </div>
                                <div class="plugin-description">
                                    <h2>
                                        <?php echo !empty($plugin['plugin_name'])?$plugin['plugin_name']:''; ?>
                                    </h2>
                                    <p>
                                        <?php echo !empty($plugin['plugin_description'])?$plugin['plugin_description']:''; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-1 col-xs-2">
                                <span class="label label-default">
                                    <?php echo !empty($plugin['version'])?'v' . $plugin['version']:''; ?>
                                </span>
                            </div>
                            <div class="col-lg-1 col-xs-2 text-right">
                                <div class="btn-group">
                                    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="material-icons-outlined">
                                            more_vert
                                        </span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <?php
                                            if ( the_option('plugin_' . $plugin_slug . '_enable') ) {
                                                ?>
                                                <a href="#" class="plugins-deactivate-plugin">
                                                    <i class="material-icons-outlined">
                                                        power_settings_new
                                                    </i>
                                                    <?php echo $this->lang->line('plugins_deactivate'); ?>
                                                </a>
                                                <?php
                                            } else {
                                                ?>
                                                <a href="#" class="plugins-activate-plugin">
                                                    <i class="material-icons-outlined">
                                                        task_alt
                                                    </i>
                                                    <?php echo $this->lang->line('plugins_activate'); ?>
                                                </a>
                                                <?php
                                            }
                                            ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php

            }
            
        } else {

            ?>
            <div class="row">
                <div class="col-lg-12">
                    <nav class="navbar navbar-default">
                        <div class="container-fluid">
                            <div class="navbar-header">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p>
                                            <?php echo $this->lang->line('plugins_no_data_found_to_show'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
            <?php

        }
        ?>
        </ul>
    </div>
</div>