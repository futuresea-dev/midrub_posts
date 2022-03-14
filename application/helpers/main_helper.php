<?php
if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

/**
 * Name: Main Helper
 * Author: Scrisoft
 * Created: 22/04/2016
 * Here you will find the following functions:
 * calculate_time - calculates time by using current time and publish time
 * update_option - saves option
 * get_option - gets option meta value
 * delete_option - deletes an option
 * smtp - configures smtp access
 * plan_feature - gets plan's feature
 * plan_explore - gets plan's start time
 * get_user_option - gets the current user option by option name
 * the_user_option - gets the current user option by option name
 * update_user_option - updates the current user option by option name
 * sends_invoice - sends the user invoice
 * calculate_size - calculates the size from bytes
 * */

if ( !function_exists( 'calculate_time' ) ) {

    /**
     * The function will calculate time between two dates
     * 
     * @param integer $from contains the time from
     * @param integer $to contains the time to
     * 
     * @return boolean true or false
     */
    function calculate_time($from, $to) {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Calculate time difference
        $calculate = $to - $from;
        
        // Get after icon
        $after = ' ' . $CI->lang->line('mm104');
        
        // Define $before variable
        $before = '';
        
        // Verify if the difference time is less than 0
        if ( $calculate < 0 ) {
            
            // Get absolute value
            $calculate = abs($calculate);
            
            // Get icon
            $after = '<i class="far fa-calendar-check pull-left"></i> ';
            
            $before = '';
            
        }
        
        // Verify if the difference time is less than 1 minute
        if ( $calculate < 60 ) {
            
            return $CI->lang->line('mm105');
            
        } else if ( $calculate < 3600 ) {
            
            // Display one minute text
            $calc = $calculate / 60;
            return $before . round($calc) . ' ' . $CI->lang->line('mm106') . $after;
            
        } else if ($calculate > 3600 AND $calculate < 86400) {
            
            // Display one hour text
            $calc = $calculate / 3600;
            return $before . round($calc) . ' ' . $CI->lang->line('mm107') . $after;
            
        } else if ($calculate >= 86400) {
            
            // Display one day text
            $calc = $calculate / 86400;
            return $before . round($calc) . ' ' . $CI->lang->line('mm103') . $after;
            
        }
        
    }

}

if ( !function_exists('get') ) {

    /**
     * The function gets content via http request
     * 
     * @param string $val contains the url
     * 
     * @return string with parsed content
     */
    function get( $val ) {
        
        // Initialize a cURL session
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_URL => $val,
            CURLOPT_HEADER => 'User-Agent: Chrome\r\n',
            CURLOPT_TIMEOUT => '3L'));
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
        
    }

}

if ( !function_exists('post') ) {

    /**
     * The function post sends content via http request
     * 
     * @param string $val contains the url
     * @param array $param contains the params to send
     * @param string $token contains the token
     * 
     * @return data with returned content
     */
    function post($val, $param, $token = NULL) {
        
        // Initialize a cURL session
        $curl = curl_init($val);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        
        if ($token) {
            $authorization = "Authorization: Bearer " . $token; // **Prepare Autorisation Token**
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        }
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($param));
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
        
    }

}

if ( !function_exists('delete') ) {

    /**
     * The function delete deletes content via http request
     * 
     * @param string $val contains the url
     * @param string $token contains the token
     * 
     * @return data with returned content
     */
    function delete($val, $token = NULL) {
        
        // Initialize a cURL session
        $curl = curl_init($val);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        
        if ($token) {
            $authorization = "Authorization: Bearer " . $token; // **Prepare Autorisation Token**
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        }
        
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
        
    }

}

if ( !function_exists('update_option') ) {

    /**
     * The function update_option updates/creates an option
     * 
     * @param string $key contains the option's key
     * @param string $value contains the new option's value
     * 
     * @return boolean true or false
     */
    function update_option( $key, $value ) {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Load Options Model
        $CI->load->model('options');
        
        return $CI->options->add_option_value( $key, $value );
        
    }

}

