<?php

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
