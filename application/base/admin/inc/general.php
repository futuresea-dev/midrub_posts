<?php
/**
 * General Inc
 *
 * This file contains the general functions
 * used in the Admin's panel
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.1
 */

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| DEFAULTS FUNCTIONS WHICH RETURNS DATA
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| DEFAULTS FUNCTIONS WHICH DISPLAYS DATA
|--------------------------------------------------------------------------
*/

if ( !function_exists('get_update_count') ) {
    
    /**
     * The function get_update_count shows the number of available updates
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */
    function get_update_count() {

        // Get codeigniter object instance
        $CI = &get_instance();

        // Get updates
        $get_transaction = $CI->base_model->get_data_where('updates', 'COUNT(update_id) AS  num');

        if ( $get_transaction ) {

            echo $get_transaction[0]['num'];

        } else {

            echo 0;

        }

    }
    
}

if ( !function_exists('get_admin_view') ) {
    
    /**
     * The function get_admin_view gets the admin view
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    function get_admin_view() {

        // Verify if view exists
        if ( md_the_component_variable('admin_content_view') ) {

            // Display view
            echo md_the_component_variable('admin_content_view');

        }
        
    }
    
}

/*
|--------------------------------------------------------------------------
| DEFAULT FUNCTIONS TO SAVE DATA
|--------------------------------------------------------------------------
*/

if ( !function_exists('set_admin_view') ) {
    
    /**
     * The function set_admin_view sets the admin's view
     * 
     * @param array $view contains the view parameters
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    function set_admin_view($view) {

        // Set content view
        md_set_component_variable('admin_content_view', $view);
        
    }
    
}

/*
|--------------------------------------------------------------------------
| REGISTER DEFAULT HOOKS
|--------------------------------------------------------------------------
*/