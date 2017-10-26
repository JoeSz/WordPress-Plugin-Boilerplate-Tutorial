<?php

/***********************************************
 * ADD TEMPLATE FINDER/LOADER ENGINE TO PLUGIN *
 * ------------------------------------------- *
 ***********************************************/

/*
 * Gamajo_Template_Loader
 * find and load template from pulgin directory.
 */

/////////////////////////////////
// ADD TO FILE -> plugin-name.php

/**
 * Use Gamajo_Template_Loader
 *
 * $templates->get_template_part required Gamajo_Template_Loader, see in tutorial file:
 * add_custom_get-template-part_to_load_template_from_plugin.php
 */
$templates->get_template_part( 'content', 'custom-post-type' );

/*
 * Custom templater (without an external php class like Gamajo_Template_Loader)
 *
 * See tutorial file:
 * get_custom_post_type_archive_template_from_plugin.php
 */

/*******************************************
 * ADD TEMPLATE GENERATOR ENGINE TO PLUGIN *
 * --------------------------------------- *
 *******************************************/

/*
 * Exopite_Template
 * load given tempalte, any file and/or "partial", and change following placeholders (-if exist-)
 *
 * [#variable]
 * %%VARIABLE%%
 * {$variable}
 * {{variable}}
 *
 * in template file based on the given $variables_array and optionally remove HTML comments even munltiline ones.
 * It will also remove not existing placeholders.
 */

/////////////////////////////////
// ADD TO FILE -> plugin-name.php

/**
 * Use Exopite_Template
 *
 * Initialize custom templater
 */
require PLUGIN_NAME_BASE_DIR . 'includes/libraries/class-exopite-template.php';

//////////////////////////////////////////////////////////////////////////
// USE INSIDE TEMPLATE FILE -> eg.: templates/archive-custom-post-type.php

$placeholders = array(
    'example-text' => esc_html__( 'Example Text', 'plugin-name' ),
);

Exopite_Template::$variables_array = $placeholders;
Exopite_Template::$filename = PLUGIN_NAME_BASE_DIR . '/templates/partial.html';
echo Exopite_Template::get_template();
