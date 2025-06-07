<?php

/************************************************************
 * ACCESS PLUGIN AND METHODS FROM INSIDE/OUTSIDE OF PLUGIN *
 * -------------------------------------------------------- *
 ************************************************************/


////////////////////////////////////////////////
// COPY -> plugin_registry.php to your plugin root

////////////////////////////////////////////////
// CHANGE IN FILE -> plugin-name.php
/**
 * This allow you to access your plugin class eg. in your template.
 *
 * Of course you do not need to use a global,
 * you could wrap it in singleton too,
 * or you can store it in a static class,
 * etc...
 *
 * This is the Singleton verion.
 *
 * The version with the use of global see in:
 * access_plugin_and_its_methods_later_from_outside_of_plugin.php
 */
// THIS:
function run_plugin_name() {

	$plugin = new Plugin_Name();
	$plugin->run();

}
run_plugin_name();

// TO:

require_once( 'plugin_registry.php' );
$registry = Plugin_Registry::get_instance();
$registry->add( 'plugin_name', new Plugin_Name() );
$registry->get( 'plugin_name' )->run();

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php


/**
 * Store plugin main class to allow public access.
 *
 * @since    20180622
 * @var object      The main class.
 */
public $main;

/**
 * Store plugin admin class to allow public access.
 *
 * @since    20180622
 * @var object      The admin class.
 */
public $admin;

/**
 * Store plugin public class to allow public access.
 *
 * @since    20180622
 * @var object      The admin class.
 */
public $public;

// ...

public function __construct() {

    // Save instance for the main class.
    $this->main = $this;

    // ...

}

// ...

private function define_admin_hooks() {

    // CHANGE THIS
    $plugin_admin = new Plugin_Name_Admin( $this->get_plugin_name(), $this->get_version() );
    // TO THIS:
    // of course, now you do not have "$plugin_admin", you need to use "$this->admin" instead.
    $this->admin = new Plugin_Name_Admin( $this->get_plugin_name(), $this->get_version(), $this->main );

    // ...

}

/**
 * Register all of the hooks related to the public-facing functionality
 * of the plugin.
 *
 * @since    1.0.0
 * @access   private
 */
private function define_public_hooks() {

    // CHANGE THIS
    $plugin_public = new Plugin_Name_Public( $this->get_plugin_name(), $this->get_version() );
    // TO THIS:
    $this->public = new Plugin_Name_Public( $this->get_plugin_name(), $this->get_version(), $this->main );

    // ...

}

////////////////////////////////////////////////
// ADD TO FILE -> admin/class-plugin-name-admin.php
// -- AND --
// ADD TO FILE -> public/class-plugin-name-public.php

/**
 * Store plugin main class to allow public access.
 *
 * @since    20180622
 * @var object      The main class.
 */
public $main;

public function __construct( $plugin_name, $version, $plugin_main ) {

    $this->main = $plugin_main;

    // ...

}

/**
 * Now you can access to your plugin/functions.
 * Example:
 */
////////////////////////////////////////////////
// ADD TO FILE -> anywhere after "plugins_loaded"
// @link: http://rachievee.com/the-wordpress-hooks-firing-sequence/

// ...

// ACCESS FROM ADMIN FROM PUBLIC
function access_test() {

    $registry = Plugin_Registry::get_instance();
    $my_plugin = $registry->get( 'my_plugin' );

    $test_function_from_main = $my_plugin->your_function_from_main();
    // - OR -
    $test_function_from_main = $registry->get( 'my_plugin' )->your_function_from_main();

    // - MORE EXAPLES -
    $test_function_from_admin = $my_plugin->admin->your_function_from_admin();
    $test_function_from_plugic = $my_plugin->public->your_function_from_public();

}

// ...
