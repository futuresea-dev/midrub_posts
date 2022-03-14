/*
 * Coupon Codes JavaScript file
*/

jQuery(document).ready(function () {
    'use strict';
    
    /*
     * Get the website's url
     */
    var url = $('meta[name=url]').attr('content');

    /*
     * Get coupon codes by page
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.8.4
     */
    Main.get_coupon_codes = function (page) {
        
        // Prepare data to send
        var data = {
            action: 'get_coupon_codes',
            page: page
        };

        // Set the CSRF field
        data[$('.settings-page').attr('data-csrf')] = $('.settings-page').attr('data-csrf-value');
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/settings', 'POST', data, 'display_coupon_codes_response');
        
    };

    /*******************************
    ACTIONS
    ********************************/

    /*
     * Load default content
     * 
     * @since   0.0.8.4 
     */
    $(function () {

        // Get coupon codes by page
        Main.get_coupon_codes(1);

    });

    /*
     * Detect pagination click
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.4
     */    
    $( document ).on( 'click', '.settings-page .pagination li a', function (e) {
        e.preventDefault();
        
        // Get the page number
        var page = $(this).attr('data-page');
        
        // Display results
        switch ( $(this).closest('ul').attr('data-type') ) {
            
            case 'coupons':
                
                // Get coupon codes by page
                Main.get_coupon_codes(page);

                break;          
            
        }
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });  

    /*
     * Detect coupon code deletion
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.4
     */    
    $( document ).on( 'click', '.settings-page .coupon-codes .delete-coupon', function (e) {
        e.preventDefault();
        
        // Prepare data to send
        var data = {
            action: 'delete_coupon_code',
            code: $(this).attr('data-coupon')
        };

        // Set the CSRF field
        data[$('.settings-page').attr('data-csrf')] = $('.settings-page').attr('data-csrf-value');
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/settings', 'POST', data, 'delete_coupon_code_response');
        
    });

    /*******************************
    RESPONSES
    ********************************/

    /*
     * Display coupon codes response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.4
     */
    Main.methods.display_coupon_codes_response = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {

            // Set current page
            Main.pagination.page = data.page;

            // Set total number of coupons
            Main.show_pagination('.settings-page', data.total);
            
            // All coupons container
            var all_coupons = '';

            // List all coupons
            for ( var c = 0; c < data.coupons.length; c++ ) {

                // Ad coupon to container
                all_coupons += '<li>'
                    + data.coupons[c].code
                    + '<span> - '
                        + data.coupons[c].value
                    + ' %</span>'
                    + '<button type="button" class="btn btn-danger pull-right delete-coupon" data-coupon="' + data.coupons[c].coupon_id + '">'
                        + '<i class="fa fa-times"></i>'
                    + '</button>'
                + '</li>';

            }

            // Display the coupons
            $( '.settings-page .coupons-list .coupon-codes' ).html( all_coupons );
            
        } else {
            
            // Display the no coupons message
            $( '.settings-page .coupons-list .coupon-codes' ).html('<p>' + data.message + '</p>');
            
        }
        
    };

    /*
     * Display deletion coupon codes response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.4
     */
    Main.methods.delete_coupon_code_response = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Get coupon codes by page
            Main.get_coupon_codes(1);
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };
    
});