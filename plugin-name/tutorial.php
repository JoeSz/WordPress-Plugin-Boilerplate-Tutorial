<?php
/*
 * REGISTER A CUSTOM POST TYPE (customers)
 * REGISTER METABOX FOR A CUSTOM POST TYPE (customers)
 * GET CUSTOM POST TYPE (customers) ARCHIVE TEMPLATE FROM PLUGIN
 * ADD/REMOVE/REORDER CUSTOM POST TYPE LIST COLUMNS (customers)
 * ADD OPTIONS PAGE TO PLUGIN
 * REGISTER SHORTCODE IN PLUGIN
 * ADD CUSTOM get_template_part TO LOAD TAMPLATE FROM PLUGIN
 * ADD TEMPLATE ENGINE TO PLUGIN
 *
 */


/*******************************************
 * REGISTER A CUSTOM POST TYPE (customers) *
 * --------------------------------------- *
 *******************************************/

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_admin_hooks() {

    // ...

    /**
     * The problem with the initial activation code is that when the activation hook runs, it's after the init hook has run,
     * so hooking into init from the activation hook won't do anything.
     * You don't need to register the CPT within the activation function unless you need rewrite rules to be added
     * via flush_rewrite_rules() on activation. In that case, you'll want to register the CPT normally, via the
     * loader on the init hook, and also re-register it within the activation function and
     * call flush_rewrite_rules() to add the CPT rewrite rules.
     *
     * @link https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/issues/261
     */
    $this->loader->add_action( 'init', $plugin_admin, 'create_custom_post_type' );

}

//////////////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name-activator.php

public static function activate() {

    // ...

    flush_rewrite_rules();

}

////////////////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name-deactivator.php

public static function deactivate() {

    // ...

    flush_rewrite_rules();

}

///////////////////////////////////////////////////
// ADD TO FILE -> admin/class-plugin-name-admin.php

/**
 * Create post type "Customers"
 *
 * @link https://codex.wordpress.org/Function_Reference/register_post_type
 */
public function create_custom_post_type() {

    $labels = array(
        'name'               => __( 'Customers' , $this->plugin_name ),
        'singular_name'      => __( 'Customer' , $this->plugin_name ),
        'menu_name'          => __( 'Customers', $this->plugin_name ),
        'parent_item_colon'  => __( 'Parent Customer', $this->plugin_name ),
        'all_items'          => __( 'All Customers', $this->plugin_name ),
        'add_new'            => __( 'Add New' , $this->plugin_name ),
        'add_new_item'       => __( 'Add New Customer' , $this->plugin_name ),
        'edit_item'          => __( 'Edit Customer' , $this->plugin_name ),
        'new_item'           => __( 'New Customer' , $this->plugin_name ),
        'view_item'          => __( 'View Customer ' , $this->plugin_name ),
        'search_items'       => __( 'Search Customer' , $this->plugin_name ),
        'not_found'          => __( 'Not Found' , $this->plugin_name ),
        'not_found_in_trash' => __( 'Not found in Trash' , $this->plugin_name ),
    );

    register_post_type( 'customers', array(
        'description'       => __( 'Customers', $this->plugin_name ),
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'capability_type'   => 'page',
        'hierarchical'      => true,
        /* Add $this->plugin_name as translatable in the permalink structure,
           to avoid conflicts with other plugins which may use customers as well. */
        'rewrite' => array(
            'slug' => __( $this->plugin_name, $this->plugin_name ) . '/' . __( 'customers', $this->plugin_name ),
            'with_front' => false
        ),
        'has_archive'       => true,
        'show_in_menu'      => true,
        'menu_position'     => 21,
        'menu_icon'         => 'dashicons-admin-users',
        'supports'          => array(
            'title',
            'editor',
            'excerpt',
            'author',
            'thumbnail',
            'page-attributes',
            'custom-fields',
        ),
    ));

}

/*******************************************************
 * REGISTER METABOX FOR A CUSTOM POST TYPE (customers) *
 * --------------------------------------------------- *
 *******************************************************/

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_admin_hooks() {

    // ...

    /**
     * Add metabox and register custom fields
     *
     * @link https://code.tutsplus.com/articles/rock-solid-wordpress-30-themes-using-custom-post-types--net-12093
     */
    $this->loader->add_action( 'admin_init', $plugin_admin, 'rerender_meta_options' );
    $this->loader->add_action( 'save_post', $plugin_admin, 'save_meta_options' );

}

