<?php
/**
 * Ajax Controller
 *
 * This file processes the Plugins's ajax calls
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.4
 */

// Define the page namespace
namespace MidrubBase\Admin\Components\Collection\Plugins\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\Admin\Components\Collection\Plugins\Helpers as MidrubBaseAdminComponentsCollectionPluginsHelpers;

/*
 * Ajax class processes the Plugins component's ajax calls
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.4
 */
class Ajax {
    
    /**
     * Class variables
     *
     * @since 0.0.8.4
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.4
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load the component's language files
        $this->CI->lang->load( 'plugins', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS);

    }

    /**
     * The public method plugins_activate_plugin activates a plugin
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function plugins_activate_plugin() {

        // Activates Plugin
        (new MidrubBaseAdminComponentsCollectionPluginsHelpers\Plugins)->plugins_activate_plugin();
        
    }

    /**
     * The public method plugins_deactivate_plugin deactivates a plugin
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function plugins_deactivate_plugin() {

        // Deactivates Plugin
        (new MidrubBaseAdminComponentsCollectionPluginsHelpers\Plugins)->plugins_deactivate_plugin();
        
    }
    
    /**
     * The public method plugins_upload_plugin uploads an plugin to be installed
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function plugins_upload_plugin() {

        // Uploads Plugin
        (new MidrubBaseAdminComponentsCollectionPluginsHelpers\Plugins)->plugins_upload_plugin();
        
    }

    /**
     * The public method plugins_unzipping_zip extract the plugin from the zip
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function plugins_unzipping_zip() {
        
        // Extract the Plugin
        (new MidrubBaseAdminComponentsCollectionPluginsHelpers\Plugins)->plugins_unzipping_zip();
        
    }

}

/* End of file ajax.php */