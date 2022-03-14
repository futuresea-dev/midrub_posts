<?php
/**
 * Members Init Hooks Inc
 *
 * PHP Version 7.3
 *
 * This files contains the hooks which are registered
 * immediately when the Member component is loaded
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

/**
 * The public method set_admin_members_field registers a members field
 * 
 * @since 0.0.8.3
 */
set_admin_members_field(
    'first_name',
    array(
        'field_name' => get_instance()->lang->line('members_first_name'),
        'field_type' => 'text_input',
        'field_required' => TRUE,
        'user_access' => TRUE,
        'words_list' => array(
            'label' => get_instance()->lang->line('members_first_name'),
            'placeholder' => get_instance()->lang->line('members_enter_first_name'),
            'description' => get_instance()->lang->line('members_first_name_description')
        )

    )

);

/**
 * The public method set_admin_members_field registers a members field
 * 
 * @since 0.0.8.3
 */
set_admin_members_field(
    'last_name',
    array(
        'field_name' => get_instance()->lang->line('members_last_name'),
        'field_type' => 'text_input',
        'field_required' => TRUE,
        'user_access' => TRUE,
        'words_list' => array(
            'label' => get_instance()->lang->line('members_last_name'),
            'placeholder' => get_instance()->lang->line('members_enter_last_name'),
            'description' => get_instance()->lang->line('members_last_name_description')
        )

    )

);

/**
 * The public method set_admin_members_field registers a members field
 * 
 * @since 0.0.8.3
 */
set_admin_members_field(
    'username',
    array(
        'field_name' => get_instance()->lang->line('members_username'),
        'field_type' => 'text_input',
        'field_required' => TRUE,
        'user_access' => TRUE,
        'words_list' => array(
            'label' => get_instance()->lang->line('members_username'),
            'placeholder' => get_instance()->lang->line('members_enter_username'),
            'description' => get_instance()->lang->line('members_username_description')
        )

    )

);

/**
 * The public method set_admin_members_field registers a members field
 * 
 * @since 0.0.8.3
 */
set_admin_members_field(
    'email',
    array(
        'field_name' => get_instance()->lang->line('members_email'),
        'field_type' => 'email_input',
        'field_required' => TRUE,
        'user_access' => TRUE,
        'words_list' => array(
            'label' => get_instance()->lang->line('members_email'),
            'placeholder' => get_instance()->lang->line('members_enter_email'),
            'description' => get_instance()->lang->line('members_email_description')
        )

    )

);

/**
 * The public method set_admin_members_field registers a members field
 * 
 * @since 0.0.8.3
 */
set_admin_members_field(
    'country',
    array(
        'field_name' => get_instance()->lang->line('members_country'),
        'field_type' => 'dynamic_list_select',
        'field_required' => FALSE,
        'field_endpoint' => site_url('admin/ajax/members'),
        'user_access' => FALSE,
        'words_list' => array(
            'label' => get_instance()->lang->line('members_country'),
            'select' => get_instance()->lang->line('members_select_country'),
            'placeholder' => get_instance()->lang->line('members_search_countries'),
            'description' => get_instance()->lang->line('members_select_country_description')
        )

    )

);

/**
 * The public method set_admin_members_field registers a members field
 * 
 * @since 0.0.8.3
 */
set_admin_members_field(
    'state',
    array(
        'field_name' => get_instance()->lang->line('members_state'),
        'field_type' => 'text_input',
        'field_required' => FALSE,
        'user_access' => TRUE,
        'words_list' => array(
            'label' => get_instance()->lang->line('members_state'),
            'placeholder' => get_instance()->lang->line('members_enter_state'),
            'description' => get_instance()->lang->line('members_state_description')
        )

    )

);

/**
 * The public method set_admin_members_field registers a members field
 * 
 * @since 0.0.8.3
 */
set_admin_members_field(
    'city',
    array(
        'field_name' => get_instance()->lang->line('members_city'),
        'field_type' => 'text_input',
        'field_required' => FALSE,
        'user_access' => TRUE,
        'words_list' => array(
            'label' => get_instance()->lang->line('members_city'),
            'placeholder' => get_instance()->lang->line('members_enter_city'),
            'description' => get_instance()->lang->line('members_city_description')
        )

    )

);

/**
 * The public method set_admin_members_field registers a members field
 * 
 * @since 0.0.8.3
 */
