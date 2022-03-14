<?php
/**
 * Members Fields Templates Inc
 *
 * PHP Version 7.3
 *
 * This files contains the templates used 
 * to display the fields templates
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/midrub_cms Midrub’s License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.3
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * The public method set_admin_members_field_template registers a members field template
 * 
 * @since 0.0.8.3
 */
set_admin_members_field_template('text_input', 'set_admin_members_field_template_text_input', 'validate_admin_members_field_template_text_input');

if ( !function_exists('set_admin_members_field_template_text_input') ) {
    
    /**
     * The function set_admin_members_field_template_text_input registers a field's template for text_input
     * 
     * @param array $args contains the template's parameters
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    function set_admin_members_field_template_text_input($args) {

        // Description container
        $description = '';

        // Verify if description exists
        if ( isset($args['words_list']['description']) ) {

            // Set description
            $description = '<small class="form-text text-muted">'
                . $args['words_list']['description']
            . '</small>';

        }

        // Required container
        $required = '';

        // Verify if input is required
        if ( !empty($args['field_required']) ) {

            // Set required value
            $required = 'required';

        }

        // Default value
        $value = '';

        // Verify if value exists
        if ( the_member_option($args['field_slug'], the_global_variable('members_member_id')) ) {

            // Set value
            $value = ' value="' . the_member_option($args['field_slug'], the_global_variable('members_member_id')) . '"';

        }

        // Display the field
        echo '<div class="form-group">'
                . '<div class="row">'
                    . '<div class="col-lg-12">'
                        . '<label for="member-field-' . $args['field_slug'] . '">'
                            . $args['words_list']['label']
                        . '</label>'
                    . '</div>'
                . '</div>'
                . '<div class="row">'
                    . '<div class="col-lg-12">'
                        . '<input type="text" class="form-control member-field-text-input" name="members-field-' . $args['field_slug'] . '-' . time() . '" id="member-field-' . $args['field_slug'] . '"' . $value . ' data-field-id="' . $args['field_slug'] . '" placeholder="' . $args['words_list']['placeholder'] . '" autocomplete="members-field-' . $args['field_slug'] . '-' . time() . '" ' . $required . '/>'
                        . $description
                    . '</div>'
                . '</div>'
            . '</div>';
        
    }
    
}

if ( !function_exists('validate_admin_members_field_template_text_input') ) {
    
    /**
     * The function validate_admin_members_field_template_text_input valiates the text input field
     * 
     * @param array $field_args contains the field's parameters
     * @param array $field_value contains the field's value
     * @param integer $member_id contains the member's ID
     * 
     * @since 0.0.8.3
     * 
     * @return array with response
     */
    function validate_admin_members_field_template_text_input($field_args, $field_value, $member_id) {

        // Create and return array
        return array(
            'success' => TRUE,
            'field_value' => $field_value['field_value']?trim(get_instance()->security->xss_clean($field_value['field_value'])):''
        );

    }
    
}

/**
 * The public method set_admin_members_field_template registers a members field template
 * 
 * @since 0.0.8.3
 */
set_admin_members_field_template('email_input', 'set_admin_members_field_template_email_input', 'validate_admin_members_field_template_email_input');

