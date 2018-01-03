=== Plugin Name ===
Donate link: http://joe.szalai.org
Tags: comments, spam
Requires at least: 4.9
Tested up to: 4.9.1
Stable tag: 4.9
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Version: 20180102
Plugin URL: https://joe.szalai.org/exopite/exopite-simple-options-framework/
GitHub URL: https://github.com/JoeSz/Exopite-Simple-Options-Framework

== Note ==

The Framework still in development stage.

Documentation is still in-progress.

The Framework based on some CodeStar Framework, MetaBox.io code and design. The fields configs desgin also based on CodeStar Framework.
I created this framework for plugins and metaboxes. Not for Themes. For Themes I recommend CodeStar Framework.

== Description ==

WHY?
I need someting fast, easy and lightweight to generate option page and/or metabox for my plugins and/or post types.
I also love to create/program someting new (for me) to have fun and leary every day. For my theme I use CodeStar Framework,
so I created similarly. Unfortunately CodeStar Framework based on static class, can not initialize multiple times, and
this is required for plugns.

Lightweight
No ads, Files are loaded only when required. Minimum footprint.

Integration
Easy to integrate with any plugin or post type (even WordPress theme, but it is not designed to do so).

Open Source
Exopite Simple Options is free and available on Github. Feel free to submit patches or add more features.

== Features ==

Available fields:
- card
- content
- notice
- text
- textarea
- select
- checkbox
- radio
- date
- switcher
- range
- image_select
- tap_list
- number
- color_picker
- botton_bar
- media upload drag and drop
- ACE field
- video mp4/oembed

== Requirements ==

Server

* WordPress 4.9+ (May work with earlier versions too)
* PHP 5.6+ (Required)
* jQuery 1.9.1+

Browsers

* Modern Browsers
* Firefox, Chrome, Safari, Opera, IE 10+
* Tested on Firefox, Chrome, Edge, IE 11

== Installation ==

Copy to plugin/theme folder.
Hook to 'init'.

== How to use ==

$config = array(

    'type'              => 'menu',                          // Required, menu or metabox
    'id'                => $this->plugin_name,              // Required, meta box id, unique per page, to save: get_option( id )
    'menu'              => 'plugins.php',                   // Required, sub page to your options page
    'submenu'           => true,                            // Required for submenu
    'title'             => 'The name',                      // The name of this page
    'capability'        => 'manage_options',                // The capability needed to view the page
    'tabbed'            => false,                           // Separate sections to tabs

);

$fields[] = array(
    'name'   => 'first',
    'title'  => 'Section First',
    'fields' => array(

        // fields...

        array(
            'id'      => 'autoload',
            'type'    => 'switcher',
            'title'   => 'Field title',
            'default' => 'yes',
        ),

    ),
);

$fields[] = array(
    'name'   => 'second',
    'title'  => 'Section Second',
    'fields' => array(

        // fields...

        array(
            'id'      => 'autoload',
            'type'    => 'switcher',
            'title'   => 'Field title',
            'default' => 'yes',
        ),

    ),
);

$options_panel = new Exopite_Simple_Options_Framework( $config, $fields );

== Changelog ==

= 20180102 - 2018-01-02 =
* Initial release.
