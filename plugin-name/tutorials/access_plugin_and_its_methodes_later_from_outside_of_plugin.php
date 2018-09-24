<?php

/*******************************************************************
 * ACCESS PLUGIN AND ITS METHODES LATER FROM OUTSIDE OF THE PLUGIN *
 * --------------------------------------------------------------- *
 *******************************************************************/

////////////////////////////////////////////////
// CHANGE IN FILE -> plugin-name.php
/**
 * This allow you to access your plugin class eg. in your template.
 *
 * Of course you do not need to use a global,
 * you could wrap it in singleton too,
 * or you can store it in a static class,
 * etc...
 */
// THIS:
function run_plugin_name() {

	$plugin = new Plugin_Name();
	$plugin->run();

}
run_plugin_name();

// TO:

global $prefix_plugin_name;
$prefix_plugin_name = new Plugin_Name();
$prefix_plugin_name->run();

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

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

/**
 * Register all of the hooks related to the admin area functionality
 * of the plugin.
 *
 * @since    1.0.0
 * @access   private
 */
private function define_admin_hooks() {

    // CHANGE THIS
    $plugin_admin = new Plugin_Name_Admin( $this->get_plugin_name(), $this->get_version() );
    // TO THIS:
    // of course, now you do not have "$plugin_admin", you need to use "$this->plugin_admin" instead.
    $this->admin = new Plugin_Name_Admin( $this->get_plugin_name(), $this->get_version() );

    // SO THIS:
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
    // BECOME THIS:
    $this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_styles' );
    // AND SO ON...

// Same with public function

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
    // of course, now you do not have "$plugin_public", you need to use "$this->plugin_public" instead.
    $this->public = new Plugin_Name_Public( $this->get_plugin_name(), $this->get_version() );
    // AND SO ON...

/**
 * Now you can access to your functions.
 * Example:
 */
////////////////////////////////////////////////
// ADD TO FILE -> public/class-plugin-name-public.php

// ...

public function access_test( $test_var ) {
    return $test_var;
}

// AND IN YOUR THEME OR TEMPLATE FILE:
global $prefix_plugin_name;
echo $prefix_plugin_name->public->access_test( $test_var );
