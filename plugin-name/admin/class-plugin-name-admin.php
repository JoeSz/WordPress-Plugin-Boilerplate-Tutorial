<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/*************************************************************
	 * ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE
	 *
	 * @tutorial access_plugin_admin_public_methodes_from_inside.php
	 */
	/**
	 * Store plugin main class to allow public access.
	 *
	 * @since    20180622
	 * @var object      The main class.
	 */
	public $main;
	// ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	// public function __construct( $plugin_name, $version ) {

	// 	$this->plugin_name = $plugin_name;
	// 	$this->version = $version;

    // }

	/*************************************************************
	 * ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE
	 *
	 * @tutorial access_plugin_admin_public_methodes_from_inside.php
	 */
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_main ) {

		$this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->main = $plugin_main;

    }
    // ACCESS PLUGIN ADMIN PUBLIC METHODES FROM INSIDE

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );

	}

    public function get_all_emails() {

        $all_users = get_users();

        $user_email_list = array();

        foreach ($all_users as $user) {
            $user_email_list[esc_html($user->user_email)] = esc_html($user->display_name);
        }

        return $user_email_list;

    }

    public function test_sanitize_callback( $val ) {
        return str_replace ( 'a', 'b', $val );
    }

    public function create_menu() {

        /**
         * Create a submenu page under Plugins.
         * Framework also add "Settings" to your plugin in plugins list.
         * @link https://github.com/JoeSz/Exopite-Simple-Options-Framework
         */
        $config_submenu = array(

            'type'              => 'menu',                          // Required, menu or metabox
            'id'                => $this->plugin_name,              // Required, meta box id, unique per page, to save: get_option( id )
            'parent'            => 'plugins.php',                   // Parent page of plugin menu (default Settings [options-general.php])
            'submenu'           => true,                            // Required for submenu
            'title'             => 'Demo Admin Page',               // The title of the options page and the name in admin menu
            'capability'        => 'manage_options',                // The capability needed to view the page
            'plugin_basename'   =>  plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' ),
            // 'tabbed'            => false,
            // 'multilang'         => false,                        // To turn of multilang, default on.

        );

        /*
         * To add a metabox.
         * This normally go to your functions.php or another hook
         */
        $config_metabox = array(

            /*
             * METABOX
             */
            'type'              => 'metabox',                       // Required, menu or metabox
            'id'                => $this->plugin_name,              // Required, meta box id, unique, for saving meta: id[field-id]
            'post_types'        => array( 'test' ),                 // Post types to display meta box
            // 'post_types'        => array( 'post', 'page' ),         // Could be multiple
            'context'           => 'advanced',                      // 	The context within the screen where the boxes should display: 'normal', 'side', and 'advanced'.
            'priority'          => 'default',                       // 	The priority within the context where the boxes should show ('high', 'low').
            'title'             => 'Demo Metabox',                  // The title of the metabox
            'capability'        => 'edit_posts',                    // The capability needed to view the page
            'tabbed'            => true,
            // 'multilang'         => false,                        // To turn of multilang, default off except if you have qTransalte-X.
            'options'           => 'simple',                        // Only for metabox, options is stored az induvidual meta key, value pair.
            /**
             * Simple options is stored az induvidual meta key, value pair, otherwise it is stored in an array.
             *
             * I implemented this option because it is possible to search in serialized (array) post meta:
             * @link https://wordpress.stackexchange.com/questions/16709/meta-query-with-meta-values-as-serialize-arrays
             * @link https://stackoverflow.com/questions/15056407/wordpress-search-serialized-meta-data-with-custom-query
             * @link https://www.simonbattersby.com/blog/2013/03/querying-wordpress-serialized-custom-post-data/
             *
             * but there is no way to sort them with wp_query or SQL.
             * @link https://wordpress.stackexchange.com/questions/87265/order-by-meta-value-serialized-array/87268#87268
             * "Not in any reliable way. You can certainly ORDER BY that value but the sorting will use the whole serialized string,
             * which will give * you technically accurate results but not the results you want. You can't extract part of the string
             * for sorting within the query itself. Even if you wrote raw SQL, which would give you access to database functions like
             * SUBSTRING, I can't think of a dependable way to do it. You'd need a MySQL function that would unserialize the value--
             * you'd have to write it yourself.
             * Basically, if you need to sort on a meta_value you can't store it serialized. Sorry."
             *
             * It is possible to get all required posts and store them in an array and then sort them as an array,
             * but what if you want multiple keys/value pair to be sorted?
             *
             * UPDATE
             * it is maybe possible:
             * @link http://www.russellengland.com/2012/07/how-to-unserialize-data-using-mysql.html
             * but it is waaay more complicated and less documented as meta query sort and search.
             * It should be not an excuse to use it, but it is not as reliable as it should be.
             *
             * @link https://wpquestions.com/Order_by_meta_key_where_value_is_serialized/7908
             * "...meta info serialized is not a good idea. But you really are going to lose the ability to query your
             * data in any efficient manner when serializing entries into the WP database.
             *
             * The overall performance saving and gain you think you are achieving by serialization is not going to be noticeable to
             * any major extent. You might obtain a slightly smaller database size but the cost of SQL transactions is going to be
             * heavy if you ever query those fields and try to compare them in any useful, meaningful manner.
             *
             * Instead, save serialization for data that you do not intend to query in that nature, but instead would only access in
             * a passive fashion by the direct WP API call get_post_meta() - from that function you can unpack a serialized entry
             * to access its array properties too."
             */

        );

        /**
         * Available fields:
         * - ACE field
         * - attached
         * - backup
         * - button
         * - botton_bar
         * - card
         * - checkbox
         * - color
         * - color_wp
         * - content
         * - date
         * - editor
         * - group/accordion item
         * - hidden
         * - image
         * - image_select
         * - meta
         * - notice
         * - number
         * - password
         * - radio
         * - range
         * - select
         * - switcher
         * - tab
         * - tap_list
         * - text
         * - textarea
         * - typography
         * - upload
         * - video mp4/oembed
         */

        $fields[] = array(
            'name'   => 'basic',
            'title'  => 'Basic',
            'icon'   => 'dashicons-admin-generic',
            'fields' => array(


                array(
                    'id'          => 'text_1',
                    'type'        => 'text',
                    'title'       => 'Text',
                    'before'      => 'Text Before',
                    'after'       => 'Text After',
                    'class'       => 'text-class',
                    'description' => 'Description',
                    'default'     => 'Default Text',
                    'attributes'    => array(
                       'rows'        => 10,
                       'cols'        => 5,
                       'placeholder' => 'do stuff',
                       'data-test'   => 'test',

                    ),
                    'help'        => 'Help text',
                    'sanitize'    => array( $this, 'test_sanitize_callback' ),

                ),

                array(
                    'id'     => 'test_disabled',
                    'type'   => 'text',
                    'title'  => 'Disabled',
                    'attributes'     => array(
                    'placeholder' => 'This is a diabled element',
                    'disabled'    => 'disabled',

                    ),
                ),

                array(
                    'id'      => 'text_icon',
                    'type'    => 'text',
                    'title'   => 'Text',
                    'prepend' => 'fa-font',
                    'append'  => 'Char',
                ),

                array(
                    'id'     => 'password_1',
                    'type'   => 'password',
                    'title'  => 'Password',
                ),



                array(
                    'type'    => 'content',
                    'wrap_class'   => 'no-border-bottom', // for all fieds
                    'title'   => 'Content Title',
                    'content' => 'You can "group" element together, by adding the class <code>no-border-bottom</code> to the <code>wrap_class</code>.',
                    'before' => 'Before Text',
                    'after'  => 'After Text',
                ),

                array(
                    'id'    => 'image_1',
                    'type'  => 'image',
                    'title' => 'Image',
                ),


                array(
                    'id'      => 'switcher_1',
                    'type'    => 'switcher',
                    'title'   => 'Switcher',
                    'label'   => 'You want to do this?',
                    'default' => 'yes',
                ),


                array(
                    'id'      => 'hidden_1',
                    'type'    => 'hidden',
                    'default' => 'hidden',
                ),

                array(
                    'id'    => 'checkbox_1',
                    'type'  => 'checkbox',
                    'title' => 'Checkbox',
                    'label' => 'Did you like this framework ?',
                    'after' => '<i>If you check this and the other checkbox, a text field will appier.</i>'
                ),

                array(
                    'id'    => 'checkbox_2',
                    'type'  => 'checkbox',
                    'title' => 'Checkbox Fancy',
                    'label' => 'Do you want to do this?',
                    'style'    => 'fancy',
                ),

                array(
                    'id'     => 'text_2',
                    'type'   => 'text',
                    'title'  => 'Text Test Dependency',
                    'dependency' => array( 'checkbox_1|checkbox_2', '==|==', 'true|true' ),
                    'attributes'    => array(
                        'placeholder' => 'Dependency test',
                    ),
                ),

                array(
                  'id'      => 'radio_1',
                  'type'    => 'radio',
                  'title'   => 'Radio',
                  'options' => array(
                    'yes'   => 'Yes, Please.',
                    'no'    => 'No, Thank you.',
                  ),
                  'default' => 'no',
                ),

                array(
                  'id'      => 'radio_2',
                  'type'    => 'radio',
                  'title'   => 'Radio Fancy',
                  'options' => array(
                    'yes'   => 'Yes, Please.',
                    'no'    => 'No, Thank you.',
                  ),
                  'default' => 'no',
                  'style'    => 'fancy',
                ),

                array(
                    'id'      => 'test_unknown_1',
                    'type'    => 'test_unknown_type',
                    'title'   => 'Test Unknown Element',
                ),


            )
        );

        $fields[] = array(
            'name'   => 'accordions',
            'title'  => 'Accordion',
            'icon'   => 'fa fa-bars',
            'fields' => array(

                array(
                    'id'          => 'accordion_1',
                    'type'        => 'accordion',
                    'title'       => esc_html__( 'Accordion', 'plugin-name' ),
                    'options' => array(
                        'allow_all_open' => false,
                    ),
                    'sections'        => array(

                        array(
                            'options' => array(
                                'icon'   => 'fa fa-star',
                                'title'  => 'Section 1',
                                'closed' => false,
                            ),
                            'fields' => array(

                                array(
                                    'id'    => 'accordion_1_section_1_text_1',
                                    'type'  => 'text',
                                    'title' => esc_html__( 'Accordion 1 Text 1', 'plugin-name' ),
                                    'default' => 'default text',
                                ),


                            ),
                        ),

                        array(
                            'options' => array(
                                'icon'   => 'fa fa-star',
                                'title'  => 'Section 2',
                            ),
                            'fields' => array(

                                array(
                                    'id'    => 'accordion_1_section_2_text_1',
                                    'type'  => 'text',
                                    'title' => esc_html__( 'Accordion 2 Text 1', 'plugin-name' ),
                                ),

                                array(
                                    'id'    => 'accordion_1_section_2_text_2',
                                    'type'  => 'text',
                                    'title' => esc_html__( 'Accordion 2 Text 2', 'plugin-name' ),
                                ),

                            ),

                        ),


                        array(
                            'options' => array(
                                'icon'   => 'fa fa-star',
                                'title'  => 'Section 3',
                            ),
                            'fields' => array(

                                array(
                                    'id'    => 'accordion_1_section_3_text_1',
                                    'type'  => 'text',
                                    'title' => esc_html__( 'Accordion 3 Text 1', 'plugin-name' ),
                                ),

                                array(
                                    'id'    => 'accordion_1_section_3_text_2',
                                    'type'  => 'text',
                                    'title' => esc_html__( 'Accordion 3 Text 2', 'plugin-name' ),
                                ),

                            ),

                        ),

                    ),

                ),


            ),
        );

        $fields[] = array(
            'name'   => 'attached',
            'title'  => 'Attached',
            'icon'   => 'dashicons-images-alt',
            'fields' => array(

                array(
                    'type'    => 'notice',
                    'class'   => 'warning',
                    'content' => 'Metabox only in metabox available.',
                ),

                array(
                    'id'      => 'attached_1',
                    'type'    => 'attached',
                    'title'   => 'Attached',
                    'options' => array(
                        'type' => '', // attach to post (only in metabox)
                    ),
                ),


            )
        );

        $fields[] = array(
            'name'   => 'backup',
            'title'  => 'Backup',
            'icon'   => 'dashicons-backup',
            'fields' => array(

                array(
                    'type'    => 'backup',
                    'title'   => esc_html__( 'Backup', 'exopite-seo-core' ),
                ),


            )
        );

        $fields[] = array(
            'name'   => 'buttons',
            'title'  => 'Button',
            'icon'   => 'fa fa-toggle-on',
            'fields' => array(

                array(
                'id'      => 'button_1',
                'type'    => 'button',
                'title'   => 'Button',
                'options' => array(
                    'href'      => '#',
                    'target'    => '_self',
                    'value'     => 'button',
                    'btn-class' => 'exopite-sof-btn',
                ),
                ),

                array(
                'id'      => 'button_bar_1',
                'type'    => 'button_bar',
                'title'   => 'Button bar',
                'options' => array(
                    'one'   => 'One',
                    'two'   => 'Two',
                    'three' => 'Three',
                ),
                'default' => 'two',
                ),


            )
        );

        $fields[] = array(
            'name'   => 'colors',
            'title'  => 'Color',
            'icon'   => 'dashicons-art',
            'fields' => array(

                array(
                    'id'     => 'color_1',
                    'type'   => 'color_wp',
                    'title'  => 'Color WordPress RGB',
                ),

                array(
                    'id'     => 'color_2',
                    'type'   => 'color_wp',
                    'title'  => 'Color WordPress RGBA',
                    'rgba'   => true,
                ),

                array(
                    'id'     => 'color_3',
                    'type'   => 'color',
                    'title'  => 'Color HTML5',
                    'picker' => 'html5',
                ),

                array(
                    'id'     => 'color_4',
                    'type'   => 'color',
                    'title'  => 'Color Minicolor RGB',
                ),

                array(
                    'id'      => 'color_5',
                    'type'    => 'color',
                    'title'   => 'Color Minicolor RGBA',
                    'rgba'    => true,
                    'default' => '#ff0000',
                    // 'default' => 'rgba(255,0,0,1)',
                ),

                array(
                    'id'      => 'color_6',
                    'type'    => 'color',
                    'title'   => 'Color Minicolor Wheel',
                    'rgba'    => true,
                    'control' => 'wheel',
                    'default' => '#ff0000',
                ),




            )
        );

        $fields[] = array(
            'name'   => 'contents',
            'title'  => 'Contents',
            'icon'   => 'fa fa-align-justify',
            'fields' => array(

                array(
                    'type'    => 'card',
                    'class'   => 'class-name', // for all fieds
                    'title'   => 'Panel Title',
                    'content' => '<p>Etiam consectetur commodo ullamcorper. Donec quis diam nulla. Maecenas at mi molestie ex aliquet dignissim a in tortor. Sed in nisl ac mi rutrum feugiat ac sed sem. Nullam tristique ex a tempus volutpat. Sed ut placerat nibh, a iaculis risus. Aliquam sit amet erat vel nunc feugiat viverra. Mauris aliquam arcu in dolor volutpat, sed tempor tellus dignissim.</p><p>Quisque nec lectus vitae velit commodo condimentum ut nec mi. Cras ut ultricies dui. Nam pretium <a href="#">rutrum eros</a> ac facilisis. Morbi vulputate vitae risus ac varius. Quisque sed accumsan diam. Sed elementum eros lectus, et egestas ante hendrerit eu. Proin porta, enim nec dignissim commodo, odio orci maximus tortor, iaculis congue felis velit sed lorem. </p>',
                    'header' => 'Header Text',
                    'footer' => 'Footer Text',
                ),

                array(
                    'type'    => 'card',
                    'class'   => 'class-name', // for all fieds
                    'content' => '<p>Etiam consectetur commodo ullamcorper. Donec quis diam nulla. Maecenas at mi molestie ex aliquet dignissim a in tortor. Sed in nisl ac mi rutrum feugiat ac sed sem. Nullam tristique ex a tempus volutpat. Sed ut placerat nibh, a iaculis risus. Aliquam sit amet erat vel nunc feugiat viverra. Mauris aliquam arcu in dolor volutpat, sed tempor tellus dignissim.</p><p>Quisque nec lectus vitae velit commodo condimentum ut nec mi. Cras ut ultricies dui. Nam pretium <a href="#">rutrum eros</a> ac facilisis. Morbi vulputate vitae risus ac varius. Quisque sed accumsan diam. Sed elementum eros lectus, et egestas ante hendrerit eu. Proin porta, enim nec dignissim commodo, odio orci maximus tortor, iaculis congue felis velit sed lorem. </p>',
                ),

                array(
                    'type'    => 'content',
                    'class'   => 'class-name', // for all fieds
                    'content' => '<p>Etiam consectetur commodo ullamcorper. Donec quis diam nulla. Maecenas at mi molestie ex aliquet dignissim a in tortor. Sed in nisl ac mi rutrum feugiat ac sed sem. Nullam tristique ex a tempus volutpat. Sed ut placerat nibh, a iaculis risus. Aliquam sit amet erat vel nunc feugiat viverra. Mauris aliquam arcu in dolor volutpat, sed tempor tellus dignissim.</p><p>Quisque nec lectus vitae velit commodo condimentum ut nec mi. Cras ut ultricies dui. </p>',

                ),

                array(
                    'type'    => 'content',
                    'class'   => 'class-name', // for all fieds
                    'title'   => 'Content Title',
                    'content' => '<p>Etiam consectetur commodo ullamcorper. Donec quis diam nulla. Maecenas at mi molestie ex aliquet dignissim a in tortor. Sed in nisl ac mi rutrum feugiat ac sed sem. Nullam tristique ex a tempus volutpat. Sed ut placerat nibh, a iaculis risus. Aliquam sit amet erat vel nunc feugiat viverra. Mauris aliquam arcu in dolor volutpat, sed tempor tellus dignissim.</p><p>Quisque nec lectus vitae velit commodo condimentum ut nec mi. Cras ut ultricies dui. Nam pretium <a href="#">rutrum eros</a> ac facilisis. Morbi vulputate vitae risus ac varius. Quisque sed accumsan diam. Sed elementum eros lectus, et egestas ante hendrerit eu. Proin porta, enim nec dignissim commodo, odio orci maximus tortor, iaculis congue felis velit sed lorem. </p>',
                    'before' => 'Before Text',
                    'after'  => 'After Text',
                ),

            )
        );

        $fields[] = array(
            'name'   => 'dates',
            'title'  => 'Date',
            'icon'   => 'fa fa-calendar',
            'fields' => array(

                array(
                    'id'     => 'date_1',
                    'type'   => 'date',
                    'title'  => 'Date ISO',
                    'format' => 'yy-mm-dd',
                    'class'  => 'datepic-class',
                    'prepend' => 'fa-calendar',
                ),

                array(
                    'id'         => 'date_2',
                    'type'       => 'date',
                    'title'      => 'Date DE',
                    'format'     => 'dd.mm.yy',
                    'class'      => 'datepic-class',
                    'wrap_class' => 'wrap_class',
                ),

                array(
                    'id'     => 'date_3',
                    'type'   => 'date',
                    'title'  => 'Date',
                    'format' => 'yy-mm-dd',
                    'class'  => 'datepic-class',
                    'picker' => 'html5',
                ),


            )
        );

        $fields[] = array(
            'title'  => esc_html__( 'Editor', 'exopite-combiner-minifier' ),
            'icon'   => 'fa fa-paragraph',
            'name'   => 'editors',
            'sections' => array(
                array(
                    'title'  => esc_html__( 'ACE Editor', 'exopite-combiner-minifier' ),
                    'name'   => 'editors',
                    'icon'   => 'fa fa-code',
                    'fields' => array(


                        array(
                            'id'      => 'ace_editor_1',
                            'type'    => 'ace_editor',
                            'title'   => 'ACE Editor',
                            'options' => array(
                                'theme'                     => 'ace/theme/chrome',
                                'mode'                      => 'ace/mode/javascript',
                                'showGutter'                => true,
                                'showPrintMargin'           => true,
                                'enableBasicAutocompletion' => true,
                                'enableSnippets'            => true,
                                'enableLiveAutocompletion'  => true,
                            ),
                            'attributes'    => array(
                                'style'        => 'height: 300px; max-width: 700px;',
                            ),
                        ),

                    ),
                ),

                array(
                    'title'  => esc_html__( 'WYSIWYG Editors', 'exopite-combiner-minifier' ),
                    'name'   => 'editors2',
                    'icon'   => 'fa fa-paragraph',
                    'fields' => array(

                        array(
                            'id'     => 'editor_1',
                            'type'   => 'editor',
                            'title'  => 'Editor TinyMCE',
                        ),

                        array(
                            'id'     => 'editor_trumbowyg',
                            'type'   => 'editor',
                            'title'  => 'Editor Trumbowyg',
                            'editor' => 'trumbowyg',
                        ),

                    ),
                ),

                array(
                    'title'  => esc_html__( 'Textarea', 'exopite-combiner-minifier' ),
                    'name'   => 'editors3',
                    'icon'   => 'dashicons-text',
                    'fields' => array(

                        array(
                            'id'          => 'textarea_1',
                            'type'        => 'textarea',
                            'title'       => 'Textarea',
                            'attributes'    => array(
                                'placeholder' => 'do stuff',
                            ),
                        ),


                    ),
                ),

            ),

        );

        $fields[] = array(
            'name'   => 'fieldsets',
            'title'  => 'Fieldset',
            'icon'   => 'fa fa-list-alt',
            'fields' => array(

                array(
                    'type'    => 'fieldset',
                    'id'      => 'fieldset_1',
                    'title'   => esc_html__( 'Fieldset field', 'plugin-name' ),
                    'description'   => esc_html__( 'Cols can be 1 to 4 and 6.', 'plugin-name' ) . '<br>' . esc_html__( 'Three cols per row.', 'plugin-name' ),
                    'options' => array(
                        'cols' => 3,
                    ),
                    'fields'  => array(

                        array(
                            'id'      => 'fieldset_1_text_1',
                            'type'    => 'text',
                            'title'   => esc_html__( 'Text', 'plugin-name' ),
                        ),

                        array(
                            'id'             => 'fieldset_1_select_1',
                            'type'           => 'select',
                            'title'          => 'Miltiselect',
                            'options'        => array(
                                'bmw'          => 'BMW',
                                'mercedes'     => 'Mercedes',
                                'volkswagen'   => 'Volkswagen',
                                'other'        => 'Other',
                            ),
                            'default_option' => 'Select your favorite car',
                            'default'     => 'bmw',
                            'attributes' => array(
                                'multiple' => 'multiple',
                                'style'    => 'width: 200px; height: 125px;',
                            ),
                            'class'       => 'chosen',
                        ),


                        array(
                             'id'      => 'fieldset_1_switcher_1',
                             'type'    => 'switcher',
                             'title'   => esc_html__( 'Switcher', 'plugin-name' ),
                             'default' => 'yes',
                         ),
                    ),
                ),

                array(
                    'type'    => 'fieldset',
                    'id'      => 'fieldset_2',
                    'title'   => esc_html__( 'Fieldset field', 'plugin-name' ),
                    'description'   => esc_html__( 'E.g.: use for border, but can be used for many things, link dimensions or spacing, etc...', 'plugin-name' ) . '<br>' . esc_html__( 'Two cols per row.', 'plugin-name' ),
                    'options' => array(
                        'cols' => 2,
                    ),
                    'fields'  => array(

                        array(
                            'id'      => 'fieldset_2_top',
                            'type'    => 'text',
                            'prepend' => 'fa fa-long-arrow-up',
                            'append'  => 'px',
                            'attributes' => array(
                                'placeholder' => esc_html__( 'top', 'plugin-name' ),
                            ),
                        ),

                        array(
                            'id'      => 'fieldset_2_bottom',
                            'type'    => 'text',
                            'prepend' => 'fa fa-long-arrow-down',
                            'append'  => 'px',
                            'attributes' => array(
                                'placeholder' => esc_html__( 'bottom', 'plugin-name' ),
                            ),
                        ),

                        array(
                            'id'      => 'fieldset_2_left',
                            'type'    => 'text',
                            'prepend' => 'fa fa-long-arrow-left',
                            'append'  => 'px',
                            'attributes' => array(
                                'placeholder' => esc_html__( 'left', 'plugin-name' ),
                            ),
                        ),

                        array(
                            'id'      => 'fieldset_2_right',
                            'type'    => 'text',
                            'prepend' => 'fa fa-long-arrow-right',
                            'append'  => 'px',
                            'attributes' => array(
                                'placeholder' => esc_html__( 'right', 'plugin-name' ),
                            ),
                        ),

                        array(
                            'id'             => 'fieldset_2_border_style',
                            'type'           => 'select',
                            'options'        => array(
                                'none'          => 'None',
                                'solid'         => 'Solid',
                                'dashed'        => 'Dashed',
                                'dotted'        => 'Dotted',
                                'double'        => 'Double',
                                'inset'         => 'Inset',
                                'outset'        => 'Outset',
                                'groove'        => 'Groove',
                                'ridge'         => 'ridge',
                            ),
                            'default_option' => 'None',
                            'default'     => 'none',
                            'class'       => 'chosen width-150',
                        ),

                        array(
                            'id'     => 'fieldset_2_color',
                            'type'   => 'color',
                            'rgba'   => true,
                        ),

                    ),
                ),

                array(
                    'type'    => 'fieldset',
                    'id'      => 'fieldset_3',
                    'title'   => esc_html__( 'Fieldset field', 'plugin-name' ),
                    'fields'  => array(

                        array(
                            'id'      => 'fieldset_3_text_1',
                            'type'    => 'text',
                            'title'   => esc_html__( 'Text', 'plugin-name' ),
                        ),

                        array(
                            'id'             => 'fieldset_3_select_1',
                            'type'           => 'select',
                            'title'          => 'Miltiselect',
                            'options'        => array(
                                'bmw'          => 'BMW',
                                'mercedes'     => 'Mercedes',
                                'volkswagen'   => 'Volkswagen',
                                'other'        => 'Other',
                            ),
                            'default_option' => 'Select your favorite car',
                            'default'     => 'bmw',
                            'attributes' => array(
                                'multiple' => 'multiple',
                                'style'    => 'width: 200px; height: 125px;',
                            ),
                            'class'       => 'chosen',
                        ),


                        array(
                             'id'      => 'fieldset_3_switcher_1',
                             'type'    => 'switcher',
                             'title'   => esc_html__( 'Switcher', 'plugin-name' ),
                             'default' => 'yes',
                         ),
                    ),
                ),


            ),
        );

        $fields[] = array(
            'name'   => 'groups',
            'title'  => 'Repeater/Sortable',
            'icon'   => 'fa fa-object-ungroup',
            'fields' => array(

                array(
                    'type'    => 'group',
                    'id'      => 'group_1',
                    'title'   => esc_html__( 'Group field nested (3 level)', 'plugin-name' ),
                    'options' => array(
                        'repeater'          => true,
                        'accordion'         => true,
                        'button_title'      => esc_html__( 'Add new (L1)', 'plugin-name' ),
                        'group_title'       => esc_html__( 'Accordion Title', 'plugin-name' ),
                        'limit'             => 50,
                        'sortable'          => true,
                    ),
                    'fields'  => array(

                        array(
                            'id'      => 'group_1_text_1',
                            'type'    => 'text',
                            'title'   => esc_html__( 'Text', 'plugin-name' ),
                            'attributes' => array(
                                // mark this field az title, on type this will change group item title
                                'data-title' => 'title',
                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
                            ),
                        ),

                        array(
                            'type'    => 'group',
                            'id'      => 'group_1_group_1',
                            'title'   => esc_html__( 'Group field', 'plugin-name' ),
                            'options' => array(
                                'repeater'          => true,
                                'accordion'         => true,
                                'button_title'      => esc_html__( 'Add new (L2)', 'plugin-name' ),
                                'group_title'       => esc_html__( 'Accordion Title', 'plugin-name' ),
                                'limit'             => 50,
                                'sortable'          => true,
                            ),
                            'fields'  => array(

                                array(
                                    'id'      => 'group_1_group_1_text_1',
                                    'type'    => 'text',
                                    // 'title'   => esc_html__( 'Text', 'plugin-name' ),
                                    'attributes' => array(
                                        // mark this field az title, on type this will change group item title
                                        'data-title' => 'title',
                                        'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
                                    ),
                                ),

                                array(
                                    'id'     => 'group_1_group_1_color_2',
                                    'type'   => 'color',
                                    'title'  => 'Color Minicolor',
                                ),

                                array(
                                    'id'     => 'group_1_group_1_date_1',
                                    'type'   => 'date',
                                    'title'  => 'Date ISO',
                                    'format' => 'yy-mm-dd',
                                    'class'  => 'datepic-class',
                                    'prepend' => 'fa-calendar',
                                ),

                                array(
                                    'id'      => 'group_1_group_1_switcher_1',
                                    'title'   => 'Switcher',
                                    'type'    => 'switcher',
                                    'default' => 'yes',
                                ),

                                array(
                                    'id'      => 'group_1_group_1_typography_1',
                                    'type'    => 'typography',
                                    'title'   => esc_html__( 'Typography', 'exopite-combiner-minifier' ),
                                    'default' => array(
                                        'family'    =>'Arial Black',
                                        'variant'   =>'600',
                                        'size'      => 16,
                                        'height'    => 24,
                                        'color'     => '#000000',
                                    ),
                                    'preview' => true,
                                ),
                                array(
                                    'id'             => 'group_1_group_1_select_1',
                                    'type'           => 'select',
                                    'title'          => 'Miltiselect',
                                    'options'        => array(
                                        'bmw'          => 'BMW',
                                        'mercedes'     => 'Mercedes',
                                        'volkswagen'   => 'Volkswagen',
                                        'other'        => 'Other',
                                    ),
                                    'default_option' => 'Select your favorite car',
                                    'default'     => 'bmw',
                                    'attributes' => array(
                                        'multiple' => 'multiple',
                                        'style'    => 'width: 200px; height: 125px;',
                                    ),
                                    'class'       => 'chosen',
                                ),

                                array(
                                    'type'    => 'group',
                                    'id'      => 'group_1_group_1_group_1',
                                    // 'title'   => esc_html__( 'Group field', 'plugin-name' ),
                                    'options' => array(
                                        'repeater'          => true,
                                        'accordion'         => true,
                                        'button_title'      => esc_html__( 'Add new (L3)', 'plugin-name' ),
                                        'group_title'       => esc_html__( 'Accordion Title', 'plugin-name' ),
                                        'limit'             => 50,
                                        'sortable'          => true,
                                    ),
                                    'fields'  => array(

                                        array(
                                            'id'     => 'group_1_group_1_group_1_editor_trumbowyg_1',
                                            'type'   => 'editor',
                                            'title'  => 'Editor Trumbowyg',
                                            'editor' => 'trumbowyg',
                                        ),

                                        array(
                                            'id'      => 'group_1_group_1_group_1_text_1',
                                            'type'    => 'text',
                                            'title'   => esc_html__( 'Text', 'plugin-name' ),
                                            'attributes' => array(
                                                // mark this field az title, on type this will change group item title
                                                'data-title' => 'title',
                                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
                                            ),
                                        ),


                                    ),

                                ),

                            ),

                        ),

                    ),
                ),

                array(
                    'type'    => 'group',
                    'id'      => 'group_2',
                    'title'   => esc_html__( 'Group field', 'plugin-name' ),
                    'options' => array(
                        'repeater'          => true,
                        'accordion'         => true,
                        'button_title'      => esc_html__( 'Add new', 'plugin-name' ),
                        'group_title'       => esc_html__( 'Accordion Title', 'plugin-name' ),
                        'limit'             => 50,
                        'sortable'          => true,
                    ),
                    'fields'  => array(

                        array(
                            'id'      => 'group_2_text_1',
                            'type'    => 'text',
                            'title'   => esc_html__( 'Text', 'plugin-name' ),
                            'attributes' => array(
                                // mark this field az title, on type this will change group item title
                                'data-title' => 'title',
                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
                            ),
                        ),

                        array(
                            'id'      => 'group_2_switcher_1',
                            'type'    => 'switcher',
                            'title'   => esc_html__( 'Switcher', 'plugin-name' ),
                            'default' => 'yes',
                        ),

                        array(
                            'id'             => 'group_2_select_1_emails_callback',
                            'type'           => 'select',
                            'title'          => esc_html__( 'Users Email (callback)', 'plugin-name' ),
                            'query'          => array(
                                'type'          => 'callback',
                                'function'      => array( $this, 'get_all_emails' ),
                                'args'          => array() // WordPress query args
                            ),
                            'attributes' => array(
                                'multiple' => 'multiple',
                                'style'    => 'width: 200px; height: 56px;',
                            ),
                            'class'       => 'chosen',
                        ),

                        array(
                            'id'          => 'group_2_tabbed_1',
                            'type'        => 'tab',
                            'title'       => esc_html__( 'Tab same with', 'plugin-name' ),
                            'options'     => array(
                                'equal_width' => true,
                            ),
                            'tabs'        => array(

                                array(
                                    'title'  => '<i class="fa fa-microchip" aria-hidden="true"></i> ' . esc_html__( 'Tab 1', 'plugin-name' ),
                                    'icon'   => 'fa fa-star',
                                    'fields' => array(

                                        array(
                                            'id'    => 'group_2_tab_1_text_1',
                                            'type'  => 'text',
                                            'title' => esc_html__( 'Tab 1 Text 1', 'plugin-name' ),
                                        ),


                                    ),
                                ),

                                array(
                                    'title'  => '<i class="fa fa-superpowers" aria-hidden="true"></i> ' . esc_html__( 'Tab 2', 'plugin-name' ),
                                    'fields' => array(

                                        array(
                                            'id'    => 'group_2_tab_2_text_text_1',
                                            'type'  => 'text',
                                            'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
                                        ),

                                        array(
                                            'id'    => 'group_2_tab_2_text_2',
                                            'type'  => 'text',
                                            'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
                                        ),

                                    ),

                                ),

                            ),

                        ),

                        array(
                            'id'     => 'group_2_editor_tinymce_1',
                            'type'   => 'editor',
                            'title'  => 'Editor TinyMCE',
                        ),

                        array(
                            'id'     => 'group_2_editor_trumbowyg_1',
                            'type'   => 'editor',
                            'title'  => 'Editor Trumbowyg',
                            'editor' => 'trumbowyg',
                        ),

                    ),

                ),

                array(
                    'type'    => 'group',
                    'id'      => 'group_3',
                    'title'   => esc_html__( 'Group field not an accordion', 'plugin-name' ),
                    'options' => array(
                        'repeater'          => true,
                        'accordion'         => true,
                        // 'accordion'         => false,
                        'button_title'      => esc_html__( 'Add new', 'plugin-name' ),
                        'group_title'       => esc_html__( 'Accordion Title', 'plugin-name' ),
                        'limit'             => 50,
                        'sortable'          => true,
                        'closed'            => false,
                    ),
                    'fields'  => array(

                        array(
                            'id'      => 'group_3_text_1',
                            'type'    => 'text',
                            'title'   => esc_html__( 'Text', 'plugin-name' ),
                            'attributes' => array(
                                // mark this field az title, on type this will change group item title
                                'data-title' => 'title',
                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
                            ),
                        ),

                        array(
                            'id'      => 'group_3_switcher_1',
                            'type'    => 'switcher',
                            'title'   => esc_html__( 'Switcher', 'plugin-name' ),
                            'default' => 'yes',
                        ),

                        array(
                            'id'          => 'group_3_tabbed_1',
                            'type'        => 'tab',
                            'title'       => esc_html__( 'Tab same with', 'plugin-name' ),
                            'options'     => array(
                                'equal_width' => true,
                            ),
                            'tabs'        => array(

                                array(
                                    'title'  => '<i class="fa fa-microchip" aria-hidden="true"></i> ' . esc_html__( 'Tab 1', 'plugin-name' ),
                                    'icon'   => 'fa fa-star',
                                    'fields' => array(

                                        array(
                                            'id'    => 'group_3_tab_1_text_1',
                                            'type'  => 'text',
                                            'title' => esc_html__( 'Tab 1 Text 1', 'plugin-name' ),
                                        ),


                                    ),
                                ),

                                array(
                                    'title'  => '<i class="fa fa-superpowers" aria-hidden="true"></i> ' . esc_html__( 'Tab 2', 'plugin-name' ),
                                    'fields' => array(

                                        array(
                                            'id'    => 'group_3_tab_2_text_2',
                                            'type'  => 'text',
                                            'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
                                        ),

                                        array(
                                            'id'    => 'group_3_tab_2_text_3',
                                            'type'  => 'text',
                                            'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
                                        ),

                                    ),

                                ),

                            ),

                        ),

                    ),

                ),

                array(
                    'type'    => 'group',
                    'id'      => 'group_4_sortable',
                    'title'   => esc_html__( 'Sortable, repetable field multiple', 'plugin-name' ),
                    'options' => array(
                        'repeater'          => true,
                        'accordion'         => true,
                        'button_title'      => esc_html__( 'Add new', 'plugin-name' ),
                        'group_title'       => esc_html__( 'Accordion Title', 'plugin-name' ),
                        'limit'             => 50,
                        'sortable'          => true,
                        'mode'              => 'compact',
                    ),
                    'fields'  => array(

                        array(
                            'id'      => 'group_4_sortable_text_1',
                            'type'    => 'text',
                            'attributes' => array(
                                // mark this field az title, on type this will change group item title
                                'data-title' => 'title',
                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
                            ),
                        ),

                        array(
                            'id'      => 'group_4_sortable_text_2',
                            'type'    => 'text',
                            'attributes' => array(
                                // mark this field az title, on type this will change group item title
                                'data-title' => 'title',
                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
                            ),
                        ),

                    ),
                ),

                array(
                    'type'    => 'group',
                    'id'      => 'group_5_sortable',
                    'title'   => esc_html__( 'Sortable (group) field single', 'plugin-name' ),
                    'options' => array(
                        'repeater'          => true,
                        'accordion'         => false,
                        'button_title'      => esc_html__( 'Add new', 'plugin-name' ),
                        'group_title'       => esc_html__( 'Accordion Title', 'plugin-name' ),
                        'limit'             => 50,
                        'sortable'          => true,
                        'mode'              => 'compact', // only repeater
                    ),
                    'fields'  => array(

                        array(
                            'id'      => 'group_5_sortable_text_1',
                            'type'    => 'text',
                            'attributes' => array(
                                // mark this field az title, on type this will change group item title
                                'data-title' => 'title',
                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
                            ),
                        ),


                    ),
                ),

            )
        );

        $fields[] = array(
            'name'   => 'image_selects',
            'title'  => 'Image select',
            'icon'   => 'fa fa-picture-o',
            'fields' => array(


                array(
                    'id'        => 'image_select_1',
                    'type'      => 'image_select',
                    'title'     => 'Image Select Radio',
                    'options'   => array(
                        'value-1' => 'https://dummyimage.com/100x80/2ecc70/fff.gif&text=100x80',
                        'value-2' => 'https://dummyimage.com/100x80/e74c3c/fff.gif&text=100x80',
                        'value-3' => 'https://dummyimage.com/100x80/ffbc00/fff.gif&text=100x80',
                        'value-4' => 'https://dummyimage.com/100x80/3498db/fff.gif&text=100x80',
                        'value-5' => 'https://dummyimage.com/100x80/555555/fff.gif&text=100x80',
                    ),
                    'radio'        => true,
                    'default'      => 'value-2',
                ),

                array(
                    'id'        => 'image_select_2',
                    'type'      => 'image_select',
                    'title'     => 'Image Select Checkbox',
                    'options'   => array(
                        'value-1' => 'https://dummyimage.com/100x80/2ecc70/fff.gif&text=100x80',
                        'value-2' => 'https://dummyimage.com/100x80/e74c3c/fff.gif&text=100x80',
                        'value-3' => 'https://dummyimage.com/100x80/ffbc00/fff.gif&text=100x80',
                        'value-4' => 'https://dummyimage.com/100x80/3498db/fff.gif&text=100x80',
                        'value-5' => 'https://dummyimage.com/100x80/555555/fff.gif&text=100x80',
                    ),
                    'default'      => 'value-3',
                    'description' => 'This is a longer description with <a href="#">link</a> to explain what this field for.<br><i>You can use any HTML here.</i>',
                ),

                array(
                    'id'          => 'image_select_3',
                    'type'        => 'image_select',
                    'title'       => 'Image Select Radio Vertical',
                    'options'     => array(
                        'value-1'   => 'https://dummyimage.com/450x70/2ecc70/fff.gif&text=450x70',
                        'value-2'   => 'https://dummyimage.com/450x70/e74c3c/fff.gif&text=450x70',
                        'value-3'   => 'https://dummyimage.com/450x70/ffbc00/fff.gif&text=450x70',
                        'value-4'   => 'https://dummyimage.com/450x70/3498db/fff.gif&text=450x70',
                        'value-5'   => 'https://dummyimage.com/450x70/555555/fff.gif&text=450x70',
                    ),
                    'default'     => 'value-4',
                    'layout'      => 'vertical',
                    'radio'       => true,
                    'description' => esc_html__( 'Vertical layot, could be used for e.g. header styles.', 'plugin-name' ),
                ),

            ),
        );

        $fields[] = array(
            'name'   => 'notices',
            'title'  => 'Notice',
            'icon'   => 'fa fa-exclamation-circle',
            'fields' => array(

                array(
                    'type'    => 'notice',
                    'class'   => 'info',
                    'content' => 'This is info notice field for your highlight sentence.',
                ),

                array(
                    'type'    => 'notice',
                    'class'   => 'primary',
                    'content' => 'This is info notice field for your highlight sentence.',
                ),

                array(
                    'type'    => 'notice',
                    'class'   => 'secondary',
                    'content' => 'This is info notice field for your highlight sentence.',
                ),

                array(
                    'type'    => 'notice',
                    'class'   => 'success',
                    'content' => 'This is info notice field for your highlight sentence.',
                ),

                array(
                    'type'    => 'notice',
                    'class'   => 'warning',
                    'content' => 'This is info notice field for your highlight sentence.',
                ),

                array(
                    'type'    => 'notice',
                    'class'   => 'danger',
                    'content' => 'This is info notice field for your highlight sentence.',
                ),

            ),
        );

        $fields[] = array(
            'name'   => 'numbers',
            'title'  => 'Number',
            'icon'   => 'fa fa-sliders',
            'fields' => array(

                array(
                    'id'      => 'number_1',
                    'type'    => 'number',
                    'title'   => 'Number',
                    'default' => '10',
                    // 'unit'    => '$',
                    'after'   => ' <i class="text-muted">$ (dollars)</i>',
                    'min'     => '2',
                    'max'     => '20',
                    'step'    => '2',
                ),

                array(
                    'id'      => 'range_1',
                    'type'    => 'range',
                    'title'   => 'range',
                    'default' => '10',
                    // 'unit'    => '$',
                    'after'   => ' <i class="text-muted">$ (dollars)</i>',
                    'min'     => '2',
                    'max'     => '20',
                ),


            )
        );

        $fields[] = array(
            'name'   => 'tab',
            'title'  => 'Tab',
            'icon'   => 'fa fa-folder',
            'fields' => array(

                array(
                    'id'          => 'tabbed_1',
                    'type'        => 'tab',
                    'title'       => esc_html__( 'Nested Tabs with same width', 'plugin-name' ),
                    'options'     => array(
                        'equal_width' => true,
                    ),
                    'tabs'        => array(

                        array(
                            'title'  => '<i class="fa fa-microchip" aria-hidden="true"></i> ' . esc_html__( 'Tab 1', 'plugin-name' ),
                            'icon'   => 'fa fa-star',
                            'fields' => array(


                                    array(
                                        'id'          => 'tabbed_1_tab1',
                                        'type'        => 'tab',
                                        'title'       => esc_html__( 'Tab same width', 'plugin-name' ),
                                        'options'     => array(
                                            'equal_width' => true,
                                        ),
                                        'tabs'        => array(

                                            array(
                                                'title'  => '<i class="fa fa-microchip" aria-hidden="true"></i> ' . esc_html__( 'Tab 1', 'plugin-name' ),
                                                'icon'   => 'fa fa-star',
                                                'fields' => array(

                                                    array(
                                                        'id'    => 'tabbed_1_tab_1_text_1',
                                                        'type'  => 'text',
                                                        'title' => esc_html__( 'Tab 1 Text 1', 'plugin-name' ),
                                                    ),

                                                ),
                                            ),

                                            array(
                                                'title'  => '<i class="fa fa-superpowers" aria-hidden="true"></i> ' . esc_html__( 'Tab 2', 'plugin-name' ),
                                                'fields' => array(

                                                    array(
                                                        'id'    => 'tabbed_1_tab_1_text_2',
                                                        'type'  => 'text',
                                                        'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
                                                    ),

                                                    array(
                                                        'id'    => 'tabbed_1_tab_1_text_3',
                                                        'type'  => 'text',
                                                        'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
                                                    ),

                                                ),

                                            ),

                                        ),

                                    ),


                            ),
                        ),

                        array(
                            'title'  => '<i class="fa fa-superpowers" aria-hidden="true"></i> ' . esc_html__( 'Tab 2', 'plugin-name' ),
                            'fields' => array(

                                array(
                                    'id'    => 'tabbed_1_tab_2_text_1',
                                    'type'  => 'text',
                                    'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
                                ),

                                array(
                                    'id'    => 'tabbed_1_tab_2_text_2',
                                    'type'  => 'text',
                                    'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
                                ),

                                array(
                                    'type'    => 'group',
                                    'id'      => 'tabbed_1_tab_2_group_1',
                                    'title'   => esc_html__( 'Group field', 'plugin-name' ),
                                    'options' => array(
                                        'repeater'          => true,
                                        'accordion'         => true,
                                        'button_title'      => esc_html__( 'Add new', 'plugin-name' ),
                                        'group_title'       => esc_html__( 'Accordion Title', 'plugin-name' ),
                                        'limit'             => 50,
                                        'sortable'          => true,
                                    ),
                                    'fields'  => array(

                                        array(
                                            'id'      => 'tabbed_1_tab_2_group_1_text_1',
                                            'type'    => 'text',
                                            'title'   => esc_html__( 'Text', 'plugin-name' ),
                                            'attributes' => array(
                                                // mark this field az title, on type this will change group item title
                                                'data-title' => 'title',
                                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
                                            ),
                                        ),

                                        array(
                                            'id'      => 'tabbed_1_tab_2_group_1_switcher_1',
                                            'type'    => 'switcher',
                                            'title'   => esc_html__( 'Switcher', 'plugin-name' ),
                                            'default' => 'yes',
                                        ),

                                    ),

                                ),

                            ),

                        ),

                    ),

                ),

                array(
                    'id'          => 'tabbed_2',
                    'type'        => 'tab',
                    'title'       => esc_html__( 'Tab same width', 'plugin-name' ),
                    'options'     => array(
                        'equal_width' => true,
                    ),
                    'tabs'        => array(

                        array(
                            'title'  => '<i class="fa fa-microchip" aria-hidden="true"></i> ' . esc_html__( 'Tab 1', 'plugin-name' ),
                            'icon'   => 'fa fa-star',
                            'fields' => array(

                                array(
                                    'id'    => 'tabbed_2_tab_1_text_1',
                                    'type'  => 'text',
                                    'title' => esc_html__( 'Tab 1 Text 1', 'plugin-name' ),
                                ),

                                array(
                                    'id'      => 'tabbed_2_tab_1_ace_editor_1',
                                    'type'    => 'ace_editor',
                                    'title'   => esc_html__( 'Tab 1 ACE Editor 1', 'plugin-name' ),
                                    'options' => array(
                                        'theme'                     => 'ace/theme/chrome',
                                        'mode'                      => 'ace/mode/javascript',
                                        'showGutter'                => true,
                                        'showPrintMargin'           => true,
                                        'enableBasicAutocompletion' => true,
                                        'enableSnippets'            => true,
                                        'enableLiveAutocompletion'  => true,
                                    ),
                                    'attributes'    => array(
                                        'style'        => 'height: 300px; max-width: 700px;',
                                    ),
                                ),

                            ),
                        ),

                        array(
                            'title'  => '<i class="fa fa-superpowers" aria-hidden="true"></i> ' . esc_html__( 'Tab 2', 'plugin-name' ),
                            'fields' => array(

                                array(
                                    'id'    => 'tabbed_2_tab_1_text_2',
                                    'type'  => 'text',
                                    'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
                                ),

                                array(
                                    'id'    => 'tabbed_2_tab_1_text_3',
                                    'type'  => 'text',
                                    'title' => esc_html__( 'Tab 2 Text 2', 'plugin-name' ),
                                ),

                            ),

                        ),

                    ),

                ),

                array(
                    'id'          => 'tabbed_3',
                    'type'        => 'tab',
                    'title'       => esc_html__( 'Tab', 'plugin-name' ),
                    'options'     => array(
                        'equal_width' => false,
                    ),
                    'tabs'        => array(

                        array(
                            'title'  => esc_html__( 'Tab 3', 'plugin-name' ),
                            'icon'   => 'fa fa-star',
                            'fields' => array(

                                array(
                                    'id'    => 'tabbed_3_tab_1_text_1',
                                    'type'  => 'text',
                                    'title' => esc_html__( 'Tab 3 Text 1', 'plugin-name' ),
                                ),

                                array(
                                    'id'      => 'tabbed_3_tab_1_ace_editor_1',
                                    'type'    => 'ace_editor',
                                    'title'   => esc_html__( 'Tab 3 ACE Editor 1', 'plugin-name' ),
                                    'options' => array(
                                        'theme'                     => 'ace/theme/chrome',
                                        'mode'                      => 'ace/mode/javascript',
                                        'showGutter'                => true,
                                        'showPrintMargin'           => true,
                                        'enableBasicAutocompletion' => true,
                                        'enableSnippets'            => true,
                                        'enableLiveAutocompletion'  => true,
                                    ),
                                    'attributes'    => array(
                                        'style'        => 'height: 300px; max-width: 700px;',
                                    ),
                                ),

                            ),
                        ),

                        array(
                            'title'  => esc_html__( 'Tab 4', 'plugin-name' ),
                            'fields' => array(

                                array(
                                    'id'    => 'tabbed_3_tab_1_text_2',
                                    'type'  => 'text',
                                    'title' => esc_html__( 'Tab 4 Text 1', 'plugin-name' ),
                                ),

                                array(
                                    'id'    => 'tabbed_3_tab_1_text_3',
                                    'type'  => 'text',
                                    'title' => esc_html__( 'Tab 4 Text 2', 'plugin-name' ),
                                ),

                            ),

                        ),

                    ),

                ),


            )
        );

       $fields[] = array(
            'name'   => 'tap_lists',
            'title'  => 'Tap List',
            'icon'   => 'fa fa-hand-pointer-o',
            'fields' => array(


                array(
                    'id'        => 'tap_list_1',
                    'type'      => 'tap_list',
                    'title'     => 'Tap list Radio',
                    'options'   => array(
                        'value-1' => 'First',
                        'value-2' => 'Second',
                        'value-3' => 'Third',
                        'value-4' => 'Forth',
                        'value-5' => 'Fifth',
                    ),
                    'radio'        => true,
                    'default'      => 'value-3',
                ),

                array(
                    'id'        => 'tap_list_2',
                    'type'      => 'tap_list',
                    'title'     => 'Tap list Checkbox',
                    'options'   => array(
                        'value-1' => 'First',
                        'value-2' => 'Second',
                        'value-3' => 'Third',
                        'value-4' => 'Forth',
                        'value-5' => 'Fifth',
                    ),
                    'default'      => array(
                        'value-2',
                        'value-3'
                    ),
                ),


            )
        );

        $fields[] = array(
            'name'   => 'typography_section',
            'title'  => 'Typography',
            'icon'   => 'fa fa-font',
            'fields' => array(

                array(
                    'id'      => 'typography_1',
                    'type'    => 'typography',
                    'title'   => esc_html__( 'Typography', 'exopite-combiner-minifier' ),
                    'default' => array(
                        'family'    =>'Arial Black',
                        'variant'   =>'600',
                        'size'      => 16,
                        'height'    => 24,
                        'color'     => '#000000',
                    ),
                    'preview' => true,
                ),


            )
        );

        $fields[] = array(
            'name'   => 'selects',
            'title'  => 'Select',
            'icon'   => 'fa fa-list-ul',
            'fields' => array(

                array(
                    'id'             => 'select_1',
                    'type'           => 'select',
                    'title'          => 'Select',
                    'options'        => array(
                        'bmw'          => 'BMW',
                        'mercedes'     => 'Mercedes',
                        'volkswagen'   => 'Volkswagen',
                        'other'        => 'Other',
                    ),
                    'default_option' => 'Select your favorite car',
                    'default'     => 'bmw',
                ),

                array(
                    'id'             => 'select_2',
                    'type'           => 'select',
                    'title'          => 'Select Chosen',
                    'options'        => array(
                        'bmw'          => 'BMW',
                        'mercedes'     => 'Mercedes',
                        'volkswagen'   => 'Volkswagen',
                        'other'        => 'Other',
                    ),
                    'default_option' => 'Select your favorite car',
                    'default'     => 'bmw',
                    'class'       => 'chosen',
                    'prepend' => 'dashicons-arrow-down-alt',
                ),

                array(
                    'id'             => 'select_3',
                    'type'           => 'select',
                    'title'          => 'Select Chosen',
                    'options'        => array(
                        'bmw'          => 'BMW',
                        'mercedes'     => 'Mercedes',
                        'volkswagen'   => 'Volkswagen',
                        'other'        => 'Other',
                    ),
                    'default_option' => 'Select your favorite car',
                    'default'     => 'bmw',
                    'class'       => 'chosen',
                    'append' => 'dashicons-admin-tools',
                ),

                array(
                    'id'             => 'select_4',
                    'type'           => 'select',
                    'title'          => 'Miltiselect',
                    'options'        => array(
                        'bmw'          => 'BMW',
                        'mercedes'     => 'Mercedes',
                        'volkswagen'   => 'Volkswagen',
                        'other'        => 'Other',
                    ),
                    'default_option' => 'Select your favorite car',
                    'default'     => 'bmw',
                    'attributes' => array(
                        'multiple' => 'multiple',
                        'style'    => 'width: 200px; height: 125px;',
                    ),
                    'class'       => 'chosen',
                ),

                array(
                    'id'             => 'select_5',
                    'type'           => 'select',
                    'title'          => 'Select Chosen Posts',
                    // 'options'        => 'posts',
                    'query'          => array(
                        'type'           => 'posts',
                        'args'           => array(
                            'orderby'      => 'post_date',
                            'order'        => 'DESC',
                        ),
                    ),
                    'default_option' => '',
                    'class'       => 'chosen',
                ),


                array(
                    'id'             => 'select_6',
                    'type'           => 'select',
                    'title'          => 'Select Chosen Pages',
                    // 'options'        => 'pages',
                    'query'          => array(
                        'type'           => 'pages',
                        'args'           => array(
                            'orderby'      => 'post_date',
                            'order'        => 'DESC',
                        ),
                    ),
                    'default_option' => '',
                    'class'       => 'chosen',
                ),

                /**
                 * Options via callback function,
                * options settings will be ignored
                */
                array(
                    'id'             => 'select_7',
                    'type'           => 'select',
                    'title'          => 'Title',
                    'query'          => array(
                        'type'          => 'callback',
                        'function'      => array( $this, 'get_all_emails' ),
                        'args'          => array() // WordPress query args
                    ),
                ),


            )
        );

        $fields[] = array(
            'name'   => 'video',
            'title'  => 'Video',
            'icon'   => 'fa fa-youtube-play',
            'fields' => array(

                array(
                    'id'            => 'video_1',
                    'type'          => 'video',
                    'title'         => 'Video oEmbed',
                    // 'default'       => '/wp-content/uploads/2018/01/video.mp4',
                    // - OR for oEmbed: -
                    'default'       => 'https://www.youtube.com/watch?v=KujZ__rrs0k',
                    'info'          => 'oEmbed',
                    'attributes'    => array(
                        'placeholder'   => 'oEmbed',
                    ),
                    'options'       => array(
                        'input'         => false,
                        'oembed'        => true,
                    ),
                ),

                array(
                    'id'            => 'video_2',
                    'type'          => 'video',
                    'title'         => 'Video oEmbed',
                    // 'default'       => '/wp-content/uploads/2018/01/video.mp4',
                    // - OR for oEmbed: -
                    'default'       => 'https://www.youtube.com/watch?v=KujZ__rrs0k',
                    'info'          => 'oEmbed',
                    'attributes'    => array(
                        'placeholder'   => 'oEmbed',
                    ),
                    'options'       => array(
                        'input'         => true,
                        'oembed'        => true,
                    ),
                ),


            )
        );

        $fields[] = array(
            'name'   => 'upload',
            'title'  => 'Upload',
            'icon'   => 'fa fa-upload',
            'fields' => array(


                array(
                    'id'      => 'upload_1',
                    'type'    => 'upload',
                    'title'   => 'Upload',
                    'options' => array(
                        'attach'                    => true, // attach to post (only in metabox)
                        'filecount'                 => '101',
                        // 'allowed'                   => array( 'png', 'jpeg' ),
                        // 'delete-enabled'            => false,
                        // 'delete-force-confirm'      => true,
                        // 'retry-enable-auto'         => true,
                        // 'retry-max-auto-attempts'   => 3,
                        // 'retry-auto-attempt-delay'  => 3,
                        // 'auto-upload'               => false,
                    ),
                ),


            )
        );

        /**
         * instantiate your admin page
         */
        $options_panel = new Exopite_Simple_Options_Framework( $config_submenu, $fields );
        $options_panel = new Exopite_Simple_Options_Framework( $config_metabox, $fields );

    }

    /**
     * Add new image size for admin thumbnail.
     *
     * @link https://wordpress.stackexchange.com/questions/54423/add-image-size-in-a-plugin-i-created/304941#304941
     */
    public function add_thumbnail_size() {
        add_image_size( 'new_thumbnail_size', 60, 75, true );
    }

    /**************************************************************
     * ADD/REMOVE/REORDER/SORT CUSTOM POST TYPE LIST COLUMNS (test)
     *
     * @tutorial add_remove_reorder_sort_custom_post_type_list_columns_in_admin_area.php
     */
    /**
     * Modify columns in tests list in admin area.
     */
    public function manage_test_posts_columns( $columns ) {

        // Remove unnecessary columns
        unset(
            $columns['author'],
            $columns['comments']
        );

        // Rename title and add ID and Address
        $columns['thumbnail'] = '';
        $columns['text_1'] = esc_attr__( 'Text', 'plugin-name' );
        $columns['color_2'] = esc_attr__( 'Color', 'plugin-name' );
        $columns['date_2'] = esc_attr__( 'Date', 'plugin-name' );


        /**
         * Rearrange column order
         *
         * Now define a new order. you need to look up the column
         * names in the HTML of the admin interface HTML of the table header.
         *
         *     "cb" is the "select all" checkbox.
         *     "title" is the title column.
         *     "date" is the date column.
         *     "icl_translations" comes from a plugin (eg.: WPML).
         *
         * change the order of the names to change the order of the columns.
         *
         * @link http://wordpress.stackexchange.com/questions/8427/change-order-of-custom-columns-for-edit-panels
         */
        $customOrder = array('cb', 'thumbnail', 'title', 'text_1', 'color_2', 'date_2', 'icl_translations', 'date');

        /**
         * return a new column array to wordpress.
         * order is the exactly like you set in $customOrder.
         */
        foreach ($customOrder as $column_name)
            $rearranged[$column_name] = $columns[$column_name];

        return $rearranged;

    }

    // Populate new columns in customers list in admin area
    public function manage_posts_custom_column( $column, $post_id ) {

        // For array, not simple options
        // global $post;
        // $custom = get_post_custom();
        // $meta = maybe_unserialize( $custom[$this->plugin_name][0] );

        // Populate column form meta
        switch ($column) {

            case "thumbnail":
                echo '<a href="' . get_edit_post_link() . '">';
                echo get_the_post_thumbnail( $post_id, array( 60, 60 ) );
                echo '</a>';
                break;
            case "text_1":
                // no break;
            case "color_2":
                // no break;
            case "date_2":
                echo get_post_meta( $post_id, $column, true );
                break;
            // case "some_column":
            //     // For array, not simple options
            //     echo $meta["some_column"];
            //     break;

        }

    }

    public function add_style_to_admin_head() {
        global $post_type;
        if ( 'test' == $post_type ) {
            ?>
                <style type="text/css">
                    .column-thumbnail {
                        width: 80px !important;
                    }
                    .column-title {
                        width: 30% !important;
                    }
                </style>
            <?php
        }
    }

    /**
     * To sort, Exopite Simple Options Framework need 'options' => 'simple'.
     * Simple options is stored az induvidual meta key, value pair, otherwise it is stored in an array.
     *
     *
     * Meta key value paars need to sort as induvidual.
     *
     * I implemented this option because it is possible to search in serialized (array) post meta:
     * @link https://wordpress.stackexchange.com/questions/16709/meta-query-with-meta-values-as-serialize-arrays
     * @link https://stackoverflow.com/questions/15056407/wordpress-search-serialized-meta-data-with-custom-query
     * @link https://www.simonbattersby.com/blog/2013/03/querying-wordpress-serialized-custom-post-data/
     *
     * but there is no way to sort them with wp_query or SQL.
     * @link https://wordpress.stackexchange.com/questions/87265/order-by-meta-value-serialized-array/87268#87268
     * "Not in any reliable way. You can certainly ORDER BY that value but the sorting will use the whole serialized string,
     * which will give * you technically accurate results but not the results you want. You can't extract part of the string
     * for sorting within the query itself. Even if you wrote raw SQL, which would give you access to database functions like
     * SUBSTRING, I can't think of a dependable way to do it. You'd need a MySQL function that would unserialize the value--
     * you'd have to write it yourself.
     * Basically, if you need to sort on a meta_value you can't store it serialized. Sorry."
     *
     * It is possible to get all required posts and store them in an array and then sort them as an array,
     * but what if you want multiple keys/value pair to be sorted?
     *
     * UPDATE
     * it is maybe possible:
     * @link http://www.russellengland.com/2012/07/how-to-unserialize-data-using-mysql.html
     * but it is waaay more complicated and less documented as meta query sort and search.
     * It should be not an excuse to use it, but it is not as reliable as it should be.
     *
     * @link https://wpquestions.com/Order_by_meta_key_where_value_is_serialized/7908
     * "...meta info serialized is not a good idea. But you really are going to lose the ability to query your
     * data in any efficient manner when serializing entries into the WP database.
     *
     * The overall performance saving and gain you think you are achieving by serialization is not going to be noticeable to
     * any major extent. You might obtain a slightly smaller database size but the cost of SQL transactions is going to be
     * heavy if you ever query those fields and try to compare them in any useful, meaningful manner.
     *
     * Instead, save serialization for data that you do not intend to query in that nature, but instead would only access in
     * a passive fashion by the direct WP API call get_post_meta() - from that function you can unpack a serialized entry
     * to access its array properties too."
     */
    public function manage_sortable_columns( $columns ) {

        $columns['text_1'] = 'text_1';
        $columns['color_2'] = 'color_2';
        $columns['date_2'] = 'date_2';

        return $columns;

    }

    public function manage_posts_orderby( $query ) {

        if( ! is_admin() || ! $query->is_main_query() ) {
            return;
        }

        /**
         * meta_types:
         * Possible values are 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 'TIME', 'UNSIGNED'.
         * Default value is 'CHAR'.
         *
         * @link https://codex.wordpress.org/Class_Reference/WP_Meta_Query
         */
        $columns = array(
            'text_1'  => 'char',
            'color_2' => 'char',
            'date_2'  => 'date',
        );

        foreach ( $columns as $key => $type ) {

            if ( $key === $query->get( 'orderby') ) {
                $query->set( 'orderby', 'meta_value' );
                $query->set( 'meta_key', $key );
                $query->set( 'meta_type', $type );
                break;
            }

        }

    }
    // END ADD/REMOVE/REORDER/SORT CUSTOM POST TYPE LIST COLUMNS (test)

    /********************************************
     * RUN CODE ON PLUGIN UPGRADE AND ADMIN NOTICE
     *
     * @tutorial run_code_on_plugin_upgrade_and_admin_notice.php
     */
    /**
     * This function runs when WordPress completes its upgrade process
     * It iterates through each plugin updated to see if ours is included
     *
     * @param $upgrader_object Array
     * @param $options Array
     * @link https://catapultthemes.com/wordpress-plugin-update-hook-upgrader_process_complete/
     */
    public function upgrader_process_complete( $upgrader_object, $options ) {

        // If an update has taken place and the updated type is plugins and the plugins element exists
        if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {

            // Iterate through the plugins being updated and check if ours is there
            foreach( $options['plugins'] as $plugin ) {
                if( $plugin == PLUGIN_NAME_BASE_NAME ) {

                    // Set a transient to record that our plugin has just been updated
                    set_transient( 'exopite_sof_updated', 1 );
                    set_transient( 'exopite_sof_updated_message', esc_html__( 'Thanks for updating', 'exopite_sof' ) );

                }
            }
        }
    }

    /**
     * Show a notice to anyone who has just updated this plugin
     * This notice shouldn't display to anyone who has just installed the plugin for the first time
     */
    public function display_update_notice() {
        // Check the transient to see if we've just activated the plugin
        if( get_transient( 'exopite_sof_updated' ) ) {

            // @link https://digwp.com/2016/05/wordpress-admin-notices/
            echo '<div class="notice notice-success is-dismissible"><p><strong>' . get_transient( 'exopite_sof_updated_message' ) . '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';

            // Delete the transient so we don't keep displaying the activation message
            delete_transient( 'exopite_sof_updated' );
            delete_transient( 'exopite_sof_updated_message' );
        }
    }
    // RUN CODE ON PLUGIN UPGRADE AND ADMIN NOTICE
}
