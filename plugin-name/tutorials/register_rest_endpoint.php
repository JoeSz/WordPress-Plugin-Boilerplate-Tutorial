<?php

/**************************
 * REGISTER REST ENDPOINT *
 * ---------------------- *
 **************************/

/**
 * Good articles about the theme:
 * - https://developer.wordpress.org/reference/functions/register_rest_route/
 * - https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 * - https://developer.wordpress.org/rest-api/extending-the-rest-api/routes-and-endpoints/
 */

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_public_hooks() { // or in define_admin_hooks() ...

    $this->loader->add_action( 'rest_api_init', $plugin_public, 'register_custom_endpoint' );

}

/////////////////////////////////////////////////////
// ADD TO FILE -> public/class-plugin-name-public.php

/**
 * Register the custom endpoint
 * then we can call it:
 * http://www.example.org/wp-json/custom-api/v1/custom-endpoint/
 */
public function register_custom_endpoint() {

    register_rest_route( 'custom-api/v1', '/custom-endpoint/', array(
        'methods' => 'GET',
        'callback' => array( $this, 'custom_api_endpoint_callback' ),
    ));

}

// Custom API endpoint callback function
public function custom_api_endpoint_callback() {

    // Write the custom API endpoint logic here
    // For example:
    $data = array(
        'status' => 'success',
        'message' => 'Custom API endpoint successfully called.'
    );

    wp_send_json($data);
}
