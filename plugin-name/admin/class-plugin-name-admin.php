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

        $fields[] = array(
            'name'   => 'first',
            'title'  => 'First',
            'icon'   => 'dashicons-admin-generic',
            'fields' => array(

                /**
                 * Available fields:
                 * - ACE field
                 * - attached
                 * - backup
                 * - button
                 * - button_bar
                 * - card
                 * - checkbox
                 * - color
                 * - content
                 * - date
                 * - editor
                 * - group
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
                 * - tap_list
                 * - text
                 * - textarea
                 * - upload
                 * - video mp4/oembed
                 *
                 * Add your fields, eg.:
                 */

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

                array(
                    'id'      => 'attached_1',
                    'type'    => 'attached',
                    'title'   => 'Attached',
                    'options' => array(
                        'type' => '', // attach to post (only in metabox)
                    ),
                ),


                array(
                    'type'    => 'notice',
                    'class'   => 'success',
                    'content' => 'This is info notice field for your highlight sentence.',
                ),

                array(
                    'id'          => 'text_1',
                    'type'        => 'text',
                    'title'       => 'Text',
                    'before'      => 'Text Before',
                    'after'       => 'Text After',
                    'class'       => 'text-class',
                    'attributes'  => 'data-test="test"',
                    'description' => 'Description',
                    'default'     => 'Default Text',
                    'attributes'    => array(
                       'rows'        => 10,
                       'cols'        => 5,
                       'placeholder' => 'do stuff',
                    ),
                    'help'        => 'Help text',

                ),

                array(
                    'id'     => 'password_1',
                    'type'   => 'password',
                    'title'  => 'Password',
                ),


                array(
                    'id'     => 'color_1',
                    'type'   => 'color',
                    'title'  => 'Color',
                ),

                array(
                    'id'     => 'color_2',
                    'type'   => 'color',
                    'title'  => 'Color',
                    'rgba'   => true,
                ),

                array(
                    'id'     => 'color_3',
                    'type'   => 'color',
                    'title'  => 'Color',
                    'picker' => 'html5',
                ),

                array(
                    'id'    => 'image_1',
                    'type'  => 'image',
                    'title' => 'Image',
                ),

                array(
                    'id'          => 'textarea_1',
                    'type'        => 'textarea',
                    'title'       => 'Textarea',
                    'help'        => 'This option field is useful. &quot;You&quot; will love it! This option field is useful. You will love it!',
                    'attributes'    => array(
                        'placeholder' => 'do stuff',
                    ),
                ),

                array(
                    'id'      => 'switcher_1',
                    'type'    => 'switcher',
                    'title'   => 'Switcher',
                    'label'   => 'You want to do this?',
                    'default' => 'yes',
                ),

                array(
                    'id'     => 'date_2',
                    'type'   => 'date',
                    'title'  => 'Date ISO',
                    'format' => 'yy-mm-dd',
                    'class'  => 'datepic-class',
                ),

                array(
                    'id'         => 'date_3',
                    'type'       => 'date',
                    'title'      => 'Date DE',
                    'format'     => 'dd.mm.yy',
                    'class'      => 'datepic-class',
                    'wrap_class' => 'wrap_class',
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
                    'id'     => 'text_2',
                    'type'   => 'text',
                    'title'  => 'Text Test Dependency',
                    'dependency' => array( 'checkbox_1|checkbox_2', '==|==', 'true|true' ),
                    'attributes'    => array(
                        'placeholder' => 'Dependency test',
                    ),
                ),


                array(
                    'id'     => 'date_1',
                    'type'   => 'date',
                    'title'  => 'Date',
                    'format' => 'yy-mm-dd',
                    'class'  => 'datepic-class',
                    'picker' => 'html5',
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
                    'id'    => 'checkbox_2',
                    'type'  => 'checkbox',
                    'title' => 'Checkbox Fancy',
                    'label' => 'Do you want to do this?',
                    'style'    => 'fancy',
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
                ),

                array(
                    'id'             => 'select_3',
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
                    'id'             => 'select_4',
                    'type'           => 'select',
                    'title'          => 'Select Chosen Posts',
                    'options'        => 'posts',
                    'query'          => array(
                        'args'           => array(
                            'orderby'      => 'post_date',
                            'order'        => 'DESC',
                        ),
                    ),
                    'default_option' => '',
                    'class'       => 'chosen',
                ),


                array(
                    'id'             => 'select_5',
                    'type'           => 'select',
                    'title'          => 'Select Chosen Pages',
                    'options'        => 'pages',
                    'query'          => array(
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
                    'id'             => 'select_6',
                    'type'           => 'select',
                    'title'          => 'Title',
                    'query'          => array(
                        'type'          => 'callback',
                        'function'      => array( $this, 'get_all_emails' ),
                        'args'          => array() // WordPress query args
                    ),
                ),



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


                array(
                    'id'      => 'test_unknown_1',
                    'type'    => 'test_unknown_type',
                    'title'   => 'Test Unknown Element',
                ),

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
                    'default'      => 'value-5',
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
                    'default'      => 'value-5',
                ),

                 array(
                    'type'    => 'group',
                    'id'      => 'group_1',
                    'title'   => esc_html__( 'Gruop field', 'plugin-name' ),
                    'options' => array(
                        'repeater'          => true,
                        'accordion'         => true,
                        'button_title'      => 'Add new',
                        'accordion_title'   => esc_html__( 'Accordion Title', 'plugin-name' ),
                        'limit'             => 50,
                        'sortable'          => true,
                    ),
                    'fields'  => array(

                        array(
                            'id'      => 'text_group',
                            'type'    => 'text',
                            'title'   => esc_html__( 'Text', 'plugin-name' ),
                            'attributes' => array(
                                // mark this field az title, on type this will change group item title
                                'data-title' => 'title',
                                'placeholder' => esc_html__( 'Some text', 'plugin-name' ),
                            ),
                        ),

                        array(
                            'id'      => 'switcher_group',
                            'type'    => 'switcher',
                            'title'   => esc_html__( 'Switcher', 'plugin-name' ),
                            'default' => 'yes',
                        ),

                        array(
                            'id'             => 'emails_group_callback',
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
                            'id'      => 'textarea_group',
                            'type'    => 'textarea',
                            'class'   => 'some-class',
                            'title'   => esc_html__( 'Textarea', 'plugin-name' ),
                            'default' => esc_html__( 'Some text', 'plugin-name' ),
                            'after'   => '<mute>' . esc_html__( 'Some info: ', 'plugin-name' ) . '</mute>',
                        ),

                        array(
                            'id'     => 'editor_tinymce_group',
                            'type'   => 'editor',
                            'title'  => 'Editor TinyMCE',
                        ),

                        array(
                            'id'     => 'editor_trumbowyg_group',
                            'type'   => 'editor',
                            'title'  => 'Editor Trumbowyg',
                            'editor' => 'trumbowyg',
                        ),

                    ),

                ),

                array(
                    'type'    => 'backup',
                    'title'   => esc_html__( 'Backup', 'exopite-seo-core' ),
                ),

            )
        );

        $fields[] = array(
            'name'   => 'second',
            'title'  => 'Second',
            'icon'   => 'dashicons-portfolio',
            'fields' => array(

                array(
                    'type'    => 'content',
                    'content' => 'Second Section',

                ),


            )
        );

        $fields[] = array(
            'name'   => 'third',
            'title'  => 'Third',
            'icon'   => 'dashicons-portfolio',
            'fields' => array(

                array(
                    'type'    => 'content',
                    'content' => 'Third Section',

                ),

            ),
        );

        /**
         * instantiate your admin page
         */
        $options_panel = new Exopite_Simple_Options_Framework( $config_submenu, $fields );
        $options_panel = new Exopite_Simple_Options_Framework( $config_metabox, $fields );

    }

    // Modify columns in customers list in admin area
    public function admin_list_edit_columns( $columns ) {

        // Remove unnecessary columns
        // unset(
        //     $columns['author'],
        //     $columns['comments']
        // );

        // Rename title and add ID and Address
        $columns['text_1'] = __( 'Text', 'plugin-name' );
        $columns['color_2'] = __( 'Color', 'plugin-name' );
        $columns['date_2'] = __( 'Date', 'plugin-name' );


        /*
         * Rearrange column order
         *
         * Now define a new order. you need to look up the column
         * names in the HTML of the admin interface HTML of the table header.
         *
         *     "cb" is the "select all" checkbox.
         *     "title" is the title column.
         *     "date" is the date column.
         *     "icl_translations" comes from a plugin (in this case, WPML).
         *
         * change the order of the names to change the order of the columns.
         *
         * @link http://wordpress.stackexchange.com/questions/8427/change-order-of-custom-columns-for-edit-panels
         */
        $customOrder = array('cb', 'title', 'text_1', 'color_2', 'date_2', 'author', 'comments', 'icl_translations', 'date');

        /*
         * return a new column array to wordpress.
         * order is the exactly like you set in $customOrder.
         */
        foreach ($customOrder as $column_name)
            $rearranged[$column_name] = $columns[$column_name];

        return $rearranged;

    }

    // Populate new columns in customers list in admin area
    public function admin_list_custom_columns( $column ) {

        /*
        'user_login' => 'js@markatus.de',
        'arbeitszeiterfassung' => 'no',
        'soll_stunden' => '',
        'anstellung' => 'vollzeit',
        'festlegung_zeitraum' => 'abreitstage_pro_woche',
        'verwaltung_jahresurlaub' => 'ja',
        'code_fuer_erfassungsbestaetigung' => '',
        'team' => 'markatus',
         */

        global $post;
        $custom = get_post_custom();
        $meta = maybe_unserialize( $custom[$this->plugin_name . '-meta'][0] );
        // Populate column form meta
        switch ($column) {

            case "text_1":
                // echo var_export( $meta, true );
                echo $meta["text_1"];
                break;
            case "color_2":
                echo $meta["color_2"];
                break;
            case "date_2":
                echo $meta["date_2"];
                break;

        }

    }
}
