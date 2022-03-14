/*
 * Offers Notifications JavaScript file
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
     * Load notifications by page
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.8.3
     */
    Main.load_offers_by_page = function (page) {
        
        // Prepare data
        var data = {
            action: 'load_offers_by_page',
            page: page
        };

        // Make ajax call
        Main.ajax_call(url + 'user/component-ajax/notifications', 'GET', data, 'display_offers_by_page_response');
        
    };
    
    
    /*******************************
    ACTIONS
    ********************************/ 

    /*
     * Load default content
     * 
     * @since   0.0.8.3 
     */
    $(function () {

        // Load offers alerts
        Main.load_offers_by_page(1);

    });
    
    /*
     * Detect notifications pagination click
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.3
     */ 
    $( document ).on( 'click', '.notifications-page .next-button, .notifications-page .back-button', function (e) {
        e.preventDefault();
        
        // Get page number
        var page = $(this).attr('data-page');
        
        // Loads notifications
        Main.load_offers_by_page(page);
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });

    /*
     * Detect notification delete click
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.3
     */ 
    $( document ).on( 'click', '.notifications-page .notifications-delete-alert', function (e) {
        e.preventDefault();
        
        // Get alert's id
        var alert_id = $(this).closest('li').attr('data-alert');
        
        // Prepare data
        var data = {
            action: 'delete_offers_alert',
            alert: alert_id
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/component-ajax/notifications', 'GET', data, 'delete_offers_alert_response');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*******************************
    RESPONSES
    ********************************/
   
    /*
     * Display offers alerts
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.3
     */
    Main.methods.display_offers_by_page_response = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            if ( $('.notifications-page .next-button').length > 0 ) {
            
                if ( data.page < 2 ) {
                    
                    $('.notifications-page .back-button').addClass('btn-disabled');
                    
                } else {
                    
                    $('.notifications-page .back-button').removeClass('btn-disabled');
                    $('.notifications-page .back-button').attr('data-page', (parseInt(data.page) - 1));
                    
                }
                
                if ( (parseInt(data.page) * 10 ) < data.total ) {
                    
                    $('.notifications-page .next-button').removeClass('btn-disabled');
                    $('.notifications-page .next-button').attr('data-page', (parseInt(data.page) + 1));
                    
                } else {
                    
                    $('.notifications-page .next-button').addClass('btn-disabled');
                    
                }
            
            }
            
            var notifications = '';
            
            // List all alerts offers
            for ( var n = 0; n < data.offers.length; n++ ) {

                // Unread mark
                var unread = '';

                // Verify if the offers alert is unread
                if ( data.offers[n].page_seen === null ) {
                    unread = ' class="notifications-unread-notification"';
                }
                
                notifications += '<li data-alert="' + data.offers[n].alert_id + '"' + unread + '>'
                    + '<div class="row">'
                        + '<div class="col-11">'
                            + '<a href="' + url + 'user/notifications?p=offers&alert=' + data.offers[n].alert_id + '" class="notifications-show-notification">'
                                + '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">'
                                    + '<path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.523-3.356c.329-.314.158-.888-.283-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767l-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288l1.847-3.658 1.846 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.564.564 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>'
                                + '</svg>'
                                + data.offers[n].name
                            + '</a>'
                        + '</div>'
                        + '<div class="col-1">'
                            + '<div class="btn-group">'
                                + '<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                    + '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">'
                                        + '<path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>'
                                    + '</svg>'
                                + '</button>'
                                + '<div class="dropdown-menu dropdown-menu-action">'
                                    + '<a href="#" class="notifications-delete-alert">'
                                        + '<i class="icon-trash"></i>'
                                        + data.words.delete
                                    + '</a>'
                                + '</div>'
                            + '</div>'
                        + '</div>'
                    + '</div>'
                + '</li>';
                
            }
            
            // Display notifications
            $( '.notifications-page .notifications-list-show' ).html( notifications );
            
        } else {

            // Set no alerts found message
            var no_alerts = '<li class="notifications-no-results-found">'
                + data.message
            + '</li>';
            
            // Display no alerts found message
            $( '.notifications-page .notifications-list-show' ).html(no_alerts);
            
        }

    }

    /*
     * Display alert deletion status
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.3
     */
    Main.methods.delete_offers_alert_response = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Loads notifications
            Main.load_offers_by_page(1);
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };  
    
    /*******************************
    FORMS
    ********************************/
    
    // Loads notifications
    Main.load_offers_by_page(1);

});