<div class="row">
    <div class="col-lg-12">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-xs-8">
                            <div class="checkbox-option-select">
                                <input id="notifications-errors-select-all" name="notifications-errors-select-all" type="checkbox" />
                                <label for="notifications-errors-select-all"></label>
                            </div>
                            <a href="#" class="btn-option notifications-delete-errors">
                                <i class="material-icons-outlined">
                                    delete_sweep
                                </i>
                                <?php echo $this->lang->line('notifications_delete'); ?>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-4">
                            <div class="dropdown" id="notifications-errors-types">
                                <button class="btn btn-secondary dropdown-toggle notifications-errors-type-btn" type="button" data-toggle="dropdown" aria-expanded="false">
                                    <?php echo $this->lang->line('notifications_all_errors_types'); ?>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdown-items">
                                    <div class="card">
                                        <div class="card-head">
                                            <input type="text" class="notifications-search-errors-types" placeholder="<?php echo $this->lang->line('notifications_search_errors_types'); ?>" />
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group notifications-errors-types-list"></ul>
                                        </div>
                                    </div>
                                </div>
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
        <div class="collapse" id="notifications-advanced-filters">
            <div class="card card-body">
                <div class="dropdown" id="notifications-advanced-filters-users">
                    <button class="btn btn-secondary notifications-advanced-filters-users-btn dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                        <?php echo $this->lang->line('notifications_all_users'); ?>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdown-items">
                        <div class="card">
                            <div class="card-head">
                                <input type="text" class="notifications-search-errors-users" placeholder="<?php echo $this->lang->line('notifications_search_for_users'); ?>" />
                            </div>
                            <div class="card-body">
                                <ul class="list-group notifications-errors-users-list"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <ul class="notifications-list-errors">
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="pagination-area">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-6">
                    <ul class="pagination" data-type="system-errors">
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