if ( !function_exists('set_admin_members_field_template_email_input') ) {
    
    /**
     * The function set_admin_members_field_template_email_input registers a field's template for email_input
     * 
     * @param array $args contains the template's parameters
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    function set_admin_members_field_template_email_input($args) {

        // Description container
        $description = '';

        // Verify if description exists
        if ( isset($args['words_list']['description']) ) {

            // Set description
            $description = '<small class="form-text text-muted">'
                . $args['words_list']['description']
            . '</small>';

        }

        // Required container
        $required = '';

        // Verify if input is required
        if ( !empty($args['field_required']) ) {

            // Set required value
            $required = 'required';

        }

        // Default value
        $value = '';

        // Verify if value exists
        if ( the_member_option($args['field_slug'], the_global_variable('members_member_id')) ) {

            // Set value
            $value = ' value="' . the_member_option($args['field_slug'], the_global_variable('members_member_id')) . '"';

        }

        // Display the field
        echo '<div class="form-group">'
                . '<div class="row">'
                    . '<div class="col-lg-12">'
                        . '<label for="member-field-' . $args['field_slug'] . '">'
                            . $args['words_list']['label']
                        . '</label>'
                    . '</div>'
                . '</div>'
                . '<div class="row">'
                    . '<div class="col-lg-12">'
                        . '<input type="email" name="members-field-' . $args['field_slug'] . '-' . time() . '" class="form-control member-field-email-input" id="member-field-' . $args['field_slug'] . '"' . $value . ' data-field-id="' . $args['field_slug'] . '" placeholder="' . $args['words_list']['placeholder'] . '" autocomplete="members-field-' . $args['field_slug'] . '-' . time() . '" ' . $required . '/>'
                        . $description
                    . '</div>'
                . '</div>'
            . '</div>';
        
    }
    
}

if ( !function_exists('validate_admin_members_field_template_email_input') ) {
    
    /**
     * The function validate_admin_members_field_template_email_input valiates the email input field
     * 
     * @param array $field_args contains the field's parameters
     * @param array $field_value contains the field's value
     * @param integer $member_id contains the member's ID
     * 
     * @since 0.0.8.3
     * 
     * @return array with response
     */
    function validate_admin_members_field_template_email_input($field_args, $field_value, $member_id) {

        // Verify if email is valid
        if ( !filter_var($field_value['field_value'], FILTER_VALIDATE_EMAIL) ) {

            // Create and return array
            return array(
                'success' => FALSE,
                'message' => get_instance()->lang->line('members_the_field_email_not_valid')
            );

        } else {

            // Create and return array
            return array(
                'success' => TRUE,
                'field_value' => $field_value['field_value']?trim(get_instance()->security->xss_clean($field_value['field_value'])):''
            );

        }

    }
    
}

/**
 * The public method set_admin_members_field_template registers a members field template
 * 
 * @since 0.0.8.3
 */
set_admin_members_field_template('password_input', 'set_admin_members_field_template_password_input', 'validate_admin_members_field_template_password_input');

if ( !function_exists('set_admin_members_field_template_password_input') ) {
    
    /**
     * The function set_admin_members_field_template_password_input registers a field's template for password_input
     * 
     * @param array $args contains the template's parameters
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    function set_admin_members_field_template_password_input($args) {

        // Description container
        $description = '';

        // Verify if description exists
        if ( isset($args['words_list']['description']) ) {

            // Set description
            $description = '<small class="form-text text-muted">'
                . $args['words_list']['description']
            . '</small>';

        }

        // Required container
        $required = '';

        // Verify if input is required
        if ( !empty($args['field_required']) ) {

            // Set required value
            $required = 'required';

        }

        // Display the field
        echo '<div class="form-group">'
                . '<div class="row">'
                    . '<div class="col-lg-12">'
                        . '<label for="member-field-' . $args['field_slug'] . '">'
                            . $args['words_list']['label']
                        . '</label>'
                    . '</div>'
                . '</div>'
                . '<div class="row">'
                    . '<div class="col-lg-12">'
                        . '<input type="password" name="members-field-' . $args['field_slug'] . '-' . time() . '" class="form-control member-field-password-input" id="member-field-' . $args['field_slug'] . '" value="" data-field-id="' . $args['field_slug'] . '" placeholder="' . $args['words_list']['placeholder'] . '" autocomplete="new-password" ' . $required . '/>'
                        . $description
                    . '</div>'
                . '</div>'
            . '</div>';
        
    }
    
}

if ( !function_exists('validate_admin_members_field_template_password_input') ) {
    
    /**
     * The function validate_admin_members_field_template_password_input validates a field's value for password_input
     * 
     * @param array $field_args contains the field's parameters
     * @param array $field_value contains the field's value
     * @param integer $member_id contains the member's ID
     * 
     * @since 0.0.8.3
     * 
     * @return array with response
     */
    function validate_admin_members_field_template_password_input($field_args, $field_value, $member_id) {

        // Verify if the password is correct
        if ( $member_id && !$field_value['field_value'] ) {

            // Create and return array
            return array(
                'success' => TRUE,
                'field_value' => $field_value['field_value']?trim(get_instance()->security->xss_clean($field_value['field_value'])):''
            );
            
        } else if ( !$field_value ) {

            // Create and return array
            return array(
                'success' => FALSE,
                'message' => str_replace('[field]', $field_args[$field_value['field_id']]['field_name'], get_instance()->lang->line('members_the_field_too_short'))
            );

        } else if ( strlen($field_value['field_value']) < 6 ) {

            // Create and return array
            return array(
                'success' => FALSE,
                'message' => str_replace('[field]', $field_args[$field_value['field_id']]['field_name'], get_instance()->lang->line('members_the_field_too_short'))
            );
            
        } else if ( strlen($field_value['field_value']) > 20 ) {

            // Create and return array
            return array(
                'success' => FALSE,
                'message' => str_replace('[field]', $field_args[$field_value['field_id']]['field_name'], get_instance()->lang->line('members_the_field_too_long'))
            );

        } else if ( preg_match('/\s/', $field_value['field_value']) ) {

            // Create and return array
            return array(
                'success' => FALSE,
                'message' => str_replace('[field]', $field_args[$field_value['field_id']]['field_name'], get_instance()->lang->line('members_the_field_without_white_spaces'))
            );

        } else {

            // Create and return array
            return array(
                'success' => TRUE,
                'field_value' => $field_value['field_value']?trim(get_instance()->security->xss_clean($field_value['field_value'])):''
            );

        }
        
    }
    
}

