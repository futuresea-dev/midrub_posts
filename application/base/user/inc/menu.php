<?php
/**
 * Menu Inc
 *
 * This file contains the function to
 * display the user's menu
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.3
 */

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| DEFAULTS FUNCTIONS WHICH DISPLAYS DATA
|--------------------------------------------------------------------------
*/

if ( !function_exists('get_menu') ) {
    
    /**
     * The function get_menu generates a menu
     * 
     * @param string $menu_slug contains the menu's slug
     * @param array $args contains the menu's arguments
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    function get_menu($menu_slug, $args) {

        // Require the Read Menu Inc file
        require_once APPPATH . 'base/inc/menu/read_menu.php';

        // Prepare menu's args
        $menu_args = array(
            'slug' => $menu_slug,
            'fields' => array(
                'selected_component',
                'permalink',
                'class'
            ),
            'language' => TRUE
        );

        // Get menu's items
        $menu_items = md_the_theme_menu($menu_args);

        // Verify if menu items exists
        if ( $menu_items ) {

            if ( isset($args['before_menu']) ) {
                echo $args['before_menu'];
            }

            // List all menu items
            for ( $m = 0; $m < count($menu_items); $m++ ) {

                $permalink = '';

                $classification_id = $menu_items[$m]['classification_id'];

                if ( is_dir(MIDRUB_BASE_USER . 'components/collection/' . $menu_items[$m]['selected_component']. '/') && !$menu_items[$m]['permalink'] ) {
                    $permalink = site_url('user/' . $menu_items[$m]['selected_component']);
                    $cl = implode('\\', array('MidrubBase', 'User', 'Components', 'Collection', ucfirst($menu_items[$m]['selected_component']), 'Main'));
                    if (!(new $cl())->check_availability()) {
                        continue;
                    }
                } else if ( is_dir(MIDRUB_BASE_USER . 'apps/collection/' . $menu_items[$m]['selected_component']. '/') && !$menu_items[$m]['permalink'] ) {
                    $permalink = site_url('user/app/' . $menu_items[$m]['selected_component']);
                    $cl = implode('\\', array('MidrubBase', 'User', 'Apps', 'Collection', ucfirst($menu_items[$m]['selected_component']), 'Main'));
                    if (!(new $cl())->check_availability()) {
                        continue;
                    }
                }

                if ( $menu_items[$m]['permalink'] ) {
                    $permalink = $menu_items[$m]['permalink'];
                }

                $class = '';

                if ( $menu_items[$m]['class'] ) {
                    $class = $menu_items[$m]['class'];
                }

                $active = '';

                if ( $menu_items[$m]['selected_component'] === md_the_component_variable('url_slug') ) {
                    $active = ' class="active"';
                }

                if ( isset($args['before_single_item']) ) {
                    echo str_replace(array('[class]', '[url]', '[active]', '[text]'), array($class, $permalink, $active, $menu_items[$m]['meta_value']), $args['before_single_item']);
                }

                if ( isset($menu_items[($m + 1)]) ) {

                    if ( $menu_items[($m + 1)]['parent'] === $classification_id ) {

                        if ( isset($args['before_submenu']) ) {
                            echo $args['before_submenu'];
                        }

                        $fs = ($m + 1);

                        for ( $m2 = $fs; $m2 < count($menu_items); $m2++ ) {

                            if ( $menu_items[$m2]['parent'] !== $classification_id ) {
                                break;
                            }

                            $permalink = '';

                            if ( is_dir(MIDRUB_BASE_USER . 'components/collection/' . $menu_items[$m2]['selected_component']. '/') && !$menu_items[$m2]['permalink'] ) {
                                $permalink = site_url('user/' . $menu_items[$m2]['selected_component']);
                                $cl = implode('\\', array('MidrubBase', 'User', 'Components', 'Collection', ucfirst($menu_items[$m2]['selected_component']), 'Main'));
                                if (!(new $cl())->check_availability()) {
                                    $m++;
                                    continue;
                                }
                            } else if ( is_dir(MIDRUB_BASE_USER . 'apps/collection/' . $menu_items[$m2]['selected_component']. '/') && !$menu_items[$m2]['permalink'] ) {
                                $permalink = site_url('user/app/' . $menu_items[$m2]['selected_component']);
                                $cl = implode('\\', array('MidrubBase', 'User', 'Apps', 'Collection', ucfirst($menu_items[$m2]['selected_component']), 'Main'));
                                if (!(new $cl())->check_availability()) {
                                    $m++;
                                    continue;
                                }
                            }
            
                            if ( $menu_items[$m2]['permalink'] ) {
                                $permalink = $menu_items[$m2]['permalink'];
                            }
            
                            $class = '';
            
                            if ( $menu_items[$m2]['class'] ) {
                                $class = $menu_items[$m2]['class'];
                            }
            
                            $active = '';
            
                            if ( $menu_items[$m2]['selected_component'] === md_the_component_variable('url_slug') ) {
                                $active = ' class="active"';
                            }
            
                            if ( isset($args['before_single_item']) ) {
                                echo str_replace(array('[class]', '[url]', '[active]', '[text]'), array($class, $permalink, $active, $menu_items[$m2]['meta_value']), $args['before_single_item']);
                            }

                            if ( isset($args['after_single_item']) ) {
                                echo $args['after_single_item'];
                            }
                            
                            $m++;

                        }

                        if ( isset($args['after_submenu']) ) {
                            echo $args['after_submenu'];
                        }
                    
                    }

                }

                if ( isset($args['after_single_item']) ) {
                    echo $args['after_single_item'];
                }

            }

            if ( isset($args['after_menu']) ) {
                echo $args['after_menu'];
            }

        }
        
    }
    
}