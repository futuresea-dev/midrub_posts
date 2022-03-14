<?php
/**
 * General Inc
 *
 * This file contains the general functions
 * used in the theme
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| DEFAULTS FUNCTIONS WHICH RETURNS DATA
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| DEFAULTS FUNCTIONS WHICH DISPLAYS DATA
|--------------------------------------------------------------------------
*/

if ( !function_exists('get_the_notifications') ) {
    
    /**
     * The function get_the_notifications loads the notifications
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_the_notifications() {

        // Run the hook
        run_hook('the_notifications', array());
        
    }
    
}

if ( !function_exists('get_the_tickets') ) {
    
    /**
     * The function get_the_tickets loads the tickets
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_the_tickets() {

        // Run the hook
        run_hook('the_tickets', array());
        
    }
    
}

if ( !function_exists('get_the_user_profile') ) {
    
    /**
     * The function get_the_user_profile loads the user's profile
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_the_user_profile() {

        // Run the hook
        run_hook('the_user_profile', array());
        
    }
    
}

if ( !function_exists('get_the_site_logo') ) {
    
    /**
     * The function get_the_site_logo displays the site's logo
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_the_site_logo() {

        // Verify if logo exists
        if ( the_option('main_logo') ) {

            echo '<a class="home-page-link" href="' . site_url() . '">'
                . '<img src="' . the_option('main_logo') . '">'
            . '</a>';

        }
        
    }
    
}

if ( !function_exists('get_the_site_favicon') ) {
    
    /**
     * The function get_the_site_favicon displays the site's favicon
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function get_the_site_favicon() {

        // Verify if favicon exists
        if ( the_option('favicon') ) {

            echo '<link rel="shortcut icon" href="' . the_option('favicon') . '" />';

        }
        
    }
    
}

/*
|--------------------------------------------------------------------------
| DEFAULT FUNCTIONS TO SAVE DATA
|--------------------------------------------------------------------------
*/

/**
 * The public method add_hook registers a hook
 * 
 * @since 0.0.7.8
 */
add_hook(
    'the_footer',
    function () {

        echo "<script src=\"" . base_url('assets/base/user/themes/blue/js/main.js') . "\"></script>\n";

    }

);

/**
 * The public method add_hook registers a hook
 * 
 * @since 0.0.7.9
 */
add_hook(
    'the_notifications',
    function () {

        // Verify if the component is enabled
        if ( the_option('component_notifications_enable') ) {

            // Get codeigniter object instance
            $CI = get_instance();
            
            // Load Notifications Model
            $this->CI->load->ext_model( MIDRUB_BASE_USER . 'themes/blue/models/', 'Blue_notifications_model', 'blue_notifications_model' );

            // Set where parameters
            $where = array(
                'user_id' => $CI->user_id,
                'plan' => the_user_option('plan'),
                'language' => $CI->config->item('language')
            );

            // Get notifications
            $notifications = $CI->blue_notifications_model->the_notifications_alerts($where);

            // Notifications container
            $all_notifications = '';
            
            // Counter
            $count = 0;
            
            // Verify if notifications exists
            if ( $notifications ) {
                
                // List all notifications
                foreach ($notifications['notifications'] as $notification) {

                    // Define variable new
                    $new = '';
                    
                    // Verify if user has read the notification
                    if ( empty($notification['page_seen']) ) {
                        
                        // Set new
                        $new = 'new';

                        // Increase counter
                        $count++;
                        
                    }

                    // Default alert icon
                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-megaphone" viewBox="0 0 16 16">'
                        . '<path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-.214c-2.162-1.241-4.49-1.843-6.912-2.083l.405 2.712A1 1 0 0 1 5.51 15.1h-.548a1 1 0 0 1-.916-.599l-1.85-3.49a68.14 68.14 0 0 0-.202-.003A2.014 2.014 0 0 1 0 9V7a2.02 2.02 0 0 1 1.992-2.013 74.663 74.663 0 0 0 2.483-.075c3.043-.154 6.148-.849 8.525-2.199V2.5zm1 0v11a.5.5 0 0 0 1 0v-11a.5.5 0 0 0-1 0zm-1 1.35c-2.344 1.205-5.209 1.842-8 2.033v4.233c.18.01.359.022.537.036 2.568.189 5.093.744 7.463 1.993V3.85zm-9 6.215v-4.13a95.09 95.09 0 0 1-1.992.052A1.02 1.02 0 0 0 1 7v2c0 .55.448 1.002 1.006 1.009A60.49 60.49 0 0 1 4 10.065zm-.657.975l1.609 3.037.01.024h.548l-.002-.014-.443-2.966a68.019 68.019 0 0 0-1.722-.082z"></path>'
                    . '</svg>';

                    // Default page
                    $page = 'news';

                    // Verify the alert's type
                    if ( $notification['alert_type'] === '1' ) {

                        // Set icon
                        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gift" viewBox="0 0 16 16">'
                            . '<path d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zM1 4v2h6V4H1zm8 0v2h6V4H9zm5 3H9v8h4.5a.5.5 0 0 0 .5-.5V7zm-7 8V7H2v7.5a.5.5 0 0 0 .5.5H7z"></path>'
                        . '</svg>';

                        // Set page
                        $page = 'offers';

                    } else if ( $notification['alert_type'] === '2' ) {

                        // Set icon
                        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">'
                            . '<path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"></path>'
                        . '</svg>';

                        // Set page
                        $page = 'miscellaneous';                        
                        
                    } else if ( $notification['alert_type'] === '3' ) {

                        // Set icon
                        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">'
                            . '<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path><path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"></path>'
                        . '</svg>';

                        // Set page
                        $page = 'errors';                         
                        
                    }
                    
                    // Set notification
                    $all_notifications .= '<li class="' . $new . '">'
                                . '<div class="row">'
                                    . '<div class="col-lg-2 col-xs-3 col-3">'
                                        . $icon
                                    . '</div>'
                                    . '<div class="col-lg-10 col-xs-9 col-9">'
                                        . '<p>'
                                            . '<a href="' . site_url('user/notifications?p=' . $page . '&alert=' . $notification['alert_id']) . '">'
                                                . $notification['name']
                                            . '</a>'
                                        . '</p>'
                                        . '<span>'
                                            . calculate_time($notification['created'], time())
                                        . '</span>'
                                    . '</div>'
                                . '</div>'
                            . '</li>';
                            
                }
                
            } else {
                
                // No notifications message
                $all_notifications = '<li>'
                    . '<div class="col-xl-12 clean col-xs-12">'
                        . '<p class="no-results">'
                            . $CI->lang->line('theme_no_notifications_found')
                        . '</p>'
                    . '</div>'
                . '</li>';
                
            }

            // Display alert
            echo '<li class="dropdown">'
                    . '<a class="dropdown-toggle" data-toggle="dropdown">'
                        . '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">'
                            . '<path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>'
                        . '</svg>'
                        . '<span class="label label-primary">'
                            . $count
                        . '</span>'
                    . '</a>'
                    . '<ul class="dropdown-menu notificationss">'
                        . '<li>'
                            . $CI->lang->line('theme_notifications')
                        . '</li>'
                        . $all_notifications
                        . '<li>'
                            . '<a href="' . site_url('user/notifications') . '">'
                                . $CI->lang->line('theme_see_all')
                            . '</a>'
                        . '</li>'
                    . '</ul>'
                . '</li>';

        }

    }

);

/**
 * The public method add_hook registers a hook
 * 
 * @since 0.0.7.9
 */
add_hook(
    'the_tickets',
    function () {

        // Verify if the component is enabled
        if ( the_option('component_faq_enable') ) {

            // Get codeigniter object instance
            $CI = get_instance();
            
            // Load Tickets Model
            $this->CI->load->ext_model( MIDRUB_BASE_USER . 'themes/blue/models/', 'Blue_tickets_model', 'blue_tickets_model' );

            // Get all tickets
            $all_tickets = $CI->blue_tickets_model->get_all_tickets_for( $CI->user_id );
            
            $tickets = '';
            
            $count = 0;
            
            // Verify if tickets exists
            if ( $all_tickets ) {
                
                // List all tickets
                foreach ( $all_tickets as $ticket ) {
                    
                    // Define new variable
                    $new = '';
                    
                    // Verify if the ticket was read already
                    if ( $ticket['status'] == 2 ) {
                        
                        $new = 'new';
                        $count++;
                        
                    }
                    
                    $tickets .= '<li class="' . $new . '">'
                                    . '<div class="row">'
                                        . '<div class="col-lg-2 col-xs-3 col-3">'
                                            . '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cup" viewBox="0 0 16 16">'
                                                . '<path d="M1 2a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v1h.5A1.5 1.5 0 0 1 16 4.5v7a1.5 1.5 0 0 1-1.5 1.5h-.55a2.5 2.5 0 0 1-2.45 2h-8A2.5 2.5 0 0 1 1 12.5V2zm13 10h.5a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.5-.5H14v8zM13 2H2v10.5A1.5 1.5 0 0 0 3.5 14h8a1.5 1.5 0 0 0 1.5-1.5V2z"/>'
                                            . '</svg>'
                                        . '</div>'
                                        . '<div class="col-lg-10 col-xs-9 col-9">'
                                            . '<p>'
                                                . '<a href="' . site_url('user/faq?p=tickets&ticket=' . $ticket['ticket_id']) . '">'
                                                    . $ticket['subject']
                                                . '</a>'
                                            . '</p>'
                                            . '<span>'
                                                . calculate_time($ticket['created'], time())
                                            . '</span>'
                                        . '</div>'
                                    . '</div>'
                                . '</li>';
                }
                
            } else {
                
                // No tickets found
                $tickets = '<li>'
                    . '<div class="col-lg-12 clean col-xs-12">'
                        . '<p class="no-results">'
                            . $CI->lang->line('theme_no_tickets_found')
                        . '</p>'
                    . '</div>'
                . '</li>';
                
            }

            echo '<li class="dropdown">'
                    . '<a class="dropdown-toggle" data-toggle="dropdown">'
                        . '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">'
                            . '<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>'
                            . '<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>'
                        . '</svg>'
                        . '<span class="label label-success">'
                            . $count
                        . '</span>'
                    . '</a>'
                    . '<ul class="dropdown-menu show-tickets-lists">'
                        . '<li>'
                            . $CI->lang->line('theme_my_tickets')
                        . '</li>'
                        . $tickets
                        . '<li>'
                            . '<a href="' . site_url('user/faq') . '">'
                                . $CI->lang->line('theme_support_center')
                            . '</a>'
                        . '</li>'
                    . '</ul>'
                . '</li>';

        }

    }

);

/**
 * The public method add_hook registers a hook
 * 
 * @since 0.0.7.9
 */
add_hook(
    'the_user_profile',
    function () {

        // Get codeigniter object instance
        $CI = get_instance();
        
        $user_profile = array();
        
        if ( !$CI->session->userdata( 'member' ) ) {
        
            // Gets current user's information
            $user_info = $CI->user->get_user_info( $CI->user_id );

            if ( $user_info['first_name'] ) {

                $user_profile['name'] = $user_info['first_name'] . ' ' . $user_info['last_name'];

            } else {

                $user_profile['name'] = $user_info['username'];

            }

            $user_profile['email'] = $user_info['email'];
            
        } else {
            
            // Load Team Model
            $CI->load->model('team');
            
            // Get member team info
            $member_info = $CI->team->get_member( $CI->user_id, 0, $CI->session->userdata( 'member' ) );

            $user_profile['name'] = $member_info[0]->member_username;

            $user_profile['email'] = $member_info[0]->member_email;
            
        }

        echo '<li class="dropdown profile-menu">'
            . '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'
                . '<img src="https://www.gravatar.com/avatar/' . md5($user_profile['email']) . '">'
                . '<strong>'
                    . $user_profile['name']
                . '</strong>'
                . '<i class="fas fa-sort-down"></i>'
            . '</a>'
            . '<ul class="dropdown-menu">';

            get_menu(
                'user_top_menu',
                array(
                    'before_menu' => '',
                    'before_single_item' => '<li[active]><a href="[url]"><i class="[class]"></i>[text]',
                    'after_single_item' => '</a></li>',
                    'after_menu' => ''
                )
            );

                echo '<li>'
                    . '<a href="' . site_url('logout') . '">'
                        . '<i class="icon-logout"></i>'
                        . $CI->lang->line('theme_sign_out')
                    . '</a>'
                . '</li>'
            . '</ul>'
        . '</li>';

    }

);