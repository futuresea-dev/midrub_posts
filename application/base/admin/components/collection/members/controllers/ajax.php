<?php
/**
 * Ajax Controller
 *
 * This file processes the Members's ajax calls
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
namespace MidrubBase\Admin\Components\Collection\Members\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\Admin\Components\Collection\Members\Helpers as MidrubBaseAdminComponentsCollectionMembersHelpers;

/*
 * Ajax class processes the Members component's ajax calls
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/midrub_cms Midrub’s License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.3
 */
class Ajax {
    
    /**
     * Class variables
     *
     * @since 0.0.8.3
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.3
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load the component's language files
        $this->CI->lang->load( 'members', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS );

    }

    /**
     * The public method members_save_member saves members
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function members_save_member() {
        
        // Save member
        (new MidrubBaseAdminComponentsCollectionMembersHelpers\Members)->members_save_member();
        
    }

    /**
     * The public method members_load_all loads members
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function members_load_all() {
        
        // Get members
        (new MidrubBaseAdminComponentsCollectionMembersHelpers\Members)->members_load_all();
        
    }

    /**
     * The public method members_delete_member deletes a member
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function members_delete_member() {
        
        // Delete member
        (new MidrubBaseAdminComponentsCollectionMembersHelpers\Members)->members_delete_member();
        
    }

    /**
     * The public method members_delete_members deletes members
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function members_delete_members() {
        
        // Delete members
        (new MidrubBaseAdminComponentsCollectionMembersHelpers\Members)->members_delete_members();
        
    }

    /**
     * The public method reload_members_dropdowns reloads the dropdown for members dropdowns
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function reload_members_dropdowns() {
        
        // Reloads dropdowns
        (new MidrubBaseAdminComponentsCollectionMembersHelpers\Plans)->reload_members_dropdowns();
        
    }

    /**
     * The public method load_payments_transactions loads payments transactions
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function load_payments_transactions() {
        
        // Get transactions
        (new MidrubBaseAdminComponentsCollectionMembersHelpers\Transactions)->load_payments_transactions();
        
    }

}

/* End of file ajax.php */