if ( !function_exists('get_option') ) {

    /**
     * The function gets option by option's name
     * 
     * @param string $name contains the option's name
     * 
     * @return string with option's value
     */
    function get_option( $name ) {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Verify if $all_options property is not empty
        if ( !empty($CI->all_options) ) {
            
            if ( isset($CI->all_options[$name]) ) {
                return $CI->all_options[$name];
            } else {
                return false;
            }
            
        } else {
        
            // Load Options Model
            $CI->load->model('options');
            
            $CI->all_options = $CI->options->get_all_options();

            if ( isset($CI->all_options[$name]) ) {
                return $CI->all_options[$name];
            } else {
                return false;
            }
        
        }
        
    }

}

if ( !function_exists('the_option') ) {

    /**
     * The function the_option returns option by option's name
     * 
     * @param string $name contains the option's name
     * 
     * @return string with option's value
     */
    function the_option( $name ) {
        
        // Get the option
        return get_option($name);
        
    }

}

if ( !function_exists('delete_option') ) {

    /**
     * The function delete_option deletes an option
     * 
     * @param string $name contains the option's name
     * 
     * @return string with option's value
     */
    function delete_option( $name ) {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Load Options Model
        $CI->load->model('options');
        
        // Return option's value
        return $CI->options->delete_option( $name );
        
    }

}

if ( !function_exists('smtp') ) {

    /**
     * The function provides the smtp configuration
     * 
     * @return array with smtp's configuration
     */
    function smtp() {
        
        // Verify if the smtp option is enabled
        if ( get_option('smtp-enable') ) {
            
            // Set the default protocol
            $protocol = 'sendmail';
            
            // Verify if user have added a protocol
            if ( get_option('smtp-protocol') ) {
                
                $protocol = get_option('smtp-protocol');
                
            }
            
            // Create the configuration array
            $d = array(
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'smtpauth' => true,
                'priority' => '1',
                'newline' => "\r\n",
                'protocol' => $protocol,
                'smtp_host' => get_option('smtp-host'),
                'smtp_port' => get_option('smtp-port'),
                'smtp_user' => get_option('smtp-username'),
                'smtp_pass' => get_option('smtp-password')
            );
            
            // Verify if ssl is enabled
            if (get_option('smtp-ssl')) {
                
                $d['smtp_crypto'] = 'ssl';
                
            } elseif (get_option('smtp-tls')) {
                
                // Set TSL if is enabled
                $d['smtp_crypto'] = 'tls';
                
            }
            
            return $d;
            
        } else {
            
            return ['mailtype' => 'html', 'charset' => 'utf-8', 'newline' => "\r\n", 'priority' => '1'];
            
        }
        
    }

}

if ( !function_exists('plan_explore') ) {

    /**
     * The function gets user plan start time
     * 
     * @return string with time
     */
    function plan_explore($user_id) {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Return time when started the plan
        return $CI->plans->plan_started($user_id);
        
    }

}

if (!function_exists('get_user_option')) {
    
    /**
     * The function gets the user options(will be deprecated soon)
     * 
     * @param string $option contains the option's name
     * @param integer $user_id contains the user's id
     * 
     * @return object or string with meta's value
     */
    function get_user_option($option, $user_id = NULL) {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Verify if user_id exists
        if ( !$user_id ) {
            
            // Get user_id
            $user_id = $CI->user_id;
            
        }

        // Verify if $all_user_options property is not empty
        if ( !empty($CI->all_user_options) ) {

            if ( isset($CI->all_user_options[$option]) ) {
                
                return $CI->all_user_options[$option];
                
            } else {
                
                return false;
                
            }
            
        } else {
        
            // Load User Meta Model
            $CI->load->model('user_meta');
            
            // Get User's options
            $CI->all_user_options = $CI->user_meta->get_all_user_options($user_id);

            // Verify if user has the option
            if ( isset($CI->all_user_options[$option]) ) {

                return $CI->all_user_options[$option];

            } else {
                
                return false;
                
            }
        
        }
        
    }

}

