<?php

/*******************************************************
 * ACCESS PLUGIN ADMIN AND PUBLIC METHODES FROM INSIDE *
 * --------------------------------------------------- *
 *******************************************************/

/**
 * Instead of this, you could use a "shared" class static or otherwise for
 * shared functions.
 */

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php


/**
 * Store plugin main class to allow public access.
 *
 * @since    20180622
 * @var object      The main class.
 */
public $main;

// ...

public function __construct() {

    // ...

    $this->main = $this;


}

// ...

private function define_admin_hooks() {



    // CHANGE THIS
    $plugin_admin = new Plugin_Name_Admin( $this->get_plugin_name(), $this->get_version() );
    // TO THIS:
    // of course, now you do not have "$plugin_admin", you need to use "$this->plugin_admin" instead.
    $plugin_admin = new Plugin_Name_Admin( $this->get_plugin_name(), $this->get_version(), $this->main );

    // -- OR IF YOU WANT TO ACCESS FROM OUTSIDE TOO, SEE access_plugin_and_its_methodes_later_from_outside_of_plugin.php --
    // $this->admin = new Plugin_Name_Admin( $this->get_plugin_name(), $this->get_version(), $this->main );


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
 * Now you can access to your functions.
 * Example:
 */
////////////////////////////////////////////////
// ADD TO FILE -> public/class-plugin-name-public.php

// ...


// ACCESS FROM ADMIN FROM PUBLIC
public function access_test() {
    $test_var = $this->main->admin->function_from_admin();
}
