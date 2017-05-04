<?php

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
 * @link https://pippinsplugins.com/template-file-loaders-plugins/
 */
define( 'PLUGIN_NAME_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
if( ! class_exists( 'Gamajo_Template_Loader' ) ) {
    require PLUGIN_NAME_PLUGIN_DIR . 'class-gamajo-template-loader.php';
}
require PLUGIN_NAME_PLUGIN_DIR . 'custom-template-loader.class.php';
$templates = new Custom_Template_Loader;

/////////////////////////////////////////////////////////////////////
// USE INSIDE TEMPLATE FILE -> templates/archive-custom-post-type.php

