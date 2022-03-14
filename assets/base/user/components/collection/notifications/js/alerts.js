/*
 * Notifications Alerts JavaScript file
*/

jQuery(document).ready( function ($) {
    'use strict';
    
    /*
     * Get the website's url
     */
    var url =  $('meta[name=url]').attr('content');
    
    /*******************************
    METHODS
    ********************************/

    /*
     * Load users alerts
     * 
     * @since   0.0.8.3
     */    
    Main.notifications_load_alerts =  function () {

        // Prepare data to send
        var data = {
            action: 'load_alerts'
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/component-ajax/notifications', 'GET', data, 'notifications_display_alerts');
        
    };

    /*******************************
    ACTIONS
    ********************************/

    /*
     * Close a notification's popup
     */  
    $(document).on('click', '.notifications-popups .notifications-popup-close-btn', function (e) {
        e.preventDefault();

        // Set this
        var $this = $(this);
        
        // Hide
        $this.closest('div').slideToggle('slow');

        // Verify if is an error
        if ( $this.closest('.notifications-error-banner').length < 1 ) {

            // Remove after 1 second
            setTimeout(function() {

                // Remove
                $this.closest('div').remove();

            }, 1000);

        }

        // Prepare data to send
        var data = {
            action: 'hide_alert',
            alert: $this.attr('data-alert')
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/component-ajax/notifications', 'GET', data, 'notifications_hide_alert');        

    });

    /*
     * Close a notification's promo
     */  
    $(document).on('click', '.notifications-promo-close-btn', function (e) {
        e.preventDefault();
        
        // Hide
        $(this).closest('.notifications-promo').remove();

        // Prepare data to send
        var data = {
            action: 'hide_alert',
            alert: $(this).attr('data-alert')
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/component-ajax/notifications', 'GET', data, 'notifications_hide_alert');        

    });
   
    /*******************************
    RESPONSES
    ********************************/

    /*
     * Display users alerts
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.3
     */
    Main.methods.notifications_display_alerts = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {

            // List alerts
            for ( var a = 0; a < data.alerts.length; a++ ) {

                // Verify which alert is by type
                if ( parseInt(data.alerts[a].alert_type) === 2 ) {

                    // Set alert's content
                    var content = '<a href="#" class="notifications-popup-close-btn" data-alert="' + data.alerts[a].alert_id + '">'
                        + '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">'
                            + '<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>'
                        + '</svg>'
                    + '</a>'
                    + '<div>'
                        + data.alerts[a].content
                    + '</div>';

                    // Set fixed
                    $('.notifications-popups .notifications-fixed-banner').html(content);

                    // Show alert
                    $('.notifications-popups .notifications-fixed-banner').slideToggle('slow');

                } else if ( parseInt(data.alerts[a].alert_type) === 1 ) {

                    // Set alert
                    var alert = data.alerts[a];

                    // Run after 5 seconds
                    setTimeout(function() {

                        // Set alert's content
                        var content = '<a href="#" class="notifications-promo-close-btn" data-alert="' + alert.alert_id + '">'
                            + '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">'
                                + '<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>'
                            + '</svg>'
                        + '</a>'
                        + '<div>'
                            + alert.content
                        + '</div>';                          

                        // Set promo
                        $('.notifications-promo .notifications-promo-banner').html(content);                        

                        // Show alert
                        $('.notifications-promo').css({'display': 'flex'});

                        // Zoom promo
                        $('.notifications-promo .notifications-promo-banner').animate({transform: 1},
                            {duration: 100,easing: 'linear',
                            step: function(now) {

                                // Set scale
                                $(this).css('transform','scale('+now+')')
                            }

                        }).css({'opacity': '1'});

                    }, 5000);

                } else if ( parseInt(data.alerts[a].alert_type) === 0 ) {

                    // Set alert's content
                    var content = '<a href="#" class="notifications-popup-close-btn" data-alert="' + data.alerts[a].alert_id + '">'
                        + '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">'
                            + '<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>'
                        + '</svg>'
                    + '</a>'
                    + '<div>'
                        + data.alerts[a].content
                    + '</div>';

                    // Set news
                    $('.notifications-popups .notifications-news-banner').html(content);  
                    
                    // Show alert
                    $('.notifications-popups .notifications-news-banner').slideToggle('slow');

                }

            }
            
        }

        // Reload this code every 15 seconds
        setInterval(function () {

            // Prepare data to send
            var data = {
                action: 'load_error_alerts'
            };
            
            // Make ajax call
            Main.ajax_call(url + 'user/component-ajax/notifications', 'GET', data, 'notifications_display_error_alerts');

        }, 15000);

    }

    /*
     * Display errors alerts
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.4
     */
    Main.methods.notifications_display_error_alerts = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {

            // Verify if the alert already exist
            if ( $('.notifications-popups .notifications-error-banner .notifications-popup-close-btn[data-alert="' + data.alerts[0].alert_id + '"]').length < 1 ) {

                // Set alert's content
                var content = '<a href="#" class="notifications-popup-close-btn" data-alert="' + data.alerts[0].alert_id + '">'
                    + '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">'
                        + '<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>'
                    + '</svg>'
                + '</a>'
                + '<div>'
                    + data.alerts[0].content
                + '</div>';

                // Set news
                $('.notifications-popups .notifications-error-banner').html(content);  
                
                // Show alert
                $('.notifications-popups .notifications-error-banner').fadeIn('slow');

            }
            
        }

    }

    /*
     * Hide alert response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.3
     */
    Main.methods.notifications_hide_alert = function ( status, data ) {}
    
    /*******************************
    DEPENDENCIES
    ********************************/

    // Load users alerts
    Main.notifications_load_alerts();
 
});