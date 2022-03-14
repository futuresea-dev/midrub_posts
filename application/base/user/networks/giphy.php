<?php
/**
 * Giphy Groups
 *
 * PHP Version 7.3
 *
 * Connects Giphy apps
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace MidrubBase\User\Networks;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Interfaces as MidrubBaseUserInterfaces;

/**
 * Giphy class - allows users to connect their Giphy's apps
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Giphy implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $CI;
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );

        // Load Base Model
        $this->CI->load->ext_model( APPPATH . 'base/models/', 'Base_model', 'base_model' );
        
    }
    
    /**
     * The public method check_availability checks if the Giphy api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        return true;
        
    }
    
    /**
     * The public method connect will redirect user to Giphy login page.
     * 
     * @return void
     */
    public function connect() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('app_name', 'App Name', 'trim|required');
            $this->CI->form_validation->set_rules('api_key', 'Api Key', 'trim|required');

            // Get post data
            $app_name = $this->CI->input->post('app_name', TRUE);
            $api_key = $this->CI->input->post('api_key', TRUE);

            // Verify if form data is valid
            if ($this->CI->form_validation->run() == false) {

                // Display the error popup
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_giphy_missing_app_name') . '</p>', false);
                

            } else {

                // Get updates
                $data = json_decode(get('http://api.giphy.com/v1/gifs/search?q=cat&api_key=' . $api_key), TRUE);

                // Verify if Giphy is valid
                if ( !empty($data['data']) ) {

                    // Verify if the account already exists
                    if ( $this->CI->networks->check_account_was_added('giphy', $api_key, $this->CI->user_id) ) {
                        
                        // Display the error popup
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_giphy_app_already_connected') . '</p>', false);
                                                
                    
                    } else {
                        
                        // Try to save app
                        if ( $this->CI->networks->add_network('giphy', $api_key, $api_key, $this->CI->user_id, '', $app_name, '') ) {

                            // Display the success message
                            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_giphy_app_was_connected') . '</p>', true); 
                            

                        } else {

                            // Display the error popup
                            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_giphy_app_was_not_connected') . '</p>', false);
                            
                            
                        }

                    }

                } else {

                    // Display the error popup
                    echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_giphy_missing_app_name') . '</p>', false);
                    

                }

            }

        } else {
            
            // Display the login form
            echo $this->CI->ecl('Social_login')->content('App Name', 'Api Key', 'Connect', $this->get_info(), 'giphy', '');            
            
        }
        
    }
    
    /**
     * The public method save will get access token.
     *
     * @param string $token contains the token for some social networks
     * 
     * @return boolean true or false
     */
    public function save($token = null) {
        
    }
    
    /**
     * The public method post publishes posts
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {}
    
    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network's data
     */
    public function get_info() {
        
        return array(
            'color' => '#6157ff',
            'icon' => '<i class="far fa-images"></i>',
            'api' => array(),
            'types' => array()
        );
        
    }
    
    /**
     * The public method preview generates a preview
     *
     * @param $args contains the img or url.
     * 
     * @return array with html content
     */
    public function preview($args) {}
    
}

/* End of file giphy.php */
