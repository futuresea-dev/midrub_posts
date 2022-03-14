<?php
/**
 * Notifications Model
 *
 * PHP Version 7.3
 *
 * Blue_notifications_model file contains the Notifications Model
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
 * Blue_notifications_model class - operates the notifications alerts tables.
 *
 * @since 0.0.7.9
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Blue_notifications_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'notifications';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();
        
        // Get notifications_alerts table
        $notifications_alerts = $this->db->table_exists('notifications_alerts');
        
        // Verify if the notifications_alerts table exists
        if ( !$notifications_alerts ) {
            
            // Create the notifications_alerts table
            $this->db->query('CREATE TABLE IF NOT EXISTS `notifications_alerts` (
                              `alert_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `alert_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `alert_type` smallint(1) NOT NULL,
                              `alert_audience` smallint(1) NOT NULL,
                              `created` varchar(30) NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }
        
        // Get notifications_alerts_fields table
        $notifications_alerts_fields = $this->db->table_exists('notifications_alerts_fields');
        
        // Verify if the notifications_alerts_fields table exists
        if ( !$notifications_alerts_fields ) {
            
            // Create the notifications_alerts_fields table
            $this->db->query('CREATE TABLE IF NOT EXISTS `notifications_alerts_fields` (
                              `field_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `alert_id` bigint(20) NOT NULL,
                              `field_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `field_value` VARBINARY(4000) NOT NULL,
                              `field_extra` VARBINARY(4000) NOT NULL,
                              `language` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }

        // Get notifications_alerts_filters table
        $notifications_alerts_filters = $this->db->table_exists('notifications_alerts_filters');

        // Verify if the notifications_alerts_filters table exists
        if ( !$notifications_alerts_filters ) {
            
            // Create the notifications_alerts_filters table
            $this->db->query('CREATE TABLE IF NOT EXISTS `notifications_alerts_filters` (
                                `filter_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                                `alert_id` bigint(20) NOT NULL,
                                `filter_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                `filter_value` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                `filter_extra` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }

        // Get notifications_alerts_users table
        $notifications_alerts_users = $this->db->table_exists('notifications_alerts_users');

        // Verify if the notifications_alerts_users table exists
        if ( !$notifications_alerts_users ) {
            
            // Create the notifications_alerts_users table
            $this->db->query('CREATE TABLE IF NOT EXISTS `notifications_alerts_users` (
                                `id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                                `alert_id` bigint(20) NOT NULL,
                                `user_id` bigint(20) NOT NULL,
                                `banner_seen` smallint(1) NOT NULL,
                                `page_seen` smallint(1) NOT NULL,
                                `deleted` smallint(1) NOT NULL,
                                `updated` varchar(30) NOT NULL,
                                `created` varchar(30) NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }

    /**
     * The public method the_notifications_alerts gets all alerts from database
     *
     * @param array $args contains the query's parameters
     * 
     * @return array with results or false
     */
    public function the_notifications_alerts($args) {

        // Select data
        $this->db->select("notifications_alerts.alert_id, notifications_alerts_fields.field_value as name, enabled.field_value as enabled, plans.filter_value as plans,
        languages.filter_value as languages, notifications_alerts_users.deleted");

        // From notifications_alerts
        $this->db->from('notifications_alerts');

        // Join notifications_alerts_fields
        $this->db->join('notifications_alerts_fields', "notifications_alerts.alert_id=notifications_alerts_fields.alert_id AND notifications_alerts_fields.field_name='page_title'", 'left');
        $this->db->join('notifications_alerts_fields enabled', "notifications_alerts.alert_id=enabled.alert_id AND enabled.field_name='page_enabled'", 'left');
        $this->db->join('notifications_alerts_filters plans', "notifications_alerts.alert_id=plans.alert_id AND plans.filter_name='plans'", 'left');
        $this->db->join('notifications_alerts_filters languages', "notifications_alerts.alert_id=languages.alert_id AND languages.filter_name='languages'", 'left');
        $this->db->join('notifications_alerts_users', 'notifications_alerts.alert_id=notifications_alerts_users.alert_id', 'left');

        // Set where
        $this->db->where(array(
            'enabled.field_value' => '1',
            'notifications_alerts.alert_audience' => '0',
            'notifications_alerts_fields.language' => $args['language']
        ));

        // Group by
        $this->db->group_by('notifications_alerts.alert_id');

        // Order by
        $this->db->order_by('notifications_alerts.alert_id', 'desc');

        // Get data
        $query = $this->db->get();
        
        // Verify if alerts exists
        if ( $query->num_rows() > 0 ) {

            // Get alerts
            $alerts = $query->result_array();

            // notifications alerts
            $notifications = array();

            // List alerts
            for ( $p = 0; $p < $query->num_rows(); $p++ ) {

                // Verify if the alert has page
                if ( empty($alerts[$p]['enabled']) ) {
                    continue;
                }

                // Verify if the alert is deleted
                if ( !empty($alerts[$p]['deleted']) ) {
                    continue;
                }                

                // Available counter
                $available = 0;

                // Verify if plans exists
                if ( !empty(unserialize($alerts[$p]['plans'])) ) {

                    // List available plans
                    foreach ( unserialize($alerts[$p]['plans']) as $plan ) {

                        // Verify if the user plan is allowed
                        if ( $plan === $args['plan'] ) {

                            // Increase the counter
                            $available++;                            

                        }

                    }

                } else {

                    // Increase the counter
                    $available++;

                }

                // Verify if languages exists
                if ( !empty(unserialize($alerts[$p]['languages'])) ) {

                    // List available languages
                    foreach ( unserialize($alerts[$p]['languages']) as $language ) {

                        // Verify if the user language is allowed
                        if ( $language === $args['language'] ) {

                            // Increase the counter
                            $available++;                            

                        }

                    }

                } else {

                    // Increase the counter
                    $available++;

                }
                
                // Verify if $available is higher than 1
                if ( $available > 1 ) {

                    // Set notifications
                    $notifications[] = $alerts[$p]['alert_id'];

                }

            }

            // Verify if notifications exists
            if ( $notifications ) {

                // Select data
                $this->db->select('notifications_alerts_fields.alert_id, notifications_alerts_fields.field_value as name, notifications_alerts_users.page_seen, notifications_alerts.alert_type, notifications_alerts.created');

                // From notifications_alerts
                $this->db->from('notifications_alerts_fields');

                // Join tables
                $this->db->join('notifications_alerts', 'notifications_alerts_fields.alert_id=notifications_alerts.alert_id', 'left');
                $this->db->join('notifications_alerts_users', 'notifications_alerts_fields.alert_id=notifications_alerts_users.alert_id', 'left');

                // Set where
                $this->db->where(array(
                    'notifications_alerts_fields.field_name' => 'page_title',
                    'notifications_alerts_fields.language' => $args['language']
                ));

                // Set where_in
                $this->db->where_in('notifications_alerts_fields.alert_id', $notifications);  
                
                // Set limit
                $this->db->limit(5, 0);

                // Order by
                $this->db->order_by('notifications_alerts_fields.alert_id', 'desc');

                // Get alerts
                $get_alerts = $this->db->get();

                // Verify if alerts exists
                if ( $get_alerts->num_rows() > 0 ) {

                    // Return response
                    return array(
                        'notifications' => $get_alerts->result_array(),
                        'total' => count($notifications)
                    );

                } else {

                    return false;

                }

            } else {

                return false;

            }
            
        } else {
            
            // Return false
            return false;
            
        }
        
    }
    
}

/* End of file blue_notifications_model.php */