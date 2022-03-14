<?php
/**
 * Midrub Base Admin
 *
 * This file loads the Midrub's Admin Base
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */

// Define the page namespace
namespace MidrubBase\Admin;

// Define the namespaces to use
use MidrubBase\Classes as MidrubBaseClasses;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MIDRUB_BASE_ADMIN') OR define('MIDRUB_BASE_ADMIN', APPPATH . 'base/admin/');

// Require the general functions file
require_once MIDRUB_BASE_ADMIN . 'inc/general.php';

/*
 * Main is the admin's base loader
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */
class Main {

    /**
     * Class variables
     *
     * @since 0.0.7.8
     */
    protected $CI, $admin_theme;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.8
     */
    public function __construct() {

        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Get activated admin theme's slug
        $this->admin_theme = str_replace('-', '_', the_option('themes_activated_admin_theme'));

    }
    
    /**
     * The public method init loads the admin's components
     * 
     * @since 0.0.7.8
     * 
     * @param string $static_slug contains static url's slug
     * @param string $component contains the component's name
     * 
     * 
     * @return void
     */
    public function init($static_slug, $component=NULL) {

        // Verify if user is not admin
        if ( $this->CI->user_role !== '1' ) {
            redirect('/');
        }        

        // If $component is null, show 404
        if ( !$component ) {
            show_404();
        } else if ( !$this->admin_theme ) {
            update_option('themes_activated_admin_theme', 'default');
        }

        // Load Admin Helper
        $this->CI->load->helper('admin_helper');

        // Set current component
        md_set_component_variable('component', $component);

        // Load component
        $this->load_component($component, 'init');
        
        // Load View
        $this->load_view();

    }
    
    /**
     * The public method ajax_init processes the ajax calls
     * 
     * @since 0.0.7.8
     * 
     * @param string $component contains the base's component
     * 
     * @return void
     */
    public function ajax_init($component) {

        // Verify if user is admin
        if ( $this->CI->user_role !== '1' || !$this->admin_theme ) {
            exit();
        }

        // Load component
        $this->load_component($component, 'ajax');
        
    }

    /**
     * The public method load_hooks by category
     * 
     * @since 0.0.7.8
     * 
     * @param string $category contains the hooks category
     * 
     * @return void
     */
    public function load_hooks($category) {

        // Load required language files
        $this->CI->lang->load( 'default_alerts', $this->CI->config->item('language') );

        if ( $category === 'admin_init' ) {

            // Include plans options
            require_once MIDRUB_BASE_PATH . 'inc/plans/options.php'; 

            // Verify if a user's theme is enabled
            if ($this->admin_theme) {

                // Verify if the theme has language files
                if (is_dir(MIDRUB_BASE_ADMIN . 'themes/' . $this->admin_theme . '/language/' . $this->CI->config->item('language') . '/')) {

                    // Load all language files
                    foreach (glob(MIDRUB_BASE_ADMIN . 'themes/' . $this->admin_theme . '/language/' . $this->CI->config->item('language') . '/' . '*.php') as $filename) {

                        $this->CI->lang->load(str_replace(array(MIDRUB_BASE_ADMIN . 'themes/' . $this->admin_theme . '/language/' . $this->CI->config->item('language') . '/', '_lang.php'), '', $filename), $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_ADMIN . 'themes/' . $this->admin_theme . '/');

                    }

                }

                // Load hooks by category
                switch ($category) {

                    case 'admin_init':
                    
                        if (file_exists(MIDRUB_BASE_ADMIN . 'themes/' . $this->admin_theme . '/core/hooks/admin_init.php')) {
                            md_include_component_file(MIDRUB_BASE_ADMIN . 'themes/' . $this->admin_theme . '/core/hooks/admin_init.php');
                        }

                        break;
                        
                }

            }

        }

        // Register all admin components hooks
        foreach (glob(MIDRUB_BASE_ADMIN . 'components/collection/*', GLOB_ONLYDIR) as $dir) {

            // Get the dir name
            $com_dir = trim(basename($dir) . PHP_EOL);

            // Create an array
            $array = array(
                'MidrubBase',
                'Admin',
                'Components',
                'Collection',
                ucfirst($com_dir),
                'Main'
            );

            // Implode the array above
            $cl = implode('\\', $array);

            // Register hooks
            (new $cl())->load_hooks($category);

        }

    }

    /**
     * The private method load_component loads an admin component based on parameters
     * 
     * @since 0.0.7.8
     * 
     * @param string $component contains the component's name
     * @param string $method contains the component's method
     * 
     * @return void
     */
    public function load_component($component, $method) {

        // Run the admin's hooks
        md_run_hook('admin_init', array());

        // Create an array
        $array = array(
            'MidrubBase',
            'Admin',
            'Components',
            'Collection',
            ucfirst($component),
            'Main'
        );

        // Implode the array above
        $cl = implode('\\', $array);

        if ( is_dir(MIDRUB_BASE_ADMIN . 'components/collection/' . $component . '/') ) {

            // Instantiate the component's view
            (new $cl())->$method();

        } else {

            // Display 404 page
            show_404();
            
        }
        
    }    

    /**
     * The private method load_view loads user's theme
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function load_view() {

        // Require the Menu Inc file
        require_once MIDRUB_BASE_ADMIN . 'inc/menu.php';

        // Load theme's inc files
        if ( is_dir(MIDRUB_BASE_ADMIN . 'themes/' . $this->admin_theme . '/core/inc/') ) {

            foreach ( glob(MIDRUB_BASE_ADMIN . 'themes/' . $this->admin_theme . '/core/inc/*.php') as $filename ) {
                require_once $filename;
            }
            
        }

        // Load the theme
        md_include_component_file(MIDRUB_BASE_ADMIN . 'themes/' . $this->admin_theme . '/main.php');
        
    }    
    
}

/* End of file main.php */