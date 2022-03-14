<?php
/**
 * Members Pages Functions
 *
 * PHP Version 7.3
 *
 * This files contains the admin's pages
 * methods used in admin -> members
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/midrub_cms Midrub’s License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.3
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Get codeigniter object instance
$CI = &get_instance();

/**
 * The public method set_members_page adds a members's page in the admin panel
 * 
 * @since 0.0.8.3
 */
set_members_page(
    'all_members',
    array(
        'page_name' => $CI->lang->line('members_all_members'),
        'page_icon' => '<i class="icon-people"></i>',
        'content' => 'md_get_members_page_all_members',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/admin/components/collection/members/styles/css/styles.css?ver=' . MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/admin/components/collection/members/js/all-members.js?ver=' . MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS_VERSION))
        )  
    )
);

if ( !function_exists('md_get_members_page_all_members') ) {

    /**
     * The function md_get_members_page_all_members displays the All Members page
     * 
     * @return void
     */
    function md_get_members_page_all_members() {

        // Get codeigniter object instance
        $CI = &get_instance();

        // Verify which page should be displayed
        if ( $CI->input->get('new', true) ) {

            // Require the Members Fields Templates Inc file
            require_once MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS . 'inc/members_fields_templates.php';      

            // Include Members New view
            md_include_component_file(MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS . 'views/management/members_new.php');

        } else if ( is_numeric($CI->input->get('member', true)) ) {

            // Get member
            $get_member = $CI->base_model->get_data_where('users', 'user_id', array('user_id' => $CI->input->get('member', true)));

            // Verify if member exists
            if ( $get_member ) {

                // Require the Members Fields Templates Inc file
                require_once MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS . 'inc/members_fields_templates.php';      

                // Set member's ID
                set_global_variable('members_member_id', $CI->input->get('member', true));

                // Include Members Edit view
                md_include_component_file(MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS . 'views/management/members_edit.php');    
            
            } else {

                // Include Members No Data view
                md_include_component_file(MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS . 'views/no_data.php');    

            }

        } else {

            // Include Members List view
            md_include_component_file(MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS . 'views/management/members_list.php');

        }
        
    }

}