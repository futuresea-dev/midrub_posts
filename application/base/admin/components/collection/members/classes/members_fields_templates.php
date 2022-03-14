<?php
/**
 * Members Fields Templates Class
 *
 * This file loads the Members_fields_templates class with methods to process the members fields templates
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
 * Members_fields_templates class loads the properties used to collect the members fields templates for the Members component
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/midrub_cms Midrub’s License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.3
 */
class Members_fields_templates {
    
    /**
     * Contains and array with saved fields templates
     *
     * @since 0.0.8.3
     */
    public static $the_fields_templates = array(); 

    /**
     * The public method set_fields_template registers a members field template
     * 
     * @param string $template_slug contains the template's slug
     * @param string $template_content contains the name of the function which shows the field
     * @param string $template_validation contains the name of the function which validates the field
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function set_fields_template($template_slug, $template_content, $template_validation) {

        // Set field's params
        self::$the_fields_templates[$template_slug] = array(
            'content' => $template_content,
            'validation' => $template_validation
        );       

    } 

    /**
     * The public method load_field_template shows the field's template
     * 
     * @param array $args contains the contents field's arguments
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function load_field_template($args) {

        // Verify if template exists
        if ( isset(self::$the_fields_templates[$args['field_type']]['content']) ) {

            // Verify if function exists
            if ( function_exists(self::$the_fields_templates[$args['field_type']]['content']) ) {

                // Load function
                self::$the_fields_templates[$args['field_type']]['content']($args);

            }

        }

    }

    /**
     * The public method the_field_validation provides the function to validate a field
     * 
     * @param string $template_slug contains the template's slug
     * 
     * @since 0.0.8.3
     * 
     * @return string with the function's name or boolean false
     */
    public function the_field_validation($template_slug) {

        // Verify if the validation function exists
        if ( isset(self::$the_fields_templates[$template_slug]['validation']) ) {

            // Verify if function is callable
            if ( function_exists(self::$the_fields_templates[$template_slug]['validation']) ) {

                // Return function's name
                return self::$the_fields_templates[$template_slug]['validation'];

            }

        }

        return false;

    }

}

/* End of file members_fields_templates.php */