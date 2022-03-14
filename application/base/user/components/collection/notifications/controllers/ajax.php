<?php
/**
 * Ajax Controller
 *
 * This file processes the component's ajax calls
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

// Define the page namespace
namespace MidrubBase\User\Components\Collection\Notifications\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Components\Collection\Notifications\Helpers as MidrubBaseUserComponentsCollectionNotificationsHelpers;

/*
 * Ajaz class processes the app's ajax calls
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */
class Ajax {
    
    /**
     * Class variables
     *
     * @since 0.0.7.9
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.9
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load language
        $this->CI->lang->load( 'notifications_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS );
        
    }

    /**
     * The public method load_news_by_page loads news alerts by page
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function load_news_by_page() {
        
        // Load alerts
        (new MidrubBaseUserComponentsCollectionNotificationsHelpers\News)->load_news_by_page();
        
    } 

    /**
     * The public method delete_news_alert deletes a news alert
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function delete_news_alert() {
        
        // Delete alert
        (new MidrubBaseUserComponentsCollectionNotificationsHelpers\News)->delete_news_alert();
        
    } 

    /**
     * The public method load_offers_by_page loads offers alerts by page
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function load_offers_by_page() {
        
        // Load alerts
        (new MidrubBaseUserComponentsCollectionNotificationsHelpers\Offers)->load_offers_by_page();
        
    } 

    /**
     * The public method delete_offers_alert deletes a offers alert
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function delete_offers_alert() {
        
        // Delete alert
        (new MidrubBaseUserComponentsCollectionNotificationsHelpers\Offers)->delete_offers_alert();
        
    }

    /**
     * The public method load_miscellaneous_by_page loads miscellaneous alerts by page
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function load_miscellaneous_by_page() {
        
        // Load alerts
        (new MidrubBaseUserComponentsCollectionNotificationsHelpers\Miscellaneous)->load_miscellaneous_by_page();
        
    } 

    /**
     * The public method delete_miscellaneous_alert deletes a miscellaneous alert
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function delete_miscellaneous_alert() {
        
        // Delete alert
        (new MidrubBaseUserComponentsCollectionNotificationsHelpers\Miscellaneous)->delete_miscellaneous_alert();
        
    }

    /**
     * The public method load_errors_by_page loads errors alerts by page
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function load_errors_by_page() {
        
        // Load alerts
        (new MidrubBaseUserComponentsCollectionNotificationsHelpers\Errors)->load_errors_by_page();
        
    } 

    /**
     * The public method delete_errors_alert deletes a errors alert
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function delete_errors_alert() {
        
        // Delete alert
        (new MidrubBaseUserComponentsCollectionNotificationsHelpers\Errors)->delete_errors_alert();
        
    }

    /**
     * The public method load_alerts loads the alerts
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function load_alerts() {
        
        // Load alerts
        (new MidrubBaseUserComponentsCollectionNotificationsHelpers\Alerts)->load_alerts();
        
    }

    /**
     * The public method hide_alert hides an alert
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function hide_alert() {
        
        // Hide alert
        (new MidrubBaseUserComponentsCollectionNotificationsHelpers\Alerts)->hide_alert();
        
    }

    /**
     * The public method load_error_alerts loads the error alerts
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function load_error_alerts() {
        
        // Load error alerts
        (new MidrubBaseUserComponentsCollectionNotificationsHelpers\Alerts)->load_error_alerts();
        
    }
    
}
