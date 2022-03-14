<div class="row">
    <div class="col-lg-12">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-xs-8">
                            <div class="checkbox-option-select">
                                <input id="notifications-alerts-select-all" name="notifications-alerts-select-all" type="checkbox" />
                                <label for="notifications-alerts-select-all"></label>
                            </div>
                            <a href="#" class="btn-option notifications-delete-alerts">
                                <i class="material-icons-outlined">
                                    delete_sweep
                                </i>
                                <?php echo $this->lang->line('notifications_delete'); ?>
                            </a>
                            <a href="<?php echo site_url('admin/notifications?p=users_alerts'); ?>&new=1" class="btn-option">
                                <i class="material-icons-outlined">
                                    notification_add
                                </i>
                                <?php echo $this->lang->line('notifications_new_alert'); ?>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-4">
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1">
                                    <i class="material-icons-outlined">
                                        search
                                    </i>
                                </span>
                                <input type="text" class="form-control notifications-search-alerts" placeholder="<?php echo $this->lang->line('notifications_search_for_alerts'); ?>" />
                                <input type="hidden" class="csrf-sanitize" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <ul class="notifications-list-alerts">
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="pagination-area">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-6">
                    <ul class="pagination" data-type="users-alerts">
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