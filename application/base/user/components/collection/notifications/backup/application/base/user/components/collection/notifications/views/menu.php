<ul class="nav nav-tabs">
    <li class="nav-item">
        <a href="<?php echo site_url('user/notifications?p=news') ?>" class="nav-link<?php echo ($page === 'news')?' active show':''; ?>">
            <?php
            echo $this->lang->line('notifications_news');
            ?>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?php echo site_url('user/notifications?p=offers') ?>" class="nav-link<?php echo ($page === 'offers')?' active show':''; ?>">
            <?php
            echo $this->lang->line('notifications_offers');
            ?>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?php echo site_url('user/notifications?p=miscellaneous') ?>" class="nav-link<?php echo ($page === 'miscellaneous')?' active show':''; ?>">
            <?php
            echo $this->lang->line('notifications_miscellaneous');
            ?>
        </a>
    </li>    
    <li class="nav-item">
        <a href="<?php echo site_url('user/notifications?p=errors') ?>" class="nav-link<?php echo ($page === 'errors')?' active show':''; ?>">
            <?php
            echo $this->lang->line('notifications_errors');
            ?>
        </a>
    </li>
</ul>