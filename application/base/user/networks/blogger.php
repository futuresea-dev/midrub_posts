<?php
/**
 * Blogger
 *
 * PHP Version 7.3
 *
 * Connect and Publish to Blogger
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
 * Blogger class - allows users to connect to their Blogger blogs and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Blogger implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $connect, $client, $CI, $clientId, $clientSecret, $apiKey, $appName, $scriptUri;

    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get the Blogger's client_id
        $this->clientId = get_option('blogger_client_id');
        
        // Get the Blogger's client_secret
        $this->clientSecret = get_option('blogger_client_secret');
        
        // Get the Blogger's api key
        $this->apiKey = get_option('blogger_api_key');
        
        // Get the Blogger's application name
        $this->appName = get_option('blogger_google_application_name');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Verify if the class Google_Client was already called
        if ( !class_exists('Google_Client', false ) ) {
            require_once FCPATH . 'vendor/google/src/Google_Client.php';
        }
        
        // Require Blog Services
        require_once FCPATH . 'vendor/google/src/contrib/Google_BloggerService.php';
        
        // Blogger Callback
        $this->scriptUri = site_url('user/callback/blogger');

        // Load Base Model
        $this->CI->load->ext_model( APPPATH . 'base/models/', 'Base_model', 'base_model' );
        
    }

    /**
     * The public method check_availability checks if the Blogger api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        // Verify if clientId, clientSecret and apiKey exists
        if ( ($this->clientId != '') AND ( $this->clientSecret != '') AND ( $this->apiKey != '') ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }

    /**
     * The public method connect will redirect user to Google login page.
     * 
     * @return void
     */
    public function connect() {
        
        // Call the class Google_Client
        $this->client = new \Google_Client();
        
        // Offline because we need to get refresh token
        $this->client->setAccessType('offline');
        
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
        
        // Load required scopes
        $this->client->setScopes(array('https://www.googleapis.com/auth/blogger https://www.googleapis.com/auth/userinfo.profile'));
        
        // Get the redirect url
        $authUrl = $this->client->createAuthUrl();
        
        // Redirect
        header('Location:' . $authUrl);
        
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
        
            // Define the callback status
            $check = 0;

            // Add form validation
            $this->CI->form_validation->set_rules('token', 'Token', 'trim|required');
            $this->CI->form_validation->set_rules('secret', 'Secret', 'trim|required');
            $this->CI->form_validation->set_rules('net_ids', 'Net Ids', 'trim|required');

            // Get post data
            $token = $this->CI->input->post('token', TRUE);
            $secret = $this->CI->input->post('secret', TRUE);
            $net_ids = $this->CI->input->post('net_ids', TRUE);

            // Verify if form data is valid
            if ($this->CI->form_validation->run() == false) {

                // Get blogs
                $curl = curl_init();
                
                // Set some options - we are passing in a useragent too here
                curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'https://www.googleapis.com/blogger/v3/users/self/blogs?fetchUserInfo=true&role=ADMIN&view=ADMIN&fields=blogUserInfos%2Citems&access_token=' . $token, CURLOPT_HEADER => false));
                
                // Send the request & save response to $resp
                $blogs = curl_exec($curl);
                
                // Close request to clear up some resources
                curl_close($curl);

                // Get connected accounts
                $get_connected = $this->CI->base_model->get_data_where(
                    'networks',
                    'network_id, net_id',
                    array(
                        'network_name' => 'blogger',
                        'user_id' => $this->CI->user_id
                    )

                );

                // Verify if user has connected accounts
                if ( $get_connected ) {

                    // List all connected accounts
                    foreach ( $get_connected as $connected ) {

                        // Verify if $net_ids is empty
                        if ( empty($net_ids) ) {

                            // Decode response
                            $getBlogs = json_decode($blogs, true);

                            // Verify if user has blogs
                            if ( !empty($getBlogs['blogUserInfos'][0]['blog']['id']) ) {

                                // List blogs
                                for ( $y = 0; $y < count($getBlogs['blogUserInfos']); $y++ ) {

                                    // Verify if this blog is connected
                                    if ( $getBlogs['blogUserInfos'][$y]['blog']['id'] === $connected['net_id'] ) {

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

                            // Decode response
                            $getBlogs = json_decode($blogs, true);

                            // Verify if user has blogs
                            if ( !empty($getBlogs['blogUserInfos'][0]['blog']['id']) ) {

                                // List blogs
                                for ( $y = 0; $y < count($getBlogs['blogUserInfos']); $y++ ) {

                                    // Verify if user has selected this blog
                                    if ( in_array($getBlogs['blogUserInfos'][$y]['blog']['id'], $net_ids) ) {
                                        continue;
                                    }

                                    // Verify if this blog is connected
                                    if ( $getBlogs['blogUserInfos'][$y]['blog']['id'] === $connected['net_id'] ) {

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

                        }

                    }

                }

                // Verify if net ids is not empty
                if ( $net_ids ) {
                    
                    // Verify if user has blogs
                    if ( $blogs ) {

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

                        // Decode response
                        $getBlogs = json_decode($blogs, true);

                        // If user has blogs will save them
                        if ( !empty($getBlogs['blogUserInfos'][0]['blog']['id']) ) {
    
                            // List blogs
                            for ( $y = 0; $y < count($getBlogs['blogUserInfos']); $y++ ) {

                                // Verify if user has selected this blog
                                if ( !in_array($getBlogs['blogUserInfos'][$y]['blog']['id'], $net_ids) ) {
                                    continue;
                                }

                                // Save company
                                if ( $this->CI->networks->add_network('blogger', $getBlogs['blogUserInfos'][$y]['blog']['id'], $token, $this->CI->user_id, $expires, $getBlogs['blogUserInfos'][$y]['blog']['name'], '', $secret) ) {
                                    $check++;
                                }

                                // Verify if number of the companies was reached
                                if ( $check >= $network_accounts ) {
                                    break;
                                }
                                
                            }

                        }

                    }

                }

            }

            if ( $check > 0 ) {
                
                // Display the success message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_all_blogger_blogs_were_added') . '</p>', true); 
                
            } else {
                
                // Display the error message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_you_don_t_have_blogs') . '</p>', false);             
                
            }

        } else {
        
            // Verify if code exists
            if ( $this->CI->input->get('code', TRUE) ) {
                
                // Call the class Google_Client
                $this->client = new \Google_Client();

                // Offline because we need to get refresh token
                $this->client->setAccessType('offline');

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

                // Load required scopes
                $this->client->setScopes(array('https://www.googleapis.com/auth/blogger https://www.googleapis.com/auth/userinfo.profile'));            
                
                // Send the received code
                $this->client->authenticate( $this->CI->input->get('code', TRUE) );
                
                // Get access token
                $token = $this->client->getAccessToken();
                
                // Set access token
                $this->client->setAccessToken($token);
                
                // Decode response
                $token = json_decode($token, true);
                
                // Verify if token exists
                if ( !empty($token['access_token']) ) {
                    
                    // Get refresh token
                    $refresh = $token['refresh_token'];
                    
                    // Get expiration time
                    $expires_in = '';
                    
                    // Get access token
                    $token = $token['access_token'];
                    
                    // we will use the token to get user data
                    $curl = curl_init();
                    
                    // Set some options - we are passing in a useragent too here
                    curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . $token, CURLOPT_HEADER => false));
                    
                    // Send the request & save response to $resp
                    $udata = json_decode(curl_exec($curl), TRUE);
                    
                    // Close request to clear up some resources
                    curl_close($curl);
                    
                    // Veify if response is valid
                    if ( !empty($udata['sub']) ) {
                        
                        // Get blogs
                        $curl = curl_init();
                        
                        // Set some options - we are passing in a useragent too here
                        curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'https://www.googleapis.com/blogger/v3/users/self/blogs?fetchUserInfo=true&role=ADMIN&view=ADMIN&fields=blogUserInfos%2Citems&access_token=' . $token, CURLOPT_HEADER => false));
                        
                        // Send the request & save response to $resp
                        $blogs = curl_exec($curl);
                        
                        // Close request to clear up some resources
                        curl_close($curl);
                        
                        // Verify if user has blogs
                        if ( $blogs ) {

                            // Items array
                            $items = array();

                            // Get Blogger Blogs
                            $get_connected = $this->CI->base_model->get_data_where(
                                'networks',
                                'net_id',
                                array(
                                    'network_name' => 'blogger',
                                    'user_id' => $this->CI->user_id
                                )

                            );

                            // Net Ids array
                            $net_ids = array();

                            // Verify if user has Blogger Blogs
                            if ( $get_connected ) {

                                // List all Blogger Blogs
                                foreach ( $get_connected as $connected ) {

                                    // Set net's id
                                    $net_ids[] = $connected['net_id'];

                                }

                            }

                            // Decode response
                            $getBlogs = json_decode($blogs, true);
                            
                            // If user has blogs will save them
                            if ( !empty($getBlogs['blogUserInfos'][0]['blog']['id']) ) {
                                
                                // List blogs
                                for ( $y = 0; $y < count($getBlogs['blogUserInfos']); $y++ ) {

                                    // Set item
                                    $items[$getBlogs['blogUserInfos'][$y]['blog']['id']] = array(
                                        'net_id' => $getBlogs['blogUserInfos'][$y]['blog']['id'],
                                        'name' => $getBlogs['blogUserInfos'][$y]['blog']['name'],
                                        'label' => '',
                                        'connected' => FALSE
                                    );

                                    // Verify if this Blog is connected
                                    if ( in_array($getBlogs['blogUserInfos'][$y]['blog']['id'], $net_ids) ) {

                                        // Set as connected
                                        $items[$getBlogs['blogUserInfos'][$y]['blog']['id']]['connected'] = TRUE;

                                    }
                                    
                                }

                                // Create the array which will provide the data
                                $args = array(
                                    'title' => 'Blogger',
                                    'network_name' => 'blogger',
                                    'items' => $items,
                                    'connect' => $this->CI->lang->line('networks_blogs'),
                                    'callback' => site_url('user/callback/blogger'),
                                    'inputs' => array(
                                        array(
                                            'token' => $token
                                        ), 
                                        array(
                                            'secret' => $refresh
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

                                // Display the error message
                                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_you_don_t_have_blogs') . '</p>', false);             
                                
                            }

                        } else {

                            // Display the error message
                            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_you_don_t_have_blogs') . '</p>', false);             
                            
                        }
                        
                    } else {

                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_you_don_t_have_blogs') . '</p>', false);             
                        
                    }
                    
                } else {

                    // Display the error message
                    echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_access_token_not_valid') . '</p>', false);             

                }
                
            } else {

                // Display the error message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);             

            }

        }
        
    }

    /**
     * The public method post publishes posts on Blogger.
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post( $args, $user_id = null ) {
        
        // Get user details
        if ( $user_id ) {
            
            // Get network's data
            $con = $this->CI->networks->get_network_data('blogger', $user_id, $args['account']);
            
        } else {
            
            // Set user's id
            $user_id = $this->CI->user_id;

            // Get account details
            $con = $this->CI->networks->get_network_data('blogger', $user_id, $args['account']);
            
        }
        
        // Verify if user has the account
        if ( $con ) {
            
            if ( $con[0]->secret ) {
                
                // will be refreshed the token
                try {
                    
                    // Call the class Google_Client
                    $this->client = new \Google_Client();

                    // Offline because we need to get refresh token
                    $this->client->setAccessType('offline');

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

                    // Load required scopes
                    $this->client->setScopes(array('https://www.googleapis.com/auth/blogger https://www.googleapis.com/auth/userinfo.profile'));

                    // Call the Blogger Service
                    $this->connect = new \Google_BloggerService($this->client);
                    
                    // Get refresh token
                    $this->client->refreshToken($con[0]->secret);
                    
                    // Get access token
                    $newtoken = $this->client->getAccessToken();
                    
                    // Set access token
                    $this->client->setAccessToken($newtoken);
                    
                    // Call the class Google_Post
                    $post = new \Google_Post();
                    
                    // Set content to publish
                    $content = $args['post'];
                    
                    // Verify if title exists
                    if ( $args ['title'] ) {
                        
                        // Set title
                        $post->setTitle( $args ['title'] );
                        
                        // Verify if url is empty
                        if ( $args['url'] ) {
                            $url = short_url($args['url']);
                            $content = str_replace($args['url'], ' ', $content);
                            $content = $content . '<br><p><a href="' . $url . '" target="_blank">' . $url . '</a></p>';
                        }
                        
                        // Set body
                        $post->setContent(nl2br($content) );
                        
                    } else {
                        
                        // Verify if url is empty
                        if ( $args['url'] ) {
                            $url = short_url($args['url']);
                            $content = str_replace($args['url'], ' ', $content);
                            
                            // Set body
                            $post->setContent(  '<br><p><a href="' . $url . '" target="_blank">' . $url . '</a></p>' );
                            
                        }
                        
                        // Set title
                        $post->setTitle( $content );
                        
                    }
                    
                    // Verify if category was selected
                    if ( $args['category'] ) {
                        
                        $category = json_decode($args['category'], true);

                        if ( !empty($category[$args['account']]) ) {

                            $post->setLabels([$category[$args['account']]]);
                            
                        }
                        
                    }
                    
                    // Publish
                    $data = $this->connect->posts->insert($con[0]->net_id, $post);
                    
                    if ( $data ) {
                        
                        return true;
                        
                    } else {
                        
                        return false;
                        
                    }
                    
                } catch (Exception $e) {
            
                    // Save the error
                    $this->CI->user_meta->update_user_option( $user_id, 'last-social-error', $e->getMessage() );

                    // Then return false
                    return false;
            
                }
                
            }
            
        }
        
    }

    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network data
     */
    public function get_info() {

        return array(
            'color' => '#fb8f3d',
            'icon' => '<i class="fa fa-bold" style="color:#ffffff"></i>',
            'api' => array('client_id', 'client_secret', 'api_key', 'google_application_name'),
            'types' => array('post', 'rss', 'categories')
        );
        
    }

    /**
     * The public method preview generates a preview for Telegram Groups.
     *
     * @param $args contains the img or url.
     * 
     * @return array with html content
     */
    public function preview($args) {}

}

/* End of file blogger.php */
