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
    $this->loader->add_action( 'init', $plugin_post_types, 'create_custom_post_type', 999 );

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

    /**
     * This is only required if the custom post type has rewrite!
     *
     * Remove rewrite rules and then recreate rewrite rules.
     *
     * This function is useful when used with custom post types as it allows for automatic flushing of the WordPress
     * rewrite rules (usually needs to be done manually for new custom post types).
     * However, this is an expensive operation so it should only be used when absolutely necessary.
     * See Usage section for more details.
     *
     * Flushing the rewrite rules is an expensive operation, there are tutorials and examples that suggest
     * executing it on the 'init' hook. This is bad practice. It should be executed either
     * on the 'shutdown' hook, or on plugin/theme (de)activation.
     *
     * @link https://codex.wordpress.org/Function_Reference/flush_rewrite_rules
     */
    flush_rewrite_rules();

}

////////////////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name-deactivator.php

public static function deactivate() {

    // ...

    /**
     * This only required if custom post type has rewrite!
     */
    flush_rewrite_rules();

}

///////////////////////////////////////////////////
// COPY FILE FROM TUTORIAL -> includes/class-plugin-name-post_types.php
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
     * Register custom post type
     *
     * @link https://codex.wordpress.org/Function_Reference/register_post_type
     */
    private function register_single_post_type( $fields ) {

        /**
		 * Labels used when displaying the posts in the admin and sometimes on the front end.  These
		 * labels do not cover post updated, error, and related messages.  You'll need to filter the
		 * 'post_updated_messages' hook to customize those.
		 */
        $labels = array(
            'name'                  => $fields['plural'],
            'singular_name'         => $fields['singular'],
            'menu_name'             => $fields['menu_name'],
            'new_item'              => sprintf( __( 'New %s', 'plugin-name' ), $fields['singular'] ),
            'add_new_item'          => sprintf( __( 'Add new %s', 'plugin-name' ), $fields['singular'] ),
            'edit_item'             => sprintf( __( 'Edit %s', 'plugin-name' ), $fields['singular'] ),
            'view_item'             => sprintf( __( 'View %s', 'plugin-name' ), $fields['singular'] ),
            'view_items'            => sprintf( __( 'View %s', 'plugin-name' ), $fields['plural'] ),
            'search_items'          => sprintf( __( 'Search %s', 'plugin-name' ), $fields['plural'] ),
            'not_found'             => sprintf( __( 'No %s found', 'plugin-name' ), strtolower( $fields['plural'] ) ),
            'not_found_in_trash'    => sprintf( __( 'No %s found in trash', 'plugin-name' ), strtolower( $fields['plural'] ) ),
            'all_items'             => sprintf( __( 'All %s', 'plugin-name' ), $fields['plural'] ),
            'archives'              => sprintf( __( '%s Archives', 'plugin-name' ), $fields['singular'] ),
            'attributes'            => sprintf( __( '%s Attributes', 'plugin-name' ), $fields['singular'] ),
            'insert_into_item'      => sprintf( __( 'Insert into %s', 'plugin-name' ), strtolower( $fields['singular'] ) ),
            'uploaded_to_this_item' => sprintf( __( 'Uploaded to this %s', 'plugin-name' ), strtolower( $fields['singular'] ) ),

            /* Labels for hierarchical post types only. */
            'parent_item'           => sprintf( __( 'Parent %s', 'plugin-name' ), $fields['singular'] ),
            'parent_item_colon'     => sprintf( __( 'Parent %s:', 'plugin-name' ), $fields['singular'] ),

            /* Custom archive label.  Must filter 'post_type_archive_title' to use. */
			'archive_title'        => $fields['plural'],
        );

        $args = array(
            'labels'             => $labels,
            'description'        => ( isset( $fields['description'] ) ) ? $fields['description'] : '',
            'public'             => ( isset( $fields['public'] ) ) ? $fields['public'] : true,
            'publicly_queryable' => ( isset( $fields['publicly_queryable'] ) ) ? $fields['publicly_queryable'] : true,
            'exclude_from_search'=> ( isset( $fields['exclude_from_search'] ) ) ? $fields['exclude_from_search'] : false,
            'show_ui'            => ( isset( $fields['show_ui'] ) ) ? $fields['show_ui'] : true,
            'show_in_menu'       => ( isset( $fields['show_in_menu'] ) ) ? $fields['show_in_menu'] : true,
            'query_var'          => ( isset( $fields['query_var'] ) ) ? $fields['query_var'] : true,
            'show_in_admin_bar'  => ( isset( $fields['show_in_admin_bar'] ) ) ? $fields['show_in_admin_bar'] : true,
            'capability_type'    => ( isset( $fields['capability_type'] ) ) ? $fields['capability_type'] : 'post',
            'has_archive'        => ( isset( $fields['has_archive'] ) ) ? $fields['has_archive'] : true,
            'hierarchical'       => ( isset( $fields['hierarchical'] ) ) ? $fields['hierarchical'] : true,
            'supports'           => ( isset( $fields['supports'] ) ) ? $fields['supports'] : array(
                    'title',
                    'editor',
                    'excerpt',
                    'author',
                    'thumbnail',
                    'comments',
                    'trackbacks',
                    'custom-fields',
                    'revisions',
                    'page-attributes',
                    'post-formats',
            ),
            'menu_position'      => ( isset( $fields['menu_position'] ) ) ? $fields['menu_position'] : 21,
            'menu_icon'          => ( isset( $fields['menu_icon'] ) ) ? $fields['menu_icon']: 'dashicons-admin-generic',
            'show_in_nav_menus'  => ( isset( $fields['show_in_nav_menus'] ) ) ? $fields['show_in_nav_menus'] : true,
        );

        if ( isset( $fields['rewrite'] ) ) {

            /**
             *  Add $this->plugin_name as translatable in the permalink structure,
             *  to avoid conflicts with other plugins which may use customers as well.
             */
            $args['rewrite'] = $fields['rewrite'];
        }

        if ( $fields['custom_caps'] ) {

            /**
             * Provides more precise control over the capabilities than the defaults.  By default, WordPress
             * will use the 'capability_type' argument to build these capabilities.  More often than not,
             * this results in many extra capabilities that you probably don't need.  The following is how
             * I set up capabilities for many post types, which only uses three basic capabilities you need
             * to assign to roles: 'manage_examples', 'edit_examples', 'create_examples'.  Each post type
             * is unique though, so you'll want to adjust it to fit your needs.
             *
             * @link https://gist.github.com/creativembers/6577149
             * @link http://justintadlock.com/archives/2010/07/10/meta-capabilities-for-custom-post-types
             */
            $args['capabilities'] = array(

                // Meta capabilities
                'edit_post'                 => 'edit_' . strtolower( $fields['singular'] ),
                'read_post'                 => 'read_' . strtolower( $fields['singular'] ),
                'delete_post'               => 'delete_' . strtolower( $fields['singular'] ),

                // Primitive capabilities used outside of map_meta_cap():
                'edit_posts'                => 'edit_' . strtolower( $fields['plural'] ),
                'edit_others_posts'         => 'edit_others_' . strtolower( $fields['plural'] ),
                'publish_posts'             => 'publish_' . strtolower( $fields['plural'] ),
                'read_private_posts'        => 'read_private_' . strtolower( $fields['plural'] ),

                // Primitive capabilities used within map_meta_cap():
                'delete_posts'              => 'delete_' . strtolower( $fields['plural'] ),
                'delete_private_posts'      => 'delete_private_' . strtolower( $fields['plural'] ),
                'delete_published_posts'    => 'delete_published_' . strtolower( $fields['plural'] ),
                'delete_others_posts'       => 'delete_others_' . strtolower( $fields['plural'] ),
                'edit_private_posts'        => 'edit_private_' . strtolower( $fields['plural'] ),
                'edit_published_posts'      => 'edit_published_' . strtolower( $fields['plural'] ),
                'create_posts'              => 'edit_' . strtolower( $fields['plural'] )

            );

            /**
             * Adding map_meta_cap will map the meta correctly.
             * @link https://wordpress.stackexchange.com/questions/108338/capabilities-and-custom-post-types/108375#108375
             */
            $args['map_meta_cap'] = true;

            /**
             * Assign capabilities to users
             * Without this, users - also admins - can not see post type.
             */
            $this->assign_capabilities( $args['capabilities'], $fields['custom_caps_users'] );
        }

        register_post_type( $fields['slug'], $args );

        /**
         * Register Taxnonmies if any
         * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
         */
        if ( isset( $fields['taxonomies'] ) && is_array( $fields['taxonomies'] ) ) {

            foreach ( $fields['taxonomies'] as $taxonomy ) {

                $this->register_single_post_type_taxnonomy( $taxonomy );

            }

        }

    }

    private function register_single_post_type_taxnonomy( $tax_fields ) {

        $labels = array(
            'name'                       => $tax_fields['plural'],
            'singular_name'              => $tax_fields['single'],
            'menu_name'                  => $tax_fields['plural'],
            'all_items'                  => sprintf( __( 'All %s' , 'plugin-name' ), $tax_fields['plural'] ),
            'edit_item'                  => sprintf( __( 'Edit %s' , 'plugin-name' ), $tax_fields['single'] ),
            'view_item'                  => sprintf( __( 'View %s' , 'plugin-name' ), $tax_fields['single'] ),
            'update_item'                => sprintf( __( 'Update %s' , 'plugin-name' ), $tax_fields['single'] ),
            'add_new_item'               => sprintf( __( 'Add New %s' , 'plugin-name' ), $tax_fields['single'] ),
            'new_item_name'              => sprintf( __( 'New %s Name' , 'plugin-name' ), $tax_fields['single'] ),
            'parent_item'                => sprintf( __( 'Parent %s' , 'plugin-name' ), $tax_fields['single'] ),
            'parent_item_colon'          => sprintf( __( 'Parent %s:' , 'plugin-name' ), $tax_fields['single'] ),
            'search_items'               => sprintf( __( 'Search %s' , 'plugin-name' ), $tax_fields['plural'] ),
            'popular_items'              => sprintf( __( 'Popular %s' , 'plugin-name' ), $tax_fields['plural'] ),
            'separate_items_with_commas' => sprintf( __( 'Separate %s with commas' , 'plugin-name' ), $tax_fields['plural'] ),
            'add_or_remove_items'        => sprintf( __( 'Add or remove %s' , 'plugin-name' ), $tax_fields['plural'] ),
            'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s' , 'plugin-name' ), $tax_fields['plural'] ),
            'not_found'                  => sprintf( __( 'No %s found' , 'plugin-name' ), $tax_fields['plural'] ),
        );

        $args = array(
        	'label'                 => $tax_fields['plural'],
        	'labels'                => $labels,
        	'hierarchical'          => ( isset( $tax_fields['hierarchical'] ) )          ? $tax_fields['hierarchical']          : true,
        	'public'                => ( isset( $tax_fields['public'] ) )                ? $tax_fields['public']                : true,
        	'show_ui'               => ( isset( $tax_fields['show_ui'] ) )               ? $tax_fields['show_ui']               : true,
        	'show_in_nav_menus'     => ( isset( $tax_fields['show_in_nav_menus'] ) )     ? $tax_fields['show_in_nav_menus']     : true,
        	'show_tagcloud'         => ( isset( $tax_fields['show_tagcloud'] ) )         ? $tax_fields['show_tagcloud']         : true,
        	'meta_box_cb'           => ( isset( $tax_fields['meta_box_cb'] ) )           ? $tax_fields['meta_box_cb']           : null,
        	'show_admin_column'     => ( isset( $tax_fields['show_admin_column'] ) )     ? $tax_fields['show_admin_column']     : true,
        	'show_in_quick_edit'    => ( isset( $tax_fields['show_in_quick_edit'] ) )    ? $tax_fields['show_in_quick_edit']    : true,
        	'update_count_callback' => ( isset( $tax_fields['update_count_callback'] ) ) ? $tax_fields['update_count_callback'] : '',
        	'show_in_rest'          => ( isset( $tax_fields['show_in_rest'] ) )          ? $tax_fields['show_in_rest']          : true,
        	'rest_base'             => $tax_fields['taxonomy'],
        	'rest_controller_class' => ( isset( $tax_fields['rest_controller_class'] ) ) ? $tax_fields['rest_controller_class'] : 'WP_REST_Terms_Controller',
        	'query_var'             => $tax_fields['taxonomy'],
        	'rewrite'               => ( isset( $tax_fields['rewrite'] ) )               ? $tax_fields['rewrite']               : true,
        	'sort'                  => ( isset( $tax_fields['sort'] ) )                  ? $tax_fields['sort']                  : '',
        );

        $args = apply_filters( $tax_fields['taxonomy'] . '_args', $args );

        register_taxonomy( $tax_fields['taxonomy'], $tax_fields['post_types'], $args );

    }

    /**
     * Assign capabilities to users
     *
     * @link https://codex.wordpress.org/Function_Reference/register_post_type
     * @link https://typerocket.com/ultimate-guide-to-custom-post-types-in-wordpress/
     */
    public function assign_capabilities( $caps_map, $users  ) {

        foreach ( $users as $user ) {

            $user_role = get_role( $user );

            foreach ( $caps_map as $cap_map_key => $capability ) {

                $user_role->add_cap( $capability );

            }

        }

    }

    /**
     * CUSTOMIZE CUSTOM POST TYPE AS YOU WISH.
     */

    /**
     * Create post types
     */
    public function create_custom_post_type() {

        /**
         * This is not all the fields, only what I find important. Feel free to change this function ;)
         *
         * @link https://codex.wordpress.org/Function_Reference/register_post_type
         *
         * For more info on fields:
         * @link https://github.com/JoeSz/WordPress-Plugin-Boilerplate-Tutorial/blob/9fb56794bc1f8aebfe04e99b15881db0c4bc61bd/plugin-name/includes/class-plugin-name-post_types.php#L230
         */
        $post_types_fields = array(
            array(
                // Slug max. 20 characters, cannot contain capital letters or spaces!
                // https://toolset.com/forums/topic/types-custom-post-type-slug-length-limited-to-20-characters/
                'slug'                  => 'test',
                'singular'              => 'Test',
                'plural'                => 'Tests',
                'menu_name'             => 'Tests',
                'description'           => 'Tests',
                'has_archive'           => true,
                'hierarchical'          => false,
                'menu_icon'             => 'dashicons-tag',
                'rewrite' => array(
                    'slug'                  => 'tests',
                    'with_front'            => true,
                    'pages'                 => true,
                    'feeds'                 => true,
                    'ep_mask'               => EP_PERMALINK,
                ),
                'menu_position'         => 21,
                'public'                => true,
                'publicly_queryable'    => true,
                'exclude_from_search'   => true,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'query_var'             => true,
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => true,
                'supports'              => array(
                    'title',
                    'editor',
                    'excerpt',
                    'author',
                    'thumbnail',
                    'comments',
                    'trackbacks',
                    'custom-fields',
                    'revisions',
                    'page-attributes',
                    'post-formats',
                ),
                'custom_caps'           => true,
                'custom_caps_users'     => array(
                    'administrator',
                ),
                'taxonomies'            => array(

                    array(
                        'taxonomy'          => 'test_category',
                        'plural'            => 'Test Categories',
                        'single'            => 'Test Category',
                        'post_types'        => array( 'test' ),
                    ),

                ),
            ),
        );

        foreach ( $post_types_fields as $fields ) {

            $this->register_single_post_type( $fields );

        }

        // ...

    }

    // ...

}