///////////////////////////////////////////////////
// ADD TO FILE -> admin/class-plugin-name-admin.php


// Save custom fields
public function save_meta_options() {

    global $post;
    update_post_meta($post->ID, "customer_id", $_POST["customer_id"]);
    update_post_meta($post->ID, "customer_address", $_POST["customer_address"]);

}

/* Create a meta box for our custom fields */
public function rerender_meta_options() {

    add_meta_box("customer-meta", "Customer Details", array($this, "dispaly_meta_options"), "customers", "normal", "low");

}

// Display meta box and custom fields
public function dispaly_meta_options() {

    global $post;
    $custom = get_post_custom($post->ID);

    $customer_id = $custom["customer_id"][0];
    ?>
    <label><?php _e( 'Customer ID:', $this->plugin_name ); ?></label><input name="customer_id" value="<?php echo $customer_id; ?>" /><br>
    <?php

    $customer_address = $custom["customer_address"][0];
    ?>
    <label><?php _e( 'Customer Address:', $this->plugin_name ); ?></label><textarea name="customer_address"><?php echo $customer_address; ?></textarea>
    <?php

}

/*****************************************************************
 * GET CUSTOM POST TYPE (customers) ARCHIVE TEMPLATE FROM PLUGIN *
 * ------------------------------------------------------------- *
 *****************************************************************/

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_public_hooks() {

    // ...

    // Override archive template location for custom post type
    $this->loader->add_filter( 'archive_template', $plugin_public, 'get_custom_post_type_template' );

}

/////////////////////////////////////////////////////
// ADD TO FILE -> public/class-plugin-name-public.php

/**
 * Override acrive template location for custom post type
 *
 * If the archive template file not exist in the theme folder, then use  the plugin template.
 * In this case, file can be overridden inside the [child] theme.
 *
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/archive_template
 * @link http://wordpress.stackexchange.com/a/116025/90212
 */
function get_custom_post_type_template( $archive_template ) {

    global $post;
    $custom_post_type = 'customers';
    $templates_dir = 'templates';

    if ( is_post_type_archive( $custom_post_type ) ) {
        $theme_files = array('archive-' . $custom_post_type . '.php', $this->plugin_name . '/archive-' . $custom_post_type . '.php');
        $exists_in_theme = locate_template( $theme_files, false );
        if ( $exists_in_theme != '' ) {
            // Try to locate in theme first
            return $archive_template;
        } else {
            // Try to locate in plugin templates folder
            if ( file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_name . '/' . $templates_dir . '/archive-' . $custom_post_type . '.php' ) ) {
                return WP_PLUGIN_DIR . '/' . $this->plugin_name . '/' . $templates_dir . '/archive-' . $custom_post_type . '.php';
            } elseif ( file_exists( file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_name . '/archive-' . $custom_post_type . '.php' ) ) {
                // Try to locate in plugin base folder
                return WP_PLUGIN_DIR . '/' . $this->plugin_name . '/archive-' . $custom_post_type . '.php';
            } else {
                return null;
            }

        }
     }

     return $template;

}

/****************************************************************
 * ADD/REMOVE/REORDER CUSTOM POST TYPE LIST COLUMNS (customers) *
 * ------------------------------------------------------------ *
 ****************************************************************/

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_admin_hooks() {

    // ...

    // Change list columns in customers list
    $this->loader->add_filter( 'manage_edit-customers_columns', $plugin_admin, 'customers_list_edit_columns' );
    $this->loader->add_action( 'manage_pages_custom_column', $plugin_admin, 'customers_list_custom_columns', 10, 2 );

}

///////////////////////////////////////////////////
// ADD TO FILE -> admin/class-plugin-name-admin.php

