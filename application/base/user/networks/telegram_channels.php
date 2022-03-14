<?php
/**
 * Telegram Channel
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Telegram Channels
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
 * Telegram_channels class - allows users to connect to their Telegram Channels and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Telegram_channels implements MidrubBaseUserInterfaces\Networks {

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
     * The public method check_availability checks if the Telegram api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        return true;
        
    }
    
    /**
     * The public method connect will redirect user to Telegram login page.
     * 
     * @return void
     */
    public function connect() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('api_key', 'App Key', 'trim|required');

            // Get post data
            $api_key = $this->CI->input->post('api_key');

            // Verify if form data is valid
            if ($this->CI->form_validation->run() == false) {

                // Display the error popup
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);

            } else {
                
                // Get updates
                $data = json_decode(get('https://api.telegram.org/bot' . $api_key . '/getUpdates?limit=1000'), TRUE);

                // Verify if updates exists
                if ( !empty($data['result']) ) {
                    
                    // Items array
                    $items = array();

                    // Get connected accounts
                    $get_connected = $this->CI->base_model->get_data_where(
                        'networks',
                        'net_id',
                        array(
                            'network_name' => 'telegram_channels',
                            'user_id' => $this->CI->user_id
                        )

                    );

                    // Net Ids array
                    $net_ids = array();

                    // Verify if user has connected accounts
                    if ( $get_connected ) {

                        // List all connected accounts
                        foreach ( $get_connected as $connected ) {

                            // Set net's id
                            $net_ids[] = $connected['net_id'];

                        }

                    }

                    // List all results
                    foreach ( $data['result'] as $result ) {

                        // Verify if the channel post exists
                        if ( !isset($result['channel_post']) ) {
                            continue;
                        }

                        // Verify if chat_id already exists
                        if ( isset($items[$result['channel_post']['chat']['id']]) ) {
                            continue;
                        }

                        // Set item
                        $items[$result['channel_post']['chat']['id']] = array(
                            'net_id' => $result['channel_post']['chat']['id'],
                            'name' => $result['channel_post']['chat']['title'],
                            'label' => '',
                            'connected' => FALSE
                        );

                        // Verify if this account is connected
                        if ( in_array($result['channel_post']['chat']['id'], $net_ids) ) {

                            // Set as connected
                            $items[$result['channel_post']['chat']['id']]['connected'] = TRUE;

                        }

                    }

                    // Create the array which will provide the data
                    $args = array(
                        'title' => 'Telegram Channels',
                        'network_name' => 'telegram_channels',
                        'items' => $items,
                        'connect' => $this->CI->lang->line('networks_channels'),
                        'callback' => site_url('user/callback/telegram_channels'),
                        'inputs' => array(
                            array(
                                'api_key' => $api_key
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

                } else {
                    
                    // Display the error popup
                    echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_no_channels_were_connected') . '</p>', false);

                }

            }

        } else {
            
            // Display the login form
            echo get_instance()->ecl('Social_login')->content('Api Key', '', 'Connect', $this->get_info(), 'telegram_channels', '');            
            
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
        
        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('api_key', 'Api Key', 'trim|required');
            $this->CI->form_validation->set_rules('net_ids', 'Net Ids', 'trim|required');

            // Get post data
            $api_key = $this->CI->input->post('api_key', TRUE);
            $net_ids = $this->CI->input->post('net_ids', TRUE);

            // Verify if form data is valid
            if ($this->CI->form_validation->run() == false) {

                // Count added channels
                $count = 0;

                // Get updates
                $data = json_decode(get('https://api.telegram.org/bot' . $api_key . '/getUpdates?limit=1000'), TRUE);

                // Get connected accounts
                $get_connected = $this->CI->base_model->get_data_where(
                    'networks',
                    'network_id, net_id',
                    array(
                        'network_name' => 'telegram_channels',
                        'user_id' => $this->CI->user_id
                    )

                );

                // Verify if user has connected accounts
                if ( $get_connected ) {

                    // List all connected accounts
                    foreach ( $get_connected as $connected ) {

                        // Verify if $net_ids is empty
                        if ( empty($net_ids) ) {

                            // Verify if user has channels
                            if ( !empty($data['result']) ) {

                                // List all found chats
                                foreach ( $data['result'] as $result ) {

                                    // Verify if the channel post exists
                                    if ( !isset($result['channel_post']) ) {
                                        continue;
                                    }

                                    // Verify if this channel is connected
                                    if ( $result['channel_post']['chat']['id'] === (int)$connected['net_id'] ) {

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

                            continue;
                            
                        }

                        // Verify if this account is still connected
                        if ( !in_array($connected['net_id'], $net_ids) ) {

                            // Verify if user has channels
                            if ( !empty($data['result']) ) {

                                // List all found chats
                                foreach ( $data['result'] as $result ) {

                                    // Verify if the channel post exists
                                    if ( !isset($result['channel_post']) ) {
                                        continue;
                                    }

                                    // Verify if user has selected this channel
                                    if ( in_array($result['channel_post']['chat']['id'], $net_ids) ) {
                                        continue;
                                    }

                                    // Verify if this channel is connected
                                    if ( $result['channel_post']['chat']['id'] === (int)$connected['net_id'] ) {

                                        // Delete the account
                                        if ( $this->CI->base_model->delete( 'networks', array( 'network_id' => $connected['network_id'] ) ) ) {

                                            $count++;

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

                        }

                    }

                }

                // Verify if net ids is not empty
                if ( $net_ids ) {

                    // Verify if updates exists
                    if ( !empty($data['result']) ) {

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

                        // List all found chats
                        foreach ( $data['result'] as $result ) {

                            // Verify if the channel post exists
                            if ( !isset($result['channel_post']) ) {
                                continue;
                            }

                            // Set chat's ID
                            $chat_id = $result['channel_post']['chat']['id'];

                            // Verify if user has selected this group
                            if ( !in_array($chat_id, $net_ids) ) {
                                continue;
                            }
                            
                            // Set chat's title
                            $title = $result['channel_post']['chat']['title'];
                    
                            // Verify if account was already added
                            if ( !$this->CI->networks->check_account_was_added('telegram_channels', $chat_id, $this->CI->user_id) ) {
                                
                                // Save group
                                $this->CI->networks->add_network('telegram_channels', $chat_id, $api_key, $this->CI->user_id, '', $title, '', '');

                                $count++;
                                
                                
                            }

                            // Verify if number of the channels was reached
                            if ( $count >= $network_accounts ) {
                                break;
                            }
                            
                        }

                        // Verify if channels were added
                        if ( $count ) {
                            
                            // Display the success popup
                            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_all_channels_were_connected') . '</p>', true);
                            exit();

                        }

                    }

                }

            }

        }

        // Display the error popup
        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_no_channels_were_connected') . '</p>', false);
        
    }
    
    /**
     * The public method post publishes posts on Telegram Channels.
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {
        
        // Verify if user_id exists
        if ( $user_id ) {
            
            // Get account details
            $user_details = $this->CI->networks->get_network_data('telegram_channels', $user_id, $args['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $user_details = $this->CI->networks->get_network_data('telegram_channels', $user_id, $args['account']);
            
        }
        
        // Get post's data
        $post = $args['post'];
        
        // Verify if title is not empty
        if( $args['title'] ) {
            
            $post = $args['title']. ' '. $post;
            
        }
        
        if ( $args['img'] ) {

            // Verify if url is empty
            if ( trim($args['url']) ) {
                
                $post = str_replace($args['url'], '', mb_substr($post, 0, 850) ) . ' ' . short_url($args['url']);

            } else {

                $post = mb_substr($post, 0, 850);                

            }

            // Replace unsupported data
            $post = str_replace(
                array('<b>', '</b>', '<strong>', '</strong>', '<i>', '</i>', '<em>', '</em>', '<code>', '</code>', '<pre>', '</pre>', '”', '_'),
                array('*', '*', '*', '*', '_', '_', '`', '`', '```', '```', '```', '```', '"', '\\_'),
                $post
            );
        
            // Publish details
            $params = [
                'chat_id' => $user_details[0]->net_id,
                'photo' => $args['img'][0]['body'],
                'caption' => $post,
                'parse_mode' => 'Markdown'
            ];        

            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.telegram.org/bot' . $user_details[0]->token . '/sendPhoto?' . http_build_query($params),
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: text/xml; charset=utf-8"
                ),
                CURLOPT_RETURNTRANSFER => true
            ));
            
            $result = curl_exec($curl);
            
            curl_close($curl);
        
        } else {

            // Verify if url is empty
            if ( trim($args['url']) ) {
                
                $post = str_replace($args['url'], '', mb_substr($post, 0, 1200) ) . ' ' . short_url($args['url']);

            } else {

                $post = mb_substr($post, 0, 1200);                

            }    

            // Replace unsupported data
            $post = str_replace(
                array('<b>', '</b>', '<strong>', '</strong>', '<i>', '</i>', '<em>', '</em>', '<code>', '</code>', '<pre>', '</pre>', '”', '_'),
                array('*', '*', '*', '*', '_', '_', '`', '`', '```', '```', '```', '```', '"', '\\_'),
                $post
            );
            
            // Publish details
            $params = array(
                'chat_id' => $user_details[0]->net_id,
                'text' => $post,
                'parse_mode' => 'Markdown'
            );        

            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.telegram.org/bot' . $user_details[0]->token . '/sendMessage?' . http_build_query($params),
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: text/xml; charset=utf-8"
                ),
                CURLOPT_RETURNTRANSFER => true
            ));
            
            $result = curl_exec($curl);
            
            curl_close($curl);
            
        }
        
        // Decode response
        $publish = json_decode($result);

        // Verify if the post was published
        if ( !empty($publish->ok) ) {
            
            return true;
            
        } else {
            
            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($publish) );
            
        }
        
    }
    
    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network's data
     */
    public function get_info() {
        
        return array(
            'color' => '#5682a3',
            'icon' => '<i class="fab fa-telegram-plane"></i>',
            'api' => array(),
            'types' => array('post', 'rss')
        );
        
    }
    
    /**
     * The public method preview generates a preview for Telegram Channels.
     *
     * @param $args contains the img or url.
     * 
     * @return array with html content
     */
    public function preview($args) {
        
    }
    
}

/* End of file telegram_channels.php */
