/*
 * Storage JavaScript file
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
     * Get items for a storage's dropdown
     * 
     * @since   0.0.8.4
     */    
    Main.settings_get_storage_dropdown_items =  function () {

        // Prepare data
        var data = {
            action: 'settings_get_storage_dropdown_items',
            key: $('.settings-page .settings-storage-locations-search').val()
        };

        // Set the CSRF field
        data[$('.settings-page').attr('data-csrf')] = $('.settings-page').attr('data-csrf-value');
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/settings', 'POST', data, 'settings_display_storage_dropdown_items');
        
    };

    /*
     * Get the selected storage's location
     * 
     * @since   0.0.8.4
     */    
    Main.settings_get_selected_storage_location = function () {

        // Prepare data
        var data = {
            action: 'settings_get_selected_storage_location'
        };

        // Set the CSRF field
        data[$('.settings-page').attr('data-csrf')] = $('.settings-page').attr('data-csrf-value');
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/settings', 'POST', data, 'settings_display_storage_location_response');
        
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

        // Load selected storae's location
        Main.settings_get_selected_storage_location();
        
    });

    /*
     * Search for available storage's locations
     * 
     * @since   0.0.8.4
     */
    $(document).on('keyup', '.settings-page .settings-storage-locations-search', function () {
        
        // Get the storage's dropdown items
        Main.settings_get_storage_dropdown_items();
        
    });

    /*
     * Detect when the storage's locations button is clicked
     * 
     * @since   0.0.8.4
     */
    $(document).on('click', '.settings-page .settings-dropdown-btn', function (e) {
        e.preventDefault();

        // Get the storage's dropdown items
        Main.settings_get_storage_dropdown_items();
        
    });

    /*
     * Detect when the storage's location is selected
     * 
     * @since   0.0.8.4
     */
    $(document).on('click', '.settings-page .settings-dropdown-list-ul a', function (e) {
        e.preventDefault();
        
        // Prepare data
        var data = {
            action: 'settings_change_storage_location',
            location: $(this).attr('data-location')
        };

        // Set the CSRF field
        data[$('.settings-page').attr('data-csrf')] = $('.settings-page').attr('data-csrf-value');
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/settings', 'POST', data, 'settings_display_storage_select_response');
        
    });
   
    /*******************************
    RESPONSES
    ********************************/ 
   
    /*
     * Display the response for storage's dropdown
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.4
     */
    Main.methods.settings_display_storage_dropdown_items = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {

            // Items container
            var items = '';

            // List all items
            for ( var d = 0; d < data.locations.length; d++ ) {

                // Add item to the list
                items += '<li class="list-group-item">'
                            + '<a href="#" data-location="' + data.locations[d].location_id + '">'
                                + data.locations[d].location_name
                            + '</a>'
                        + '</li>';

            }

            // Display all items
            $('.settings-page .upload_locations_list').html(items);
            
        } else {

            var message = '<li class="list-group-item no-results-found">'
                + data.message
            + '</li>';

            // Display no contents found
            $('.settings-page .upload_locations_list').html(message);
            
        }

    };

    /*
     * Display the response for storage's dropdown
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.4
     */
    Main.methods.settings_display_storage_select_response = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Set the selected storage's location
            $('.settings-page [data-option="storage_locations"] .btn-secondary').html(data.location.location_name).attr('data-location', data.location.location_id);
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };

    /*
     * Display the storage's location response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.4
     */
    Main.methods.settings_display_storage_location_response = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Set the selected storage's location
            $('.settings-page [data-option="storage_locations"] .btn-secondary').html(data.location.location_name).attr('data-location', data.location.location_id);
            
        } else {
            
            // Set the selected storage's location
            $('.settings-page [data-option="storage_locations"] .btn-secondary').html(data.location_name);            
            
        }

    };
    
    /*******************************
    FORMS
    ********************************/
    
    /*******************************
    DEPENDENCIES
    ********************************/
 
});