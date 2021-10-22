<?php

/**************************
 * REGISTER AJAX CALLBACK *
 * ---------------------- *
 **************************/

/**
 * Good articles about the theme:
 * - https://developer.wordpress.org/plugins/javascript/ajax/
 * - https://premium.wpmudev.org/blog/using-ajax-with-wordpress/
 */

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_public_hooks() {

    // ...

    /**
     * The wp_ajax_ is telling wordpress to use ajax and the prefix_ajax_first is the hook name to use in JavaScript or in URL.
     *
     * Call AJAX function via URL: https://www.yourwebsite.com/wp-admin/admin-ajax.php?action=prefix_ajax_first&post_id=23&other_param=something
     *
     * The ajax_first is the callback function.
     * wp_ajax_ is for authenticated users
     * wp_ajax_nopriv_ is for NOT authenticated users
     */
    $this->loader->add_action('wp_ajax_prefix_ajax_first', $plugin_public, 'ajax_first');
    $this->loader->add_action('wp_ajax_nopriv_prefix_ajax_first', $plugin_public, 'ajax_first');

}

/////////////////////////////////////////////////////
// ADD TO FILE -> public/class-plugin-name-public.php

/*
If you do not want this to do, you could also add a parameter to your button or wrapper,
if you active the AJAX with a button click or so.
eg:
<input type="button" name="some-name" id="some-id" class="button button-primary" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>" value="Click for AJAX">
JS:
$( '#some-id' ).on( 'click', function(){
    var ajax_url = $( this ).data( 'ajaxurl' );

    // ...
});


*/

public function enqueue_scripts() {

    // ...

    /**
     *  In backend there is global ajaxurl variable defined by WordPress itself.
     *
     * This variable is not created by WP in frontend. It means that if you want to use AJAX calls in frontend, then you have to define such variable by yourself.
     * Good way to do this is to use wp_localize_script.
     *
     * @link http://wordpress.stackexchange.com/a/190299/90212
     *
     * You could also pass this datas with the "data" attribute somewhere in your form.
     */
    wp_localize_script( $this->plugin_name, 'wp_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        /**
         * Create nonce for security.
         *
         * @link https://codex.wordpress.org/Function_Reference/wp_create_nonce
         */
        '_nonce' => wp_create_nonce( 'any_value_here' ),

    ) );


}

/////////////////////////////////////////////////////
// ADD TO FILE -> public/class-plugin-name-public.php

// Callback function
public function ajax_first() {

    /**
     * Do not forget to check your nonce for security!
     *
     * @link https://codex.wordpress.org/Function_Reference/wp_verify_nonce
     */
    if( ! wp_verify_nonce( $_POST['_nonce'], 'any_value_here' ) ) {
        wp_send_json_error();
        die();
    }
    /**
     * OR you can use check_ajax_referer
     *
     * @link https://codex.wordpress.org/Function_Reference/check_ajax_referer
     * @link https://tommcfarlin.com/secure-ajax-requests-in-wordpress/
     * @link https://wordpress.stackexchange.com/questions/48110/wp-verify-nonce-vs-check-admin-referer
     */
    if ( ! check_ajax_referer( 'any_value_here', '_nonce', false ) ) {
        wp_send_json_error( 'Invalid security token sent.' );
        die();
    }

  // The rest of the function that does actual work.

    $ret = array();

    /**
     * Code to handle POST request...
     *
     * See tutorial file:
     * handling_POST_request.php
     */

    // Eg.: get POST value
    $function = sanitize_text_field( $_POST["function"] );

    // Eg.: custom Loop for Custom Post Type
    $args = array(
        'post_type' => 'your_post_type',
        'posts_per_page' => '-1', // for all of them
    );

    $loop = new WP_Query( $args );

    while( $loop->have_posts() ): $loop->the_post();
        $ret['meta_field'] = get_post_meta( $post_id, 'custom_meta_id', true );
        $ret['post_id']    = get_the_ID();
    endwhile;

    wp_reset_query();

    die( json_encode( $ret ) );

}

/////////////////////////////////////////////////
// ADD TO FILE -> public/js/plugin-name-public.js

// ...

$(function() {

    // ...

    var dataJSON = {
        'action': 'prefix_ajax_first',
        'whatever': 1234,
        'nonce': 'nonce-key',
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
        beforeSend: function() {
            $('.some-class').addClass('loading');
        },
        success: function( response ){
            // on success
            // code...
        },
        error: function( xhr, status, error ) {
            console.log( 'Status: ' + xhr.status );
            console.log( 'Error: ' + xhr.responseText );
        },
        complete: function() {
            $('.some-class').removeClass('loading');
        }
    });

});

// ...

/////////////////////////////////////////////////////
// OR more modern and handeling file upload as well.
//
// Required modern browser, for more information on compatibility, please check:
// https://developer.mozilla.org/en-US/docs/Web/API/FormData
// https://caniuse.com/#search=FormData
//
// BE CAREFUL WITH OLDER SAFARI/iOS DEVICES!
//
// For file upload use must declair enctype="multipart/form-data"
// eg.: <form enctype="multipart/form-data" method="post" name="primaryPostForm" id="primaryPostForm">

// ...

$(function() {

    // On form submit
    $( '#ajax' ).on( 'submit', '#primaryPostForm', function( event ) {
        event.preventDefault();

        // Validate form (optional)
        $( this ).validate();

        var form = document.getElementById('primaryPostForm');

        var formData = new FormData( form );

        // From the wp_ajax_prefix_ajax_first hook
        formData.append( 'action', 'prefix_ajax_first' );

        $.ajax({
            cache: false,
            type: "POST",
            url: wp_ajax.ajax_url,
            data: formData,
            processData: false, // Required for file upload
            contentType: false, // Required for file upload
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

}

// ...
