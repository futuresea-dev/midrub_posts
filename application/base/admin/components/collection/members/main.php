<?php
/**
 * Members Component
 *
 * This file loads the Members component
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
namespace MidrubBase\Admin\Components\Collection\Members;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS') OR define('MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS', MIDRUB_BASE_PATH . 'admin/components/collection/members/');
defined('MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS_VERSION') OR define('MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS_VERSION', '0.0.3');

// Define the namespaces to use
use MidrubBase\Admin\Interfaces as MidrubBaseAdminInterfaces;
use MidrubBase\Admin\Components\Collection\Members\Controllers as MidrubBaseAdminComponentsCollectionMembersControllers;

/*
 * Main class loads the Members component loader
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
        (new MidrubBaseAdminComponentsCollectionMembersControllers\Init)->view();
        
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
            (new MidrubBaseAdminComponentsCollectionMembersControllers\Ajax)->$action();

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

        // Load hooks by category
        switch ($category) {

            case 'init':
                
                // Require the Members Init Inc file
                require_once MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS . 'inc/members_init.php';           

                break;

            case 'auth_init':
            case 'frontend_init':

            
                break;

            case 'admin_init':

                // Load the component's language files
                $this->CI->lang->load( 'members', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS );

                // Verify if is the members's component
                if ( md_the_component_variable('component') === 'members' ) {

                    // Require the members_pages file
                    require_once MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS . '/inc/members_pages.php';

                    // Require the Members Init Hooks Inc file
                    require_once MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS . 'inc/members_init_hooks.php';     

                } else if (md_the_component_variable('component') === 'notifications') {

                    // Require the email_templates file
                    require_once MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS . 'inc/email_templates.php';

                }

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
     * The public method component_info contains the Members component's info
     * 
     * @return array with Members component's information
     */
    public function component_info() {
        
        // Load the component's language files
        $this->CI->lang->load( 'members', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS );
        
        // Return component information
        return array(
            'app_name' => $this->CI->lang->line('members'),
            'display_app_name' => $this->CI->lang->line('members'),
            'component_slug' => 'members',
            'component_icon' => '<i class="far fa-edit"></i>',
            'version' => MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS_VERSION,
            'required_version' => '0.0.8.3'
        );
        
    }

}

/* End of file main.php */