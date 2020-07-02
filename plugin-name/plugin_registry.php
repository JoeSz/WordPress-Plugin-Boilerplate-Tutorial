<?php
/**
 * Class to save plugin instances for later use.
 *
 * Idea: https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/issues/217#issuecomment-55960466
 * For "Tom McFarlin"case, it is integrated inside in the plugin, but in my case (for working with multiple plugins)
 * it is better to use as a standalone class.
 *
 * How to use:
 * - Set (with plugin boilerplate)
 *   require_once( 'plugin_registry.php' );
 *   $registry = Plugin_Registry::get_instance();
 *   $registry->add( 'exopite_password_policy', new Exopite_Password_Policy() );
 *   $registry->get( 'exopite_password_policy' )->run();
 *
 * - Get
 *   $registry = Plugin_Registry::get_instance();
 *   $my_plugin = $registry->get( 'my_plugin' );
 */

if ( ! class_exists( 'Plugin_Registry' ) ) :

class Plugin_Registry {

    private static $instances;

    private static $instance = null;

    private function __construct() {
        self::$instances = array();
    }

    public static function get_instance() {

        if ( null === self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;

    }

    public function add( $key, $plugin_instance ) {

        if ( ! isset( self::$instances[ $key ] ) ) {
            self::$instances[ $key ] = $plugin_instance;
        }

    }

    public function get( $key ) {
        return self::$instances[ $key ];
    }

    public function remove( $key ) {

        if ( ! isset( self::$instances[ $key ] ) ) {
            unset( self::$instances[ $key ] );
        }

    }

    public function has( $key ) {
        return ( isset( self::$instances[ $key ] ) );
    }

}

endif;
