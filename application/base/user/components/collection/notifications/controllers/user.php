<?php
/**
 * User Controller
 *
 * This file loads the Notifications component in the user panel
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

// Define the page namespace
namespace MidrubBase\User\Components\Collection\Notifications\Controllers;

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * User class loads the Notifications's content
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */
class User {
    
    /**
     * Class variables
     *
     * @since 0.0.7.9
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.9
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load language
        $this->CI->lang->load( 'notifications_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS );
        
    }
    
    /**
     * The public method view loads the component's template
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function view() {

        // Set the Notifications styles
        set_css_urls(array('stylesheet', base_url('assets/base/user/components/collection/notifications/styles/css/styles.css?ver=' . MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS_VERSION), 'text/css', 'all'));
        
        // Set the user's view
        $this->set_user_view();
        
    }

    /**
     * The public method set_user_view prepare and add in the queue the user view
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function set_user_view() {

        // Set the page's title
        set_the_title($this->CI->lang->line('notifications'));

        // Verify if single page exists
        if ( $this->CI->input->get('p', TRUE) ) {

            switch ( $this->CI->input->get('p', TRUE) ) {

                case 'news':

                    // Verify if alert page exists
                    if ( is_numeric($this->CI->input->get('alert', TRUE)) ) {

                        // Load Notifications News Model
                        $this->CI->load->ext_model( MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'models/', 'Notifications_news_model', 'notifications_news_model' );

                        // Set where parameters
                        $where = array(
                            'user_id' => $this->CI->user_id,
                            'alert_id' => $this->CI->input->get('alert', TRUE),
                            'plan' => the_user_option('plan'),
                            'language' => $this->CI->config->item('language')
                        );

                        // Get news alert
                        $notification = $this->CI->notifications_news_model->the_news_alert($where);

                        // Verify if the news alert exists
                        if ( $notification ) {

                            // Verify if the alert was seen
                            if ( !$notification[0]['page_seen'] ) {

                                // Mark the alert as seen
                                $this->mark_alert_seen($this->CI->input->get('alert', TRUE), 0);

                            }

                            // Prepare view params
                            $params = array(
                                'notification' => $notification,
                                'page' => 'news',
                                'page_icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-megaphone" viewBox="0 0 16 16">'
                                    . '<path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-.214c-2.162-1.241-4.49-1.843-6.912-2.083l.405 2.712A1 1 0 0 1 5.51 15.1h-.548a1 1 0 0 1-.916-.599l-1.85-3.49a68.14 68.14 0 0 0-.202-.003A2.014 2.014 0 0 1 0 9V7a2.02 2.02 0 0 1 1.992-2.013 74.663 74.663 0 0 0 2.483-.075c3.043-.154 6.148-.849 8.525-2.199V2.5zm1 0v11a.5.5 0 0 0 1 0v-11a.5.5 0 0 0-1 0zm-1 1.35c-2.344 1.205-5.209 1.842-8 2.033v4.233c.18.01.359.022.537.036 2.568.189 5.093.744 7.463 1.993V3.85zm-9 6.215v-4.13a95.09 95.09 0 0 1-1.992.052A1.02 1.02 0 0 0 1 7v2c0 .55.448 1.002 1.006 1.009A60.49 60.49 0 0 1 4 10.065zm-.657.975l1.609 3.037.01.024h.548l-.002-.014-.443-2.966a68.019 68.019 0 0 0-1.722-.082z"/>'
                                . '</svg> '
                            );

                            // Set views params
                            set_user_view(

                                $this->CI->load->ext_view(
                                    MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'views',
                                    'notification',
                                    $params,
                                    true
                                )

                            );                            

                        } else {

                            // Display the 404 page
                            show_404();

                        }

                    } else {

                        // Set the Main Notifications Js
                        set_js_urls(array(base_url('assets/base/user/components/collection/notifications/js/main.js?ver=' . MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS_VERSION)));

                        // Prepare view params
                        $params = array(
                            'page' => 'news',
                            'page_header' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-megaphone" viewBox="0 0 16 16">'
                                . '<path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-.214c-2.162-1.241-4.49-1.843-6.912-2.083l.405 2.712A1 1 0 0 1 5.51 15.1h-.548a1 1 0 0 1-.916-.599l-1.85-3.49a68.14 68.14 0 0 0-.202-.003A2.014 2.014 0 0 1 0 9V7a2.02 2.02 0 0 1 1.992-2.013 74.663 74.663 0 0 0 2.483-.075c3.043-.154 6.148-.849 8.525-2.199V2.5zm1 0v11a.5.5 0 0 0 1 0v-11a.5.5 0 0 0-1 0zm-1 1.35c-2.344 1.205-5.209 1.842-8 2.033v4.233c.18.01.359.022.537.036 2.568.189 5.093.744 7.463 1.993V3.85zm-9 6.215v-4.13a95.09 95.09 0 0 1-1.992.052A1.02 1.02 0 0 0 1 7v2c0 .55.448 1.002 1.006 1.009A60.49 60.49 0 0 1 4 10.065zm-.657.975l1.609 3.037.01.024h.548l-.002-.014-.443-2.966a68.019 68.019 0 0 0-1.722-.082z"/>'
                            . '</svg> ' . $this->CI->lang->line('notifications_news')
                        );

                        // Set views params
                        set_user_view(

                            $this->CI->load->ext_view(
                                MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'views',
                                'main',
                                $params,
                                true
                            )

                        );

                    }

                    break;

                case 'offers':

                    // Verify if alert page exists
                    if ( is_numeric($this->CI->input->get('alert', TRUE)) ) {

                        // Load Notifications Offers Model
                        $this->CI->load->ext_model( MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'models/', 'Notifications_offers_model', 'notifications_offers_model' );

                        // Set where parameters
                        $where = array(
                            'user_id' => $this->CI->user_id,
                            'alert_id' => $this->CI->input->get('alert', TRUE),
                            'plan' => the_user_option('plan'),
                            'language' => $this->CI->config->item('language')
                        );

                        // Get offers alert
                        $notification = $this->CI->notifications_offers_model->the_offers_alert($where);

                        // Verify if the offers alert exists
                        if ( $notification ) {

                            // Verify if the alert was seen
                            if ( !$notification[0]['page_seen'] ) {

                                // Mark the alert as seen
                                $this->mark_alert_seen($this->CI->input->get('alert', TRUE), 1);

                            }

                            // Prepare view params
                            $params = array(
                                'notification' => $notification,
                                'page' => 'offers',
                                'page_icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gift" viewBox="0 0 16 16">'
                                    . '<path d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zM1 4v2h6V4H1zm8 0v2h6V4H9zm5 3H9v8h4.5a.5.5 0 0 0 .5-.5V7zm-7 8V7H2v7.5a.5.5 0 0 0 .5.5H7z"/>'
                                . '</svg> '
                            );

                            // Set views params
                            set_user_view(

                                $this->CI->load->ext_view(
                                    MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'views',
                                    'notification',
                                    $params,
                                    true
                                )

                            );                            

                        } else {

                            // Display the 404 page
                            show_404();

                        }

                    } else {

                        // Set the Offers Notifications Js
                        set_js_urls(array(base_url('assets/base/user/components/collection/notifications/js/offers.js?ver=' . MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS_VERSION)));

                        // Prepare view params
                        $params = array(
                            'notifications' => array(),
                            'total' => 0,
                            'page' => 'offers',
                            'page_header' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gift" viewBox="0 0 16 16">'
                                . '<path d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zM1 4v2h6V4H1zm8 0v2h6V4H9zm5 3H9v8h4.5a.5.5 0 0 0 .5-.5V7zm-7 8V7H2v7.5a.5.5 0 0 0 .5.5H7z"/>'
                            . '</svg> ' . $this->CI->lang->line('notifications_offers')
                        );

                        // Set views params
                        set_user_view(

                            $this->CI->load->ext_view(
                                MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'views',
                                'main',
                                $params,
                                true
                            )

                        );

                    }

                    break; 
                    
                case 'miscellaneous':

                    // Verify if alert page exists
                    if ( is_numeric($this->CI->input->get('alert', TRUE)) ) {

                        // Load Notifications Miscellaneous Model
                        $this->CI->load->ext_model( MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'models/', 'Notifications_miscellaneous_model', 'notifications_miscellaneous_model' );

                        // Set where parameters
                        $where = array(
                            'user_id' => $this->CI->user_id,
                            'alert_id' => $this->CI->input->get('alert', TRUE),
                            'plan' => the_user_option('plan'),
                            'language' => $this->CI->config->item('language')
                        );

                        // Get miscellaneous alert
                        $notification = $this->CI->notifications_miscellaneous_model->the_miscellaneous_alert($where);

                        // Verify if the miscellaneous alert exists
                        if ( $notification ) {

                            // Verify if the alert was seen
                            if ( !$notification[0]['page_seen'] ) {

                                // Mark the alert as seen
                                $this->mark_alert_seen($this->CI->input->get('alert', TRUE), 2);

                            }

                            // Prepare view params
                            $params = array(
                                'notification' => $notification,
                                'page' => 'miscellaneous',
                                'page_icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">'
                                    . '<path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>'
                                . '</svg> '
                            );

                            // Set views params
                            set_user_view(

                                $this->CI->load->ext_view(
                                    MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'views',
                                    'notification',
                                    $params,
                                    true
                                )

                            );                            

                        } else {

                            // Display the 404 page
                            show_404();

                        }

                    } else {

                        // Verify if alert page exists
                        if ( is_numeric($this->CI->input->get('alert', TRUE)) ) {

                            // Load Notifications Errors Model
                            $this->CI->load->ext_model( MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'models/', 'Notifications_errors_model', 'notifications_errors_model' );

                            // Set where parameters
                            $where = array(
                                'user_id' => $this->CI->user_id,
                                'alert_id' => $this->CI->input->get('alert', TRUE),
                                'plan' => the_user_option('plan'),
                                'language' => $this->CI->config->item('language')
                            );

                            // Get errors alert
                            $notification = $this->CI->notifications_errors_model->the_errors_alert($where);

                            // Verify if the errors alert exists
                            if ( $notification ) {

                                // Prepare view params
                                $params = array(
                                    'notification' => $notification,
                                    'page' => 'errors',
                                    'page_icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">'
                                        . '<path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>'
                                    . '</svg> '
                                );

                                // Set views params
                                set_user_view(

                                    $this->CI->load->ext_view(
                                        MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'views',
                                        'notification',
                                        $params,
                                        true
                                    )

                                );                            

                            } else {

                                // Display the 404 page
                                show_404();

                            }

                        } else {

                            // Set the miscellaneous Notifications Js
                            set_js_urls(array(base_url('assets/base/user/components/collection/notifications/js/miscellaneous.js?ver=' . MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS_VERSION)));

                            // Prepare view params
                            $params = array(
                                'notifications' => array(),
                                'total' => 0,
                                'page' => 'miscellaneous',
                                'page_header' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">'
                                    . '<path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>'
                                . '</svg> ' . $this->CI->lang->line('notifications_miscellaneous')
                            );

                            // Set views params
                            set_user_view(

                                $this->CI->load->ext_view(
                                    MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'views',
                                    'main',
                                    $params,
                                    true
                                )

                            );

                        }

                    }

                    break; 

                case 'errors':

                    // Verify if alert page exists
                    if ( is_numeric($this->CI->input->get('alert', TRUE)) ) {

                        // Load Notifications Errors Model
                        $this->CI->load->ext_model( MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'models/', 'Notifications_errors_model', 'notifications_errors_model' );

                        // Set where parameters
                        $where = array(
                            'user_id' => $this->CI->user_id,
                            'alert_id' => $this->CI->input->get('alert', TRUE),
                            'plan' => the_user_option('plan'),
                            'language' => $this->CI->config->item('language')
                        );

                        // Get errors alert
                        $notification = $this->CI->notifications_errors_model->the_errors_alert($where);

                        // Verify if the errors alert exists
                        if ( $notification ) {

                            // Verify if the alert was seen
                            if ( !$notification[0]['page_seen'] ) {

                                // Mark the alert as seen
                                $this->mark_alert_seen($this->CI->input->get('alert', TRUE), 3);

                            }

                            // Prepare view params
                            $params = array(
                                'notification' => $notification,
                                'page' => 'errors',
                                'page_icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">'
                                    . '<path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>'
                                . '</svg> '
                            );

                            // Set views params
                            set_user_view(

                                $this->CI->load->ext_view(
                                    MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'views',
                                    'notification',
                                    $params,
                                    true
                                )

                            );                            

                        } else {

                            // Display the 404 page
                            show_404();

                        }

                    } else {

                        // Set the Errors Notifications Js
                        set_js_urls(array(base_url('assets/base/user/components/collection/notifications/js/errors.js?ver=' . MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS_VERSION)));

                        // Prepare view params
                        $params = array(
                            'notifications' => array(),
                            'total' => 0,
                            'page' => 'errors',
                            'page_header' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">'
                                . '<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>'
                                . '<path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>'
                            . '</svg> ' . $this->CI->lang->line('notifications_errors')
                        );

                        // Set views params
                        set_user_view(

                            $this->CI->load->ext_view(
                                MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'views',
                                'main',
                                $params,
                                true
                            )

                        );

                    }

                    break;

                default:

                    show_404();

                    break;

            }

        } else {

            // Set the Main Notifications Js
            set_js_urls(array(base_url('assets/base/user/components/collection/notifications/js/main.js?ver=' . MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS_VERSION)));

            // Prepare view params
            $params = array(
                'page' => 'news',
                'page_header' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-megaphone" viewBox="0 0 16 16">'
                    . '<path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-.214c-2.162-1.241-4.49-1.843-6.912-2.083l.405 2.712A1 1 0 0 1 5.51 15.1h-.548a1 1 0 0 1-.916-.599l-1.85-3.49a68.14 68.14 0 0 0-.202-.003A2.014 2.014 0 0 1 0 9V7a2.02 2.02 0 0 1 1.992-2.013 74.663 74.663 0 0 0 2.483-.075c3.043-.154 6.148-.849 8.525-2.199V2.5zm1 0v11a.5.5 0 0 0 1 0v-11a.5.5 0 0 0-1 0zm-1 1.35c-2.344 1.205-5.209 1.842-8 2.033v4.233c.18.01.359.022.537.036 2.568.189 5.093.744 7.463 1.993V3.85zm-9 6.215v-4.13a95.09 95.09 0 0 1-1.992.052A1.02 1.02 0 0 0 1 7v2c0 .55.448 1.002 1.006 1.009A60.49 60.49 0 0 1 4 10.065zm-.657.975l1.609 3.037.01.024h.548l-.002-.014-.443-2.966a68.019 68.019 0 0 0-1.722-.082z"/>'
                . '</svg> ' . $this->CI->lang->line('notifications_news')
            );

            // Set views params
            set_user_view(

                $this->CI->load->ext_view(
                    MIDRUB_BASE_USER_COMPONENTS_NOTIFICATIONS . 'views',
                    'main',
                    $params,
                    true
                )

            );
        
        }
        
    } 

    /**
     * The protected method mark_alert_seen marks an alert as seen
     * 
     * @param integer $alert_id contains the alert's ID
     * @param integer $alert_type contains the alert's type
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    protected function mark_alert_seen($alert_id, $alert_type) {

           // Get the alert
           $alert_user = $this->CI->base_model->get_data_where(
            'notifications_alerts_users',
            'alert_id',
            array(
                'alert_id' => $alert_id,
                'user_id' => $this->CI->user_id
            )
        );

        // Verify if the alert exists
        if ( $alert_user ) {

            // Save the user's alert as seen by using the Base's Model
            $this->CI->base_model->update('notifications_alerts_users', array('alert_id' => $alert_id), array('page_seen' => 1));

        } else {

            // Create user's activity for alert
            $user_activity = array(
                'alert_id' => $alert_id,
                'user_id' => $this->CI->user_id,
                'page_seen' => 1,
                'deleted' => 0,
                'updated' => time(),
                'created' => time()
            );

            // Verify if $alert_type is not a promo
            if ( $alert_type !== 1 ) {

                // Set banner seen
                $user_activity['banner_seen'] = 1;

            }

            // Save the user's alert as seen by using the Base's Model
            $this->CI->base_model->insert('notifications_alerts_users', $user_activity);

        }

    }
    
}
