<section class="notifications-page">
    <div class="row">
        <div class="col-xl-8 offset-xl-2">
            <h1 class="notifications-title">
                <?php
                echo $this->lang->line('notifications');
                ?>
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-2 offset-xl-2">
            <div class="notifications-menu-group">
                <?php
                get_the_file(MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'views/menu.php');
                ?>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="notifications-list theme-box">
                <div class="tab-content">
                    <div class="tab-pane container fade active show" id="advanced">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-8">
                                        <?php echo $page_header; ?>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="back-button btn-disabled">
                                            <i class="arrow-left"></i>
                                        </button>
                                        <button type="button" class="next-button btn-disabled">
                                            <i class="arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-12">
                                        <ul class="notifications-list-show">
                                            <li class="notifications-no-results-found">
                                                <div class="row">
                                                    <div class="col-12">
                                                    <?php
                                                        echo $this->lang->line('notifications_no_found');
                                                    ?>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>