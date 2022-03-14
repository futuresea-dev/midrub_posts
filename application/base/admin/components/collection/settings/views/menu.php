<ul class="nav nav-pills nav-stacked labels-info">
    <li>
        <h4>
            <?php echo $this->lang->line('settings'); ?>
        </h4>
    </li>
    <li <?php settings_menu_item_active(); ?>>
        <a href="<?php echo site_url('admin/settings') ?>">
            <i class="material-icons-outlined">
                settings
            </i>
            <?php echo $this->lang->line('general'); ?>
        </a>
    </li>  
    <li <?php settings_menu_item_active('advanced' ); ?>>
        <a href="<?php echo site_url('admin/settings?p=advanced') ?>">
            <i class="material-icons-outlined">
                settings_suggest
            </i>
            <?php echo $this->lang->line('advanced'); ?>
        </a>
    </li> 
    <li <?php settings_menu_item_active('users'); ?>>
        <a href="<?php echo site_url('admin/settings?p=users') ?>">
            <i class="material-icons-outlined">
                people_outline
            </i>
            <?php echo $this->lang->line('users'); ?>
        </a>
    </li> 
    <li <?php settings_menu_item_active('storage'); ?>>
        <a href="<?php echo site_url('admin/settings?p=storage') ?>">
            <i class="material-icons-outlined">
                settings_system_daydream
            </i>
            <?php echo $this->lang->line('settings_storage'); ?>
        </a>
    </li>    
    <li <?php settings_menu_item_active('smtp'); ?>>
        <a href="<?php echo site_url('admin/settings?p=smtp') ?>">
            <i class="material-icons-outlined">
                mark_email_read
            </i>
            <?php echo $this->lang->line('smtp'); ?>
        </a>
    </li> 
</ul>
<ul class="nav nav-pills nav-stacked labels-info">
    <li>
        <h4>
            <?php echo $this->lang->line('customizations'); ?>
        </h4>
    </li>
    <li <?php settings_menu_item_active('appearance'); ?>>
        <a href="<?php echo site_url('admin/settings?p=appearance') ?>">
            <i class="material-icons-outlined">
                imagesearch_roller
            </i>
            <?php echo $this->lang->line('appearance'); ?>
        </a>
    </li>
</ul>
<ul class="nav nav-pills nav-stacked labels-info">
    <li>
        <h4>
            <?php echo $this->lang->line('payments'); ?>
        </h4>
    </li>
    <li <?php settings_menu_item_active('gateways' ); ?>>
        <a href="<?php echo site_url('admin/settings?p=gateways') ?>">
            <i class="material-icons-outlined">
                store
            </i>
            <?php echo $this->lang->line('gateways'); ?>
        </a>
    </li>  
    <li <?php settings_menu_item_active('coupon-codes' ); ?>>
        <a href="<?php echo site_url('admin/settings?p=coupon-codes') ?>">
            <i class="material-icons-outlined">
                card_giftcard
            </i>
            <?php echo $this->lang->line('coupon_codes'); ?>
        </a>
    </li>  
</ul>
<ul class="nav nav-pills nav-stacked labels-info">
    <li>
        <h4>
            <?php echo $this->lang->line('affiliates'); ?>
        </h4>
    </li>
    <li <?php settings_menu_item_active('affiliates-reports'); ?>>
        <a href="<?php echo site_url('admin/settings?p=affiliates-reports') ?>">
            <i class="material-icons-outlined">
                point_of_sale
            </i>
            <?php echo $this->lang->line('reports'); ?>
        </a>
    </li>
    <li <?php settings_menu_item_active('affiliates-settings'); ?>>
        <a href="<?php echo site_url('admin/settings?p=affiliates-settings') ?>">
            <i class="material-icons-outlined">
                price_change
            </i>
            <?php echo $this->lang->line('settings'); ?>
        </a>
    </li>    
</ul>
<ul class="nav nav-pills nav-stacked labels-info">
    <li>
        <h4>
            <?php echo $this->lang->line('api'); ?>
        </h4>
    </li>
    <li <?php settings_menu_item_active('api-permissions'); ?>>
        <a href="<?php echo site_url('admin/settings?p=api-permissions') ?>">
            <i class="material-icons-outlined">
                recent_actors
            </i>
            <?php echo $this->lang->line('permissions'); ?>
        </a>
    </li>
    <li <?php settings_menu_item_active('api-applications'); ?>>
        <a href="<?php echo site_url('admin/settings?p=api-applications') ?>">
            <i class="material-icons-outlined">
                calendar_view_day
            </i>
            <?php echo $this->lang->line('settings_apps'); ?>
        </a>
    </li>    
</ul>