<section class="single-faq-article">
    <div class="container-fluid">    
        <div class="row">
            <div class="col-xl-2 offset-xl-2">
                <ul class="nav nav-tabs">
                    <?php

                    // Verify if category exists
                    if ( $categories ) {

                        // Subcategories container
                        $subcategories = array();

                        // List categories
                        foreach ($categories as $category) {

                            // Verify if category has parent
                            if ( $category->parent > 0 ) {

                                // Set subcategory
                                $subcategories[$category->parent][] = $category;

                            }

                        }

                        // Category empty
                        $category = '';

                        // Parent category
                        $parent_name = '';

                        // List categories
                        foreach ($categories as $cat) {

                            // Verify if category is a subcategory
                            if ( $cat->category_id !== $category_id && $cat->parent !== $category_id && $cat->category_id !== $parent ) {
                                continue;
                            }
                            
                            if ( $cat->parent > 0 ) {
                                continue;
                            }                        

                            // Subcategory container
                            $subcats = '';

                            // Verify if subcategory exists
                            if ( isset($subcategories[$cat->category_id]) ) {

                                // Add list
                                $subcats = '<ul class="list-group">';

                                // List subcategory
                                foreach ( $subcategories[$cat->category_id] as $subcat ) {
                                    
                                    // Verify if subcategory has current $category id as parent
                                    if ( $subcat->category_id === $category_id ) {
                                        
                                        // Set parent name
                                        $parent_name = $subcat->name;
                                        
                                    }

                                    // Set subcategory
                                    $subcats .= '<li class="nav-item">'
                                                    . '<a href="' . site_url('user/faq?p=categories&category=' . $subcat->category_id) . '">'
                                                        . $subcat->name
                                                    . '</a>'
                                                . '</li>';

                                }

                                // End the lost
                                $subcats .= '</ul>';

                            }

                            // Set category
                            $category = $cat->name;

                            // Display the category
                            echo '<li>'
                                    . '<h3>'
                                        . $cat->name
                                    . '</h3>'
                                    . $subcats
                                . '</li>';

                        }

                    }
                    ?>
                </ul>
            </div>
            <div class="col-xl-6">
                <div class="settings-list">
                    <div class="tab-content">
                        <div class="tab-pane container fade active show" id="main-settings">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="<?php echo site_url('user/faq') ?>"><?php echo $this->lang->line('support_center'); ?></a></li>
                                            <?php
                                            if ( ($parent < 1) && isset($category) ) {
                                                ?>
                                                <li class="breadcrumb-item">
                                                    <a href="<?php echo site_url('user/faq?p=categories&category=' . $category_id) ?>">
                                                        <?php
                                                        echo $category;
                                                        ?>
                                                    </a>
                                                </li>
                                                <?php
                                            } else if ( isset($category) ) {
                                            ?>
                                            <li class="breadcrumb-item">
                                                <a href="<?php echo site_url('user/faq?p=categories&category=' . $parent) ?>">
                                                    <?php
                                                    echo $category;
                                                    ?>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="<?php echo site_url('user/faq?p=categories&category=' . $category_id) ?>">
                                                    <?php
                                                    echo $parent_name;
                                                    ?>
                                                </a>
                                            </li>
                                            <?php
                                            }
                                            ?>
                                            <li class="breadcrumb-item">
                                                <?php
                                                    echo @$article['data'][$this->config->item('language')]['title'];
                                                ?>
                                            </li>                                            
                                        </ol>
                                    </nav>
                                </div>
                                <div class="panel-body">
                                    <div class="article">
                                        <h1 class="title">
                                            <?php
                                            echo @$article['data'][$this->config->item('language')]['title'];
                                            ?>
                                        </h1>
                                        <?php
                                        echo @htmlspecialchars_decode($article['data'][$this->config->item('language')]['body']);
                                        ?>                                       
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

<?php
get_the_file(MIDRUB_BASE_USER_COMPONENTS_FAQ . 'views/footer.php');
?>