<ul>
    <?php
    if ( the_admin_plugins_pages() ) {

        // Get plugins_pages array
        $plugins_pages = the_admin_plugins_pages();

        for ($c = 0; $c < count($plugins_pages); $c++) {

            // Get the page value
            $page = array_values($plugins_pages[$c]);

            // Get page slug
            $page_slug = array_keys($plugins_pages[$c]);

            $active = '';

            if ( ($this->input->get('p', true) === $page_slug[0]) || ($c < 1 && !$this->input->get('p', true) ) ) {
                $active = ' class="active"';
            }

            // Display page
            echo '<li>
                <a href="' . site_url('admin/plugins?p=' . $page_slug[0]) . '"' . $active . '>
                    ' . $page[0]['page_icon'] . '
                    ' . $page[0]['page_name'] . '
                </a>
            </li>';
        }
    }
    ?>
</ul>
<ul>
    <?php
    if ( the_admin_plugins_plugin_pages() ) {

        // Get plugin_pages array
        $plugin_pages = the_admin_plugins_plugin_pages();

        for ($p = 0; $p < count($plugin_pages); $p++) {

            // Get the page value
            $page = array_values($plugin_pages[$p]);

            // Get page slug
            $page_slug = array_keys($plugin_pages[$p]);

            $active = '';

            if ( $this->input->get('p', true) === $page_slug[0] ) {
                $active = ' class="active"';
            }

            // Display page
            echo '<li>
                <a href="' . site_url('admin/plugins?p=' . $page_slug[0]) . '"' . $active . '>
                    ' . $page[0]['page_icon'] . '
                    ' . $page[0]['page_name'] . '
                </a>
            </li>';
        }
    }
    ?>
</ul>
