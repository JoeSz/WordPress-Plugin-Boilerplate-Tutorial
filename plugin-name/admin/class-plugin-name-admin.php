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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

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


    public function create_menu() {

        /*
         * Create a submenu page under Plugins.
         * Framework also add "Settings" to your plugin in plugins list.
         */
        $config_submenu = array(

            'type'              => 'menu',                          // Required, menu or metabox
            'id'                => $this->plugin_name . '-test',    // Required, meta box id, unique per page, to save: get_option( id )
            'menu'              => 'plugins.php',                   // Required, sub page to your options page
            'submenu'           => true,                            // Required for submenu
            'title'             => 'Demo Admin Page',               //The name of this page
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
            'id'                => $this->plugin_name . '-meta',    // Required, meta box id, unique, for saving meta: id[field-id]
            'post_types'        => array( 'page' ),                 // Post types to display meta box
            // 'post_types'        => array( 'post', 'page' ),         // Post types to display meta box
            'context'           => 'advanced',
            'priority'          => 'default',
            'title'             => 'Demo Metabox',                  // The name of this page
            'capability'        => 'edit_posts',                    // The capability needed to view the page
            'tabbed'            => true,

        );

        $fields[] = array(
            'name'   => 'first',
            'title'  => 'First',
            'icon'   => 'dashicons-admin-generic',
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

                array(
                    'id'            => 'video_1',
                    'type'          => 'video',
                    'title'         => 'Video oEmbed',
                    'options'       => array(
                        // 'input'         => false,
                        // 'oembed'        => true,
                    ),
                    'default'       => '/wp-content/uploads/2018/01/video.mp4',
                    // 'default'       => 'https://www.youtube.com/watch?v=KujZ__rrs0k',
                    // 'info'          => 'oEmbed',
                    // 'attributes'    => array(
                    //     'placeholder'   => 'oEmbed',
                    // ),
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
                    'id'     => 'color_1',
                    'type'   => 'color',
                    'title'  => 'Color',
                    'rgba'   => true,
                ),

                array(
                    'id'     => 'color_2',
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
                  'id'      => 'button_bar_1',
                  'type'    => 'botton_bar',
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
                    'id'             => 'select_4',
                    'type'           => 'select',
                    'title'          => 'Select Chosen Posts',
                    'options'        => 'posts',
                    'query_args'     => array(
                        'orderby'      => 'post_date',
                        'order'        => 'DESC',
                    ),
                    'default_option' => '',
                    'class'       => 'chosen',
                ),

                array(
                    'id'             => 'select_4',
                    'type'           => 'select',
                    'title'          => 'Select Chosen Pages',
                    'options'        => 'pages',
                    'query_args'     => array(
                        'orderby'      => 'post_date',
                        'order'        => 'DESC',
                    ),
                    'default_option' => '',
                    'class'       => 'chosen',
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
                ),

                array(
                    'id'             => 'select_2',
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
                    'id'             => 'select_2',
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
                    'title'  => 'Editor',
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

            )
        );

        $fields[] = array(
            'name'   => 'second',
            'title'  => 'Second',
            'icon'   => 'dashicons-portfolio',
            'fields' => array(

                array(
                    'type'    => 'content_second',
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
                    'type'    => 'content_third',
                    'content' => 'Third Section',

                ),


            )
        );

        /**
         * instantiate your admin page
         */
        $options_panel = new Exopite_Simple_Options_Framework( $config_submenu, $fields );
        $options_panel = new Exopite_Simple_Options_Framework( $config_metabox, $fields );

    }

}
