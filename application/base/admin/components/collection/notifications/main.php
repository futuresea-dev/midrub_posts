<?php
/**
 * Notifications Component
 *
 * This file loads the Notifications component
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/midrub_cms Midrub’s License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.3
 */

// Define the page namespace
namespace MidrubBase\Admin\Components\Collection\Notifications;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MIDRUB_BASE_ADMIN_COMPONENTS_NOTIFICATIONS') OR define('MIDRUB_BASE_ADMIN_COMPONENTS_NOTIFICATIONS', MIDRUB_BASE_PATH . 'admin/components/collection/notifications/');
defined('MIDRUB_BASE_ADMIN_COMPONENTS_NOTIFICATIONS_VERSION') OR define('MIDRUB_BASE_ADMIN_COMPONENTS_NOTIFICATIONS_VERSION', '0.0.4');

// Define the namespaces to use
use MidrubBase\Admin\Interfaces as MidrubBaseAdminInterfaces;
use MidrubBase\Admin\Components\Collection\Notifications\Controllers as MidrubBaseAdminComponentsCollectionNotificationsControllers;

/*
 * Main class loads the Notifications component loader
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/midrub_cms Midrub’s License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.3
 */
class Main implements MidrubBaseAdminInterfaces\Admin {
    
    /**
     * Class variables
     */
    protected
            $CI;

    /**
     * Initialise the Class
     */
    public function __construct() {
        
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        
    }
    
    /**
     * The public method init loads the component's main page in the admin panel
     * 
     * @return void
     */
    public function init() {
        
        // Instantiate the class
        (new MidrubBaseAdminComponentsCollectionNotificationsControllers\Init)->view();
        
    }
    
    /**
     * The public method ajax processes the ajax's requests
     * 
     * @return void
     */
    public function ajax() {
        
        // Get action's get input
        $action = $this->CI->input->get('action');

        if ( !$action ) {
            $action = $this->CI->input->post('action');
        }
        
        try {

            // Call method if exists
            (new MidrubBaseAdminComponentsCollectionNotificationsControllers\Ajax)->$action();

        } catch (Exception $ex) {

            $data = array(
                'success' => FALSE,
                'message' => $ex->getMessage()
            );

            echo json_encode($data);

        }
        
    }

    /**
     * The public method load_hooks by category
     * 
     * @param string $category contains the hooks category
     * 
     * @return void
     */
    public function load_hooks($category) {

        // Load the component's language files
        $this->CI->lang->load( 'notifications', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_ADMIN_COMPONENTS_NOTIFICATIONS );

        // Load hooks by category
        switch ($category) {

            case 'init':
                
                // Require the Notifications Init Inc file
                require_once MIDRUB_BASE_ADMIN_COMPONENTS_NOTIFICATIONS . 'inc/notifications_init.php';           

                break;

            case 'admin_init':

                // Require the Notifications Init Hooks Inc file
                require_once MIDRUB_BASE_ADMIN_COMPONENTS_NOTIFICATIONS . 'inc/notifications_init_hooks.php';                   

                // Require the Notifications Pages Inc file
                require_once MIDRUB_BASE_ADMIN_COMPONENTS_NOTIFICATIONS . '/inc/notifications_pages.php';  

                break;

        }

    }

    /**
     * The public method api_call processes the api's calls
     * 
     * @return void
     */
    public function api_call() {

    }
    
    /**
     * The public method component_info contains the Notifications component's info
     * 
     * @return array with Notifications component's information
     */
    public function component_info() {
        
        // Load the component's language files
        $this->CI->lang->load( 'notifications', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_ADMIN_COMPONENTS_NOTIFICATIONS );
        
        // Return component information
        return array(
            'app_name' => $this->CI->lang->line('notifications'),
            'display_app_name' => $this->CI->lang->line('notifications'),
            'component_slug' => 'notifications',
            'component_icon' => '<i class="far fa-edit"></i>',
            'version' => MIDRUB_BASE_ADMIN_COMPONENTS_NOTIFICATIONS_VERSION,
            'required_version' => '0.0.8.3'
        );
        
    }

}

/* End of file main.php */