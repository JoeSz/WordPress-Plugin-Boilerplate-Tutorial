<?php

/******************************
 * RUN CODE ON PLUGIN UPGRADE *
 * -------------------------- *
 ******************************/

 /**
  * This is useful if you need to display a message or update options, etc...
  */

////////////////////////////////////////////////
// ADD TO FILE -> plugin-name.php

define( 'PLUGIN_NAME_BASE_NAME', plugin_basename( __FILE__ ) );

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_admin_hooks() {

	// ...

	/**
	* This function runs when WordPress completes its upgrade process
	* It iterates through each plugin updated to see if ours is included
	* @param $upgrader_object Array
	* @param $options Array
	*/
	$this->loader->add_action( 'upgrader_process_complete', $plugin_admin, 'upgrader_process_complete', 10, 2 );

	/**
	* Show a notice to anyone who has just updated this plugin
	* This notice shouldn't display to anyone who has just installed the plugin for the first time
	*/
	$this->loader->add_action( 'admin_notices', $plugin_admin, 'display_update_notice' );

	// ...

}

///////////////////////////////////////////////////
// ADD TO FILE -> admin/class-plugin-name-admin.php

    /**
     * This function runs when WordPress completes its upgrade process
     * It iterates through each plugin updated to see if ours is included
     *
     * @param $upgrader_object Array
     * @param $options Array
     * @link https://catapultthemes.com/wordpress-plugin-update-hook-upgrader_process_complete/
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/upgrader_process_complete
     */
    public function upgrader_process_complete( $upgrader_object, $options ) {

        // If an update has taken place and the updated type is plugins and the plugins element exists
        if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {

            // Iterate through the plugins being updated and check if ours is there
            foreach( $options['plugins'] as $plugin ) {
                if( $plugin == PLUGIN_NAME_BASE_NAME ) {

					// Your code here, eg display a message:

                    // Set a transient to record that our plugin has just been updated
					set_transient( $this->plugin_name . '_updated', 1 );
					set_transient( $this->plugin_name . '_updated_message', esc_html__( 'Thanks for updating', 'exopite_sof' ) );

                }
            }
        }
    }

    /**
     * Show a notice to anyone who has just updated this plugin
     * This notice shouldn't display to anyone who has just installed the plugin for the first time
     */
    public function display_update_notice() {

        // Check the transient to see if we've just activated the plugin
        if( get_transient( $this->plugin_name . '_updated' ) ) {

			/**
			 * Display a message.
			 */
            // @link https://digwp.com/2016/05/wordpress-admin-notices/
			echo '<div class="notice notice-success is-dismissible"><p><strong>' . get_transient( 'exopite_sof_updated_message' ) . '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';

            // Delete the transient so we don't keep displaying the activation message
            delete_transient( $this->plugin_name . '_updated' );
            delete_transient( $this->plugin_name . '_updated_message' );
		}

    }