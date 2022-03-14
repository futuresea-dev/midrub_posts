<?php
/**
 * Notifications Miscellaneous Model
 *
 * PHP Version 7.3
 *
 * Notifications_miscellaneous_model file contains the Notifications Miscellaneous Model
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
 * Notifications_miscellaneous_model class - operates the notifications alerts tables
 *
 * @since 0.0.8.3
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Notifications_miscellaneous_model extends CI_MODEL {
    
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
        
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }

    /**
     * The public method the_miscellaneous_alerts gets all alerts from database
     *
     * @param array $args contains the query's parameters
     * 
     * @return array with results or false
     */
    public function the_miscellaneous_alerts($args) {

        // Select data
        $this->db->select("notifications_alerts.alert_id, notifications_alerts_fields.field_value as name, enabled.field_value as enabled, plans.filter_value as plans,
        languages.filter_value as languages, notifications_alerts_users.deleted, notifications_alerts_users.page_seen");

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
            'notifications_alerts.alert_type' => '2',
            'notifications_alerts.alert_audience' => '0',
            'notifications_alerts_fields.language' => $args['language']
        ));

        // Group by
        $this->db->group_by(array('notifications_alerts.alert_id', 'notifications_alerts.alert_type'));

        // Order by
        $this->db->order_by('notifications_alerts.alert_id', 'desc');

        // Get data
        $query = $this->db->get();
        
        // Verify if alerts exists
        if ( $query->num_rows() > 0 ) {

            // Get alerts
            $alerts = $query->result_array();

            // miscellaneous alerts
            $miscellaneous = array();

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

                    // Set miscellaneous
                    $miscellaneous[] = $alerts[$p]['alert_id'];

                }

            }

            // Verify if miscellaneous exists
            if ( $miscellaneous ) {

                // Select data
                $this->db->select('notifications_alerts_fields.alert_id, notifications_alerts_fields.field_value as name, notifications_alerts_users.banner_seen, notifications_alerts_users.page_seen');

                // From notifications_alerts
                $this->db->from('notifications_alerts_fields');

                // Join tables
                $this->db->join('notifications_alerts_users', 'notifications_alerts_fields.alert_id=notifications_alerts_users.alert_id', 'left');

                // Set where
                $this->db->where(array(
                    'notifications_alerts_fields.field_name' => 'page_title',
                    'notifications_alerts_fields.language' => $args['language']
                ));

                // Set where_in
                $this->db->where_in('notifications_alerts_fields.alert_id', $miscellaneous);  
                
                // Group by
                $this->db->group_by('notifications_alerts_fields.alert_id');

                // Set limit
                $this->db->limit($args['limit'], $args['page']);

                // Order by
                $this->db->order_by('notifications_alerts_fields.alert_id', 'desc');

                // Get alerts
                $get_alerts = $this->db->get();

                // Verify if alerts exists
                if ( $get_alerts->num_rows() > 0 ) {

                    // Return response
                    return array(
                        'miscellaneous' => $get_alerts->result_array(),
                        'total' => count($miscellaneous)
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

    /**
     * The public method the_miscellaneous_alert gets the miscellaneous alert
     *
     * @param array $args contains the query's parameters
     * 
     * @return array with alert or false
     */
    public function the_miscellaneous_alert($args) {

        // Select data
        $this->db->select("notifications_alerts.alert_id, notifications_alerts_fields.field_value as name, content.field_value as page_content, enabled.field_value as enabled,
        plans.filter_value as plans, languages.filter_value as languages, notifications_alerts_users.page_seen, notifications_alerts_users.deleted");

        // From notifications_alerts
        $this->db->from('notifications_alerts');

        // Join notifications_alerts_fields
        $this->db->join('notifications_alerts_fields', "notifications_alerts.alert_id=notifications_alerts_fields.alert_id AND notifications_alerts_fields.field_name='page_title'", 'left');
        $this->db->join('notifications_alerts_fields content', "notifications_alerts.alert_id=content.alert_id AND content.field_name='page_content'", 'left');
        $this->db->join('notifications_alerts_fields enabled', "notifications_alerts.alert_id=enabled.alert_id AND enabled.field_name='page_enabled'", 'left');
        $this->db->join('notifications_alerts_filters plans', "notifications_alerts.alert_id=plans.alert_id AND plans.filter_name='plans'", 'left');
        $this->db->join('notifications_alerts_filters languages', "notifications_alerts.alert_id=languages.alert_id AND languages.filter_name='languages'", 'left');
        $this->db->join('notifications_alerts_users', 'notifications_alerts.alert_id=notifications_alerts_users.alert_id', 'left');

        // Set where
        $this->db->where(array(
            'notifications_alerts.alert_id' => $args['alert_id'],
            'enabled.field_value' => '1',
            'notifications_alerts.alert_type' => '2',
            'notifications_alerts.alert_audience' => '0',
            'notifications_alerts_fields.language' => $args['language']
        ));

        // Group by
        $this->db->group_by('notifications_alerts.alert_id');

        // Get data
        $query = $this->db->get();
        
        // Verify if alerts exists
        if ( $query->num_rows() > 0 ) {

            // Get alerts
            $alerts = $query->result_array();

            // miscellaneous alerts
            $miscellaneous = array();

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

                    // Set miscellaneous
                    $miscellaneous[] = $alerts[$p];

                }

            }

            // Verify if miscellaneous exists
            if ( $miscellaneous ) {

                // Return response
                return $miscellaneous;

            } else {

                return false;

            }
            
        } else {
            
            // Return false
            return false;
            
        }
        
    }

    /**
     * The public method mark_banner_as_seen marks an alert's banner as seen
     *
     * @param array $alert_ids contains the alerts ids which should be updated
     * 
     * @return boolean true or false
     */
    public function mark_banner_as_seen($alert_ids) {

        // Set data
        $this->db->set('banner_seen', 1);

        // Where in
        $this->db->where_in('alert_id', $alert_ids);

        // Update table
        $this->db->update('notifications_alerts_users');
        
        // Verify if alerts exists
        if ( $this->db->affected_rows() ) {

            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
}

/* End of file notifications_miscellaneous_model.php */