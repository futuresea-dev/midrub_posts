<?php
/**
 * Adminarea
 *
 * PHP Version 5.6
 *
 * Adminarea contains the Adminarea class for admin account
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Adminarea class - contains all metods and pages for admin account.
 *
 * @category Social
 * @package Midrub
 * @author Scrisoft <asksyn@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link https://www.midrub.com/
 */
class Adminarea extends MY_Controller {

    public $user_id, $user_role, $socials = [];

    public function __construct() {
        parent::__construct();
        
        // Load form helper library
        $this->load->helper('form');
        
        // Load form validation library
        $this->load->library('form_validation');
        
        // Load User Model
        $this->load->model('user');
        
        // Load User Meta Model
        $this->load->model('user_meta');
        
        // Load Notifications Model
        $this->load->model('notifications');
        
        // Load Posts Model
        $this->load->model('posts');
        
        // Load Plans Model
        $this->load->model('plans');
        
        // Load Urls Model
        $this->load->model('urls');
        
        // Load Networks Model
        $this->load->model('networks');
        
        // Load Options Model
        $this->load->model('options');
        
        // Load Campaigns Model
        $this->load->model('campaigns');
        
        // Load session library
        $this->load->library('session');
        
        // Load URL Helper
        $this->load->helper('url');
        
        // Load Main Helper
        $this->load->helper('main_helper');
        
        // Load Admin Helper
        $this->load->helper('admin_helper');
        
        // Load Alerts Helper
        $this->load->helper('alerts_helper');
        
        // Load SMTP
        $config = smtp();
        
        // Load Sending Email Class
        $this->load->library('email', $config);
        
        // Load Gshorter library
        $this->load->library('gshorter');
        
        // Check if session username exists
        if (isset($this->session->userdata ['username'])) {
            
            // Set user_id
            $this->user_id = $this->user->get_user_id_by_username($this->session->userdata ['username']);
            
            // Set user_role
            $this->user_role = $this->user->check_role_by_username($this->session->userdata ['username']);
            
        }
        
        // Load language
        if ( file_exists( APPPATH . 'language/' . $this->config->item('language') . '/default_alerts_lang.php' ) ) {
            $this->lang->load( 'default_alerts', $this->config->item('language') );
        }
    }

    /**
     * The public method dashboard displays admin dashboard.
     * 
     * @return void
     */
    public function dashboard() {
        
        // Check if the session exists and if the login user is admin
        $this->check_session($this->user_role, 1);

        // Redirect to the Dashboard component
        redirect('admin/dashboard');

    }
    
    /**
     * The public method ajax is universal caller for default user ajax calls
     * 
     * @param string $name contains the helper name
     * 
     * @return void
     */
    public function ajax($name) {
        
        // Check if the session exists and if the login user is admin
        $this->check_session($this->user_role, 1);
        
        // Verify if helper exists
        if ( file_exists( APPPATH . 'helpers/' . $name . '_helper.php' ) ) {
            
            // Get action's get input
            $action = $this->input->get('action');

            if ( !$action ) {
                $action = $this->input->post('action');
            }

            try {

                // Load Helper
                $this->load->helper($name . '_helper');
                
                // Call the function
                $action();

            } catch (Exception $ex) {

                $data = array(
                    'success' => FALSE,
                    'message' => $ex->getMessage()
                );

                echo json_encode($data);

            }
            
        } else {
            
            show_error('Invalid parameters');
            
        }
        
    }

    /**
     * The public method set_option enables and disables an option
     *
     * @param string $option_name contains the option name
     * 
     * @return void
     */
    public function set_option($option_name, $value = null) {
        
        // Check if the session exists and if the login user is admin
        $this->check_session($this->user_role, 1);
        
        if ( $value ) {
            
            // Enable and add value to an option
            $value = str_replace('-', '/', $value);
            
            $value = $this->security->xss_clean(base64_decode($value));
            
            if ( $this->options->add_option_value($option_name, $value) ) {
                
                echo json_encode(1);
                
            } else {
                
                display_mess();
                
            }
            
        } else {
            
            // Enable or disable the $option_name option
            if ( $this->options->enable_or_disable_network($option_name) ) {
                
                echo json_encode(1);
                
            } else {
                
                display_mess();
                
            }
            
        }
        
    }
    
    /**
     * The public method payment displays the network single page.
     *
     * @param string $gateway contains the gateway's name
     * 
     * @return void
     */
    public function payment($gateway) {
        
        // Check if the session exists and if the login user is admin
        $this->check_session($this->user_role, 1);  
        
        $class = [];
        
        if ( $gateway ) {
            
            // Get all available payment gateways
            include_once APPPATH . 'interfaces/Payments.php';
                
            if ( file_exists(APPPATH . 'payments/' . ucfirst($gateway) . '.php') ) {

                // Require the class
                require_once APPPATH . 'payments/' . ucfirst($gateway) . '.php';
                
                $gateway = ucfirst($gateway);

                // Call the class
                $get = new $gateway;

                // Get class info
                $class = $get->info();

            }
            
        }

        $this->body = 'admin/gateway';
        
        $this->content = [
            'gateway' => $gateway,
            'class' => $class
        ];
        
        $this->admin_layout();
        
    }
    
