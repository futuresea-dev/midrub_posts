/*
 * All Members JavaScript file
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
     * Load members by page
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.8.3
     */    
    Main.members_load_all =  function (page) {

        // Prepare data to send
        var data = {
            action: 'members_load_all',
            key: $('.members-page .search-members').val(),
            page: page
            
        };
        
        // Set the CSRF field
        data[$('.members-page .csrf-sanitize').attr('name')] = $('.members-page .csrf-sanitize').val();
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/members', 'POST', data, 'members_load_all');
        
    };

    /*
     * Display members pagination
     *
     * @param string id contains the box identifier
     * @param integer total contains the total results 
     * 
     * @since   0.0.8.3
     */
    Main.show_members_pagination = function( id, total ) {
        
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

    /*
     * Load the members dropdowns
     * 
     * @since   0.0.8.3
     */    
    Main.reload_members_dropdowns = function () {
            
        // List all dropdowns
        for ( var d = 0; d < $('.members-page .members-dynamic-dropdown-btn').length; d++ ) {

            // Get field's url
            let field_url = $('.members-page .members-dynamic-dropdown-btn').eq(d).attr('data-field-url');     

            // Prepare data to send
            var data = {
                action: 'reload_members_dropdowns',
                field_id: $('.members-page .members-dynamic-dropdown-btn').eq(d).attr('data-field-id')
            };

            // Verify if member's ID exists
            if ( $('.members-page .members-save-form').attr('data-member') ) {

                // Set member's ID
                data['member_id'] = $('.members-page .members-save-form').attr('data-member');

            }
            
            // Set CSRF field
            data[$('.members-page .members-save-form').attr('data-csrf')] = $('input[name="' + $('.members-page .members-save-form').attr('data-csrf') + '"]').val();

            // Make ajax call
            Main.ajax_call(field_url, 'POST', data, 'reload_members_dropdowns');

        }
        
    };

    /*******************************
    ACTIONS
    ********************************/

    /*
     * Search members
     * 
     * @since   0.0.8.3
     */
    $(document).on('keyup', '.members-page .search-members', function () {
        
        // Load all members by page
        Main.members_load_all(1);
        
    });

    /*
     * Search for dropdown items
     * 
     * @since   0.0.8.3
     */
    $(document).on('keyup', '.members-page .members-search-dropdown-items', function () {

        // Get field's url
        let field_url = $(this).closest('.dropdown').find('.btn-secondary').attr('data-field-url');   

        // Prepare data to send
        var data = {
            action: 'reload_members_dropdowns',
            field_id: $(this).closest('.dropdown').find('.btn-secondary').attr('data-field-id'),
            key: $(this).val()
        };
        
        // Set CSRF field
        data[$('.members-page .members-save-form').attr('data-csrf')] = $('input[name="' + $('.members-page .members-save-form').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(field_url, 'POST', data, 'reload_members_dropdowns');
        
    });

    /*
     * Detect input keyup
     * 
     * @since   0.0.8.3
     */
    $('.members-page .members-save-form .member-field-text-input, .members-page .members-save-form .member-field-email-input, .members-page .members-save-form .member-field-password-input').keyup(function () {
        
        // Display save button
        $('.members-page .settings-save-changes').fadeIn('slow');
        
    });

    /*
     * Detect all members selection
     * 
     * @since   0.0.8.3
     */ 
    $( document ).on( 'click', '.members-page #members-select-all', function () {
        
        // Run after 500 mileseconds
        setTimeout(function(){
            
            // Verify if slect all is checked
            if ( $( '.members-page #members-select-all' ).is(':checked') ) {

                // Check all
                $( '.members-page .list-members li input[type="checkbox"]' ).prop('checked', true);

            } else {

                // Uncheck all
                $( '.members-page .list-members li input[type="checkbox"]' ).prop('checked', false);

            }
        
        },500);
        
    });

    /*
     * Select a template list item
     * 
     * @since   0.0.8.3
     */
    $(document).on('click', '.members-page .members-dropdown-list-ul a, .members-page .members-dropdown-dynamic-list-ul a', function (e) {
        e.preventDefault();

        // Get item's value
        var item_value = $(this).attr('data-value'); 
        
        // Get item's title
        var item_title = $(this).text();

        // Add item's title and item's value
        $(this).closest('.dropdown').find('.btn-secondary').text(item_title);
        $(this).closest('.dropdown').find('.btn-secondary').attr('data-value', item_value);

        // Display save button
        $('.members-page .settings-save-changes').fadeIn('slow');

    });  

    /*
     * Detect checkbox click
     * 
     * @since   0.0.8.3
     */ 
    $( document ).on( 'click', '.members-page .members-save-form .member-field-checkbox-input', function () {

        // Display save button
        $('.members-page .settings-save-changes').fadeIn('slow');

    });

    /*
     * Submit the form
     * 
     * @since   0.0.8.3
     */ 
    $( document ).on( 'click', '.settings-save-changes .btn-default', function () {

        // Save member's data
        $('.members-page .members-save-form').submit();

    });

    /*
     * Delete member by id
     * 
     * @since   0.0.8.3
     */
    $(document).on('click', '.members-page .list-members .members-delete-member', function (e) {
        e.preventDefault();

        // Prepare data to send
        var data = {
            action: 'members_delete_member',
            member_id: $(this).closest('.members-single').attr('data-id')
        };

        // Set the CSRF field
        data[$('.members-page .csrf-sanitize').attr('name')] = $('.members-page .csrf-sanitize').val();        
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/members', 'POST', data, 'delete_member_response');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });

    /*
     * Delete members by id
     * 
     * @since   0.0.8.3
     */
    $(document).on('click', '.members-page .members-delete-members', function (e) {
        e.preventDefault();

        // Define the members ids variable
        var members_ids = [];
        
        // Get selected members ids
        $('.members-page .list-members li input[type="checkbox"]:checkbox:checked').each(function () {
            members_ids.push($(this).closest('.members-single').attr('data-id'));
        });

        // Prepare data to send
        var data = {
            action: 'members_delete_members',
            members_ids: members_ids
        };

        // Set the CSRF field
        data[$('.members-page .csrf-sanitize').attr('name')] = $('.members-page .csrf-sanitize').val();        
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/members', 'POST', data, 'delete_members_response');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });

    /*
     * Displays pagination by page click
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.3
     */    
    $( document ).on( 'click', '.members-page .pagination li a', function (e) {
        e.preventDefault();
        
        // Get the page number
        var page = $(this).attr('data-page');

        // Display results
        switch ($(this).closest('ul').attr('data-type')) {

            case 'members':

                // Load all members by page
                Main.members_load_all(page);
                
                // Display loading animation
                $('.page-loading').fadeIn('slow');              

                break;

        }
        
    });
   
    /*******************************
    RESPONSES
    ********************************/ 

    /*
     * Display members
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.3
     */
    Main.methods.members_load_all = function ( status, data ) {

        // Uncheck all selected members
        $( '.members-page #members-select-all' ).prop('checked', false)

        // Verify if the success response exists
        if ( status === 'success' ) {

            // Generate pagination
            Main.pagination.page = data.page;
            Main.show_members_pagination('.members-page', data.total);

            // All members
            var all_members = '';
            
            // List all members
            for ( var m = 0; m < data.members.length; m++ ) {
                
                // Set name
                var name = (data.members[m].first_name)?data.members[m].first_name + ' ' + data.members[m].last_name:data.members[m].username;

                // Position 
                var position = data.words.administrator;

                // Verify role
                if ( parseInt(data.members[m].role) < 1 ) {

                    // Set new position
                    position = data.words.user;

                }

                // Member's status
                var member_status = '<span class="member-status-active">'
                    + data.words.active
                + '</span>';

                // Verify if status is inactive
                if ( parseInt(data.members[m].status) === 0 ) {

                    // New member's status
                    member_status = '<span class="member-status-inactive">'
                        + data.words.inactive
                    + '</span>';                    

                } else if ( parseInt(data.members[m].status) === 2 ) {

                    // New member's status
                    member_status = '<span class="member-status-blocked">'
                        + data.words.blocked
                    + '</span>';                    

                }

                // Set member
                all_members += '<li class="members-single" data-id="' + data.members[m].user_id + '">'
                                    + '<div class="row">'
                                        + '<div class="col-lg-8 col-md-5 col-xs-5">'
                                            + '<div class="checkbox-option-select">'
                                                + '<input id="members-members-single-' + data.members[m].user_id + '" name="members-members-single-' + data.members[m].user_id + '" type="checkbox" />'
                                                + '<label for="members-members-single-' + data.members[m].user_id + '"></label>'
                                            + '</div>'
                                            + '<div class="media">'
                                                + '<div class="media-left">'
                                                    + '<img class="mr-3" src="' + data.members[m].avatar + '" alt="Member Avatar" style="width: 43px;" />'
                                                + '</div>'
                                                + '<div class="media-body">'
                                                    + '<h5 class="mt-0">'
                                                        + '<a href="' + url + 'admin/members?p=all_members&member=' + data.members[m].user_id + '">'
                                                            + name
                                                        + '</a>'
                                                    + '</h5>'
                                                    + member_status
                                                + '</div>'
                                            + '</div>'
                                        + '</div>'
                                        + '<div class="col-lg-1 col-md-2 col-xs-2">'
                                            + '<div class="member-role">'
                                                + '<span class="label label-primary">'
                                                    + position
                                                + '</span>'
                                            + '</div>'
                                        + '</div>'
                                        + '<div class="col-lg-2 col-md-2 col-xs-2">'
                                            + '<div class="member-signup">'
                                                + '<span>'
                                                    + Main.calculate_time(data.members[m].time_joined, data.current_time).replace(/<[^>]*>?/gm, '')
                                                + '</span>'
                                            + '</div>'
                                        + '</div>'
                                        + '<div class="col-lg-1 col-md-2 col-xs-2 text-right">'
                                            + '<div class="btn-group">'
                                                + '<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                                    + '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-three-dots-vertical" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'
                                                        + '<path fill-rule="evenodd" d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />'
                                                    + '</svg>'
                                                + '</button>'
                                                + '<ul class="dropdown-menu">'
                                                    + '<li>'
                                                        + '<a href="#" class="members-delete-member">'
                                                            + '<i class="icon-trash"></i>'
                                                            + data.words.delete
                                                        + '</a>'
                                                    + '</li>'
                                                + '</ul>'
                                            + '</div>'
                                        + '</div>'
                                    + '</div>'
                                + '</li>';

            }

            // Get the page
            var page = ( (data.page - 1) < 1)?1:((data.page - 1) * 20);

            // Get results to
            var to = ((parseInt(page) * 20) < data.total)?(parseInt(data.page) * 20):data.total;

            // Display members
            $('.members-page .list-members').html(all_members);

            // Display start listing
            $('.members-page .pagination-from').text(page);  
            
            // Display end listing
            $('.members-page .pagination-to').text(to);  

            // Display total items
            $('.members-page .pagination-total').text(data.total);

            // Show Pagination
            $('.members-page .pagination-area').show();  
            
        } else {

            // Hide Pagination
            $('.members-page .pagination-area').hide();  
            
            // Set no data found message
            var no_data = '<li>'
                                + data.message
                            + '</li>';

            // Display members
            $('.members-page .list-members').html(no_data);   
            
        }

    };

    /*
     * Display the dropdown's items for members dropdown
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.3
     */
    Main.methods.reload_members_dropdowns = function ( status, data ) {
        
        // Verify if the success response exists
        if ( status === 'success' ) {

            // Verify if the response has items
            if ( typeof data.response.items !== 'undefined' ) {

                // Verify if the selected parameter exists
                if ( typeof data.response.selected !== 'undefined' ) {

                    // Display the selected item's Name
                    $('.members-page .members-dynamic-dropdown-btn[data-field-id="' + data.field_id + '"]').html(data.response.selected.name);

                    // Set the selected item's ID
                    $('.members-page .members-dynamic-dropdown-btn[data-field-id="' + data.field_id + '"]').attr('data-value', data.response.selected.id);

                }
                
                // Items variable
                var items = '';

                // List all items
                for ( var i = 0; i < data.response.items.length; i++ ) {

                    // Set item
                    items += '<li class="list-group-item">'
                                + '<a href="#" data-value="' + data.response.items[i].id + '">'
                                    + data.response.items[i].name
                                + '</a>'
                            + '</li>';

                }

                // Display the items
                $('.members-page .members-dynamic-dropdown-btn[data-field-id="' + data.field_id + '"]').closest('.dropdown').find('.members-dropdown-dynamic-list-ul').html(items);
                
            } else {
                
                // Prepare no items message
                var no_items = '<li class="list-group-item no-results-found">'
                                        + data.response.no_items_message
                                    + '</li>';
                        
                // Display message
                $('.members-page .members-dynamic-dropdown-btn[data-field-id="' + data.field_id + '"]').closest('.dropdown').find('.members-dropdown-dynamic-list-ul').html(no_items);

            }
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);            
            
        }

    }

    /*
     * Display the member saving response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.3
     */
    Main.methods.members_save_member = function ( status, data ) {
        
        // Verify if the success response exists
        if ( status === 'success' ) {

            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Hide save button
            $('.members-page .settings-save-changes').fadeOut('slow');            

            // Verify if member's ID exists
            if ( !$('.members-page .members-save-form').attr('data-member') ) {
            
                // Load members dropdowns
                Main.reload_members_dropdowns();

                // Reset the form
                $('.members-page .members-save-form')[0].reset();

                // Reset all checkboxes
                $('.members-page .members-save-form .member-field-checkbox-input').prop('checked',false);

                // Get all list select fields
                var list_selects = $('.members-page .members-save-form .members-dropdown-btn');

                // Verify if list select fields exists
                if (list_selects.length > 0) {

                    // List all list fields
                    for (var l = 0; l < list_selects.length; l++) {

                        // Reset button's value
                        $(list_selects[l]).attr('data-value', 0);

                        // Reset button's title
                        $(list_selects[l]).html($(list_selects[l]).attr('data-title'));

                    }

                } 

                // Get all dynamic list select fields
                var dynamic_list_selects = $('.members-page .members-save-form .members-dynamic-dropdown-btn');

                // Verify if dynamic list select fields exists
                if (dynamic_list_selects.length > 0) {

                    // List all dynamic list fields
                    for (var d = 0; d < dynamic_list_selects.length; d++) {

                        // Reset button's value
                        $(dynamic_list_selects[d]).attr('data-value', 0);

                        // Reset button's title
                        $(dynamic_list_selects[d]).html($(dynamic_list_selects[d]).attr('data-title'));                    

                        // Reset search input
                        $(dynamic_list_selects[d]).closest('dropdown').find('.members-search-dropdown-items').val('');

                    }

                }

            }
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);            
            
        }

    }

    /*
     * Display member deletion response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.3
     */
    Main.methods.delete_member_response = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Load all members by page
            Main.members_load_all(1);
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };

    /*
     * Display member deletion response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.3
     */
    Main.methods.delete_members_response = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Load all members by page
            Main.members_load_all(1);
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };

    /*******************************
    FORMS
    ********************************/

    /*
     * Save member
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.3
     */
    $('.members-page .members-save-form').submit(function (e) {
        e.preventDefault();

        // Define data to send
        var data = {
            action: 'members_save_member',
            fields: {}
        };

        // Get all text input fields
        var text_inputs = $(this).find('.member-field-text-input');

        // Verify if text input fields exists
        if (text_inputs.length > 0) {

            // List all input fields
            for (var t = 0; t < text_inputs.length; t++) {

                // Set field
                data['fields'][$(text_inputs[t]).attr('data-field-id')] = {
                    'field_id': $(text_inputs[t]).attr('data-field-id'),
                    'field_type': 'text_input',
                    'field_value': $(text_inputs[t]).val()
                };

            }

        }

        // Get all email input fields
        var email_inputs = $(this).find('.member-field-email-input');

        // Verify if email input fields exists
        if (email_inputs.length > 0) {

            // List all input fields
            for (var e = 0; e < email_inputs.length; e++) {

                // Set field
                data['fields'][$(email_inputs[e]).attr('data-field-id')] = {
                    'field_id': $(email_inputs[e]).attr('data-field-id'),
                    'field_type': 'email_input',
                    'field_value': $(email_inputs[e]).val()
                };

            }

        }

        // Get all password input fields
        var password_inputs = $(this).find('.member-field-password-input');

        // Verify if password input fields exists
        if (password_inputs.length > 0) {

            // List all input fields
            for (var p = 0; p < password_inputs.length; p++) {

                // Set field
                data['fields'][$(password_inputs[p]).attr('data-field-id')] = {
                    'field_id': $(password_inputs[p]).attr('data-field-id'),
                    'field_type': 'password_input',
                    'field_value': $(password_inputs[p]).val()
                };

            }

        }
        
        // Get all list select fields
        var list_selects = $(this).find('.members-dropdown-btn');

        // Verify if list select fields exists
        if (list_selects.length > 0) {

            // List all list fields
            for (var l = 0; l < list_selects.length; l++) {

                // Set field
                data['fields'][$(list_selects[l]).attr('data-field-id')] = {
                    'field_id': $(list_selects[l]).attr('data-field-id'),
                    'field_type': 'list_select',
                    'field_value': $(list_selects[l]).attr('data-value')
                };

            }

        } 
        
        // Get all dynamic list select fields
        var dynamic_list_selects = $(this).find('.members-dynamic-dropdown-btn');

        // Verify if dynamic list select fields exists
        if (dynamic_list_selects.length > 0) {

            // List all dynamic list fields
            for (var d = 0; d < dynamic_list_selects.length; d++) {

                // Set field
                data['fields'][$(dynamic_list_selects[d]).attr('data-field-id')] = {
                    'field_id': $(dynamic_list_selects[d]).attr('data-field-id'),
                    'field_type': 'dynamic_list_select',
                    'field_value': $(dynamic_list_selects[d]).attr('data-value')
                };

            }

        }
        
        // Get all checkbox input fields
        var checkbox_inputs = $(this).find('.member-field-checkbox-input');        
        
        // Verify if checkbox input fields exists
        if (checkbox_inputs.length > 0) {

            // List all input fields
            for (var c = 0; c < checkbox_inputs.length; c++) {

                // Verify if the checkbox is checked
                if ($(checkbox_inputs[c]).is(':checked')) {

                    // Set field
                    data['fields'][$(checkbox_inputs[c]).attr('data-field-id')] = {
                        'field_id': $(checkbox_inputs[c]).attr('data-field-id'),
                        'field_type': 'checkbox_input',
                        'field_value': 1
                    };

                } else {

                    // Set field
                    data['fields'][$(checkbox_inputs[c]).attr('data-field-id')] = {
                        'field_id': $(checkbox_inputs[c]).attr('data-field-id'),
                        'field_type': 'checkbox_input',
                        'field_value': 0
                    };
                    
                }

            }

        }

        // Verify if member's ID exists
        if ( $('.members-page .members-save-form').attr('data-member') ) {

            // Set member's ID
            data['member_id'] = $('.members-page .members-save-form').attr('data-member');

        }
        
        // Set the CSRF field
        data[$(this).attr('data-csrf')] = $('input[name="' + $(this).attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/members', 'POST', data, 'members_save_member');

        // Display loading animation
        $('.page-loading').fadeIn('slow');

    });
    
    /*******************************
    DEPENDENCIES
    ********************************/

    // Verify if member list class exists
    if ( $('.members-page .list-members').length > 0 ) {

        // Load all members by page
        Main.members_load_all(1);

    } else if ( $('.members-page .members-dynamic-dropdown-btn').length > 0 ) {

        // Load members dropdowns
        Main.reload_members_dropdowns();

    }
 
});