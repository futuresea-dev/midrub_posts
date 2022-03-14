<?php get_theme_part('header'); ?>
<section class="plans-list">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 all-plans">
                <div class="row">
                    <div class="col-12">
                        <?php
                        if (the_content_meta('theme_plans_title')) {

                            echo '<h1>' . the_content_meta('theme_plans_title') . '</h1>';
                        }
                        if (the_content_meta('theme_plans_text_below_title')) {

                            echo '<h2>' . the_content_meta('theme_plans_text_below_title') . '</h2>';
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <?php

                            // Get plans
                            $plans = get_all_visible_plans();   
                            
                            // Verify if is a group
                            if ( !empty($plans[0]['plans']) ) {

                                // Set nav start
                                echo '<ul class="nav justify-content-center" id="plans-tab" role="tablist">';

                                // Set groups
                                $groups = $plans;

                                // List groups
                                foreach ( $groups as $group ) {

                                    // Nav active
                                    $nav_active = ( $groups[0]['group_id'] === $group['group_id'] )?' active':'';

                                    // Set nav's item
                                    echo '<li class="nav-item">'
                                            . '<a class="nav-link' . $nav_active . '" id="tab-' . $group['group_id'] . '-tab" data-toggle="tab" href="#tab-' . $group['group_id'] . '" role="tab" aria-controls="tab-' . $group['group_id'] . '" aria-selected="true">'
                                                . $group['group_name']
                                            . '</a>'
                                        . '</li>';


                                }

                                echo '</ul>';

                            }
                        
                        ?>
                    </div>
                </div>                                  
                <div class="row">
                    <div class="col-12">
                        <div class="plans-area">
                            <?php

                            // Verify if is a group
                            if ( !empty($plans[0]['plans']) ) {

                                // Set tabs
                                echo '<div class="tab-content" id="plans-tabs-parent">';

                                // Set groups
                                $groups = $plans;

                                // List groups
                                foreach ( $groups as $group ) {

                                    // Tab active
                                    $tab_active = ( $groups[0]['group_id'] === $group['group_id'] )?' show active':'';

                                    // Set Tab Start
                                    echo '<div class="tab-pane fade' . $tab_active . '" id="tab-' . $group['group_id'] . '" role="tabpanel" aria-labelledby="tab-' . $group['group_id'] . '-tab">';

                                    // Set row
                                    echo '<div class="row">';

                                        $plans = $group['plans'];

                                        $column = 4;

                                        if ( count($plans) === 1 ) {
                                            $column = 12;
                                        } else if ( count($plans) === 2 ) {
                                            $column = 6;
                                        } else if ( count($plans) === 4 ) {
                                            $column = 3;
                                        } else if ( count($plans) === 5 ) {
                                            $column = 2;
                                        }

                                        $url = the_url_by_page_role('sign_up')?the_url_by_page_role('sign_up'):site_url('auth/signup');

                                        foreach ( $plans as $plan ) {

                                            $period = get_the_string('theme_per_month', true);

                                            if ( $plan['period'] > 30 ) {

                                                $period = get_the_string('theme_per_year', true);

                                            }

                                            $plans_features = '';

                                            if ( $plan['features']) {

                                                $features = explode("\n", $plan['features']);

                                                foreach ($features as $feature) {
                                                    
                                                    if ( $feature ) {
                                                    
                                                        $plans_features .= '<li>'
                                                                    . '<i class="icon-check"></i>'
                                                                    . $feature
                                                                . '</li>';

                                                    }

                                                }

                                            }

                                            echo '<div class="col-lg-' . $column . '">'
                                                    . '<div class="row">'
                                                        . '<div class="col-12">'
                                                            . '<h3>'
                                                                . $plan['plan_name']
                                                            . '</h3>'
                                                        . '</div>'
                                                    . '</div>'
                                                    . '<div class="row">'
                                                        . '<div class="col-12">'
                                                            . '<h2>'
                                                                . '<span>'
                                                                    . $plan['currency_sign']
                                                                . '</span>'
                                                                . $plan['plan_price']
                                                            . '</h2>'
                                                        . '</div>'
                                                    . '</div>'
                                                    . '<div class="row">'
                                                        . '<div class="col-12">'
                                                            . '<h4>'
                                                                . $period
                                                            . '</h4>'
                                                        . '</div>'
                                                    . '</div>'
                                                    . '<div class="row">'
                                                        . '<div class="col-12">'
                                                            . '<ul>'
                                                                . $plans_features
                                                            . '</ul>'
                                                        . '</div>'
                                                    . '</div>'
                                                    . '<div class="row">'
                                                        . '<div class="col-12 text-center">'
                                                            . '<a href="' . $url . '?plan=' . $plan['plan_id'] . '" class="btn-success">'
                                                                . get_the_string('theme_get_started', true)
                                                            . '</a>'
                                                        . '</div>'
                                                    . '</div>'
                                                . '</div>';

                                        }

                                    // End row
                                    echo '</div>';

                                    // Set Tab End
                                    echo '</div>';

                                }

                                // Set Tabs End
                                echo '</div>';

                            } else {

                                // Set row
                                echo '<div class="row">';

                                $column = 4;

                                if ( count($plans) === 1 ) {
                                    $column = 12;
                                } else if ( count($plans) === 2 ) {
                                    $column = 6;
                                } else if ( count($plans) === 4 ) {
                                    $column = 3;
                                } else if ( count($plans) === 5 ) {
                                    $column = 2;
                                }

                                $url = the_url_by_page_role('sign_up')?the_url_by_page_role('sign_up'):site_url('auth/signup');

                                foreach ( $plans as $plan ) {

                                    $period = get_the_string('theme_per_month', true);

                                    if ( $plan['period'] > 30 ) {

                                        $period = get_the_string('theme_per_year', true);

                                    }

                                    $plans_features = '';

                                    if ( $plan['features']) {

                                        $features = explode("\n", $plan['features']);

                                        foreach ($features as $feature) {
                                            
                                            if ( $feature ) {
                                            
                                                $plans_features .= '<li>'
                                                            . '<i class="icon-check"></i>'
                                                            . $feature
                                                        . '</li>';

                                            }

                                        }

                                    }

                                    echo '<div class="col-lg-' . $column . '">'
                                            . '<div class="row">'
                                                . '<div class="col-12">'
                                                    . '<h3>'
                                                        . $plan['plan_name']
                                                    . '</h3>'
                                                . '</div>'
                                            . '</div>'
                                            . '<div class="row">'
                                                . '<div class="col-12">'
                                                    . '<h2>'
                                                        . '<span>'
                                                            . $plan['currency_sign']
                                                        . '</span>'
                                                        . $plan['plan_price']
                                                    . '</h2>'
                                                . '</div>'
                                            . '</div>'
                                            . '<div class="row">'
                                                . '<div class="col-12">'
                                                    . '<h4>'
                                                        . $period
                                                    . '</h4>'
                                                . '</div>'
                                            . '</div>'
                                            . '<div class="row">'
                                                . '<div class="col-12">'
                                                    . '<ul>'
                                                        . $plans_features
                                                    . '</ul>'
                                                . '</div>'
                                            . '</div>'
                                            . '<div class="row">'
                                                . '<div class="col-12 text-center">'
                                                    . '<a href="' . $url . '?plan=' . $plan['plan_id'] . '" class="btn-success">'
                                                        . get_the_string('theme_get_started', true)
                                                    . '</a>'
                                                . '</div>'
                                            . '</div>'
                                        . '</div>';

                                }

                                // End row
                                echo '</div>';

                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_theme_part('footer'); ?>