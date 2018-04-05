<?php


/********************************************************************************************
 * REGISTER METABOX FOR A CUSTOM POST TYPE WITH OPTIONS FRAMEWORK ("test" custom post type) *
 * ---------------------------------------------------------------------------------------- *
 ********************************************************************************************/

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
    * To add a metabox.
    * This normally go to your functions.php or another hook
    */
    $config_metabox = array(

        /*
        * METABOX
        */
        'type'              => 'metabox',                       // Required, menu or metabox
        'id'                => $this->plugin_name . '-meta',    // Required, meta box id, unique, for saving meta: id[field-id]
        // 'post_types'        => array( 'page' ),                 // Post types to display meta box
        'post_types'        => array( 'test', 'post', 'page' ),         // Post types to display meta box
        'context'           => 'advanced',
        'priority'          => 'default',
        'title'             => 'Demo Metabox',                  // The name of this page
        'capability'        => 'edit_posts',                    // The capability needed to view the page
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
    $metabox_panel = new Exopite_Simple_Options_Framework( $config_metabox, $fields );

}