// Modify columns in customers list in admin area
public function customers_list_edit_columns( $columns ) {

    // Remove unnecessary columns
    unset(
        $columns['author'],
        $columns['comments']
    );

    // Rename title and add ID and Address
    $columns['title'] = __( 'Customer Name', $this->plugin_name );
    $columns['customer_id'] = __( 'ID', $this->plugin_name );
    $columns['customer_address'] = __( 'Address', $this->plugin_name );

    /*
     * Rearrange column order
     *
     * Now define a new order. you need to look up the column
     * names in the HTML of the admin interface HTML of the table header.
     *
     *     "cb" is the "select all" checkbox.
     *     "title" is the title column.
     *     "date" is the date column.
     *     "icl_translations" comes from a plugin (in this case, WPML).
     *
     * change the order of the names to change the order of the columns.
     *
     * @link http://wordpress.stackexchange.com/questions/8427/change-order-of-custom-columns-for-edit-panels
     */
    $customOrder = array('cb', 'title', 'customer_id', 'customer_address', 'icl_translations', 'date');

    /*
     * return a new column array to wordpress.
     * order is the exactly like you set in $customOrder.
     */
    foreach ($customOrder as $column_name)
        $rearranged[$column_name] = $columns[$column_name];

    return $rearranged;

}

// Populate new columns in customers list in admin area
public function customers_list_custom_columns( $column ) {

    global $post;
    $custom = get_post_custom();

    // Populate column form meta
    switch ($column) {
        case "customer_id":
            echo $custom["customer_id"][0];
            break;
        case "customer_address":
            echo $custom["customer_address"][0];
            break;

    }

}

// Make columns sortable
public function customers_list_sortable_columns() {

    return array(
            'title'            => 'title',
            'customer_id'      => 'customer_id',
            'customer_address' => 'customer_address',
            'date'             => 'date',
    );

}

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
    $this->loader->add_action('admin_init', $plugin_admin, 'options_update');

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
     */
    add_submenu_page( 'plugins.php', 'Plugin settings page title', 'Admin area menu slug', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
    );

}

 /**
 * Add settings action link to the plugins page.
 *
 * @since    1.0.0
 */
public function add_action_links( $links ) {

    /*
    *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
    */
   $settings_link = array(
    '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>',
   );
   return array_merge(  $settings_link, $links );

}

/**
 * Render the settings page for this plugin.
 *
 * @since    1.0.0
 */
public function display_plugin_setup_page() {

    include_once( 'partials/plugin-name-admin-display.php' );

}

/**
 * Validate fields from admin area plugin settings form ('exopite-lazy-load-xt-admin-display.php')
 * @param  mixed $input as field form settings form
 * @return mixed as validated fields
 */
public function validate($input) {

    $valid = array();

    $valid['example_checkbox'] = ( isset( $input['example_checkbox'] ) && ! empty( $input['example_checkbox'] ) ) ? 1 : 0;
    $valid['example_text'] = ( isset( $input['example_text'] ) && ! empty( $input['example_text'] ) ) ? esc_attr( $input['example_text'] ) : 'default';
    $valid['example_select'] = ( isset($input['example_select'] ) && ! empty( $input['example_select'] ) ) ? esc_attr($input['example_select']) : 1;

    return $valid;

}

public function options_update() {

    register_setting( $this->plugin_name, $this->plugin_name, array( $this, 'validate' ) );

}