set_admin_members_field(
    'postal_code',
    array(
        'field_name' => get_instance()->lang->line('members_postal_code'),
        'field_type' => 'text_input',
        'field_required' => FALSE,
        'user_access' => TRUE,
        'words_list' => array(
            'label' => get_instance()->lang->line('members_postal_code'),
            'placeholder' => get_instance()->lang->line('members_enter_postal_code'),
            'description' => get_instance()->lang->line('members_postal_code_description')
        )

    )

);

/**
 * The public method set_admin_members_field registers a members field
 * 
 * @since 0.0.8.3
 */
set_admin_members_field(
    'street_number',
    array(
        'field_name' => get_instance()->lang->line('members_street_number'),
        'field_type' => 'text_input',
        'field_required' => FALSE,
        'user_access' => TRUE,
        'words_list' => array(
            'label' => get_instance()->lang->line('members_street_number'),
            'placeholder' => get_instance()->lang->line('members_enter_street_number'),
            'description' => get_instance()->lang->line('members_street_number_description')
        )

    )

);

/**
 * The public method set_admin_members_field registers a members field
 * 
 * @since 0.0.8.3
 */
set_admin_members_field(
    'password',
    array(
        'field_name' => get_instance()->lang->line('members_password'),
        'field_type' => 'password_input',
        'field_required' => TRUE,
        'user_access' => TRUE,
        'words_list' => array(
            'label' => get_instance()->lang->line('members_password'),
            'placeholder' => get_instance()->lang->line('members_enter_password'),
            'description' => get_instance()->lang->line('members_password_description')
        )

    )

);

/**
 * The public method set_admin_members_field registers a members field
 * 
 * @since 0.0.8.3
 */
set_admin_members_field(
    'role',
    array(
        'field_name' => get_instance()->lang->line('members_role'),
        'field_type' => 'list_select',
        'field_required' => TRUE,
        'user_access' => TRUE,
        'items' => array(
            '0' => get_instance()->lang->line('members_user'),
            '1' => get_instance()->lang->line('members_administrator')
        ),
        'words_list' => array(
            'label' => get_instance()->lang->line('members_role'),
            'select' => get_instance()->lang->line('members_select_role'),
            'description' => get_instance()->lang->line('members_role_description')
        )

    )

);

/**
 * The public method set_admin_members_field registers a members field
 * 
 * @since 0.0.8.3
 */
set_admin_members_field(
    'status',
    array(
        'field_name' => get_instance()->lang->line('members_status'),
        'field_type' => 'list_select',
        'field_required' => TRUE,
        'user_access' => TRUE,
        'items' => array(
            '0' => get_instance()->lang->line('members_inactive'),
            '1' => get_instance()->lang->line('members_active'),
            '2' => get_instance()->lang->line('members_blocked')
        ),
        'words_list' => array(
            'label' => get_instance()->lang->line('members_status'),
            'select' => get_instance()->lang->line('members_select_status'),
            'description' => get_instance()->lang->line('members_select_status_description')
        )

    )

);

/**
 * The public method set_admin_members_field registers a members field
 * 
 * @since 0.0.8.3
 */
set_admin_members_field(
    'plan',
    array(
        'field_name' => get_instance()->lang->line('members_plan'),
        'field_type' => 'dynamic_list_select',
        'field_required' => FALSE,
        'field_endpoint' => site_url('admin/ajax/members'),
        'user_access' => FALSE,
        'words_list' => array(
            'label' => get_instance()->lang->line('members_plan'),
            'select' => get_instance()->lang->line('members_select_plan'),
            'placeholder' => get_instance()->lang->line('members_search_plans'),
            'description' => get_instance()->lang->line('members_select_plan_description')
        )

    )

);

/**
 * The public method set_admin_members_field registers a members field
 * 
 * @since 0.0.8.3
 */
set_admin_members_field(
    'send_email',
    array(
        'field_name' => get_instance()->lang->line('members_send_email'),
        'field_type' => 'checkbox_input',
        'field_required' => FALSE,
        'user_access' => FALSE,
        'words_list' => array(
            'label' => get_instance()->lang->line('members_send_email'),
            'description' => get_instance()->lang->line('members_send_email_description')
        )

    )

);

