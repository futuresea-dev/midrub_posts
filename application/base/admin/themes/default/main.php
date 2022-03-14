<!DOCTYPE html>
<html lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php get_the_site_favicon(); ?>

    <!-- Title -->
    <title><?php get_the_title(); ?></title>

    <!-- Set website url -->
    <meta name="url" content="<?php echo site_url(); ?>">

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.css'); ?>" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.9.0/css/all.css">

    <!-- Simple Line Icons -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">

    <!-- Google Icons -->
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp">

    <!-- Date Time Picker -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/user/styles/css/bootstrap-datetimepicker.css'); ?>" media="all">

    <!-- Default Theme Styles -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/base/admin/themes/default/styles/css/styles.css'); ?>" media="all">    

    <!-- End temporary content -->
    <?php md_get_the_meta_description(); ?>
    <?php md_get_the_meta_keywords(); ?>

    <!-- Styles -->
    <?php md_get_the_css_urls(); ?>

</head>
<body>
    <header>
        <div class="container-fluid">
            <?php get_the_site_logo(); ?>
            <ul class="nav navbar-nav navbar-left">
                <li>
                    <a href="<?php echo site_url('admin/notifications?p=users_alerts&new=1') ?>" class="btn btn-labeled btn-primary">
                        <span class="btn-label">
                            <i class="material-icons-outlined">
                                notification_add
                            </i>
                        </span>
                        <?php echo $this->lang->line('ma8'); ?>
                    </a>
                </li>
                <li>
                    <button type="button" class="btn btn-labeled short-menu">
                        <i class="fa fa-bars fa-lg"></i>
                    </button>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="<?php echo site_url('admin/support') ?>">
                        <i class="material-icons-outlined">
                            quiz
                        </i>
                        <span class="label label-success"><?php echo get_the_tickets_notification(); ?></span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="<?php echo site_url('admin/update') ?>">
                        <i class="material-icons-outlined">
                            update
                        </i>
                        <span class="label label-primary"><?php echo get_update_count(); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo site_url('logout') ?>">
                        <i class="material-icons-outlined">
                            logout
                        </i>
                    </a>
                </li>
            </ul>
        </div>
    </header>
    <nav>
        <ul>
            <li<?php echo ( the_global_variable('component_slug') === 'dashboard' )?' class="active"':''; ?>>
                <a href="<?php echo site_url('admin/home') ?>">
                    <i class="material-icons-outlined">
                        dashboard
                    </i><br>
                    <?php echo $this->lang->line('ma1'); ?>
                </a>
            </li>
            <li<?php echo ( the_global_variable('component_slug') === 'notifications' )?' class="active"':''; ?>>
                <a href="<?php echo site_url('admin/notifications') ?>">
                    <i class="material-icons-outlined">
                        notifications
                    </i><br>
                    <?php echo $this->lang->line('ma2'); ?>
                </a>
            </li>        
            <li<?php echo ( the_global_variable('component_slug') === 'members' )?' class="active"':''; ?>>
                <a href="<?php echo site_url('admin/members') ?>">
                    <i class="material-icons-outlined">
                        people
                    </i><br>
                    <?php echo $this->lang->line('ma3'); ?>
                </a>
            </li>
            <li<?php echo ( the_global_variable('component_slug') === 'frontend' )?' class="active"':''; ?>>
                <a href="<?php echo site_url('admin/frontend') ?>">
                    <i class="material-icons-outlined">
                        web
                    </i><br>
                    <?php echo $this->lang->line('frontend'); ?>
                </a>
            </li>
            <li<?php echo ( the_global_variable('component_slug') === 'user' )?' class="active"':''; ?>>
                <a href="<?php echo site_url('admin/user') ?>">
                    <i class="material-icons-outlined">
                        person_outline
                    </i><br>
                    <?php echo $this->lang->line('ma104'); ?>
                </a>
            </li>
            <li<?php echo ( the_global_variable('component_slug') === 'admin' )?' class="active"':''; ?>>
                <a href="<?php echo site_url('admin/admin') ?>">
                    <i class="material-icons-outlined">
                        person_add_alt
                    </i><br>
                    <?php echo $this->lang->line('ma105'); ?>
                </a>
            </li> 
            <li<?php echo ( the_global_variable('component_slug') === 'plugins' )?' class="active"':''; ?>>
                <a href="<?php echo site_url('admin/plugins') ?>">
                    <i class="material-icons-outlined">dns</i><br>
                    <?php echo $this->lang->line('default_theme_plugins'); ?>
                </a>
            </li>                         
            <li<?php echo ( the_global_variable('component_slug') === 'settings' )?' class="active"':''; ?>>
                <a href="<?php echo site_url('admin/settings') ?>">
                    <i class="material-icons-outlined">
                        settings
                    </i><br>
                    <?php echo $this->lang->line('ma7'); ?>
                </a>
            </li>
        </ul>
    </nav>
    <?php get_admin_view(); ?>
    <script language="javascript">
        // Encode special characters
        function htmlEntities(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }
        
        // Translation characters
        var translation = {
            "mm103":htmlEntities("<?php echo $this->lang->line("mm103"); ?>"),
            "mm111":htmlEntities("<?php echo $this->lang->line("mm111"); ?>"),
            "mm112":htmlEntities("<?php echo $this->lang->line("mm112"); ?>"),
            "mm113":htmlEntities("<?php echo $this->lang->line("mm113"); ?>"),
            "mm116":htmlEntities("<?php echo $this->lang->line("mm116"); ?>"),
            "mm104":htmlEntities("<?php echo $this->lang->line("mm104"); ?>"),
            "mm105":htmlEntities("<?php echo $this->lang->line("mm105"); ?>"),
            "mm106":htmlEntities("<?php echo $this->lang->line("mm106"); ?>"),
            "mm107":htmlEntities("<?php echo $this->lang->line("mm107"); ?>"),
            "mm3":htmlEntities("<?php echo $this->lang->line("mm3"); ?>"),
            "mm142":htmlEntities("<?php echo $this->lang->line("mm142"); ?>"),
            "mm143":htmlEntities("<?php echo $this->lang->line("mm143"); ?>"),
            "mm144":htmlEntities("<?php echo $this->lang->line("mm144"); ?>"),
            "mm145":htmlEntities("<?php echo $this->lang->line("mm145"); ?>"),
            "mm187":htmlEntities("<?php echo $this->lang->line("mm187"); ?>"),
            "mm188":htmlEntities("<?php echo $this->lang->line("mm188"); ?>"),
            "ma18":htmlEntities("<?php echo $this->lang->line("ma18"); ?>"),
            "ma91":htmlEntities("<?php echo $this->lang->line("ma91"); ?>"),
            "ma141":htmlEntities("<?php echo $this->lang->line("ma141"); ?>"),
            "ma142":htmlEntities("<?php echo $this->lang->line("ma142"); ?>"),
            "mm128":htmlEntities("<?php echo $this->lang->line("mm128"); ?>"),
            "mm129":htmlEntities("<?php echo $this->lang->line("mm129"); ?>"),
            "mm200":htmlEntities("<?php echo $this->lang->line("mm200"); ?>"),
            "mm201":htmlEntities("<?php echo $this->lang->line("mm201"); ?>"),
            "mm130":htmlEntities("<?php echo $this->lang->line("mm130"); ?>"),
            "mm154":htmlEntities("<?php echo $this->lang->line("mm154"); ?>"),
            "mm135":htmlEntities("<?php echo $this->lang->line("mm135"); ?>")
        };
    </script>
    <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/admin/js/main.js?ver=') . MD_VER ?>"></script>
    <script src="<?php echo base_url('assets/base/admin/themes/default/js/main.js?ver=') . MD_VER ?>"></script>
    <?php md_get_the_js_urls(); ?>
</body>
</html>