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

    /**
     * This only required if custom post type has rewrite!
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
         */
        $post_types_fields = array(
            array(
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
            ),
        );

        // ...

    }