if (!function_exists('the_member_option')) {
    
    /**
     * The function the_member_option gets the member's options
     * 
     * @param string $option contains the option's name
     * @param integer $member_id contains the member's id
     * 
     * @return object or string with meta's value
     */
    function the_member_option($option, $member_id) {
        
        // Get codeigniter object instance
        $CI = get_instance();

        // Verify if $all_member_options property is not empty
        if ( !empty($CI->all_member_options) ) {

            if ( isset($CI->all_member_options[$option]) ) {
                
                return $CI->all_member_options[$option];
                
            } else {
                
                return false;
                
            }
            
        } else {
        
            // Load User Meta Model
            $CI->load->model('user_meta');
            
            // Set member's options
            $CI->all_member_options = $CI->user_meta->get_all_user_options($member_id);

            // Verify if member has the option
            if ( isset($CI->all_member_options[$option]) ) {

                return $CI->all_member_options[$option];

            } else {
                
                return false;
                
            }
        
        }
        
    }

}

/**
 * The public method set_admin_members_member_data_for_general_tab registers data for the General members tab
 * 
 * @since 0.0.8.3
 */
set_admin_members_member_data_for_general_tab('the_admin_members_member_last_access_data_for_general_tab');

if ( !function_exists('the_admin_members_member_last_access_data_for_general_tab') ) {
    
    /**
     * The function the_admin_members_member_last_access_data_for_general_tab returns the last_access's data
     * 
     * @since 0.0.8.3
     * 
     * @return array with response or boolean false
     */
    function the_admin_members_member_last_access_data_for_general_tab() {

        // Set value
        $value = get_instance()->lang->line('members_never');

        // Verify for last access
        if ( the_member_option('last_access', get_instance()->input->get('member', true)) ) {

            // Set new value
            $value = substr(the_member_option('last_access', get_instance()->input->get('member', true)), 0, 10);

        }

        // Set response
        return array(
            'label' => get_instance()->lang->line('members_last_access'),
            'value' => $value
        );
        
    }
    
}

/**
 * The public method set_admin_members_member_data_for_general_tab registers data for the General members tab
 * 
 * @since 0.0.8.3
 */
set_admin_members_member_data_for_general_tab('the_admin_members_member_signup_data_for_general_tab');

if ( !function_exists('the_admin_members_member_signup_data_for_general_tab') ) {
    
    /**
     * The function the_admin_members_member_signup_data_for_general_tab returns the signup's data
     * 
     * @since 0.0.8.3
     * 
     * @return array with response or boolean false
     */
    function the_admin_members_member_signup_data_for_general_tab() {

        // Set response
        return array(
            'label' => get_instance()->lang->line('members_joined'),
            'value' => substr(the_member_option('date_joined', get_instance()->input->get('member', true)), 0, 10)
        );
        
    }
    
}

/**
 * The public method set_admin_members_member_tab registers a members tab
 * 
 * @since 0.0.8.3
 */
set_admin_members_member_tab(
    'general_activity',
    array(
        'tab_name' => get_instance()->lang->line('members_general'),
        'tab_icon' => '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-easel" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'
            . '<path d="M8.473.337a.5.5 0 0 0-.946 0L6.954 2h2.092L8.473.337zM12.15 11h-1.058l1.435 4.163a.5.5 0 0 0 .946-.326L12.15 11zM8.5 11h-1v2.5a.5.5 0 0 0 1 0V11zm-3.592 0H3.85l-1.323 3.837a.5.5 0 1 0 .946.326L4.908 11z" />'
            . '<path fill-rule="evenodd" d="M14 3H2v7h12V3zM2 2a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z" />'
        . '</svg>',
        'tab_content' => '<ul class="list-group">' . the_admin_members_member_data_for_general_tab() . '</li>',
        'css_urls' => array(),
        'js_urls' => array() 
    )
);

/**
 * The public method set_admin_members_member_tab registers a members tab
 * 
 * @since 0.0.8.3
 */
set_admin_members_member_tab(
    'transactions',
    array(
        'tab_name' => get_instance()->lang->line('members_transactions'),
        'tab_icon' => '<i class="fas fa-file-invoice-dollar"></i>',
        'tab_content' => '<div class="row">
            <div class="col-lg-12">
                <ul class="list-transactions">
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="pagination-area" style="display: block;">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-xs-6">
                            <ul class="pagination" data-type="member-transactions">
                            </ul>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-6 text-right">
                            <p>
                                <span>
                                    <span class="pagination-from"></span>
                                    –
                                    <span class="pagination-to"></span>
                                </span>
                                ' . get_instance()->lang->line('members_of') . ' <span class="pagination-total"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/admin/components/collection/members/styles/css/tab-transactions.css?ver=' . MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/admin/components/collection/members/js/tab-transactions.js?ver=' . MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS_VERSION))
        )  
    )
);