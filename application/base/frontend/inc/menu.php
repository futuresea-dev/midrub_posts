<?php
/**
 * Menu Inc
 *
 * This file contains the function to
 * display the frontend's menu
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.3
 */

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\Frontend\Classes as MidrubBaseFrontendClasses;

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

        // Display menu
        (new MidrubBaseFrontendClasses\Menu)->get_menu($menu_slug, $args);
        
    }
    
}