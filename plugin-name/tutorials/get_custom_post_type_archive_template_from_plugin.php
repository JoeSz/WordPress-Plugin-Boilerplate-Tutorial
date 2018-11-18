<?php


/*****************************************************************
 * GET CUSTOM POST TYPE (customers) ARCHIVE TEMPLATE FROM PLUGIN *
 * ------------------------------------------------------------- *
 *****************************************************************/

////////////////////////////////////////////////
// ADD TO FILE -> includes/class-plugin-name.php

private function define_public_hooks() {

    // ...

    // Override archive template location for custom post type
    $this->loader->add_filter( 'archive_template', $plugin_public, 'get_custom_post_type_archive_template' );

    //OR
    $this->loader->add_filter( 'template_include', $plugin_public, 'get_custom_post_type_templates' );

}

/////////////////////////////////////////////////////
// ADD TO FILE -> public/class-plugin-name-public.php

/**
 * Override archive template location for custom post type
 *
 * If the archive template file not exist in the theme folder, then use  the plugin template.
 * In this case, file can be overridden inside the [child] theme.
 *
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/archive_template
 * @link http://wordpress.stackexchange.com/a/116025/90212
 */
function get_custom_post_type_archive_template(  ) {

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
            } elseif ( file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_name . '/archive-' . $custom_post_type . '.php' ) ) {
                // Try to locate in plugin base folder
                return WP_PLUGIN_DIR . '/' . $this->plugin_name . '/archive-' . $custom_post_type . '.php';
            } else {
                return null;
            }

        }

    }

    return $archive_template;

}

// OR

function locate_template( $template, $settings, $page_type ) {

    $theme_files = array(
        $page_type . '-' . $settings['custom_post_type'] . '.php',
        $this->plugin_name . DIRECTORY_SEPARATOR . $page_type . '-' . $settings['custom_post_type'] . '.php',
    );

    $exists_in_theme = locate_template( $theme_files, false );

    if ( $exists_in_theme != '' ) {

        // Try to locate in theme first
        return $template;

    } else {

        // Try to locate in plugin base folder,
        // try to locate in plugin $settings['templates'] folder,
        // return $template if non of above exist
        $locations = array(
            join( DIRECTORY_SEPARATOR, array( WP_PLUGIN_DIR, $this->plugin_name, '' ) ),
            join( DIRECTORY_SEPARATOR, array( WP_PLUGIN_DIR, $this->plugin_name, $settings['templates_dir'], '' ) ), //plugin $settings['templates'] folder
        );

        foreach ( $locations as $location ) {
            if ( file_exists( $location . $theme_files[0] ) ) {
                return $location . $theme_files[0];
            }
        }

        return $template;

    }

}

function get_custom_post_type_templates( $template ) {
    global $post;

    $settings = array(
        'custom_post_type' => 'exopite-portfolio',
        'templates_dir' => 'templates',
    );

    //if ( $settings['custom_post_type'] == get_post_type() && ! is_archive() && ! is_search() ) {
    if ( $settings['custom_post_type'] == get_post_type() && is_single() ) {

        return $this->locate_template( $template, $settings, 'single' );

    }

    return $template;
}
