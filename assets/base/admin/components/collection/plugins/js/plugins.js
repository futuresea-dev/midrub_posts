/*
 * Plugins JavaScript file
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
     * Unzipping the plugin's zip
     * 
     * @param integer file_name contains the file name
     * 
     * @since   0.0.8.4
     */    
    Main.plugins_unzipping = function (file_name) {

        // Prepare data to send
        var data = {
            action: 'plugins_unzipping_zip',
            file_name: file_name
        };

        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/plugins', 'GET', data, 'plugins_unzipping_zip_response');
        
    };

    /*******************************
    ACTIONS
    ********************************/ 

    /*
     * File change detection
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.4
     */
    $(document).on('change', '#file', function (e) {

        // Upload app
        $('#plugins-upload-plugin').submit();

    });

    /*
     * Activate plugin
     * 
     * @since   0.0.8.4
     */
    $(document).on('click', '.plugins-page .plugins-activate-plugin', function (e) {
        e.preventDefault();
        
        // Get plugin's slug
        var plugin_slug = $(this).closest('.plugin-single').attr('data-slug');

        // Prepare data
        var data = {
            action: 'plugins_activate_plugin',
            plugin_slug: plugin_slug
        };
        
        // Set the CSRF field
        data[$('.plugins-page').attr('data-csrf')] = $('.plugins-page').attr('data-csrf-value');

        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/plugins', 'POST', data, 'plugins_activate_plugin');
        
    }); 
    
    /*
     * Deactivate plugin
     * 
     * @since   0.0.8.4
     */
    $(document).on('click', '.plugins-page .plugins-deactivate-plugin', function (e) {
        e.preventDefault();
        
        // Get plugin's slug
        var plugin_slug = $(this).closest('.plugin-single').attr('data-slug');

        // Prepare data
        var data = {
            action: 'plugins_deactivate_plugin',
            plugin_slug: plugin_slug
        };
        
        // Set the CSRF field
        data[$('.plugins-page').attr('data-csrf')] = $('.plugins-page').attr('data-csrf-value');

        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/plugins', 'POST', data, 'plugins_deactivate_plugin');
        
    });

    /*
     * Select a plugin
     * 
     * @since   0.0.8.4
     */ 
    $( document ).on( 'click', '.plugins-page .plugins-select-plugin', function (e) {
        e.preventDefault();
        
        // Select an app
        $('#file').click();
        
    });
   
    /*******************************
    RESPONSES
    ********************************/

    /*
     * Display plugin activation response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.4
     */
    Main.methods.plugins_activate_plugin = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Refresh page after 2 seconds
            setTimeout(

                function(){

                    // Refresh page
                    document.location.href = document.location.href;

                }, 2000

            );
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };

    /*
     * Display plugin deactivation response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.4
     */
    Main.methods.plugins_deactivate_plugin = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Refresh page after 2 seconds
            setTimeout(

                function() {

                    // Refresh page
                    document.location.href = document.location.href;

                }, 2000

            );
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };

    /*
     * Display unzipping zip response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.4
     */
    Main.methods.plugins_unzipping_zip_response = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {

            // Show the status
            $('#plugins-plugin-installation .progress-bar-striped').attr('aria-valuenow', '100');
            $('#plugins-plugin-installation .progress-bar-striped').css('width', '100%');
            $('#plugins-plugin-installation .progress-bar-striped').text('100%');
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            setTimeout(function() {
                
                // Redirect to plugins page
                document.location.href = url + 'admin/plugins?p=plugins';

            }, 3000);
            
        } else {

            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };
    
    /*******************************
    FORMS
    ********************************/
   
    /*
     * Upload app
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.4
     */
    $('#plugins-upload-plugin').submit(function (e) {
        e.preventDefault();

        // Get the files
        var files = $('#file')[0].files;

        // Verify if files exists
        if ( files.length > 0 ) {
    
            // Split the file's type
            var fileType = files[0].type.split('/');

            // Prepare the formdata
            var form = new FormData();

            // Set path
            form.append('path', '/');

            // Set file
            form.append('file', files[0]);

            // Set type
            form.append('type', fileType[0]);

            // Set enctype
            form.append('enctype', 'multipart/form-data');

            // Set CSRF
            form.append($('#plugins-upload-plugin').attr('data-csrf'), $('input[name="' + $('#plugins-upload-plugin').attr('data-csrf') + '"]').val());
    
            // Set the action
            form.append('action', 'plugins_upload_plugin');

            // Show installation process
            $('#plugins-plugin-installation').modal('show');

            // Upload media
            $.ajax({
                url: url + 'admin/ajax/plugins',
                type: 'POST',
                data: form,
                dataType: 'JSON',
                processData: false,
                contentType: false,
                success: function (data) {

                    // Verify if the success response exists
                    if ( data.success ) {

                        // Show the status
                        $('#plugins-plugin-installation .progress-bar-striped').attr('aria-valuenow', '20');
                        $('#plugins-plugin-installation .progress-bar-striped').css('width', '20%');
                        $('#plugins-plugin-installation .progress-bar-striped').text('20%');
                        
                        // Show message
                        $('#plugins-plugin-installation p').text(data.message);

                        // Unzip
                        Main.plugins_unzipping(data.file_name);
                        
                    } else {

                        // Display alert
                        Main.popup_fon('sube', data.message, 1500, 2000);
                        
                    }

                },
                error: function (jqXHR) {

                    // Hide the modal
                    $('#plugins-plugin-installation').modal('hide');

                }

            });

        }

    });
    
    /*******************************
    DEPENDENCIES
    ********************************/
 
});