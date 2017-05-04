<?php

/**************************
 * REGISTER AJAX CALLBACK *
 * ---------------------- *
 **************************/

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_public_hooks() {

    // ...

    // The wp_ajax_ is telling wordpress to use ajax and the prefix_ajax_first is the hook name to use in JavaScript.
    // The ajax_first is the callback function.
    $this->loader->add_action('wp_ajax_prefix_ajax_first', $plugin_public, 'ajax_first');

}

/////////////////////////////////////////////////////
// ADD TO FILE -> public/class-plugin-name-public.php

public function enqueue_scripts() {

    // ...

    /**
     *  In backend there is global ajaxurl variable defined by WordPress itself.
     *
     * This variable is not created by WP in frontend. It means that if you want to use AJAX calls in frontend, then you have to define such variable by yourself.
     * Good way to do this is to use wp_localize_script.
     *
     * @link http://wordpress.stackexchange.com/a/190299/90212
     */
    wp_localize_script( $this->plugin_name, 'wp_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

}

// Callback function
public function ajax_first() {

    $ret = '';

    // code...

    die( $ret );

}

/////////////////////////////////////////////////////
// ADD TO FILE -> public/js/plugin-name-public.js

$(function() {

    // ...

    var dataJSON = {
        'action': 'prefix_ajax_first',
        'whatever': 1234
    };

    // Or get from HTML element:

        // < ... id="selector" data-value="key=value&key2=value2[...]" ... >
        var dataItem = $( '#selector' ).data( 'value' );

        // Convert to JSON array
        var dataJSON = dataItem?JSON.parse( '{"' + dataItem.replace( /&/g, '","' ).replace( /=/g,'":"' ) + '"}', function( key, value ) {
            return key===""?value:decodeURIComponent(value);
        }):{};

    // From the wp_ajax_prefix_ajax_first hook
    dataJSON["action"] ='prefix_ajax_first';

    $.ajax({
        cache: false,
        type: "POST",
        url: wp_ajax.ajax_url,
        data: dataJSON,
        success: function( response ){
            // on success
            // code...
        },
        error: function( xhr, status, error ) {
            console.log( 'Status: ' + xhr.status );
            console.log( 'Error: ' + xhr.responseText );
        }
    });

});
