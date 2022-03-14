<?php
/**
 * Midrub Components Notifications
 *
 * This file loads the Notifications Components
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

// Define the page namespace
namespace MidrubBase\User\Components\Collection\Notifications;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS') OR define('MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS', APPPATH . 'base/user/components/collection/notifications/');
defined('MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS_VERSION') OR define('MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS_VERSION', '0.5');

// Define the namespaces to use
use MidrubBase\User\Interfaces as MidrubBaseUserInterfaces;
use MidrubBase\User\Components\Collection\Notifications\Controllers as MidrubBaseUserComponentsCollectionNotificationsControllers;

/*
 * Main class loads the Notifications component loader
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */
class Main implements MidrubBaseUserInterfaces\Components {
   
    /**
     * Class variables
     *
     * @since 0.0.7.9
     */
    protected
            $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.9
     */
    public function __construct() {
        
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        
    }

    /**
     * The public method check_availability checks if the component is available
     *
     * @return boolean true or false
     */
    public function check_availability() {

        if ( !get_option('component_notifications_enable') || !team_role_permission('notifications') ) {
            return false;
        } else {
            return true;
        }
        
    }
    
    /**
     * The public method user loads the component's main page in the user panel
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function user() {

        // Verify if the component is enabled
        if ( !get_option('component_notifications_enable') ) {
            show_404();
        }

        // Instantiate the class
        (new MidrubBaseUserComponentsCollectionNotificationsControllers\User)->view();
        
    }
    
    /**
     * The public method ajax processes the ajax's requests
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function ajax() {

        // Get action's get input
        $action = $this->CI->input->get('action', TRUE);

        if ( !$action ) {
            $action = $this->CI->input->post('action');
        }
        
        try {
            
            // Call method if exists
            (new MidrubBaseUserComponentsCollectionNotificationsControllers\Ajax)->$action();
            
        } catch (Exception $ex) {
            
            $data = array(
                'success' => FALSE,
                'message' => $ex->getMessage()
            );
            
            echo json_encode($data);
            
        }

    }

    /**
     * The public method rest processes the rest's requests
     * 
     * @param string $endpoint contains the requested endpoint
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function rest($endpoint) {

    }
    
    /**
     * The public method cron_jobs loads the cron jobs commands
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function cron_jobs() {
        
    }
    
    /**
     * The public method delete_account is called when user's account is deleted
     * 
     * @param integer $user_id contains the user's ID
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function delete_account($user_id) {
        
    }
    
    /**
     * The public method hooks contains the component's hooks
     * 
     * @param string $category contains the hooks category
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function load_hooks( $category ) {

        // Load and run hooks based on category
        switch ($category) {

            case 'user_init':

                // Set the Alerts styles
                set_css_urls(array('stylesheet', base_url('assets/base/user/components/collection/notifications/styles/css/alerts.css?ver=' . MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS_VERSION), 'text/css', 'all'));

                // Load the Alerts JS
                set_js_urls(array(base_url('assets/base/user/components/collection/notifications/js/alerts.js?ver=' . MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS_VERSION)));

                // Add html for alerts
                add_hook(
                    'the_footer',
                    function () {

                        // Display alerts block
                        echo '<div class="notifications-popups">'
                            . '<div class="notifications-popup notifications-error-banner">'
                            . '</div>'
                            . '<div class="notifications-popup notifications-news-banner">'
                            . '</div>'
                            . '<div class="notifications-popup notifications-fixed-banner">'
                            . '</div>'
                        . '</div>'
                        . '<div class="notifications-promo">'
                            . '<div class="notifications-promo-banner">'
                            . '</div>'
                        . '</div>';

                    }

                );

                break;

            case 'admin_init':

                // Load the component's language files
                $this->CI->lang->load('notifications_admin', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS);
                    
                // Require the Admin Inc
                md_include_component_file(MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'inc/admin.php');

                break;

        }

    }
    
    /**
     * The public method component_info contains the component's info
     * 
     * @since 0.0.7.9
     * 
     * @return array with component's information
     */
    public function component_info() {

        // Load the component's language files
        $this->CI->lang->load( 'notifications_admin', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS );
        
        // Return component's information
        return array(
            'component_name' => $this->CI->lang->line('notifications'),
            'component_slug' => 'notifications',
            'component_icon' => '<i class="icon-bell"></i>',
            'version' => MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS_VERSION,
            'update_url' => 'https://update.midrub.com/notifications/',
            'update_code' => TRUE,
            'update_code_url' => 'https://access-codes.midrub.com/',
            'min_version' => '0.0.8.3',
            'max_version' => '0.0.8.4',
        );
        
    }

}

/* End of file main.php */
