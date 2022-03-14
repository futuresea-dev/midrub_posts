/*
 * Main javascript file
*/
jQuery(document).ready( function ($) {
    'use strict';
    
    /*******************************
    METHODS
    ********************************/

    /*
     * Display alert
     */
    Main.popup_fon = function( cl, msg, ft, lt ) {

        // Add message
        $('<div class="md-message ' + cl + '"><i class="icon-bell"></i> ' + msg + '</div>').insertAfter('section');

        // Display alert
        setTimeout(function () {

            $( document ).find( '.md-message' ).animate({opacity: '0'}, 500);

        }, ft);

        // Hide alert
        setTimeout(function () {

            $( document ).find( '.md-message' ).remove();

        }, lt);

    };
  
    /*******************************
    ACTIONS
    ********************************/
   
    /*******************************
    RESPONSES
    ********************************/ 
    
    /*******************************
    FORMS
    ********************************/
    
    /*******************************
    DEPENDENCIES
    ********************************/

    // Hide the loading page animation
    setTimeout(function(){
        $('.page-loading').fadeOut('slow');
    }, 600);

});