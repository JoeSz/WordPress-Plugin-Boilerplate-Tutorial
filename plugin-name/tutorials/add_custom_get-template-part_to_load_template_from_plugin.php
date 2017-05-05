<?php

/*************************************************************
 * ADD CUSTOM get_template_part TO LOAD TAMPLATE FROM PLUGIN *
 * --------------------------------------------------------- *
 *************************************************************/

////////////////////////////////////////////////////////////////////////
// EDIT FILE -> eg.: includes/libraries/class-custom-template-loader.php

/**
 * Directory name where custom templates for this plugin should be found in the theme.
 *
 * @since 1.0.0
 * @type string
 */
protected $theme_template_directory = 'templates';

/**
 * Reference to the root directory path of this plugin.
 * 'PLUGIN_NAME_BASE_DIR' was definied in main plugin file.
 *
 * @since 1.0.0
 * @type string
 */
protected $plugin_directory = PLUGIN_NAME_BASE_DIR;

/**
 * Initialize custom template loader
 *
 * Template File Loaders in Plugins
 * Template file loaders like this are used in a lot of large-scale plugins in order to
 * provide greater flexibility and better control for advanced users that want to tailor
 * a plugin’s output more to their specific needs.
 *
 * @link https://github.com/pippinsplugins/pw-sample-template-loader-plugin
 * @link https://pippinsplugins.com/template-file-loaders-plugins/
 */
if( ! class_exists( 'Gamajo_Template_Loader' ) ) {
    require PLUGIN_NAME_BASE_DIR . 'includes/libraries/class-gamajo-template-loader.php';
}
require PLUGIN_NAME_BASE_DIR . 'includes/libraries/class-custom-template-loader.php';

//////////////////////////////////////////////////////////////////////////
// USE INSIDE TEMPLATE FILE -> eg.: templates/archive-custom-post-type.php

function sample_shortcode() {
    $templates = new Custom_Template_Loader;
    // Turn on output buffering, because it is a shortcode, content file should not echo anything out.
    // In other cases, output buffering may not required.
    ob_start();
    // Load template from PLUGIN_NAME_BASE_DIR/templates/content-header.php
    $templates->get_template_part( 'content', 'header' );
    // Return content ftom the file
    return ob_get_clean();
}
add_shortcode( 'sample-shortcode', 'sample_shortcode' );