    /**
     * The public method delete_media deletes user's media
     *
     * @param integer $user_id is the user's id
     * @param integer $media_id contains the media's ID
     * 
     * @return void
     */
    public function delete_media( $user_id, $media_id ) {
        
        // Verify if session exists and if the user is admin
        $this->if_session_exists($this->user_role, 1 );
        
        // Load Media Model
        $this->load->model('media');
        
        // Get media
        $get_media = $this->media->single_media($user_id, $media_id);
        
        // Verify if the user is owner of the media
        if ( $get_media ) {
            
            if ( $this->media->delete_media($user_id, $media_id) ) {
                
                // Get file path
                $filename = str_replace(base_url(), FCPATH, $get_media[0]->body);
                
                try {
                    
                    // Get file data
                    $info = new SplFileInfo( $filename );                    
                
                    // Delete file 
                    @unlink($filename);
                    
                    // Delete cover
                    @unlink( str_replace('.' . $info->getExtension(), '-cover.png', $filename) );
                    
                    // Verify if the file was deleted
                    if ( !file_exists($filename) ) {
                        
                        // Get file size
                        $file_size = $get_media[0]->size;
                        
                        // Get user storage
                        $user_storage = get_user_option('user_storage', $user_id);
                        
                        $total_storage = $user_storage - $file_size;
                        
                        // Update the user storage
                        update_user_option( $user_id, 'user_storage', $total_storage );
                        
                    }
                
                } catch ( Exception $e ) {
                
                }
                
            }
            
        }
        
    }

    /**
     * The public method upmedia uploads media files using ajax
     * 
     * @return void
     */
    public function upmedia() {
        
        // Verify if session exists and if the user is admin
        $this->if_session_exists($this->user_role,1);
        
        // The background can be a video or an image
        $allowedimageformat = ($_POST ['media-name'] == 'login-bg') ? [
            'gif',
            'png',
            'jpg',
            'jpeg',
            'avi',
            'mp4',
            'webm'
                ] : [
            'gif',
            'png',
            'jpg',
            'jpeg',
            'ico'
        ];
        
        $format = pathinfo($_FILES ['file'] ['name'], PATHINFO_EXTENSION);
        
        if ( !in_array($format, $allowedimageformat) ) {
            
            echo ($_POST ['media-name'] == 'login-bg') ? 2 : 1;
            die();
            
        }
        
        $config ['upload_path'] = 'assets/img';
        
        $config ['file_name'] = time();
        
        $this->load->library('upload', $config);
        
        $this->upload->initialize($config);
        
        $this->upload->set_allowed_types('*');
        
        $data ['upload_data'] = '';
        
        if ( $this->upload->do_upload('file') ) {
            
            // Delete old media file
            $old_url = get_option($_POST ['media-name']);
            
            if ( $old_url ) {
                
                $url = str_replace($this->config->base_url(), '', $old_url);
                // Check if old file exist and delete it
                if ( file_exists($url) ) {
                    
                    unlink($url);
                    
                }
                
            }
            
            // Get information about uploaded file
            $data ['upload_data'] = $this->upload->data();
            
            $this->options->set_media_option($_POST ['media-name'], $this->config->base_url() . 'assets/img/' . $data ['upload_data'] ['file_name']);
            
            if ( !in_array($format, [
                        'avi',
                        'mp4',
                        'webm'
                    ])) {
                
                echo '<img src="' . $this->config->base_url() . 'assets/img/' . $data ['upload_data'] ['file_name'] . '" class="thumbnail" />';
                
            } else {
                
                echo '<video autoplay="" loop="" class="fillWidth fadeIn wow collapse in" id="video-background" width="187"><source src="' . $this->config->base_url() . 'assets/img/' . $data ['upload_data'] ['file_name'] . '" type="video/mp4"></video>';
                
            }
            
        }
        
    }

    /**
     * The public method get_statistics gets statistics and display in the admin dashboard
     *
     * @param integer $num contains the period number
     * 
     * @return void
     */
    public function get_statistics($num) {
        
        // Verify if session exists and if the user is admin
        $this->if_session_exists($this->user_role,1);
        
        // This function get statistics by $num
        $statistics = generate_admin_statstics($num, $this->user->get_last_users($num));
        
        if ( $statistics ) {
            
            echo json_encode($statistics);
            
        }
        
    }

}

/* End of file Adminarea.php */
