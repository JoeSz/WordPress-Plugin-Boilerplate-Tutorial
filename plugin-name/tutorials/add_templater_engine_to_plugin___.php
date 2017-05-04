<?php

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
Exopite_Template::$filename = WP_PLUGIN_DIR . '/' . $this->plugin_name . 'templates/partial.html';
echo Exopite_Template::get_template();
