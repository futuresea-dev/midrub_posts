<div class="row">
    <div class="col-lg-12">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-xs-8">
                            <a href="<?php echo site_url('admin/notifications?p=email_templates'); ?>&new=1" class="btn-option">
                                <i class="material-icons">
                                    post_add
                                </i>
                                <?php echo $this->lang->line('notifications_new_template'); ?>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-4">
                            <div class="input-group">
                                <span class="input-group-addon" id="search-notifications-icon">
                                    <i class="material-icons-outlined">
                                        search
                                    </i>
                                </span>
                                <input type="text" class="form-control search-templates" placeholder="<?php echo $this->lang->line('notifications_search_for_templates'); ?>" />
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
        <ul class="notifications-list-templates">
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="pagination-area">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-6">
                    <ul class="pagination" data-type="templates">
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