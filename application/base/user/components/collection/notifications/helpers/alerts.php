<?php
/**
 * Alerts Helper
 *
 * This file contains the class Alerts
 * with methods to manage the users alerts
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
 * Alerts class provides the methods to manage the users alerts
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.3
*/
class alerts {
    
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

        // Load Notifications Alerts Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'models/', 'Notifications_alerts_model', 'notifications_alerts_model' );

    }

    /**
     * The public method load_alerts loads the alerts
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function load_alerts() {

        // Set where parameters
        $where = array(
            'user_id' => $this->CI->user_id,
            'plan' => the_user_option('plan'),
            'language' => $this->CI->config->item('language')
        );

        // Get alerts alerts
        $alerts = $this->CI->notifications_alerts_model->the_alerts($where);

        // Verify if alerts alerts exists
        if ( $alerts ) {

            // User's alerts
            $user_alerts = array();

            // List found alerts
            foreach ( $alerts['alerts'] as $alert ) {

                // Set alert
                $user_alerts[] = array(
                    'alert_id' => $alert['alert_id'],
                    'alert_type' => $alert['alert_type'],
                    'content' => html_entity_decode($alert['content'])
                );

            }
            
            // Prepare the success response
            $data = array(
                'success' => TRUE,
                'alerts' => $user_alerts
            );

            // Display the succes response
            echo json_encode($data);
            exit();
            
        }

        // Prepare the error response
        $data = array(
            'success' => FALSE
        );

        // Display the error response
        echo json_encode($data);

    }

    /**
     * The public method hide_alert hides an alert
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function hide_alert() {
        
        // Get alert's input
        $alert = $this->CI->input->get('alert', TRUE);  

        // Verify if the alert is numeric
        if ( is_numeric($alert) ) {

            // Get the alert
            $alert_data = $this->CI->base_model->get_data_where(
                'notifications_alerts',
                'alert_type',
                array(
                    'alert_id' => $alert
                )
            );

            // Verify if the alert exists
            if ( !$alert_data ) {

                // Prepare the error response
                $data = array(
                    'success' => FALSE
                );

                // Display the error response
                echo json_encode($data);
                exit();
                
            }

            // If alert type is 2 it can't be deleted
            if ( $alert_data[0]['alert_type'] === '2' ) {

                // Prepare the error response
                $data = array(
                    'success' => FALSE
                );

                // Display the error response
                echo json_encode($data);
                exit();

            }
            
            // Get the alert
            $alert_user = $this->CI->base_model->get_data_where(
                'notifications_alerts_users',
                'alert_id',
                array(
                    'alert_id' => $alert,
                    'user_id' => $this->CI->user_id
                )
            );

            // Verify if the alert exists
            if ( $alert_user ) {

                // Try to change the alert's status
                if ( $this->CI->base_model->update('notifications_alerts_users', array('alert_id' => $alert), array('banner_seen' => 1)) ) {

                    // Prepare the success response
                    $data = array(
                        'success' => TRUE
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
                    'banner_seen' => 1,
                    'page_seen' => 0,
                    'deleted' => 0,
                    'updated' => time(),
                    'created' => time()
                );

                // Save the user's activity by using the Base's Model
                if ( $this->CI->base_model->insert('notifications_alerts_users', $user_activity) ) {

                    // Prepare the success response
                    $data = array(
                        'success' => TRUE
                    );
        
                    // Display the success response
                    echo json_encode($data);
                    exit();
                    
                }

            }

        }

        // Prepare the error response
        $data = array(
            'success' => FALSE
        );

        // Display the error response
        echo json_encode($data);
        
    }

    /**
     * The public method load_error_alerts loads the error alerts
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function load_error_alerts() {

        // Set where parameters
        $where = array(
            'user_id' => $this->CI->user_id,
            'plan' => the_user_option('plan'),
            'language' => $this->CI->config->item('language')
        );

        // Get alerts alerts
        $alerts = $this->CI->notifications_alerts_model->the_error_alerts($where);

        // Verify if alerts alerts exists
        if ( $alerts ) {

            // User's alerts
            $user_alerts = array();

            // List found alerts
            foreach ( $alerts['alerts'] as $alert ) {

                // Set alert
                $user_alerts[] = array(
                    'alert_id' => $alert['alert_id'],
                    'alert_type' => $alert['alert_type'],
                    'content' => html_entity_decode($alert['content'])
                );

            }
            
            // Prepare the success response
            $data = array(
                'success' => TRUE,
                'alerts' => $user_alerts
            );

            // Display the succes response
            echo json_encode($data);
            exit();
            
        }

        // Prepare the error response
        $data = array(
            'success' => FALSE
        );

        // Display the error response
        echo json_encode($data);

    }

}

/* End of file alerts.php */