/**
 * The public method set_admin_members_field_template registers a members field template
 * 
 * @since 0.0.8.3
 */
set_admin_members_field_template('list_select', 'set_admin_members_field_template_list_select', 'validate_admin_members_field_template_list_select');

if ( !function_exists('set_admin_members_field_template_list_select') ) {
    
    /**
     * The function set_admin_members_field_template_list_select registers a field's template for list_select
     * 
     * @param array $args contains the template's parameters
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    function set_admin_members_field_template_list_select($args) {

        // Description container
        $description = '';

        // Verify if description exists
        if ( isset($args['words_list']['description']) ) {

            // Set description
            $description = '<small class="form-text text-muted">'
                . $args['words_list']['description']
            . '</small>';

        }

        // Required container
        $required = '';

        // Verify if input is required
        if ( !empty($args['field_required']) ) {

            // Set required value
            $required = 'required';

        }

        // Items container
        $items = '';

        // Verify if items exists
        if ( !empty($args['items']) ) {

            // List items
            foreach ( $args['items'] as $key => $value ) {

                // Set items
                $items .= '<li class="list-group-item">'
                            . '<a href="#" class="member-field-select-item" data-value="' . $key . '">'
                                . $value
                            . '</a>'
                        . '</li>';

            }

        } else {

            // Set no found message
            $items = '<li class="list-group-item no-results-found">'
                        . get_instance()->lang->line('members_no_data_found')
                    . '</li>';

        }

        // Default selected text
        $selected = $args['words_list']['select'];

        // Default selected id
        $selected_id = 0;
        
        // Verify if an item was selected
        if ( the_member_option($args['field_slug'], the_global_variable('members_member_id')) !== FALSE ) {

            // Set new selection
            $selected = isset($args['items'][the_member_option($args['field_slug'], the_global_variable('members_member_id'))])?$args['items'][the_member_option($args['field_slug'], the_global_variable('members_member_id'))]:$selected;

            // Set selected ID
            $selected_id = the_member_option($args['field_slug'], the_global_variable('members_member_id'));

        }

        // Display the field
        echo '<div class="form-group">'
                . '<div class="row">'
                    . '<div class="col-lg-12">'
                        . '<label for="member-field-' . $args['field_slug'] . '">'
                            . $args['words_list']['label']
                        . '</label>'
                    . '</div>'
                . '</div>'
                . '<div class="row">'
                    . '<div class="col-lg-12">'
                        . '<div class="dropdown">'
                            . '<button class="btn btn-secondary members-dropdown-btn dropdown-toggle member-field-' . $args['field_slug'] . '" type="button" data-toggle="dropdown" data-title="' . $args['words_list']['select'] . '" data-field-id="' . $args['field_slug'] . '" data-value="' . $selected_id . '" aria-expanded="false">'
                                . $selected
                            . '</button>'
                            . '<div class="dropdown-menu" aria-labelledby="dropdown-items">'
                                . '<div class="card">'
                                    . '<div class="card-body">'
                                        . '<ul class="list-group members-dropdown-list-ul">'
                                            . $items
                                        . '</ul>'
                                    . '</div>'
                                . '</div>'
                            . '</div>'
                        . '</div>'
                        . $description
                    . '</div>'
                . '</div>'
            . '</div>';
        
    }
    
}

if ( !function_exists('validate_admin_members_field_template_list_select') ) {
    
    /**
     * The function validate_admin_members_field_template_list_select validates a field's value for list_select
     * 
     * @param array $field_args contains the field's parameters
     * @param array $field_value contains the field's value
     * @param integer $member_id contains the member's ID
     * 
     * @since 0.0.8.3
     * 
     * @return array with response
     */
    function validate_admin_members_field_template_list_select($field_args, $field_value, $member_id) {

        // Create and return array
        return array(
            'success' => TRUE,
            'field_value' => $field_value['field_value']?trim(get_instance()->security->xss_clean($field_value['field_value'])):''
        );
        
    }
    
}

