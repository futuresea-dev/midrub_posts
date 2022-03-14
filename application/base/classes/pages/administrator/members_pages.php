<?php
/**
 * Members Pages Class
 *
 * This file loads the Members_pages Class with properties used to displays pages in the user and administrator panel
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.3
 */

// Define the page namespace
namespace MidrubBase\Classes\Pages\Administrator;

// Constats
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Members_pages class loads the general properties used to displays pages in the user and administrator panel
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.3
 */
class Members_pages {
    
    /**
     * Contains and array with saved pages
     *
     * @since 0.0.8.3
     */
    public static $the_pages = array(); 

    /**
     * The public method set_page adds page
     * 
     * @param string $page_slug contains the page's slug
     * @param array $args contains the page's arguments
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function set_page($page_slug, $args) {

        // Verify if the page has valid fields
        if ( $page_slug && isset($args['page_name']) && isset($args['page_icon']) ) {

            self::$the_pages[][$page_slug] = $args;
            
        }

    } 

    /**
     * The public method load_pages loads all pages
     * 
     * @since 0.0.8.3
     * 
     * @return array with pages or boolean false
     */
    public function load_pages() {

        // Verify if pages exists
        if ( self::$the_pages ) {

            return self::$the_pages;

        } else {

            return false;

        }

    }

}
