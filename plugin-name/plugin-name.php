<?php

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
 * Version:           1.0.0
 * Author:            Your Name or Your Company
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-name
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_PLUGIN_NAME', 'plugin-name' );
define( 'PLUGIN_NAME_BASE_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Initialize custom templater
 */
if( ! class_exists( 'Exopite_Template' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/libraries/class-exopite-template.php';
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-activator.php';
	Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/*
 * Update
 */
if ( is_admin() ) {

	/**
	 * A custom update checker for WordPress plugins.
	 *
	 * How to use:
	 * - Copy vendor/plugin-update-checker to your plugin OR
	 *   Download https://github.com/YahnisElsts/plugin-update-checker to the folder
	 * - Create a subdomain or a folder for the update server eg. https://updates.example.net
	 *   Download https://github.com/YahnisElsts/wp-update-server and copy to the subdomain or folder
	 * - Add plguin zip to the 'packages' folder
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

		require_once join( DIRECTORY_SEPARATOR, array( PLUGIN_NAME_BASE_DIR, 'vendor', 'plugin-update-checker', 'plugin-update-checker.php' ) );

	}

	$MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
		'https://updates.example.net/?action=get_metadata&slug=' . PLUGIN_NAME_PLUGIN_NAME, //Metadata URL.
		__FILE__, //Full path to the main plugin file.
		PLUGIN_NAME_PLUGIN_NAME //Plugin slug. Usually it's the same as the name of the directory.
	);

	/**
	 * add plugin upgrade notification
	 * https://andidittrich.de/2015/05/howto-upgrade-notice-for-wordpress-plugins.html
	 */
	add_action( 'in_plugin_update_message-' . PLUGIN_NAME_PLUGIN_NAME . '/' . PLUGIN_NAME_PLUGIN_NAME .'.php', 'plugin_name_show_upgrade_notification', 10, 2 );
	function plugin_name_show_upgrade_notification( $current_plugin_metadata, $new_plugin_metadata ) {

		/**
		 * Check "upgrade_notice" in readme.txt.
		 *
		 * Eg.:
		 * == Upgrade Notice ==
		 * = 20180624 = <- new version
		 * Notice		<- message
		 *
		 */
		if ( isset( $new_plugin_metadata->upgrade_notice ) && strlen( trim( $new_plugin_metadata->upgrade_notice ) ) > 0 ) {

			// Display "upgrade_notice".
			echo sprintf( '<div style="background-color:#d54e21;padding:10px;color:#f9f9f9;margin-top:10px"><strong>%1$s: </strong>%2$s</div>', esc_attr( 'Important Upgrade Notice', 'terminplaner' ), esc_html( $new_plugin_metadata->upgrade_notice ) );

		}
	}


}
// End Update

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

	$plugin = new Plugin_Name();
	$plugin->run();

}
run_plugin_name();
