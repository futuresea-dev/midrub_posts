<?php
/**
 * Linkedin Companies
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Linkedin Companies
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
 * Linkedin_companies class - allows users to connect Linkedin Companies and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Linkedin_companies implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $CI, $redirect_uri, $client_id, $client_secret, $endpoint = 'https://www.linkedin.com/oauth/v2';
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get Linkedin client_id
        $this->client_id = get_option('linkedin_companies_client_id');
        
        // Get Linkedin client_secret
        $this->client_secret = get_option('linkedin_companies_client_secret');
        
        // Set redirect_url
        $this->redirect_uri = site_url('user/callback/linkedin_companies');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );

        // Load the Vendor dependencies
        require_once FCPATH . 'vendor/autoload.php';
    
        // Load Base Model
        $this->CI->load->ext_model( APPPATH . 'base/models/', 'Base_model', 'base_model' );
        
    }
    
    /**
     * The public method check_availability checks if the Linkedin Companies api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        if ( ( $this->client_id != '' ) AND ( $this->client_secret != '' ) ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method connect will redirect user to Linkedin login page.
     * 
     * @return void
     */
    public function connect() {

        // Set params
        $params = array(
            'response_type' => 'code',
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect_uri,
            'state' => time(),
            'scope' => 'r_basicprofile rw_organization_admin r_organization_social w_organization_social'
        );
        
        // Get redirect url
        $url = $this->endpoint . '/authorization?' . http_build_query($params);
        
        // Redirect
        header('Location:' . $url);

    }

    /**
     * The public method save will get access token.
     *
     * @param void
     */
    public function save($token = null) {

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

                // Get linkedin organizations
                $organizations = json_decode(get('https://api.linkedin.com/v2/organizationalEntityAcls?q=roleAssignee&role=ADMINISTRATOR&state=APPROVED&projection=(*,elements*(*,organizationalTarget~(*)))&oauth2_access_token=' . $token), true);

                // Get connected accounts
                $get_connected = $this->CI->base_model->get_data_where(
                    'networks',
                    'network_id, net_id',
                    array(
                        'network_name' => 'linkedin_companies',
                        'user_id' => $this->CI->user_id
                    )

                );

                // Verify if user has connected accounts
                if ( $get_connected ) {

                    // List all connected accounts
                    foreach ( $get_connected as $connected ) {

                        // Verify if $net_ids is empty
                        if ( empty($net_ids) ) {

                            // Verify if companies were found
                            if ( isset($organizations['elements']) ) {

                                // Save company
                                for ( $y = 0; $y < count($organizations['elements']); $y++ ) {

                                    // Verify if this company is connected
                                    if ( $organizations['elements'][$y]['organizationalTarget~']['id'] === (int)$connected['net_id'] ) {

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

                            // Verify if companies were found
                            if ( isset($organizations['elements']) ) {

                                // Save company
                                for ( $y = 0; $y < count($organizations['elements']); $y++ ) {

                                    // Verify if user has selected this Linkedin Company
                                    if ( in_array($organizations['elements'][$y]['organizationalTarget~']['id'], $net_ids) ) {
                                        continue;
                                    }

                                    // Verify if this company is connected
                                    if ( $organizations['elements'][$y]['organizationalTarget~']['id'] === (int)$connected['net_id'] ) {

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
                    
                    // Verify if companies were found
                    if ( isset($organizations['elements']) ) {

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
                        
                        // Save company
                        for ( $y = 0; $y < count($organizations['elements']); $y++ ) {

                            // Verify if user has selected this Linkedin Company
                            if ( !in_array($organizations['elements'][$y]['organizationalTarget~']['id'], $net_ids) ) {
                                continue;
                            }
                            
                            // Save company
                            if ( $this->CI->networks->add_network('linkedin_companies', $organizations['elements'][$y]['organizationalTarget~']['id'], $token, $this->CI->user_id, $expires, $organizations['elements'][$y]['organizationalTarget~']['localizedName'], '', $token) ) {
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

            if ( $check > 0 ) {
                
                // Display the success message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_all_linkedin_companies_connected') . '</p>', true); 
                
            } else {
                
                // Display the error message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_no_linkedin_companies_connected') . '</p>', false);             
                
            }

        } else {

            // Verify if the code exists
            if ($this->CI->input->get('code', TRUE)) {

                // Set params
                $params = array(
                    'grant_type' => 'authorization_code',
                    'code' => $this->CI->input->get('code', TRUE),
                    'redirect_uri' => $this->redirect_uri,
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret
                );

                // Get access token
                $response = json_decode(post($this->endpoint . '/accessToken', $params), true);

                // Verify if token exists
                if ( isset($response['access_token']) ) {

                    // Get exiration time
                    $expires = date('Y-m-d H:i:s', time() + $response['expires_in']);

                    // Get linkedin profile
                    $profile = json_decode(get('https://api.linkedin.com/v2/me?oauth2_access_token=' . $response['access_token']), true);

                    // Verify if the profile exists
                    if ($profile) {

                        // Get linkedin organizations
                        $organizations = json_decode(get('https://api.linkedin.com/v2/organizationalEntityAcls?q=roleAssignee&role=ADMINISTRATOR&state=APPROVED&projection=(*,elements*(*,organizationalTarget~(*)))&oauth2_access_token=' . $response['access_token']), true);

                        // Verify if companies were found
                        if ( !empty($organizations['elements']) ) {

                            // Items array
                            $items = array();

                            // Get Linkedin Companies
                            $get_connected = $this->CI->base_model->get_data_where(
                                'networks',
                                'net_id',
                                array(
                                    'network_name' => 'linkedin_companies',
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

                            // Save company
                            for ( $y = 0; $y < count($organizations['elements']); $y++ ) {

                                // Set item
                                $items[$organizations['elements'][$y]['organizationalTarget~']['id']] = array(
                                    'net_id' => $organizations['elements'][$y]['organizationalTarget~']['id'],
                                    'name' => $organizations['elements'][$y]['organizationalTarget~']['localizedName'],
                                    'label' => '',
                                    'connected' => FALSE
                                );

                                // Verify if this Company is connected
                                if ( in_array($organizations['elements'][$y]['organizationalTarget~']['id'], $net_ids) ) {

                                    // Set as connected
                                    $items[$organizations['elements'][$y]['organizationalTarget~']['id']]['connected'] = TRUE;

                                }
                                
                            }

                            // Create the array which will provide the data
                            $args = array(
                                'title' => 'Linkedin Companies',
                                'network_name' => 'linkedin_companies',
                                'items' => $items,
                                'connect' => $this->CI->lang->line('networks_companies'),
                                'callback' => site_url('user/callback/linkedin_companies'),
                                'inputs' => array(
                                    array(
                                        'token' => $response['access_token']
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
                            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_you_have_no_companies') . '</p>', false);

                        }

                    } else {

                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_you_have_no_companies') . '</p>', false);

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
     * The public method publishes posts on Linkedin Companies.
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
            $user_details = $this->CI->networks->get_network_data(strtolower('linkedin_companies'), $user_id, $args['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $user_details = $this->CI->networks->get_network_data(strtolower('linkedin_companies'), $user_id, $args['account']);
            
        }
        
        // Get the post
        $post = strip_tags($args['post']);
        
        // Verify if url exists
        if ( $args['url'] ) {
            $post = str_replace($args['url'], ' ', $post);
        }       
        
        $new_post = mb_substr($post, 0, 699);

        try {
    
            // Prepare the post
            $post_body = new \stdClass;
            $post_body->owner = 'urn:li:organization:' . $user_details[0]->net_id;
            $post_body->text = new \stdClass;
            $post_body->text->text = $new_post;
            $post_body->distribution = new \stdClass;
            $post_body->distribution->linkedInDistributionTarget = new \stdClass;
            $post_body->content = new \stdClass;

            // Verify if image exists
            if ( !empty($args['img']) ) {

                // Prepare the request
                $request = new \stdClass;
                $request->registerUploadRequest = new \stdClass;
                $request->registerUploadRequest->owner = 'urn:li:organization:' . $user_details[0]->net_id;
                $request->registerUploadRequest->recipes = array();
                $request->registerUploadRequest->recipes[] = 'urn:li:digitalmediaRecipe:feedshare-image';
                $request->registerUploadRequest->serviceRelationships = array();
                $request->registerUploadRequest->serviceRelationships[] = (object) array(
                    'identifier' => 'urn:li:userGeneratedContent',
                    'relationshipType' => 'OWNER'
                );

                // Execute request
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'https://api.linkedin.com/v2/assets?action=registerUpload&oauth2_access_token=' . $user_details[0]->token);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen(json_encode($request)))
                );    

                // Get the response
                $response = json_decode(curl_exec($curl), true);
                curl_close ($curl);
                
                // Prepare response
                $request_url = $response['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'];

                // Set image
                $img = str_replace(base_url(), FCPATH, $args['img'][0]['body']);

                // Get image's type
                $img_mime_type = image_type_to_mime_type( exif_imagetype( $img ) );

                // Get image's source
                $img_data   = file_get_contents( $img );

                // Get image's length
                $img_length = strlen( $img_data );

                // Get image's binary
                $imageBinary = class_exists('CURLFile', false) ? new \CURLFile($img, $img_mime_type, basename($img_mime_type)) : "@" . $img;

                // Upload image
                $guzzle = new \GuzzleHttp\Client();
                $result = $guzzle->request(
                    'PUT',
                    $request_url,
                    array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $user_details[0]->token,
                        ),
                        'body' => fopen( $img, 'r' ),
        
                    )
                );

                $post_body->content->contentEntities = array( (object) array( 'entity' => $response['value']['asset'] ) );
                $post_body->content->shareMediaCategory = 'IMAGE';
                
            }

            // Verify if title exists
            if ( $args['title'] ) {

                // Set title
                $post_body->content->title = $args['title'];

            }

            // Verify if url exists
            if ( $args['url'] && isset($args['img'][0]['body']) ) {
                
                // Set url
                $post_body->content->landingPageUrl = short_url($args['url']);

            } else if ($args['url']) {

                // Set url
                $post_body->content->contentEntities = array( (object) array( 'entityLocation' => short_url($args['url']) ) );
            }
        
            // Publish post
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://api.linkedin.com/v2/shares/?oauth2_access_token=' . $user_details[0]->token);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_body));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($post_body)))
            );                                                                                                             
            
            // Get publish response
            $post_response = json_decode(curl_exec($curl), true);

            // Clear cache
            curl_close ($curl);

            // Verify if the post was published
            if ( !empty($post_response['id']) ) {

                return $post_response['id'];

            } else {
                
                // Save the error
                $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($post_response));

                // Then return falsed
                return false;
                
            }

        } catch (Exception $e) {

            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', $e->getMessage());

            // Then return falsed
            return false;
            
        }
        
    }
    
    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network details
     */
    public function get_info() {
        
        return array(
            'color' => '#eddb11',
            'icon' => '<i class="fab fa-linkedin"></i>',
            'api' => array('client_id', 'client_secret'),
            'types' => array('insights', 'post', 'rss')
        );
        
    }
    
    /**
     * The public method preview generates a preview for Linkedin Companies.
     *
     * @param array $args contains the img or url.
     * 
     * @return array with html content
     */
    public function preview($args) {
    }
    
}

/* End of file linkedin_companies.php */