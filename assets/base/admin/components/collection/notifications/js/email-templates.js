/*
 * Email Templates JavaScript file
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
     * Load email templates by page
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.8.3
     */    
    Main.email_templates_load_all =  function (page) {

        // Prepare data to send
        var data = {
            action: 'email_templates_load_all',
            key: $('.notifications-page .search-templates').val(),
            page: page
            
        };
        
        // Set the CSRF field
        data[$('.notifications-page .csrf-sanitize').attr('name')] = $('.notifications-page .csrf-sanitize').val();
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/notifications', 'POST', data, 'email_templates_display_all');
        
    };

    /*
     * Display email templates pagination
     *
     * @param string id contains the box identifier
     * @param integer total contains the total results 
     * 
     * @since   0.0.8.3
     */
    Main.show_email_templates_pagination = function( id, total ) {
        
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
        var tot = parseInt(total) / 20;
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
     * Search for email templates
     * 
     * @since   0.0.8.3
     */
    $(document).on('keyup', '.notifications-page .search-templates', function () {
        
        // Load all email's templates by page
        Main.email_templates_load_all(1);
        
    });

    /*
     * Detect when a template is selected
     * 
     * @since   0.0.8.3
     */
    $(document).on('change', '.notifications-page .notifications-email-template-select', function () {
        
        // Prepare data to send
        var data = {
            action: 'get_email_template_placeholder',
            template_slug: $(this).val()
        };
        
        // Set CSRF
        data[$('.notifications-page form').attr('data-csrf')] = $('input[name="' + $('.notifications-page form').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/notifications', 'POST', data, 'display_email_template_placeholder');
        
    });

    /*
     * Detects pagination click
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.3
     */    
    $( document ).on( 'click', '.notifications-page .pagination li a', function (e) {
        e.preventDefault();
        
        // Get the page number
        var page = $(this).attr('data-page');

        // Display results
        switch ($(this).closest('ul').attr('data-type')) {

            case 'templates':

                // Load all email's templates by page
                Main.email_templates_load_all(page);
                
                // Display loading animation
                $('.page-loading').fadeIn('slow');              

                break;

        }
        
    });
   
    /*******************************
    RESPONSES
    ********************************/ 

    /*
     * Display email templates
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.3
     */
    Main.methods.email_templates_display_all = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {

            // Generate pagination
            Main.pagination.page = data.page;
            Main.show_email_templates_pagination('.notifications-page', data.total);

            // All templates
            var all_templates = '';
            
            // List all templates
            for ( var t = 0; t < data.templates.length; t++ ) {

                // Set template
                all_templates += '<li class="templates-single">'
                                    + '<div class="row">'
                                        + '<div class="col-lg-8 col-md-5 col-xs-5">'
                                            + '<h5 class="mt-0">'
                                                + '<a href="' + url + 'admin/notifications?p=email_templates&template=' + data.templates[t].template_id + '">'
                                                    + data.templates[t].template_title
                                                + '</a>'
                                            + '</h5>'
                                        + '</div>'
                                        + '<div class="col-lg-4 col-md-7 col-xs-7">'
                                            + '<h5 class="mt-0">'
                                                + data.templates[t].template
                                            + '</h5>'
                                        + '</div>'                                        
                                    + '</div>'
                                + '</li>';

            }

            // Get the page
            var page = ( (data.page - 1) < 1)?1:((data.page - 1) * 20);

            // Get results to
            var to = ((parseInt(page) * 20) < data.total)?(parseInt(data.page) * 20):data.total;

            // Display the email templates
            $('.notifications-page .notifications-list-templates').html(all_templates);

            // Display start listing
            $('.notifications-page .pagination-from').text(page);  
            
            // Display end listing
            $('.notifications-page .pagination-to').text(to);  

            // Display total items
            $('.notifications-page .pagination-total').text(data.total);

            // Show Pagination
            $('.notifications-page .pagination-area').show();  
            
        } else {

            // Hide Pagination
            $('.notifications-page .pagination-area').hide();  
            
            // Set no data found message
            var no_data = '<li>'
                                + data.message
                            + '</li>';

            // Display the no data found message
            $('.notifications-page .notifications-list-templates').html(no_data);   
            
        }

    }

     /*
     * Display template's placeholders
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.3
     */
    Main.methods.display_email_template_placeholder = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {

            // Placeholders container
            var placeholders = '';

            // List all placeholders
            for ( var p = 0; p < data.placeholders.length; p++ ) {

                // Set placeholder
                placeholders += '<li>'
                    + '<p>'
                        + '<span class="notifications-emails-template-placeholder">'
                            + data.placeholders[p].code
                        + '</span>'
                        + data.placeholders[p].description
                    + '</p>'
                + '</li>';

            }

            // Display the placeholders
            $('.notifications-page .notifications-emails-template-placeholders').html(placeholders);
            
        } else {
            
            // Set no placeholders found message
            var no_placeholders = '<li>'
                + '<p>'
                    + data.message
                + '</p>'
            + '</li>';

            // Display the no placeholders found message
            $('.notifications-page .notifications-emails-template-placeholders').html(no_placeholders);

        }

    };  

    /*
     * Display the template creation response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.3
     */
    Main.methods.create_email_template = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Get selected template
            var template_slug = $('.notifications-page .notifications-create-email-template .notifications-email-template-select').val();

            // Verify if template's slug exists
            if ( template_slug ) {

                // Remove select's option
                $('.notifications-page .notifications-email-template-select option[value="' + template_slug + '"]').remove()

            }

            // Reset the form
            $('.notifications-page .notifications-create-email-template')[0].reset();

            // Get langs
            var langs = $(document).find('.tab-content .tab-pane');

            // List langs
            for (var e = 0; e < langs.length; e++) {

                // Display editor
                $('.' + $(langs[e]).find('.summernote-body').attr('data-dir')).summernote('reset');

            } 
            
            // Set no placeholders found message
            var no_placeholders = '<li>'
                + '<p>'
                    + data.words.no_placeholders_found
                + '</p>'
            + '</li>';

            // Display the no placeholders found message
            $('.notifications-page .notifications-emails-template-placeholders').html(no_placeholders);            
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };

    /*
     * Display the template update response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.3
     */
    Main.methods.update_email_template = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);         
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };

    /*******************************
    FORMS
    ********************************/

    /*
     * Create an email template
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.3
     */
    $('.notifications-page .notifications-create-email-template').submit(function (e) {
        e.preventDefault();

        // Prepare data to send
        var data = {
            action: 'create_email_template',
            template: $('.notifications-page .notifications-create-email-template .notifications-email-template-select').val()
        };
        
        // Get all editors
        var editors = $('.notifications-page .tab-all-editors > .tab-pane');
        
        // List all editors
        for ( var d = 0; d < editors.length; d++ ) {
            
            // Set editor's data
            data[$(editors[d]).attr('id')] = {
                'title': $(editors[d]).find('.article-title').val(),
                'body': $(editors[d]).find('.summernote-body').summernote('code')
            };
            
        }

        // Set CSRF
        data[$(this).attr('data-csrf')] = $('input[name="' + $(this).attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/notifications', 'POST', data, 'create_email_template');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });

    /*
     * Update an email template
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.3
     */
    $('.notifications-page .notifications-update-email-template').submit(function (e) {
        e.preventDefault();

        // Prepare data to send
        var data = {
            action: 'update_email_template',
            template_id: $(this).attr('data-template'),
            template: $('.notifications-page .notifications-update-email-template .notifications-email-template-select').val()
        };
        
        // Get all editors
        var editors = $('.notifications-page .tab-all-editors > .tab-pane');
        
        // List all editors
        for ( var d = 0; d < editors.length; d++ ) {
            
            // Set editor's data
            data[$(editors[d]).attr('id')] = {
                'title': $(editors[d]).find('.article-title').val(),
                'body': $(editors[d]).find('.summernote-body').summernote('code')
            };
            
        }

        // Set CSRF
        data[$(this).attr('data-csrf')] = $('input[name="' + $(this).attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/notifications', 'POST', data, 'update_email_template');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });    
    
    /*******************************
    DEPENDENCIES
    ********************************/

    // Verify if is the page with email templates
    if ( $('.notifications-page .notifications-list-templates').length > 0 ) {

        // Load all email's templates by page
        Main.email_templates_load_all(1);

    } else if ( ( $('.notifications-page .notifications-email-template').length > 0 ) || ( $('.notifications-page .notifications-new-email-template').length > 0 ) ) {

        // Get langs
        var langs = $(document).find('.tab-content .tab-pane');

        // List langs
        for (var e = 0; e < langs.length; e++) {

            // Display editor
            $('.' + $(langs[e]).find('.summernote-body').attr('data-dir')).summernote('code', $(langs[e]).find('.article-body').val());

        }

    }
 
});