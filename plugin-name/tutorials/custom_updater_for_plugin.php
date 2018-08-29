<?php

/*******************************************************
 * CUSTOM UPDATER FOR PLUGIN *
 * --------------------------------------------------- *
 *******************************************************/

 ///////
// FIRST

// You need a public accessable webspace and IP or URL
// eg. update.yourwebsite.ext
// there copy the files from https://github.com/YahnisElsts/wp-update-server
// then zip your plugin and upload to update.yourwebsite.ext packages folder
//
// This update server will read your plugin php comment and reamde files data
// So all infos comming from there. Like version.
// Eg:
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Plugin_Name
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Plugin Boilerplate Tutorial
 * Plugin URI:        http://example.com/plugin-name-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0                                     <--- IMPORTANT FOR UPDATE
 * Author:            Your Name or Your Company
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-name
 * Domain Path:       /languages
 */
// and the readme file too:
/*
=== Plugin Name ===
Contributors: (this should be a list of wordpress.org userid's)
Donate link: http://example.com/
Tags: comments, spam
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

#OTHER SECTION TO ADD

# This is for the "Important upgrade notice"
== Upgrade Notice ==
= 20180624 =
Important notice to this update

*/

//////////
// SECOND:

// Copy the files from https://github.com/YahnisElsts/plugin-update-checker to /vendor/plugin-update-checker/

 ////////////////////////////////
// ADD TO FILE -> plugin-name.php

// AFTER THIS:
register_activation_hook( __FILE__, 'activate_exopite_seo_core' );
register_deactivation_hook( __FILE__, 'deactivate_exopite_seo_core' );

// ADD TO FILE:
 /**
 * Update
 */
if ( is_admin() ) {

    /**
     * A custom update checker for WordPress plugins.
     *
     * Useful if you don't want to host your project
     * in the official WP repository, but would still like it to support automatic updates.
     * Despite the name, it also works with themes.
     *
     * @link http://w-shadow.com/blog/2011/06/02/automatic-updates-for-commercial-themes/
     * @link https://github.com/YahnisElsts/plugin-update-checker
     * @link https://github.com/YahnisElsts/wp-update-server
     */
    if( ! class_exists( 'Puc_v4_Factory' ) ) {

        require_once join( DIRECTORY_SEPARATOR, array( EXOPITE_SEO_PATH, 'vendor', 'plugin-update-checker', 'plugin-update-checker.php' ) );

    }

    $MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
        'https://update.yourwebsite.ext/?action=get_metadata&slug=plugin-name', //Metadata URL.
        __FILE__, //Full path to the main plugin file.
        'plugin-name' //Plugin slug. Usually it's the same as the name of the directory.
    );

    /**
     * Add plugin upgrade notification
     * https://andidittrich.de/2015/05/howto-upgrade-notice-for-wordpress-plugins.html
     *
     * This version add an extra <p> after the notice.
     * I want that to remove later.
     */
    add_action('in_plugin_update_message-plugin-name/plugin-name.php', 'show_upgrade_notification', 10, 2);
    function show_upgrade_notification( $current_plugin_metadata, $new_plugin_metadata ){
       // check "upgrade_notice"
       if (isset( $new_plugin_metadata->upgrade_notice ) && strlen( trim( $new_plugin_metadata->upgrade_notice ) )  > 0 ) {

            echo '<span style="background-color:#d54e21;padding:6px;color:#f9f9f9;margin-top:10px;display:block;"><strong>' . esc_html( 'Upgrade Notice', 'plugin-name' ) . ':</strong><br>';
            echo esc_html( $new_plugin_metadata->upgrade_notice );
            echo '</span>';

       }
    }

    /* From GitHub direct: */
    // $MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    //     'https://github.com/Username/plugin-name', //Metadata URL.
    //     __FILE__, //Full path to the main plugin file.
    //     'plugin-name' //Plugin slug. Usually it's the same as the name of the directory.
    // );

}
// End Update

/**
 * Then if you have a new version
 * - update plugin php and readme
 *   - add new version
 *   - maybe add Changelog
 *   - maybe add Upgrade Notice
 * - zip and upload to update server packages folder
 *
 * Thats it.
 * Then your update will displayed in WordPress and user can update
 */
