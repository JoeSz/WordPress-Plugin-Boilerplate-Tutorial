<?php
/**
 * Template part for displaying custom post type posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package plugin-name
 */

$placeholders = array(
    'post-id' => 'post-' . get_the_ID(),
    'title' => get_the_title(),
    'the_excerpt'  => get_the_excerpt(),
    'footer' => 'This is the footer.',
);

Exopite_Template::$variables_array = $placeholders;
Exopite_Template::$filename = 'templates/partial.html';
echo Exopite_Template::get_template();

