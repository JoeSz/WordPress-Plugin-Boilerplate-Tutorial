<?php

/*******************************************
 * REGISTER A CUSTOM POST TYPE (customers) *
 * --------------------------------------- *
 *******************************************/

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function load_dependencies() {

    // ...

    /**
     * Custom Post Types
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-post_types.php';

    // ...

    $this->loader = new Plugin_Name_Loader();

}

private function define_admin_hooks() {

    // ...

    $plugin_post_types = new Plugin_Name_Post_Types();

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
    $this->loader->add_action( 'init', $plugin_post_types, 'create_custom_post_type' );

}

//////////////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name-activator.php

public static function activate() {

    // ...

    /**
     * Custom Post Types
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-post_types.php';
    $plugin_post_types = new Plugin_Name_Post_Types();

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
    $plugin_post_types->create_custom_post_type();

    flush_rewrite_rules();

}

////////////////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name-deactivator.php

public static function deactivate() {

    // ...

    flush_rewrite_rules();

}

///////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name-post_types.php
<?php

/**
 * Register custom post type
 *
 * @link       http://joe.szalai.org
 * @since      1.0.0
 *
 * @package    Exopite_Portfolio
 * @subpackage Exopite_Portfolio/includes
 */
class Plugin_Name_Post_Types {

    /**
     * Create post type "customers"
     *
     * @link https://codex.wordpress.org/Function_Reference/register_post_type
     */
    public function create_custom_post_type() {

        // custom post type
        $slug = 'customers';
        $has_archive = false;
        $hierarchical = false;
        $supports = array(
            'title',
            'editor',
            'excerpt',
            'author',
            'thumbnail',
            'page-attributes',
            'custom-fields',
        );

        $labels = array(
            'name'               => esc_html__( 'Customers' , 'plugin-name' ),
            'singular_name'      => esc_html__( 'Customer' , 'plugin-name' ),
            'menu_name'          => esc_html__( 'Customers', 'plugin-name' ),
            'parent_item_colon'  => esc_html__( 'Parent Customer', 'plugin-name' ),
            'all_items'          => esc_html__( 'All Customers', 'plugin-name' ),
            'add_new'            => esc_html__( 'Add New' , 'plugin-name' ),
            'add_new_item'       => esc_html__( 'Add New Customer' , 'plugin-name' ),
            'edit_item'          => esc_html__( 'Edit Customer' , 'plugin-name' ),
            'new_item'           => esc_html__( 'New Customer' , 'plugin-name' ),
            'view_item'          => esc_html__( 'View Customer ' , 'plugin-name' ),
            'search_items'       => esc_html__( 'Search Customer' , 'plugin-name' ),
            'not_found'          => esc_html__( 'Not Found' , 'plugin-name' ),
            'not_found_in_trash' => esc_html__( 'Not found in Trash' , 'plugin-name' ),
        );

        $args = array(
            'labels'             => $labels,
            'description'        => esc_html__( 'Customers', 'plugin-name' ),
            'public'             => true,
            'publicly_queryable' => true,
            'exclude_from_search'=> true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'show_in_admin_bar'  => true,
            'capability_type'    => 'page',
            'has_archive'        => $has_archive,
            'hierarchical'       => $hierarchical,
            'menu_position'      => null,
            'supports'           => $supports,
            'menu_position'      => 21,
            'menu_icon'          => 'dashicons-tickets',
            /* Add $this->plugin_name as translatable in the permalink structure,
               to avoid conflicts with other plugins which may use customers as well. */
            'rewrite' => array(
                'slug' => esc_attr__( $this->plugin_name, $this->plugin_name ) . '/' . esc_attr__( 'customers', 'plugin-name' ),
                'with_front' => false
            ),
        );

        register_post_type( $slug, $args );

    }

}
