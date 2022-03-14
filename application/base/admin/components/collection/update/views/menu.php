<ul class="nav nav-pills nav-stacked labels-info">
    <li <?php echo (!$this->input->get('p', true))?'class="active"':''; ?>>
        <a href="<?php echo site_url('admin/update') ?>">
            <i class="material-icons-outlined">
                room_preferences
            </i>
            <?php echo $this->lang->line('update_system'); ?>
        </a>
    </li>
    <li <?php echo ( $this->input->get('p', true) === 'apps' )?'class="active"':''; ?>>
        <a href="<?php echo site_url('admin/update') ?>?p=apps">
            <i class="material-icons-outlined">
                recent_actors
            </i>
            <?php echo $this->lang->line('update_apps'); ?>
        </a>
    </li> 
    <li <?php echo ( $this->input->get('p', true) === 'user_components' )?'class="active"':''; ?>>
        <a href="<?php echo site_url('admin/update') ?>?p=user_components">
            <i class="material-icons-outlined">
                switch_account
            </i>
            <?php echo $this->lang->line('update_user_components'); ?>
        </a>
    </li>  
    <li <?php echo ( $this->input->get('p', true) === 'frontend_themes' )?'class="active"':''; ?>>
        <a href="<?php echo site_url('admin/update') ?>?p=frontend_themes">
            <i class="material-icons-outlined">
                contacts
            </i>
            <?php echo $this->lang->line('update_frontend_themes'); ?>
        </a>
    </li> 
    <li <?php echo ( $this->input->get('p', true) === 'plugins' )?'class="active"':''; ?>>
        <a href="<?php echo site_url('admin/update') ?>?p=plugins">
            <i class="material-icons-outlined">
                dns
            </i>
            <?php echo $this->lang->line('update_plugins'); ?>
        </a>
    </li>            
</ul>