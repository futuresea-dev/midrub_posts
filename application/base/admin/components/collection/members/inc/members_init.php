<?php
/**
 * Members Init Inc
 *
 * PHP Version 7.3
 *
 * This files contains the functions which
 * are runned when the pages loads
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

// Define the namespaces to use
use MidrubBase\Admin\Components\Collection\Members\Classes as MidrubBaseAdminComponentsCollectionMembersClasses;

if ( !function_exists('set_admin_members_field') ) {
    
    /**
     * The function set_admin_members_field registers a members field
     * 
     * @param string $field_slug contains the field's slug
     * @param array $args contains the contents field's arguments
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    function set_admin_members_field($field_slug, $args) {

        // Call the Members class
        $members = (new MidrubBaseAdminComponentsCollectionMembersClasses\Members);

        // Set members fields in the queue
        $members->set_field($field_slug, $args);
        
    }
    
}

if ( !function_exists('remove_admin_members_field') ) {
    
    /**
     * The function remove_admin_members_field removes a members field
     * 
     * @param string $field_slug contains the field's slug
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    function remove_admin_members_field($field_slug) {

        // Call the Members class
        $members = (new MidrubBaseAdminComponentsCollectionMembersClasses\Members);

        // Remove members fields from the queue
        $members->remove_field($field_slug);
        
    }
    
}

if ( !function_exists('the_admin_members_fields') ) {
    
    /**
     * The function the_admin_members_fields returns the members fields
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    function the_admin_members_fields() {

        // Call the Members class
        $members = (new MidrubBaseAdminComponentsCollectionMembersClasses\Members);

        // Returns the fields
        return $members->load_fields();
        
    }
    
}

if ( !function_exists('get_admin_members_fields') ) {
    
    /**
     * The function get_admin_members_fields shows the members fields
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    function get_admin_members_fields() {

        // Call the Members_fields_templates class
        $members_fields_templates = (new MidrubBaseAdminComponentsCollectionMembersClasses\Members_fields_templates);
        
        // Get the members fields
        $members_fields = the_admin_members_fields();

        // Verify if $members_fields is not empty
        if ( $members_fields ) {

            // List all members fields
            foreach ( $members_fields as $field_slug => $field_args ) {

                // Set field's slug
                $field_args['field_slug'] = $field_slug;

                // Show field's template
                $members_fields_templates->load_field_template($field_args);

            }

        }
        
    }
    
}

if ( !function_exists('set_admin_members_field_template') ) {
    
    /**
     * The function set_admin_members_field_template registers a members field template
     * 
     * @param string $template_slug contains the template's slug
     * @param string $template_content contains the name of the function which shows the field
     * @param string $template_validation contains the name of the function which validates the field
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    function set_admin_members_field_template($template_slug, $template_content, $template_validation) {

        // Call the Members_fields_templates class
        $members_fields_templates = (new MidrubBaseAdminComponentsCollectionMembersClasses\Members_fields_templates);

        // Set members fields templates in the queue
        $members_fields_templates->set_fields_template($template_slug, $template_content, $template_validation);
        
    }
    
}

if ( !function_exists('validate_admin_members_field') ) {
    
    /**
     * The function validate_admin_members_field validates an admin member field
     * 
     * @param string $template_slug contains the template's slug
     * @param array $field_args contains the field's parameters
     * @param array $field_value contains the field's value
     * @param integer $member_id contains the member's ID
     * 
     * @since 0.0.8.3
     * 
     * @return array with response
     */
    function validate_admin_members_field($template_slug, $field_args, $field_value, $member_id) {

        // Call the Members_fields_templates class
        $members_fields_templates = (new MidrubBaseAdminComponentsCollectionMembersClasses\Members_fields_templates);
        
        // Set the validation's function
        $validation_func = $members_fields_templates->the_field_validation($template_slug);

        // Verify if $validation_func exists
        if ( $validation_func ) {

            // Set response
            return $validation_func($field_args, $field_value, $member_id);

        } else {

            // Set error response
            return array(
                'success' => FALSE,
                'message' => str_replace('[field]', $field_args[$field_value['field_id']]['field_name'], get_instance()->lang->line('members_the_field_can_not_be_validated'))
            );

        }
        
    }
    
}

if ( !function_exists('set_admin_members_member_tab') ) {
    
    /**
     * The function set_admin_members_member_tab registers a member's tab
     * 
     * @param string $tab_slug contains the tab's slug
     * @param array $args contains the contents tab's arguments
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    function set_admin_members_member_tab($tab_slug, $args) {

        // Call the Members_tabs class
        $members_tabs = (new MidrubBaseAdminComponentsCollectionMembersClasses\Members_tabs);

        // Set member's tabs in the queue
        $members_tabs->set_tab($tab_slug, $args);
        
    }
    
}

if ( !function_exists('the_admin_members_member_tabs') ) {
    
    /**
     * The function the_admin_members_member_tabs returns the member's tabs
     * 
     * @since 0.0.8.3
     * 
     * @return array with response or boolean false
     */
    function the_admin_members_member_tabs() {

        // Call the Members_tabs class
        $members_tabs = (new MidrubBaseAdminComponentsCollectionMembersClasses\Members_tabs);

        // Returns the tabs
        return $members_tabs->load_tabs();
        
    }
    
}

if ( !function_exists('set_admin_members_member_data_for_general_tab') ) {
    
    /**
     * The function set_admin_members_member_data_for_general_tab registers new data for the General Members tab
     * 
     * @param string $func contains the function's name
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    function set_admin_members_member_data_for_general_tab($func) {

        // Call the Members_general_tab class
        $members_general_tab = (new MidrubBaseAdminComponentsCollectionMembersClasses\Members_general_tab);

        // Set new tab's data in the queue
        $members_general_tab->set_tab_data($func);
        
    }
    
}

if ( !function_exists('the_admin_members_member_data_for_general_tab') ) {
    
    /**
     * The function the_admin_members_member_data_for_general_tab returns the member's tabs
     * 
     * @since 0.0.8.3
     * 
     * @return string with response
     */
    function the_admin_members_member_data_for_general_tab() {

        // Call the Members_general_tab class
        $members_general_tab = (new MidrubBaseAdminComponentsCollectionMembersClasses\Members_general_tab);

        // Get functions
        $functions = $members_general_tab->load_tab_data();

        // Data container
        $data = '';

        // Verify if $functions is not empty
        if ( $functions ) {

            // List the data
            foreach ( $functions as $function ) {

                // Get function's data
                $response = $function();

                // Verify if $response has required fields
                if ( isset($response['label']) && isset($response['value']) ) {

                    // Set data
                    $data .= '<li class="list-group-item d-flex justify-content-between align-items-center">'
                        . $response['label']
                        . '<span class="badge badge-primary badge-pill">'
                            . $response['value']
                        . '</span>'
                    . '</li>';

                }

            }

        }

        // Returns the data
        return $data;
        
    }
    
}