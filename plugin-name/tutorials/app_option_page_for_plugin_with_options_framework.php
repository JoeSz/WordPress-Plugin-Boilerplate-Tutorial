<?php


/*********************************************
 * ADD OPTIONS PAGE TO PLUGIN WITH FRAMEWORK *
 * ----------------------------------------- *
 *********************************************/

/**
 * Copy exopite-simple-options content folder from
 * https://github.com/JoeSz/Exopite-Simple-Options-Framework
 * to admin/exopite-simple-options.
 */


////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function load_dependencies() {

    // ...

    /**
     * Exopite Simple Options Framework
     *
     * @link https://github.com/JoeSz/Exopite-Simple-Options-Framework
     * @author Joe Szalai
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/exopite-simple-options/exopite-simple-options-framework-class.php';

    // ...

    $this->loader = new Plugin_Name_Loader();

}


private function define_admin_hooks() {

    // ...

    // Save/Update our plugin options
    $this->loader->add_action( 'init', $plugin_admin, 'create_menu', 999 );

    // ...

}

///////////////////////////////////////////////////
// ADD TO FILE -> admin/class-plugin-name-admin.php
public function create_menu() {

    /*
    * Create a submenu page under Plugins.
    * Framework also add "Settings" to your plugin in plugins list.
    */
    $config_submenu = array(

        'type'              => 'menu',                          // Required, menu or metabox
        'id'                => $this->plugin_name . '-test',    // Required, meta box id, unique per page, to save: get_option( id )
        'parent'            => 'plugins.php',                   // Required, sub page to your options page
        // 'parent'            => 'edit.php?post_type=your_post_type',
        'submenu'           => true,                            // Required for submenu
        'title'             => esc_html__( 'Demo Admin Page', 'plugin-name' ),    //The name of this page
        'capability'        => 'manage_options',                // The capability needed to view the page
        'plugin_basename'   => plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' ),
        // 'tabbed'            => false,

    );

    //
    // - OR --
    // eg: if Yoast SEO is active, then add submenu under Yoast SEO admin menu,
    // if not then under Plugins admin menu:
    //

    if ( ! function_exists( 'is_plugin_active' ) ) require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

    $parent = ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) ? 'wpseo_dashboard' : 'plugins.php';
    $settings_link = ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) ? 'admin.php?page=plugin-name' : 'plugins.php?page=plugin-name';

    $config_submenu = array(

        'type'              => 'menu',                                          // Required, menu or metabox
        'id'                => $this->plugin_name,                              // Required, meta box id, unique per page, to save: get_option( id )
        'menu'              => $parent,                                         // Required, sub page to your options page
        'submenu'           => true,                                            // Required for submenu
        'settings-link'     => $settings_link,
        'title'             => esc_html__( 'Demo Admin Page', 'plugin-name' ),    //The name of this page
        'capability'        => 'manage_options',                                // The capability needed to view the page
        'plugin_basename'   => plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' ),
        'tabbed'            => true,

    );

    $fields[] = array(
        'name'   => 'first',
        'title'  => 'First',
        'icon'   => 'dashicons-admin-generic',
        'fields' => array(

            /**
             * Available fields:
             * - ACE field
             * - attached
             * - backup
             * - button
             * - botton_bar
             * - card
             * - checkbox
             * - color
             * - content
             * - date
             * - editor
             * - group
             * - hidden
             * - image
             * - image_select
             * - meta
             * - notice
             * - number
             * - password
             * - radio
             * - range
             * - select
             * - switcher
             * - tap_list
             * - text
             * - textarea
             * - upload
             * - video mp4/oembed
             *
             * Add your fields, eg.:
             */

            array(
                'id'          => 'text_1',
                'type'        => 'text',
                'title'       => 'Text',
                'before'      => 'Text Before',
                'after'       => 'Text After',
                'class'       => 'text-class',
                'attributes'  => 'data-test="test"',
                'description' => 'Description',
                'default'     => 'Default Text',
                'attributes'    => array(
                    'rows'        => 10,
                    'cols'        => 5,
                    'placeholder' => 'do stuff',
                ),
                'help'        => 'Help text',

            ),

            array(
                'id'     => 'password_1',
                'type'   => 'password',
                'title'  => 'Password',
            ),


            array(
                'id'     => 'color_1',
                'type'   => 'color',
                'title'  => 'Color',
            ),

            array(
                'id'     => 'color_1',
                'type'   => 'color',
                'title'  => 'Color',
                'rgba'   => true,
            ),

            array(
                'id'     => 'color_2',
                'type'   => 'color',
                'title'  => 'Color',
                'picker' => 'html5',
            ),

            array(
                'id'    => 'image_1',
                'type'  => 'image',
                'title' => 'Image',
            ),

            array(
                'id'          => 'textarea_1',
                'type'        => 'textarea',
                'title'       => 'Textarea',
                'help'        => 'This option field is useful. &quot;You&quot; will love it! This option field is useful. You will love it!',
                'attributes'    => array(
                    'placeholder' => 'do stuff',
                ),
            ),
        ),
    );

    /**
     * Second Tab
     */
    $fields[] = array(
        'name'   => 'second',
        'title'  => 'Second',
        'icon'   => 'dashicons-portfolio',
        'fields' => array(

            array(
                'type'    => 'content_second',
                'content' => 'Second Section',

            ),

        )
    );

    /**
     * Third Tab
     */
    $fields[] = array(
        'name'   => 'third',
        'title'  => 'Third',
        'icon'   => 'dashicons-portfolio',
        'fields' => array(

            array(
                'type'    => 'content_third',
                'content' => 'Third Section',

            ),

        )
    );

    /**
     * instantiate your admin page
     */
    $options_panel = new Exopite_Simple_Options_Framework( $config_submenu, $fields );

}
