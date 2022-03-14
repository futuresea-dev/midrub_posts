<?php
/**
 * Frontend Midrub Theme Model
 *
 * PHP Version 7.3
 *
 * frontend_midrub_theme_model file contains the Frontend Midrub Theme Model
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */

 // Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Frontend_midrub_theme_model class - operates the plans_groups table.
 *
 * @since 0.0.8.2
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Frontend_midrub_theme_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'oauth_tokens';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();
        
        // Get plans_groups table
        $plans_groups = $this->db->table_exists('plans_groups');

        // Verify if the plans_groups table exists
        if (!$plans_groups) {

            $this->db->query('CREATE TABLE IF NOT EXISTS `plans_groups` (
                `group_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `group_name` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `created` varchar(30) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

        }
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }

    /**
     * The public method get_public_plans gets plans which are public
     * 
     * @return array with data or boolean false
     */
    public function get_public_plans() {

        // Get selected groups plans
        $get_groups = $this->base_model->get_data_where(
            'plans_meta',
            'plan_id',
            array(
                'meta_name' => 'plans_group'
            )
        );

        // Ids array
        $ids = array();
        
        // Verify if selected groups plans exists
        if ( $get_groups ) {

            // List all groups
            foreach ( $get_groups as $get_group ) {

                // Set id
                $ids[] = $get_group['plan_id'];

            }

        }

        // If ids is not empty
        if ( $ids ) {

            // Select columns
            $this->db->select('plans.*, plans_meta.meta_value AS plans_group, plans_groups.group_name');

            // From plans table
            $this->db->from('plans');

            // Set where
            $this->db->where(array(
                'plans.visible' => 0,
                'plans_meta.meta_name' => 'plans_group'
            ));            

            // Set where in
            $this->db->where_in('plans.plan_id', $ids);

            // Set join
            $this->db->join('plans_meta', 'plans.plan_id=plans_meta.plan_id', 'LEFT');
            $this->db->join('plans_groups', 'plans_meta.meta_value=plans_groups.group_id', 'LEFT');

            // Set order
            $this->db->order_by('plans_groups.group_id', 'ASC');

        } else {

            // Select columns
            $this->db->select('plans.*');

            // From plans table
            $this->db->from('plans');

            // Set where
            $this->db->where('plans.visible', 0);

            // Set order
            $this->db->order_by('plans.plan_id', 'ASC');

        }
        
        // Get data
        $query = $this->db->get();
        
        // Verify if data exists
        if ( $query->num_rows() > 0 ) {

            // If ids is not empty
            if ( $ids ) {

                // Get response
                $response = $query->result_array();

                // Groups array
                $groups = array();

                // List all plans
                foreach ( $response as $plan ) {

                    // Verify if group already exists
                    if ( !isset($groups[$plan['plans_group']]) ) {
                        $groups[$plan['plans_group']] = array(
                            'group_id' => $plan['plans_group'],
                            'group_name' => $plan['group_name'],
                            'plans' => array()
                        );
                    }

                    // Set plan
                    $groups[$plan['plans_group']]['plans'][] = $plan;

                }

                // Return groups
                return array_values($groups);

            } else {

                // Return data
                return $query->result_array();

            }

            
        } else {
            
            return false;
            
        }
        
    }
    
}

/* End of file frontend_midrub_theme_model.php */