<?php


/******************************
 * ADD OPTIONS PAGE TO PLUGIN *
 * -------------------------- *
 ******************************/

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_admin_hooks() {

    // ...

    // From here added
    // Save/Update our plugin options
    $this->loader->add_action( 'admin_init', $plugin_admin, 'options_update' );

    // Add menu item
    $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

    // Add Settings link to the plugin
    $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );

    $this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

}

///////////////////////////////////////////////////
// ADD TO FILE -> admin/class-plugin-name-admin.php

/**
 * Register the administration menu for this plugin into the WordPress Dashboard menu.
 *
 * @since    1.0.0
 */
public function add_plugin_admin_menu() {

    /**
     * Add a settings page for this plugin to the Settings menu.
     *
     * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
     *
     *        Administration Menus: http://codex.wordpress.org/Administration_Menus
     *
     * add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
     *
     * @link https://codex.wordpress.org/Function_Reference/add_options_page
     *
     * If you want to list plugin options page under a custom post type, then change 'plugin.php' to e.g. 'edit.php?post_type=your_custom_post_type'
     */
    add_submenu_page( 'plugins.php', 'Plugin settings page title', 'Admin area menu slug', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page' ) );

}

/**
 * Add settings action link to the plugins page.
 *
 * @since    1.0.0
 */
public function add_action_links( $links ) {

    /**
     * Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
     * The "plugins.php" must match with the previously added add_submenu_page first option.
     * For custom post type you have to change 'plugins.php?page=' to 'edit.php?post_type=your_custom_post_type&page='
     */
    $settings_link = array( '<a href="' . admin_url( 'plugins.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>', );

    // -- OR --

    // $settings_link = array( '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>', );

    return array_merge(  $settings_link, $links );

}

/**
 * Render the settings page for this plugin.
 *
 * @since    1.0.0
 */
public function display_plugin_setup_page() {

    include_once( 'partials/' . $this->plugin_name . '-admin-display.php' );

}

/**
 * Validate fields from admin area plugin settings form ('exopite-lazy-load-xt-admin-display.php')
 * @param  mixed $input as field form settings form
 * @return mixed as validated fields
 */
public function validate($input) {

    // $valid = array();

    // $valid['example_checkbox'] = ( isset( $input['example_checkbox'] ) && ! empty( $input['example_checkbox'] ) ) ? 1 : 0;
    // $valid['example_text'] = ( isset( $input['example_text'] ) && ! empty( $input['example_text'] ) ) ? esc_attr( $input['example_text'] ) : 'default';
    // $example_textarea['example_textarea'] = ( isset( $input['example_textarea'] ) && ! empty( $input['example_textarea'] ) ) ? sanitize_textarea_field( $input['example_textarea'] ) : 'default';
    // $valid['example_select'] = ( isset($input['example_select'] ) && ! empty( $input['example_select'] ) ) ? esc_attr($input['example_select']) : 1;

    // return $valid;

    // -- OR --

    $options = get_option( $this->plugin_name );

    $options['example_checkbox'] = ( isset( $input['example_checkbox'] ) && ! empty( $input['example_checkbox'] ) ) ? 1 : 0;
    $options['example_text'] = ( isset( $input['example_text'] ) && ! empty( $input['example_text'] ) ) ? esc_attr( $input['example_text'] ) : 'default';
    $options['example_textarea'] = ( isset( $input['example_textarea'] ) && ! empty( $input['example_textarea'] ) ) ? sanitize_textarea_field( $input['example_textarea'] ) : 'default';
    $options['example_select'] = ( isset($input['example_select'] ) && ! empty( $input['example_select'] ) ) ? esc_attr($input['example_select']) : 1;

    return $options;

}

public function options_update() {

    register_setting( $this->plugin_name, $this->plugin_name, array(
       'sanitize_callback' => array( $this, 'validate' ),
    ) );

}

////////////////////////////////////////////////////////
// ADD TO FILE -> partials/plugin-name-admin-display.php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h2>Plugin Name <?php esc_attr_e('Options', 'plugin_name' ); ?></h2>

    <form method="post" name="<?php echo $this->plugin_name; ?>" action="options.php">
    <?php
        //Grab all options
        $options = get_option( $this->plugin_name );

        $example_select = ( isset( $options['example_select'] ) && ! empty( $options['example_select'] ) ) ? esc_attr( $options['example_select'] ) : '1';
        $example_text = ( isset( $options['example_text'] ) && ! empty( $options['example_text'] ) ) ? esc_attr( $options['example_text'] ) : 'default';
        $example_textarea = ( isset( $options['example_textarea'] ) && ! empty( $options['example_textarea'] ) ) ? sanitize_textarea_field( $options['example_textarea'] ) : 'default';
        $example_checkbox = ( isset( $options['example_checkbox'] ) && ! empty( $options['example_checkbox'] ) ) ? 1 : 0;

        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);

    ?>

    <!-- Select -->
    <fieldset>
        <p><?php esc_attr_e( 'Example Select.', 'plugin_name' ); ?></p>
        <legend class="screen-reader-text">
            <span><?php esc_attr_e( 'Example Select', 'plugin_name' ); ?></span>
        </legend>
        <label for="example_select">
            <select name="<?php echo $this->plugin_name; ?>[example_select]" id="<?php echo $this->plugin_name; ?>-example_select">
                <option <?php if ( $example_select == 'first' ) echo 'selected="selected"'; ?> value="first">First</option>
                <option <?php if ( $example_select == 'second' ) echo 'selected="selected"'; ?> value="second">Second</option>
            </select>
        </label>
    </fieldset>

    <!-- Text -->
    <fieldset>
        <p><?php esc_attr_e( 'Example Text.', 'plugin_name' ); ?></p>
        <legend class="screen-reader-text">
            <span><?php esc_attr_e( 'Example Text', 'plugin_name' ); ?></span>
        </legend>
        <input type="text" class="example_text" id="<?php echo $this->plugin_name; ?>-example_text" name="<?php echo $this->plugin_name; ?>[example_text]" value="<?php if( ! empty( $example_text ) ) echo $example_text; else echo 'default'; ?>"/>
    </fieldset>

    <!-- Textarea -->
    <fieldset>
        <p><?php esc_attr_e( 'Example Text.', 'plugin_name' ); ?></p>
        <legend class="screen-reader-text">
            <span><?php esc_attr_e( 'Example Text', 'plugin_name' ); ?></span>
        </legend>
        <textarea class="example_textarea" id="<?php echo $this->plugin_name; ?>-example_textarea" name="<?php echo $this->plugin_name; ?>[example_textarea]" rows="4" cols="50">
            <?php if( ! empty( $example_textarea ) ) echo $example_text; else echo 'default'; ?>
        </textarea>
    </fieldset>

    <!-- Checkbox -->
    <fieldset>
        <p><?php esc_attr_e( 'Example Checkbox.', 'plugin_name' ); ?></p>
        <legend class="example-Checkbox">
            <span><?php esc_attr_e( 'Example Checkbox', 'plugin_name' ); ?></span>
        </legend>
        <label for="<?php echo $this->plugin_name; ?>-example_checkbox">
            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-example_checkbox" name="<?php echo $this->plugin_name; ?>[example_checkbox]" value="1" <?php checked( $example_checkbox, 1 ); ?> />
            <span><?php esc_attr_e('Example Checkbox', 'plugin_name' ); ?></span>
        </label>
    </fieldset>

    <?php submit_button( __( 'Save all changes', 'plugin_name' ), 'primary','submit', TRUE ); ?>
    </form>
</div>
