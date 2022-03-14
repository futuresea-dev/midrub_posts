<?php
/**
 * Storage Options
 *
 * This file contains the class Storage
 * with all storage admin's options
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.
 */

// Define the page namespace
namespace MidrubBase\Admin\Components\Collection\Settings\Options;

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Storage class provides the storage admin's options
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.4
*/
class Storage {
    
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
        
    }

    /**
     * The public method get_options provides all Admin's general settings
     * 
     * @since 0.0.8.4
     * 
     * @return array with settings
     */ 
    public function get_options() {
        
        // Array with all Admin's general settings
        return array (
            
            array (
                'type' => 'storage_dropdown',
                'name' => 'storage_locations',
                'title' => $this->CI->lang->line('settings_storage_location'),
                'description' => $this->CI->lang->line('settings_storage_location_description')
            )
            
        );
        
    }

}