/**
 * The public method set_admin_members_field_template registers a members field template
 * 
 * @since 0.0.8.3
 */
set_admin_members_field_template('dynamic_list_select', 'set_admin_members_field_template_dynamic_list_select', 'validate_admin_members_field_template_dynamic_list_select');

if ( !function_exists('set_admin_members_field_template_dynamic_list_select') ) {
    
    /**
     * The function set_admin_members_field_template_dynamic_list_select registers a field's template for dynamic_list_select
     * 
     * @param array $args contains the template's parameters
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    function set_admin_members_field_template_dynamic_list_select($args) {

        // Description container
        $description = '';

        // Verify if description exists
        if ( isset($args['words_list']['description']) ) {

            // Set description
            $description = '<small class="form-text text-muted">'
                . $args['words_list']['description']
            . '</small>';

        }

        // Required container
        $required = '';

        // Verify if input is required
        if ( !empty($args['field_required']) ) {

            // Set required value
            $required = 'required';

        }

        // Items container
        $items = '';

        // Verify if items exists
        if ( !empty($args['items']) ) {

            // List items
            foreach ( $args['items'] as $key => $value ) {

                // Set items
                $items .= '<li class="list-group-item">'
                            . '<a href="#" class="member-field-select-item" data-value="' . $key . '">'
                                . $value
                            . '</a>'
                        . '</li>';

            }

        } else {

            // Set no found message
            $items = '<li class="list-group-item no-results-found">'
                        . get_instance()->lang->line('members_no_data_found')
                    . '</li>';

        }

        // Display the field
        echo '<div class="form-group">'
                . '<div class="row">'
                    . '<div class="col-lg-12">'
                        . '<label for="member-field-' . $args['field_slug'] . '">'
                            . $args['words_list']['label']
                        . '</label>'
                    . '</div>'
                . '</div>'
                . '<div class="row">'
                    . '<div class="col-lg-12">'
                        . '<div class="dropdown">'
                            . '<button class="btn btn-secondary members-dynamic-dropdown-btn dropdown-toggle member-field-' . $args['field_slug'] . '" type="button" data-toggle="dropdown" data-title="' . $args['words_list']['select'] . '" data-field-id="' . $args['field_slug'] . '" data-field-url="' . $args['field_endpoint'] . '" data-value="0" aria-expanded="false">'
                                . $args['words_list']['select']
                            . '</button>'
                            . '<div class="dropdown-menu" aria-labelledby="dropdown-items">'
                                . '<div class="card">'
                                    . '<div class="card-head">'
                                        . '<input type="text" class="members-search-dropdown-items" placeholder="' . $args['words_list']['placeholder'] . '" />'
                                    . '</div>'
                                    . '<div class="card-body">'
                                        . '<ul class="list-group members-dropdown-dynamic-list-ul">'
                                        . '</ul>'
                                    . '</div>'
                                . '</div>'
                            . '</div>'
                        . '</div>'
                        . $description
                    . '</div>'
                . '</div>'
            . '</div>';
        
    }
    
}

if ( !function_exists('validate_admin_members_field_template_dynamic_list_select') ) {
    
    /**
     * The function validate_admin_members_field_template_dynamic_list_select validates a field's value for dynamic_list_select
     * 
     * @param array $field_args contains the field's parameters
     * @param array $field_value contains the field's value
     * @param integer $member_id contains the member's ID
     * 
     * @since 0.0.8.3
     * 
     * @return array with response
     */
    function validate_admin_members_field_template_dynamic_list_select($field_args, $field_value, $member_id) {

        // Create and return array
        return array(
            'success' => TRUE,
            'field_value' => $field_value['field_value']?trim(get_instance()->security->xss_clean($field_value['field_value'])):''
        );
        
    }
    
}

