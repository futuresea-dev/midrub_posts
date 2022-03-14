<?php
/**
 * Offers Helper
 *
 * This file contains the class Offers
 * with methods to manage the offers alerts
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
 * Offers class provides the methods to manage the offers alerts
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.3
*/
class Offers {
    
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

        // Load Notifications Offers Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'models/', 'Notifications_offers_model', 'notifications_offers_model' );

    }

    /**
     * The public method load_offers_by_page loads offers alerts by page
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */ 
    public function load_offers_by_page() {
        
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

        // Get offers alerts
        $offers_alerts = $this->CI->notifications_offers_model->the_offers_alerts($where);

        // Verify if offers alerts exists
        if ( $offers_alerts ) {
            
            // Prepare the success response
            $data = array(
                'success' => TRUE,
                'offers' => $offers_alerts['offers'],
                'total' => $offers_alerts['total'],
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
    public function delete_offers_alert() {
        
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
                    'seen' => 1,
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

/* End of file offers.php */