if (!function_exists('the_user_option')) {
    
    /**
     * The function the_user_option gets the user's options
     * 
     * @param string $option contains the option's name
     * @param integer $user_id contains the user's id
     * 
     * @return object or string with meta's value
     */
    function the_user_option($option, $user_id = NULL) {

        return get_user_option($option, $user_id);

    }

}

if ( !function_exists( 'update_user_option' ) ) {
    
    /**
     * The function update_user_option updates the user's option
     * 
     * @param integer $user_id contains the user_id
     * @param string $meta_name contains the user's meta name
     * @param string $meta_value contains the user's meta value
     * 
     * @return boolean true or false
     */
    function update_user_option($user_id, $meta_name, $meta_value) {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Load User Meta Model
        $CI->load->model('user_meta');
        
        // Save meta value
        return $CI->user_meta->update_user_meta($user_id, $meta_name, $meta_value);
        
    }

}

if ( !function_exists( 'delete_user_option' ) ) {
    
    /**
     * The function delete_user_option deletes a user's option
     * 
     * @param integer $user_id contains the user_id
     * @param string $meta_name contains the user's meta name
     * 
     * @return boolean true or false
     */
    function delete_user_option($user_id, $meta_name) {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Delete meta
        return $CI->user->delete_user_option($user_id, $meta_name);
        
    }

}

if (!function_exists('plan_feature')) {
    
    /**
     * The function gets plan's feature
     * 
     * @param string $features contains the feature's name
     * @param integer $plan_id contains optionally the plan's id
     * 
     * @return string with feature's value or boolean false
     */
    function plan_feature($feature, $plan_id = 0) {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Verify if $plan_features property is not empty
        if ( !empty($CI->plan_features) ) {
            
            if ( isset($CI->plan_features[$feature]) ) {
                
                return $CI->plan_features[$feature];
                
            } else {
                
                return false;
                
            }
            
        } else {
            
            if ( !$plan_id ) {
                $plan_id = get_user_option('plan');
            }
            
            $plan_info = $CI->plans->get_plan($plan_id);

            if ( isset($plan_info[0]) ) {

                $CI->plan_features = $plan_info[0];

            }

            // Verify if plan's feature exists
            if ( isset($CI->plan_features[$feature]) ) {

                return $CI->plan_features[$feature];

            } else {
                
                return false;
                
            }
        
        }
        
    }

}

if ( !function_exists( 'calculate_size' ) ) {
    
    /**
     * The function calculate_size calculates the size
     * 
     * @param integer $size contains size in bytes
     * 
     * @return string with size
     */
    function calculate_size($size) {
        if (!$size) {
            return '0';
        }
        $base = log($size, 1024);
        $suffixes = array('', 'K', 'M', 'G', 'T');
        if ( isset($suffixes[floor($base)]) ) {
            return round(pow(1024, $base - floor($base)), 2) . ' ' . $suffixes[floor($base)];
        } else {
            return '0';
        }
        
    }

}

if ( !function_exists( 'get_post_media_array' ) ) {
    
    /**
     * The function update_user_meta updates the user's meta
     * 
     * @param integer $user_id contains the user_id
     * @param array $medias contains the medias's ids 
     * 
     * @return array with medias
     */
    function get_post_media_array($user_id, $medias) {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Load Media Model
        $CI->load->model('media');
        
        // Verify if $medias is not empty
        if ( $medias ) {
            
            // Get urls if exists
            return $CI->media->get_medias_by_type($user_id, $medias);
            
        } else {
            
            return $medias;
            
        }
        
    }

}

if (!function_exists('parse_array')) {
    
    /**
     * The function applies a user function recursively to every member of an array
     * 
     * @return string with custom code
     */
    function parse_array($array) {
        
        $parsed = array();
        
        array_walk_recursive($array, function($a) use (&$parsed) {
            $parsed[] = $a;
        });
        
        return $parsed;
        
    }

}



