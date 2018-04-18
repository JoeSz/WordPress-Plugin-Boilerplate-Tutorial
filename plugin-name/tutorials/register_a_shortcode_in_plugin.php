<?php

/********************************
 * REGISTER SHORTCODE IN PLUGIN *
 * ---------------------------- *
 ********************************/

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name-loader.php

/**
 * The array of shortcodes registered with WordPress.
 *
 * @since    1.0.0
 * @access   protected
 * @var      array    $shortcodes    The shortcodes registered with WordPress to fire when the plugin loads.
 */
protected $shortcodes;

/**
 * Initialize the collections used to maintain the actions and filters.
 *
 * @since    1.0.0
 */
public function __construct() {

    // ...

    $this->shortcodes = array();

}

/**
 * Add a new shortcode to the collection to be registered with WordPress
 *
 * @since     1.0.0
 * @param     string        $tag           The name of the new shortcode.
 * @param     object        $component      A reference to the instance of the object on which the shortcode is defined.
 * @param     string        $callback       The name of the function that defines the shortcode.
 */
public function add_shortcode( $tag, $component, $callback, $priority = 10, $accepted_args = 2 ) {
    $this->shortcodes = $this->add( $this->shortcodes, $tag, $component, $callback, $priority, $accepted_args );
}

/**
 * Register the filters and actions with WordPress.
 *
 * @since    1.0.0
 */
public function run() {

    // ...

    foreach ( $this->shortcodes as $hook ) {
        add_shortcode(  $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
    }

}

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_public_hooks() {

    // ...

    /**
     * Register shortcode via loader
     *
     * Use: [short-code-name args]
     *
     * @link https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/issues/262
     */
    $this->loader->add_shortcode( "shortcode-name", $plugin_public, "shortcode_function", $priority = 10, $accepted_args = 2 );
}

/////////////////////////////////////////////////////
// ADD TO FILE -> public/class-plugin-name-public.php

function shortcode_function( $atts ) {

    $args = shortcode_atts(
        array(
            'arg1'   => 'arg1',
            'arg2'   => 'arg2',
        ),
        $atts
    );

    // code...

    $var = ( strtolower( $args['arg1']) != "" ) ? strtolower( $args['arg1'] ) : 'default';

    // code...

    return $var;
}
