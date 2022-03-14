/*
 * Tab Transactions JavaScript file
*/

jQuery(document).ready( function ($) {
    'use strict';
    
    /*
     * Get the website's url
     */
    var url = $('meta[name=url]').attr('content');
    
    /*******************************
    METHODS
    ********************************/

    /*
     * Load payments transactions
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.8.3
     */    
    Main.load_payments_transactions = function (page) {

        // Verify if is the member's page
        if ( $('.members-page .members-save-form').length > 0 ) {

            // Prepare data to send
            var data = {
                action: 'load_payments_transactions',
                member_id: $('.members-page .members-save-form').attr('data-member'),
                page: page
            };
            
            // Set CSRF field
            data[$('.members-page .members-save-form').attr('data-csrf')] = $('input[name="' + $('.members-page .members-save-form').attr('data-csrf') + '"]').val();
            
            // Make ajax call
            Main.ajax_call(url + 'admin/ajax/members', 'POST', data, 'load_payments_transactions');

        }
        
    };

    /*
     * Display transactions pagination
     */
    Main.show_transactions_pagination = function( id, total ) {
        
        // Empty pagination
        $( id + ' .pagination' ).empty();
        
        // Verify if page is not 1
        if ( parseInt(Main.pagination.page) > 1 ) {
            
            var bac = parseInt(Main.pagination.page) - 1;
            var pages = '<li><a href="#" data-page="' + bac + '">' + translation.mm128 + '</a></li>';
            
        } else {
            
            var pages = '<li class="pagehide"><a href="#">' + translation.mm128 + '</a></li>';
            
        }
        
        // Count pages
        var tot = parseInt(total) / 10;
        tot = Math.ceil(tot) + 1;
        
        // Calculate start page
        var from = (parseInt(Main.pagination.page) > 2) ? parseInt(Main.pagination.page) - 2 : 1;
        
        // List all pages
        for ( var p = from; p < parseInt(tot); p++ ) {
            
            // Verify if p is equal to current page
            if ( p === parseInt(Main.pagination.page) ) {
                
                // Display current page
                pages += '<li class="active"><a data-page="' + p + '">' + p + '</a></li>';
                
            } else if ( (p < parseInt(Main.pagination.page) + 3) && (p > parseInt(Main.pagination.page) - 3) ) {
                
                // Display page number
                pages += '<li><a href="#" data-page="' + p + '">' + p + '</a></li>';
                
            } else if ( (p < 6) && (Math.round(tot) > 5) && ((parseInt(Main.pagination.page) === 1) || (parseInt(Main.pagination.page) === 2)) ) {
                
                // Display page number
                pages += '<li><a href="#" data-page="' + p + '">' + p + '</a></li>';
                
            } else {
                
                break;
                
            }
            
        }
        
        // Verify if current page is 1
        if (p === 1) {
            
            // Display current page
            pages += '<li class="active"><a data-page="' + p + '">' + p + '</a></li>';
            
        }
        
        // Set the next page
        var next = parseInt( Main.pagination.page );
        next++;
        
        // Verify if next page should be displayed
        if (next < Math.round(tot)) {
            
            $( id + ' .pagination' ).html( pages + '<li><a href="#" data-page="' + next + '">' + translation.mm129 + '</a></li>' );
            
        } else {
            
            $( id + ' .pagination' ).html( pages + '<li class="pagehide"><a href="#">' + translation.mm129 + '</a></li>' );
            
        }
        
    };

    /*******************************
    ACTIONS
    ********************************/

    /*
     * Displays pagination by page click
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.3
     */    
    $( document ).on( 'click', 'body .pagination li a', function (e) {
        e.preventDefault();
        
        // Get the page number
        var page = $(this).attr('data-page');

        // Display results
        switch ($(this).closest('ul').attr('data-type')) {

            case 'member-transactions':

                // Load all transactions
                Main.load_payments_transactions(page);

                // Display loading animation
                $('.page-loading').fadeIn('slow');                

                break;

        }
        
    });
   
    /*******************************
    RESPONSES
    ********************************/ 

    /*
     * Display transactions
     * 
     * @param string status contains the response status
     * @param object data contains the response transaction
     * 
     * @since   0.0.8.3
     */
    Main.methods.load_payments_transactions = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {

            // Generate pagination
            Main.pagination.page = data.page;
            Main.show_transactions_pagination('.members-page #members-member-tab-transactions', data.total);

            // All transactions
            var all_transactions = '';
            
            // List all transactions
            for ( var c = 0; c < data.transactions.length; c++ ) {

                // Default status
                var status = '<span class="label label-default">'
                                + data.words.incomplete
                            + '</span>';

                // Verify if transaction was successfully
                if ( data.transactions[c].status === '1' ) {

                    status = '<span class="label label-primary">'
                                    + data.words.success
                                + '</span>';         
    
                } else if ( data.transactions[c].status > 1 ) {

                    status = '<span class="label label-danger">'
                                    + data.words.error
                                + '</span>';                        

                }

                // Set transaction
                all_transactions += '<li class="transactions-single" data-id="' + data.transactions[c].transaction_id + '">'
                    + '<div class="row">'
                        + '<div class="col-lg-8 col-md-7 col-xs-6">'
                            + '<a href="' + url + 'admin/admin?p=transactions&transaction=' + data.transactions[c].transaction_id + '">'
                                + '#' + data.transactions[c].transaction_id
                            + '</a>'
                        + '</div>'
                        + '<div class="col-lg-4 col-md-5 col-xs-6 text-right">'
                            + status
                        + '</div>'
                    + '</div>'
                + '</li>';

            }

            // Get the page
            var page = ( (data.page - 1) < 1)?1:((data.page - 1) * 10);

            // Get results to
            var to = ((parseInt(page) * 10) < data.total)?(parseInt(data.page) * 10):data.total;

            // Display transactions
            $('.members-page #members-member-tab-transactions .list-transactions').html(all_transactions);

            // Display start listing
            $('.members-page #members-member-tab-transactions .pagination-from').text(page);  
            
            // Display end listing
            $('.members-page #members-member-tab-transactions .pagination-to').text(to);  

            // Display total items
            $('.members-page #members-member-tab-transactions .pagination-total').text(data.total);

            // Show Pagination
            $('.members-page #members-member-tab-transactions .pagination-area').show();  
            
        } else {

            // Hide Pagination
            $('.members-page #members-member-tab-transactions .pagination-area').hide();  
            
            // Set no data found message
            var no_data = '<li>'
                                + data.message
                            + '</li>';

            // Display transactions
            $('.members-page #members-member-tab-transactions .list-transactions').html(no_data);   
            
        }

    };

    // Load all transactions
    Main.load_payments_transactions(1);
 
});