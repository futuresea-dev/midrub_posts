<?php
/**
 * News Helper
 *
 * This file contains the class News
 * with methods to manage the news alerts
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.3
 */

// Define the page namespace
namespace MidrubBase\User\Components\Collection\Notifications\Helpers; 

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * News class provides the methods to manage the news alerts
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.3
*/
class News {
    
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

        // Load Notifications News Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'models/', 'Notifications_news_model', 'notifications_news_model' );

    }

    /**
     * The public method load_news_by_page loads news alerts by page
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */ 
    public function load_news_by_page() {
        
        // Get page's input
        $page = $this->CI->input->get('page', TRUE);   
        
        // Verify if the page isn't numeric
        if ( !is_numeric($page) ) {
            $page = 1;
        }
        
        // Set limit
        $limit = 10;
        
        // Set page
        $page--;

        // Set where parameters
        $where = array(
            'user_id' => $this->CI->user_id,
            'page' => ($page * $limit),
            'limit' => $limit,
            'plan' => the_user_option('plan'),
            'language' => $this->CI->config->item('language')
        );

        // Get news alerts
        $news_alerts = $this->CI->notifications_news_model->the_news_alerts($where);
        
        // Verify if news alerts exists
        if ( $news_alerts ) {
            
            // Unseen banners
            $unseen_banners = array();

            // List news alerts
            foreach ( $news_alerts['news'] as $alert ) {

                // Verify if banner is seen
                if ( $alert['banner_seen'] === 0 ) {

                    // Set unseen
                    $unseen_banners[] = $alert['alert_id'];

                } else if ( !is_numeric($alert['banner_seen']) ) {

                    // Create user's activity for alert
                    $user_activity = array(
                        'alert_id' => $alert['alert_id'],
                        'user_id' => $this->CI->user_id,
                        'banner_seen' => 1,
                        'page_seen' => 0,
                        'deleted' => 0,
                        'updated' => time(),
                        'created' => time()
                    );

                    // Save the user's activity by using the Base's Model
                    $this->CI->base_model->insert('notifications_alerts_users', $user_activity);

                }

            }
            
            // Verify if unseen banners exists
            if ( $unseen_banners ) {

                // Set as seen
                $this->CI->notifications_news_model->mark_banner_as_seen($unseen_banners);

            }

            // Prepare the success response
            $data = array(
                'success' => TRUE,
                'news' => $news_alerts['news'],
                'total' => $news_alerts['total'],
                'date' => time(),
                'page' => ($page + 1),
                'user_id' => $this->CI->user_id,
                'words' => array(
                    'delete' => $this->CI->lang->line('notifications_delete')
                )
            );

            // Display the succes response
            echo json_encode($data);
            
        } else {
            
            // Prepare the error response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('notifications_no_found')
            );

            // Display the error response
            echo json_encode($data);            
            
        }
        
    }

    /**
     * The public method delete_notification deletes notification by notification's ID
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */ 
    public function delete_news_alert() {
        
        // Get alert's input
        $alert = $this->CI->input->get('alert', TRUE);  

        // Verify if the alert is numeric
        if ( is_numeric($alert) ) {
            
            // Get the alert
            $alert_data = $this->CI->base_model->get_data_where(
                'notifications_alerts_users',
                'alert_id',
                array(
                    'alert_id' => $alert,
                    'user_id' => $this->CI->user_id
                )
            );

            // Verify if the alert exists
            if ( $alert_data ) {

                // Try to change the alert's status
                if ( $this->CI->base_model->update('notifications_alerts_users', array('alert_id' => $alert), array('deleted' => 1)) ) {

                    // Prepare the success response
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('notification_was_deleted_successfully')
                    );
        
                    // Display the success response
                    echo json_encode($data);
                    exit();

                }

            } else {

                // Create user's activity for alert
                $user_activity = array(
                    'alert_id' => $alert,
                    'user_id' => $this->CI->user_id,
                    'page_seen' => 1,
                    'deleted' => 1,
                    'updated' => time(),
                    'created' => time()
                );

                // Save the user's activity by using the Base's Model
                if ( $this->CI->base_model->insert('notifications_alerts_users', $user_activity) ) {

                    // Prepare the success response
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('notification_was_deleted_successfully')
                    );
        
                    // Display the success response
                    echo json_encode($data);
                    exit();
                    
                }

            }

        }

        // Prepare the error response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('notification_was_not_deleted_successfully')
        );

        // Display the error response
        echo json_encode($data);
        
    }

}

/* End of file news.php */