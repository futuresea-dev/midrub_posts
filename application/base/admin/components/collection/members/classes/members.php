<?php
/**
 * Members Class
 *
 * This file loads the Members class with methods to register the fields
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
namespace MidrubBase\Admin\Components\Collection\Members\Classes;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Members class loads the properties used to collect the members fields for the Members component
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/midrub_cms Midrub’s License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.3
 */
class Members {
    
    /**
     * Contains and array with saved fields
     *
     * @since 0.0.8.3
     */
    public static $the_fields = array(); 

    /**
     * The public method set_field registers a members field
     * 
     * @param string $field_slug contains the field's slug
     * @param array $args contains the contents field's arguments
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function set_field($field_slug, $args) {

        // Verify if the field has valid parameters
        if ( isset($args['field_name']) && isset($args['field_type']) ) {

            self::$the_fields[$field_slug] = $args;
            
        }

    } 

    /**
     * The public method remove_field removes a members field
     * 
     * @param string $field_slug contains the field's slug
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function remove_field($field_slug) {

        // Verify if the field has valid parameters
        if ( isset(self::$the_fields[$field_slug]) ) {

            // Remove
            unset(self::$the_fields[$field_slug]);
            
        }

    }     

    /**
     * The public method load_field loads a field
     * 
     * @param string $field_slug contains the field's slug
     * 
     * @since 0.0.8.3
     * 
     * @return array with field or boolean false
     */
    public function load_field($field_slug) {

        // Verify if field exists
        if ( isset(self::$the_fields[$field_slug]) ) {

            return self::$the_fields;

        } else {

            return false;

        }

    }

    /**
     * The public method load_fields loads all members fields
     * 
     * @since 0.0.8.3
     * 
     * @return array with fields or boolean false
     */
    public function load_fields() {

        // Verify if fields exists
        if ( self::$the_fields ) {

            return self::$the_fields;

        } else {

            return false;

        }

    }

}

/* End of file members.php */