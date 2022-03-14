<?php
/**
 * Ajax Controller
 *
 * This file processes the Settings's ajax calls
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */

// Define the page namespace
namespace  MidrubBase\Admin\Components\Collection\Settings\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\Admin\Components\Collection\Settings\Helpers as MidrubBaseAdminComponentsCollectionSettingsHelpers;

/*
 * Ajax class processes the admin component's ajax calls
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */
class Ajax {
    
    /**
     * Class variables
     *
     * @since 0.0.7.6
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.6
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load the component's language files
        $this->CI->lang->load( 'settings', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_ADMIN_COMPONENTS_SETTINGS );
        
    }
    
    /**
     * The public method save_admin_options saves the admin's settings
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function save_admin_settings() {
        
        // Save
        (new MidrubBaseAdminComponentsCollectionSettingsHelpers\Settings)->save_admin_settings();
        
    }

    /**
     * The public method upload_media_in_storage uploads media in storage
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function upload_media_in_storage() {
        
        // Upload the media's files
        (new MidrubBaseAdminComponentsCollectionSettingsHelpers\Settings)->upload_media_in_storage();
        
    }

    /**
     * The public method settings_get_storage_dropdown_items loads the storage's dropdown items
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function settings_get_storage_dropdown_items() {
        
        // Get the items
        (new MidrubBaseAdminComponentsCollectionSettingsHelpers\Storage)->settings_get_storage_dropdown_items();
        
    }

    /**
     * The public method settings_change_storage_location change the storage's location
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function settings_change_storage_location() {
        
        // Send location
        (new MidrubBaseAdminComponentsCollectionSettingsHelpers\Storage)->settings_change_storage_location();
        
    }

    /**
     * The public method settings_get_selected_storage_location get the storage's location
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function settings_get_selected_storage_location() {
        
        // Get the location
        (new MidrubBaseAdminComponentsCollectionSettingsHelpers\Storage)->settings_get_selected_storage_location();
        
    }

    /**
     * The public method get_coupon_codes gets coupons codes
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function get_coupon_codes() {
        
        // Get coupon codes
        (new MidrubBaseAdminComponentsCollectionSettingsHelpers\Coupons)->get_coupon_codes();
        
    }

    /**
     * The public method delete_coupon_code deletes coupons codes
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function delete_coupon_code() {
        
        // Delete coupon codes
        (new MidrubBaseAdminComponentsCollectionSettingsHelpers\Coupons)->delete_coupon_code();
        
    }
    
    /**
     * The public method load_referrals_reports loads referrals reports
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_referrals_reports() {
        
        (new MidrubBaseAdminComponentsCollectionSettingsHelpers\Referrals)->load_referrals_reports();
        
    }
    
    /**
     * The public method load_referrers_list loads referrers list
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function load_referrers_list() {
        
        (new MidrubBaseAdminComponentsCollectionSettingsHelpers\Referrals)->load_referrers_list();
        
    }
    
    /**
     * The public method referral_pay_gains pays user gains
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function referral_pay_gains() {
        
        (new MidrubBaseAdminComponentsCollectionSettingsHelpers\Referrals)->referral_pay_gains();
        
    }
    
    /**
     * The public method update_api_permission_settings saves permission status
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function update_api_permission_settings() {
        
        (new MidrubBaseAdminComponentsCollectionSettingsHelpers\Oauth)->update_api_permission_settings();
        
    }    

    /**
     * The public method create_new_api_app creates a new application
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function create_new_api_app() {
        
        (new MidrubBaseAdminComponentsCollectionSettingsHelpers\Oauth)->create_new_api_app();
        
    }
    
    /**
     * The public method update_api_app updates an application
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function update_api_app() {
        
        (new MidrubBaseAdminComponentsCollectionSettingsHelpers\Oauth)->update_api_app();
        
    }    

    /**
     * The public method load_api_applications loads the api's list
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function load_api_applications() {
        
        (new MidrubBaseAdminComponentsCollectionSettingsHelpers\Oauth)->load_applications_list();
        
    }    
    
    /**
     * The public method delete_api_application deletes an application
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function delete_api_application() {
        
        (new MidrubBaseAdminComponentsCollectionSettingsHelpers\Oauth)->delete_api_application();
        
    }
    
    /**
     * The public method manage_api_application get application's details
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function manage_api_application() {
        
        (new MidrubBaseAdminComponentsCollectionSettingsHelpers\Oauth)->manage_api_application();
        
    }    
 
}

/* End of file ajax.php */