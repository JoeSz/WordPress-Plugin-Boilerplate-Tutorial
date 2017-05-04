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
