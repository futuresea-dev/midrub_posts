<?php
/**
 * Members Helpers
 *
 * This file contains the class Members
 * with methods to manage the members
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/midrub_cms Midrub’s License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.3
 */

// Define the page namespace
namespace MidrubBase\Admin\Components\Collection\Members\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\Classes\Email as MidrubBaseClassesEmail;
use MidrubBase\Classes\Media as MidrubBaseClassesMedia;

/*
 * Members class provides the methods to manage the members
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/midrub_cms Midrub’s License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.3
*/
class Members {
    
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

        // Load Base Plans Model
        $this->CI->load->ext_model( MIDRUB_BASE_PATH . 'models/', 'Base_plans', 'base_plans' );

        // Load Base Users Model
        $this->CI->load->ext_model( MIDRUB_BASE_PATH . 'models/', 'Base_users', 'base_users' );

        // Load Base Teams Model
        $this->CI->load->ext_model( MIDRUB_BASE_PATH . 'models/', 'Base_teams', 'base_teams' );

        // Load the bcrypt library
        $this->CI->load->library('bcrypt');

        // Require the Members Fields Inc file
        require_once MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS . 'inc/members_fields.php';

        // Require the Members Fields Templates Inc file
        require_once MIDRUB_BASE_ADMIN_COMPONENTS_MEMBERS . 'inc/members_fields_templates.php'; 
        
    }

    /**
     * The public method members_save_member saves members
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function members_save_member() {
        
        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('member_id', 'Member ID', 'trim|numeric');
            $this->CI->form_validation->set_rules('fields', 'Fields', 'trim');
            
            // Get received data
            $member_id = $this->CI->input->post('member_id');
            $fields = $this->CI->input->post('fields');
           
            // Check form validation
            if ($this->CI->form_validation->run() !== false ) {

                // Verify if fields exists
                if ( $fields ) {

                    // Get IP
                    $ip = $this->CI->input->ip_address();

                    // Default member's fields
                    $member_default = array(
                        'username' => '',
                        'email' => '',
                        'last_name' => '',
                        'first_name' => '',
                        'password' => '',
                        'role' => 0,
                        'status' => 1,
                        'date_joined' => date('Y-m-d H:i:s'),
                        'ip_address' => $ip
                    ); 

                    // Additional member's fields
                    $member_additional = array();                    

                    // List all fields
                    foreach ( $fields AS $slug => $args ) {

                        // Get field
                        $get_field = the_admin_members_field($slug);
                        
                        // Verify if the field is required
                        if ( empty($get_field[$slug]['field_required']) ) {

                            // Verify if is default field
                            if ( isset($member_default[$slug]) ) {

                                // Validate the field
                                $validtion_response = validate_admin_members_field($get_field[$slug]['field_type'], $get_field, $args, $member_id);

                                // Verify if response is valid
                                if ( !empty($validtion_response['success']) ) {

                                    // Set field
                                    $member_default[$slug] = $validtion_response['field_value'];                                    

                                }

                            } else {

                                // Validate the field
                                $validtion_response = validate_admin_members_field($get_field[$slug]['field_type'], $get_field, $args, $member_id);

                                // Verify if response is valid
                                if ( !empty($validtion_response['success']) ) {

                                    // Set field
                                    $member_additional[$slug] = $validtion_response['field_value'];                                    

                                }

                            }

                        } else {

                            // Validate the field
                            $validtion_response = validate_admin_members_field($get_field[$slug]['field_type'], $get_field, $args, $member_id);

                            // Verify if is default field
                            if ( isset($member_default[$slug]) ) {

                                // Verify if the required field is numeric
                                if ( is_numeric($member_default[$slug]) ) {

                                    // Verify if response is valid
                                    if ( !empty($validtion_response['success']) ) {                                    

                                        // Verify if field's value is numeric
                                        if ( is_numeric($validtion_response['field_value']) ) {

                                            // Set field
                                            $member_default[$slug] = (int)$validtion_response['field_value'];                                        

                                        } else {

                                            // Verify if value is null
                                            if ( !$validtion_response['field_value'] ) {

                                                // Set field
                                                $member_default[$slug] = 0;  

                                            } else {

                                                // Prepare the error message
                                                $data = array(
                                                    'success' => FALSE,
                                                    'message' => str_replace('[field]', $get_field[$slug]['field_name'], $this->CI->lang->line('members_the_field_should_be_numeric'))
                                                );

                                                // Display error message
                                                echo json_encode($data);
                                                exit();

                                            }
                                            
                                        }

                                    } else {

                                        // Prepare the error message
                                        $data = array(
                                            'success' => FALSE,
                                            'message' => $validtion_response['message']
                                        );

                                        // Display error message
                                        echo json_encode($data);
                                        exit();

                                    }

                                } else {

                                    // Verify if response is valid
                                    if ( !empty($validtion_response['success']) ) {

                                        // Set field
                                        $member_default[$slug] = $validtion_response['field_value'];                                    

                                    } else {

                                        // Prepare the error message
                                        $data = array(
                                            'success' => FALSE,
                                            'message' => $validtion_response['message']
                                        );

                                        // Display error message
                                        echo json_encode($data);
                                        exit();

                                    }

                                }

                            } else {

                                // Verify if response is valid
                                if ( !empty($validtion_response['success']) ) {

                                    // Set field
                                    $member_additional[$slug] = $validtion_response['field_value'];                                    

                                } else {

                                    // Prepare the error message
                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $validtion_response['message']
                                    );

                                    // Display error message
                                    echo json_encode($data);
                                    exit();

                                }

                            }

                        }
                        
                    }

                    // Verify if email exists
                    if ( empty($member_default['email']) ) {

                        // Prepare the error message
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('members_the_email_is_required')
                        );

                        // Display error message
                        echo json_encode($data);
                        exit();                        

                    } else if ($this->CI->base_users->get_user_ceil('email', $member_default['email'])) {

                        // Verify if $member_id exists
                        if ( $member_id ) {

                            // Get member by email
                            $get_member = $this->CI->base_model->get_data_where('users', 'user_id', array('email' => $member_default['email']));

                            // Verify if $get_member is not empty
                            if ( !empty($get_member) ) {

                                // Verify if user_id is not of the current member
                                if ( $get_member[0]['user_id'] !== $member_id ) {

                                    // Display error message
                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('members_the_email_already_in_use')
                                    );
                        
                                    echo json_encode($data);
                                    exit();

                                }

                            }

                        } else {

                            // Display error message
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('members_the_email_already_in_use')
                            );
                
                            echo json_encode($data);
                            exit();

                        }
            
                    } else if ($this->CI->base_teams->check_member_email($member_default['email'])) {

                        // Display error message
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('members_the_email_already_in_use')
                        );
            
                        echo json_encode($data);
                        exit();
            
                    }

                    // Verify if first name exists
                    if ( empty($member_default['first_name']) ) {

                        // Prepare the error message
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('members_the_first_name_is_required')
                        );

                        // Display error message
                        echo json_encode($data);
                        exit();                        

                    }

                    // Verify if last name exists
                    if ( empty($member_default['last_name']) ) {

                        // Prepare the error message
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('members_the_last_name_is_required')
                        );

                        // Display error message
                        echo json_encode($data);
                        exit();                        

                    }

                    // Decrypted password
                    $decrypted_password = '';

                    // Verify if member's ID exists
                    if ( !$member_id ) {

                        // Verify if password exists
                        if ( empty($member_default['password']) ) {

                            // Prepare the error message
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('members_the_password_is_required')
                            );

                            // Display error message
                            echo json_encode($data);
                            exit();                        

                        } else {

                            // Set $decrypted_password's value
                            $decrypted_password = $member_default['password'];

                            // Encrypt the password
                            $member_default['password'] = $this->CI->bcrypt->hash_password($member_default['password']);

                        }

                    } else {

                        // Verify if password exists
                        if ( empty($member_default['password']) ) {

                            // Remove the password
                            unset($member_default['password']);                       

                        } else {

                            // Set $decrypted_password's value
                            $decrypted_password = $member_default['password'];

                            // Encrypt the password
                            $member_default['password'] = $this->CI->bcrypt->hash_password($member_default['password']);

                        }
                        
                    }

                    // Verify if member's ID exists
                    if ( !$member_id ) {

                        // Verify if username exists
                        if ( empty($member_default['username']) ) {

                            // Prepare the error message
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('members_the_username_is_required')
                            );

                            // Display error message
                            echo json_encode($data);
                            exit();                        

                        } else if ( strlen($member_default['username']) < 6 ) {

                            // Prepare the error message
                            $data = array(
                                'success' => FALSE,
                                'message' => str_replace('[field]', $this->CI->lang->line('members_the_username'), $this->CI->lang->line('members_the_field_too_short'))
                            );

                            // Display error message
                            echo json_encode($data);
                            exit();    

                        } else if ( strlen($member_default['username']) > 20 ) {

                            // Prepare the error message
                            $data = array(
                                'success' => FALSE,
                                'message' => str_replace('[field]', $this->CI->lang->line('members_the_username'), $this->CI->lang->line('members_the_field_too_long'))
                            );

                            // Display error message
                            echo json_encode($data);
                            exit();    

                        } else if ( preg_match('/\s/', $member_default['username']) ) {

                            // Prepare the error message
                            $data = array(
                                'success' => FALSE,
                                'message' => str_replace('[field]', $this->CI->lang->line('members_the_username'), $this->CI->lang->line('members_the_field_without_white_spaces'))
                            );

                            // Display error message
                            echo json_encode($data);
                            exit();    

                        } else if ( preg_match('/\s/', $member_default['username']) ) {

                            // Prepare the error message
                            $data = array(
                                'success' => FALSE,
                                'message' => str_replace('[field]', $this->CI->lang->line('members_the_username'), $this->CI->lang->line('members_the_field_without_white_spaces'))
                            );

                            // Display error message
                            echo json_encode($data);
                            exit();    

                        } else if ( preg_match('[@_!#$%^&*()<>?/|}{~:]', $member_default['username']) ) {

                            // Prepare the error message
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('members_the_username_without_special_characters')
                            );

                            // Display error message
                            echo json_encode($data);
                            exit();    

                        } else if ($this->CI->base_users->get_user_ceil('username', $member_default['username'])) {

                            // Display error message
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('members_the_username_already_in_use')
                            );
                
                            echo json_encode($data);
                            exit();
                            
                        }

                    }

                    // Verify if member's ID exists
                    if ( !$member_id ) {
                    
                        // Save user by using the Base's Model
                        $member_id = $this->CI->base_model->insert('users', $member_default);

                    } else {

                        // Remove username
                        unset($member_default['username']);                        

                        // Remove data_joined
                        unset($member_default['date_joined']);

                        // Remove ip_address
                        unset($member_default['ip_address']);

                        // Update use by using the Base's Model
                        $this->CI->base_model->update('users', array('user_id' => $member_id), $member_default);
                        
                    }

                    // Verify if the member was saved
                    if ( $member_id ) {

                        // Verify if additional exists
                        if ( $member_additional ) {

                            // List all additional fields
                            foreach ( $member_additional as $key => $value ) {

                                // Verify if $key is send_email
                                if ( $key === 'send_email' ) {
                                    continue;
                                }

                                // Verify if $key is plan
                                if ( $key === 'plan' && ($member_default['role'] < 1) ) {

                                    // Verify if user has changed the plan
                                    if ( the_user_option('plan', $member_id) !== $value ) {

                                        // Get plan's data
                                        $plan_data = $this->CI->base_plans->get_plan($value);

                                        // Verify if plan exists
                                        if ($plan_data) {

                                            // Set the user plan
                                            $this->CI->plans->change_plan($value, $member_id);

                                        }

                                    }

                                } else if ($key !== 'plan') {

                                    // Set member's meta
                                    update_user_option($member_id, $key, $value);

                                }

                            }

                        }

                        // Verify if member was updated
                        if ( $this->CI->input->post('member_id') ) {

                            // Verify if additional exists
                            if ( $member_additional ) {

                                // List all additional fields
                                foreach ( $member_additional as $key => $value ) {

                                    // Verify if member will receive an email
                                    if ( ( $key === 'send_email' ) && ( $value === '1' ) ) {

                                        // New Password
                                        $new_password = '';

                                        // Verify if $decrypted_password exists
                                        if ( $decrypted_password ) {

                                            // Set new password
                                            $new_password = '<p>' . $this->CI->lang->line('members_your_new_password_is') . ' ' . $decrypted_password . '</p><br>';

                                        }

                                        // Create email's subject
                                        $subject = $this->CI->lang->line('members_account_information');

                                        // Create email's body
                                        $body = '<p>' . $this->CI->lang->line('members_your_account_was_updated') . ' ' . $this->CI->config->item('site_name') . '</p><br>'
                                        . $new_password
                                        . '<p>' . $this->CI->lang->line('members_cheers') . '</p>'
                                        . '<p>' . str_replace('[site_name]', $this->CI->config->item('site_name'), $this->CI->lang->line('members_the_site_team')) . '</p>';

                                        // Placeholders
                                        $placeholders = array('[first_name]', '[last_name]', '[website_name]', '[email]', '[password]', '[login_url]', '[website_url]');

                                        // Set first name
                                        $first_name = $member_default['first_name'];
                            
                                        // Set last name
                                        $last_name = $member_default['last_name'];

                                        // Get account updated template
                                        $account_updated = the_admin_notifications_email_template('members_member_account_updated', $this->CI->config->item('language'));

                                        // Verify if $account_updated exists
                                        if ( $account_updated ) {

                                            // New subject
                                            $subject = $account_updated[0]['template_title'];

                                            // New body
                                            $body = $account_updated[0]['template_body'];

                                        }

                                        // Replacers
                                        $replacers = array(
                                            $first_name,
                                            $last_name,
                                            $this->CI->config->item('site_name'),
                                            $member_default['email'],
                                            $decrypted_password,
                                            the_url_by_page_role('sign_in') ? the_url_by_page_role('sign_in') : site_url('auth/signin'),
                                            site_url()
                                        );

                                        // Create email's args
                                        $email_args = array(
                                            'from_name' => $this->CI->config->item('site_name'),
                                            'from_email' => $this->CI->config->item('contact_mail'),
                                            'to_email' => $member_default['email'],
                                            'subject' => str_replace($placeholders, $replacers, $subject),
                                            'body' => str_replace($placeholders, $replacers, $body)
                                        );

                                        // Send template
                                        (new MidrubBaseClassesEmail\Send())->send_mail($email_args);

                                    }

                                }

                            }

                            // Prepare success message
                            $data = array(
                                'success' => TRUE,
                                'message' => $this->CI->lang->line('members_member_was_updated')
                            );

                            // Display the success message
                            echo json_encode($data);

                        } else {

                            // Verify if additional exists
                            if ( $member_additional ) {

                                // List all additional fields
                                foreach ( $member_additional as $key => $value ) {

                                    // Verify if member will receive an email
                                    if ( ( $key === 'send_email' ) && ( $value === '1' ) ) {

                                        // Create email's subject
                                        $subject = $this->CI->lang->line('members_account_information');

                                        // Create email's body
                                        $body = $this->CI->lang->line('members_your_account_was_created') . ' ' . $this->CI->config->item('site_name')
                                        . '<br>'
                                        . '<p>' . $this->CI->lang->line('members_you_can_login_here') . ': [login_url]</p><br>'
                                        . '<p>' . $this->CI->lang->line('members_email') . ': ' . $member_default['email'] . '</p>'
                                        . '<p>' . $this->CI->lang->line('members_password') . ': ' . $decrypted_password . '</p><br>'
                                        . '<p>' . $this->CI->lang->line('members_cheers') . '</p>'
                                        . '<p>' . str_replace('[site_name]', $this->CI->config->item('site_name'), $this->CI->lang->line('members_the_site_team')) . '</p>';

                                        // Placeholders
                                        $placeholders = array('[first_name]', '[last_name]', '[website_name]', '[email]', '[password]', '[login_url]', '[website_url]');

                                        // Set first name
                                        $first_name = $member_default['first_name'];
                            
                                        // Set last name
                                        $last_name = $member_default['last_name'];

                                        // Get account updated template
                                        $account_updated = the_admin_notifications_email_template('members_member_account_created', $this->CI->config->item('language'));

                                        // Verify if $account_updated exists
                                        if ( $account_updated ) {

                                            // New subject
                                            $subject = $account_updated[0]['template_title'];

                                            // New body
                                            $body = $account_updated[0]['template_body'];

                                        }

                                        // Replacers
                                        $replacers = array(
                                            $first_name,
                                            $last_name,
                                            $member_default['email'],
                                            $decrypted_password,
                                            $this->CI->config->item('site_name'),
                                            the_url_by_page_role('sign_in') ? the_url_by_page_role('sign_in') : site_url('auth/signin'),
                                            site_url()
                                        );

                                        // Create email's args
                                        $email_args = array(
                                            'from_name' => $this->CI->config->item('site_name'),
                                            'from_email' => $this->CI->config->item('contact_mail'),
                                            'to_email' => $member_default['email'],
                                            'subject' => str_replace($placeholders, $replacers, $subject),
                                            'body' => str_replace($placeholders, $replacers, $body)
                                        );

                                        // Send template
                                        (new MidrubBaseClassesEmail\Send())->send_mail($email_args);

                                    }

                                }

                            }

                            // Prepare success message
                            $data = array(
                                'success' => TRUE,
                                'message' => $this->CI->lang->line('members_member_was_saved')
                            );

                            // Display the success message
                            echo json_encode($data);

                        }

                    } else {

                        // Verify if member was updated
                        if ( $this->CI->input->post('member_id') ) {
                            
                            // Prepare error message
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('members_member_was_not_updated')
                            );

                            // Display the error message
                            echo json_encode($data);
                            
                        } else {

                            // Prepare error message
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('members_member_was_not_saved')
                            );

                            // Display the error message
                            echo json_encode($data);

                        }

                    }

                    exit();

                }

            }
            
        }

        // Prepare error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('members_error_has_occurred')
        );

        // Delete the error message
        echo json_encode($data);
        
    }

    /**
     * The public method members_load_all loads members
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function members_load_all() {
        
        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('page', 'Page', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            
            // Get received data
            $page = $this->CI->input->post('page');
            $key = $this->CI->input->post('key');
           
            // Check form validation
            if ($this->CI->form_validation->run() !== false ) {

                // Set the limit
                $limit = 10;
                $page--;

                // Prepare arguments for request
                $args = array(
                    'start' => ($page * $limit),
                    'limit' => $limit,
                    'key' => $this->CI->db->escape_like_str($key)
                );
                
                // Use the base model for a simply sql query
                $get_members = $this->CI->base_users->the_members_list($args);

                // Verify if members exists
                if ( $get_members ) {

                    // Prepare arguments for request
                    $args = array(
                        'key' => $this->CI->db->escape_like_str($key)
                    );
                    
                    // Use the base model for a simply sql query
                    $total = $this->CI->base_users->the_members_list($args);   
                    
                    // Members container
                    $members = array();

                    // List members
                    foreach ( $get_members as $get_member ) {

                        // Set member
                        $members[] = array(
                            'user_id' => $get_member['user_id'],
                            'username' => $get_member['username'],
                            'email' => $get_member['email'],
                            'last_name' => $get_member['last_name'],
                            'first_name' => $get_member['first_name'],
                            'role' => $get_member['role'],
                            'status' => $get_member['status'],
                            'time_joined' => strtotime($get_member['date_joined']),
                            'avatar' => '//www.gravatar.com/avatar/' . md5($get_member['email'])
                        );

                    }

                    // Prepare the response
                    $data = array(
                        'success' => TRUE,
                        'members' => $members,
                        'total' => $total,
                        'page' => ($page + 1),
                        'current_time' => time(),
                        'words' => array(
                            'delete' => $this->CI->lang->line('members_delete'),
                            'active' => $this->CI->lang->line('members_active'),
                            'inactive' => $this->CI->lang->line('members_inactive'),
                            'blocked' => $this->CI->lang->line('members_blocked'),
                            'administrator' => strtolower($this->CI->lang->line('members_administrator')),
                            'user' => strtolower($this->CI->lang->line('members_user'))
                        )
                    );

                    // Display the response
                    echo json_encode($data);
                    exit();

                }

            }
            
        }

        // Display error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('members_no_members_found')
        );

        echo json_encode($data);
        
    }

    /**
     * The public method members_delete_member deletes a member
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function members_delete_member() {

        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('member_id', 'Member ID', 'trim|numeric|required');
            
            // Get received data
            $member_id = $this->CI->input->post('member_id');
           
            // Check form validation
            if ($this->CI->form_validation->run() !== false ) {

                // Try to delete the member
                $response = $this->delete_member($member_id);

                // Display response
                echo json_encode($response);
                exit();

            }
            
        }

        // Prepare error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('members_error_has_occurred')
        );

        // Delete the error message
        echo json_encode($data);

    }

    /**
     * The public method members_delete_members deletes members by members ids
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */ 
    public function members_delete_members() {

        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('members_ids', 'Members Ids', 'trim');
           
            // Get received data
            $members_ids = $this->CI->input->post('members_ids');

            // Check form validation
            if ($this->CI->form_validation->run() !== false ) {

                // Verify if members ids exists
                if ( $members_ids ) {

                    // Count number of deleted members
                    $count = 0;

                    // List all members
                    foreach ( $members_ids as $member_id ) {

                        // Try to delete the member
                        $response = $this->delete_member($member_id);

                        // Verify if the member was deleted
                        if ( !empty($response['success']) ) {
                            $count++;
                        }

                    }

                    // Prepare success message
                    $data = array(
                        'success' => TRUE,
                        'message' => $count . ' ' . $this->CI->lang->line('members_were_deleted_succesfully')
                    );

                    // Display the success message
                    echo json_encode($data);

                } else {

                    // Prepare the error message
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('notifications_no_alerts_found')
                    );

                    // Display the error message
                    echo json_encode($data);

                }

                exit();

            }

        }

        // Prepare error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('members_error_has_occurred')
        );

        // Delete the error message
        echo json_encode($data);

    }

    /**
     * The protected method delete_member deletes a member
     * 
     * @param integer $member_id contains the member's ID
     * 
     * @since 0.0.8.3
     * 
     * @return array with response
     */
    protected function delete_member($member_id) {

        // Get member
        $member = $this->CI->base_users->get_user_ceil('user_id', $member_id);

        // Verify if member exists
        if ( !$member ) {

            // Prepare the error message
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('members_the_member_was_not_found')
            ); 

        }

        // Verify if administrator wants to delete his account
        if ( $member === $this->CI->user_id ) {

            // Prepare the error message
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('members_administrator_can_not_delete_his_account')
            ); 

        } 

        try {

            // List all apps
            foreach (glob(APPPATH . 'base/user/apps/collection/*', GLOB_ONLYDIR) as $dir) {

                // Get app's directory
                $app_dir = trim(basename($dir) . PHP_EOL);

                // Create an array
                $array = array(
                    'MidrubBase',
                    'User',
                    'Apps',
                    'Collection',
                    ucfirst($app_dir),
                    'Main'
                );

                // Implode the array above
                $cl = implode('\\', $array);

                // Delete user's data
                (new $cl())->delete_account($member_id);

            }

        } catch ( Exception $e ) {

            // Prepare the error message
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('members_app_member_records_can_not_be_deleted')
            );                  

        }

        /** 

        try {

            // List all user's components
            foreach (glob(APPPATH . 'base/user/components/collection/*', GLOB_ONLYDIR) as $dir) {

                // Get app's directory
                $app_dir = trim(basename($dir) . PHP_EOL);

                // Create an array
                $array = array(
                    'MidrubBase',
                    'User',
                    'Components',
                    'Collection',
                    ucfirst($app_dir),
                    'Main'
                );

                // Implode the array above
                $cl = implode('\\', $array);

                // Delete user's data
                (new $cl())->delete_account($member_id);

            }

        } catch ( Exception $e ) {

            // Prepare the error message
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('members_component_member_records_can_not_be_deleted')
            );

            // Display error message
            echo json_encode($data);
            exit();                     

        }

        */

        // Delete all member's networks
        $this->CI->base_model->delete('networks', array('user_id' => $member_id));

        // Get all user medias
        $get_medias = $this->CI->base_model->get_data_where('media', '*', array('user_id' => $member_id));

        // Verify if user has media and delete them
        if ( $get_medias ) {
            
            // List all media files
            foreach( $get_medias as $media ) {

                // Delete media
                (new MidrubBaseClassesMedia\Delete)->delete_file(array(
                    'media_id' => $media['media_id'],
                    'user_id' => $member_id
                ), true);

            }
            
        }

        // Try to delete the member's team
        $this->CI->base_teams->delete_members($member_id);

        // Try to delete the member
        if ( $this->CI->base_users->delete_user($member_id) ) {
            
            // Prepare success message
            return array(
                'success' => TRUE,
                'message' => $this->CI->lang->line('members_member_was_deleted')
            );
            
        } else {
            
            // Prepare error message
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('members_member_was_not_deleted')
            );
            
        }

    }

}

/* End of file members.php */