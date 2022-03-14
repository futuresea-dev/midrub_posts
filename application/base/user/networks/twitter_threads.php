<?php
/**
 * Twitter Threads
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Twitter
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
use Abraham\TwitterOAuth\TwitterOAuth as TwitterOAuth;

// If session valiable doesn't exists will be called
if ( !isset($_SESSION) ) {
    session_start();
}

/**
 * Twitter_threads class - allows users to connect to their Twitter Account and publish threads.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Twitter_threads implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    public $CI, $connection, $twitter_key, $twitter_secret;

    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get the Twitter app_id
        $this->twitter_key = get_option('twitter_threads_app_id');
        
        // Get the Twitter app_secret
        $this->twitter_secret = get_option('twitter_threads_app_secret');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Require the vendor autoload
        require_once FCPATH . 'vendor/autoload.php';
        
        // Call the TwitterOAuth
        $this->connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret);
        
    }

    /**
     * The public method check_availability checks if the Twitter api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        // Verify if the twitter_key and twitter_secret is not empty
        if ( ($this->twitter_key != '') AND ( $this->twitter_secret != '') ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }

    /**
     * The public method connect will redirect user to Twitter login page.
     * 
     * @return void
     */
    public function connect() {
        
        // Request the token
        $request_token = $this->connection->oauth('oauth/request_token', array('oauth_callback' => site_url('user/callback/twitter_threads')));
        
        // Create empty session variables
        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
        
        // Generate the redirect url
        $url = $this->connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
        
        // Redirect
        header('Location: ' . $url);
        
    }

    /**
     * The public method save will get access token.
     *
     * @param string $token contains the token for some social networks
     * 
     * @return void
     */
    public function save($token = null) {
        
        // Verify if oauth_verifier exists
        if ( $this->CI->input->get('oauth_verifier', TRUE) ) {
            
            // Call the TwitterOAuth class
            $twitterOauth = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
            
            // Get access token
            $twToken = $twitterOauth->oauth('oauth/access_token', array('oauth_verifier' => $this->CI->input->get('oauth_verifier', TRUE)));
            $newTwitterOauth = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $twToken['oauth_token'], $twToken['oauth_token_secret']);
            $response = (array) $newTwitterOauth->get('account/verify_credentials');
            
            // Verify if access token exists
            if ( !empty($twToken['oauth_token']) && !empty($response) ) {
                
                // Verify if account was already saved 
                if ( $this->CI->networks->check_account_was_added('twitter_threads', $response['id'], $this->CI->user_id) ) {

                    // Display the error message
                    echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_already_added') . ' Twitter ' . $this->CI->lang->line('networks_change_your_account') . '</p>', false); 
                    
                } else {

                    // True to save the account
                    if ( $this->CI->networks->add_network('twitter_threads', $response['id'], $twToken['oauth_token'], $this->CI->user_id, '', @$response['screen_name'], @$response['profile_image_url'], $twToken['oauth_token_secret']) ) {

                        // Display the success message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_your_account_was_connected') . '</p>', true);

                    } else {

                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_was_not_connected') . '</p>', true);

                    }
                    
                }
                
            }
            
        } else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false); 
            
        }
        
    }

    /**
     * The public method post publishes threads on Twitter.
     *
     * @param $args contains the threads data.
     * @param $user_id is the ID of the current user
     * 
     * @return boolean true if the thread was published
     */
    public function post($args, $user_id = NULL) {
        
        // Verify if user_id exists
        if ( $user_id ) {
            
            // Get account details
            $user_details = $this->CI->networks->get_network_data('twitter_threads', $user_id, $args['account']);
            
        } else {

            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $user_details = $this->CI->networks->get_network_data('twitter_threads', $user_id, $args['account']);
            
        }
        
        try {
            
            // Call the TwitterOAuth class with valid access token
            $connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $user_details[0]->token, $user_details[0]->secret);
            
            // Create the tweet
            $post = $args['post'];
            
            // Verify if title exists
            if ( $args['title'] ) {
                
                // Add even title
                $post = $args['title'] . ' ' . $post;
                
            }

            // Tweet's ID
            $tweet_id = 0;

            // Verify the length of the post
            if ( strlen($post) > 276 ) {

                // Tweets container
                $tweets = array();

                // Explode by dots
                $parts = explode('.', $post);

                // List parts
                foreach ( $parts as $part ) {

                    // Verify part length
                    if ( strlen($part) > 276 ) {

                        // Cut
                        $subparts = str_split($part, 276);

                        // List subparts
                        foreach ( $subparts as $subpart ) {

                            // Add tweet
                            $tweets[] = $subpart;                            

                        }

                    } else {

                        // Add tweet
                        $tweets[] = $part . '.';

                    }

                }

                // Verify if $tweets is not empty
                if ( $tweets ) {

                    // List tweets
                    foreach ( $tweets as $tweet ) {

                        // Verify if tweet exists
                        if ( $tweet_id ) {

                            // One character won't be published
                            if ( strlen(trim($tweet)) < 2 ) {
                                continue;
                            }

                            // Pause 1 second
                            sleep(1);

                            // Retweet
                            $connection->post( 'statuses/update' , array( 'in_reply_to_status_id' => $tweet_id, 'status' => '@' . $user_details[0]->user_name . ' ' . $tweet ) );

                        } else {

                            // Try to publish
                            $check = $connection->post( 'statuses/update', array( 'status' => $tweet ) );

                            // Verify if the tweet was published
                            if ( empty($check->errors) ) {

                                // Set Tweet's ID
                                $tweet_id = $check->id;

                            } else {

                                // Save the error
                                update_user_option($user_id, 'last-social-error', json_encode($check->errors));

                                // Then return falsed
                                return false;
                                
                            }

                        }

                    }

                } else {

                    // Save the error
                    update_user_option($user_id, 'last-social-error', $this->CI->lang->line('networks_an_error_occurred'));

                    // Then return falsed
                    return false;

                }

            } else {

                // Try to publish
                $check = $connection->post( 'statuses/update', array( 'status' => $post ) );

                // Verify if the tweet was published
                if ( empty($check->errors) ) {

                    // Set Tweet's ID
                    $tweet_id = $check->id;

                } else {

                    // Save the error
                    update_user_option($user_id, 'last-social-error', json_encode($check->errors));

                    // Then return falsed
                    return false;
                    
                }

            }

            // Verify if url exists
            if ( $args['url'] ) {

                // Short url
                $new_url = short_url($args['url']);

                // Retweet
                $connection->post(  'statuses/update' , array( 'in_reply_to_status_id' => $tweet_id, 'status' => '@' . $user_details[0]->user_name . ' ' . $new_url ) );

            }

            // Verify if image exists
            if ( $args['img'] ) {
                
                $photos = array();
                
                $i = 0;
                
                foreach ( $args['img'] as $img ) {
                    
                    if ( $i > 3 ) {
                        continue;
                    }

                    // Verify if the image is on server
                    if ( strpos($img['body'], base_url()) !== false ) {

                        // Try to upload the image
                        $status = $connection->upload('media/upload', array('media' => str_replace(site_url(), FCPATH, $img['body'])));

                        // Verify for media id string
                        if ( !empty($status->media_id_string) ) {

                            // Set media's id
                            $photos[] = $status->media_id;
                            $i++;

                        }

                    } else {

                        // Get extension
                        $extension = pathinfo($img['body'], PATHINFO_EXTENSION);

                        // Verify if extension exists
                        if ( $extension ) {

                            // Prepare file
                            $filename = FCPATH . 'assets/share/' . uniqid() . time() . '.' . $extension;
                            
                            // Save file
                            file_put_contents($filename, $img['body']);
                            
                            // Verify if the file was saved
                            if ( file_exists($filename) ) {
                                
                                // Try to upload the image
                                $status = $connection->upload('media/upload', array('media' => str_replace(site_url(), FCPATH, $filename)));

                                // Verify for media id string
                                if ( !empty($status->media_id_string) ) {

                                    // Set media's id
                                    $photos[] = $status->media_id;
                                    $i++;

                                }                                
                              
                            }

                        }

                    }

                }

                // Retweet
                $connection->post(  'statuses/update' , array( 'in_reply_to_status_id' => $tweet_id, 'status' => '@' . $user_details[0]->user_name, 'media_ids' => implode(',', $photos) ) );
                
                // Upload the image
                sleep(1);
                
            } 
            
            // Verify if video exists
            if ($args['video']) {
                
                // Upload the video
                $media = $connection->upload('media/upload', array('media' => str_replace(site_url(), FCPATH, $args['video'][0]['body']), 'media_type' => 'video/mp4'), true);

                // Retweet
                $connection->post(  'statuses/update' , array( 'in_reply_to_status_id' => $tweet_id, 'status' => '@' . $user_details[0]->user_name, 'media_ids' => $media->media_id_string ) );
                
                // Upload the videos
                sleep(1);
                
            }

            // Verify if the tweet was published successfully
            if ( $tweet_id ) {

                return $tweet_id;

            } else {
                
                // Save the error
                update_user_option($user_id, 'last-social-error', $this->CI->lang->line('networks_an_error_occurred') );

                // Then return falsed
                return false;
                
            }

        } catch (Exception $e) {

            // Save the error
            update_user_option($user_id, 'last-social-error', $e->getMessage());

            // Then return falsed
            return false;

        }
        
    }

    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network's data
     */
    public function get_info() {
        
        return array(
            'color' => '#1da1f2',
            'icon' => '<i class="icon-social-twitter"></i>',
            'preview_icon' => '<i class="icon-screen-desktop"></i>',
            'api' => array('app_id', 'app_secret'),
            'types' => array('post', 'rss', 'preview', 'insights')
        );
        
    }

    /**
     * The public preview generates a preview for Twitter.
     *
     * @param $args contains the img or url.
     * 
     * @return array with html content
     */
    public function preview($args) {

        // Default body value
        $body = '';

        // Default media value
        $media = '';

        // Default url value
        $url = '';

        if ( $args ) {

            if ( isset($args['body']) ) {

                $body = '<tr>'
                            . '<td colspan="2" class="post-preview-body" style="padding: 0 15px 15px; font-style: normal; font-weight: normal; line-height: normal; font-size: 13px; color: rgb(20, 24, 35);">'
                                . $args['body']
                            . '</td>'  
                        . '</tr>';                

            }

            if ( isset($args['medias']) ) {

                foreach ( $args['medias'] as $medi ) {
                                
                    if ( $medi['type'] === 'image' ) {

                        $media .= '<tr>'                                                              
                                    . '<td colspan="2">'
                                        . '<div data-id="' . $medi['id'] . '" data-type="' . $medi['type'] . '" style="padding: 0 10px;">'
                                            . '<img src="' . $medi['url'] . '" style="width: 100%; height: 269px; border-radius: 20px;">'
                                            . '<a href="#" class="btn-delete-post-media" style="position: absolute; right: 30px; margin-top: 5px; font-size: 20px; border: 0 !important;">'
                                                . '<i class="icon-close" style="background-color: #343a40; color: #FFFFFF; border-radius: 50%;"></i>'
                                            . '</a>'
                                        . '</div>'
                                    . '</td>'
                                . '</tr>';

                    } else {

                        $media .= '<tr>'                                           
                                    . '<td colspan="2">'
                                        . '<div data-id="' . $medi['id'] . '" data-type="' . $medi['type'] . '" style="padding: 0 10px;">'
                                            . '<video style="width: 100%; height: 269px; border-radius: 20px;" controls="">'
                                                . '<source src="' . $medi['url'] . '" type="video/mp4">'
                                            . '</video>'
                                            . '<a href="#" class="btn-delete-post-media" style="position: absolute; right: 30px; margin-top: 30px; font-size: 20px; border: 0 !important;">'
                                                . '<i class="icon-close" style="background-color: #343a40; color: #FFFFFF; border-radius: 50%;"></i>'
                                            . '</a>'
                                        . '</div>'
                                    . '</td>'
                                . '</tr>';

                    }

                }               

            }

            if ( isset($args['link']) ) {

                $parse = parse_url($args['link']['url']);

                if ( isset($args['link']['img']) ) {

                    $url = '<table class="full" style="border-radius: 20px; width: calc(100% - 30px); border: 1px solid #e0e4e9; margin: 15px; display: block;">'
                        . '<tbody>'
                            . '<tr>'
                                . '<td>'
                                    . '<a href="#" class="btn-delete-post-url" class="btn-delete-post-media" style="position: absolute; right: 35px; margin-top: 5px; font-size: 20px; border: 0 !important;">'
                                        . '<i class="icon-close" style="background-color: #343a40; color: #FFFFFF; border-radius: 50%;"></i>'
                                    . '</a>'
                                    . '<img src="' . $args['link']['img'] . '" style="width: 100%;">'
                                . '</td>'
                            . '</tr>'
                            . '<tr>'
                                . '<td style="padding: 15px;">'
                                    . '<h3 style="padding: 15px 15px 0 0; font-size: 14px; margin-bottom: 0;">'
                                        . $args['link']['title']
                                    . '</h3>'
                                    . '<a href="' . $args['link']['url'] . '" target="_blank" style="padding: 0 15px 0 0; font-size: 13px; color: rgba(0, 0, 0, 0.54);">'
                                        . $parse['host']
                                    . '</a>'
                                . '</td>'
                            . '</tr>'
                        . '</tbody>'
                    . '</table>';
                    
                } else {

                    $url = '<table class="full" style="border-radius: 20px; width: calc(100% - 30px); border: 1px solid #e0e4e9; margin: 15px; display: block;">'
                        . '<tbody>'
                            . '<tr>'
                                . '<td style="padding: 15px;">'
                                    . '<h3 style="padding: 15px 15px 0 0; font-size: 14px; margin-bottom: 0;">'
                                        . $args['link']['title']
                                    . '</h3>'
                                    . '<a href="' . $args['link']['url'] . '" target="_blank" style="padding: 0 15px 0 0; font-size: 13px; color: rgba(0, 0, 0, 0.54);">'
                                        . $parse['host']
                                    . '</a>'
                                    . '<a href="#" class="btn-delete-post-url" class="btn-delete-post-media" style="position: absolute; right: 35px; margin-top: 5px; font-size: 20px; border: 0 !important;">'
                                        . '<i class="icon-close" style="background-color: #343a40; color: #FFFFFF; border-radius: 50%;"></i>'
                                    . '</a>'
                                . '</td>'
                            . '</tr>'
                        . '</tbody>'
                    . '</table>';
                    
                }

            }

        }

        return array(
            'body' => '<table style="width: calc(100% + 30px); box-shadow: rgba(0, 0, 0, 0.06) 0px 7px 8px; margin-left: -15px; margin-bottom: 30px;">'
                        . '<thead>'
                            . '<tr>'
                                . '<th style="width: 60px; text-align: center;">'
                                    . '<img src="' . base_url('assets/img/avatar-placeholder.png') . '" style="width: 40px; border-radius: 50%;">'
                                . '</th>'                                                    
                                . '<th colspan="2" style="padding: 0 15px 9px;">'
                                    . '<h3 style="padding-top: 0; font-family: \'Open Sans\', sans-serif, \'Arimo\'; font-weight: bold;">'
                                        . '<a href="#" style="display: block; font-style: normal; pointer-events: none; line-height: normal; font-size: 13px; color: rgb(20, 24, 35);">'
                                            . $this->CI->lang->line('networks_your_twitter_id')
                                            . '&nbsp;&nbsp;&bull;&nbsp;&nbsp;'
                                            . '<span style="font-weight: 400; color: #657785;">' . $this->CI->lang->line('networks_now') . '</span>'
                                        . '</a>'
                                    . '</h3>'
                                . '</th>'
                            . '</tr>'
                        . '</thead>'
                        . '<tbody>'
                            . '<tr>'
                                . '<td>'
                                . '</td>'  
                                . '<td colspan="2" class="clean">'
                                    . '<table class="full" style="width: 100%;">'
                                        . '<tbody>'
                                            . $body
                                            . $media
                                            . $url
                                        . '</tbody>'
                                        . '<tfoot>'
                                        . '<tr>'
                                            . '<td>'
                                            . '</td>'
                                            . '<td colspan="2">'
                                                . '<i class="far fa-comment" style="float: left; line-height: 40px; margin: 0 30px 0 15px;"></i>'
                                                . '<i class="fas fa-retweet" style="float: left; line-height: 40px; margin: 0 30px;"></i>'
                                                . '<i class="far fa-heart" style="float: left; line-height: 40px; margin: 0 30px;"></i>'
                                                . '<i class="icon-chart" style="float: left; line-height: 40px; margin: 0 30px;"></i>'
                                            . '</td>'                             
                                        . '</tr>'
                                    . '</tfoot>'
                                    . '</table>'
                                . '</td>'
                            . '</tr>'
                        . '</tbody>'
                    . '</table>'

        );

    }

}

/* End of file twitter_threads.php */
