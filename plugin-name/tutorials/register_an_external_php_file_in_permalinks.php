<?php

/***********************************************
 * REGISTER AN EXTERNAL PHP FILE IN PERMALINKS *
 * ------------------------------------------- *
 ***********************************************/

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_public_hooks() {

    // ...

    /**
     * Create a redirecton to a custom php file
     *
     * Use: <website>/?<plugin_name>=my_value&[bla=bla]
     *
     * @link http://wordpress.stackexchange.com/a/38990/90212
     * @link http://wordpress.stackexchange.com/questions/81850/external-rewrite-rules
     * @link http://stackoverflow.com/questions/25310665/wordpress-how-to-create-a-rewrite-rule-for-a-file-in-a-custom-plugin
     * @link https://premium.wpmudev.org/blog/building-customized-urls-wordpress/?nhtz=b&utm_expid=3606929-91.15T0nlf8TFCqo1W_BlZjGg.1&utm_referrer=https%3A%2F%2Fwww.google.de%2F
     */
    // add blackout to the whitelist of variables it wordpress knows and allows
    $this->loader->add_filter( 'query_vars', $plugin_public, 'whitelist_query_variable' );

    // If this is done, we can access it later
    // This example checks very early in the process:
    // if the variable is set, we include our page and stop execution after it
    $this->loader->add_action( 'parse_request', $plugin_public, 'redirect_to_file' );

}

/////////////////////////////////////////////////////
// ADD TO FILE -> public/class-plugin-name-public.php

// add blackout to the whitelist of variables it wordpress knows and allows
// in this case it is the plugin name
public function whitelist_query_variable( $query_vars ) {

    $query_vars[] = $this->plugin_name;
    return $query_vars;

}

// If this is done, we can access it later
// This example checks very early in the process:
// if the variable is set, we include our page and stop execution after it
public function redirect_to_file( &$wp ){

    if ( array_key_exists( $this->plugin_name, $wp->query_vars ) ) {

        switch ( $wp->query_vars[$this->plugin_name] ) {

            case 'my_value':
                include( WP_PLUGIN_DIR . '/' . $this->plugin_name . '/templates/custom-php-file.php' );
                break;

            // ...

        }

        exit();

    }
}
