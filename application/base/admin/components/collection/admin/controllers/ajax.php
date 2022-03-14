<?php
/**
 * Ajax Controller
 *
 * This file processes the Admin's ajax calls
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */

// Define the page namespace
namespace MidrubBase\Admin\Components\Collection\Admin\Controllers;

defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\Admin\Components\Collection\Admin\Helpers as MidrubBaseAdminComponentsCollectionAdminHelpers;

/*
 * Ajax class processes the admin component's ajax calls
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */
class Ajax {
    
    /**
     * Class variables
     *
     * @since 0.0.8.0
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.0
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

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
        (new MidrubBaseAdminComponentsCollectionAdminHelpers\Transactions)->load_payments_transactions();
        
    }

    /**
     * The public method delete_transaction deletes transaction by transaction's id
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function delete_transaction() {
        
        // Delete transaction
        (new MidrubBaseAdminComponentsCollectionAdminHelpers\Transactions)->delete_transaction();
        
    } 
    
    /**
     * The public method delete_transactions deletes transactions by transactions ids
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function delete_transactions() {
        
        // Delete transactions
        (new MidrubBaseAdminComponentsCollectionAdminHelpers\Transactions)->delete_transactions();
        
    } 

    /**
     * The public method change_transaction_status changes the transaction's status
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function change_transaction_status() {
        
        // Change status
        (new MidrubBaseAdminComponentsCollectionAdminHelpers\Transactions)->change_transaction_status();
        
    } 

    /**
     * The public method save_invoice_settings saves the invoice's template
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function save_invoice_settings() {
        
        // Save invoice's template
        (new MidrubBaseAdminComponentsCollectionAdminHelpers\Invoices)->save_invoice_settings();
        
    }
    
    /**
     * The public method load_payments_invoices loads the invoices
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function load_payments_invoices() {
        
        // Loads invoices by page
        (new MidrubBaseAdminComponentsCollectionAdminHelpers\Invoices)->load_payments_invoices();
        
    }

    /**
     * The public method delete_invoice deletes invoice by invoice's id
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function delete_invoice() {
        
        // Delete invoice
        (new MidrubBaseAdminComponentsCollectionAdminHelpers\Invoices)->delete_invoice();
        
    } 
    
    /**
     * The public method delete_invoices deletes invoices by invoices ids
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function delete_invoices() {
        
        // Delete invoices
        (new MidrubBaseAdminComponentsCollectionAdminHelpers\Invoices)->delete_invoices();
        
    }

    /**
     * The public method activate_theme activates a theme
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function activate_theme() {
        
        // Activate theme
        (new MidrubBaseAdminComponentsCollectionAdminHelpers\Themes)->activate();
        
    }

    /**
     * The public method deactivate_theme deactivates a theme
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function deactivate_theme() {
        
        // Deactivate theme
        (new MidrubBaseAdminComponentsCollectionAdminHelpers\Themes)->deactivate();
        
    }

    /**
     * The public method upload_theme uploads an theme to be installed
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function upload_theme() {
        
        // Uploads theme
        (new MidrubBaseAdminComponentsCollectionAdminHelpers\Themes)->upload_theme();
        
    }

    /**
     * The public method unzipping_theme_zip extract the theme from the zip
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function unzipping_theme_zip() {
        
        // Extract the theme
        (new MidrubBaseAdminComponentsCollectionAdminHelpers\Themes)->unzipping_zip();
        
    }

}

/* End of file ajax.php */