////////////////////////////////////////////////////////
// ADD TO FILE -> partials/plugin-name-admin-display.php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h2>Plugin name <?php _e(' Options', $this->plugin_name); ?></h2>
    <form method="post" name="cleanup_options" action="options.php">
    <?php
        //Grab all options
        $options = get_option($this->plugin_name);

        $example_select = ( isset($ options['example_checkbox'] ) && ! empty( $options['example_checkbox'] ) ) ? esc_attr( $options['example_checkbox'] ) : '1';
        $example_text = ( isset( $options['example_text'] ) && ! empty( $options['example_text'] ) ) ? esc_attr( $options['example_text'] ) : 'default';
        $example_radio = ( isset( $options['example_radio'] ) && ! empty( $options['example_radio'] ) ) ? 1 : 0;

        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);

        // Sources: - http://searchengineland.com/tested-googlebot-crawls-javascript-heres-learned-220157
        //          - http://dinbror.dk/blog/lazy-load-images-seo-problem/
        //          - https://webmasters.googleblog.com/2015/10/deprecating-our-ajax-crawling-scheme.html
    ?>

    <!-- Select -->
    <fieldset>
        <p><?php _e( 'Example Select.', $this->plugin_name ); ?></p>
        <legend class="screen-reader-text">
            <span><?php _e( 'Example Select', $this->plugin_name ); ?></span>
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
        <p><?php _e( 'Example Text.', $this->plugin_name ); ?></p>
        <legend class="screen-reader-text">
            <span><?php _e( 'Example Text', $this->plugin_name ); ?></span>
        </legend>
        <input type="text" class="example_text" id="<?php echo $this->plugin_name; ?>-example_text" name="<?php echo $this->plugin_name; ?>[example_text]" value="<?php if( ! empty( $example_text ) ) echo $example_text; else echo 'default'; ?>"/>
    </fieldset>

    <!-- Checkbox -->
    <fieldset>
        <p><?php _e( 'Example Radio.', $this->plugin_name ); ?></p>
        <legend class="example-radio">
            <span><?php _e( 'Example Radio', $this->plugin_name ); ?></span>
        </legend>
        <label for="<?php echo $this->plugin_name; ?>-example_radio">
            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-example_radio" name="<?php echo $this->plugin_name; ?>[example_radio]" value="1" <?php checked( $example_radio, 1 ); ?> />
            <span><?php esc_attr_e('Example Radio', $this->plugin_name); ?></span>
        </label>
    </fieldset>

    <?php submit_button( __( 'Save all changes', $this->plugin_name ), 'primary','submit', TRUE ); ?>
    </form>

?>

<?php
/********************************
 * REGISTER SHORTCODE IN PLUGIN *
 * ---------------------------- *
 ********************************/

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_public_hooks() {

    // ...

    /**
     * Register shortcode via loader
     *
     * Use: [short-code-name args]
     *
     * @link https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/issues/262
     */
    $this->loader->add_shortcode( "short-code-name", $plugin_public, "shortcode_function", $priority = 10, $accepted_args = 2 );
}

/////////////////////////////////////////////////////
// ADD TO FILE -> public/class-plugin-name-public.php

function shortcode_function( $atts ) {

    $args = shortcode_atts(
        array(
            'arg1'   => 'arg1',
            'arg2'   => 'arg2',
        ),
        $atts
    );

    // code...

    $var = ( strtolower( $args['arg1']) != "" ) ? strtolower( $args['arg1'] : 'default';

    // code...

    return $var;
}

/*************************************************************
 * ADD CUSTOM get_template_part TO LOAD TAMPLATE FROM PLUGIN *
 * --------------------------------------------------------- *
 *************************************************************/

/**
 * Initialize custom template loader
 *
 * Template File Loaders in Plugins
 * Template file loaders like this are used in a lot of large-scale plugins in order to
 * provide greater flexibility and better control for advanced users that want to tailor
 * a plugin’s output more to their specific needs.
 *
 * @link https://github.com/pippinsplugins/pw-sample-template-loader-plugin
 * @link https://pippinsplugins.com/template-file-loaders-plugins/#
 */
define( 'PLUGIN_NAME_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
if( ! class_exists( 'Gamajo_Template_Loader' ) ) {
    require PLUGIN_NAME_PLUGIN_DIR . 'class-gamajo-template-loader.php';
}
require PLUGIN_NAME_PLUGIN_DIR . 'custom-template-loader.class.php';
$templates = new Custom_Template_Loader;

/////////////////////////////////////////////////////////////////////
// USE INSIDE TEMPLATE FILE -> templates/archive-custom-post-type.php

/*********************************
 * ADD TEMPLATE ENGINE TO PLUGIN *
 * ----------------------------- *
 *********************************/

/////////////////////////////////
// ADD TO FILE -> plugin-name.php

$templates->get_template_part( 'content', 'custom-post-type' );

/**
 * Initialize custom templater
 */
if( ! class_exists( 'Exopite_Template' ) ) {
    require plugin_dir_path( __FILE__ ) . 'exopite-template.class.php';
}

/////////////////////////////////////////////////////////////////////
// USE INSIDE TEMPLATE FILE -> templates/archive-custom-post-type.php

$placeholders = array(
    'example-text' => esc_html__( 'Example Text', 'plugin-name' ),
);

Exopite_Template::$variables_array = $placeholders;
Exopite_Template::$filename = 'templates/partial.html';
echo Exopite_Template::get_template();
