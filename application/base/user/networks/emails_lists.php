<?php
    
/**
 * Emails Lists
 *
 * PHP Version 7.3
 *
 * Connect and send emails
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
 * Emails_lists class - allows users to connect their emails lists and send emails.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Emails_lists implements MidrubBaseUserInterfaces\Networks {
    
    /**
     * Class variables
     */
    public $CI, $clientId, $clientSecret, $apiKey, $appName, $scriptUri;
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );

    }
    
    /**
     * The public method check_availability checks if the Facebook api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        return true;
        
    }
    
    /**
     * The public method connect the user emails lists
     *
     * @return void
     */
    public function connect() {
        
        $check = 0;
        
        // Get Emails lists
        $this->CI->db->select('lists_items.*, lists_items_meta.meta_value as name');
        $this->CI->db->from('lists_items');
        $this->CI->db->join('lists_items_meta', 'lists_items.list_id=lists_items_meta.list_id', 'LEFT');
        $this->CI->db->where(array(
            'lists_items.user_id' => $this->CI->user_id,
            'lists_items_meta.meta_name' => 'name'
        ));

        // Get request
        $query = $this->CI->db->get();

        // Items array
        $items = array();

        // Get connected lists
        $get_connected = $this->CI->base_model->get_data_where(
            'networks',
            'network_id, net_id',
            array(
                'network_name' => 'emails_lists',
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
        
        // Verify if results exists
        if ( $query->num_rows() > 0 ) {
            
            // Get results
            $results = $query->result_array();

            // List all results
            foreach ( $results as $list ) {

                // Verify if list_id already exists
                if ( isset($items[$list['list_id']]) ) {
                    continue;
                }

                // Set item
                $items[$list['list_id']] = array(
                    'net_id' => $list['list_id'],
                    'name' => $list['name'],
                    'label' => '',
                    'connected' => FALSE
                );

                // Verify if this account is connected
                if ( in_array($list['list_id'], $net_ids) ) {

                    // Set as connected
                    $items[$list['list_id']]['connected'] = TRUE;

                }

            }

            // Create the array which will provide the data
            $args = array(
                'title' => 'Emails Lists',
                'network_name' => 'emails_lists',
                'items' => $items,
                'connect' => $this->CI->lang->line('networks_emails_lists'),
                'callback' => site_url('user/callback/emails_lists'),
                'inputs' => array() 
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

            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_no_emails_lists_found') . '</p>', false);

        }
        
    }
    
    /**
     * The public method save will get access token.
     *
     * @param string $token contains the token for some social networks
     *
     * @return void
     */
    public function save($token = null) {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('net_ids', 'Net Ids', 'trim|required');

            // Get post data
            $net_ids = $this->CI->input->post('net_ids', TRUE);

            // Verify if form data is valid
            if ($this->CI->form_validation->run() === false) {

                // Count added lists
                $count = 0;

                // Get connected lists
                $get_connected = $this->CI->base_model->get_data_where(
                    'networks',
                    'network_id, net_id',
                    array(
                        'network_name' => 'emails_lists',
                        'user_id' => $this->CI->user_id
                    )

                );

                // Get Emails lists
                $this->CI->db->select('lists_items.*, lists_items_meta.meta_value as name');
                $this->CI->db->from('lists_items');
                $this->CI->db->join('lists_items_meta', 'lists_items.list_id=lists_items_meta.list_id', 'LEFT');
                $this->CI->db->where(array(
                    'lists_items.user_id' => $this->CI->user_id,
                    'lists_items_meta.meta_name' => 'name'
                ));

                // Get request
                $query = $this->CI->db->get();

                // Verify if user has connected accounts
                if ( $get_connected ) {

                    // List all connected accounts
                    foreach ( $get_connected as $connected ) {

                        // Verify if $net_ids is empty
                        if ( empty($net_ids) ) {

                            // Verify if emails lists
                            if ( $query->num_rows() > 0 ) {

                                // Get results
                                $results = $query->result_array();

                                // List all results
                                foreach ( $results as $list ) {

                                    // Verify if this list is connected
                                    if ( $list['list_id'] === (int)$connected['net_id'] ) {

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

                            // Verify if emails lists
                            if ( $query->num_rows() > 0 ) {

                                // Get results
                                $results = $query->result_array();

                                // List all results
                                foreach ( $results as $list ) {

                                    // Verify if user has selected this list
                                    if ( in_array($list['list_id'], $net_ids) ) {
                                        continue;
                                    }

                                    // Verify if this list is connected
                                    if ( $list['list_id'] === (int)$connected['net_id'] ) {

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

                    // Verify if results exists
                    if ( $query->num_rows() > 0 ) {

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

                        // Get results
                        $results = $query->result_array();
            
                        // List all results
                        foreach ( $results as $list ) {

                            // Verify if user has selected list
                            if ( !in_array($list['list_id'], $net_ids) ) {
                                continue;
                            }
                    
                            // Verify if the list was already added
                            if ( !$this->CI->networks->check_account_was_added('emails_lists', $list['list_id'], $this->CI->user_id) ) {
                                
                                // Save list
                                $this->CI->networks->add_network('emails_lists', $list['list_id'], $list['list_id'], $this->CI->user_id, '', $list['name'], '', '');

                                $count++;
                                
                                
                            }

                            // Verify if number of the lists was reached
                            if ( $count >= $network_accounts ) {
                                break;
                            }
                            
                        }

                        // Verify if the lists were saved
                        if ( $count ) {
                            
                            // Display the success popup
                            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_seleted_lists_were_saved') . '</p>', true);
                            exit();

                        }

                    }

                }

            }

        } else {

            // Get connected lists
            $get_connected = $this->CI->base_model->get_data_where(
                'networks',
                'network_id',
                array(
                    'network_name' => 'emails_lists',
                    'user_id' => $this->CI->user_id
                )

            );

            // Verify if user has connected accounts
            if ( $get_connected ) {

                // List all connected accounts
                foreach ( $get_connected as $connected ) {

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

        // Display the error popup
        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_seleted_lists_were_not_saved') . '</p>', false);

    }
    
    /**
     * The public method post publishes posts on Facebook Groups.
     *
     * @param array $args contains the post data
     * @param integer $user_id is the ID of the current user
     *
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {
        
        // Get user details
        if ($user_id) {
            
            // if the $user_id variable is not null, will be published a postponed post
            $user_details = $this->CI->networks->get_network_data('emails_lists', $user_id, $args['account']);
            
        } else {
            
            $user_id = $this->CI->user_id;
            $user_details = $this->CI->networks->get_network_data('emails_lists', $user_id, $args['account']);
            
        }

        // Use the base model for a simply sql query
        $get_list = $this->CI->base_model->get_data_where(
            'lists_items_meta',
            'lists_items_meta.*',
            array(
                'lists_items.list_id' => $user_details[0]->net_id,
                'lists_items.user_id' => $user_id
            ),
            array(),
            array(),
            array(array(
                'table' => 'lists_items',
                'condition' => 'lists_items_meta.list_id=lists_items.list_id',
                'join_from' => 'LEFT'
            ))
        );

        // Verify if the list exists
        if ( $get_list ) {

            // Params array
            $params = array(
                'list_id' => $user_details[0]->net_id,
                'name' => '',
                'subject' => '',
                'delay' => 0,
                'before' => '',
                'after' => '',
                'selected_account' => 0
            );

            // List all list's metas
            foreach ( $get_list as $list ) {

                // Verify if is the name
                if ( $list['meta_name'] === 'name' ) {

                    // Set new name's value
                    $params['name'] = $list['meta_value'];

                } else if ( $list['meta_name'] === 'subject' ) {

                    // Set new subject's value
                    $params['subject'] = $list['meta_value'];

                } else if ( $list['meta_name'] === 'delay' ) {

                    // Set new delay's value
                    $params['delay'] = (int)$list['meta_value'];

                } else if ( $list['meta_name'] === 'before' ) {

                    // Set new before value
                    $params['before'] = $list['meta_value'];

                } else if ( $list['meta_name'] === 'after' ) {

                    // Set new after value
                    $params['after'] = $list['meta_value'];

                } else if ( $list['meta_name'] === 'selected_account' ) {

                    // Set new selected_account value
                    $params['selected_account'] = $list['meta_value'];

                }

            }

            // Use the base model for a simply sql query
            $get_emails = $this->CI->base_model->get_data_where(
                'lists_items_emails',
                'email_id, email',
                array(
                    'list_id' => $user_details[0]->net_id
                )
            );

            // Set emails
            $params['emails'] = array();

            // Verify if emails exists
            if ( $get_emails ) {

                // List all emails
                foreach ( $get_emails as $get ) {

                    // Set email
                    $params['emails'][$get['email_id']] = $get['email'];

                }

            } else {

                // Save the error
                $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', 'The list is empty.' );

                // Then return false
                return false;

            }

            // Verify if the selected account exists
            if ( $params['selected_account'] ) {
            
                // Use the base model to verify if user is the owner of the account
                $get_account = $this->CI->base_model->get_data_where(
                    'networks',
                    '*',
                    array(
                        'network_id' => $params['selected_account'],
                        'user_id' => $user_id
                    )
                );

                // Verify if account exists
                if ( $get_account ) {

                    // Get the Gmail's client_id
                    $this->clientId = get_option('gmail_client_id');

                    // Get the Gmail's client_secret
                    $this->clientSecret = get_option('gmail_client_secret');

                    // Get the Gmail's api key
                    $this->apiKey = get_option('gmail_api_key');

                    // Get the Gmail's application name
                    $this->appName = get_option('gmail_google_application_name');

                    // Require the  vendor's libraries
                    require_once FCPATH . 'vendor/autoload.php';
                    require_once FCPATH . 'vendor/google/apiclient-services/src/Google/Service/Gmail.php';

                    // Gmail Callback
                    $this->scriptUri = base_url() . 'user/callback/gmail';

                    // Call the class Google_Client
                    $this->client = new \Google_Client();

                    // Name of the google application
                    $this->client->setApplicationName($this->appName);

                    // Set the client_id
                    $this->client->setClientId($this->clientId);

                    // Set the client_secret
                    $this->client->setClientSecret($this->clientSecret);

                    // Redirects to same url
                    $this->client->setRedirectUri($this->scriptUri);

                    // Set the api key
                    $this->client->setDeveloperKey($this->apiKey);

                    // Default token
                    $token = '';

                    // Get response
                    $response = json_decode(post('https://www.googleapis.com/oauth2/v4/token', array('client_id' => $this->clientId, 'client_secret' => $this->clientSecret, 'refresh_token' => $get_account[0]['secret'], 'grant_type' => 'refresh_token')), true);

                    // Verify if response exists
                    if ( $response ) {

                        // Set token
                        $token = $response['access_token'];

                    }

                    // Set access token
                    $this->client->setAccessToken($token);

                    // Email from
                    $email_from = $this->CI->config->item('contact_mail');

                    // Get user information
                    $user_data = json_decode(get('https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $response['access_token']), true);

                    // Verify if user data exists
                    if (isset($user_data['email'])) {
                        $email_from = $user_data['email'];
                    }

                    // Set subject
                    $subject = empty($params['subject'])?$params['name']:$params['subject'];

                    // Set service
                    $service = new \Google_Service_Gmail($this->client);

                    // Count sender
                    $count_sender = 0;

                    // Verify if emails exists
                    if ( $params['emails'] ) {

                        // Prepare the history
                        $history_args = array(
                            'user_id' => $this->CI->user_id,
                            'list_id' => $user_details[0]->net_id,
                            'created' => time()
                        );

                        // Save the history by using the Base's Model
                        $history_id = $this->CI->base_model->insert('lists_history', $history_args);

                        // List all emails
                        foreach ( $params['emails'] as $email_id => $email ) {
                        
                            $boundary = uniqid(rand(), true);
                            $subjectCharset = $charset = 'utf-8';
                            $strSubject = $subject;

                            $strRawMessage = "To: " . $email . "\r\n";
                            $strRawMessage .= "From: " . $user_data['name'] . "<" . $email_from . ">" . "\r\n";

                            $strRawMessage .= 'Subject: =?' . $subjectCharset . '?B?' . base64_encode($strSubject) . "?=\r\n";
                            $strRawMessage .= 'MIME-Version: 1.0' . "\r\n";
                            $strRawMessage .= 'Content-type: Multipart/Alternative; boundary="' . $boundary . '"' . "\r\n";

                            $strRawMessage .= "\r\n--{$boundary}\r\n";
                            $strRawMessage .= 'Content-Type: text/plain; charset=' . $charset . "\r\n";
                            $strRawMessage .= 'Content-Transfer-Encoding: 7bit' . "\r\n\r\n";
                            $strRawMessage .= $subject . "\r\n";

                            $strRawMessage .= "--{$boundary}\r\n";
                            $strRawMessage .= 'Content-Type: text/html; charset=' . $charset . "\r\n";
                            $strRawMessage .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";

                            // Verify if before content exists
                            if ( $params['before'] ) {

                                $strRawMessage .= $params['before'] . "\r\n";

                            }

                            $strRawMessage .= $args['post'] . "\r\n";

                            // Verify if after content exists
                            if ( $params['after'] ) {

                                $strRawMessage .= $params['after'] . "\r\n";

                            }

                            try {

                                $mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
                                $msg = new \Google_Service_Gmail_Message();
                                $msg->setRaw($mime);
                                $service->users_messages->send('me', $msg);
                                $count_sender++;

                                // Prepare the email's history
                                $email_args = array(
                                    'history_id' => $history_id,
                                    'email_id' => $email_id,
                                    'status' => 1
                                );

                                // Save the email history by using the Base's Model
                                $this->CI->base_model->insert('lists_history_emails', $email_args);

                            } catch (Exception $e) {
                                
                                // Prepare the email's history
                                $email_args = array(
                                    'history_id' => $history_id,
                                    'email_id' => $email_id,
                                    'status' => 2,
                                    'error' => $e->getMessage()
                                );

                                // Save the email history by using the Base's Model
                                $this->CI->base_model->insert('lists_history_emails', $email_args);                                
                                
                            }

                            // Verify if delay exists
                            if ( $params['delay'] ) {

                                // Get the user's plan
                                $plan_id = get_user_option('plan', $user_id);
            
                                // Verify if plan's delay exists
                                if ( plan_feature('lists_sending_delay', $plan_id) ) {

                                    // Set sleep
                                    sleep(plan_feature('lists_sending_delay', $plan_id));

                                }
            
                            }

                        }

                    }

                    // Verify if at least one email was sent
                    if ( $count_sender ) {
                        return true;
                    } else {
                        return false;
                    }

                }

            } else {

                // Save the error
                $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', 'No selected account in the list.' );

                // Then return false
                return false;

            }

        } else {

            // Save the error
            $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', 'The list was not found.' );

            // Then return false
            return false;

        }

        // Then return false
        return false;        
        
    }
    
    /**
     * The public method get_info displays information about this class
     *
     * @return array with network data
     */
    public function get_info() {
        
        return array(
            'custom_connect' => $this->CI->lang->line('networks_connect_lists'),
            'color' => '#339af0',
            'icon' => '<i class="fas fa-at"></i>',
            'api' => array(),
            'types' => array('post', 'rss')
        );
        
    }
    
    /**
     * The public method preview generates a preview for Facebook Ad Labels
     *
     * @param $args contains the img or url
     *
     * @return array with html content
     */
    public function preview($args) {}
    
}

/* End of file emails_lists.php */