/**
 * The public method set_admin_members_field_template registers a members field template
 * 
 * @since 0.0.8.3
 */
set_admin_members_field_template('checkbox_input', 'set_admin_members_field_template_checkbox_input', 'validate_admin_members_field_template_checkbox_input');

if ( !function_exists('set_admin_members_field_template_checkbox_input') ) {
    
    /**
     * The function set_admin_members_field_template_checkbox_input registers a field's template for checkbox_input
     * 
     * @param array $args contains the template's parameters
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    function set_admin_members_field_template_checkbox_input($args) {

        // Description container
        $description = '';

        // Verify if description exists
        if ( isset($args['words_list']['description']) ) {

            // Set description
            $description = '<small class="form-text text-muted">'
                . $args['words_list']['description']
            . '</small>';

        }

        // Default checked
        $checked = '';

        // Verify if the checkbox is checked
        if ( the_member_option($args['field_slug'], the_global_variable('members_member_id')) ) {

            // Set checked
            $checked = ' checked';

        }

        // Display the field
        echo '<div class="form-group">'
                . '<div class="row">'
                    . '<div class="col-lg-6 col-md-6 col-xs-12">'
                        . '<h4>'
                            . $args['words_list']['label']
                        . '</h4>'
                        . '<p>'
                            . $description
                        . '</p>'
                    . '</div>'
                    . '<div class="col-lg-6 col-md-6 col-xs-12">'
                        . '<div class="checkbox-option pull-right">'
                            . '<input id="member-field-' . $args['field_slug'] . '" name="' . $args['field_slug'] . '" class="member-field-checkbox-input" type="checkbox" data-field-id="' . $args['field_slug'] . '" ' . $checked . '/>'
                            . '<label for="member-field-' . $args['field_slug'] . '"></label>'
                        . '</div>'
                    . '</div>'
                . '</div>'
            . '</div>';
        
    }
    
}

if ( !function_exists('validate_admin_members_field_template_checkbox_input') ) {
    
    /**
     * The function validate_admin_members_field_template_checkbox_input validates a field's value for checkbox_input
     * 
     * @param array $field_args contains the field's parameters
     * @param array $field_value contains the field's value
     * @param integer $member_id contains the member's ID
     * 
     * @since 0.0.8.3
     * 
     * @return array with response
     */
    function validate_admin_members_field_template_checkbox_input($field_args, $field_value, $member_id) {

        // Create and return array
        return array(
            'success' => TRUE,
            'field_value' => $field_value['field_value']?trim(get_instance()->security->xss_clean($field_value['field_value'])):''
        );
        
    }
    
}