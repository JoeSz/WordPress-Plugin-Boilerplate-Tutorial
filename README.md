# WordPress Plugin Boilerplate Tutorial with Examples
## **THIS IS A TUTORIAL WITH EXAMPLES**
## Now you can install the plugin, I will implement all functionality.

This is a tutorial plugin with examples. I created some tutorials to help with my work and I tought, I share this to help others.

More will come... ;)

LAST CHANGE: 2019-05-27

---

> A standardized, organized, object-oriented foundation for building high-quality WordPress Plugins.

---

Get the WordPress Plugin Boilderpalte form here: https://github.com/DevinVinson/WordPress-Plugin-Boilerplate <br />
Github link for the WordPress Plugin Boilerplate Generator: https://github.com/Tmeister/wppb-gen-site <br />
Generate your personalized plugin: [Plugin Boilerplate Generator](https://wppb.me/)

## Tutorial and example code for:
* How to register a **custom post types** (e.g.: tests) with **taxonomies** and **capabilities**
* How to register **metabox** for a custom post type (e.g.: tests)
* How to register metabox for a custom post type with `Exopite Simple Options Framework` (https://github.com/JoeSz/Exopite-Simple-Options-Framework) (e.g.: tests)
* How to get custom post type (e.g.: customers) **archive template** from plugin folder
* How to add/remove/reorder/sort **custom** post type **list columns** in **admin area** (e.g.: test)
* How to add admin **options page** to plugin
* How to add admin options page to plugin with `Exopite Simple Options Framework`
* How to register **shortcodes** in plugin
* How to **access plugin** and it's **methodes** later from **outside** of the plugin (eg. from theme or template file)
* How to **access plugin methodes** from **inside** of the plugin (eg. from admin from public)
* How to **register, update** and **use custom** datebase **tables**
* Added **custom `get_template_part`** to load (single) template from plugin folder<br />
(`class-gamajo-template-loader.php` from https://github.com/GaryJones/Gamajo-Template-Loader and<br />
`class-custom-template-loader.php`)
* Added template engine to plugin <br />
(`class-exopite-template.php` https://gist.github.com/JoeSz/3e27ee4230b8ce842ac4989016a38caa)
* Register any external php file in permalinks
* Register **AJAX callback** (also JavaScript code)
* Register **POST callback**
* **Export CSV**
* Add **custom updater** for your plugin with `YahnisElsts - Plugin Update Checker` (https://github.com/YahnisElsts/plugin-update-checker)
* Handle AJAX/POST callback (eg.:)<br />
  * check nonce,
  * insert post and post meta,
  * upload file and add to meta and/or WordPress Media
* Run code on plugin upgrade

Check out the `/plugin-name/tutorials/`.


### Example Plugins, Plugins I have created with the Boilerplate

#### You could take a look, how you can implement some functions.

* [Exopite-Multifilter-Multi-Sorter](https://github.com/JoeSz/Exopite-Multifilter-Multi-Sorter-WordPress-Plugin) - Working with shortcodes and AJAX
* [Exopite-Notificator](https://github.com/JoeSz/Exopite-Notificator-WordPress-Plugin) - Working with hooks for comments, posts, user profile, authenticate, etc... in WordPress.
* [Exopite-Combiner-and-Minifier-WordPress-Plugin](https://github.com/JoeSz/Exopite-Combiner-and-Minifier-WordPress-Plugin) - Working with output buffering and DOM manipulation with PHP DOMDocument.
* [Exopite-SEO-Core](https://github.com/JoeSz/Exopite-SEO-Core) - Working with several SEO releated WordPress hooks.
* [Exopite-Lazy-Load-XT](https://github.com/JoeSz/Exopite-Lazy-Load-XT-WordPress-Plugin) - Working with output buffering as well as content manipulation (like thumbnails, etc...) in WordPress.

## Contents

The WordPress Plugin Boilerplate includes the following files:

* `.gitignore`. Used to exclude certain files from the repository.
* `CHANGELOG.md`. The list of changes to the core project.
* `README.md`. The file that you’re currently reading.
* A `plugin-name` directory that contains the source code - a fully executable WordPress plugin.

## Features

* The Boilerplate is based on the [Plugin API](http://codex.wordpress.org/Plugin_API), [Coding Standards](http://codex.wordpress.org/WordPress_Coding_Standards), and [Documentation Standards](https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/).
* All classes, functions, and variables are documented so that you know what you need to be changed.
* The Boilerplate uses a strict file organization scheme that correspond both to the WordPress Plugin Repository structure, and that make it easy to organize the files that compose the plugin.
* The project includes a `.pot` file as a starting point for internationalization.

## Installation

The Boilerplate can be installed directly into your plugins folder "as-is". You will want to rename it and the classes inside of it to fit your needs.

Note that this will activate the source code of the Boilerplate, but because the Boilerplate has no real functionality so no menu  items, meta boxes, or custom post types will be added.

## Tutorial files

You may delete, what you don't use, on production

Tutorial files

* `tutorials/access_plugin_admin_public_methodes_from_inside.php`
* `tutorials/access_plugin_and_its_methodes_later_from_outside_of_plugin.php`
* `tutorials/add_custom_get-template-part_to_load_template_from_plugin.php`
* `tutorials/add_remove_reorder_sort_custom_post_type_list_columns_in_admin_area.php`
* `tutorials/add_templater_engine_to_plugin.php`
* `tutorials/app_option_page_for_plugin.php`
* `tutorials/app_option_page_for_plugin_with_options_framework.php`
* `tutorials/custom_post_types.php`
* `tutorials/custom_updater_for_plugin.php`
* `tutorials/export_csv.php`
* `tutorials/get_custom_post_type_archive_template_from_plugin.php`
* `tutorials/handling_POST_request.php`
* `tutorials/meta_box_for_custom_post_type.php`
* `tutorials/meta_box_for_custom_post_type_with_options_framework.php`
* `tutorials/register_ajax_callback.php`
* `tutorials/register_an_external_php_file_in_permalinks.php`
* `tutorials/register_a_shortcode_in_plugin.php`
* `tutorials/register_and_use_custom_tables_in_datebase.php`
* `tutorials/register_post_callback_without_ajax.php`
* `tutorials/run_code_on_plugin_upgrade_and_admin_notice.php`

Template File Loaders in Plugins

Template file loaders like this are used in a lot of large-scale plugins in order to provide greater flexibility and better control for advanced users that want to tailor a plugin’s output more to their specific needs.

Source: https://github.com/pippinsplugins/pw-sample-template-loader-plugin
Tutorial: https://pippinsplugins.com/template-file-loaders-plugins/

* `includes/libraries/class-gamajo-template-loader.php`
* `includes/libraries/class-custom-template-loader.php`
* `includes/libraries/class-exopite-template.class.php`
* `templates/partial.html`
* `templates/archive-custom-post-type.php`
* `templates/content-custom-post-type.php`

## WordPress.org Preparation

The original launch of this version of the boilerplate included the folder structure needed for using your plugin on the WordPress.org. That folder structure has been moved to its own repo here: https://github.com/DevinVinson/Plugin-Directory-Boilerplate

## Recommended Tools

### i18n Tools

The WordPress Plugin Boilerplate uses a variable to store the text domain used when internationalizing strings throughout the Boilerplate. To take advantage of this method, there are tools that are recommended for providing correct, translatable files:

* [Easy PO](http://www.eazypo.ca/)
* [Poedit](http://www.poedit.net/)
* [makepot](http://i18n.svn.wordpress.org/tools/trunk/)
* [i18n](https://github.com/grappler/i18n)

Any of the above tools should provide you with the proper tooling to internationalize the plugin.

## License

The WordPress Plugin Boilerplate is licensed under the GPL v3 or later.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

> You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

A copy of the license is included in the root of the plugin’s directory. The file is named `LICENSE`.

## Important Notes

### Licensing

The WordPress Plugin Boilerplate is licensed under the GPL v2 or later; however, if you opt to use third-party code that is not compatible with v2, then you may need to switch to using code that is GPL v3 compatible.

For reference, [here's a discussion](http://make.wordpress.org/themes/2013/03/04/licensing-note-apache-and-gpl/) that covers the Apache 2.0 License used by [Bootstrap](http://twitter.github.io/bootstrap/).

### Includes

Note that if you include your own classes, or third-party libraries, there are three locations in which said files may go:

* `plugin-name/includes` is where functionality shared between the admin area and the public-facing parts of the site reside
* `plugin-name/admin` is for all admin-specific functionality
* `plugin-name/public` is for all public-facing functionality

Note that previous versions of the Boilerplate did not include `Plugin_Name_Loader` but this class is used to register all filters and actions with WordPress.

The example code provided shows how to register your hooks with the Loader class.

### What About Other Features?

The previous version of the WordPress Plugin Boilerplate included support for a number of different projects such as the [GitHub Updater](https://github.com/afragen/github-updater).

These tools are not part of the core of this Boilerplate, as I see them as being additions, forks, or other contributions to the Boilerplate.

The same is true of using tools like Grunt, Composer, etc. These are all fantastic tools, but not everyone uses them. In order to  keep the core Boilerplate as light as possible, these features have been removed and will be introduced in other editions, and will be listed and maintained on the project homepage

# Credits

The WordPress Plugin Boilerplate was started in 2011 by [Tom McFarlin](http://twitter.com/tommcfarlin/) and has since included a number of great contributions. In March of 2015 the project was handed over by Tom to Devin Vinson.

The current version of the Boilerplate was developed in conjunction with [Josh Eaton](https://twitter.com/jjeaton), [Ulrich Pogson](https://twitter.com/grapplerulrich), and [Brad Vincent](https://twitter.com/themergency).

The homepage is based on a design as provided by [HTML5Up](http://html5up.net), the Boilerplate logo was designed by  Rob McCaskill of [BungaWeb](http://bungaweb.com), and the site `favicon` was created by [Mickey Kay](https://twitter.com/McGuive7).

## Documentation, FAQs, and More

If you’re interested in writing any documentation or creating tutorials please [let me know](http://devinvinson.com/contact/) .

CHANGELOG
---------

= 2019-05-27 =
* Update Exopite Simple Options Framework

= 2019-04-10 =
* Add gallery to death simple metabox field creator

= 2018-09-30 =
* Add tutorial: run code on plugin upgrade with admin notice
* Add sort to custom post type list columns in admin area
* Add tutorial to pluhin: add, remove, reorder and sort custom post type list columns in admin area

= 2018-09-25 =
* Update Exopite Simple Options Framework
* Add tutorial: Export CSV

= 2018-08-29 =
* Add tutorial: How to access plugin and it's methodes later from outside of the plugin (eg. from theme or template file)
* Add tutorial: Custom updater for your plugin
* Add tutorial: How to register, update and use custom datebase tables

= 1.0.2 =
* Add tutorial

= 1.0.1 =
* Add shortcode hooks. More: https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/issues/262

= 1.0.0 =
* Initial release
