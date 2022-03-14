<?php
/**
 * Pinterest
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Pinterest
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

// If session valiable doesn't exists will be called
if ( !isset( $_SESSION) ) {
    session_start();
}

/**
 * Pinterest class - allows users to connect to their Pinterest Account and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Pinterest implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $app_id, $app_secret, $pinterest, $loginurl;
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Require the Pinterest Library
        require_once FCPATH . 'vendor/autoload.php';
        
        // Verify if is enabled the option for App Id and App Secret
        if ( !get_option('pinterest_user_api_key') ) {
            
            // Get Pinterest app_id
            $this->app_id = get_option('pinterest_app_id');
            
            // Get Pinterest app_secret
            $this->app_secret = get_option('pinterest_app_secret');
            
            // Call the Pinterest class
            $this->pinterest = new \DirkGroenen\Pinterest\Pinterest($this->app_id, $this->app_secret);
            
        }

        // Load Base Model
        $this->CI->load->ext_model( APPPATH . 'base/models/', 'Base_model', 'base_model' );
        
    }
    
    /**
     * The public method check_availability checks if the Pinterest api is configured correctly.
     *
     * @return boolean true or empty
     */
    public function check_availability() {
        
        // Verify if is enabled the option for App Id and App Secret
        if ( get_option( 'pinterest_user_api_key' ) ) {
            
            return true;
            
        } else {
            
            if ( ( $this->app_id == '' ) AND ( $this->app_secret == '' ) ) {
                
                return false;
                
            } else {
                
                return true;
                
            }
        
        }
        
    }
    
    /**
     * The public method connect will redirect user to Pinterest login page.
     * 
     * @return void
     */
    public function connect() {
        
        // Verify if is enabled the option for App Id and App Secret
        if (get_option('pinterest_user_api_key')) {
            
            // Check if data was submitted
            if ($this->CI->input->post()) {
                
                // Add form validation
                $this->CI->form_validation->set_rules('app_id', 'App ID', 'trim|required');
                $this->CI->form_validation->set_rules('app_secret', 'App Secret', 'trim|required');
                
                // Get post data
                $app_id = $this->CI->input->post('app_id');
                $app_secret = $this->CI->input->post('app_secret');
                
                // Verify if form data is valid
                if ($this->CI->form_validation->run() == false) {
                    
                    display_mess(45);
                    
                } else {
                    
                    $_SESSION['app_id'] = $app_id;
                    $_SESSION['app_secret'] = $app_secret;
                    
                }
                
            }
            
            if ( !isset($_SESSION['app_id']) ) {
                
                echo get_instance()->ecl('Social_login')->content('App ID', 'App Secret', 'Connect', $this->get_info(), 'pinterest', $this->CI->lang->line('networks_connect_pinterest'));

            } else {
                
                // Call the Pinterest class
                $this->pinterest = new \DirkGroenen\Pinterest\Pinterest($_SESSION['app_id'], $_SESSION['app_secret']);
                $this->loginurl = $this->pinterest->auth->getLoginUrl(str_replace(['http://', 'http://www.'], ['https://', 'https://www.'], base_url()) . 'user/callback/pinterest', array('read_public,write_public'));
                header('Location:' . $this->loginurl); 
                
            }
            
        } else {
            
            // Get redirect url
            $this->loginurl = $this->pinterest->auth->getLoginUrl(str_replace(['http://', 'http://www.'], ['https://', 'https://www.'], base_url()) . 'user/callback/pinterest', array('read_public,write_public'));
            
            // Redirect
            header('Location:' . $this->loginurl);            
            
        }
        
    }
    
    /**
     * The public method save will get access token.
     *
     * @param string $token contains the token for some social networks
     */
    public function save($token = NULL) {

        // Check if data was submitted
        if ($this->CI->input->post()) {
        
            // Define the callback status
            $check = 0;

            // Add form validation
            $this->CI->form_validation->set_rules('token', 'Token', 'trim|required');
            $this->CI->form_validation->set_rules('net_ids', 'Net Ids', 'trim|required');

            // Get post data
            $token = $this->CI->input->post('token', TRUE);
            $net_ids = $this->CI->input->post('net_ids', TRUE);

            // Verify if form data is valid
            if ($this->CI->form_validation->run() == false) {

                // Get connected accounts
                $get_connected = $this->CI->base_model->get_data_where(
                    'networks',
                    'network_id, net_id',
                    array(
                        'network_name' => 'pinterest',
                        'user_id' => $this->CI->user_id
                    )

                );

                // Verify if user has connected accounts
                if ( $get_connected ) {

                    // List all connected accounts
                    foreach ( $get_connected as $connected ) {

                        // Verify if $net_ids is empty
                        if ( empty($net_ids) ) {

                            // Delete the account
                            if ( $this->CI->base_model->delete( 'networks', array( 'network_id' => $connected['network_id'] ) ) ) {

                                // Delete all account's records
                                md_run_hook(
                                    'delete_network_account',
                                    array(
                                        'account_id' => $connected['network_id']
                                    )
                                    
                                );

                            }

                            continue;
                            
                        }

                        // Verify if this account is still connected
                        if ( !in_array($connected['net_id'], $net_ids) ) {

                            // Delete the account
                            if ( $this->CI->base_model->delete( 'networks', array( 'network_id' => $connected['network_id'] ) ) ) {

                                // Delete all account's records
                                md_run_hook(
                                    'delete_network_account',
                                    array(
                                        'account_id' => $connected['network_id']
                                    )
                                    
                                );

                            }

                        }

                    }

                }

                // Verify if net ids is not empty
                if ( $net_ids ) {

                    // Get my boards
                    $boards = json_decode(get('https://api.pinterest.com/v1/me/boards/?access_token=' . $token), TRUE);

                    // Verify if boards exists
                    if ( empty($boards['data']) ) {

                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_no_pinterest_boards_connected') . '</p>', false);
                        exit();

                    }

                    // Calculate expire token period
                    $expires = '';

                    // Get the user's plan
                    $user_plan = get_user_option( 'plan');

                    // Get plan's data
                    $get_plan = $this->CI->base_model->get_data_where(
                        'plans',
                        'network_accounts',
                        array(
                            'plan_id' => $user_plan
                        )

                    );

                    // Set network's accounts
                    $network_accounts = 0;                        

                    // Verify if plan's data exists
                    if ( $get_plan ) {

                        // Set network's accounts
                        $network_accounts = $get_plan[0]['network_accounts'];

                    }
                    
                    // Save boards
                    for ( $y = 0; $y < count($boards['data']); $y++ ) {

                        // Get board's slug
                        $board_slug = urldecode(str_replace('https://www.pinterest.com/', '', $boards['data'][$y]['url']));

                        // Verify if user has selected this board
                        if ( !in_array($board_slug, $net_ids) ) {
                            continue;
                        }
                        
                        // Save company
                        if ( $this->CI->networks->add_network('pinterest', $board_slug, $token, $this->CI->user_id, $expires, $boards['data'][$y]['name'], '', $token) ) {
                            $check++;
                        }

                        // Verify if number of the companies was reached
                        if ( $check >= $network_accounts ) {
                            break;
                        }
                        
                    }

                }

            }

            if ( $check > 0 ) {
                
                // Display the success message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_all_pinterest_boards_connected') . '</p>', true); 
                
            } else {
                
                // Display the error message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_no_pinterest_boards_connected') . '</p>', false);             
                
            }

        } else {
        
            // Verify if code exists
            if ( $this->CI->input->get('code', TRUE) ) {
                
                // Verify if is enabled the option for App Id and App Secret
                if ( get_option('pinterest_user_api_key') ) {
                    
                    // Verify if session app_id exists
                    if ( isset($_SESSION['app_id']) ) {
                        
                        // Call the Pinterest class
                        $this->pinterest = new \DirkGroenen\Pinterest\Pinterest($_SESSION['app_id'], $_SESSION['app_secret']);
                        
                    } else {
                        
                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);   
                        exit();
                        
                    }
                    
                }
                
                // Get Pinterest access token
                $token = $this->pinterest->auth->getOAuthToken( $this->CI->input->get('code', TRUE) );
                
                // Set access token
                $this->pinterest->auth->setOAuthToken($token->access_token);
                
                // Get user data
                $me = $this->pinterest->users->me();
                
                // Get access token
                if ( $token->access_token ) {

                    // Get my boards
                    $boards = json_decode(get('https://api.pinterest.com/v1/me/boards/?access_token=' . $token->access_token), TRUE);

                    // Verify if boards exists
                    if ( empty($boards['data']) ) {

                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_no_pinterest_boards_connected') . '</p>', false);
                        exit();

                    }
                    
                    // Items array
                    $items = array();

                    // Get Linkedin Companies
                    $get_connected = $this->CI->base_model->get_data_where(
                        'networks',
                        'net_id',
                        array(
                            'network_name' => 'pinterest',
                            'user_id' => $this->CI->user_id
                        )

                    );

                    // Net Ids array
                    $net_ids = array();

                    // Verify if user has Linkedin Companies
                    if ( $get_connected ) {

                        // List all Linkedin Companies
                        foreach ( $get_connected as $connected ) {

                            // Set net's id
                            $net_ids[] = $connected['net_id'];

                        }

                    }

                    // List all boards
                    foreach ($boards['data'] as $board) {

                        // Get board's slug
                        $board_slug = str_replace('https://www.pinterest.com/', '', $board['url']);

                        // Set item
                        $items[$board_slug] = array(
                            'net_id' => $board_slug,
                            'name' => $board['name'],
                            'label' => '',
                            'connected' => FALSE
                        );

                        // Verify if this Board is connected
                        if ( in_array($board_slug, $net_ids) ) {

                            // Set as connected
                            $items[$board_slug]['connected'] = TRUE;

                        }

                    }

                    // Create the array which will provide the data
                    $args = array(
                        'title' => 'Pinterest',
                        'network_name' => 'pinterest',
                        'items' => $items,
                        'connect' => $this->CI->lang->line('networks_boards'),
                        'callback' => site_url('user/callback/pinterest'),
                        'inputs' => array(
                            array(
                                'token' => $token->access_token
                            )
                        ) 
                    );

                    // Get the user's plan
                    $user_plan = get_user_option( 'plan');

                    // Get plan's data
                    $get_plan = $this->CI->base_model->get_data_where(
                        'plans',
                        'network_accounts',
                        array(
                            'plan_id' => $user_plan
                        )

                    );

                    // Verify if plan's data exists
                    if ( $get_plan ) {

                        // Set network's accounts
                        $args['network_accounts'] = $get_plan[0]['network_accounts'];

                    } else {

                        // Set network's accounts
                        $args['network_accounts'] = 0;

                    }

                    // Set the number of the connected accounts
                    $args['connected_accounts'] = count($net_ids);

                    // Load the list
                    $this->CI->load->view('social/list', $args);
                    
                }
                
            } else {
                
                // Display the error message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);   
                
            }

        }
        
    }
    
    /**
     * The public method post publishes posts on Pinterest.
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post($args, $user_id = NULL) {
        
        // Verify if user_id exists
        if ( $user_id ) {
            
            // Get account details
            $user_details = $this->CI->networks->get_network_data('pinterest', $user_id, $args['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $user_details = $this->CI->networks->get_network_data('pinterest', $user_id, $args['account']);
            
        }
        
        // Verify if image exists
        if ( !$args['img'] ) {
            
            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($this->CI->lang->line('a_photo_is_required_to_publish_here')));

            // Then return false
            return false;
            
        }
        
        // Get user details
        if ($user_details) {
            
            // Verify if is enabled the option for App Id and App Secret
            if ( get_option('pinterest_user_api_key') ) {
                
                    // Call the Pinterest class
                    $this->pinterest = new \DirkGroenen\Pinterest\Pinterest( $user_details[0]->api_key, $user_details[0]->api_secret );
                
            }
            
            try {
                
                // Set access token
                $this->pinterest->auth->setOAuthToken($user_details[0]->token);
                
                // Create the post content
                $data = array(
                    'image_url' => $args['img'][0]['body'],
                    'board' => urldecode($user_details[0]->net_id)
                );
                
                // Set post's data
                $post = $args['post'];
                
                // Verify if url exists
                if ( $args['url'] ) {
                    $post = str_replace($args['url'], ' ', $post);
                    $data['link'] = short_url($args['url']);
                }
                
                // Prepare the post
                $data['note'] = mb_substr($post, 0, 499);

                $response = $this->pinterest->request->post('pins/', $data);

                // Verify if the pin was created
                if ( $response->data['id'] ) {
                    
                    return true;
                    
                } else {
                    
                    return false;
                    
                }
                
                
            } catch (Exception $e) {

                // Save the error
                $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', $e->getMessage());

                // Then return falsed
                return false;

            }
            
        }
        
    }
    
    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network's data
     */
    public function get_info() {

        $checked = '';

        if ( get_option('pinterest_user_api_key') ) {
            $checked = ' checked';
        }
        
        return array(
            'color' => '#be000f',
            'icon' => '<i class="fab fa-pinterest"></i>',
            'api' => array('app_id', 'app_secret'),
            'types' => array('post', 'rss'),
            'extra_content' => '<div class="form-group">'
                    . '<div class="row">'
                        . '<div class="col-lg-10 col-xs-6">'
                            . '<label for="menu-item-text-input">'
                                . 'Any user will use his App ID and App Secret'
                            . '</label>'
                        . '</div>'
                    . '<div class="col-lg-2 col-xs-6">'
                        . '<div class="checkbox-option pull-right">'
                            . '<input id="pinterest_user_api_key" name="pinterest_user_api_key" class="social-option-checkbox" type="checkbox" ' . $checked . '>'
                            . '<label for="pinterest_user_api_key"></label>'
                        . '</div>'
                    . '</div>'
                . '</div>'
            . '</div>'
        );
        
    }
    
    /**
     * The public method preview generates a preview for Pinterest.
     *
     * @param $args contains the img or url.
     * 
     * @return array with html content
     */
    public function preview($args) {
    }
    
}

/* End of file pinterest.php */
