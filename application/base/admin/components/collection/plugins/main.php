<?php
/**
 * Midrub Admin Plugins
 *
 * This file loads the Admin Plugins component
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.4
 */

// Define the page namespace
namespace MidrubBase\Admin\Components\Collection\Plugins;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS') OR define('MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS', MIDRUB_BASE_PATH . 'admin/components/collection/plugins/');
defined('MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS_VERSION') OR define('MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS_VERSION', '0.2');

// Define the namespaces to use
use MidrubBase\Admin\Interfaces as MidrubBaseAdminInterfaces;
use MidrubBase\Admin\Components\Collection\Plugins\Controllers as MidrubBaseAdminComponentsCollectionPluginsControllers;

/*
 * Main class loads the Plugins component loader
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.4
 */
class Main implements MidrubBaseAdminInterfaces\Admin {
    
    /**
     * Class variables
     *
     * @since 0.0.8.4
     */
    protected
            $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.4
     */
    public function __construct() {
        
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        
    }
    
    /**
     * The public method init loads the component's main page in the Plugins panel
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function init() {
        
        // Instantiate the class
        (new MidrubBaseAdminComponentsCollectionPluginsControllers\Init)->view();
        
    }
    
    /**
     * The public method ajax processes the ajax's requests
     * 
     * @since 0.0.8.4
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
            (new MidrubBaseAdminComponentsCollectionPluginsControllers\Ajax)->$action();

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
     * @since 0.0.8.4
     * 
     * @param string $category contains the hooks category
     * 
     * @return void
     */
    public function load_hooks($category) {

        // Load hooks by category
        switch ($category) {

            case 'init':
                
                // Require the Plugins Init Inc file
                require_once MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'inc/plugins_init.php';           

                break;

            case 'admin_init':

                // Load the component's language files
                $this->CI->lang->load( 'plugins', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS);

                // Verify if plugins has opened the plugins's component
                if ( the_global_variable('component') === 'plugins' ) {

                    // Require the Plugins Init Hooks Inc file
                    require_once MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'inc/plugins_init_hooks.php';    

                    // Require the plugins_pages file
                    require_once MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'inc/plugins_pages.php';

                }

                break;

        }

    }

    /**
     * The public method api_call processes the api's calls
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function api_call() {

    }
    
    /**
     * The public method component_info contains the admin component's info
     * 
     * @since 0.0.8.4
     * 
     * @return array with admin component's information
     */
    public function component_info() {
        
        // Load the component's language files
        $this->CI->lang->load( 'plugins', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS);
        
        // Return component information
        return array(
            'app_name' => $this->CI->lang->line('plugins'),
            'display_app_name' => $this->CI->lang->line('plugins'),
            'component_slug' => 'plugins',
            'component_icon' => '<i class="icon-calculator"></i>',
            'version' => MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS_VERSION,
            'required_version' => '0.0.8.4'
        );
        
    }

}

/* End of file main.php */